<?php

function social_media_runtime_debug($key = null, $value = null)
{
    static $state = [];

    if ($key === null) {
        return $state;
    }

    if ($value === null) {
        return isset($state[$key]) ? $state[$key] : null;
    }

    $state[$key] = $value;
    return $value;
}

function social_media_bootstrap()
{
    static $bootstrapped = false;

    if ($bootstrapped) {
        return;
    }

    global $config;

    ORM::raw_execute("
        CREATE TABLE IF NOT EXISTS `{$config['db']['pre']}social_media_assets` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) DEFAULT NULL,
            `asset_type` varchar(20) NOT NULL DEFAULT 'image',
            `post_type` varchar(20) NOT NULL DEFAULT 'all',
            `file_name` varchar(255) DEFAULT NULL,
            `preview_name` varchar(255) DEFAULT NULL,
            `tags` text DEFAULT NULL,
            `text_position` varchar(30) NOT NULL DEFAULT 'center',
            `status` tinyint(1) NOT NULL DEFAULT 1,
            `width` int(11) DEFAULT NULL,
            `height` int(11) DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    ORM::raw_execute("
        CREATE TABLE IF NOT EXISTS `{$config['db']['pre']}social_media_posts` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) DEFAULT NULL,
            `batch_key` varchar(80) DEFAULT NULL,
            `post_type` varchar(20) DEFAULT NULL,
            `title` varchar(255) DEFAULT NULL,
            `caption` longtext DEFAULT NULL,
            `overlay_text` text DEFAULT NULL,
            `asset_id` int(11) DEFAULT NULL,
            `asset_file` varchar(255) DEFAULT NULL,
            `preview_image` varchar(255) DEFAULT NULL,
            `metadata` longtext DEFAULT NULL,
            `created_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ");

    social_media_ensure_asset_schema();

    $bootstrapped = true;
}

function social_media_ensure_asset_schema()
{
    global $config;

    $table = $config['db']['pre'] . 'social_media_assets';
    $columns = [
        'analysis_json' => "ALTER TABLE `{$table}` ADD `analysis_json` LONGTEXT NULL DEFAULT NULL",
        'manifest_json' => "ALTER TABLE `{$table}` ADD `manifest_json` LONGTEXT NULL DEFAULT NULL",
        'render_preset' => "ALTER TABLE `{$table}` ADD `render_preset` VARCHAR(50) NULL DEFAULT 'auto'",
    ];

    $pdo = ORM::get_db();
    foreach ($columns as $column => $sql) {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `{$table}` LIKE ?");
        $stmt->execute([$column]);
        if (!$stmt->fetch()) {
            ORM::raw_execute($sql);
        }
    }
}

function social_media_make_directory($path)
{
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

function social_media_get_profile($user_id)
{
    $defaults = [
        'founder_name' => '',
        'founder_title' => '',
        'company_name' => '',
        'company_logo' => '',
        'company_website' => '',
        'company_industry' => '',
        'company_description' => '',
        'target_audience' => '',
        'brand_voice' => '',
        'content_goals' => '',
        'key_products' => '',
        'differentiators' => '',
        'instagram_handle' => '',
        'competitors' => [],
        'competitor_notes' => '',
    ];

    $raw = get_user_option($user_id, 'social_company_profile', '');
    if (empty($raw)) {
        return $defaults;
    }

    $profile = json_decode($raw, true);
    if (!is_array($profile)) {
        return $defaults;
    }

    $profile['competitors'] = !empty($profile['competitors']) && is_array($profile['competitors'])
        ? array_values(array_filter(array_map('trim', $profile['competitors'])))
        : [];

    return array_merge($defaults, $profile);
}

function social_media_save_profile($user_id, $data)
{
    $profile = social_media_get_profile($user_id);
    $profile = array_merge($profile, $data);
    $profile['competitors'] = social_media_normalize_list(isset($profile['competitors']) ? $profile['competitors'] : []);
    update_user_option($user_id, 'social_company_profile', json_encode($profile));
    update_user_option($user_id, 'social_company_intelligence', '');
    return $profile;
}

function social_media_normalize_list($value)
{
    if (is_array($value)) {
        $items = $value;
    } else {
        $items = preg_split('/[\r\n,]+/', (string) $value);
    }

    $items = array_map('trim', $items);
    $items = array_values(array_unique(array_filter($items)));

    return $items;
}

function social_media_get_campaign_catalog()
{
    return [
        'brand-awareness' => [
            'label' => 'Brand Awareness',
            'goal' => 'Get as many relevant people as possible to know your brand exists.',
            'focus' => [
                'Reach and impressions',
                'Visual identity',
                'Simple messaging about who you are and what you do',
            ],
            'content_examples' => [
                'Short videos',
                'Lifestyle imagery',
                'Brand story posts',
            ],
            'when_to_use' => [
                'New business',
                'Entering a new market',
                'Launching a new product line',
            ],
        ],
        'engagement' => [
            'label' => 'Engagement',
            'goal' => 'Increase interaction like likes, comments, shares, and saves.',
            'focus' => [
                'Emotional triggers',
                'Conversation starters',
                'Relatability',
            ],
            'content_examples' => [
                'Questions and polls',
                'Memes',
                'Controlled controversial takes',
                'Tag-a-friend posts',
            ],
            'when_to_use' => [
                'Low engagement pages',
                'Building community',
            ],
        ],
        'lead-generation' => [
            'label' => 'Lead Generation',
            'goal' => 'Collect user data such as emails, phone numbers, and signups.',
            'focus' => [
                'Value exchange',
                'Low friction forms',
                'Clear CTA',
            ],
            'content_examples' => [
                'Free guides',
                'Discount codes',
                'Webinars',
            ],
            'when_to_use' => [
                'Service businesses',
                'B2B',
                'Pre-sales funnels',
            ],
        ],
        'conversion-sales' => [
            'label' => 'Conversion / Sales',
            'goal' => 'Drive direct purchases.',
            'focus' => [
                'Strong offer',
                'Urgency and scarcity',
                'Clear product benefits',
            ],
            'content_examples' => [
                'Product demos',
                'Testimonials',
                'Before and after',
                'Limited-time deals',
            ],
            'when_to_use' => [
                'E-commerce',
                'Proven product-market fit',
            ],
        ],
        'retargeting' => [
            'label' => 'Retargeting',
            'goal' => 'Convert people who already interacted but did not buy.',
            'focus' => [
                'Reminder plus trust building',
                'Objection handling',
                'Incentives',
            ],
            'content_examples' => [
                'Still thinking about this posts',
                'Reviews',
                'Cart abandonment ads',
            ],
            'when_to_use' => [
                'Always-on high ROI campaigns',
            ],
        ],
        'traffic' => [
            'label' => 'Traffic',
            'goal' => 'Drive users to a website, app, or landing page.',
            'focus' => [
                'Click-through rate',
                'Curiosity hooks',
                'Strong headlines',
            ],
            'content_examples' => [
                'Blog links',
                'Learn more posts',
                'News-style content',
            ],
            'when_to_use' => [
                'Content marketing',
                'SEO support',
            ],
        ],
        'influencer-collaboration' => [
            'label' => 'Influencer / Collaboration',
            'goal' => 'Leverage existing audiences for trust and reach.',
            'focus' => [
                'Authenticity',
                'Audience alignment',
                'Social proof',
            ],
            'content_examples' => [
                'Influencer reviews',
                'Co-branded posts',
                'Giveaways',
            ],
            'when_to_use' => [
                'Fast brand growth',
                'Entering niche communities',
            ],
        ],
        'product-launch' => [
            'label' => 'Product Launch',
            'goal' => 'Create hype and an initial sales spike.',
            'focus' => [
                'Teasing',
                'Countdown',
                'Exclusivity',
            ],
            'content_examples' => [
                'Coming soon posts',
                'Behind the scenes',
                'Early access offers',
            ],
            'when_to_use' => [
                'New product or service release',
            ],
        ],
        'educational-value' => [
            'label' => 'Educational / Value',
            'goal' => 'Build authority and trust.',
            'focus' => [
                'Teaching',
                'Solving problems',
                'Positioning as expert',
            ],
            'content_examples' => [
                'How-to posts',
                'Tips and tricks',
                'Industry insights',
            ],
            'when_to_use' => [
                'Long-term brand building',
                'High-ticket offers',
            ],
        ],
        'loyalty-retention' => [
            'label' => 'Loyalty / Retention',
            'goal' => 'Keep existing customers and increase repeat purchases.',
            'focus' => [
                'Relationship',
                'Rewards',
                'Community',
            ],
            'content_examples' => [
                'Exclusive discounts',
                'VIP content',
                'User-generated content',
            ],
            'when_to_use' => [
                'After you start getting customers',
            ],
        ],
    ];
}

function social_media_get_funnel_stage_catalog()
{
    return [
        'awareness' => 'Awareness - Who are you?',
        'engagement' => 'Engagement - I like you',
        'trust' => 'Trust - I believe you',
        'conversion' => 'Conversion - I buy',
        'retargeting' => 'Retargeting - I was unsure, now I buy',
        'loyalty' => 'Loyalty - I buy again',
    ];
}

function social_media_get_instagram_grid_catalog()
{
    return [
        'bold_monochrome_ad_copy' => [
            'label' => 'Bold Monochrome Ad Copy Grid',
            'description' => 'High-contrast conversion grid with bold text tiles alternating against relevant product and lifestyle imagery.',
            'objective' => 'conversion',
            'layout' => ['text', 'image', 'text', 'image', 'text', 'image', 'text', 'image', 'text'],
            'background_tone' => 'bold',
            'headline_font_key' => 'bebas-neue',
            'body_font_key' => 'inter',
            'text_case' => 'uppercase',
            'text_align' => 'center',
            'palette' => ['#D84315', '#FF5722', '#F4A261'],
            'image_keywords' => ['product', 'lifestyle', 'detail', 'commercial'],
        ],
        'checkerboard_affirmation' => [
            'label' => 'Checkerboard Affirmation Lifestyle Grid',
            'description' => 'Soft identity-led checkerboard grid alternating reflective text tiles with calm lifestyle imagery.',
            'objective' => 'awareness',
            'layout' => ['image', 'text', 'image', 'text', 'image', 'text', 'image', 'text', 'image'],
            'background_tone' => 'soft',
            'headline_font_key' => 'playfair-display',
            'body_font_key' => 'lora',
            'text_case' => 'sentence',
            'text_align' => 'center',
            'palette' => ['#EADCD6', '#F5EDEA', '#D8B7A6'],
            'image_keywords' => ['lifestyle', 'editorial', 'minimal', 'soft'],
        ],
        'central_spine_moodboard' => [
            'label' => 'Central Spine Moodboard Grid',
            'description' => 'A moodboard grid with a locked text spine in the middle column and aesthetic imagery on both sides.',
            'objective' => 'identity',
            'layout' => ['image', 'text', 'image', 'image', 'text', 'image', 'image', 'text', 'image'],
            'background_tone' => 'minimal',
            'headline_font_key' => 'josefin-sans',
            'body_font_key' => 'raleway',
            'text_case' => 'lowercase',
            'text_align' => 'center',
            'palette' => ['#EDEDED', '#F4A6A6', '#6FA3B8'],
            'image_keywords' => ['moodboard', 'aesthetic', 'editorial', 'minimal'],
        ],
        'authority_hook_grid' => [
            'label' => 'Authority Hook Grid',
            'description' => 'Text-heavy authority grid using bold editorial hooks with occasional proof imagery to humanize the brand.',
            'objective' => 'authority',
            'layout' => ['text', 'text', 'image', 'text', 'image', 'text', 'text', 'text', 'image'],
            'background_tone' => 'minimal',
            'headline_font_key' => 'abril-fatface',
            'body_font_key' => 'dm-sans',
            'text_case' => 'sentence',
            'text_align' => 'center',
            'palette' => ['#F4F4F2', '#D9D9D9', '#FFFFFF'],
            'image_keywords' => ['founder', 'lifestyle', 'editorial', 'proof'],
        ],
        'nature_wellness_editorial' => [
            'label' => 'Nature Wellness Editorial Grid',
            'description' => 'Checkerboard editorial system that pairs calm nature visuals with educational brand panels.',
            'objective' => 'education',
            'layout' => ['image', 'text', 'image', 'text', 'image', 'text', 'image', 'text', 'image'],
            'background_tone' => 'earthy',
            'headline_font_key' => 'playfair-display',
            'body_font_key' => 'nunito-sans',
            'text_case' => 'sentence',
            'text_align' => 'center',
            'palette' => ['#1F2A1F', '#EDEBE6', '#4A5A44'],
            'image_keywords' => ['nature', 'wellness', 'slow living', 'editorial'],
        ],
        'vertical_phrase_narrative' => [
            'label' => 'Vertical Phrase Narrative Grid',
            'description' => 'A storytelling grid with a vertical phrase down the center column and warm lifestyle imagery on both sides.',
            'objective' => 'identity',
            'layout' => ['image', 'text', 'image', 'image', 'text', 'image', 'image', 'text', 'image'],
            'background_tone' => 'warm',
            'headline_font_key' => 'cormorant-garamond',
            'body_font_key' => 'karla',
            'text_case' => 'title',
            'text_align' => 'center',
            'palette' => ['#CFC7C0', '#A98F7A', '#E8E1D9'],
            'image_keywords' => ['cozy', 'lifestyle', 'warm', 'vintage'],
        ],
    ];
}

function social_media_choose_instagram_grid_template($profile, $campaignType = '', $preferredStyle = 'auto')
{
    $catalog = social_media_get_instagram_grid_catalog();
    if (!empty($preferredStyle) && $preferredStyle !== 'auto' && !empty($catalog[$preferredStyle])) {
        return [$preferredStyle, $catalog[$preferredStyle]];
    }

    $industry = strtolower(trim((string) ($profile['company_industry'] ?? '')));
    if (preg_match('/wellness|spa|beauty|skincare|nature/', $industry)) {
        return ['nature_wellness_editorial', $catalog['nature_wellness_editorial']];
    }

    $map = [
        'brand_awareness' => 'checkerboard_affirmation',
        'engagement' => 'vertical_phrase_narrative',
        'lead_generation' => 'authority_hook_grid',
        'conversion' => 'bold_monochrome_ad_copy',
        'retargeting' => 'authority_hook_grid',
        'traffic' => 'central_spine_moodboard',
        'influencer_collaboration' => 'checkerboard_affirmation',
        'product_launch' => 'bold_monochrome_ad_copy',
        'educational_value' => 'nature_wellness_editorial',
        'loyalty_retention' => 'vertical_phrase_narrative',
    ];

    $key = !empty($map[$campaignType]) ? $map[$campaignType] : 'central_spine_moodboard';
    return [$key, $catalog[$key]];
}

function social_media_get_selection_options($catalog, $field)
{
    $options = [];
    foreach ($catalog as $item) {
        if (!empty($item[$field]) && is_array($item[$field])) {
            foreach ($item[$field] as $value) {
                $key = md5($value);
                $options[$key] = $value;
            }
        }
    }
    asort($options);
    return $options;
}

function social_media_build_campaign_brief($input)
{
    $catalog = social_media_get_campaign_catalog();
    $funnelStages = social_media_get_funnel_stage_catalog();
    $campaignType = !empty($input['campaign_type']) && isset($catalog[$input['campaign_type']]) ? $catalog[$input['campaign_type']] : null;
    $focusArea = trim((string) ($input['focus_area'] ?? ''));
    $contentAngle = trim((string) ($input['content_angle'] ?? ''));
    $useCase = trim((string) ($input['use_case'] ?? ''));
    $funnelStage = trim((string) ($input['funnel_stage'] ?? ''));
    $notes = trim((string) ($input['description'] ?? ''));

    $parts = [];
    if ($campaignType) {
        $parts[] = 'Campaign type: ' . $campaignType['label'];
        $parts[] = 'Primary goal: ' . $campaignType['goal'];
        $parts[] = 'Campaign focus: ' . implode('; ', $campaignType['focus']);
        $parts[] = 'Content examples to emulate: ' . implode('; ', $campaignType['content_examples']);
        $parts[] = 'Best use cases: ' . implode('; ', $campaignType['when_to_use']);
    }
    if ($funnelStage !== '' && isset($funnelStages[$funnelStage])) {
        $parts[] = 'Funnel stage: ' . $funnelStages[$funnelStage];
    }
    if ($focusArea !== '') {
        $parts[] = 'Selected focus area: ' . $focusArea;
    }
    if ($contentAngle !== '') {
        $parts[] = 'Preferred content angle: ' . $contentAngle;
    }
    if ($useCase !== '') {
        $parts[] = 'Current use case: ' . $useCase;
    }
    if ($notes !== '') {
        $parts[] = 'Extra campaign notes: ' . $notes;
    }

    return implode("\n", $parts);
}

function social_media_handle_profile_upload($field_name, $existing = '')
{
    if (empty($_FILES[$field_name]) || empty($_FILES[$field_name]['name'])) {
        return $existing;
    }

    $target_dir = ROOTPATH . '/storage/company/';
    social_media_make_directory($target_dir);

    $result = quick_file_upload($field_name, $target_dir);
    if (!$result['success']) {
        return $existing;
    }

    if (!empty($existing) && file_exists($target_dir . $existing)) {
        unlink($target_dir . $existing);
    }

    return $result['file_name'];
}

function social_media_upload_company_logo($user_id, $existing = '')
{
    $fileName = social_media_handle_profile_upload('company_logo', $existing);
    if ($fileName !== $existing || !empty($_FILES['company_logo']['name'])) {
        $profile = social_media_get_profile($user_id);
        $profile['company_logo'] = $fileName;
        social_media_save_profile($user_id, $profile);
    }
    return $fileName;
}

function social_media_get_company_context_text($user_id)
{
    $profile = social_media_get_profile($user_id);
    $parts = [];

    if (!empty($profile['company_name'])) {
        $parts[] = 'Company: ' . $profile['company_name'];
    }
    if (!empty($profile['founder_name'])) {
        $parts[] = 'Founder: ' . $profile['founder_name'] . (!empty($profile['founder_title']) ? ' (' . $profile['founder_title'] . ')' : '');
    }
    if (!empty($profile['company_website'])) {
        $parts[] = 'Website: ' . $profile['company_website'];
    }
    if (!empty($profile['company_industry'])) {
        $parts[] = 'Industry: ' . $profile['company_industry'];
    }
    if (!empty($profile['company_description'])) {
        $parts[] = 'About: ' . $profile['company_description'];
    }
    if (!empty($profile['target_audience'])) {
        $parts[] = 'Audience: ' . $profile['target_audience'];
    }
    if (!empty($profile['brand_voice'])) {
        $parts[] = 'Brand voice: ' . $profile['brand_voice'];
    }
    if (!empty($profile['content_goals'])) {
        $parts[] = 'Content goals: ' . $profile['content_goals'];
    }
    if (!empty($profile['key_products'])) {
        $parts[] = 'Products/services: ' . $profile['key_products'];
    }
    if (!empty($profile['differentiators'])) {
        $parts[] = 'Differentiators: ' . $profile['differentiators'];
    }
    if (!empty($profile['instagram_handle'])) {
        $parts[] = 'Instagram: ' . $profile['instagram_handle'];
    }
    if (!empty($profile['competitors'])) {
        $parts[] = 'Competitors: ' . implode(', ', $profile['competitors']);
    }
    if (!empty($profile['competitor_notes'])) {
        $parts[] = 'Competitor notes: ' . $profile['competitor_notes'];
    }
    $intelligence = social_media_get_company_intelligence($user_id);
    if (!empty($intelligence['summary_text'])) {
        $parts[] = "Stored company intelligence:\n" . $intelligence['summary_text'];
    }

    return implode("\n", $parts);
}

function social_media_get_company_intelligence_refresh_hours()
{
    return max(6, (int) get_env_setting('SOCIAL_COMPANY_INTELLIGENCE_REFRESH_HOURS', 72));
}

function social_media_profile_signature($profile)
{
    return sha1(json_encode([
        'company_name' => !empty($profile['company_name']) ? $profile['company_name'] : '',
        'company_website' => !empty($profile['company_website']) ? $profile['company_website'] : '',
        'company_industry' => !empty($profile['company_industry']) ? $profile['company_industry'] : '',
        'company_description' => !empty($profile['company_description']) ? $profile['company_description'] : '',
        'target_audience' => !empty($profile['target_audience']) ? $profile['target_audience'] : '',
        'brand_voice' => !empty($profile['brand_voice']) ? $profile['brand_voice'] : '',
        'content_goals' => !empty($profile['content_goals']) ? $profile['content_goals'] : '',
        'key_products' => !empty($profile['key_products']) ? $profile['key_products'] : '',
        'differentiators' => !empty($profile['differentiators']) ? $profile['differentiators'] : '',
        'competitors' => !empty($profile['competitors']) ? array_values($profile['competitors']) : [],
        'competitor_notes' => !empty($profile['competitor_notes']) ? $profile['competitor_notes'] : '',
    ]));
}

function social_media_get_company_intelligence($user_id, $force = false)
{
    $profile = social_media_get_profile($user_id);
    $signature = social_media_profile_signature($profile);
    $raw = get_user_option($user_id, 'social_company_intelligence', '');
    $cached = !empty($raw) ? json_decode($raw, true) : [];
    $refreshHours = social_media_get_company_intelligence_refresh_hours();
    $needsRefresh = $force || empty($cached) || !is_array($cached);

    if (!$needsRefresh) {
        $cachedSignature = !empty($cached['profile_signature']) ? $cached['profile_signature'] : '';
        $refreshedAt = !empty($cached['refreshed_at']) ? strtotime($cached['refreshed_at']) : 0;
        if ($cachedSignature !== $signature) {
            $needsRefresh = true;
        } elseif ($refreshedAt <= 0 || (time() - $refreshedAt) > ($refreshHours * 3600)) {
            $needsRefresh = true;
        }
    }

    if (!$needsRefresh) {
        return $cached;
    }

    $intelligence = social_media_refresh_company_intelligence($user_id, $profile);
    update_user_option($user_id, 'social_company_intelligence', json_encode($intelligence));
    return $intelligence;
}

function social_media_extract_site_points($html, $limit = 6)
{
    $points = [];
    if (!is_string($html) || trim($html) === '') {
        return $points;
    }

    if (preg_match_all('/<h[1-3][^>]*>(.*?)<\/h[1-3]>/is', $html, $matches)) {
        foreach ($matches[1] as $match) {
            $text = trim(html_entity_decode(strip_tags($match), ENT_QUOTES, 'UTF-8'));
            if ($text !== '') {
                $points[] = $text;
            }
            if (count($points) >= $limit) {
                return array_slice(array_values(array_unique($points)), 0, $limit);
            }
        }
    }

    if (preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $html, $matches)) {
        foreach ($matches[1] as $match) {
            $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($match), ENT_QUOTES, 'UTF-8')));
            if ($text !== '' && strlen($text) > 50) {
                $points[] = $text;
            }
            if (count($points) >= $limit) {
                break;
            }
        }
    }

    return array_slice(array_values(array_unique($points)), 0, $limit);
}

function social_media_fetch_site_snapshot($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }

    if (!preg_match('/^https?:\/\//i', $url)) {
        $url = 'https://' . ltrim($url, '/');
    }

    $snapshot = [
        'url' => $url,
        'title' => '',
        'description' => '',
        'headings' => [],
        'summary_points' => [],
        'instagram' => '',
    ];

    $html = social_media_safe_fetch_url($url);
    if (!$html) {
        return $snapshot;
    }

    if (preg_match('/<title>(.*?)<\/title>/is', $html, $match)) {
        $snapshot['title'] = trim(html_entity_decode(strip_tags($match[1]), ENT_QUOTES, 'UTF-8'));
    }

    if (preg_match('/<meta[^>]+name=["\']description["\'][^>]+content=["\']([^"\']+)["\']/is', $html, $match)
        || preg_match('/<meta[^>]+property=["\']og:description["\'][^>]+content=["\']([^"\']+)["\']/is', $html, $match)) {
        $snapshot['description'] = trim(html_entity_decode($match[1], ENT_QUOTES, 'UTF-8'));
    }

    $snapshot['summary_points'] = social_media_extract_site_points($html, 6);
    $snapshot['headings'] = array_slice($snapshot['summary_points'], 0, 3);

    if (preg_match('/https?:\/\/(www\.)?instagram\.com\/[A-Za-z0-9._-]+/i', $html, $match)) {
        $snapshot['instagram'] = $match[0];
    }

    return $snapshot;
}

function social_media_get_recent_chat_context($user_id, $limit = 8)
{
    global $config;

    $chats = ORM::for_table($config['db']['pre'] . 'ai_chat')
        ->where('user_id', $user_id)
        ->order_by_desc('id')
        ->limit($limit)
        ->find_array();

    if (empty($chats)) {
        return '';
    }

    $lines = [];
    $chats = array_reverse($chats);
    foreach ($chats as $chat) {
        if (!empty($chat['user_message'])) {
            $lines[] = 'Founder asked: ' . trim($chat['user_message']);
        }
        if (!empty($chat['ai_message'])) {
            $lines[] = 'Agent answered: ' . trim($chat['ai_message']);
        }
    }

    return implode("\n", array_slice($lines, -12));
}

function social_media_get_competitor_snapshots($profile)
{
    $snapshots = [];
    foreach (array_slice($profile['competitors'], 0, 5) as $url) {
        $snapshots[] = social_media_fetch_competitor_snapshot($url);
    }
    return array_filter($snapshots);
}

function social_media_fetch_competitor_snapshot($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return null;
    }

    if (strpos($url, 'instagram.com/') !== false && !preg_match('/^https?:\/\//i', $url)) {
        $url = 'https://' . ltrim($url, '/');
    }
    if (strpos($url, 'instagram.com/') !== false) {
        return [
            'url' => $url,
            'title' => '',
            'description' => '',
            'headings' => [],
            'summary_points' => [],
            'instagram' => $url,
        ];
    }

    return social_media_fetch_site_snapshot($url);
}

function social_media_generate_intelligence_fallback($profile, $companySite, $competitors)
{
    $summary = [];
    $market = [];
    $edges = [];

    if (!empty($profile['company_description'])) {
        $summary[] = 'Company summary: ' . $profile['company_description'];
    }
    if (!empty($profile['key_products'])) {
        $summary[] = 'Core offer: ' . $profile['key_products'];
    }
    if (!empty($profile['target_audience'])) {
        $summary[] = 'Audience: ' . $profile['target_audience'];
    }
    if (!empty($profile['differentiators'])) {
        $edges[] = $profile['differentiators'];
    }
    if (!empty($companySite['description'])) {
        $summary[] = 'Website positioning: ' . $companySite['description'];
    }
    if (!empty($companySite['summary_points'])) {
        $summary[] = 'Website highlights: ' . implode('; ', array_slice($companySite['summary_points'], 0, 4));
    }

    foreach (array_slice($competitors, 0, 3) as $competitor) {
        $label = !empty($competitor['title']) ? $competitor['title'] : (!empty($competitor['url']) ? $competitor['url'] : 'Competitor');
        $market[] = $label . ': ' . (!empty($competitor['description']) ? $competitor['description'] : 'No description found');
    }

    return [
        'company_summary' => implode(' ', array_filter($summary)),
        'market_research' => !empty($market) ? 'Competitor/market signals: ' . implode(' | ', $market) : '',
        'competitive_edges' => !empty($edges) ? implode(' | ', social_media_normalize_list($edges)) : '',
        'strategic_guidance' => 'Use messaging that emphasizes clarity, differentiation, trust signals, and relevance to the target audience.',
    ];
}

function social_media_generate_intelligence_via_openai($profile, $companySite, $competitors, $historyContext = '')
{
    if (trim((string) get_api_key()) === '') {
        return [];
    }

    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

    $openAi = new Orhanerday\OpenAi\OpenAi(get_api_key());
    $model = social_media_get_chat_model_candidates();
    $model = !empty($model[0]) ? $model[0] : get_default_openai_chat_model();

    $system = 'You are a business strategist. Build a concise reusable company intelligence brief as valid JSON only.';
    $prompt = "Create a structured intelligence summary for Atlas to reuse in AI chats and social media generation.\n"
        . "Return JSON only with keys: company_summary, market_research, competitive_edges, strategic_guidance.\n"
        . "Company profile:\n" . json_encode($profile) . "\n\n"
        . "Company website snapshot:\n" . json_encode($companySite) . "\n\n"
        . "Competitor snapshots:\n" . json_encode($competitors) . "\n\n"
        . "Recent founder/agent history:\n" . $historyContext . "\n\n"
        . "Be specific. Focus on what the company does, who it serves, how it is different, market themes, and how content should position the brand.";

    $response = $openAi->chat([
        'model' => $model,
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $prompt],
        ],
        'temperature' => 0.4,
        'response_format' => ['type' => 'json_object'],
        'max_tokens' => 900,
    ]);
    $decoded = json_decode($response, true);
    if (empty($decoded['choices'][0]['message']['content'])) {
        return [];
    }
    $json = social_media_extract_json($decoded['choices'][0]['message']['content']);
    return is_array($json) ? $json : [];
}

function social_media_refresh_company_intelligence($user_id, $profile = null)
{
    $profile = $profile ?: social_media_get_profile($user_id);
    $companySite = !empty($profile['company_website']) ? social_media_fetch_site_snapshot($profile['company_website']) : [];
    $competitors = social_media_get_competitor_snapshots($profile);
    $historyContext = social_media_get_recent_chat_context($user_id, 10);
    $aiSummary = social_media_generate_intelligence_via_openai($profile, $companySite, $competitors, $historyContext);
    $fallback = social_media_generate_intelligence_fallback($profile, $companySite, $competitors);
    $intelligence = array_merge($fallback, array_filter($aiSummary, function ($value) {
        return trim((string) $value) !== '';
    }));

    $summaryText = implode("\n", array_filter([
        !empty($intelligence['company_summary']) ? 'Company summary: ' . $intelligence['company_summary'] : '',
        !empty($intelligence['market_research']) ? 'Market research: ' . $intelligence['market_research'] : '',
        !empty($intelligence['competitive_edges']) ? 'Competitive edges: ' . $intelligence['competitive_edges'] : '',
        !empty($intelligence['strategic_guidance']) ? 'Strategic guidance: ' . $intelligence['strategic_guidance'] : '',
    ]));

    return [
        'profile_signature' => social_media_profile_signature($profile),
        'refreshed_at' => date('Y-m-d H:i:s'),
        'company_site' => $companySite,
        'competitors' => $competitors,
        'company_summary' => !empty($intelligence['company_summary']) ? $intelligence['company_summary'] : '',
        'market_research' => !empty($intelligence['market_research']) ? $intelligence['market_research'] : '',
        'competitive_edges' => !empty($intelligence['competitive_edges']) ? $intelligence['competitive_edges'] : '',
        'strategic_guidance' => !empty($intelligence['strategic_guidance']) ? $intelligence['strategic_guidance'] : '',
        'summary_text' => $summaryText,
    ];
}

function social_media_safe_fetch_url($url)
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 8);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AtlasSocialBot/1.0');
        $body = curl_exec($ch);
        curl_close($ch);
        return is_string($body) ? $body : '';
    }

    return @file_get_contents($url);
}

function social_media_http_get_json($url, $headers = [])
{
    $body = '';

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AtlasSocialBot/1.0');
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        $body = curl_exec($ch);
        curl_close($ch);
    } else {
        $contextHeaders = "User-Agent: AtlasSocialBot/1.0\r\n";
        if (!empty($headers)) {
            $contextHeaders .= implode("\r\n", $headers) . "\r\n";
        }
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => $contextHeaders,
                'timeout' => 15,
            ],
        ]);
        $body = @file_get_contents($url, false, $context);
    }

    $decoded = json_decode((string) $body, true);
    return is_array($decoded) ? $decoded : [];
}

function social_media_download_remote_file($url)
{
    $url = trim((string) $url);
    if ($url === '' || !preg_match('/^https?:\/\//i', $url)) {
        return '';
    }

    $pathInfo = parse_url($url, PHP_URL_PATH);
    $extension = strtolower(pathinfo((string) $pathInfo, PATHINFO_EXTENSION));
    if ($extension === '') {
        $extension = 'jpg';
    }
    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
        $extension = 'jpg';
    }

    $target = rtrim(sys_get_temp_dir(), '/\\') . '/atlas-social-' . md5($url) . '.' . $extension;
    if (file_exists($target) && filesize($target) > 0) {
        return $target;
    }

    $body = '';
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 8);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'AtlasSocialBot/1.0');
        $body = curl_exec($ch);
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "User-Agent: AtlasSocialBot/1.0\r\n",
                'timeout' => 20,
            ],
        ]);
        $body = @file_get_contents($url, false, $context);
    }

    if (!is_string($body) || $body === '') {
        return '';
    }

    file_put_contents($target, $body);
    return file_exists($target) ? $target : '';
}

function social_media_track_unsplash_download($asset)
{
    static $tracked = [];

    if (empty($asset['remote_provider']) || $asset['remote_provider'] !== 'unsplash' || empty($asset['remote_download_url'])) {
        return false;
    }

    $downloadUrl = trim((string) $asset['remote_download_url']);
    if ($downloadUrl === '' || isset($tracked[$downloadUrl])) {
        return isset($tracked[$downloadUrl]);
    }

    $headers = [
        'Authorization: Client-ID ' . trim((string) get_env_setting('UNSPLASH_ACCESS_KEY', '')),
        'Accept-Version: v1',
    ];
    social_media_http_get_json($downloadUrl, $headers);
    $tracked[$downloadUrl] = true;

    return true;
}

function social_media_unsplash_attribution_url($url)
{
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }

    $parts = parse_url($url);
    if (empty($parts['scheme']) || empty($parts['host'])) {
        return $url;
    }

    $query = [];
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
    }
    $query['utm_source'] = 'atlas';
    $query['utm_medium'] = 'referral';

    $rebuilt = $parts['scheme'] . '://' . $parts['host'];
    if (!empty($parts['path'])) {
        $rebuilt .= $parts['path'];
    }
    $rebuilt .= '?' . http_build_query($query);
    if (!empty($parts['fragment'])) {
        $rebuilt .= '#' . $parts['fragment'];
    }

    return $rebuilt;
}

function social_media_remote_asset_sources()
{
    $env = get_env_setting('SOCIAL_REMOTE_ASSET_SOURCES', 'unsplash,pexels,pixabay');
    $providers = array_map('trim', explode(',', strtolower((string) $env)));
    return array_values(array_unique(array_filter($providers)));
}

function social_media_remote_asset_keys()
{
    return [
        'unsplash' => trim((string) get_env_setting('UNSPLASH_ACCESS_KEY', '')),
        'pexels' => trim((string) get_env_setting('PEXELS_API_KEY', '')),
        'pixabay' => trim((string) get_env_setting('PIXABAY_API_KEY', '')),
    ];
}

function social_media_remote_assets_enabled()
{
    if (!get_env_setting('SOCIAL_REMOTE_ASSETS_ENABLED', true)) {
        return false;
    }

    foreach (social_media_remote_asset_keys() as $key) {
        if ($key !== '') {
            return true;
        }
    }

    return false;
}

function social_media_build_asset_search_queries($keywords = [], $design = [])
{
    $keywords = social_media_normalize_list($keywords);
    $designTags = social_media_normalize_list(isset($design['asset_tags']) ? $design['asset_tags'] : []);
    $pool = array_values(array_unique(array_filter(array_merge($keywords, $designTags))));
    $queries = [];

    if (!empty($pool)) {
        $queries[] = implode(' ', array_slice($pool, 0, 3));
        if (count($pool) > 3) {
            $queries[] = implode(' ', array_slice($pool, 3, 3));
        }
        if (count($pool) > 1) {
            $queries[] = $pool[0] . ' ' . end($pool);
        }
    }

    $queries[] = 'creative brand background';
    return array_values(array_unique(array_filter(array_map('trim', $queries))));
}

function social_media_build_provider_search_queries($provider, $keywords = [], $design = [])
{
    $queries = social_media_build_asset_search_queries($keywords, $design);
    if ($provider !== 'pixabay' && $provider !== 'unsplash') {
        return $queries;
    }

    $tokens = [];
    foreach (array_merge((array) $keywords, social_media_normalize_list(isset($design['asset_tags']) ? $design['asset_tags'] : [])) as $keyword) {
        $tokens = array_merge($tokens, social_media_keyword_tokens($keyword));
    }
    $tokens = array_values(array_unique($tokens));
    $families = social_media_detect_keyword_family($tokens);
    $providerQueries = [];

    if (in_array('dog', $families, true)) {
        $providerQueries[] = 'dog leash dog owner park';
        $providerQueries[] = 'dog walking leash outdoors';
        $providerQueries[] = 'puppy collar pet accessory';
    } elseif (in_array('cat', $families, true)) {
        $providerQueries[] = 'cat collar pet owner home';
        $providerQueries[] = 'cat accessory indoor pet';
    }

    if (!empty($tokens)) {
        $providerQueries[] = implode(' ', array_slice($tokens, 0, 4));
        if (count($tokens) > 4) {
            $providerQueries[] = implode(' ', array_slice($tokens, 4, 4));
        }
    }

    foreach ($queries as $query) {
        if ($provider === 'pixabay' && stripos($query, 'creative brand background') !== false) {
            continue;
        }
        $providerQueries[] = $query;
    }

    if ($provider === 'unsplash') {
        $providerQueries[] = implode(' ', array_slice(array_filter(array_unique(array_merge($tokens, $families))), 0, 4));
    }

    return array_values(array_unique(array_filter(array_map('trim', $providerQueries))));
}

function social_media_keyword_tokens($value)
{
    $value = strtolower(trim((string) $value));
    if ($value === '') {
        return [];
    }

    preg_match_all('/[a-z0-9]{3,}/', $value, $matches);
    return array_values(array_unique($matches[0]));
}

function social_media_keyword_families()
{
    return [
        'dog' => ['dog', 'dogs', 'puppy', 'puppies', 'canine', 'leash', 'collar'],
        'cat' => ['cat', 'cats', 'kitten', 'kittens', 'feline'],
        'bird' => ['bird', 'birds', 'avian', 'parrot'],
        'horse' => ['horse', 'horses', 'equine'],
    ];
}

function social_media_detect_keyword_family($tokens)
{
    $tokens = array_map('strtolower', (array) $tokens);
    $families = social_media_keyword_families();
    $matched = [];

    foreach ($families as $family => $variants) {
        foreach ($variants as $variant) {
            if (in_array($variant, $tokens, true)) {
                $matched[] = $family;
                break;
            }
        }
    }

    return array_values(array_unique($matched));
}

function social_media_candidate_text_blob($candidate)
{
    return strtolower(trim(implode(' ', array_filter([
        !empty($candidate['title']) ? $candidate['title'] : '',
        !empty($candidate['tags']) ? $candidate['tags'] : '',
        !empty($candidate['remote_author']) ? $candidate['remote_author'] : '',
        !empty($candidate['analysis']['ocr_text']) ? $candidate['analysis']['ocr_text'] : '',
    ]))));
}

function social_media_relevance_signal($candidate, $keywords = [], $designTags = [])
{
    $text = social_media_candidate_text_blob($candidate);
    $keywordTokens = [];
    foreach (array_merge((array) $keywords, (array) $designTags) as $keyword) {
        $keywordTokens = array_merge($keywordTokens, social_media_keyword_tokens($keyword));
    }
    $keywordTokens = array_values(array_unique($keywordTokens));
    $matchCount = 0;
    $familiesWanted = social_media_detect_keyword_family($keywordTokens);
    $familiesFound = social_media_detect_keyword_family(social_media_keyword_tokens($text));
    $familyPenalty = 0;

    foreach ($keywordTokens as $token) {
        if (strpos($text, $token) !== false) {
            $matchCount++;
        }
    }

    if (!empty($familiesWanted) && !empty($familiesFound)) {
        foreach ($familiesFound as $family) {
            if (!in_array($family, $familiesWanted, true)) {
                $familyPenalty += 10;
            }
        }
    }

    return [
        'text' => $text,
        'match_count' => $matchCount,
        'families_wanted' => $familiesWanted,
        'families_found' => $familiesFound,
        'family_penalty' => $familyPenalty,
    ];
}

function social_media_guess_background_tone($color)
{
    $color = social_media_normalize_hex_color($color, '#223046');
    list($r, $g, $b) = social_media_hex_to_rgb($color);
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    if ($brightness < 90) {
        return 'dark';
    }
    if ($brightness > 180) {
        return 'light';
    }
    return 'warm';
}

function social_media_remote_asset_record($provider, $id, $title, $imageUrl, $previewUrl, $width, $height, $tags = [], $color = '#223046', $pageUrl = '', $author = '', $authorUrl = '', $downloadUrl = '')
{
    $tone = social_media_guess_background_tone($color);

    return [
        'id' => null,
        'remote_id' => $provider . ':' . $id,
        'title' => $title,
        'asset_type' => 'image',
        'post_type' => 'post',
        'file_name' => '',
        'preview_name' => '',
        'remote_url' => $imageUrl,
        'remote_preview_url' => $previewUrl ?: $imageUrl,
        'remote_page_url' => $pageUrl,
        'remote_author' => $author,
        'remote_author_url' => $authorUrl,
        'remote_download_url' => $downloadUrl,
        'remote_provider' => $provider,
        'tags' => implode(', ', social_media_normalize_list($tags)),
        'text_position' => 'center',
        'status' => 1,
        'width' => $width,
        'height' => $height,
        'analysis' => [
            'source_width' => (int) $width,
            'source_height' => (int) $height,
            'best_text_zone' => 'center',
            'suggested_text_color' => $tone === 'light' ? '#111827' : '#FFFFFF',
            'overlay_opacity' => $tone === 'light' ? 0.18 : 0.34,
            'ocr_text' => '',
            'dominant_colors' => [$color, $tone === 'light' ? '#F5F2EC' : '#08111C'],
            'background_tone' => $tone,
            'template_kind' => 'background',
            'empty_layout_score' => 0.85,
            'analysis_version' => 1,
        ],
        'manifest' => [],
    ];
}

function social_media_search_unsplash($query, $limit = 8)
{
    $key = trim((string) get_env_setting('UNSPLASH_ACCESS_KEY', ''));
    if ($key === '') {
        return [];
    }

    $url = 'https://api.unsplash.com/search/photos?query=' . rawurlencode($query) . '&per_page=' . max(1, min(30, (int) $limit)) . '&orientation=squarish&content_filter=high&order_by=relevant';
    $data = social_media_http_get_json($url, [
        'Authorization: Client-ID ' . $key,
        'Accept-Version: v1',
    ]);

    $assets = [];
    foreach (($data['results'] ?? []) as $item) {
        if (empty($item['id']) || empty($item['urls']['regular'])) {
            continue;
        }
        $assets[] = social_media_remote_asset_record(
            'unsplash',
            $item['id'],
            !empty($item['alt_description']) ? $item['alt_description'] : 'Unsplash photo',
            $item['urls']['regular'],
            !empty($item['urls']['small']) ? $item['urls']['small'] : $item['urls']['regular'],
            !empty($item['width']) ? (int) $item['width'] : 0,
            !empty($item['height']) ? (int) $item['height'] : 0,
            array_filter([
                !empty($item['alt_description']) ? $item['alt_description'] : '',
                !empty($item['description']) ? $item['description'] : '',
            ]),
            !empty($item['color']) ? $item['color'] : '#223046',
            !empty($item['links']['html']) ? social_media_unsplash_attribution_url($item['links']['html']) : '',
            !empty($item['user']['name']) ? $item['user']['name'] : '',
            !empty($item['user']['links']['html']) ? social_media_unsplash_attribution_url($item['user']['links']['html']) : '',
            !empty($item['links']['download_location']) ? $item['links']['download_location'] : ''
        );
    }

    return $assets;
}

function social_media_search_pexels($query, $limit = 8)
{
    $key = trim((string) get_env_setting('PEXELS_API_KEY', ''));
    if ($key === '') {
        return [];
    }

    $url = 'https://api.pexels.com/v1/search?query=' . rawurlencode($query) . '&per_page=' . max(1, min(30, (int) $limit)) . '&orientation=square';
    $data = social_media_http_get_json($url, [
        'Authorization: ' . $key,
    ]);

    $assets = [];
    foreach (($data['photos'] ?? []) as $item) {
        if (empty($item['id']) || empty($item['src']['large2x'])) {
            continue;
        }
        $assets[] = social_media_remote_asset_record(
            'pexels',
            $item['id'],
            !empty($item['alt']) ? $item['alt'] : 'Pexels photo',
            $item['src']['large2x'],
            !empty($item['src']['medium']) ? $item['src']['medium'] : $item['src']['large2x'],
            !empty($item['width']) ? (int) $item['width'] : 0,
            !empty($item['height']) ? (int) $item['height'] : 0,
            array_filter([
                !empty($item['alt']) ? $item['alt'] : '',
                !empty($item['photographer']) ? $item['photographer'] : '',
            ]),
            !empty($item['avg_color']) ? $item['avg_color'] : '#223046',
            !empty($item['url']) ? $item['url'] : '',
            !empty($item['photographer']) ? $item['photographer'] : ''
        );
    }

    return $assets;
}

function social_media_search_pixabay($query, $limit = 8)
{
    $key = trim((string) get_env_setting('PIXABAY_API_KEY', ''));
    if ($key === '') {
        return [];
    }

    $families = social_media_detect_keyword_family(social_media_keyword_tokens($query));
    $category = '';
    if (!empty($families)) {
        $category = '&category=animals';
    }

    $url = 'https://pixabay.com/api/?key=' . rawurlencode($key)
        . '&q=' . rawurlencode($query)
        . '&image_type=photo&safesearch=true&editors_choice=true&order=popular'
        . $category
        . '&min_width=1000&min_height=1000&per_page=' . max(3, min(50, (int) $limit));
    $data = social_media_http_get_json($url);

    $assets = [];
    foreach (($data['hits'] ?? []) as $item) {
        if (empty($item['id']) || empty($item['largeImageURL'])) {
            continue;
        }
        $assets[] = social_media_remote_asset_record(
            'pixabay',
            $item['id'],
            !empty($item['tags']) ? $item['tags'] : 'Pixabay photo',
            $item['largeImageURL'],
            !empty($item['webformatURL']) ? $item['webformatURL'] : $item['largeImageURL'],
            !empty($item['imageWidth']) ? (int) $item['imageWidth'] : 0,
            !empty($item['imageHeight']) ? (int) $item['imageHeight'] : 0,
            social_media_normalize_list(!empty($item['tags']) ? $item['tags'] : ''),
            '#223046',
            !empty($item['pageURL']) ? $item['pageURL'] : '',
            !empty($item['user']) ? $item['user'] : ''
        );
    }

    return $assets;
}

function social_media_filter_remote_assets($assets, $provider, $keywords = [], $design = [])
{
    if (empty($assets)) {
        return [];
    }

    $filtered = [];
    $designTags = social_media_normalize_list(isset($design['asset_tags']) ? $design['asset_tags'] : []);

    foreach ($assets as $asset) {
        $relevance = social_media_relevance_signal($asset, $keywords, $designTags);

        if (!empty($relevance['families_wanted']) && empty($relevance['families_found'])) {
            continue;
        }
        if ($relevance['family_penalty'] > 0) {
            continue;
        }
        if ($provider === 'unsplash') {
            if (!empty($relevance['families_wanted']) && empty($relevance['families_found'])) {
                continue;
            }
            if ($relevance['match_count'] < 2) {
                continue;
            }
        }
        if ($provider === 'pixabay') {
            if ($relevance['match_count'] < 2) {
                continue;
            }
            if (strpos($relevance['text'], 'abstract') !== false || strpos($relevance['text'], 'pattern') !== false) {
                continue;
            }
        }

        $filtered[] = $asset;
    }

    return !empty($filtered) ? $filtered : $assets;
}

function social_media_search_remote_assets($post_type, $keywords = [], $design = [])
{
    if ($post_type !== 'post' || !social_media_remote_assets_enabled()) {
        return [];
    }

    $queries = social_media_build_asset_search_queries($keywords, $design);
    $providers = social_media_remote_asset_sources();
    $assets = [];

    foreach ($providers as $provider) {
        $providerQueries = social_media_build_provider_search_queries($provider, $keywords, $design);
        foreach ($providerQueries as $query) {
            $providerAssets = [];
            if ($provider === 'unsplash') {
                $providerAssets = social_media_search_unsplash($query, 6);
            } elseif ($provider === 'pexels') {
                $providerAssets = social_media_search_pexels($query, 6);
            } elseif ($provider === 'pixabay') {
                $providerAssets = social_media_search_pixabay($query, 6);
            }

            if (!empty($providerAssets)) {
                $assets = array_merge($assets, social_media_filter_remote_assets($providerAssets, $provider, $keywords, $design));
            }

            if (count($assets) >= 18) {
                break 2;
            }
        }
    }

    $unique = [];
    foreach ($assets as $asset) {
        $key = social_media_asset_unique_key($asset);
        if (!isset($unique[$key])) {
            $unique[$key] = $asset;
        }
    }

    return array_values($unique);
}

function social_media_get_format_map()
{
    return [
        'post' => [
            'label' => 'Post',
            'width' => 1080,
            'height' => 1080,
            'asset_type' => 'image',
            'headline_limit' => 70,
            'body_limit' => 180,
        ],
        'reel' => [
            'label' => 'Reel',
            'width' => 1080,
            'height' => 1920,
            'asset_type' => 'video',
            'headline_limit' => 55,
            'body_limit' => 130,
        ],
    ];
}

function social_media_get_assets($filters = [])
{
    social_media_bootstrap();
    global $config;

    $orm = ORM::for_table($config['db']['pre'] . 'social_media_assets')
        ->order_by_desc('id');

    if (!array_key_exists('status', $filters) || $filters['status'] !== 'all') {
        $orm->where('status', array_key_exists('status', $filters) ? (int) $filters['status'] : 1);
    }

    if (!empty($filters['asset_type'])) {
        $orm->where('asset_type', $filters['asset_type']);
    }

    if (!empty($filters['post_type'])) {
        $orm->where_raw('(post_type = ? OR post_type = ?)', [$filters['post_type'], 'all']);
    }

    $assets = $orm->find_array();
    foreach ($assets as &$asset) {
        $asset = social_media_prepare_asset_record($asset);
    }

    return $assets;
}

function social_media_get_asset($id)
{
    social_media_bootstrap();
    global $config;

    $asset = ORM::for_table($config['db']['pre'] . 'social_media_assets')->find_array($id);
    return $asset ? social_media_prepare_asset_record($asset) : null;
}

function social_media_prepare_asset_record($asset)
{
    if (empty($asset)) {
        return $asset;
    }

    if (!empty($asset['remote_id'])) {
        $asset['analysis'] = !empty($asset['analysis']) && is_array($asset['analysis']) ? $asset['analysis'] : [];
        $asset['manifest'] = !empty($asset['manifest']) && is_array($asset['manifest']) ? $asset['manifest'] : [];
        return $asset;
    }

    if (!empty($asset['id']) && (empty($asset['analysis_json']) || empty($asset['manifest_json']))) {
        social_media_refresh_asset_analysis($asset['id']);
        global $config;
        $asset = ORM::for_table($config['db']['pre'] . 'social_media_assets')->find_array($asset['id']);
    }

    $asset['analysis'] = !empty($asset['analysis_json']) ? json_decode($asset['analysis_json'], true) : [];
    $asset['manifest'] = !empty($asset['manifest_json']) ? json_decode($asset['manifest_json'], true) : [];

    return $asset;
}

function social_media_refresh_asset_analysis($assetId, $force = false)
{
    social_media_bootstrap();
    global $config;

    $asset = ORM::for_table($config['db']['pre'] . 'social_media_assets')->find_one($assetId);
    if (!$asset) {
        return false;
    }

    if (!$force && !empty($asset['analysis_json']) && !empty($asset['manifest_json'])) {
        return true;
    }

    $analysis = social_media_analyze_asset($asset->as_array());
    $manifest = social_media_generate_template_manifest($asset->as_array(), $analysis);

    $asset->analysis_json = json_encode($analysis);
    $asset->manifest_json = json_encode($manifest);
    $asset->text_position = !empty($analysis['best_text_zone']) ? $analysis['best_text_zone'] : $asset['text_position'];
    if (!empty($analysis['source_width'])) {
        $asset->width = $analysis['source_width'];
    }
    if (!empty($analysis['source_height'])) {
        $asset->height = $analysis['source_height'];
    }
    if (!empty($analysis['preview_name']) && empty($asset['preview_name'])) {
        $asset->preview_name = $analysis['preview_name'];
    }
    $asset->updated_at = date('Y-m-d H:i:s');
    $asset->save();

    return true;
}

function social_media_get_recent_posts($user_id, $limit = 18)
{
    social_media_bootstrap();
    global $config;

    $posts = ORM::for_table($config['db']['pre'] . 'social_media_posts')
        ->where('user_id', $user_id)
        ->order_by_desc('id')
        ->limit($limit)
        ->find_array();

    foreach ($posts as &$post) {
        $post['metadata'] = !empty($post['metadata']) ? json_decode($post['metadata'], true) : [];
    }

    return $posts;
}

function social_media_get_recent_grid_batch($user_id)
{
    $posts = social_media_get_recent_posts($user_id, 40);
    $batchKey = '';

    foreach ($posts as $post) {
        if (!empty($post['metadata']['grid']['template_key'])) {
            $batchKey = $post['batch_key'];
            break;
        }
    }

    if ($batchKey === '') {
        return [];
    }

    $batch = [];
    foreach ($posts as $post) {
        if ($post['batch_key'] === $batchKey) {
            $batch[] = $post;
        }
    }

    usort($batch, function ($a, $b) {
        $aPos = !empty($a['metadata']['grid']['position']) ? (int) $a['metadata']['grid']['position'] : 0;
        $bPos = !empty($b['metadata']['grid']['position']) ? (int) $b['metadata']['grid']['position'] : 0;
        return $aPos <=> $bPos;
    });

    return array_slice($batch, 0, 9);
}

function social_media_get_post($user_id, $id)
{
    social_media_bootstrap();
    global $config;

    $post = ORM::for_table($config['db']['pre'] . 'social_media_posts')
        ->where('user_id', $user_id)
        ->find_one($id);

    if ($post && !empty($post['metadata'])) {
        $post->metadata = json_decode($post['metadata'], true);
    }

    return $post;
}

function social_media_pick_asset($post_type, $keywords = [])
{
    $formats = social_media_get_format_map();
    $asset_type = $formats[$post_type]['asset_type'];
    $assets = social_media_get_assets([
        'post_type' => $post_type,
        'asset_type' => $asset_type,
    ]);

    if (empty($assets) && $asset_type === 'video') {
        $assets = social_media_get_assets([
            'post_type' => $post_type,
        ]);
    }

    if (empty($assets)) {
        return null;
    }

    $bestAsset = null;
    $bestScore = -1;
    foreach ($assets as $asset) {
        $score = 0;
        $tags = social_media_normalize_list(isset($asset['tags']) ? $asset['tags'] : '');
        foreach ($keywords as $keyword) {
            if (in_array(strtolower($keyword), array_map('strtolower', $tags), true)) {
                $score += 2;
            }
        }
        if ($asset['post_type'] === $post_type) {
            $score += 1;
        }
        if (!empty($asset['analysis']['best_text_zone'])) {
            $score += 1;
        }
        if (empty($asset['analysis']['ocr_text'])) {
            $score += 1;
        } else {
            $score -= 1;
        }
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestAsset = $asset;
        }
    }

    return $bestAsset ?: $assets[array_rand($assets)];
}

function social_media_pick_asset_with_design($post_type, $keywords = [], $design = [], $excludedAssetIds = [])
{
    $asset = social_media_pick_asset($post_type, $keywords);
    $formats = social_media_get_format_map();
    $asset_type = $formats[$post_type]['asset_type'];
    $assets = social_media_search_remote_assets($post_type, $keywords, $design);
    $localAssets = social_media_get_assets([
        'post_type' => $post_type,
        'asset_type' => $asset_type,
    ]);
    $assets = array_merge($assets, $localAssets);

    if (empty($assets) && $asset_type === 'video') {
        $assets = social_media_get_assets([
            'post_type' => $post_type,
        ]);
    }

    if (empty($assets)) {
        return $asset;
    }

    $excludedAssetIds = array_values(array_filter((array) $excludedAssetIds, function ($value) {
        return $value !== null && $value !== '';
    }));
    $preferredAssets = [];
    foreach ($assets as $candidate) {
        $candidateKey = social_media_asset_unique_key($candidate);
        if ($candidateKey !== '' && in_array($candidateKey, $excludedAssetIds, true)) {
            continue;
        }
        $preferredAssets[] = $candidate;
    }
    if (!empty($preferredAssets)) {
        $assets = $preferredAssets;
    }

    $designTags = social_media_normalize_list(isset($design['asset_tags']) ? $design['asset_tags'] : []);
    $desiredTone = !empty($design['background_tone']) ? strtolower(trim((string) $design['background_tone'])) : '';
    $bestAsset = null;
    $bestScore = -999;

    foreach ($assets as $candidate) {
        $score = 0;
        $tags = social_media_normalize_list(isset($candidate['tags']) ? $candidate['tags'] : '');
        $relevance = social_media_relevance_signal($candidate, $keywords, $designTags);
        foreach ($keywords as $keyword) {
            if (in_array(strtolower($keyword), array_map('strtolower', $tags), true)) {
                $score += 2;
            }
        }
        foreach ($designTags as $tag) {
            if (in_array(strtolower($tag), array_map('strtolower', $tags), true)) {
                $score += 2;
            }
        }
        if ($candidate['post_type'] === $post_type) {
            $score += 1;
        }
        if (!empty($candidate['analysis']['background_tone']) && $desiredTone !== '' && $candidate['analysis']['background_tone'] === $desiredTone) {
            $score += 2;
        }
        if (!empty($candidate['analysis']['template_kind']) && $candidate['analysis']['template_kind'] === 'background') {
            $score += 4;
        } else {
            $score -= 3;
        }
        if (isset($candidate['analysis']['empty_layout_score'])) {
            $score += (float) $candidate['analysis']['empty_layout_score'] * 4;
        }
        if (empty($candidate['analysis']['ocr_text'])) {
            $score += 2;
        } else {
            $score -= min(4, strlen($candidate['analysis']['ocr_text']) / 60);
        }
        $score += $relevance['match_count'] * 3;
        $score -= $relevance['family_penalty'];
        if (!empty($candidate['remote_provider']) && !empty($relevance['families_wanted']) && empty($relevance['families_found']) && $relevance['match_count'] === 0) {
            $score -= 8;
        }
        if (!empty($candidate['remote_provider']) && strpos($relevance['text'], 'abstract') !== false) {
            $score -= 4;
        }

        if ($score > $bestScore || ($score === $bestScore && (!empty($candidate['id']) && !empty($bestAsset['id']) ? (int) $candidate['id'] > (int) $bestAsset['id'] : !empty($candidate['id'])))) {
            $bestScore = $score;
            $bestAsset = $candidate;
        }
    }

    return $bestAsset ?: $asset;
}

function social_media_generate_batch($user_id, $brief = '')
{
    social_media_runtime_debug('openai', null);
    $profile = social_media_get_profile($user_id);
    $companyContext = social_media_get_company_context_text($user_id);
    $historyContext = social_media_get_recent_chat_context($user_id);
    $competitorSnapshots = social_media_get_competitor_snapshots($profile);
    $fontCatalog = social_media_get_font_prompt_catalog();
    $paletteCatalog = social_media_get_palette_prompt_catalog();

    $system = "You are Atlas Social Strategist. Generate practical, brand-aware social media packages for founders. Use the campaign strategy as a hard constraint for messaging, CTA style, and content angle. Return valid JSON only.";

    $competitorText = json_encode($competitorSnapshots);
    $userPrompt = "Create exactly 9 social media ideas for this company.\n"
        . "Need exactly 9 post items.\n"
        . "Each item must include: post_type, title, hook, caption, cta, hashtags, visual_brief, keywords, design.\n"
        . "For every item, create copy using these exact rules:\n"
        . "1. overlay_text is the final hook that appears on the artwork. It must be short, execution-ready, and fully usable as a post opener.\n"
        . "2. overlay_text should follow hook patterns like problem question, pain hook, contrarian hook, or outcome hook. Use actual hooks, not labels.\n"
        . "3. overlay_text must be 3 to 9 words, with a hard maximum of 55 characters where possible.\n"
        . "4. Do not use placeholders or generic labels like 'founder insight', 'myth busting', 'case study', or 'trend reaction' as overlay_text.\n"
        . "5. caption must be publish-ready, useful, specific, and persuasive. The opening statement must combine exactly one ICP, one problem, and one UVP in a punchy marketing-ready sentence.\n"
        . "6. caption should feel like real social copy, not instructions to a marketer. No meta language, no explaining the framework.\n"
        . "7. cta must be a direct, short, usable phrase aligned to the campaign goal. Prefer 2 to 6 words.\n"
        . "8. write like a strong social strategist who understands the company, market, customer pain, and competitor gaps.\n"
        . "Every post must feel final and publishable. Do not explain the strategy to the audience. Turn the strategy into sharp copy.\n"
        . "Use the selected campaign type, funnel stage, focus area, content angle, and use case to decide what kind of hook, caption, and CTA should be written.\n"
        . "The design object must include: headline_font_key, body_font_key, headline_size, body_size, text_case, text_align, overlay_color, overlay_opacity, text_color, accent_color, background_tone, asset_tags.\n"
        . "Only use font keys from this approved list:\n{$fontCatalog}\n\n"
        . "Use these palette directions and background tones when choosing colors:\n{$paletteCatalog}\n\n"
        . "Pick typography that fits the idea: bold sans for direct hooks, editorial serif for thoughtful content, condensed display for strong statements, clean grotesk for educational posts.\n"
        . "Company context:\n{$companyContext}\n\n"
        . "Recent company history from agents:\n{$historyContext}\n\n"
        . "Competitor research:\n{$competitorText}\n\n"
        . "Campaign brief:\n{$brief}\n\n"
        . "JSON shape: {\"items\":[...]}";

    $items = social_media_generate_batch_via_openai($system, $userPrompt, $user_id);
    $generationSource = 'openai';
    if (empty($items)) {
        $items = social_media_generate_fallback_batch($profile, $brief);
        $generationSource = 'fallback';
    }
    $items = social_media_normalize_generated_items($items, $profile);
    foreach ($items as &$item) {
        $item['_generation_source'] = $generationSource;
        $item['_openai_debug'] = social_media_runtime_debug('openai');
    }
    unset($item);
    return $items;
}

function social_media_generate_instagram_grid($user_id, $brief = '', $input = [])
{
    social_media_runtime_debug('openai', null);
    $profile = social_media_get_profile($user_id);
    list($templateKey, $template) = social_media_choose_instagram_grid_template(
        $profile,
        !empty($input['campaign_type']) ? $input['campaign_type'] : '',
        !empty($input['grid_style']) ? $input['grid_style'] : 'auto'
    );

    $companyContext = social_media_get_company_context_text($user_id);
    $historyContext = social_media_get_recent_chat_context($user_id);
    $competitorSnapshots = social_media_get_competitor_snapshots($profile);
    $fontCatalog = social_media_get_font_prompt_catalog();
    $paletteCatalog = social_media_get_palette_prompt_catalog();

    $system = "You are Atlas Grid Director. Generate cohesive Instagram grid content packages that feel intentional, brand-aware, and sequence-ready. Return valid JSON only.";
    $userPrompt = "Create exactly 9 Instagram grid posts in order for this company.\n"
        . "Use this selected grid system as a hard visual and sequencing constraint:\n"
        . json_encode($template) . "\n\n"
        . "Each item must include: title, overlay_text, caption, cta, hashtags, visual_brief, keywords, design.\n"
        . "The sequence must respect this tile layout: " . implode(', ', $template['layout']) . ".\n"
        . "Rules:\n"
        . "1. Text tiles must produce a short, bold overlay that can sit alone on a tile.\n"
        . "2. Image tiles should keep overlay_text minimal or empty and depend on relevant imagery.\n"
        . "3. Captions must be publish-ready and tied to the selected campaign goal and the company context.\n"
        . "4. The full 9-tile sequence should feel like one Instagram grid, not 9 unrelated posts.\n"
        . "5. Use only approved fonts:\n{$fontCatalog}\n\n"
        . "6. Use these palette directions when choosing color behavior:\n{$paletteCatalog}\n\n"
        . "Company context:\n{$companyContext}\n\n"
        . "Recent company history from agents:\n{$historyContext}\n\n"
        . "Competitor research:\n" . json_encode($competitorSnapshots) . "\n\n"
        . "Campaign brief:\n{$brief}\n\n"
        . "JSON shape: {\"items\":[...]}";

    $items = social_media_generate_batch_via_openai($system, $userPrompt, $user_id);
    $generationSource = 'openai';
    if (empty($items)) {
        $items = social_media_generate_instagram_grid_fallback($profile, $template, $brief);
        $generationSource = 'fallback';
    }

    $items = social_media_normalize_instagram_grid_items($items, $profile, $templateKey, $template, $brief);
    foreach ($items as &$item) {
        $item['_generation_source'] = $generationSource;
        $item['_openai_debug'] = social_media_runtime_debug('openai');
    }
    unset($item);

    return [
        'template_key' => $templateKey,
        'template' => $template,
        'items' => $items,
    ];
}

function social_media_generate_instagram_grid_fallback($profile, $template, $brief = '')
{
    $company = !empty($profile['company_name']) ? $profile['company_name'] : 'your brand';
    $audience = !empty($profile['target_audience']) ? trim(strtok($profile['target_audience'], ",\n")) : 'your audience';
    $product = !empty($profile['key_products']) ? trim(strtok($profile['key_products'], ",\n")) : 'your offer';
    $briefSummary = trim(preg_replace('/\s+/', ' ', strtok((string) $brief, "\n")));
    $baseHooks = [
        'Why your message gets ignored',
        'What makes your brand memorable',
        'The simplest way to stand out',
        'Where the real value shows up',
        'How to make the offer click',
        'What your audience actually needs',
        'The reason this content converts',
        'Why clarity beats more noise',
        'Build trust before the ask',
    ];

    $items = [];
    foreach ($template['layout'] as $index => $mode) {
        $hook = $baseHooks[$index % count($baseHooks)];
        $items[] = [
            'title' => $company . ' Grid Tile ' . ($index + 1),
            'overlay_text' => $mode === 'text' ? $hook : '',
            'caption' => ucfirst($audience) . ' who want a clearer result from ' . $product . ' get a sharper, more useful message through ' . $company . '.' . ($briefSummary !== '' ? ' Built for ' . rtrim($briefSummary, '.') . '.' : ''),
            'cta' => 'Save this post',
            'hashtags' => ['#' . preg_replace('/\s+/', '', ucwords($company)), '#InstagramGrid', '#Marketing'],
            'visual_brief' => $mode === 'text'
                ? 'Create a branded text tile that fits the selected grid system and keeps the message visually dominant.'
                : 'Use a relevant lifestyle or product image that supports the selected grid system and matches the brand tone.',
            'keywords' => [$company, $product, $audience],
            'design' => [],
        ];
    }

    return $items;
}

function social_media_normalize_instagram_grid_items($items, $profile, $templateKey, $template, $brief = '')
{
    $fontKeys = array_keys(social_media_get_available_fonts());
    $defaults = social_media_get_design_defaults();
    $normalized = [];
    $briefSummary = trim(preg_replace('/\s+/', ' ', strtok((string) $brief, "\n")));
    $company = !empty($profile['company_name']) ? $profile['company_name'] : 'Atlas';
    $industry = !empty($profile['company_industry']) ? $profile['company_industry'] : 'your market';
    $product = !empty($profile['key_products']) ? trim(strtok($profile['key_products'], ",\n")) : $industry;

    for ($i = 0; $i < 9; $i++) {
        $mode = !empty($template['layout'][$i]) ? $template['layout'][$i] : 'image';
        $source = !empty($items[$i]) && is_array($items[$i]) ? $items[$i] : [];
        $design = $defaults['post'];
        $design['headline_font_key'] = !empty($template['headline_font_key']) ? $template['headline_font_key'] : ($fontKeys[0] ?? 'inter');
        $design['body_font_key'] = !empty($template['body_font_key']) ? $template['body_font_key'] : ($fontKeys[1] ?? $design['headline_font_key']);
        $design['headline_size'] = $mode === 'text' ? 92 : 100;
        $design['body_size'] = 22;
        $design['text_case'] = !empty($template['text_case']) ? $template['text_case'] : 'sentence';
        $design['text_align'] = !empty($template['text_align']) ? $template['text_align'] : 'center';
        $design['background_tone'] = !empty($template['background_tone']) ? $template['background_tone'] : 'minimal';
        $design['background_colors'] = !empty($template['palette']) ? array_slice($template['palette'], 0, 2) : ['#F5F3EC', '#EEE7DA'];
        $design['overlay_opacity'] = $mode === 'text' ? 0.04 : 0.18;
        $design['text_color'] = !empty($template['palette'][0]) && social_media_guess_background_tone($template['palette'][0]) === 'light' ? '#171717' : '#FFFFFF';
        $design['accent_color'] = !empty($template['palette'][2]) ? $template['palette'][2] : $design['text_color'];
        $design['overlay_color'] = !empty($template['palette'][1]) ? $template['palette'][1] : $design['accent_color'];
        $design['asset_tags'] = $mode === 'image'
            ? array_values(array_unique(array_merge([$industry, $product], $template['image_keywords'])))
            : [$industry, $company];
        $design = social_media_normalize_design($design, 'post', $fontKeys, $defaults);

        $overlay = trim((string) ($source['overlay_text'] ?? ''));
        if ($mode === 'text' && $overlay === '') {
            $overlay = 'Make your brand easier to trust';
        }

        $caption = trim((string) ($source['caption'] ?? ''));
        if ($caption === '') {
            $caption = 'People who need a clearer path to choosing ' . $product . ' get that clarity through ' . $company . '\'s more useful approach.' . ($briefSummary !== '' ? ' Designed for ' . rtrim($briefSummary, '.') . '.' : '');
        }

        $normalized[] = [
            'post_type' => 'post',
            'title' => trim((string) ($source['title'] ?? ($company . ' Grid Tile ' . ($i + 1)))),
            'hook' => $overlay,
            'overlay_text' => $mode === 'text' ? $overlay : '',
            'caption' => $caption,
            'cta' => trim((string) ($source['cta'] ?? ($mode === 'text' ? 'Save this post' : 'Follow for more'))),
            'hashtags' => social_media_normalize_list(!empty($source['hashtags']) ? $source['hashtags'] : ['#' . preg_replace('/\s+/', '', ucwords($company)), '#InstagramGrid', '#Marketing']),
            'visual_brief' => trim((string) ($source['visual_brief'] ?? ($mode === 'text'
                ? 'Create a text-led tile that fits the selected grid template.'
                : 'Use a relevant, on-brand image that supports the selected grid template.'))),
            'keywords' => array_values(array_unique(array_merge(
                social_media_normalize_list(!empty($source['keywords']) ? $source['keywords'] : []),
                [$industry, $product],
                $mode === 'image' ? $template['image_keywords'] : [$company]
            ))),
            'design' => $design,
            'render_options' => $mode === 'text'
                ? ['show_logo' => false, 'show_label' => false, 'show_subheadline' => false, 'show_brand' => false, 'show_cta' => false, 'show_headline' => true]
                : ['show_logo' => false, 'show_label' => false, 'show_subheadline' => false, 'show_brand' => false, 'show_cta' => false, 'show_headline' => false],
            'grid' => [
                'template_key' => $templateKey,
                'template_label' => $template['label'],
                'tile_mode' => $mode,
                'position' => $i + 1,
            ],
            '_generation_source' => !empty($source['_generation_source']) ? $source['_generation_source'] : 'openai',
            '_openai_debug' => !empty($source['_openai_debug']) ? $source['_openai_debug'] : [],
        ];
    }

    return $normalized;
}

function social_media_generate_batch_via_openai($system, $userPrompt, $user_id)
{
    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

    $openAi = new Orhanerday\OpenAi\OpenAi(get_api_key());
    $modelsToTry = social_media_get_chat_model_candidates();
    $lastError = '';
    $lastAttempt = '';

    foreach ($modelsToTry as $modelToTry) {
        $payload = [
            'model' => $modelToTry,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.7,
            'response_format' => ['type' => 'json_object'],
            'max_tokens' => 2200,
            'user' => $user_id,
        ];

        $response = $openAi->chat($payload);
        $decoded = json_decode($response, true);

        if (!empty($decoded['error']['message'])) {
            $lastError = $decoded['error']['message'];
            $lastAttempt = 'json_mode';
            unset($payload['response_format']);
            $payload['temperature'] = 0.6;
            $response = $openAi->chat($payload);
            $decoded = json_decode($response, true);
        }

        if (!empty($decoded['error']['message'])) {
            $lastError = $decoded['error']['message'];
            $lastAttempt = 'plain_chat';
            continue;
        }

        if (empty($decoded['choices'][0]['message']['content'])) {
            $lastError = 'No content returned';
            $lastAttempt = 'plain_chat';
            continue;
        }

        $json = social_media_extract_json($decoded['choices'][0]['message']['content']);
        if (empty($json['items']) || !is_array($json['items'])) {
            $lastError = 'Invalid JSON payload';
            $lastAttempt = 'plain_chat';
            social_media_runtime_debug('openai', [
                'attempt' => $lastAttempt,
                'model' => $modelToTry,
                'error' => $lastError,
                'raw_excerpt' => substr($decoded['choices'][0]['message']['content'], 0, 400),
            ]);
            continue;
        }

        social_media_runtime_debug('openai', [
            'attempt' => 'success',
            'model' => $modelToTry,
            'error' => '',
        ]);

        return $json['items'];
    }

    error_log('Social media generation error: ' . $lastError);
    social_media_runtime_debug('openai', [
        'attempt' => $lastAttempt,
        'model' => !empty($modelsToTry) ? end($modelsToTry) : '',
        'error' => $lastError,
    ]);
    return [];
}

function social_media_get_chat_model_candidates()
{
    $preferred = normalize_openai_model(get_default_openai_chat_model());
    $candidates = [
        $preferred,
        'gpt-4o-mini',
        'gpt-4o',
        'gpt-4.1-mini',
        'gpt-4.1',
    ];

    $normalized = [];
    foreach ($candidates as $candidate) {
        $candidate = normalize_openai_model($candidate);
        if (!in_array($candidate, $normalized, true)) {
            $normalized[] = $candidate;
        }
    }

    return $normalized;
}

function social_media_extract_json($text)
{
    $text = trim((string) $text);
    $text = preg_replace('/^```(?:json)?\s*/i', '', $text);
    $text = preg_replace('/\s*```$/', '', $text);
    $decoded = json_decode($text, true);
    if (is_array($decoded)) {
        return $decoded;
    }

    $start = strpos($text, '{');
    $end = strrpos($text, '}');
    if ($start === false || $end === false || $end <= $start) {
        return [];
    }

    $decoded = json_decode(substr($text, $start, $end - $start + 1), true);
    return is_array($decoded) ? $decoded : [];
}

