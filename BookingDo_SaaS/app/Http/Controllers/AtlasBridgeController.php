<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\CustomStatus;
use App\Models\LandingSettings;
use App\Models\OtherSettings;
use App\Models\Payment;
use App\Models\Settings;
use App\Models\StoreCategory;
use App\Models\TelegramMessage;
use App\Models\Timing;
use App\Models\User;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AtlasBridgeController extends Controller
{
    protected $atlasLinkTable = 'atlas_workspace_links';

    public function provision(Request $request)
    {
        try {
            [$payload, $errorResponse] = $this->validatedPayload($request);
            if ($errorResponse) {
                return $errorResponse;
            }

            $this->ensureAtlasLinkTable();
            $vendor = $this->findLinkedVendor($payload);
            if (!$vendor) {
                $vendor = $this->createVendorWorkspace($payload);
            }

            if (!$vendor) {
                return response()->json([
                    'success' => false,
                    'error' => 'BookingDo workspace not found.',
                ], 500);
            }

            $this->syncVendor($vendor, $payload);
            $this->saveAtlasLink($payload, $vendor);

            return response()->json([
                'success' => true,
                'platform_user_id' => (int) $vendor->id,
                'platform_vendor_id' => (int) $vendor->id,
                'platform_slug' => (string) $vendor->slug,
                'platform_email' => (string) $vendor->email,
                'platform_status' => 'active',
                'dashboard_url' => url('/admin/dashboard'),
                'public_url' => url('/' . ltrim((string) $vendor->slug, '/')),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function launch(Request $request)
    {
        [$payload, $errorResponse] = $this->validatedPayload($request);
        if ($errorResponse) {
            abort($errorResponse->getStatusCode(), $errorResponse->getData(true)['error']);
        }

        $this->ensureAtlasLinkTable();
        $vendor = $this->findLinkedVendor($payload);
        if (!$vendor) {
            $vendor = $this->createVendorWorkspace($payload);
        }

        if (!$vendor) {
            abort(500, 'BookingDo workspace not found.');
        }

        $this->syncVendor($vendor, $payload);
        $this->saveAtlasLink($payload, $vendor);
        return redirect('/?source=atlas&atlas_email=' . urlencode((string) $vendor->email));
    }

    protected function validatedPayload(Request $request): array
    {
        $payloadEncoded = (string) ($request->input('payload', $request->query('payload', '')));
        $signature = (string) ($request->input('sig', $request->query('sig', '')));
        $sharedSecret = (string) env('WEBSITE_PLATFORM_SHARED_SECRET', env('APP_KEY', 'atlas-website-secret'));

        if ($payloadEncoded === '' || $signature === '') {
            return [null, response()->json(['success' => false, 'error' => 'Missing launch signature.'], 403)];
        }

        $expectedSignature = hash_hmac('sha256', $payloadEncoded, $sharedSecret);
        if (!hash_equals($expectedSignature, $signature)) {
            return [null, response()->json(['success' => false, 'error' => 'Invalid launch signature.'], 403)];
        }

        $payloadJson = base64_decode(strtr($payloadEncoded, '-_', '+/'));
        $payload = json_decode((string) $payloadJson, true);
        if (!is_array($payload) || empty($payload['email']) || empty($payload['atlas_user_id'])) {
            return [null, response()->json(['success' => false, 'error' => 'Invalid launch payload.'], 403)];
        }

        if (!empty($payload['exp']) && (int) $payload['exp'] < time()) {
            return [null, response()->json(['success' => false, 'error' => 'Launch link expired.'], 403)];
        }

        return [$payload, null];
    }

    protected function ensureAtlasLinkTable(): void
    {
        DB::statement(
            "CREATE TABLE IF NOT EXISTS `{$this->atlasLinkTable}` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `atlas_user_id` INT UNSIGNED NOT NULL,
                `vendor_user_id` INT UNSIGNED NOT NULL,
                `email` VARCHAR(255) NULL,
                `slug` VARCHAR(191) NULL,
                `created_at` TIMESTAMP NULL DEFAULT NULL,
                `updated_at` TIMESTAMP NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `atlas_workspace_links_user_unique` (`atlas_user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
    }

    protected function findLinkedVendor(array $payload): ?User
    {
        $linkedVendorId = DB::table($this->atlasLinkTable)
            ->where('atlas_user_id', (int) $payload['atlas_user_id'])
            ->value('vendor_user_id');

        if ($linkedVendorId) {
            return User::where('id', (int) $linkedVendorId)->where('type', 2)->first();
        }

        $vendor = User::where('email', $payload['email'])->where('type', 2)->first();
        if ($vendor) {
            $this->saveAtlasLink($payload, $vendor);
        }

        return $vendor;
    }

    protected function saveAtlasLink(array $payload, User $vendor): void
    {
        DB::table($this->atlasLinkTable)->updateOrInsert(
            ['atlas_user_id' => (int) $payload['atlas_user_id']],
            [
                'vendor_user_id' => (int) $vendor->id,
                'email' => (string) $vendor->email,
                'slug' => (string) $vendor->slug,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    protected function createVendorWorkspace(array $payload): User
    {
        return DB::transaction(function () use ($payload) {
            $storeId = (int) (StoreCategory::where('is_available', 1)->where('is_deleted', 2)->orderBy('id')->value('id') ?: 1);
            $countryId = (int) (Country::where('is_available', 1)->where('is_deleted', 2)->orderBy('id')->value('id') ?: 1);
            $cityId = (int) (City::where('country_id', $countryId)->where('is_available', 1)->where('is_deleted', 2)->orderBy('id')->value('id') ?: 1);
            $defaultVendor = User::where('id', 1)->first();

            $vendor = new User();
            $vendor->store_id = $storeId;
            $vendor->name = (string) ($payload['company_name'] ?? $payload['name'] ?? 'Atlas Business');
            $vendor->slug = $this->makeUniqueSlug((string) ($payload['slug'] ?? $payload['company_name'] ?? $payload['name'] ?? 'atlas-business'));
            $vendor->email = (string) $payload['email'];
            $vendor->mobile = (string) ($payload['phone'] ?? '');
            $vendor->image = 'default.png';
            $vendor->password = !empty($payload['password_hash']) ? (string) $payload['password_hash'] : Hash::make(Str::random(32));
            $vendor->login_type = 'normal';
            $vendor->type = 2;
            $vendor->token = '';
            $vendor->country_id = $countryId;
            $vendor->city_id = $cityId;
            $vendor->is_available = 1;
            $vendor->is_verified = 1;
            $vendor->wallet = 0;
            $vendor->commission_on_off = $defaultVendor ? $defaultVendor->commission_on_off : 2;
            $vendor->commission_type = $defaultVendor ? $defaultVendor->commission_type : 1;
            $vendor->commission_amount = $defaultVendor ? $defaultVendor->commission_amount : 0;
            $vendor->save();

            $this->seedVendorTiming($vendor->id);
            $this->seedVendorCustomStatuses($vendor->id);
            $this->seedVendorPayments($vendor->id);
            $this->seedVendorMessaging($vendor->id);
            $this->seedVendorSettings($vendor, $payload);
            $this->seedVendorOtherSettings($vendor->id);

            return $vendor;
        });
    }

    protected function makeUniqueSlug(string $slugSource): string
    {
        $baseSlug = Str::slug($slugSource) ?: 'atlas-business';
        $slug = $baseSlug;
        $counter = 2;

        while (User::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    protected function seedVendorTiming(int $vendorId): void
    {
        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day) {
            $timing = new Timing();
            $timing->vendor_id = $vendorId;
            $timing->day = $day;
            $timing->open_time = '09:00 AM';
            $timing->break_start = '01:00 PM';
            $timing->break_end = '02:00 PM';
            $timing->close_time = '09:00 PM';
            $timing->is_always_close = '2';
            $timing->save();
        }
    }

    protected function seedVendorCustomStatuses(int $vendorId): void
    {
        foreach (CustomStatus::where('vendor_id', 1)->get() as $default) {
            $status = new CustomStatus();
            $status->vendor_id = $vendorId;
            $status->name = $default->name;
            $status->type = $default->type;
            $status->status_use = $default->status_use;
            $status->is_available = $default->is_available;
            $status->is_deleted = $default->is_deleted;
            $status->save();
        }
    }

    protected function seedVendorPayments(int $vendorId): void
    {
        foreach (Payment::where('vendor_id', 1)->get() as $default) {
            $payment = new Payment();
            $payment->vendor_id = $vendorId;
            $payment->payment_name = $default->payment_name;
            $payment->unique_identifier = $default->unique_identifier;
            $payment->currency = $default->currency;
            $payment->image = $default->image;
            $payment->payment_type = $default->payment_type;
            $payment->public_key = '-';
            $payment->secret_key = '-';
            $payment->encryption_key = '-';
            $payment->environment = 1;
            $payment->is_available = 1;
            $payment->is_activate = $default->is_activate;
            $payment->save();
        }
    }

    protected function seedVendorMessaging(int $vendorId): void
    {
        $defaultWhatsapp = WhatsappMessage::where('vendor_id', 1)->first();
        if ($defaultWhatsapp) {
            $whatsapp = new WhatsappMessage();
            $whatsapp->vendor_id = $vendorId;
            $whatsapp->item_message = $defaultWhatsapp->item_message;
            $whatsapp->whatsapp_message = $defaultWhatsapp->whatsapp_message;
            $whatsapp->status_message = $defaultWhatsapp->status_message;
            $whatsapp->order_whatsapp_message = $defaultWhatsapp->order_whatsapp_message;
            $whatsapp->order_status_message = $defaultWhatsapp->order_status_message;
            $whatsapp->whatsapp_number = $defaultWhatsapp->whatsapp_number;
            $whatsapp->whatsapp_phone_number_id = $defaultWhatsapp->whatsapp_phone_number_id;
            $whatsapp->whatsapp_access_token = $defaultWhatsapp->whatsapp_access_token;
            $whatsapp->whatsapp_chat_on_off = $defaultWhatsapp->whatsapp_chat_on_off;
            $whatsapp->whatsapp_mobile_view_on_off = $defaultWhatsapp->whatsapp_mobile_view_on_off;
            $whatsapp->whatsapp_chat_position = $defaultWhatsapp->whatsapp_chat_position;
            $whatsapp->order_created = $defaultWhatsapp->order_created;
            $whatsapp->status_change = $defaultWhatsapp->status_change;
            $whatsapp->product_order_created = $defaultWhatsapp->product_order_created;
            $whatsapp->order_status_change = $defaultWhatsapp->order_status_change;
            $whatsapp->message_type = $defaultWhatsapp->message_type;
            $whatsapp->save();
        }

        $defaultTelegram = TelegramMessage::where('vendor_id', 1)->first();
        if ($defaultTelegram) {
            $telegram = new TelegramMessage();
            $telegram->vendor_id = $vendorId;
            $telegram->item_message = $defaultTelegram->item_message;
            $telegram->telegram_message = $defaultTelegram->telegram_message;
            $telegram->order_telegram_message = $defaultTelegram->order_telegram_message;
            $telegram->order_created = $defaultTelegram->order_created;
            $telegram->product_order_created = $defaultTelegram->product_order_created;
            $telegram->telegram_access_token = $defaultTelegram->telegram_access_token;
            $telegram->telegram_chat_id = $defaultTelegram->telegram_chat_id;
            $telegram->save();
        }
    }

    protected function seedVendorSettings(User $vendor, array $payload): void
    {
        $adminSettings = Settings::where('vendor_id', 1)->first();
        $landingSettings = LandingSettings::where('vendor_id', 1)->first();
        $description = trim((string) ($payload['company_description'] ?? ''));
        $siteName = trim((string) ($payload['site_name'] ?? $vendor->name));

        $settings = new Settings();
        $settings->vendor_id = $vendor->id;
        $settings->currencies = $adminSettings->currencies ?? 'usd';
        $settings->default_currency = $adminSettings->default_currency ?? 'usd';
        $settings->logo = 'default.png';
        $settings->favicon = 'default.png';
        $settings->og_image = 'default.png';
        $settings->home_banner = !empty($adminSettings->home_banner) ? $adminSettings->home_banner : 'homebanner.png';
        $settings->footer_description = $description !== '' ? $description : ($adminSettings->footer_description ?? '');
        $settings->timezone = $adminSettings->timezone ?? 'UTC';
        $settings->web_title = $siteName !== '' ? $siteName : ($adminSettings->web_title ?? $vendor->name);
        $settings->copyright = $adminSettings->copyright ?? '';
        $settings->email = $vendor->email;
        $settings->mobile = $vendor->mobile ?: '-';
        $settings->contact = '-';
        $settings->time_format = $adminSettings->time_format ?? 2;
        $settings->date_format = $adminSettings->date_format ?? 'd M, Y';
        $settings->order_prefix = $adminSettings->order_prefix ?? 'ATLS';
        $settings->order_number_start = $adminSettings->order_number_start ?? 1001;
        $settings->firebase = '-';
        $settings->primary_color = $landingSettings->primary_color ?? '#000000';
        $settings->secondary_color = $landingSettings->secondary_color ?? '#3595d5';
        $settings->contact_email_message = $adminSettings->contact_email_message ?? null;
        $settings->new_order_invoice_email_message = $adminSettings->new_order_invoice_email_message ?? null;
        $settings->vendor_new_order_email_message = $adminSettings->vendor_new_order_email_message ?? null;
        $settings->order_status_email_message = $adminSettings->order_status_email_message ?? null;
        $settings->new_product_order_invoice_email_message = $adminSettings->new_product_order_invoice_email_message ?? null;
        $settings->vendor_new_product_order_email_message = $adminSettings->vendor_new_product_order_email_message ?? null;
        $settings->product_order_status_email_message = $adminSettings->product_order_status_email_message ?? null;
        $settings->referral_earning_email_message = $adminSettings->referral_earning_email_message ?? null;
        $settings->description = $description !== '' ? $description : ($adminSettings->description ?? '');
        $settings->about_content = $description !== '' ? $description : ($adminSettings->about_content ?? '');
        $settings->landing_website_title = $siteName !== '' ? $siteName : ($adminSettings->landing_website_title ?? $vendor->name);
        $settings->homepage_title = $siteName !== '' ? $siteName : ($adminSettings->homepage_title ?? $vendor->name);
        $settings->homepage_subtitle = $description !== '' ? Str::limit($description, 180, '') : ($adminSettings->homepage_subtitle ?? '');
        $settings->meta_title = $siteName !== '' ? $siteName : ($adminSettings->meta_title ?? $vendor->name);
        $settings->meta_description = $description !== '' ? $description : ($adminSettings->meta_description ?? '');
        $settings->service_on_off = 1;
        $settings->shop_on_off = 2;
        $settings->save();
    }

    protected function seedVendorOtherSettings(int $vendorId): void
    {
        $default = OtherSettings::where('vendor_id', 1)->first();
        $other = new OtherSettings();
        $other->vendor_id = $vendorId;
        if ($default) {
            $other->maintenance_on_off = $default->maintenance_on_off;
            $other->maintenance_title = $default->maintenance_title;
            $other->maintenance_description = $default->maintenance_description;
        }
        $other->save();
    }

    protected function syncVendor(User $vendor, array $payload): void
    {
        $vendor->name = $payload['company_name'] ?? ($payload['name'] ?? $vendor->name);
        $vendor->email = $payload['email'] ?? $vendor->email;
        if (!empty($payload['phone'])) {
            $vendor->mobile = $payload['phone'];
        }
        if (empty($vendor->slug) && !empty($payload['slug'])) {
            $vendor->slug = Str::slug($payload['slug']);
        }
        if (!empty($payload['password_hash'])) {
            $vendor->password = (string) $payload['password_hash'];
        }
        $vendor->save();

        $brandColors = array_values(array_filter((array) ($payload['brand_colors'] ?? [])));
        $primaryColor = $brandColors[0] ?? null;
        $secondaryColor = $brandColors[1] ?? ($brandColors[0] ?? null);
        $description = trim((string) ($payload['company_description'] ?? ''));
        $siteName = trim((string) ($payload['site_name'] ?? ($payload['company_name'] ?? $vendor->name)));

        $settings = Settings::where('vendor_id', $vendor->id)->first();
        if ($settings) {
            $settings->email = $vendor->email;
            $settings->mobile = $vendor->mobile;
            $settings->description = $description !== '' ? $description : $settings->description;
            $settings->about_content = $description !== '' ? $description : $settings->about_content;
            $settings->web_title = $siteName !== '' ? $siteName : $settings->web_title;
            $settings->landing_website_title = $siteName !== '' ? $siteName : $settings->landing_website_title;
            $settings->homepage_title = $siteName !== '' ? $siteName : $settings->homepage_title;
            $settings->homepage_subtitle = $description !== '' ? Str::limit($description, 180, '') : $settings->homepage_subtitle;
            $settings->footer_description = $description !== '' ? $description : $settings->footer_description;
            $settings->meta_title = $siteName !== '' ? $siteName : $settings->meta_title;
            $settings->meta_description = $description !== '' ? $description : $settings->meta_description;
            $settings->service_on_off = 1;
            $settings->shop_on_off = 2;
            if ($primaryColor) {
                $settings->primary_color = $primaryColor;
            }
            if ($secondaryColor) {
                $settings->secondary_color = $secondaryColor;
            }
            $settings->save();
        }
    }
}
