<?php

namespace App\Http\Controllers;

use App\Helpers\helper;
use App\Models\About;
use App\Models\City;
use App\Models\Country;
use App\Models\Settings;
use App\Models\StoreCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AtlasBridgeController extends Controller
{
    public function launch(Request $request)
    {
        $payloadEncoded = (string) $request->query('payload', '');
        $signature = (string) $request->query('sig', '');
        $sharedSecret = (string) env('WEBSITE_PLATFORM_SHARED_SECRET', env('APP_KEY', 'atlas-website-secret'));

        if ($payloadEncoded === '' || $signature === '') {
            abort(403, 'Missing launch signature.');
        }

        $expectedSignature = hash_hmac('sha256', $payloadEncoded, $sharedSecret);
        if (!hash_equals($expectedSignature, $signature)) {
            abort(403, 'Invalid launch signature.');
        }

        $payloadJson = base64_decode(strtr($payloadEncoded, '-_', '+/'));
        $payload = json_decode((string) $payloadJson, true);

        if (!is_array($payload) || empty($payload['email'])) {
            abort(403, 'Invalid launch payload.');
        }

        if (!empty($payload['exp']) && (int) $payload['exp'] < time()) {
            abort(403, 'Launch link expired.');
        }

        $vendor = User::where('email', $payload['email'])->where('type', 2)->first();

        if (!$vendor) {
            $storeId = (int) (StoreCategory::where('is_available', 1)->where('is_deleted', 2)->orderBy('id')->value('id') ?: 1);
            $countryId = (int) (Country::where('is_available', 1)->where('is_deleted', 2)->orderBy('id')->value('id') ?: 1);
            $cityId = (int) (City::where('country_id', $countryId)->where('is_available', 1)->where('is_deleted', 2)->orderBy('id')->value('id') ?: 1);
            $vendorId = helper::vendor_register(
                $payload['company_name'] ?? $payload['name'],
                $payload['email'],
                $payload['phone'] ?? '',
                Hash::make(Str::random(32)),
                '',
                $payload['slug'] ?? '',
                '',
                '',
                $countryId,
                $cityId,
                $storeId,
                2
            );

            if (!is_numeric($vendorId)) {
                abort(500, 'Unable to create Storemart workspace.');
            }

            $vendor = User::find((int) $vendorId);
        }

        if (!$vendor) {
            abort(500, 'Storemart workspace not found.');
        }

        $this->syncVendor($vendor, $payload);

        Auth::login($vendor, true);
        $request->session()->put('admin_login', 1);
        $request->session()->put('user_login', 1);

        return redirect('/admin/dashboard');
    }

    protected function syncVendor(User $vendor, array $payload): void
    {
        $vendor->name = $payload['company_name'] ?? $vendor->name;
        $vendor->email = $payload['email'] ?? $vendor->email;
        if (!empty($payload['phone'])) {
            $vendor->mobile = $payload['phone'];
        }
        if (empty($vendor->slug) && !empty($payload['slug'])) {
            $vendor->slug = Str::slug($payload['slug']);
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
            $settings->website_title = $siteName !== '' ? $siteName : $settings->website_title;
            $settings->meta_title = $siteName !== '' ? $siteName : $settings->meta_title;
            $settings->meta_description = $description !== '' ? $description : $settings->meta_description;
            if ($primaryColor) {
                $settings->primary_color = $primaryColor;
            }
            if ($secondaryColor) {
                $settings->secondary_color = $secondaryColor;
            }
            $settings->save();
        }

        $about = About::firstOrNew(['vendor_id' => $vendor->id]);
        $about->about_content = $description !== '' ? nl2br(e($description)) : ($about->about_content ?? '');
        $about->save();
    }
}