function social_media_generate_fallback_batch($profile, $brief = '')
{
    $company = !empty($profile['company_name']) ? $profile['company_name'] : 'your company';
    $industry = !empty($profile['company_industry']) ? $profile['company_industry'] : 'your market';
    $audience = !empty($profile['target_audience']) ? $profile['target_audience'] : 'your audience';
    $product = !empty($profile['key_products']) ? trim(strtok($profile['key_products'], ",\n")) : $industry;
    $differentiator = !empty($profile['differentiators']) ? trim(strtok($profile['differentiators'], ".\n")) : 'a more useful approach';
    $companyTone = !empty($profile['brand_voice']) ? $profile['brand_voice'] : 'clear, confident, practical';
    $briefSummary = trim(preg_replace('/\s+/', ' ', strtok((string) $brief, "\n")));
    $audienceLower = strtolower($audience);
    $productLower = strtolower($product);
    $industryLower = strtolower($industry);
    $themes = [
        [
            'title' => 'Founder take for ' . $company,
            'overlay' => 'Still choosing style over function?',
            'hook' => 'Still choosing style over function?',
            'caption' => 'Busy ' . $audienceLower . ' who want something that looks sharp and actually lasts get a more useful option through ' . $company . '\'s ' . $productLower . ' approach.',
            'cta' => 'See the difference',
        ],
        [
            'title' => 'Educational post for ' . $company,
            'overlay' => 'Is your leash routine working?',
            'hook' => 'Is your leash routine working?',
            'caption' => 'Dog owners who struggle with messy walks and weak gear get a cleaner, more reliable experience through ' . $company . '\'s better-built ' . $productLower . '.',
            'cta' => 'Save this tip',
        ],
        [
            'title' => 'Customer transformation for ' . $company,
            'overlay' => 'Make every walk feel easier',
            'hook' => 'Make every walk feel easier',
            'caption' => 'Pet owners who are tired of awkward, forgettable accessories get a smarter everyday upgrade through ' . $company . '\'s creative ' . $productLower . '.',
            'cta' => 'Shop the look',
        ],
        [
            'title' => 'Framework post for ' . $company,
            'overlay' => 'Want calmer walks fast?',
            'hook' => 'Want calmer walks fast?',
            'caption' => 'Style-conscious ' . $audienceLower . ' who want more control without losing personality get a more thoughtful solution through ' . $company . '\'s design-led ' . $productLower . '.',
            'cta' => 'Try it now',
        ],
        [
            'title' => 'Myth busting post for ' . $company,
            'overlay' => 'Cheap leashes cost more later',
            'hook' => 'Cheap leashes cost more later',
            'caption' => 'Pet owners who keep replacing low-quality gear get longer-lasting value through ' . $company . '\'s more durable and design-forward ' . $productLower . '.',
            'cta' => 'Upgrade your gear',
        ],
        [
            'title' => 'Case study post for ' . $company,
            'overlay' => 'Better gear changes the walk',
            'hook' => 'Better gear changes the walk',
            'caption' => 'Active ' . $audienceLower . ' who want more comfort and confidence on every outing get both through ' . $company . '\'s well-designed ' . $productLower . '.',
            'cta' => 'Find your fit',
        ],
        [
            'title' => 'Trend reaction post for ' . $company,
            'overlay' => 'Pet style should still perform',
            'hook' => 'Pet style should still perform',
            'caption' => 'Modern ' . $audienceLower . ' who want standout accessories without sacrificing function get both through ' . $company . '\'s practical take on ' . $productLower . '.',
            'cta' => 'Explore the collection',
        ],
        [
            'title' => 'Case study post for ' . $company,
            'overlay' => 'Your pet deserves better design',
            'hook' => 'Your pet deserves better design',
            'caption' => 'Design-minded ' . $audienceLower . ' who want accessories that feel unique and dependable get that edge through ' . $company . '\'s signature ' . $productLower . '.',
            'cta' => 'Discover more',
        ],
        [
            'title' => 'How-to post for ' . $company,
            'overlay' => 'Need better walks this week?',
            'hook' => 'Need better walks this week?',
            'caption' => 'Pet owners who want better control, cleaner design, and a more enjoyable routine get it through ' . $company . '\'s fresh approach to ' . $productLower . '.',
            'cta' => 'Shop now',
        ],
    ];
    $types = ['post', 'post', 'post', 'post', 'post', 'post', 'post', 'post', 'post'];
    $items = [];
    $designDefaults = social_media_get_design_defaults();
    $fontKeys = array_keys(social_media_get_available_fonts());
    $paletteKeys = array_keys(social_media_get_design_palette_library());

    foreach ($types as $index => $type) {
        $theme = $themes[$index];
        $design = $designDefaults[$type];
        $design['headline_font_key'] = $fontKeys[$index % max(count($fontKeys), 1)];
        $design['body_font_key'] = $fontKeys[($index + 7) % max(count($fontKeys), 1)];
        $paletteKey = $paletteKeys[$index % max(count($paletteKeys), 1)];
        $palette = social_media_get_design_palette_library()[$paletteKey];
        $design['background_palette'] = $paletteKey;
        $design['background_tone'] = $palette['tone'];
        $design['overlay_color'] = $palette['overlay'];
        $design['text_color'] = $palette['text'];
        $design['accent_color'] = $palette['accent'];
        $items[] = [
            'post_type' => $type,
            'title' => $theme['title'],
            'hook' => $theme['hook'],
            'overlay_text' => $theme['overlay'],
            'caption' => $theme['caption'] . ($briefSummary !== '' ? ' Best fit for ' . rtrim($briefSummary, '.') . '.' : ''),
            'cta' => $theme['cta'],
            'hashtags' => ['#' . preg_replace('/\s+/', '', ucwords($industry)), '#' . preg_replace('/\s+/', '', ucwords($company)), '#Marketing'],
            'visual_brief' => 'Use a polished branded layout with a clear focal point, generous spacing, and typography that matches a ' . $companyTone . ' tone.',
            'keywords' => [$industry, $product, $company, $differentiator],
            'design' => $design,
            'slides' => !empty($theme['slides']) ? $theme['slides'] : [],
            'reel_script' => !empty($theme['reel_script']) ? $theme['reel_script'] : [],
        ];
    }

    return $items;
}

