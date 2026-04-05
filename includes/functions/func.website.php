<?php

function website_builder_table($name)
{
    global $config;
    return $config['db']['pre'] . $name;
}

function website_builder_ensure_tables()
{
    static $done = false;

    if ($done) {
        return;
    }

    $db = ORM::get_db();
    $charset = "DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $queries = [
        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_sites') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) DEFAULT NULL,
            `site_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `site_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'service',
            `template_key` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `slug` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `subdomain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `custom_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
            `brand_colors_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `theme_tokens_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `content_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `published_at` datetime DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",

        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_pages') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_id` int(11) DEFAULT NULL,
            `page_key` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `content_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `sort_order` int(11) DEFAULT 0,
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",

        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_products') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_id` int(11) DEFAULT NULL,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `price` decimal(12,2) DEFAULT 0.00,
            `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
            `metadata_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",

        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_services') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_id` int(11) DEFAULT NULL,
            `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `duration_minutes` int(11) DEFAULT 60,
            `price` decimal(12,2) DEFAULT 0.00,
            `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
            `metadata_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",

        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_orders') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_id` int(11) DEFAULT NULL,
            `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `amount` decimal(12,2) DEFAULT 0.00,
            `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
            `metadata_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",

        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_bookings') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_id` int(11) DEFAULT NULL,
            `service_id` int(11) DEFAULT NULL,
            `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `booking_start` datetime DEFAULT NULL,
            `booking_end` datetime DEFAULT NULL,
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
            `metadata_json` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",

        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_wallet_ledger') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_id` int(11) DEFAULT NULL,
            `user_id` int(11) DEFAULT NULL,
            `entry_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `amount` decimal(12,2) DEFAULT 0.00,
            `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
            `reference_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `reference_id` int(11) DEFAULT NULL,
            `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'posted',
            `created_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",

        "CREATE TABLE IF NOT EXISTS `" . website_builder_table('website_payouts') . "` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_id` int(11) DEFAULT NULL,
            `user_id` int(11) DEFAULT NULL,
            `amount` decimal(12,2) DEFAULT 0.00,
            `currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
            `payment_method` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `account_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
            `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB {$charset}",
    ];

    foreach ($queries as $query) {
        $db->exec($query);
    }

    $done = true;
}

function website_builder_template_catalog()
{
    return [
        'aimeos-commerce-minimal' => [
            'key' => 'aimeos-commerce-minimal',
            'type' => 'ecommerce',
            'title' => __('Aimeos Minimal Store'),
            'badge' => __('Products'),
            'description' => __('A clean product storefront shaped from the Aimeos catalog home flow, ideal for a focused small catalog and a straightforward path to checkout.'),
            'source' => __('Aimeos catalog home + basket flow'),
            'source_path' => 'aimeos/views/catalog/home.blade.php',
            'source_paths' => [
                'aimeos/views/catalog/home.blade.php',
                'aimeos/views/basket/index.blade.php',
                'aimeos/views/checkout/index.blade.php',
            ],
            'preview_theme' => 'minimal',
            'preview_sections' => [
                __('Hero offer'),
                __('Featured products'),
                __('Benefits'),
                __('Checkout CTA'),
            ],
            'features' => [
                __('Product grid, featured collection, and quick checkout flow'),
                __('Designed for brands selling a small curated catalog'),
                __('Best for modern DTC products and minimal commerce brands'),
            ],
        ],
        'aimeos-commerce-editorial' => [
            'key' => 'aimeos-commerce-editorial',
            'type' => 'ecommerce',
            'title' => __('Aimeos Editorial Commerce'),
            'badge' => __('Products'),
            'description' => __('A richer Aimeos storefront shaped around product storytelling, product detail education, and premium conversion sections.'),
            'source' => __('Aimeos detail + checkout confirm flow'),
            'source_path' => 'aimeos/views/catalog/detail.blade.php',
            'source_paths' => [
                'aimeos/views/catalog/detail.blade.php',
                'aimeos/views/checkout/confirm.blade.php',
                'aimeos/views/catalog/list.blade.php',
            ],
            'preview_theme' => 'editorial',
            'preview_sections' => [
                __('Story-led hero'),
                __('Best sellers'),
                __('Product education'),
                __('Trust section'),
            ],
            'features' => [
                __('Story-first homepage paired with product highlights'),
                __('Works well for premium, lifestyle, and aesthetic brands'),
                __('Built to support product education and brand positioning'),
            ],
        ],
        'aimeos-service-studio' => [
            'key' => 'aimeos-service-studio',
            'type' => 'service',
            'title' => __('Aimeos Service Studio'),
            'badge' => __('Bookings'),
            'description' => __('A service website adapted from the Aimeos page and checkout structure, rebuilt around offers, proof, and a booking-first conversion path.'),
            'source' => __('Aimeos page + Atlas booking flow'),
            'source_path' => 'aimeos/views/page/index.blade.php',
            'source_paths' => [
                'aimeos/views/page/index.blade.php',
                'aimeos/views/checkout/index.blade.php',
            ],
            'preview_theme' => 'studio',
            'preview_sections' => [
                __('Offer overview'),
                __('Service cards'),
                __('Testimonials'),
                __('Booking CTA'),
            ],
            'features' => [
                __('Service cards, booking CTA, testimonials, and FAQ'),
                __('Ideal for dog walkers, barbers, consultants, clinics, and studios'),
                __('Made for businesses that need bookings more than a large catalog'),
            ],
        ],
        'aimeos-service-local' => [
            'key' => 'aimeos-service-local',
            'type' => 'service',
            'title' => __('Aimeos Local Service Pro'),
            'badge' => __('Bookings'),
            'description' => __('A local-service website adapted from Aimeos page and update flows, focused on trust, availability, and simple booking conversion.'),
            'source' => __('Aimeos page + update flow'),
            'source_path' => 'aimeos/views/page/index.blade.php',
            'source_paths' => [
                'aimeos/views/page/index.blade.php',
                'aimeos/views/checkout/update.blade.php',
            ],
            'preview_theme' => 'local',
            'preview_sections' => [
                __('Local trust hero'),
                __('Services'),
                __('FAQ'),
                __('Schedule request'),
            ],
            'features' => [
                __('Optimized for trust, contact, and recurring bookings'),
                __('Great for service businesses with a few offers and a clear CTA'),
                __('Supports simple booking and lead capture side by side'),
            ],
        ],
    ];
}

function website_builder_templates_for_type($type)
{
    $catalog = website_builder_template_catalog();
    $matches = [];

    foreach ($catalog as $template) {
        if (!empty($template['type']) && (string) $template['type'] === (string) $type) {
            $matches[] = $template;
        }
    }

    if (!empty($matches)) {
        return $matches;
    }

    return array_values(array_filter($catalog, function ($template) {
        return !empty($template['type']) && $template['type'] === 'service';
    }));
}

function website_builder_get_template($templateKey)
{
    $templates = website_builder_template_catalog();
    return isset($templates[$templateKey]) ? $templates[$templateKey] : null;
}

function website_builder_split_values($value, $limit = 6)
{
    if (is_array($value)) {
        $items = $value;
    } else {
        $items = preg_split('/[\n,]+/', (string) $value);
    }

    $clean = [];
    foreach ($items as $item) {
        $item = trim((string) $item);
        if ($item !== '') {
            $clean[] = $item;
        }
    }

    return array_slice(array_values(array_unique($clean)), 0, $limit);
}

function website_builder_slugify($text)
{
    $slug = URLify::filter((string) $text);
    return trim($slug, '-') ?: 'site';
}

function website_builder_unique_slug($desiredSlug, $excludeSiteId = 0)
{
    $baseSlug = website_builder_slugify($desiredSlug);
    $slug = $baseSlug;
    $counter = 2;

    while (true) {
        $query = ORM::for_table(website_builder_table('website_sites'))
            ->where('slug', $slug);
        if ($excludeSiteId > 0) {
            $query = $query->where_not_equal('id', (int) $excludeSiteId);
        }
        if (!$query->find_one()) {
            return $slug;
        }
        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
}

function website_builder_generation_context($userId)
{
    $profile = social_media_get_profile($userId);
    $intelligence = social_media_get_company_intelligence($userId);

    return [
        'profile' => $profile,
        'intelligence' => $intelligence,
        'company_name' => !empty($profile['company_name']) ? $profile['company_name'] : __('Your business'),
        'company_description' => !empty($profile['company_description']) ? $profile['company_description'] : __('A clear, trustworthy business with a polished customer experience.'),
        'audience' => !empty($profile['target_audience']) ? $profile['target_audience'] : __('people who need a simple and trustworthy solution'),
        'offer' => !empty($profile['key_products']) ? trim(strtok($profile['key_products'], ",\n")) : __('Signature offer'),
        'tone' => !empty($profile['tone_attributes']) ? implode(', ', (array) $profile['tone_attributes']) : __('clear, polished, trustworthy'),
    ];
}

function website_builder_company_profile_status($profile, $intelligence)
{
    $missing = [];

    if (empty($profile['company_name'])) {
        $missing[] = __('Company name');
    }
    if (empty($profile['company_website']) && empty($profile['company_description'])) {
        $missing[] = __('Website or company description');
    }
    if (empty($intelligence['company_summary'])) {
        $missing[] = __('Company summary');
    }
    if (empty($profile['target_audience']) && empty($profile['ideal_customer_profile'])) {
        $missing[] = __('Ideal customer profile');
    }
    if (empty($profile['top_problems_solved'])) {
        $missing[] = __('Problems solved');
    }
    if (empty($profile['unique_selling_points']) && empty($profile['differentiators'])) {
        $missing[] = __('Unique selling points');
    }
    if (empty($profile['brand_colors'])) {
        $missing[] = __('Brand colors');
    }
    if (empty($profile['tone_attributes']) && empty($profile['brand_voice'])) {
        $missing[] = __('Tone of voice');
    }

    return [
        'ready' => empty($missing),
        'missing' => $missing,
    ];
}

function website_builder_infer_site_type($profile, $intelligence)
{
    $haystack = strtolower(trim(implode(' ', array_filter([
        !empty($profile['company_industry']) ? $profile['company_industry'] : '',
        !empty($profile['company_description']) ? $profile['company_description'] : '',
        !empty($profile['key_products']) ? $profile['key_products'] : '',
        !empty($profile['content_goals']) ? $profile['content_goals'] : '',
        !empty($profile['differentiators']) ? $profile['differentiators'] : '',
        !empty($intelligence['company_summary']) ? $intelligence['company_summary'] : '',
        !empty($intelligence['market_research']) ? $intelligence['market_research'] : '',
    ]))));

    $ecommerceSignals = [
        'shop', 'store', 'ecommerce', 'e-commerce', 'product', 'products', 'catalog',
        'skincare', 'fashion', 'merch', 'merchandise', 'supplement', 'jewelry',
        'accessories', 'leash', 'leashes', 'inventory', 'sell online', 'checkout'
    ];
    $serviceSignals = [
        'booking', 'bookings', 'appointment', 'appointments', 'service', 'services',
        'consulting', 'consultant', 'barber', 'salon', 'clinic', 'coaching', 'training',
        'dog walking', 'grooming', 'agency', 'call', 'session', 'sessions'
    ];

    $ecommerceScore = 0;
    $serviceScore = 0;

    foreach ($ecommerceSignals as $signal) {
        if ($signal !== '' && strpos($haystack, $signal) !== false) {
            $ecommerceScore++;
        }
    }
    foreach ($serviceSignals as $signal) {
        if ($signal !== '' && strpos($haystack, $signal) !== false) {
            $serviceScore++;
        }
    }

    return $ecommerceScore > $serviceScore ? 'ecommerce' : 'service';
}

function website_builder_site_is_launched($site)
{
    if (empty($site)) {
        return false;
    }

    return !empty($site['published_at']) || in_array($site['status'], ['published', 'active', 'launched'], true);
}

function website_builder_generate_structured_fields($userId, $siteType, $fields = [])
{
    $context = website_builder_generation_context($userId);
    $profile = $context['profile'];
    $intelligence = $context['intelligence'];
    $siteType = $siteType === 'ecommerce' ? 'ecommerce' : 'service';
    $fields = array_values(array_unique(array_filter((array) $fields)));

    $fallback = [
        'website_title' => $context['company_name'],
        'subdomain' => website_builder_slugify($context['company_name']),
        'first_item_title' => $context['offer'],
        'first_item_description' => !empty($intelligence['competitive_edges'])
            ? $intelligence['competitive_edges']
            : $context['company_description'],
        'first_item_price' => $siteType === 'ecommerce' ? '49' : '35',
        'first_item_duration' => $siteType === 'service' ? '60' : '',
    ];

    if (empty($fields)) {
        return $fallback;
    }

    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

    $openAi = new Orhanerday\OpenAi\OpenAi(get_api_key());
    $modelsToTry = function_exists('social_media_get_chat_model_candidates')
        ? social_media_get_chat_model_candidates()
        : [normalize_openai_model(get_default_openai_chat_model()), 'gpt-4o-mini', 'gpt-4o'];

    $system = "You generate concise website setup defaults for small business websites. Return valid JSON only.";
    $userPrompt = "Generate values only for these keys: " . implode(', ', $fields) . ".\n"
        . "Business type: {$siteType}\n"
        . "Company: " . (!empty($profile['company_name']) ? $profile['company_name'] : '') . "\n"
        . "Description: " . (!empty($profile['company_description']) ? $profile['company_description'] : '') . "\n"
        . "Audience: " . (!empty($profile['target_audience']) ? $profile['target_audience'] : '') . "\n"
        . "Offerings: " . (!empty($profile['key_products']) ? $profile['key_products'] : '') . "\n"
        . "Differentiators: " . (!empty($profile['differentiators']) ? $profile['differentiators'] : '') . "\n"
        . "USPs: " . implode('; ', !empty($profile['unique_selling_points']) ? (array) $profile['unique_selling_points'] : []) . "\n"
        . "Tone: " . implode(', ', !empty($profile['tone_attributes']) ? (array) $profile['tone_attributes'] : []) . "\n"
        . "Company intelligence summary: " . (!empty($intelligence['company_summary']) ? $intelligence['company_summary'] : '') . "\n"
        . "Market guidance: " . (!empty($intelligence['strategic_guidance']) ? $intelligence['strategic_guidance'] : '') . "\n\n"
        . "Rules:\n"
        . "1. website_title should be short and brand-ready.\n"
        . "2. subdomain should be lowercase, simple, and without the .hatchers.ai suffix.\n"
        . "3. first_item_title must be a clear customer-facing product or service name.\n"
        . "4. first_item_description must be concise, benefits-led, and ready to publish.\n"
        . "5. first_item_price should be numeric only.\n"
        . "6. first_item_duration should be numeric minutes only for service businesses.\n"
        . "7. Return only the requested keys.\n"
        . "JSON shape: {\"website_title\":\"...\",\"subdomain\":\"...\",\"first_item_title\":\"...\",\"first_item_description\":\"...\",\"first_item_price\":\"...\",\"first_item_duration\":\"...\"}";

    foreach ($modelsToTry as $modelToTry) {
        $payload = [
            'model' => $modelToTry,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.5,
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 700,
            'user' => $userId,
        ];

        $response = $openAi->chat($payload);
        $decoded = json_decode($response, true);
        if (!empty($decoded['error']['message'])) {
            unset($payload['response_format']);
            $response = $openAi->chat($payload);
            $decoded = json_decode($response, true);
        }

        if (empty($decoded['choices'][0]['message']['content'])) {
            continue;
        }

        $json = function_exists('social_media_extract_json')
            ? social_media_extract_json($decoded['choices'][0]['message']['content'])
            : json_decode($decoded['choices'][0]['message']['content'], true);

        if (!is_array($json)) {
            continue;
        }

        foreach ($fields as $field) {
            if (empty($json[$field]) && isset($fallback[$field])) {
                $json[$field] = $fallback[$field];
            }
        }

        if (!empty($json['subdomain'])) {
            $json['subdomain'] = website_builder_slugify($json['subdomain']);
        }

        return array_intersect_key($json, array_flip($fields)) + array_intersect_key($fallback, array_flip($fields));
    }

    return array_intersect_key($fallback, array_flip($fields));
}

function website_builder_theme_tokens($profile)
{
    $brandColors = !empty($profile['brand_colors']) && is_array($profile['brand_colors']) ? array_values($profile['brand_colors']) : [];
    $primary = !empty($brandColors[0]) ? $brandColors[0] : '#111111';
    $secondary = !empty($brandColors[1]) ? $brandColors[1] : '#f3efe6';
    $accent = !empty($brandColors[2]) ? $brandColors[2] : $primary;

    return [
        'primary' => $primary,
        'secondary' => $secondary,
        'accent' => $accent,
        'surface' => '#ffffff',
        'muted' => '#6f6a61',
    ];
}

function website_builder_build_offerings($template, $profile)
{
    $offerings = website_builder_split_values($profile['key_products'], 6);
    if (empty($offerings)) {
        $offerings = website_builder_split_values($profile['unique_selling_points'], 6);
    }
    if (empty($offerings)) {
        $offerings = $template['type'] === 'ecommerce'
            ? [__('Featured product'), __('Signature product'), __('Best seller')]
            : [__('Core service'), __('Premium service'), __('Signature service')];
    }

    return $offerings;
}

function website_builder_build_faq($profile)
{
    $problems = website_builder_split_values($profile['top_problems_solved'], 4);
    $faq = [];

    foreach ($problems as $problem) {
        $faq[] = [
            'question' => sprintf(__('How do you help with %s?'), mb_strtolower($problem)),
            'answer' => __('Atlas generated this draft from your company intelligence. You can refine the answer in the website editor before publishing.'),
        ];
    }

    if (empty($faq)) {
        $faq = [
            [
                'question' => __('What makes your business different?'),
                'answer' => __('This draft highlights your strongest differentiators and gives you a clean place to explain why customers should choose you.'),
            ],
            [
                'question' => __('How do customers get started?'),
                'answer' => __('Use this section to explain your booking or purchase process in simple, reassuring language.'),
            ],
        ];
    }

    return $faq;
}

function website_builder_generate_blueprint($userId, $templateKey, $overrides = [])
{
    $template = website_builder_get_template($templateKey);
    if (empty($template)) {
        return null;
    }

    $profile = social_media_get_profile($userId);
    $intelligence = social_media_get_company_intelligence($userId);
    $tokens = website_builder_theme_tokens($profile);

    $existingSite = website_builder_get_primary_site($userId);
    $companyName = !empty($overrides['website_title']) ? $overrides['website_title'] : (!empty($profile['company_name']) ? $profile['company_name'] : __('Your business'));
    $companyDescription = !empty($profile['company_description']) ? $profile['company_description'] : __('We help customers with a clear offer, thoughtful brand positioning, and a polished experience.');
    $audience = !empty($profile['target_audience']) ? $profile['target_audience'] : __('people who need a simple and trustworthy solution');
    $uspList = website_builder_split_values($profile['unique_selling_points'], 4);
    $offerings = website_builder_build_offerings($template, $profile);
    if (!empty($overrides['first_item_title'])) {
        array_unshift($offerings, $overrides['first_item_title']);
        $offerings = array_values(array_unique(array_filter($offerings)));
    }
    $faq = website_builder_build_faq($profile);

    $heroTitle = $template['type'] === 'ecommerce'
        ? sprintf(__('Shop %s with confidence'), $companyName)
        : sprintf(__('Book %s with confidence'), $companyName);

    $heroSubtitle = !empty($intelligence['company_summary'])
        ? $intelligence['company_summary']
        : sprintf(__('Built for %s, this draft website turns your company intelligence into a polished online presence.'), $audience);

    $primaryCta = $template['type'] === 'ecommerce' ? __('Start selling') : __('Start taking bookings');
    $secondaryCta = __('Edit company intelligence');

    $pages = [
        [
            'page_key' => 'home',
            'title' => __('Home'),
            'slug' => '',
            'sort_order' => 1,
            'content' => [
                'hero' => [
                    'eyebrow' => !empty($profile['company_industry']) ? $profile['company_industry'] : __('Your Website'),
                    'title' => $heroTitle,
                    'subtitle' => $heroSubtitle,
                    'primary_cta' => $primaryCta,
                    'secondary_cta' => $secondaryCta,
                ],
                'offerings' => $offerings,
                'proof' => !empty($uspList) ? $uspList : [__('Clear positioning'), __('Brand-aligned design'), __('Built from company intelligence')],
                'faq' => $faq,
            ],
        ],
        [
            'page_key' => 'about',
            'title' => __('About'),
            'slug' => 'about',
            'sort_order' => 2,
            'content' => [
                'title' => sprintf(__('About %s'), $companyName),
                'body' => $companyDescription,
            ],
        ],
        [
            'page_key' => 'contact',
            'title' => __('Contact'),
            'slug' => 'contact',
            'sort_order' => 3,
            'content' => [
                'title' => __('Contact'),
                'body' => __('Use this page to collect inquiries, direct bookings, or support requests from your customers.'),
            ],
        ],
    ];

    $requestedSubdomain = !empty($overrides['subdomain']) ? $overrides['subdomain'] : $companyName;
    $siteSlugBase = website_builder_unique_slug($requestedSubdomain, !empty($existingSite['id']) ? (int) $existingSite['id'] : 0);

    return [
        'site_name' => $companyName,
        'site_type' => $template['type'],
        'template_key' => $template['key'],
        'slug' => $siteSlugBase,
        'subdomain' => $siteSlugBase . '.hatchers.ai',
        'status' => 'draft',
        'brand_colors_json' => json_encode(!empty($profile['brand_colors']) ? $profile['brand_colors'] : []),
        'theme_tokens_json' => json_encode($tokens),
        'content_json' => json_encode([
            'company_name' => $companyName,
            'company_description' => $companyDescription,
            'audience' => $audience,
            'intelligence' => $intelligence,
            'profile' => [
                'industry' => $profile['company_industry'],
                'brand_voice' => $profile['brand_voice'],
                'tone_attributes' => $profile['tone_attributes'],
                'visual_mood' => $profile['visual_mood'],
                'reference_brands' => $profile['reference_brands'],
            ],
            'template_source' => $template['source'],
            'payment_mode' => 'atlas_wallet',
            'checkout_enabled' => $template['type'] === 'ecommerce',
            'booking_enabled' => $template['type'] === 'service',
            'offerings' => $offerings,
            'faq' => $faq,
            'launch_setup' => [
                'first_item_title' => !empty($overrides['first_item_title']) ? $overrides['first_item_title'] : (!empty($offerings[0]) ? $offerings[0] : ''),
                'first_item_description' => !empty($overrides['first_item_description']) ? $overrides['first_item_description'] : '',
                'first_item_price' => !empty($overrides['first_item_price']) ? $overrides['first_item_price'] : '',
                'first_item_duration' => !empty($overrides['first_item_duration']) ? $overrides['first_item_duration'] : '',
            ],
        ]),
        'pages' => $pages,
    ];
}

function website_builder_create_or_refresh_primary_site($userId, $templateKey, $overrides = [])
{
    website_builder_ensure_tables();
    $blueprint = website_builder_generate_blueprint($userId, $templateKey, $overrides);
    if (empty($blueprint)) {
        return null;
    }

    $siteTable = website_builder_table('website_sites');
    $pagesTable = website_builder_table('website_pages');
    $now = date('Y-m-d H:i:s');

    $site = ORM::for_table($siteTable)
        ->where('user_id', (int) $userId)
        ->find_one();

    if (!$site) {
        $site = ORM::for_table($siteTable)->create();
        $site->user_id = (int) $userId;
        $site->created_at = $now;
    }

    $site->site_name = $blueprint['site_name'];
    $site->site_type = $blueprint['site_type'];
    $site->template_key = $blueprint['template_key'];
    $site->slug = $blueprint['slug'];
    $site->subdomain = $blueprint['subdomain'];
    $site->status = $blueprint['status'];
    $site->brand_colors_json = $blueprint['brand_colors_json'];
    $site->theme_tokens_json = $blueprint['theme_tokens_json'];
    $site->content_json = $blueprint['content_json'];
    $site->updated_at = $now;
    $site->save();

    ORM::for_table($pagesTable)
        ->where('site_id', (int) $site->id)
        ->delete_many();

    foreach ($blueprint['pages'] as $page) {
        $item = ORM::for_table($pagesTable)->create();
        $item->site_id = (int) $site->id;
        $item->page_key = $page['page_key'];
        $item->title = $page['title'];
        $item->slug = $page['slug'];
        $item->sort_order = (int) $page['sort_order'];
        $item->status = 'draft';
        $item->content_json = json_encode($page['content']);
        $item->created_at = $now;
        $item->updated_at = $now;
        $item->save();
    }

    $contentJson = json_decode($blueprint['content_json'], true);
    $offerings = !empty($contentJson['offerings']) ? $contentJson['offerings'] : [];
    if ($blueprint['site_type'] === 'ecommerce') {
        website_builder_seed_products((int) $site->id, $offerings);
        if (!empty($overrides['first_item_title']) || !empty($overrides['first_item_description']) || !empty($overrides['first_item_price'])) {
            $firstProduct = ORM::for_table(website_builder_table('website_products'))
                ->where('site_id', (int) $site->id)
                ->order_by_asc('id')
                ->find_one();
            if ($firstProduct) {
                if (!empty($overrides['first_item_title'])) {
                    $firstProduct->title = $overrides['first_item_title'];
                    $firstProduct->slug = website_builder_slugify($overrides['first_item_title']);
                }
                if (!empty($overrides['first_item_description'])) {
                    $firstProduct->description = $overrides['first_item_description'];
                }
                if ($overrides['first_item_price'] !== '' && is_numeric($overrides['first_item_price'])) {
                    $firstProduct->price = (float) $overrides['first_item_price'];
                }
                $firstProduct->updated_at = $now;
                $firstProduct->save();
            }
        }
    } else {
        website_builder_seed_services((int) $site->id, $offerings);
        if (!empty($overrides['first_item_title']) || !empty($overrides['first_item_description']) || !empty($overrides['first_item_price']) || !empty($overrides['first_item_duration'])) {
            $firstService = ORM::for_table(website_builder_table('website_services'))
                ->where('site_id', (int) $site->id)
                ->order_by_asc('id')
                ->find_one();
            if ($firstService) {
                if (!empty($overrides['first_item_title'])) {
                    $firstService->title = $overrides['first_item_title'];
                    $firstService->slug = website_builder_slugify($overrides['first_item_title']);
                }
                if (!empty($overrides['first_item_description'])) {
                    $firstService->description = $overrides['first_item_description'];
                }
                if ($overrides['first_item_price'] !== '' && is_numeric($overrides['first_item_price'])) {
                    $firstService->price = (float) $overrides['first_item_price'];
                }
                if ($overrides['first_item_duration'] !== '' && is_numeric($overrides['first_item_duration'])) {
                    $firstService->duration_minutes = (int) $overrides['first_item_duration'];
                }
                $firstService->updated_at = $now;
                $firstService->save();
            }
        }
    }

    return website_builder_get_site((int) $site->id, $userId);
}

function website_builder_get_primary_site($userId)
{
    website_builder_ensure_tables();
    $site = ORM::for_table(website_builder_table('website_sites'))
        ->where('user_id', (int) $userId)
        ->find_one();

    return $site ? website_builder_format_site($site) : null;
}

function website_builder_get_site($siteId, $userId = null)
{
    website_builder_ensure_tables();
    $query = ORM::for_table(website_builder_table('website_sites'))
        ->where('id', (int) $siteId);

    if ($userId !== null) {
        $query->where('user_id', (int) $userId);
    }

    $site = $query->find_one();
    return $site ? website_builder_format_site($site) : null;
}

function website_builder_get_site_pages($siteId)
{
    website_builder_ensure_tables();
    $rows = ORM::for_table(website_builder_table('website_pages'))
        ->where('site_id', (int) $siteId)
        ->order_by_asc('sort_order')
        ->find_many();

    $pages = [];
    foreach ($rows as $row) {
        $pages[] = [
            'id' => (int) $row['id'],
            'page_key' => $row['page_key'],
            'title' => $row['title'],
            'slug' => $row['slug'],
            'status' => $row['status'],
            'content' => json_decode((string) $row['content_json'], true) ?: [],
        ];
    }
    return $pages;
}

function website_builder_get_page($siteId, $pageKey)
{
    website_builder_ensure_tables();
    $row = ORM::for_table(website_builder_table('website_pages'))
        ->where('site_id', (int) $siteId)
        ->where('page_key', $pageKey)
        ->find_one();

    if (!$row) {
        return null;
    }

    return [
        'id' => (int) $row['id'],
        'page_key' => $row['page_key'],
        'title' => $row['title'],
        'slug' => $row['slug'],
        'status' => $row['status'],
        'content' => json_decode((string) $row['content_json'], true) ?: [],
    ];
}

function website_builder_update_page($siteId, $pageKey, array $content, $title = null)
{
    website_builder_ensure_tables();
    $row = ORM::for_table(website_builder_table('website_pages'))
        ->where('site_id', (int) $siteId)
        ->where('page_key', $pageKey)
        ->find_one();

    if (!$row) {
        return false;
    }

    if ($title !== null) {
        $row->title = $title;
    }

    $row->content_json = json_encode($content);
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    return true;
}

function website_builder_get_site_by_slug($slug)
{
    website_builder_ensure_tables();
    $rawSlug = trim((string) $slug);
    if ($rawSlug === '') {
        return null;
    }

    $candidates = [$rawSlug];

    $parsedHost = parse_url($rawSlug, PHP_URL_HOST);
    if (!empty($parsedHost)) {
        $candidates[] = $parsedHost;
    }

    $normalized = preg_replace('#^https?://#i', '', $rawSlug);
    $normalized = preg_replace('#/.*$#', '', $normalized);
    $normalized = trim((string) $normalized, '/');
    if ($normalized !== '') {
        $candidates[] = $normalized;
    }

    foreach (array_values(array_unique($candidates)) as $candidate) {
        $site = ORM::for_table(website_builder_table('website_sites'))
            ->where('slug', $candidate)
            ->find_one();
        if ($site) {
            return website_builder_format_site($site);
        }

        $site = ORM::for_table(website_builder_table('website_sites'))
            ->where('subdomain', $candidate)
            ->find_one();
        if ($site) {
            return website_builder_format_site($site);
        }

        if (stripos($candidate, '.hatchers.ai') !== false) {
            $shortCandidate = preg_replace('/\.hatchers\.ai$/i', '', $candidate);
            if ($shortCandidate !== '') {
                $site = ORM::for_table(website_builder_table('website_sites'))
                    ->where('slug', $shortCandidate)
                    ->find_one();
                if ($site) {
                    return website_builder_format_site($site);
                }
            }
        }
    }

    return null;
}

function website_builder_seed_products($siteId, array $offerings)
{
    $table = website_builder_table('website_products');
    $exists = ORM::for_table($table)->where('site_id', (int) $siteId)->count();
    if ($exists > 0) {
        return;
    }

    $now = date('Y-m-d H:i:s');
    $index = 0;
    foreach (array_slice($offerings, 0, 4) as $offering) {
        $item = ORM::for_table($table)->create();
        $item->site_id = (int) $siteId;
        $item->title = $offering;
        $item->slug = website_builder_slugify($offering);
        $item->description = __('Starter product generated from your company intelligence. Replace this with real product information, pricing, and positioning.');
        $item->price = $index === 0 ? 49 : ($index === 1 ? 79 : 99);
        $item->currency = 'USD';
        $item->status = 'active';
        $item->metadata_json = json_encode([
            'featured' => $index === 0,
            'source' => 'atlas_seed',
        ]);
        $item->created_at = $now;
        $item->updated_at = $now;
        $item->save();
        $index++;
    }
}

function website_builder_seed_services($siteId, array $offerings)
{
    $table = website_builder_table('website_services');
    $exists = ORM::for_table($table)->where('site_id', (int) $siteId)->count();
    if ($exists > 0) {
        return;
    }

    $now = date('Y-m-d H:i:s');
    $index = 0;
    foreach (array_slice($offerings, 0, 4) as $offering) {
        $item = ORM::for_table($table)->create();
        $item->site_id = (int) $siteId;
        $item->title = $offering;
        $item->slug = website_builder_slugify($offering);
        $item->description = __('Starter service generated from your company intelligence. Replace this with the exact scope, outcomes, and booking details you want customers to see.');
        $item->duration_minutes = $index === 0 ? 30 : ($index === 1 ? 45 : 60);
        $item->price = $index === 0 ? 35 : ($index === 1 ? 55 : 75);
        $item->currency = 'USD';
        $item->status = 'active';
        $item->metadata_json = json_encode([
            'featured' => $index === 0,
            'source' => 'atlas_seed',
        ]);
        $item->created_at = $now;
        $item->updated_at = $now;
        $item->save();
        $index++;
    }
}

function website_builder_get_products($siteId)
{
    $rows = ORM::for_table(website_builder_table('website_products'))
        ->where('site_id', (int) $siteId)
        ->order_by_asc('id')
        ->find_many();

    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'id' => (int) $row['id'],
            'title' => $row['title'],
            'slug' => $row['slug'],
            'description' => $row['description'],
            'price' => (float) $row['price'],
            'currency' => $row['currency'],
            'status' => $row['status'],
            'metadata' => json_decode((string) $row['metadata_json'], true) ?: [],
        ];
    }

    return $items;
}

function website_builder_get_services($siteId)
{
    $rows = ORM::for_table(website_builder_table('website_services'))
        ->where('site_id', (int) $siteId)
        ->order_by_asc('id')
        ->find_many();

    $items = [];
    foreach ($rows as $row) {
        $metadata = json_decode((string) $row['metadata_json'], true) ?: [];
        $items[] = [
            'id' => (int) $row['id'],
            'title' => $row['title'],
            'slug' => $row['slug'],
            'description' => $row['description'],
            'duration_minutes' => (int) $row['duration_minutes'],
            'price' => (float) $row['price'],
            'currency' => $row['currency'],
            'status' => $row['status'],
            'metadata' => $metadata,
            'availability_note' => !empty($metadata['availability_note']) ? $metadata['availability_note'] : '',
            'schedule' => !empty($metadata['schedule']) && is_array($metadata['schedule']) ? $metadata['schedule'] : website_builder_default_service_schedule(),
            'slot_interval_minutes' => !empty($metadata['slot_interval_minutes']) ? (int) $metadata['slot_interval_minutes'] : 30,
            'booking_buffer_minutes' => !empty($metadata['booking_buffer_minutes']) ? (int) $metadata['booking_buffer_minutes'] : 0,
            'min_notice_hours' => isset($metadata['min_notice_hours']) ? (int) $metadata['min_notice_hours'] : 2,
            'max_advance_days' => !empty($metadata['max_advance_days']) ? (int) $metadata['max_advance_days'] : 30,
            'blocked_dates' => !empty($metadata['blocked_dates']) && is_array($metadata['blocked_dates']) ? $metadata['blocked_dates'] : [],
        ];
    }

    return $items;
}

function website_builder_default_service_schedule()
{
    return [
        'mon' => ['enabled' => 1, 'start' => '09:00', 'end' => '17:00'],
        'tue' => ['enabled' => 1, 'start' => '09:00', 'end' => '17:00'],
        'wed' => ['enabled' => 1, 'start' => '09:00', 'end' => '17:00'],
        'thu' => ['enabled' => 1, 'start' => '09:00', 'end' => '17:00'],
        'fri' => ['enabled' => 1, 'start' => '09:00', 'end' => '17:00'],
        'sat' => ['enabled' => 0, 'start' => '10:00', 'end' => '14:00'],
        'sun' => ['enabled' => 0, 'start' => '10:00', 'end' => '14:00'],
    ];
}

function website_builder_weekday_labels()
{
    return [
        'mon' => __('Monday'),
        'tue' => __('Tuesday'),
        'wed' => __('Wednesday'),
        'thu' => __('Thursday'),
        'fri' => __('Friday'),
        'sat' => __('Saturday'),
        'sun' => __('Sunday'),
    ];
}

function website_builder_normalize_time_value($value, $fallback = '09:00')
{
    $value = trim((string) $value);
    if (preg_match('/^\d{2}:\d{2}$/', $value)) {
        return $value;
    }
    return $fallback;
}

function website_builder_normalize_service_schedule($input)
{
    $defaults = website_builder_default_service_schedule();
    foreach ($defaults as $day => $defaultDay) {
        $rawDay = isset($input[$day]) && is_array($input[$day]) ? $input[$day] : [];
        $start = website_builder_normalize_time_value(isset($rawDay['start']) ? $rawDay['start'] : $defaultDay['start'], $defaultDay['start']);
        $end = website_builder_normalize_time_value(isset($rawDay['end']) ? $rawDay['end'] : $defaultDay['end'], $defaultDay['end']);
        if ($end <= $start) {
            $end = $defaultDay['end'];
        }
        $defaults[$day] = [
            'enabled' => !empty($rawDay['enabled']) ? 1 : 0,
            'start' => $start,
            'end' => $end,
        ];
    }
    return $defaults;
}

function website_builder_normalize_blocked_dates($value)
{
    $parts = preg_split('/[\s,]+/', (string) $value);
    $dates = [];
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $part)) {
            $dates[] = $part;
        }
    }
    return array_values(array_unique($dates));
}

function website_builder_get_service($siteId, $serviceId)
{
    foreach (website_builder_get_services($siteId) as $service) {
        if ((int) $service['id'] === (int) $serviceId) {
            return $service;
        }
    }
    return null;
}

function website_builder_booking_status_blocks_slot($status)
{
    return in_array($status, ['pending', 'paid', 'confirmed', 'completed'], true);
}

function website_builder_booking_conflict_exists($siteId, $serviceId, $start, $end, $excludeBookingId = 0)
{
    $rows = ORM::for_table(website_builder_table('website_bookings'))
        ->where('site_id', (int) $siteId)
        ->where('service_id', (int) $serviceId)
        ->find_many();

    $startTs = strtotime($start);
    $endTs = strtotime($end);

    foreach ($rows as $row) {
        if ($excludeBookingId && (int) $row['id'] === (int) $excludeBookingId) {
            continue;
        }
        if (!website_builder_booking_status_blocks_slot($row['status'])) {
            continue;
        }
        $rowStart = !empty($row['booking_start']) ? strtotime($row['booking_start']) : 0;
        $rowEnd = !empty($row['booking_end']) ? strtotime($row['booking_end']) : 0;
        if (!$rowStart || !$rowEnd) {
            continue;
        }
        if ($startTs < $rowEnd && $endTs > $rowStart) {
            return true;
        }
    }

    return false;
}

function website_builder_get_site_blackouts($site)
{
    $content = !empty($site['content']) && is_array($site['content']) ? $site['content'] : [];
    $items = !empty($content['booking_blackouts']) && is_array($content['booking_blackouts']) ? $content['booking_blackouts'] : [];
    $normalized = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $start = !empty($item['start_date']) ? trim((string) $item['start_date']) : '';
        $end = !empty($item['end_date']) ? trim((string) $item['end_date']) : '';
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
            continue;
        }
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) {
            $end = $start;
        }
        if ($end < $start) {
            $end = $start;
        }
        $normalized[] = [
            'id' => !empty($item['id']) ? $item['id'] : uniqid('blk_', true),
            'start_date' => $start,
            'end_date' => $end,
            'label' => !empty($item['label']) ? trim((string) $item['label']) : '',
        ];
    }
    return $normalized;
}

function website_builder_is_date_blocked($site, $date)
{
    foreach (website_builder_get_site_blackouts($site) as $blackout) {
        if ($date >= $blackout['start_date'] && $date <= $blackout['end_date']) {
            return $blackout;
        }
    }
    return false;
}

function website_builder_add_blackout_period($siteId, $startDate, $endDate, $label = '')
{
    $site = website_builder_get_site($siteId);
    if (!$site) {
        return [false, __('Website not found.')];
    }

    $startDate = trim((string) $startDate);
    $endDate = trim((string) $endDate);
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
        return [false, __('Please choose a valid blackout start date.')];
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
        $endDate = $startDate;
    }
    if ($endDate < $startDate) {
        $endDate = $startDate;
    }

    $content = !empty($site['content']) && is_array($site['content']) ? $site['content'] : [];
    $blackouts = website_builder_get_site_blackouts($site);
    $blackouts[] = [
        'id' => uniqid('blk_', true),
        'start_date' => $startDate,
        'end_date' => $endDate,
        'label' => trim((string) $label),
    ];
    $content['booking_blackouts'] = $blackouts;

    $row = ORM::for_table(website_builder_table('website_sites'))->find_one((int) $siteId);
    if (!$row) {
        return [false, __('Website not found.')];
    }
    $row->content_json = json_encode($content);
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    return [true, null];
}

function website_builder_remove_blackout_period($siteId, $blackoutId)
{
    $site = website_builder_get_site($siteId);
    if (!$site) {
        return false;
    }

    $content = !empty($site['content']) && is_array($site['content']) ? $site['content'] : [];
    $blackouts = array_values(array_filter(website_builder_get_site_blackouts($site), function ($item) use ($blackoutId) {
        return $item['id'] !== $blackoutId;
    }));
    $content['booking_blackouts'] = $blackouts;

    $row = ORM::for_table(website_builder_table('website_sites'))->find_one((int) $siteId);
    if (!$row) {
        return false;
    }
    $row->content_json = json_encode($content);
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();
    return true;
}

function website_builder_date_weekday_key($date)
{
    $map = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    $index = (int) date('N', strtotime($date)) - 1;
    return isset($map[$index]) ? $map[$index] : 'mon';
}

function website_builder_get_service_slots($site, $service, $date, $excludeBookingId = 0)
{
    $date = trim((string) $date);
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        return ['success' => false, 'message' => __('Choose a valid date first.'), 'slots' => []];
    }

    $today = date('Y-m-d');
    if ($date < $today) {
        return ['success' => false, 'message' => __('Past dates are not available.'), 'slots' => []];
    }

    $minNoticeHours = max(0, (int) $service['min_notice_hours']);
    $maxAdvanceDays = max(1, (int) $service['max_advance_days']);
    $blockedDates = !empty($service['blocked_dates']) ? (array) $service['blocked_dates'] : [];
    if (in_array($date, $blockedDates, true)) {
        return ['success' => false, 'message' => __('This date is blocked for bookings.'), 'slots' => []];
    }
    $siteBlackout = website_builder_is_date_blocked($site, $date);
    if ($siteBlackout) {
        $message = !empty($siteBlackout['label'])
            ? sprintf(__('This date is unavailable: %s'), $siteBlackout['label'])
            : __('This date is blocked for bookings.');
        return ['success' => false, 'message' => $message, 'slots' => []];
    }

    $daysAhead = floor((strtotime($date . ' 00:00:00') - strtotime($today . ' 00:00:00')) / 86400);
    if ($daysAhead > $maxAdvanceDays) {
        return ['success' => false, 'message' => sprintf(__('Bookings are only open %d days ahead.'), $maxAdvanceDays), 'slots' => []];
    }

    $weekdayKey = website_builder_date_weekday_key($date);
    $schedule = !empty($service['schedule'][$weekdayKey]) ? $service['schedule'][$weekdayKey] : null;
    if (empty($schedule) || empty($schedule['enabled'])) {
        return ['success' => false, 'message' => __('This service is not available on the selected day.'), 'slots' => []];
    }

    $startTimestamp = strtotime($date . ' ' . $schedule['start'] . ':00');
    $endTimestamp = strtotime($date . ' ' . $schedule['end'] . ':00');
    if (!$startTimestamp || !$endTimestamp || $endTimestamp <= $startTimestamp) {
        return ['success' => false, 'message' => __('Working hours are not configured correctly for this day.'), 'slots' => []];
    }

    $interval = max(15, (int) $service['slot_interval_minutes']);
    $buffer = max(0, (int) $service['booking_buffer_minutes']);
    $duration = max(15, (int) $service['duration_minutes']);
    $cutoff = time() + ($minNoticeHours * 3600);
    $slots = [];

    for ($slotStart = $startTimestamp; $slotStart + ($duration * 60) <= $endTimestamp; $slotStart += ($interval * 60)) {
        $slotEnd = $slotStart + (($duration + $buffer) * 60);
        if ($slotStart < $cutoff) {
            continue;
        }
        $bookingEnd = $slotStart + ($duration * 60);
        $slotValue = date('Y-m-d H:i:s', $slotStart);
        if (website_builder_booking_conflict_exists($site['id'], $service['id'], $slotValue, date('Y-m-d H:i:s', $slotEnd), $excludeBookingId)) {
            continue;
        }
        $slots[] = [
            'value' => $slotValue,
            'label' => date('h:i A', $slotStart) . ' - ' . date('h:i A', $bookingEnd),
            'end' => date('Y-m-d H:i:s', $bookingEnd),
        ];
    }

    if (empty($slots)) {
        return ['success' => false, 'message' => __('No open booking slots are available for that date.'), 'slots' => []];
    }

    return ['success' => true, 'message' => '', 'slots' => $slots];
}

function website_builder_validate_booking_slot($site, $service, $start, $excludeBookingId = 0)
{
    if (empty($start) || !preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $start)) {
        return [false, __('Choose a valid booking slot.'), null];
    }

    $slotCheck = website_builder_get_service_slots($site, $service, date('Y-m-d', strtotime($start)), $excludeBookingId);
    if (empty($slotCheck['success'])) {
        return [false, !empty($slotCheck['message']) ? $slotCheck['message'] : __('This slot is not available.'), null];
    }

    $allowed = [];
    foreach ($slotCheck['slots'] as $slot) {
        $allowed[$slot['value']] = $slot;
    }
    if (empty($allowed[$start])) {
        return [false, __('The selected slot is no longer available. Please choose another one.'), null];
    }

    return [true, '', $allowed[$start]['end']];
}

function website_builder_save_product($siteId, array $data, $productId = 0)
{
    $table = website_builder_table('website_products');
    $row = $productId
        ? ORM::for_table($table)->where('site_id', (int) $siteId)->where('id', (int) $productId)->find_one()
        : null;

    if (!$row) {
        $row = ORM::for_table($table)->create();
        $row->site_id = (int) $siteId;
        $row->created_at = date('Y-m-d H:i:s');
    }

    $row->title = $data['title'];
    $row->slug = website_builder_slugify($data['title']);
    $row->description = $data['description'];
    $row->price = (float) $data['price'];
    $row->currency = !empty($data['currency']) ? $data['currency'] : 'USD';
    $row->status = !empty($data['status']) ? $data['status'] : 'active';
    $existingMeta = !empty($row['metadata_json']) ? json_decode((string) $row['metadata_json'], true) : [];
    if (!is_array($existingMeta)) {
        $existingMeta = [];
    }
    $row->metadata_json = json_encode(array_merge($existingMeta, [
        'source' => 'atlas_editor',
        'availability_note' => !empty($data['availability_note']) ? $data['availability_note'] : '',
    ]));
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    return (int) $row->id;
}

function website_builder_save_service($siteId, array $data, $serviceId = 0)
{
    $table = website_builder_table('website_services');
    $row = $serviceId
        ? ORM::for_table($table)->where('site_id', (int) $siteId)->where('id', (int) $serviceId)->find_one()
        : null;

    if (!$row) {
        $row = ORM::for_table($table)->create();
        $row->site_id = (int) $siteId;
        $row->created_at = date('Y-m-d H:i:s');
    }

    $row->title = $data['title'];
    $row->slug = website_builder_slugify($data['title']);
    $row->description = $data['description'];
    $row->duration_minutes = (int) $data['duration_minutes'];
    $row->price = (float) $data['price'];
    $row->currency = !empty($data['currency']) ? $data['currency'] : 'USD';
    $row->status = !empty($data['status']) ? $data['status'] : 'active';
    $existingMeta = !empty($row['metadata_json']) ? json_decode((string) $row['metadata_json'], true) : [];
    if (!is_array($existingMeta)) {
        $existingMeta = [];
    }
    $row->metadata_json = json_encode(array_merge($existingMeta, [
        'source' => 'atlas_editor',
        'availability_note' => !empty($data['availability_note']) ? $data['availability_note'] : '',
        'schedule' => website_builder_normalize_service_schedule(!empty($data['schedule']) ? $data['schedule'] : []),
        'slot_interval_minutes' => max(15, (int) (!empty($data['slot_interval_minutes']) ? $data['slot_interval_minutes'] : 30)),
        'booking_buffer_minutes' => max(0, (int) (!empty($data['booking_buffer_minutes']) ? $data['booking_buffer_minutes'] : 0)),
        'min_notice_hours' => max(0, (int) (!empty($data['min_notice_hours']) ? $data['min_notice_hours'] : 2)),
        'max_advance_days' => max(1, (int) (!empty($data['max_advance_days']) ? $data['max_advance_days'] : 30)),
        'blocked_dates' => website_builder_normalize_blocked_dates(!empty($data['blocked_dates']) ? $data['blocked_dates'] : ''),
    ]));
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    return (int) $row->id;
}

function website_builder_delete_product($siteId, $productId)
{
    return ORM::for_table(website_builder_table('website_products'))
        ->where('site_id', (int) $siteId)
        ->where('id', (int) $productId)
        ->delete_many();
}

function website_builder_delete_service($siteId, $serviceId)
{
    return ORM::for_table(website_builder_table('website_services'))
        ->where('site_id', (int) $siteId)
        ->where('id', (int) $serviceId)
        ->delete_many();
}

function website_builder_add_wallet_entry($siteId, $userId, $entryType, $amount, $referenceType, $referenceId, $description, $status = 'pending')
{
    $row = ORM::for_table(website_builder_table('website_wallet_ledger'))->create();
    $row->site_id = (int) $siteId;
    $row->user_id = (int) $userId;
    $row->entry_type = $entryType;
    $row->amount = (float) $amount;
    $row->currency = 'USD';
    $row->reference_type = $referenceType;
    $row->reference_id = (int) $referenceId;
    $row->description = $description;
    $row->status = $status;
    $row->created_at = date('Y-m-d H:i:s');
    $row->save();

    return (int) $row->id;
}

function website_builder_create_order_request($site, $productId, array $payload)
{
    $product = ORM::for_table(website_builder_table('website_products'))
        ->where('site_id', (int) $site['id'])
        ->where('id', (int) $productId)
        ->find_one();

    if (!$product) {
        return [false, __('Product not found.')];
    }

    $row = ORM::for_table(website_builder_table('website_orders'))->create();
    $row->site_id = (int) $site['id'];
    $row->customer_name = $payload['customer_name'];
    $row->customer_email = $payload['customer_email'];
    $row->amount = (float) $product['price'];
    $row->currency = $product['currency'];
    $row->status = 'pending';
    $row->metadata_json = json_encode([
        'product_id' => (int) $product['id'],
        'product_title' => $product['title'],
        'notes' => !empty($payload['notes']) ? $payload['notes'] : '',
        'capture_mode' => 'atlas_checkout_placeholder',
    ]);
    $row->created_at = date('Y-m-d H:i:s');
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    website_builder_add_wallet_entry($site['id'], $site['user_id'], 'website_order', (float) $product['price'], 'order', (int) $row->id, sprintf(__('Pending order for %s'), $product['title']));

    return [(int) $row->id, null];
}

function website_builder_create_booking_request($site, $serviceId, array $payload)
{
    $serviceRow = ORM::for_table(website_builder_table('website_services'))
        ->where('site_id', (int) $site['id'])
        ->where('id', (int) $serviceId)
        ->find_one();

    if (!$serviceRow) {
        return [false, __('Service not found.')];
    }

    $service = website_builder_get_service($site['id'], $serviceId);
    if (empty($service)) {
        return [false, __('Service not found.')];
    }

    $start = !empty($payload['booking_start']) ? $payload['booking_start'] : null;
    list($slotOk, $slotMessage, $end) = website_builder_validate_booking_slot($site, $service, $start);
    if (!$slotOk) {
        return [false, $slotMessage];
    }

    $bufferedEnd = date('Y-m-d H:i:s', strtotime($end . ' +' . (int) $service['booking_buffer_minutes'] . ' minutes'));
    if (website_builder_booking_conflict_exists($site['id'], $service['id'], $start, $bufferedEnd)) {
        return [false, __('That time slot was just booked. Please choose another one.')];
    }

    $row = ORM::for_table(website_builder_table('website_bookings'))->create();
    $row->site_id = (int) $site['id'];
    $row->service_id = (int) $serviceRow['id'];
    $row->customer_name = $payload['customer_name'];
    $row->customer_email = $payload['customer_email'];
    $row->booking_start = $start;
    $row->booking_end = $end;
    $row->status = 'pending';
    $row->metadata_json = json_encode([
        'service_title' => $service['title'],
        'notes' => !empty($payload['notes']) ? $payload['notes'] : '',
        'capture_mode' => 'atlas_booking_placeholder',
    ]);
    $row->created_at = date('Y-m-d H:i:s');
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    website_builder_add_wallet_entry($site['id'], $site['user_id'], 'website_booking', (float) $service['price'], 'booking', (int) $row->id, sprintf(__('Pending booking for %s'), $service['title']));

    return [(int) $row->id, null];
}

function website_builder_get_upcoming_bookings($siteId, $limit = 12)
{
    $items = [];
    foreach (website_builder_get_bookings($siteId) as $booking) {
        if (empty($booking['booking_start'])) {
            continue;
        }
        if (strtotime($booking['booking_start']) < strtotime('-1 day')) {
            continue;
        }
        if (!website_builder_booking_status_blocks_slot($booking['status'])) {
            continue;
        }
        $items[] = $booking;
    }

    usort($items, function ($a, $b) {
        return strtotime($a['booking_start']) <=> strtotime($b['booking_start']);
    });

    return array_slice($items, 0, $limit);
}

function website_builder_get_wallet_summary($siteId)
{
    $rows = ORM::for_table(website_builder_table('website_wallet_ledger'))
        ->where('site_id', (int) $siteId)
        ->find_many();

    $summary = [
        'pending' => 0,
        'posted' => 0,
        'count' => 0,
    ];

    foreach ($rows as $row) {
        $amount = (float) $row['amount'];
        $summary['count']++;
        if ($row['status'] === 'posted') {
            $summary['posted'] += $amount;
        } else {
            $summary['pending'] += $amount;
        }
    }

    $payoutRows = ORM::for_table(website_builder_table('website_payouts'))
        ->where('site_id', (int) $siteId)
        ->find_many();
    $summary['requested'] = 0;
    $summary['paid_out'] = 0;
    foreach ($payoutRows as $row) {
        $amount = (float) $row['amount'];
        if ($row['status'] === 'paid') {
            $summary['paid_out'] += $amount;
        } elseif ($row['status'] !== 'rejected') {
            $summary['requested'] += $amount;
        }
    }
    $summary['available'] = max(0, $summary['posted'] - $summary['requested'] - $summary['paid_out']);

    return $summary;
}

function website_builder_get_orders($siteId)
{
    $rows = ORM::for_table(website_builder_table('website_orders'))
        ->where('site_id', (int) $siteId)
        ->order_by_desc('id')
        ->find_many();

    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'id' => (int) $row['id'],
            'customer_name' => $row['customer_name'],
            'customer_email' => $row['customer_email'],
            'amount' => (float) $row['amount'],
            'currency' => $row['currency'],
            'status' => $row['status'],
        'metadata' => json_decode((string) $row['metadata_json'], true) ?: [],
        'created_at' => $row['created_at'],
        ];
    }

    return $items;
}

function website_builder_get_booking($siteId, $bookingId)
{
    foreach (website_builder_get_bookings($siteId) as $booking) {
        if ((int) $booking['id'] === (int) $bookingId) {
            return $booking;
        }
    }
    return null;
}

function website_builder_get_bookings($siteId)
{
    $rows = ORM::for_table(website_builder_table('website_bookings'))
        ->where('site_id', (int) $siteId)
        ->order_by_desc('id')
        ->find_many();

    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'id' => (int) $row['id'],
            'service_id' => (int) $row['service_id'],
            'customer_name' => $row['customer_name'],
            'customer_email' => $row['customer_email'],
            'booking_start' => $row['booking_start'],
            'booking_end' => $row['booking_end'],
            'status' => $row['status'],
            'metadata' => json_decode((string) $row['metadata_json'], true) ?: [],
            'created_at' => $row['created_at'],
        ];
    }

    return $items;
}

function website_builder_prepare_payment_session($site, $requestType, $requestId)
{
    global $link;

    $accessToken = uniqid('web_', true);

    if ($requestType === 'website_order') {
        $request = ORM::for_table(website_builder_table('website_orders'))
            ->where('site_id', (int) $site['id'])
            ->where('id', (int) $requestId)
            ->find_one();
        if (!$request) {
            return [false, __('Order request not found.')];
        }

        $meta = json_decode((string) $request['metadata_json'], true) ?: [];
        $title = !empty($meta['product_title']) ? $meta['product_title'] : __('Website order');
        $amount = (float) $request['amount'];
    } else {
        $request = ORM::for_table(website_builder_table('website_bookings'))
            ->where('site_id', (int) $site['id'])
            ->where('id', (int) $requestId)
            ->find_one();
        if (!$request) {
            return [false, __('Booking request not found.')];
        }

        $meta = json_decode((string) $request['metadata_json'], true) ?: [];
        $title = !empty($meta['service_title']) ? $meta['service_title'] : __('Website booking');
        $service = ORM::for_table(website_builder_table('website_services'))->find_one((int) $request['service_id']);
        $amount = $service ? (float) $service['price'] : 0;
    }

    $_SESSION['quickad'][$accessToken] = [
        'name' => $title,
        'amount' => $amount,
        'base_amount' => $amount,
        'folder' => 'stripe',
        'payment_type' => $requestType,
        'product_id' => (int) $requestId,
        'user_id' => (int) $site['user_id'],
        'website_site_id' => (int) $site['id'],
        'return_url' => $config['site_url'] . 'site/' . (!empty($site['slug']) ? $site['slug'] : $site['subdomain']) . '?checkout=success',
        'cancel_url' => $config['site_url'] . 'site/' . (!empty($site['slug']) ? $site['slug'] : $site['subdomain']) . '?checkout=cancel',
        'trans_desc' => $requestType === 'website_order'
            ? sprintf(__('Website order for %s'), $title)
            : sprintf(__('Website booking for %s'), $title),
    ];

    return [$accessToken, null];
}

function website_builder_update_order_status($siteId, $orderId, $status)
{
    $allowed = ['pending', 'paid', 'processing', 'fulfilled', 'canceled'];
    if (!in_array($status, $allowed, true)) {
        return false;
    }

    $row = ORM::for_table(website_builder_table('website_orders'))
        ->where('site_id', (int) $siteId)
        ->find_one((int) $orderId);
    if (!$row) {
        return false;
    }
    $row->status = $status;
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();
    return true;
}

function website_builder_update_booking_status($siteId, $bookingId, $status)
{
    $allowed = ['pending', 'paid', 'confirmed', 'completed', 'canceled'];
    if (!in_array($status, $allowed, true)) {
        return false;
    }

    $row = ORM::for_table(website_builder_table('website_bookings'))
        ->where('site_id', (int) $siteId)
        ->find_one((int) $bookingId);
    if (!$row) {
        return false;
    }
    $row->status = $status;
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();
    return true;
}

function website_builder_ensure_booking_reschedule_token($siteId, $bookingId)
{
    $row = ORM::for_table(website_builder_table('website_bookings'))
        ->where('site_id', (int) $siteId)
        ->find_one((int) $bookingId);
    if (!$row) {
        return '';
    }
    $meta = !empty($row['metadata_json']) ? json_decode((string) $row['metadata_json'], true) : [];
    if (!is_array($meta)) {
        $meta = [];
    }
    if (empty($meta['reschedule_token'])) {
        $meta['reschedule_token'] = bin2hex(random_bytes(16));
        $row->metadata_json = json_encode($meta);
        $row->updated_at = date('Y-m-d H:i:s');
        $row->save();
    }
    return $meta['reschedule_token'];
}

function website_builder_get_booking_reschedule_url($site, $booking)
{
    $token = website_builder_ensure_booking_reschedule_token($site['id'], $booking['id']);
    return website_builder_get_site_public_url($site) . '?reschedule=1&booking=' . (int) $booking['id'] . '&token=' . urlencode($token);
}

function website_builder_get_booking_for_reschedule($siteId, $bookingId, $token)
{
    $booking = website_builder_get_booking($siteId, $bookingId);
    if (!$booking) {
        return null;
    }
    $meta = !empty($booking['metadata']) ? $booking['metadata'] : [];
    if (empty($meta['reschedule_token']) || !hash_equals((string) $meta['reschedule_token'], (string) $token)) {
        return null;
    }
    return $booking;
}

function website_builder_reschedule_booking($site, $bookingId, $newStart, $actor = 'owner', $token = '')
{
    $booking = $actor === 'customer'
        ? website_builder_get_booking_for_reschedule($site['id'], $bookingId, $token)
        : website_builder_get_booking($site['id'], $bookingId);

    if (!$booking) {
        return [false, __('Booking not found.')];
    }

    $service = website_builder_get_service($site['id'], $booking['service_id']);
    if (!$service) {
        return [false, __('Service not found.')];
    }

    list($slotOk, $slotMessage, $end) = website_builder_validate_booking_slot($site, $service, $newStart, $booking['id']);
    if (!$slotOk) {
        return [false, $slotMessage];
    }

    $row = ORM::for_table(website_builder_table('website_bookings'))
        ->where('site_id', (int) $site['id'])
        ->find_one((int) $bookingId);
    if (!$row) {
        return [false, __('Booking not found.')];
    }

    $meta = !empty($row['metadata_json']) ? json_decode((string) $row['metadata_json'], true) : [];
    if (!is_array($meta)) {
        $meta = [];
    }
    $meta['rescheduled_at'] = date('Y-m-d H:i:s');
    $meta['rescheduled_by'] = $actor;
    $meta['previous_booking_start'] = $booking['booking_start'];
    $meta['previous_booking_end'] = $booking['booking_end'];
    $row->booking_start = $newStart;
    $row->booking_end = $end;
    if ($row->status === 'pending') {
        $row->status = 'confirmed';
    }
    $row->metadata_json = json_encode($meta);
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    return [website_builder_get_booking($site['id'], $bookingId), null];
}

function website_builder_build_booking_calendar($siteId, $month = '')
{
    $month = preg_match('/^\d{4}-\d{2}$/', (string) $month) ? $month : date('Y-m');
    $firstDay = strtotime($month . '-01');
    $startOffset = (int) date('N', $firstDay) - 1;
    $gridStart = strtotime('-' . $startOffset . ' days', $firstDay);
    $site = website_builder_get_site($siteId);
    $bookings = website_builder_get_bookings($siteId);
    $days = [];

    for ($i = 0; $i < 42; $i++) {
        $timestamp = strtotime('+' . $i . ' days', $gridStart);
        $date = date('Y-m-d', $timestamp);
        $dayBookings = [];
        foreach ($bookings as $booking) {
            if (!empty($booking['booking_start']) && date('Y-m-d', strtotime($booking['booking_start'])) === $date) {
                $dayBookings[] = $booking;
            }
        }
        $days[] = [
            'date' => $date,
            'day' => date('j', $timestamp),
            'in_month' => date('Y-m', $timestamp) === $month,
            'is_today' => $date === date('Y-m-d'),
            'booking_count' => count($dayBookings),
            'bookings' => $dayBookings,
            'blackout' => $site ? website_builder_is_date_blocked($site, $date) : false,
        ];
    }

    return [
        'month' => $month,
        'label' => date('F Y', $firstDay),
        'days' => $days,
        'prev_month' => date('Y-m', strtotime('-1 month', $firstDay)),
        'next_month' => date('Y-m', strtotime('+1 month', $firstDay)),
    ];
}

function website_builder_build_booking_week($siteId, $startDate = '')
{
    $startDate = preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $startDate) ? $startDate : date('Y-m-d');
    $site = website_builder_get_site($siteId);
    $bookings = website_builder_get_bookings($siteId);
    $days = [];
    for ($i = 0; $i < 7; $i++) {
        $timestamp = strtotime($startDate . ' +' . $i . ' days');
        $date = date('Y-m-d', $timestamp);
        $dayBookings = [];
        foreach ($bookings as $booking) {
            if (!empty($booking['booking_start']) && date('Y-m-d', strtotime($booking['booking_start'])) === $date) {
                $dayBookings[] = $booking;
            }
        }
        usort($dayBookings, function ($a, $b) {
            return strtotime($a['booking_start']) <=> strtotime($b['booking_start']);
        });
        $days[] = [
            'date' => $date,
            'label' => date('D, d M', $timestamp),
            'bookings' => $dayBookings,
            'blackout' => $site ? website_builder_is_date_blocked($site, $date) : false,
        ];
    }
    return $days;
}

function website_builder_get_payouts($siteId)
{
    $rows = ORM::for_table(website_builder_table('website_payouts'))
        ->where('site_id', (int) $siteId)
        ->order_by_desc('id')
        ->find_many();

    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'id' => (int) $row['id'],
            'amount' => (float) $row['amount'],
            'currency' => $row['currency'],
            'payment_method' => $row['payment_method'],
            'account_details' => $row['account_details'],
            'status' => $row['status'],
            'notes' => $row['notes'],
            'created_at' => $row['created_at'],
        ];
    }

    return $items;
}

function website_builder_request_payout($site, array $payload)
{
    global $config;

    $wallet = website_builder_get_wallet_summary($site['id']);
    $amount = (float) $payload['amount'];
    $minimum = (float) get_option('affiliate_minimum_payout', 0);

    if ($amount <= 0) {
        return [false, __('Amount is not valid.')];
    }
    if ($minimum > 0 && $amount < $minimum) {
        return [false, __("Minimum withdrawal amount is:") . price_format($minimum)];
    }
    if ($amount > $wallet['available']) {
        return [false, __('Insufficient available earnings for this payout request.')];
    }
    if (empty($payload['payment_method']) || empty($payload['account_details'])) {
        return [false, __('Payment details are required.')];
    }

    $row = ORM::for_table(website_builder_table('website_payouts'))->create();
    $row->site_id = (int) $site['id'];
    $row->user_id = (int) $site['user_id'];
    $row->amount = $amount;
    $row->currency = 'USD';
    $row->payment_method = $payload['payment_method'];
    $row->account_details = $payload['account_details'];
    $row->status = 'pending';
    $row->notes = !empty($payload['notes']) ? $payload['notes'] : '';
    $row->created_at = date('Y-m-d H:i:s');
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    $owner = website_builder_get_owner($site['user_id']);
    if (!empty($config['admin_email'])) {
        $subject = sprintf(__('New website payout request from %s'), $site['site_name']);
        $body = nl2br(escape(sprintf(
            __("Website: %s\nOwner: %s\nAmount: %s\nMethod: %s\n\nReview it in the admin Website Payouts queue."),
            $site['site_name'],
            $owner ? ($owner['name'] ?: $owner['username']) : __('Unknown owner'),
            price_format($amount),
            $payload['payment_method']
        )));
        website_builder_send_email_message($config['admin_email'], $config['site_title'], $subject, $body);
    }

    return [(int) $row->id, null];
}

function website_builder_get_owner($userId)
{
    global $config;

    $user = ORM::for_table($config['db']['pre'] . 'user')
        ->where('id', (int) $userId)
        ->find_one();

    if (!$user) {
        return null;
    }

    return [
        'id' => (int) $user['id'],
        'name' => $user['name'],
        'username' => $user['username'],
        'email' => $user['email'],
    ];
}

function website_builder_get_site_public_url($site)
{
    global $link;
    global $config;
    return $config['site_url'] . 'site/' . (!empty($site['slug']) ? $site['slug'] : $site['subdomain']);
}

function website_builder_send_email_message($toEmail, $toName, $subject, $body)
{
    if (empty($toEmail) || empty($subject) || empty($body)) {
        return false;
    }

    email($toEmail, $toName ?: $toEmail, $subject, $body);
    return true;
}

function website_builder_send_customer_confirmation($site, $requestType, $request)
{
    global $config;

    if (empty($site) || empty($request) || empty($request['customer_email'])) {
        return false;
    }

    $siteUrl = website_builder_get_site_public_url($site);
    $siteName = !empty($site['site_name']) ? $site['site_name'] : $config['site_title'];
    $customerName = !empty($request['customer_name']) ? $request['customer_name'] : __('Customer');
    $amount = isset($request['amount']) ? price_format($request['amount']) : '';
    $metadata = !empty($request['metadata']) && is_array($request['metadata']) ? $request['metadata'] : [];

    if ($requestType === 'website_order') {
        $itemTitle = !empty($metadata['product_title']) ? $metadata['product_title'] : __('your order');
        $subject = sprintf(__('Your order with %s is confirmed'), $siteName);
        $body = nl2br(escape(sprintf(
            __("Hi %s,\n\nYour order for %s has been confirmed and your payment has been received.\n\nAmount: %s\nWebsite: %s\n\nWe will contact you soon with the next steps.\n\nThanks,\n%s"),
            $customerName,
            $itemTitle,
            $amount,
            $siteUrl,
            $siteName
        )));
    } else {
        $itemTitle = !empty($metadata['service_title']) ? $metadata['service_title'] : __('your booking');
        $requestedTime = !empty($request['booking_start']) ? date('d M Y h:i A', strtotime($request['booking_start'])) : __('To be confirmed');
        $rescheduleUrl = !empty($request['id']) ? website_builder_get_booking_reschedule_url($site, $request) : '';
        $subject = sprintf(__('Your booking with %s is confirmed'), $siteName);
        $bodyText = sprintf(
            __("Hi %s,\n\nYour booking for %s has been confirmed and your payment has been received.\n\nRequested time: %s\nAmount: %s\nWebsite: %s"),
            $customerName,
            $itemTitle,
            $requestedTime,
            $amount,
            $siteUrl
        );
        if (!empty($rescheduleUrl)) {
            $bodyText .= "\n\n" . sprintf(__('Need a different time? Reschedule here: %s'), $rescheduleUrl);
        }
        $bodyText .= "\n\n" . sprintf(__('We will follow up if we need any additional details.\n\nThanks,\n%s'), $siteName);
        $body = nl2br(escape($bodyText));
    }

    return website_builder_send_email_message($request['customer_email'], $customerName, $subject, $body);
}

function website_builder_send_owner_request_notification($site, $requestType, $request)
{
    global $config;

    if (empty($site) || empty($request)) {
        return false;
    }

    $owner = website_builder_get_owner($site['user_id']);
    if (!$owner || empty($owner['email'])) {
        return false;
    }

    $metadata = !empty($request['metadata']) && is_array($request['metadata']) ? $request['metadata'] : [];
    $label = $requestType === 'website_order'
        ? (!empty($metadata['product_title']) ? $metadata['product_title'] : __('Website order'))
        : (!empty($metadata['service_title']) ? $metadata['service_title'] : __('Website booking'));

    $subject = $requestType === 'website_order'
        ? sprintf(__('New paid order on %s'), $site['site_name'])
        : sprintf(__('New paid booking on %s'), $site['site_name']);

    $lines = [
        sprintf(__('Customer: %s'), $request['customer_name']),
        sprintf(__('Email: %s'), $request['customer_email']),
        sprintf(__('Item: %s'), $label),
    ];

    if (!empty($request['amount'])) {
        $lines[] = sprintf(__('Amount: %s'), price_format($request['amount']));
    }
    if ($requestType === 'website_booking' && !empty($request['booking_start'])) {
        $lines[] = sprintf(__('Requested time: %s'), date('d M Y h:i A', strtotime($request['booking_start'])));
    }
    $lines[] = sprintf(__('Website dashboard: %s'), $config['site_url'] . 'your-website/dashboard/' . $site['id']);

    $body = nl2br(escape(implode("\n", $lines)));
    return website_builder_send_email_message($owner['email'], $owner['name'], $subject, $body);
}

function website_builder_send_booking_rescheduled_notifications($site, $booking, $actor = 'owner')
{
    if (empty($site) || empty($booking)) {
        return false;
    }

    $metadata = !empty($booking['metadata']) && is_array($booking['metadata']) ? $booking['metadata'] : [];
    $serviceTitle = !empty($metadata['service_title']) ? $metadata['service_title'] : __('your booking');
    $scheduledTime = !empty($booking['booking_start']) ? date('d M Y h:i A', strtotime($booking['booking_start'])) : __('To be confirmed');
    $customerName = !empty($booking['customer_name']) ? $booking['customer_name'] : __('Customer');
    $siteUrl = website_builder_get_site_public_url($site);
    $rescheduleUrl = website_builder_get_booking_reschedule_url($site, $booking);

    $customerSubject = sprintf(__('Your booking with %s was updated'), $site['site_name']);
    $customerBody = nl2br(escape(sprintf(
        __("Hi %s,\n\nYour booking for %s has been moved to %s.\n\nWebsite: %s\nReschedule again if needed: %s\n\nThanks,\n%s"),
        $customerName,
        $serviceTitle,
        $scheduledTime,
        $siteUrl,
        $rescheduleUrl,
        $site['site_name']
    )));
    website_builder_send_email_message($booking['customer_email'], $customerName, $customerSubject, $customerBody);

    $owner = website_builder_get_owner($site['user_id']);
    if ($owner && !empty($owner['email'])) {
        $ownerSubject = sprintf(__('Booking updated on %s'), $site['site_name']);
        $ownerBody = nl2br(escape(sprintf(
            __("Booking: %s\nCustomer: %s\nNew time: %s\nChanged by: %s\n\nWebsite dashboard: %s"),
            $serviceTitle,
            $customerName,
            $scheduledTime,
            ucfirst($actor),
            $GLOBALS['config']['site_url'] . 'your-website/dashboard/' . $site['id']
        )));
        website_builder_send_email_message($owner['email'], $owner['name'], $ownerSubject, $ownerBody);
    }

    return true;
}

function website_builder_send_payout_status_email($payout, $site, $status, $reason = '')
{
    if (empty($payout) || empty($site)) {
        return false;
    }

    if (!in_array($status, ['paid', 'rejected'], true)) {
        return false;
    }

    $owner = website_builder_get_owner($site['user_id']);
    if (!$owner || empty($owner['email'])) {
        return false;
    }

    global $config;

    $amount = price_format($payout['amount']);
    $method = !empty($payout['payment_method']) ? $payout['payment_method'] : __('Selected method');
    $dashboardUrl = $config['site_url'] . 'your-website/dashboard/' . $site['id'];

    if ($status === 'paid') {
        $subject = sprintf(__('Your website payout from %s was approved'), $site['site_name']);
        $body = nl2br(escape(sprintf(
            __("Hi %s,\n\nYour website payout request for %s via %s has been approved by the Atlas team.\n\nSite: %s\nWebsite dashboard: %s\n\nYou can review your website dashboard for the latest payout status."),
            $owner['name'] ?: $owner['username'],
            $amount,
            $method,
            $site['site_name'],
            $dashboardUrl
        )));
    } else {
        $subject = sprintf(__('Your website payout from %s needs attention'), $site['site_name']);
        $bodyText = sprintf(
            __("Hi %s,\n\nYour website payout request for %s via %s was rejected.\n\nSite: %s"),
            $owner['name'] ?: $owner['username'],
            $amount,
            $method,
            $site['site_name']
        );
        if ($reason !== '') {
            $bodyText .= "\n\n" . sprintf(__('Reason: %s'), $reason);
        }
        $bodyText .= "\n\n" . sprintf(__('Website dashboard: %s'), $dashboardUrl);
        $body = nl2br(escape($bodyText));
    }

    return website_builder_send_email_message($owner['email'], $owner['name'], $subject, $body);
}

function website_builder_update_payout_status($payoutId, $status, $notes = '')
{
    $allowed = ['pending', 'paid', 'rejected'];
    if (!in_array($status, $allowed, true)) {
        return [false, __('Invalid payout status.')];
    }

    $row = ORM::for_table(website_builder_table('website_payouts'))->find_one((int) $payoutId);
    if (!$row) {
        return [false, __('Payout request not found.')];
    }

    $site = website_builder_get_site((int) $row['site_id']);
    if (!$site) {
        return [false, __('Website not found for this payout request.')];
    }

    $row->status = $status;
    $row->notes = $notes;
    $row->updated_at = date('Y-m-d H:i:s');
    $row->save();

    website_builder_send_payout_status_email([
        'amount' => (float) $row['amount'],
        'payment_method' => $row['payment_method'],
    ], $site, $status, $notes);

    return [true, __('Payout request updated successfully.')];
}

function website_builder_format_site($site)
{
    $content = json_decode((string) $site['content_json'], true) ?: [];
    $tokens = json_decode((string) $site['theme_tokens_json'], true) ?: [];
    $colors = json_decode((string) $site['brand_colors_json'], true) ?: [];
    $template = website_builder_get_template($site['template_key']);

    return [
        'id' => (int) $site['id'],
        'user_id' => (int) $site['user_id'],
        'site_name' => $site['site_name'],
        'site_type' => $site['site_type'],
        'template_key' => $site['template_key'],
        'template' => $template,
        'slug' => $site['slug'],
        'subdomain' => $site['subdomain'],
        'custom_domain' => $site['custom_domain'],
        'status' => $site['status'],
        'brand_colors' => $colors,
        'theme_tokens' => $tokens,
        'content' => $content,
        'published_at' => $site['published_at'],
        'created_at' => $site['created_at'],
        'updated_at' => $site['updated_at'],
    ];
}