function social_media_normalize_generated_items($items, $profile)
{
    $bucketed = ['post' => []];
    $fontKeys = array_keys(social_media_get_available_fonts());
    $designDefaults = social_media_get_design_defaults();

    foreach ($items as $item) {
        $type = isset($item['post_type']) ? strtolower(trim($item['post_type'])) : 'post';
        if (!isset($bucketed[$type])) {
            $type = 'post';
        }

        $item['post_type'] = $type;
        $item['title'] = trim((string) ($item['title'] ?? ucfirst($type) . ' idea'));
        $item['hook'] = trim((string) ($item['hook'] ?? $item['title']));
        $item['overlay_text'] = trim((string) ($item['overlay_text'] ?? $item['hook']));
        $item['caption'] = trim((string) ($item['caption'] ?? ''));
        $item['cta'] = trim((string) ($item['cta'] ?? ''));
        $item['visual_brief'] = trim((string) ($item['visual_brief'] ?? ''));
        $item['keywords'] = social_media_normalize_list(isset($item['keywords']) ? $item['keywords'] : []);
        $item['hashtags'] = social_media_normalize_list(isset($item['hashtags']) ? $item['hashtags'] : []);
        $item['slides'] = [];
        $item['reel_script'] = [];
        $item['design'] = social_media_normalize_design(isset($item['design']) && is_array($item['design']) ? $item['design'] : [], $type, $fontKeys, $designDefaults);

        $bucketed[$type][] = $item;
    }

    $normalized = [];
    $targets = ['post' => 9];
    foreach ($targets as $type => $targetCount) {
        while (count($bucketed[$type]) < $targetCount) {
            $fallback = social_media_generate_fallback_batch($profile);
            foreach ($fallback as $item) {
                if ($item['post_type'] === $type) {
                    $bucketed[$type][] = $item;
                    if (count($bucketed[$type]) >= $targetCount) {
                        break;
                    }
                }
            }
        }
        $normalized = array_merge($normalized, array_slice($bucketed[$type], 0, $targetCount));
    }

    return $normalized;
}

function social_media_get_font_registry()
{
    $base = ROOTPATH . '/includes/assets/social-fonts/';

    return [
        'anton' => ['label' => 'Anton', 'file' => $base . 'Anton-Regular.ttf', 'style' => 'loud condensed headline'],
        'bebas-neue' => ['label' => 'Bebas Neue', 'file' => $base . 'BebasNeue-Regular.ttf', 'style' => 'poster condensed uppercase'],
        'league-spartan' => ['label' => 'League Spartan', 'file' => $base . 'LeagueSpartan%5Bwght%5D.ttf', 'style' => 'bold geometric'],
        'montserrat' => ['label' => 'Montserrat', 'file' => $base . 'Montserrat%5Bwght%5D.ttf', 'style' => 'clean startup sans'],
        'poppins' => ['label' => 'Poppins', 'file' => $base . 'Poppins-Bold.ttf', 'body_file' => $base . 'Poppins-Regular.ttf', 'style' => 'friendly rounded sans'],
        'dm-sans' => ['label' => 'DM Sans', 'file' => $base . 'DMSans%5Bopsz%2Cwght%5D.ttf', 'style' => 'modern neutral sans'],
        'manrope' => ['label' => 'Manrope', 'file' => $base . 'Manrope%5Bwght%5D.ttf', 'style' => 'sleek modern sans'],
        'space-grotesk' => ['label' => 'Space Grotesk', 'file' => $base . 'SpaceGrotesk%5Bwght%5D.ttf', 'style' => 'tech editorial grotesk'],
        'sora' => ['label' => 'Sora', 'file' => $base . 'Sora%5Bwght%5D.ttf', 'style' => 'sharp futuristic sans'],
        'outfit' => ['label' => 'Outfit', 'file' => $base . 'Outfit%5Bwght%5D.ttf', 'style' => 'clean geometric UI'],
        'archivo-black' => ['label' => 'Archivo Black', 'file' => $base . 'ArchivoBlack-Regular.ttf', 'style' => 'heavy statement sans'],
        'oswald' => ['label' => 'Oswald', 'file' => $base . 'Oswald%5Bwght%5D.ttf', 'style' => 'narrow display sans'],
        'barlow-condensed' => ['label' => 'Barlow Condensed', 'file' => $base . 'BarlowCondensed-Bold.ttf', 'body_file' => $base . 'BarlowCondensed-Regular.ttf', 'style' => 'sports editorial condensed'],
        'libre-baskerville' => ['label' => 'Libre Baskerville', 'file' => $base . 'LibreBaskerville-Regular.ttf', 'style' => 'classic editorial serif'],
        'playfair-display' => ['label' => 'Playfair Display', 'file' => $base . 'PlayfairDisplay%5Bwght%5D.ttf', 'style' => 'luxury editorial serif'],
        'cormorant-garamond' => ['label' => 'Cormorant Garamond', 'file' => $base . 'CormorantGaramond-Regular.ttf', 'style' => 'fashion serif'],
        'abril-fatface' => ['label' => 'Abril Fatface', 'file' => $base . 'AbrilFatface-Regular.ttf', 'style' => 'bold magazine serif'],
        'lora' => ['label' => 'Lora', 'file' => $base . 'Lora%5Bwght%5D.ttf', 'style' => 'readable content serif'],
        'prata' => ['label' => 'Prata', 'file' => $base . 'Prata-Regular.ttf', 'style' => 'dramatic luxe serif'],
        'raleway' => ['label' => 'Raleway', 'file' => $base . 'Raleway%5Bwght%5D.ttf', 'style' => 'elegant thin sans'],
        'urbanist' => ['label' => 'Urbanist', 'file' => $base . 'Urbanist%5Bwght%5D.ttf', 'style' => 'clean polished sans'],
        'plus-jakarta-sans' => ['label' => 'Plus Jakarta Sans', 'file' => $base . 'PlusJakartaSans%5Bwght%5D.ttf', 'style' => 'premium startup sans'],
        'inter' => ['label' => 'Inter', 'file' => $base . 'Inter%5Bopsz%2Cwght%5D.ttf', 'style' => 'neutral ui sans'],
        'archivo' => ['label' => 'Archivo', 'file' => $base . 'Archivo%5Bwdth%2Cwght%5D.ttf', 'style' => 'bold newsroom sans'],
        'rubik' => ['label' => 'Rubik', 'file' => $base . 'Rubik%5Bwght%5D.ttf', 'style' => 'rounded geometric sans'],
        'nunito-sans' => ['label' => 'Nunito Sans', 'file' => $base . 'NunitoSans%5BYTLC%2Copsz%2Cwdth%2Cwght%5D.ttf', 'style' => 'soft approachable sans'],
        'work-sans' => ['label' => 'Work Sans', 'file' => $base . 'WorkSans%5Bwght%5D.ttf', 'style' => 'practical sans'],
        'josefin-sans' => ['label' => 'Josefin Sans', 'file' => $base . 'JosefinSans%5Bwght%5D.ttf', 'style' => 'retro elegant sans'],
        'mulish' => ['label' => 'Mulish', 'file' => $base . 'Mulish%5Bwght%5D.ttf', 'style' => 'clean readable sans'],
        'figtree' => ['label' => 'Figtree', 'file' => $base . 'Figtree%5Bwght%5D.ttf', 'style' => 'friendly modern sans'],
        'epilogue' => ['label' => 'Epilogue', 'file' => $base . 'Epilogue%5Bwght%5D.ttf', 'style' => 'strong balanced sans'],
        'ibm-plex-sans' => ['label' => 'IBM Plex Sans', 'file' => $base . 'IBMPlexSans-Bold.ttf', 'body_file' => $base . 'IBMPlexSans-Regular.ttf', 'style' => 'technical humanist sans'],
        'karla' => ['label' => 'Karla', 'file' => $base . 'Karla%5Bwght%5D.ttf', 'style' => 'compact editorial sans'],
        'cabin' => ['label' => 'Cabin', 'file' => $base . 'Cabin%5Bwght%5D.ttf', 'style' => 'friendly sturdy sans'],
        'bricolage-grotesque' => ['label' => 'Bricolage Grotesque', 'file' => $base . 'BricolageGrotesque%5Bopsz%2Cwdth%2Cwght%5D.ttf', 'style' => 'expressive modern grotesk'],
        'syne' => ['label' => 'Syne', 'file' => $base . 'Syne%5Bwght%5D.ttf', 'style' => 'experimental display'],
        'fraunces' => ['label' => 'Fraunces', 'file' => $base . 'Fraunces%5Bopsz%2CSOFT%2CWONK%2Cwght%5D.ttf', 'style' => 'quirky editorial serif'],
        'jost' => ['label' => 'Jost', 'file' => $base . 'Jost%5Bwght%5D.ttf', 'style' => 'clean geometric sans'],
    ];
}

function social_media_is_font_file($path)
{
    if (empty($path) || !file_exists($path) || !is_file($path)) {
        return false;
    }

    $handle = @fopen($path, 'rb');
    if (!$handle) {
        return false;
    }

    $sample = fread($handle, 128);
    fclose($handle);
    if ($sample === false) {
        return false;
    }

    $lower = strtolower($sample);
    if (strpos($lower, '<!doctype html') !== false || strpos($lower, '<html') !== false) {
        return false;
    }

    $signature = substr($sample, 0, 4);
    return in_array($signature, ["\x00\x01\x00\x00", 'OTTO', 'true', 'ttcf'], true);
}

function social_media_get_available_fonts()
{
    static $available = null;

    if ($available !== null) {
        return $available;
    }

    $available = [];
    foreach (social_media_get_font_registry() as $key => $font) {
        if (!empty($font['file']) && social_media_is_font_file($font['file'])) {
            $available[$key] = $font;
        }
    }

    return $available;
}

function social_media_get_font_prompt_catalog()
{
    $lines = [];
    foreach (social_media_get_available_fonts() as $key => $font) {
        $lines[] = $key . ' = ' . $font['label'] . ' (' . $font['style'] . ')';
    }
    return implode("\n", $lines);
}

function social_media_get_design_palette_library()
{
    return [
        'midnight-amber' => ['label' => 'Midnight Amber', 'background' => '#08111C', 'overlay' => '#08111C', 'text' => '#F5F7FA', 'accent' => '#FFB547', 'tone' => 'dark'],
        'ink-stone' => ['label' => 'Ink Stone', 'background' => '#101726', 'overlay' => '#101726', 'text' => '#F2F4F8', 'accent' => '#8AE0FF', 'tone' => 'dark'],
        'sand-charcoal' => ['label' => 'Sand Charcoal', 'background' => '#1B1A17', 'overlay' => '#1B1A17', 'text' => '#F9F3E8', 'accent' => '#DAB785', 'tone' => 'earthy'],
        'cream-ink' => ['label' => 'Cream Ink', 'background' => '#F7F2E9', 'overlay' => '#F7F2E9', 'text' => '#17202A', 'accent' => '#CA7C1D', 'tone' => 'light'],
        'sage-graphite' => ['label' => 'Sage Graphite', 'background' => '#E7EFE8', 'overlay' => '#16221D', 'text' => '#0D1512', 'accent' => '#3E7C59', 'tone' => 'soft'],
        'electric-cobalt' => ['label' => 'Electric Cobalt', 'background' => '#0E1F5B', 'overlay' => '#08143B', 'text' => '#FFFFFF', 'accent' => '#7ED7FF', 'tone' => 'vivid'],
        'rosewood' => ['label' => 'Rosewood', 'background' => '#301A22', 'overlay' => '#301A22', 'text' => '#FFF3F5', 'accent' => '#F28CA5', 'tone' => 'editorial'],
        'forest-lime' => ['label' => 'Forest Lime', 'background' => '#12261E', 'overlay' => '#12261E', 'text' => '#F4FBF6', 'accent' => '#B9FF6A', 'tone' => 'bold'],
        'mono-slate' => ['label' => 'Mono Slate', 'background' => '#20242D', 'overlay' => '#20242D', 'text' => '#F6F8FB', 'accent' => '#D1D7E0', 'tone' => 'minimal'],
        'sunset-coral' => ['label' => 'Sunset Coral', 'background' => '#FCE9E4', 'overlay' => '#7A2E2E', 'text' => '#231616', 'accent' => '#E75B55', 'tone' => 'warm'],
    ];
}

function social_media_get_palette_prompt_catalog()
{
    $lines = [];
    foreach (social_media_get_design_palette_library() as $key => $palette) {
        $lines[] = $key . ' = ' . $palette['label'] . ' (' . $palette['tone'] . ', bg ' . $palette['background'] . ', accent ' . $palette['accent'] . ')';
    }
    return implode("\n", $lines);
}

function social_media_get_design_defaults()
{
    return [
        'post' => [
            'headline_font_key' => 'anton',
            'body_font_key' => 'dm-sans',
            'headline_size' => 100,
            'body_size' => 28,
            'text_case' => 'uppercase',
            'text_align' => 'center',
            'overlay_color' => '#08111C',
            'overlay_opacity' => 0.34,
            'text_color' => '#FFFFFF',
            'accent_color' => '#FFB547',
            'background_tone' => 'dark',
            'asset_tags' => ['bold', 'clean'],
        ],
        'reel' => [
            'headline_font_key' => 'league-spartan',
            'body_font_key' => 'dm-sans',
            'headline_size' => 72,
            'body_size' => 24,
            'text_case' => 'uppercase',
            'text_align' => 'center',
            'overlay_color' => '#08111C',
            'overlay_opacity' => 0.22,
            'text_color' => '#FFFFFF',
            'accent_color' => '#FFB547',
            'background_tone' => 'dark',
            'asset_tags' => ['motion', 'bold'],
        ],
    ];
}

function social_media_normalize_hex_color($color, $fallback)
{
    $color = trim((string) $color);
    if (preg_match('/^#?[0-9a-fA-F]{6}$/', $color)) {
        return '#' . strtoupper(ltrim($color, '#'));
    }
    if (preg_match('/^#?[0-9a-fA-F]{3}$/', $color)) {
        $value = strtoupper(ltrim($color, '#'));
        return '#' . $value[0] . $value[0] . $value[1] . $value[1] . $value[2] . $value[2];
    }
    return $fallback;
}

function social_media_normalize_design($design, $type, $fontKeys, $defaultsMap)
{
    $defaults = isset($defaultsMap[$type]) ? $defaultsMap[$type] : reset($defaultsMap);
    $normalized = array_merge($defaults, is_array($design) ? $design : []);
    $palettes = social_media_get_design_palette_library();
    $paletteKey = isset($normalized['background_tone']) ? strtolower(trim((string) $normalized['background_tone'])) : '';

    if (isset($palettes[$paletteKey])) {
        $palette = $palettes[$paletteKey];
        $normalized['background_palette'] = $paletteKey;
        $normalized['background_tone'] = $palette['tone'];
        $normalized['overlay_color'] = social_media_normalize_hex_color(isset($normalized['overlay_color']) ? $normalized['overlay_color'] : $palette['overlay'], $palette['overlay']);
        $normalized['text_color'] = social_media_normalize_hex_color(isset($normalized['text_color']) ? $normalized['text_color'] : $palette['text'], $palette['text']);
        $normalized['accent_color'] = social_media_normalize_hex_color(isset($normalized['accent_color']) ? $normalized['accent_color'] : $palette['accent'], $palette['accent']);
    } else {
        $normalized['overlay_color'] = social_media_normalize_hex_color(isset($normalized['overlay_color']) ? $normalized['overlay_color'] : $defaults['overlay_color'], $defaults['overlay_color']);
        $normalized['text_color'] = social_media_normalize_hex_color(isset($normalized['text_color']) ? $normalized['text_color'] : $defaults['text_color'], $defaults['text_color']);
        $normalized['accent_color'] = social_media_normalize_hex_color(isset($normalized['accent_color']) ? $normalized['accent_color'] : $defaults['accent_color'], $defaults['accent_color']);
        $normalized['background_tone'] = $defaults['background_tone'];
        $normalized['background_palette'] = !empty($defaults['background_palette']) ? $defaults['background_palette'] : '';
    }

    if (empty($fontKeys)) {
        $fontKeys = ['fallback'];
    }
    if (empty($normalized['headline_font_key']) || !in_array($normalized['headline_font_key'], $fontKeys, true)) {
        $normalized['headline_font_key'] = in_array($defaults['headline_font_key'], $fontKeys, true) ? $defaults['headline_font_key'] : $fontKeys[0];
    }
    if (empty($normalized['body_font_key']) || !in_array($normalized['body_font_key'], $fontKeys, true)) {
        $normalized['body_font_key'] = in_array($defaults['body_font_key'], $fontKeys, true) ? $defaults['body_font_key'] : $fontKeys[0];
    }

    $normalized['headline_size'] = max(28, min(120, (int) $normalized['headline_size']));
    $normalized['body_size'] = max(16, min(48, (int) $normalized['body_size']));
    $normalized['overlay_opacity'] = min(0.72, max(0.08, (float) $normalized['overlay_opacity']));
    $normalized['text_case'] = in_array($normalized['text_case'], ['uppercase', 'title', 'sentence', 'lowercase'], true) ? $normalized['text_case'] : $defaults['text_case'];
    $normalized['text_align'] = in_array($normalized['text_align'], ['left', 'center', 'right'], true) ? $normalized['text_align'] : $defaults['text_align'];
    $normalized['asset_tags'] = social_media_normalize_list(isset($normalized['asset_tags']) ? $normalized['asset_tags'] : []);

    return $normalized;
}

function social_media_font_path($fontKey = '', $useBodyFile = false)
{
    $available = social_media_get_available_fonts();
    if ($fontKey !== '' && !empty($available[$fontKey])) {
        if ($useBodyFile && !empty($available[$fontKey]['body_file']) && social_media_is_font_file($available[$fontKey]['body_file'])) {
            return $available[$fontKey]['body_file'];
        }
        return $available[$fontKey]['file'];
    }

    $paths = [
        '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
        '/usr/share/fonts/TTF/DejaVuSans-Bold.ttf',
        '/Library/Fonts/Arial Bold.ttf',
        '/Library/Fonts/Arial.ttf',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            return $path;
        }
    }

    return '';
}

function social_media_load_image_resource($path)
{
    if (!file_exists($path)) {
        return null;
    }

    $info = @getimagesize($path);
    if (empty($info['mime'])) {
        return null;
    }

    if ($info['mime'] === 'image/jpeg') {
        return imagecreatefromjpeg($path);
    }
    if ($info['mime'] === 'image/png') {
        return imagecreatefrompng($path);
    }
    if ($info['mime'] === 'image/gif') {
        return imagecreatefromgif($path);
    }
    if ($info['mime'] === 'image/webp' && function_exists('imagecreatefromwebp')) {
        return imagecreatefromwebp($path);
    }

    return null;
}

function social_media_shell_available($functionName)
{
    if (!function_exists($functionName)) {
        return false;
    }

    $disabled = ini_get('disable_functions');
    if (empty($disabled)) {
        return true;
    }

    $disabledList = array_map('trim', explode(',', $disabled));
    return !in_array($functionName, $disabledList, true);
}

function social_media_command_path($binary)
{
    $env = get_env_setting(strtoupper($binary) . '_PATH');
    if (!empty($env) && file_exists($env)) {
        return $env;
    }

    if (social_media_shell_available('shell_exec')) {
        $path = trim((string) shell_exec('command -v ' . escapeshellarg($binary) . ' 2>/dev/null'));
        if ($path !== '') {
            return $path;
        }
    }

    return '';
}

function social_media_ffmpeg_path()
{
    return social_media_command_path('ffmpeg');
}

function social_media_tesseract_path()
{
    return social_media_command_path('tesseract');
}

function social_media_asset_source_path($asset)
{
    if (!empty($asset['remote_url'])) {
        if (!empty($asset['remote_provider']) && $asset['remote_provider'] === 'unsplash') {
            social_media_track_unsplash_download($asset);
        }
        return social_media_download_remote_file($asset['remote_url']);
    }
    if (!empty($asset['file_name'])) {
        return ROOTPATH . '/storage/social_assets/' . $asset['file_name'];
    }
    return '';
}

function social_media_asset_preview_path($asset)
{
    if (!empty($asset['remote_preview_url'])) {
        return social_media_download_remote_file($asset['remote_preview_url']);
    }
    if (!empty($asset['preview_name'])) {
        return ROOTPATH . '/storage/social_assets/' . $asset['preview_name'];
    }
    return '';
}

function social_media_asset_unique_key($asset)
{
    if (!empty($asset['remote_id'])) {
        return (string) $asset['remote_id'];
    }
    if (!empty($asset['id'])) {
        return 'local:' . (string) $asset['id'];
    }
    if (!empty($asset['file_name'])) {
        return 'file:' . (string) $asset['file_name'];
    }
    if (!empty($asset['remote_url'])) {
        return 'url:' . md5((string) $asset['remote_url']);
    }
    return '';
}

function social_media_analyze_asset($asset)
{
    $sourcePath = social_media_asset_source_path($asset);
    $previewPath = social_media_asset_preview_path($asset);
    $previewName = !empty($asset['preview_name']) ? $asset['preview_name'] : '';

    if ($asset['asset_type'] === 'video' && empty($previewPath)) {
        $extractedPreview = social_media_extract_video_preview($asset);
        if (!empty($extractedPreview)) {
            $previewName = $extractedPreview;
            $previewPath = ROOTPATH . '/storage/social_assets/' . $previewName;
        }
    }

    $analysis = [
        'asset_type' => $asset['asset_type'],
        'preview_name' => $previewName,
        'source_width' => 0,
        'source_height' => 0,
        'preview_width' => 0,
        'preview_height' => 0,
        'brightness' => [],
        'clutter' => [],
        'best_text_zone' => 'center',
        'suggested_text_color' => '#FFFFFF',
        'overlay_opacity' => 0.36,
        'ocr_text' => '',
        'dominant_colors' => ['#08111C', '#15253A', '#F5F7FA'],
        'background_tone' => 'dark',
        'template_kind' => 'background',
        'empty_layout_score' => 0.5,
        'analysis_version' => 1,
    ];

    if ($sourcePath && file_exists($sourcePath)) {
        if ($asset['asset_type'] === 'image') {
            $size = @getimagesize($sourcePath);
            if ($size) {
                $analysis['source_width'] = $size[0];
                $analysis['source_height'] = $size[1];
            }
        } else {
            $videoInfo = social_media_probe_video_dimensions($sourcePath);
            if (!empty($videoInfo['width'])) {
                $analysis['source_width'] = $videoInfo['width'];
                $analysis['source_height'] = $videoInfo['height'];
            }
        }
    }

    if ($previewPath && file_exists($previewPath)) {
        $previewInfo = @getimagesize($previewPath);
        if ($previewInfo) {
            $analysis['preview_width'] = $previewInfo[0];
            $analysis['preview_height'] = $previewInfo[1];
        }

        $zoneMetrics = social_media_measure_asset_zones($previewPath);
        $analysis['brightness'] = $zoneMetrics['brightness'];
        $analysis['clutter'] = $zoneMetrics['clutter'];
        $analysis['best_text_zone'] = $zoneMetrics['best_zone'];
        $analysis['suggested_text_color'] = $zoneMetrics['text_color'];
        $analysis['overlay_opacity'] = $zoneMetrics['overlay_opacity'];
        $analysis['dominant_colors'] = $zoneMetrics['dominant_colors'];
        $analysis['background_tone'] = $zoneMetrics['background_tone'];
        $analysis['ocr_text'] = social_media_ocr_image($previewPath);
        $ocrWeight = strlen(trim($analysis['ocr_text'])) > 16 ? 0.55 : 0.0;
        $avgClutter = array_sum($analysis['clutter']) / max(count($analysis['clutter']), 1);
        $analysis['empty_layout_score'] = max(0, min(1, 1 - min(1, ($avgClutter * 1.45) + $ocrWeight)));
        $analysis['template_kind'] = $analysis['empty_layout_score'] >= 0.58 ? 'background' : 'reference';
    }

    return $analysis;
}

function social_media_extract_video_preview($asset)
{
    $ffmpeg = social_media_ffmpeg_path();
    if ($ffmpeg === '') {
        return '';
    }

    $sourcePath = social_media_asset_source_path($asset);
    if (!$sourcePath || !file_exists($sourcePath) || !social_media_shell_available('exec')) {
        return '';
    }

    $output = uniqid('preview_') . '.jpg';
    $outputPath = ROOTPATH . '/storage/social_assets/' . $output;
    $cmd = escapeshellarg($ffmpeg)
        . ' -y -i ' . escapeshellarg($sourcePath)
        . ' -ss 00:00:01.000 -vframes 1 '
        . escapeshellarg($outputPath) . ' 2>&1';

    $lines = [];
    $status = 0;
    exec($cmd, $lines, $status);
    return ($status === 0 && file_exists($outputPath)) ? $output : '';
}

function social_media_probe_video_dimensions($path)
{
    $ffmpeg = social_media_ffmpeg_path();
    if ($ffmpeg === '' || !social_media_shell_available('exec')) {
        return ['width' => 0, 'height' => 0];
    }

    $cmd = escapeshellarg($ffmpeg) . ' -i ' . escapeshellarg($path) . ' 2>&1';
    $lines = [];
    $status = 0;
    exec($cmd, $lines, $status);
    $output = implode("\n", $lines);
    if (preg_match('/, (\d{2,5})x(\d{2,5})[,\s]/', $output, $match)) {
        return ['width' => (int) $match[1], 'height' => (int) $match[2]];
    }

    return ['width' => 0, 'height' => 0];
}

function social_media_measure_asset_zones($imagePath)
{
    $image = social_media_load_image_resource($imagePath);
    if (!$image) {
        return [
            'brightness' => ['top' => 120, 'center' => 120, 'bottom' => 120],
            'clutter' => ['top' => 0.5, 'center' => 0.5, 'bottom' => 0.5],
            'best_zone' => 'center',
            'text_color' => '#FFFFFF',
            'overlay_opacity' => 0.36,
            'dominant_colors' => ['#08111C', '#15253A', '#F5F7FA'],
            'background_tone' => 'dark',
        ];
    }

    $sampleW = 60;
    $sampleH = 60;
    $sample = imagecreatetruecolor($sampleW, $sampleH);
    imagecopyresampled($sample, $image, 0, 0, 0, 0, $sampleW, $sampleH, imagesx($image), imagesy($image));
    imagedestroy($image);

    $segments = [
        'top' => [0, 19],
        'center' => [20, 39],
        'bottom' => [40, 59],
    ];
    $brightness = [];
    $clutter = [];
    $bestZone = 'center';
    $bestScore = null;
    $colorBuckets = [];
    $satSum = 0;
    $satCount = 0;

    foreach ($segments as $zone => $range) {
        $sum = 0;
        $count = 0;
        $edge = 0;
        for ($y = $range[0]; $y <= $range[1]; $y++) {
            for ($x = 0; $x < $sampleW; $x++) {
                $rgb = imagecolorat($sample, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $lum = (0.299 * $r) + (0.587 * $g) + (0.114 * $b);
                $sum += $lum;
                $count++;
                $max = max($r, $g, $b);
                $min = min($r, $g, $b);
                $satSum += ($max === 0) ? 0 : (($max - $min) / $max);
                $satCount++;
                $bucket = sprintf('#%02X%02X%02X', (int) min(255, round($r / 32) * 32), (int) min(255, round($g / 32) * 32), (int) min(255, round($b / 32) * 32));
                if (!isset($colorBuckets[$bucket])) {
                    $colorBuckets[$bucket] = 0;
                }
                $colorBuckets[$bucket]++;

                if ($x > 0) {
                    $prev = imagecolorat($sample, $x - 1, $y);
                    $pr = ($prev >> 16) & 0xFF;
                    $pg = ($prev >> 8) & 0xFF;
                    $pb = $prev & 0xFF;
                    $edge += abs($r - $pr) + abs($g - $pg) + abs($b - $pb);
                }
            }
        }

        $brightness[$zone] = $count ? round($sum / $count, 2) : 120;
        $clutter[$zone] = $count ? round($edge / ($count * 3), 4) : 0.5;
        $score = $clutter[$zone] + (abs($brightness[$zone] - 128) / 255);
        if ($bestScore === null || $score < $bestScore) {
            $bestScore = $score;
            $bestZone = $zone;
        }
    }

    imagedestroy($sample);

    $zoneBrightness = $brightness[$bestZone];
    $textColor = $zoneBrightness > 155 ? '#0B1220' : '#FFFFFF';
    $overlayOpacity = $zoneBrightness > 155 ? 0.22 : 0.40;
    arsort($colorBuckets);
    $dominantColors = array_slice(array_keys($colorBuckets), 0, 3);
    while (count($dominantColors) < 3) {
        $dominantColors[] = end($dominantColors) ?: '#08111C';
    }
    $avgBrightness = array_sum($brightness) / max(count($brightness), 1);
    $avgSaturation = $satCount ? $satSum / $satCount : 0;
    if ($avgBrightness > 180) {
        $backgroundTone = 'light';
    } elseif ($avgBrightness < 80 && $avgSaturation < 0.22) {
        $backgroundTone = 'dark';
    } elseif ($avgSaturation > 0.42) {
        $backgroundTone = 'vivid';
    } elseif ($avgSaturation < 0.18) {
        $backgroundTone = 'minimal';
    } else {
        $backgroundTone = 'soft';
    }

    return [
        'brightness' => $brightness,
        'clutter' => $clutter,
        'best_zone' => $bestZone,
        'text_color' => $textColor,
        'overlay_opacity' => $overlayOpacity,
        'dominant_colors' => $dominantColors,
        'background_tone' => $backgroundTone,
    ];
}

function social_media_ocr_image($imagePath)
{
    $tesseract = social_media_tesseract_path();
    if ($tesseract === '' || !social_media_shell_available('exec')) {
        return '';
    }

    $cmd = escapeshellarg($tesseract) . ' ' . escapeshellarg($imagePath) . ' stdout 2>/dev/null';
    $lines = [];
    $status = 0;
    exec($cmd, $lines, $status);
    if ($status !== 0) {
        return '';
    }

    return trim(implode("\n", $lines));
}

function social_media_generate_template_manifest($asset, $analysis)
{
    $variants = [];
    $zones = ['top', 'center', 'bottom'];
    $bestZone = in_array($analysis['best_text_zone'], $zones, true) ? $analysis['best_text_zone'] : 'center';

    foreach (social_media_get_format_map() as $format => $spec) {
        $variants[$format] = social_media_build_manifest_variant($spec, $analysis, $bestZone, $format);
    }

    return [
        'version' => 1,
        'render_preset' => !empty($asset['render_preset']) ? $asset['render_preset'] : 'auto',
        'variants' => $variants,
        'ocr_text' => $analysis['ocr_text'],
        'background_tone' => !empty($analysis['background_tone']) ? $analysis['background_tone'] : 'dark',
        'dominant_colors' => !empty($analysis['dominant_colors']) ? $analysis['dominant_colors'] : ['#08111C', '#15253A', '#F5F7FA'],
    ];
}

function social_media_build_manifest_variant($spec, $analysis, $bestZone, $format)
{
    $width = $spec['width'];
    $height = $spec['height'];
    $padding = 84;
    $zoneYMap = [
        'top' => 180,
        'center' => (int) floor($height * 0.28),
        'bottom' => (int) floor($height * 0.50),
    ];
    $baseY = $zoneYMap[$bestZone];
    $headlineSize = $format === 'reel' ? 92 : 100;
    $subheadlineSize = $format === 'reel' ? 28 : 26;
    $ctaSize = 24;

    return [
        'width' => $width,
        'height' => $height,
        'overlay' => [
            'color' => '#07111C',
            'opacity' => $analysis['overlay_opacity'],
        ],
        'zones' => [
            'logo' => [
                'x' => $padding,
                'y' => 40,
                'width' => 120,
                'height' => 120,
            ],
            'label' => [
                'x' => $padding,
                'y' => $baseY,
                'width' => 240,
                'height' => 40,
                'font_size' => 26,
                'color' => '#FFAD33',
                'align' => 'left',
                'max_lines' => 1,
            ],
            'headline' => [
                'x' => $padding,
                'y' => $baseY + 54,
                'width' => $width - ($padding * 2),
                'height' => $format === 'reel' ? 360 : 300,
                'font_size' => $headlineSize,
                'min_font_size' => 28,
                'line_height' => 1.12,
                'color' => $analysis['suggested_text_color'],
                'align' => 'left',
                'max_lines' => $format === 'reel' ? 5 : 3,
                'shrink_to_fit' => true,
            ],
            'subheadline' => [
                'x' => $padding,
                'y' => $baseY + 320,
                'width' => $width - ($padding * 2),
                'height' => 180,
                'font_size' => $subheadlineSize,
                'min_font_size' => 18,
                'line_height' => 1.28,
                'color' => $analysis['suggested_text_color'] === '#FFFFFF' ? '#D7E0EB' : '#253142',
                'align' => 'left',
                'max_lines' => 4,
                'shrink_to_fit' => true,
            ],
            'cta' => [
                'x' => $padding,
                'y' => $height - 120,
                'width' => $width - ($padding * 2),
                'height' => 40,
                'font_size' => $ctaSize,
                'min_font_size' => 18,
                'line_height' => 1.0,
                'color' => '#D7E0EB',
                'align' => 'left',
                'max_lines' => 1,
                'shrink_to_fit' => true,
            ],
            'brand' => [
                'x' => $padding,
                'y' => $height - 160,
                'width' => $width - ($padding * 2),
                'height' => 40,
                'font_size' => 24,
                'min_font_size' => 18,
                'line_height' => 1.0,
                'color' => $analysis['suggested_text_color'],
                'align' => 'left',
                'max_lines' => 1,
                'shrink_to_fit' => true,
            ],
        ],
        'rules' => [
            'ideal_text_zone' => $bestZone,
            'supports_long_copy' => $format !== 'reel',
        ],
    ];
}

function social_media_render_reel_video($asset, $overlayFile)
{
    $ffmpeg = social_media_ffmpeg_path();
    if ($ffmpeg === '' || empty($asset['file_name'])) {
        return '';
    }

    $inputPath = ROOTPATH . '/storage/social_assets/' . $asset['file_name'];
    $overlayPath = ROOTPATH . '/storage/social_posts/' . $overlayFile;
    if (!file_exists($inputPath) || !file_exists($overlayPath)) {
        return '';
    }

    $videoDir = ROOTPATH . '/storage/social_posts/videos/';
    social_media_make_directory($videoDir);
    $outputFile = uniqid('reel_') . '.mp4';
    $outputPath = $videoDir . $outputFile;

    $cmd = escapeshellarg($ffmpeg)
        . ' -y -i ' . escapeshellarg($inputPath)
        . ' -loop 1 -i ' . escapeshellarg($overlayPath)
        . ' -filter_complex '
        . escapeshellarg('[0:v]scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,setsar=1[bg];[1:v]scale=1080:1920[fg];[bg][fg]overlay=0:0,format=yuv420p[v]')
        . ' -map [v] -t 8 -r 30 -an ' . escapeshellarg($outputPath) . ' 2>&1';

    if (social_media_shell_available('exec')) {
        $output = [];
        $status = 0;
        exec($cmd, $output, $status);
        if ($status === 0 && file_exists($outputPath)) {
            return $outputFile;
        }
    }

    return '';
}

function social_media_render_lines($text, $maxChars)
{
    $words = preg_split('/\s+/', trim((string) $text));
    $lines = [];
    $line = '';

    foreach ($words as $word) {
        $candidate = trim($line . ' ' . $word);
        if (strlen($candidate) > $maxChars && $line !== '') {
            $lines[] = $line;
            $line = $word;
        } else {
            $line = $candidate;
        }
    }

    if ($line !== '') {
        $lines[] = $line;
    }

    return array_slice($lines, 0, 5);
}

function social_media_wrap_text_for_box($text, $fontPath, $fontSize, $maxWidth, $maxLines)
{
    return social_media_wrap_text_measurement($text, $fontPath, $fontSize, $maxWidth, $maxLines)['lines'];
}

function social_media_wrap_text_measurement($text, $fontPath, $fontSize, $maxWidth, $maxLines)
{
    $text = trim((string) $text);
    if ($text === '') {
        return [
            'lines' => [],
            'overflow' => false,
        ];
    }

    if (!$fontPath || !function_exists('imagettfbbox')) {
        $lines = social_media_render_lines($text, max(18, (int) floor($maxWidth / max($fontSize * 0.72, 1))));
        return [
            'lines' => array_slice($lines, 0, $maxLines),
            'overflow' => count($lines) > $maxLines,
        ];
    }

    $words = preg_split('/\s+/', $text);
    $lines = [];
    $line = '';

    foreach ($words as $word) {
        $candidate = trim($line . ' ' . $word);
        $box = imagettfbbox($fontSize, 0, $fontPath, $candidate);
        $candidateWidth = abs($box[2] - $box[0]);
        if ($candidateWidth > $maxWidth && $line !== '') {
            $lines[] = $line;
            $line = $word;
        } else {
            $line = $candidate;
        }
    }

    if ($line !== '') {
        $lines[] = $line;
    }

    return [
        'lines' => array_slice($lines, 0, $maxLines),
        'overflow' => count($lines) > $maxLines,
    ];
}

function social_media_fit_text_to_zone($text, $zone, $fontPath)
{
    $fontSize = !empty($zone['font_size']) ? (int) $zone['font_size'] : 28;
    $minFontSize = !empty($zone['min_font_size']) ? (int) $zone['min_font_size'] : 16;
    $maxLines = !empty($zone['max_lines']) ? (int) $zone['max_lines'] : 3;
    $lineHeight = !empty($zone['line_height']) ? (float) $zone['line_height'] : 1.2;

    while ($fontSize >= $minFontSize) {
        $wrapped = social_media_wrap_text_measurement($text, $fontPath, $fontSize, $zone['width'], $maxLines);
        $lines = $wrapped['lines'];
        $estimatedHeight = count($lines) * (int) floor($fontSize * $lineHeight);
        if (!$wrapped['overflow'] && $estimatedHeight <= $zone['height'] && count($lines) <= $maxLines) {
            return [
                'font_size' => $fontSize,
                'lines' => $lines,
                'line_height' => $lineHeight,
            ];
        }
        $fontSize -= 2;
    }

    return [
        'font_size' => $minFontSize,
        'lines' => social_media_wrap_text_measurement($text, $fontPath, $minFontSize, $zone['width'], $maxLines)['lines'],
        'line_height' => $lineHeight,
    ];
}

function social_media_open_asset_background($asset, $width, $height, $backgroundColors = [])
{
    $canvas = imagecreatetruecolor($width, $height);
    imagealphablending($canvas, true);
    imagesavealpha($canvas, true);
    $debug = [
        'attempted_paths' => [],
        'used_path' => '',
        'used_source' => false,
        'fallback_gradient' => false,
        'remote_provider' => !empty($asset['remote_provider']) ? $asset['remote_provider'] : '',
        'remote_url' => !empty($asset['remote_url']) ? $asset['remote_url'] : '',
        'remote_preview_url' => !empty($asset['remote_preview_url']) ? $asset['remote_preview_url'] : '',
        'remote_source_path' => '',
        'remote_preview_path' => '',
        'remote_source_downloaded' => false,
        'remote_preview_downloaded' => false,
    ];

    $assetPaths = [];
    $sourcePath = social_media_asset_source_path($asset);
    $previewPath = social_media_asset_preview_path($asset);
    if (!empty($asset['remote_url'])) {
        $debug['remote_source_path'] = (string) $sourcePath;
        $debug['remote_source_downloaded'] = ($sourcePath !== '' && file_exists($sourcePath));
    }
    if (!empty($asset['remote_preview_url'])) {
        $debug['remote_preview_path'] = (string) $previewPath;
        $debug['remote_preview_downloaded'] = ($previewPath !== '' && file_exists($previewPath));
    }
    if ($sourcePath && !empty($asset['asset_type']) && $asset['asset_type'] === 'image') {
        $assetPaths[] = $sourcePath;
    }
    if ($previewPath) {
        $assetPaths[] = $previewPath;
    }

    foreach ($assetPaths as $assetPath) {
        $debug['attempted_paths'][] = $assetPath;
        if ($assetPath && file_exists($assetPath)) {
            $source = social_media_load_image_resource($assetPath);
            if ($source) {
                $srcWidth = imagesx($source);
                $srcHeight = imagesy($source);
                imagecopyresampled($canvas, $source, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
                imagedestroy($source);
                $debug['used_path'] = $assetPath;
                $debug['used_source'] = true;
                return ['canvas' => $canvas, 'debug' => $debug];
            }
        }
    }

    $fallbackTop = !empty($backgroundColors[0]) ? $backgroundColors[0] : (!empty($asset['analysis']['dominant_colors'][0]) ? $asset['analysis']['dominant_colors'][0] : '#1E2638');
    $fallbackBottom = !empty($backgroundColors[1]) ? $backgroundColors[1] : (!empty($asset['analysis']['dominant_colors'][1]) ? $asset['analysis']['dominant_colors'][1] : '#08111C');
    list($topR, $topG, $topB) = social_media_hex_to_rgb($fallbackTop);
    list($bottomR, $bottomG, $bottomB) = social_media_hex_to_rgb($fallbackBottom);
    $top = imagecolorallocate($canvas, $topR, $topG, $topB);
    $bottom = imagecolorallocate($canvas, $bottomR, $bottomG, $bottomB);
    imagefilledrectangle($canvas, 0, 0, $width, $height / 2, $top);
    imagefilledrectangle($canvas, 0, $height / 2, $width, $height, $bottom);
    $debug['fallback_gradient'] = true;
    return ['canvas' => $canvas, 'debug' => $debug];
}

function social_media_render_text_block($canvas, $text, $x, $y, $width, $fontSize, $color, $fontPath)
{
    $lines = social_media_render_lines($text, max(18, (int) floor($width / ($fontSize * 0.72))));
    $currentY = $y;

    foreach ($lines as $line) {
        if ($fontPath && function_exists('imagettftext')) {
            imagettftext($canvas, $fontSize, 0, $x, $currentY, $color, $fontPath, $line);
            $currentY += (int) floor($fontSize * 1.45);
        } else {
            imagestring($canvas, 5, $x, $currentY - 15, $line, $color);
            $currentY += 24;
        }
    }

    return $currentY;
}

function social_media_hex_to_rgb($hex)
{
    $hex = ltrim((string) $hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}

function social_media_relative_luminance($hex)
{
    list($r, $g, $b) = social_media_hex_to_rgb($hex);
    $channels = [$r / 255, $g / 255, $b / 255];
    foreach ($channels as &$channel) {
        $channel = ($channel <= 0.03928) ? ($channel / 12.92) : pow(($channel + 0.055) / 1.055, 2.4);
    }
    unset($channel);
    return (0.2126 * $channels[0]) + (0.7152 * $channels[1]) + (0.0722 * $channels[2]);
}

function social_media_contrast_ratio($colorA, $colorB)
{
    $l1 = social_media_relative_luminance($colorA);
    $l2 = social_media_relative_luminance($colorB);
    $lighter = max($l1, $l2);
    $darker = min($l1, $l2);
    return ($lighter + 0.05) / ($darker + 0.05);
}

function social_media_render_zone_text($canvas, $text, $zone, $fontPath)
{
    if (trim((string) $text) === '' || empty($zone) || (!empty($zone['height']) && (int) $zone['height'] <= 0) || (!empty($zone['max_lines']) && (int) $zone['max_lines'] <= 0)) {
        return;
    }

    list($r, $g, $b) = social_media_hex_to_rgb(isset($zone['color']) ? $zone['color'] : '#FFFFFF');
    $color = imagecolorallocate($canvas, $r, $g, $b);
    $fit = social_media_fit_text_to_zone($text, $zone, $fontPath);
    $fontSize = $fit['font_size'];
    $lineHeight = (int) floor($fontSize * $fit['line_height']);
    $y = $zone['y'] + $fontSize;

    foreach ($fit['lines'] as $line) {
        $x = $zone['x'];
        if (!empty($zone['align']) && $zone['align'] !== 'left' && $fontPath && function_exists('imagettfbbox')) {
            $box = imagettfbbox($fontSize, 0, $fontPath, $line);
            $lineWidth = abs($box[2] - $box[0]);
            if ($zone['align'] === 'center') {
                $x = $zone['x'] + (int) floor(($zone['width'] - $lineWidth) / 2);
            } elseif ($zone['align'] === 'right') {
                $x = $zone['x'] + $zone['width'] - $lineWidth;
            }
        }

        if ($fontPath && function_exists('imagettftext')) {
            imagettftext($canvas, $fontSize, 0, $x, $y, $color, $fontPath, $line);
        } else {
            imagestring($canvas, 5, $x, $y - 15, $line, $color);
        }
        $y += $lineHeight;
    }
}

function social_media_transform_text_case($text, $mode)
{
    $text = trim((string) $text);
    switch ($mode) {
        case 'uppercase':
            return function_exists('mb_strtoupper') ? mb_strtoupper($text, 'UTF-8') : strtoupper($text);
        case 'lowercase':
            return function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
        case 'title':
            return function_exists('mb_convert_case') ? mb_convert_case($text, MB_CASE_TITLE, 'UTF-8') : ucwords(strtolower($text));
        default:
            return $text;
    }
}

function social_media_get_palette_by_tone($tone)
{
    $palettes = social_media_get_design_palette_library();
    if (!empty($palettes[$tone])) {
        return $palettes[$tone];
    }

    foreach ($palettes as $palette) {
        if ($palette['tone'] === $tone) {
            return $palette;
        }
    }

    return reset($palettes);
}

function social_media_apply_design_to_variant($variant, $design, $asset)
{
    $palette = social_media_get_palette_by_tone(isset($design['background_tone']) ? $design['background_tone'] : 'dark');
    $dominant = !empty($asset['analysis']['dominant_colors']) ? $asset['analysis']['dominant_colors'] : [$palette['background'], $palette['overlay']];
    $suggestedTextColor = !empty($asset['analysis']['suggested_text_color']) ? social_media_normalize_hex_color($asset['analysis']['suggested_text_color'], $palette['text']) : $palette['text'];
    $designTextColor = social_media_normalize_hex_color(isset($design['text_color']) ? $design['text_color'] : $palette['text'], $palette['text']);
    $primaryBackground = social_media_normalize_hex_color(isset($dominant[0]) ? $dominant[0] : $palette['background'], $palette['background']);
    if (social_media_contrast_ratio($designTextColor, $primaryBackground) < 3.8) {
        $design['text_color'] = $suggestedTextColor;
        if (social_media_contrast_ratio(social_media_normalize_hex_color(isset($design['accent_color']) ? $design['accent_color'] : $palette['accent'], $palette['accent']), $primaryBackground) < 2.8) {
            $design['accent_color'] = $suggestedTextColor;
        }
        $design['overlay_opacity'] = max(isset($design['overlay_opacity']) ? (float) $design['overlay_opacity'] : 0.32, !empty($asset['analysis']['overlay_opacity']) ? (float) $asset['analysis']['overlay_opacity'] : 0.32, 0.3);
    }
    $variant['overlay']['color'] = social_media_normalize_hex_color(isset($design['overlay_color']) ? $design['overlay_color'] : $palette['overlay'], $palette['overlay']);
    $variant['overlay']['opacity'] = min(0.72, max(0.08, isset($design['overlay_opacity']) ? (float) $design['overlay_opacity'] : 0.32));
    $variant['background_colors'] = [
        $primaryBackground,
        social_media_normalize_hex_color(isset($dominant[1]) ? $dominant[1] : $palette['overlay'], $palette['overlay']),
    ];
    if (!empty($design['background_colors']) && is_array($design['background_colors'])) {
        $forcedColors = array_values(array_filter(array_map(function ($color) {
            return social_media_normalize_hex_color($color, '');
        }, $design['background_colors'])));
        if (!empty($forcedColors)) {
            $variant['background_colors'] = [
                $forcedColors[0],
                !empty($forcedColors[1]) ? $forcedColors[1] : $forcedColors[0],
            ];
        }
    }

    foreach (['headline', 'subheadline', 'brand', 'cta'] as $zoneName) {
        if (!empty($variant['zones'][$zoneName])) {
            $variant['zones'][$zoneName]['align'] = $design['text_align'];
        }
    }

    if ($design['text_align'] === 'center') {
        $contentWidth = (int) floor($variant['width'] * 0.84);
        $contentX = (int) floor(($variant['width'] - $contentWidth) / 2);
        $variant['zones']['label']['x'] = $contentX;
        $variant['zones']['label']['width'] = $contentWidth;
        $variant['zones']['label']['align'] = 'center';
        $variant['zones']['headline']['x'] = $contentX;
        $variant['zones']['headline']['width'] = $contentWidth;
        $variant['zones']['subheadline']['x'] = $contentX;
        $variant['zones']['subheadline']['width'] = $contentWidth;
        $variant['zones']['brand']['x'] = $contentX;
        $variant['zones']['brand']['width'] = $contentWidth;
        $variant['zones']['cta']['x'] = $contentX;
        $variant['zones']['cta']['width'] = $contentWidth;

        if ($variant['height'] >= 1800) {
            $variant['zones']['headline']['y'] = 520;
            $variant['zones']['headline']['height'] = 360;
            $variant['zones']['subheadline']['y'] = 920;
            $variant['zones']['subheadline']['height'] = 180;
            $variant['zones']['brand']['y'] = 1640;
            $variant['zones']['cta']['y'] = 1720;
        } elseif ($variant['height'] >= 1300) {
            $variant['zones']['headline']['y'] = 270;
            $variant['zones']['headline']['height'] = 320;
            $variant['zones']['subheadline']['y'] = 640;
            $variant['zones']['subheadline']['height'] = 170;
            $variant['zones']['brand']['y'] = 1110;
            $variant['zones']['cta']['y'] = 1180;
        } else {
            $variant['zones']['headline']['y'] = 250;
            $variant['zones']['headline']['height'] = 330;
            $variant['zones']['subheadline']['y'] = 620;
            $variant['zones']['subheadline']['height'] = 150;
            $variant['zones']['brand']['y'] = 840;
            $variant['zones']['cta']['y'] = 905;
        }
    }

    if ($variant['height'] === 1080) {
        $contentWidth = (int) floor($variant['width'] * 0.82);
        $contentX = (int) floor(($variant['width'] - $contentWidth) / 2);
        $headlineHeight = 410;
        $headlineY = (int) floor(($variant['height'] - $headlineHeight) / 2) - 85;

        $variant['zones']['label']['height'] = 0;
        $variant['zones']['label']['max_lines'] = 0;
        $variant['zones']['headline']['x'] = $contentX;
        $variant['zones']['headline']['width'] = $contentWidth;
        $variant['zones']['headline']['y'] = max(120, $headlineY);
        $variant['zones']['headline']['height'] = $headlineHeight;
        $variant['zones']['headline']['font_size'] = 100;
        $variant['zones']['headline']['min_font_size'] = 32;
        $variant['zones']['headline']['line_height'] = 1.34;
        $variant['zones']['headline']['align'] = 'center';
        $variant['zones']['headline']['max_lines'] = 5;

        $variant['zones']['subheadline']['height'] = 0;
        $variant['zones']['subheadline']['max_lines'] = 0;
        $variant['zones']['subheadline']['y'] = $variant['height'] + 100;

        $variant['zones']['brand']['x'] = $contentX;
        $variant['zones']['brand']['width'] = $contentWidth;
        $variant['zones']['brand']['align'] = 'center';
        $variant['zones']['brand']['y'] = 870;

        $variant['zones']['cta']['x'] = $contentX;
        $variant['zones']['cta']['width'] = $contentWidth;
        $variant['zones']['cta']['align'] = 'center';
        $variant['zones']['cta']['y'] = 935;
    }

    $variant['zones']['label']['color'] = social_media_normalize_hex_color($design['accent_color'], $palette['accent']);
    $variant['zones']['headline']['color'] = social_media_normalize_hex_color($design['text_color'], $palette['text']);
    if ($variant['height'] !== 1080) {
        $variant['zones']['headline']['font_size'] = (int) $design['headline_size'];
    }
    $variant['zones']['headline']['font_key'] = $design['headline_font_key'];
    $variant['zones']['headline']['text_case'] = $design['text_case'];
    $variant['zones']['subheadline']['color'] = social_media_normalize_hex_color($design['text_color'], $palette['text']);
    $variant['zones']['subheadline']['font_size'] = (int) $design['body_size'];
    $variant['zones']['subheadline']['font_key'] = $design['body_font_key'];
    $variant['zones']['subheadline']['text_case'] = 'sentence';
    $variant['zones']['brand']['color'] = social_media_normalize_hex_color($design['text_color'], $palette['text']);
    $variant['zones']['brand']['font_key'] = $design['body_font_key'];
    $variant['zones']['cta']['color'] = social_media_normalize_hex_color($design['accent_color'], $palette['accent']);
    $variant['zones']['cta']['font_key'] = $design['body_font_key'];
    $variant['zones']['cta']['font_size'] = max(18, (int) $design['body_size'] - 2);

    return $variant;
}

function social_media_render_preview($post, $asset, $profile)
{
    $formats = social_media_get_format_map();
    $format = $formats[$post['post_type']];
    $asset = $asset ? social_media_prepare_asset_record($asset) : [];
    $variant = social_media_get_manifest_variant($asset, $post['post_type'], $format);
    $variant = social_media_apply_design_to_variant($variant, $post['design'], $asset);
    $width = $variant['width'];
    $height = $variant['height'];
    $backgroundResult = social_media_open_asset_background($asset ?: [], $width, $height, !empty($variant['background_colors']) ? $variant['background_colors'] : []);
    $canvas = is_array($backgroundResult) && isset($backgroundResult['canvas']) ? $backgroundResult['canvas'] : $backgroundResult;
    $backgroundDebug = is_array($backgroundResult) && isset($backgroundResult['debug']) ? $backgroundResult['debug'] : [];

    list($ovR, $ovG, $ovB) = social_media_hex_to_rgb($variant['overlay']['color']);
    $overlay = imagecolorallocatealpha($canvas, $ovR, $ovG, $ovB, (int) floor(127 * min(max($variant['overlay']['opacity'], 0), 1)));
    imagefilledrectangle($canvas, 0, 0, $width, $height, $overlay);

    $renderOptions = !empty($post['render_options']) && is_array($post['render_options']) ? $post['render_options'] : [];
    $showLogo = !isset($renderOptions['show_logo']) || $renderOptions['show_logo'];
    $showLabel = !isset($renderOptions['show_label']) || $renderOptions['show_label'];
    $showHeadline = !isset($renderOptions['show_headline']) || $renderOptions['show_headline'];
    $showSubheadline = !isset($renderOptions['show_subheadline']) || $renderOptions['show_subheadline'];
    $showBrand = !isset($renderOptions['show_brand']) || $renderOptions['show_brand'];
    $showCta = !isset($renderOptions['show_cta']) || $renderOptions['show_cta'];

    if ($showLogo && !empty($profile['company_logo'])) {
        $logoPath = ROOTPATH . '/storage/company/' . $profile['company_logo'];
        if (file_exists($logoPath)) {
            $logo = social_media_load_image_resource($logoPath);
            if ($logo) {
                $logoZone = $variant['zones']['logo'];
                $logoCanvas = imagecreatetruecolor($logoZone['width'], $logoZone['height']);
                imagealphablending($logoCanvas, false);
                imagesavealpha($logoCanvas, true);
                $transparent = imagecolorallocatealpha($logoCanvas, 0, 0, 0, 127);
                imagefill($logoCanvas, 0, 0, $transparent);
                imagecopyresampled($logoCanvas, $logo, 0, 0, 0, 0, $logoZone['width'], $logoZone['height'], imagesx($logo), imagesy($logo));
                imagecopy($canvas, $logoCanvas, $logoZone['x'], $logoZone['y'], 0, 0, $logoZone['width'], $logoZone['height']);
                imagedestroy($logo);
                imagedestroy($logoCanvas);
            }
        }
    }

    $brand = !empty($profile['company_name']) ? $profile['company_name'] : 'Atlas';
    $labelFont = social_media_font_path($post['design']['body_font_key']);
    $headlineFont = social_media_font_path($post['design']['headline_font_key']);
    $bodyFont = social_media_font_path($post['design']['body_font_key'], true);
    if ($showLabel && $post['post_type'] !== 'post') {
        social_media_render_zone_text($canvas, strtoupper($format['label']), $variant['zones']['label'], $labelFont);
    }
    if ($showHeadline) {
        social_media_render_zone_text($canvas, social_media_transform_text_case($post['overlay_text'], $post['design']['text_case']), $variant['zones']['headline'], $headlineFont);
    }
    if ($showSubheadline && $post['post_type'] === 'reel') {
        social_media_render_zone_text($canvas, social_media_transform_text_case($post['hook'], 'sentence'), $variant['zones']['subheadline'], $bodyFont);
    }
    if ($showBrand) {
        social_media_render_zone_text($canvas, $brand, $variant['zones']['brand'], $bodyFont);
    }
    if ($showCta) {
        social_media_render_zone_text($canvas, trim((string) $post['cta']), $variant['zones']['cta'], $bodyFont);
    }

    $targetDir = ROOTPATH . '/storage/social_posts/';
    social_media_make_directory($targetDir);
    $fileName = uniqid('social_') . '.jpg';
    imagejpeg($canvas, $targetDir . $fileName, 90);
    imagedestroy($canvas);

    return [
        'file_name' => $fileName,
        'debug' => [
            'asset_id' => !empty($asset['id']) ? (int) $asset['id'] : 0,
            'asset_title' => !empty($asset['title']) ? $asset['title'] : '',
            'asset_type' => !empty($asset['asset_type']) ? $asset['asset_type'] : '',
            'asset_file' => !empty($asset['file_name']) ? $asset['file_name'] : '',
            'asset_preview' => !empty($asset['preview_name']) ? $asset['preview_name'] : '',
            'background' => $backgroundDebug,
            'fonts' => [
                'headline' => $headlineFont,
                'body' => $bodyFont,
            ],
        ],
    ];
}

function social_media_get_manifest_variant($asset, $postType, $format)
{
    if (!empty($asset['manifest']['variants'][$postType])) {
        return $asset['manifest']['variants'][$postType];
    }

    return social_media_build_manifest_variant($format, [
        'best_text_zone' => !empty($asset['text_position']) ? $asset['text_position'] : 'center',
        'suggested_text_color' => '#FFFFFF',
        'overlay_opacity' => 0.36,
    ], !empty($asset['text_position']) ? $asset['text_position'] : 'center', $postType);
}

function social_media_store_generated_posts($user_id, $items, $brief = '', $options = [])
{
    social_media_bootstrap();
    global $config;

    $profile = social_media_get_profile($user_id);
    $batchKey = !empty($options['batch_key']) ? $options['batch_key'] : uniqid('batch_');
    $stored = [];
    $usedAssetIds = [];

    foreach ($items as $item) {
        $keywords = array_merge($item['keywords'], social_media_normalize_list($profile['company_industry']));
        $asset = social_media_pick_asset_with_design($item['post_type'], $keywords, $item['design'], $usedAssetIds);
        $assetKey = social_media_asset_unique_key($asset);
        if ($assetKey !== '') {
            $usedAssetIds[] = $assetKey;
        }
        $previewResult = social_media_render_preview($item, $asset, $profile);
        $preview = is_array($previewResult) ? $previewResult['file_name'] : $previewResult;
        $renderDebug = is_array($previewResult) && !empty($previewResult['debug']) ? $previewResult['debug'] : [];
        $renderedVideo = '';
        $sourceVideo = '';
        if ($item['post_type'] === 'reel' && !empty($asset['asset_type']) && $asset['asset_type'] === 'video') {
            $renderedVideo = social_media_render_reel_video($asset, $preview);
            if (empty($renderedVideo) && !empty($asset['file_name'])) {
                $sourceVideo = $asset['file_name'];
            }
        }

        $record = ORM::for_table($config['db']['pre'] . 'social_media_posts')->create();
        $record->user_id = $user_id;
        $record->batch_key = $batchKey;
        $record->post_type = $item['post_type'];
        $record->title = $item['title'];
        $record->caption = $item['caption'];
        $record->overlay_text = $item['overlay_text'];
        $record->asset_id = !empty($asset['id']) ? $asset['id'] : null;
        $record->asset_file = !empty($asset['file_name']) ? $asset['file_name'] : '';
        $record->preview_image = $preview;
        $record->metadata = json_encode([
            'brief' => $brief,
            'hook' => $item['hook'],
            'cta' => $item['cta'],
            'hashtags' => $item['hashtags'],
            'visual_brief' => $item['visual_brief'],
            'slides' => $item['slides'],
            'reel_script' => $item['reel_script'],
            'design' => $item['design'],
            'grid' => !empty($item['grid']) ? $item['grid'] : [],
            'render_options' => !empty($item['render_options']) ? $item['render_options'] : [],
            'asset' => $asset ?: [],
            'rendered_video' => $renderedVideo,
            'source_video' => $sourceVideo,
            'debug' => [
                'generation_source' => !empty($item['_generation_source']) ? $item['_generation_source'] : 'unknown',
                'openai' => !empty($item['_openai_debug']) ? $item['_openai_debug'] : [],
                'render' => $renderDebug,
                'rendered_video_exists' => !empty($renderedVideo),
                'source_video_used' => !empty($sourceVideo),
            ],
        ]);
        $record->created_at = date('Y-m-d H:i:s');
        $record->save();

        $stored[] = [
            'id' => $record->id(),
            'post_type' => $item['post_type'],
            'title' => $item['title'],
            'overlay_text' => $item['overlay_text'],
            'caption' => $item['caption'],
            'cta' => $item['cta'],
            'hashtags' => $item['hashtags'],
            'visual_brief' => $item['visual_brief'],
            'slides' => $item['slides'],
            'reel_script' => $item['reel_script'],
            'design' => $item['design'],
            'preview_image' => $config['site_url'] . 'storage/social_posts/' . $preview,
            'batch_key' => $batchKey,
            'rendered_video' => !empty($renderedVideo) ? $config['site_url'] . 'storage/social_posts/videos/' . $renderedVideo : '',
            'source_video' => !empty($sourceVideo) ? $config['site_url'] . 'storage/social_assets/' . $sourceVideo : '',
            'asset_preview' => !empty($asset['preview_name']) ? $config['site_url'] . 'storage/social_assets/' . $asset['preview_name'] : '',
            'asset_type' => !empty($asset['asset_type']) ? $asset['asset_type'] : '',
            'asset_title' => !empty($asset['title']) ? $asset['title'] : '',
            'grid' => !empty($item['grid']) ? $item['grid'] : [],
            'render_options' => !empty($item['render_options']) ? $item['render_options'] : [],
            'debug' => [
                'generation_source' => !empty($item['_generation_source']) ? $item['_generation_source'] : 'unknown',
                'openai' => !empty($item['_openai_debug']) ? $item['_openai_debug'] : [],
                'render' => $renderDebug,
                'rendered_video_exists' => !empty($renderedVideo),
                'source_video_used' => !empty($sourceVideo),
            ],
        ];
    }

    return $stored;
}

function social_media_save_asset($payload)
{
    social_media_bootstrap();
    global $config;

    $assetDir = ROOTPATH . '/storage/social_assets/';
    social_media_make_directory($assetDir);

    $asset = !empty($payload['id'])
        ? ORM::for_table($config['db']['pre'] . 'social_media_assets')->find_one($payload['id'])
        : ORM::for_table($config['db']['pre'] . 'social_media_assets')->create();

    if (!$asset) {
        return ['success' => false, 'error' => __('Asset not found.')];
    }

    $fileName = !empty($asset['file_name']) ? $asset['file_name'] : '';
    if (!empty($_FILES['asset_file']['name'])) {
        $allowed = $payload['asset_type'] === 'video' ? ['video/*'] : ['image/*'];
        $upload = quick_file_upload('asset_file', $assetDir, $allowed);
        if (!$upload['success']) {
            return ['success' => false, 'error' => $upload['error']];
        }
        if (!empty($fileName) && file_exists($assetDir . $fileName)) {
            unlink($assetDir . $fileName);
        }
        $fileName = $upload['file_name'];
    } elseif (empty($fileName)) {
        return ['success' => false, 'error' => __('Primary asset file is required.')];
    }

    $previewName = !empty($asset['preview_name']) ? $asset['preview_name'] : '';
    if (!empty($_FILES['asset_preview']['name'])) {
        $previewUpload = quick_file_upload('asset_preview', $assetDir);
        if (!$previewUpload['success']) {
            return ['success' => false, 'error' => $previewUpload['error']];
        }
        if (!empty($previewName) && $previewName !== $fileName && file_exists($assetDir . $previewName)) {
            unlink($assetDir . $previewName);
        }
        $previewName = $previewUpload['file_name'];
    } elseif ($payload['asset_type'] === 'image') {
        $previewName = $fileName;
    }

    $width = null;
    $height = null;
    if (!empty($previewName) && file_exists($assetDir . $previewName)) {
        $size = @getimagesize($assetDir . $previewName);
        if ($size) {
            $width = $size[0];
            $height = $size[1];
        }
    }

    $asset->title = $payload['title'];
    $asset->asset_type = $payload['asset_type'];
    $asset->post_type = $payload['post_type'];
    $asset->file_name = $fileName;
    $asset->preview_name = $previewName;
    $asset->tags = implode(',', social_media_normalize_list($payload['tags']));
    $asset->text_position = $payload['text_position'];
    $asset->render_preset = !empty($payload['render_preset']) ? $payload['render_preset'] : 'auto';
    if (isset($payload['manifest_json']) && trim($payload['manifest_json']) !== '') {
        $manifest = json_decode($payload['manifest_json'], true);
        if (is_array($manifest)) {
            $asset->manifest_json = json_encode($manifest);
        }
    }
    $asset->status = !empty($payload['status']) ? 1 : 0;
    $asset->width = $width;
    $asset->height = $height;
    if (empty($asset['id'])) {
        $asset->created_at = date('Y-m-d H:i:s');
    }
    $asset->updated_at = date('Y-m-d H:i:s');
    $asset->save();

    social_media_refresh_asset_analysis($asset->id(), empty($asset['manifest_json']));

    return ['success' => true, 'id' => $asset->id()];
}

function social_media_delete_asset($id)
{
    social_media_bootstrap();
    global $config;

    $asset = ORM::for_table($config['db']['pre'] . 'social_media_assets')->find_one($id);
    if (!$asset) {
        return false;
    }

    $assetDir = ROOTPATH . '/storage/social_assets/';
    foreach (['file_name', 'preview_name'] as $field) {
        if (!empty($asset[$field]) && file_exists($assetDir . $asset[$field])) {
            unlink($assetDir . $asset[$field]);
        }
    }

    $asset->delete();
    return true;
}

function social_media_delete_post($user_id, $id)
{
    social_media_bootstrap();
    global $config;

    $post = ORM::for_table($config['db']['pre'] . 'social_media_posts')
        ->where('user_id', $user_id)
        ->find_one($id);

    if (!$post) {
        return false;
    }

    $postDir = ROOTPATH . '/storage/social_posts/';
    if (!empty($post['preview_image']) && file_exists($postDir . $post['preview_image'])) {
        unlink($postDir . $post['preview_image']);
    }

    $meta = !empty($post['metadata']) ? json_decode($post['metadata'], true) : [];
    if (!empty($meta['rendered_video'])) {
        $videoPath = ROOTPATH . '/storage/social_posts/videos/' . $meta['rendered_video'];
        if (file_exists($videoPath)) {
            unlink($videoPath);
        }
    }

    $post->delete();
    return true;
}
