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

    return implode("\n", $parts);
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

    if (!preg_match('/^https?:\/\//i', $url) && strpos($url, 'instagram.com/') === false) {
        $url = 'https://' . $url;
    } elseif (strpos($url, 'instagram.com/') !== false && !preg_match('/^https?:\/\//i', $url)) {
        $url = 'https://' . ltrim($url, '/');
    }

    $snapshot = [
        'url' => $url,
        'title' => '',
        'description' => '',
        'instagram' => '',
    ];

    if (strpos($url, 'instagram.com/') !== false) {
        $snapshot['instagram'] = $url;
        return $snapshot;
    }

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

    if (preg_match('/https?:\/\/(www\.)?instagram\.com\/[A-Za-z0-9._-]+/i', $html, $match)) {
        $snapshot['instagram'] = $match[0];
    }

    return $snapshot;
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

    if (empty($asset['analysis_json']) || empty($asset['manifest_json'])) {
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
        return $asset;
    }

    $excludedAssetIds = array_map('intval', (array) $excludedAssetIds);
    $preferredAssets = [];
    foreach ($assets as $candidate) {
        $candidateId = !empty($candidate['id']) ? (int) $candidate['id'] : 0;
        if ($candidateId > 0 && in_array($candidateId, $excludedAssetIds, true)) {
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

    $system = "You are Atlas Social Strategist. Generate practical, brand-aware social media packages for founders. Return valid JSON only.";

    $competitorText = json_encode($competitorSnapshots);
    $userPrompt = "Create exactly 9 social media ideas for this company.\n"
        . "Need exactly 9 post items.\n"
        . "Each item must include: post_type, title, hook, caption, cta, hashtags, visual_brief, keywords, design.\n"
        . "Post overlay_text must be short, punchy, and final-ready.\n"
        . "Do not use placeholders or generic labels like 'founder insight', 'myth busting', 'case study', or 'trend reaction' as overlay_text.\n"
        . "overlay_text should read like the actual quote, claim, framework, or contrarian hook that will appear on the design.\n"
        . "caption must be publish-ready, useful, specific, and persuasive. It should sound like a real social caption, not an instruction to a marketer.\n"
        . "The CTA must be direct and natural, not generic.\n"
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
    $themes = [
        [
            'title' => 'Founder take for ' . $company,
            'overlay' => 'What most ' . strtolower($audience) . ' get wrong about ' . strtolower($product),
            'hook' => 'Most ' . strtolower($audience) . ' do not need more options. They need better guidance on ' . strtolower($product) . '.',
            'caption' => $company . ' believes the real opportunity in ' . strtolower($industry) . ' is not selling harder, it is making decisions easier. Here is one practical thing founders and customers should understand about ' . strtolower($product) . ': ' . $differentiator . '.',
            'cta' => 'Follow for more sharp founder insights and send us a DM if you want help applying this.',
        ],
        [
            'title' => 'Educational post for ' . $company,
            'overlay' => '3 signs your ' . strtolower($product) . ' strategy needs work',
            'hook' => 'If your current approach feels noisy, these are usually the first three signs something is off.',
            'caption' => 'A strong ' . strtolower($product) . ' strategy should feel simple, useful, and repeatable. If people feel confused, if results depend on constant discounting, or if the value is hard to explain in one sentence, the strategy needs tightening.',
            'cta' => 'Save this post and share it with a founder who is refining their offer.',
        ],
        [
            'title' => 'Customer transformation for ' . $company,
            'overlay' => 'The shift that makes ' . strtolower($product) . ' easier to buy',
            'hook' => 'When brands focus on clarity instead of noise, customers move faster.',
            'caption' => 'The biggest transformation we see is this: once a company explains why its solution matters in plain language, customers stop hesitating. Better messaging around ' . strtolower($product) . ' creates trust, and trust creates action.',
            'cta' => 'Comment with your biggest messaging challenge and we may turn it into the next post.',
        ],
        [
            'title' => 'Framework post for ' . $company,
            'overlay' => 'A simple framework for better ' . strtolower($product),
            'hook' => 'This is the 5-step framework we would use to make your message land faster.',
            'caption' => 'If you are building awareness in ' . strtolower($industry) . ', keep the message simple: problem, cost of ignoring it, better approach, proof, then call to action. This structure consistently turns vague content into practical content.',
            'cta' => 'Save this framework for your next campaign.',
        ],
        [
            'title' => 'Myth busting post for ' . $company,
            'overlay' => 'The biggest myth about ' . strtolower($product),
            'hook' => 'The myth sounds smart, but it usually slows growth.',
            'caption' => 'One of the biggest myths in ' . strtolower($industry) . ' is that more content automatically means more growth. In reality, focused content with a clear point of view outperforms generic volume almost every time.',
            'cta' => 'Send this to someone who is posting constantly but still not converting.',
        ],
        [
            'title' => 'Case study post for ' . $company,
            'overlay' => 'What a better ' . strtolower($product) . ' message changes',
            'hook' => 'Small changes in positioning often lead to bigger trust and faster action.',
            'caption' => 'When a company stops describing features and starts framing the outcome, people understand the value faster. That one change can improve clicks, conversations, and conversions without changing the product itself.',
            'cta' => 'Follow for more examples of positioning that actually converts.',
        ],
        [
            'title' => 'Trend reaction post for ' . $company,
            'overlay' => 'Why this trend matters for ' . strtolower($audience),
            'hook' => 'Trends only matter if they change customer behavior or expectations.',
            'caption' => 'The best way to react to a trend is not to copy the surface. It is to ask what expectation has changed and how your product should respond. That is how you stay relevant without looking reactive.',
            'cta' => 'Comment “trend” if you want more fast reaction breakdowns.',
        ],
        [
            'title' => 'Case study post for ' . $company,
            'overlay' => 'A better way to talk about ' . strtolower($product),
            'hook' => 'Most brands explain what they do. Better brands explain what changes for the customer.',
            'caption' => 'If your audience does not immediately understand why your solution matters, the problem is usually positioning, not effort. Tighten the message, simplify the proof, and the content becomes easier to trust and share.',
            'cta' => 'DM us if you want help rewriting your positioning.',
        ],
        [
            'title' => 'How-to post for ' . $company,
            'overlay' => 'How to make your content convert',
            'hook' => 'Better content starts with a sharper point, not more words.',
            'caption' => 'Here is the rule: lead with one strong point, support it with one useful insight, and end with one direct action. That structure makes content easier to remember and far more likely to convert.',
            'cta' => 'Save this and use it in your next content sprint.',
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
            'caption' => $theme['caption'] . ($briefSummary !== '' ? ' This supports the campaign focus on ' . rtrim($briefSummary, '.') . '.' : ''),
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
    if (!empty($asset['file_name'])) {
        return ROOTPATH . '/storage/social_assets/' . $asset['file_name'];
    }
    return '';
}

function social_media_asset_preview_path($asset)
{
    if (!empty($asset['preview_name'])) {
        return ROOTPATH . '/storage/social_assets/' . $asset['preview_name'];
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
    $text = trim((string) $text);
    if ($text === '') {
        return [];
    }

    if (!$fontPath || !function_exists('imagettfbbox')) {
        return array_slice(social_media_render_lines($text, max(18, (int) floor($maxWidth / max($fontSize * 0.72, 1)))), 0, $maxLines);
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

    return array_slice($lines, 0, $maxLines);
}

function social_media_fit_text_to_zone($text, $zone, $fontPath)
{
    $fontSize = !empty($zone['font_size']) ? (int) $zone['font_size'] : 28;
    $minFontSize = !empty($zone['min_font_size']) ? (int) $zone['min_font_size'] : 16;
    $maxLines = !empty($zone['max_lines']) ? (int) $zone['max_lines'] : 3;
    $lineHeight = !empty($zone['line_height']) ? (float) $zone['line_height'] : 1.2;

    while ($fontSize >= $minFontSize) {
        $lines = social_media_wrap_text_for_box($text, $fontPath, $fontSize, $zone['width'], $maxLines);
        $estimatedHeight = count($lines) * (int) floor($fontSize * $lineHeight);
        if ($estimatedHeight <= $zone['height'] && count($lines) <= $maxLines) {
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
        'lines' => social_media_wrap_text_for_box($text, $fontPath, $minFontSize, $zone['width'], $maxLines),
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
    ];

    $assetPaths = [];
    if (!empty($asset['file_name']) && $asset['asset_type'] === 'image') {
        $assetPaths[] = ROOTPATH . '/storage/social_assets/' . $asset['file_name'];
    }
    if (!empty($asset['preview_name'])) {
        $assetPaths[] = ROOTPATH . '/storage/social_assets/' . $asset['preview_name'];
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
    $variant['overlay']['color'] = social_media_normalize_hex_color(isset($design['overlay_color']) ? $design['overlay_color'] : $palette['overlay'], $palette['overlay']);
    $variant['overlay']['opacity'] = min(0.72, max(0.08, isset($design['overlay_opacity']) ? (float) $design['overlay_opacity'] : 0.32));
    $variant['background_colors'] = [
        social_media_normalize_hex_color(isset($dominant[0]) ? $dominant[0] : $palette['background'], $palette['background']),
        social_media_normalize_hex_color(isset($dominant[1]) ? $dominant[1] : $palette['overlay'], $palette['overlay']),
    ];

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
        $headlineHeight = 320;
        $headlineY = (int) floor(($variant['height'] - $headlineHeight) / 2) - 70;

        $variant['zones']['label']['height'] = 0;
        $variant['zones']['label']['max_lines'] = 0;
        $variant['zones']['headline']['x'] = $contentX;
        $variant['zones']['headline']['width'] = $contentWidth;
        $variant['zones']['headline']['y'] = max(120, $headlineY);
        $variant['zones']['headline']['height'] = $headlineHeight;
        $variant['zones']['headline']['font_size'] = 100;
        $variant['zones']['headline']['min_font_size'] = 56;
        $variant['zones']['headline']['line_height'] = 1.24;
        $variant['zones']['headline']['align'] = 'center';

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

    if (!empty($profile['company_logo'])) {
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
    if ($post['post_type'] !== 'post') {
        social_media_render_zone_text($canvas, strtoupper($format['label']), $variant['zones']['label'], $labelFont);
    }
    social_media_render_zone_text($canvas, social_media_transform_text_case($post['overlay_text'], $post['design']['text_case']), $variant['zones']['headline'], $headlineFont);
    if ($post['post_type'] === 'reel') {
        social_media_render_zone_text($canvas, social_media_transform_text_case($post['hook'], 'sentence'), $variant['zones']['subheadline'], $bodyFont);
    }
    social_media_render_zone_text($canvas, $brand, $variant['zones']['brand'], $bodyFont);
    social_media_render_zone_text($canvas, trim((string) $post['cta']), $variant['zones']['cta'], $bodyFont);

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

function social_media_store_generated_posts($user_id, $items, $brief = '')
{
    social_media_bootstrap();
    global $config;

    $profile = social_media_get_profile($user_id);
    $batchKey = uniqid('batch_');
    $stored = [];
    $usedAssetIds = [];

    foreach ($items as $item) {
        $keywords = array_merge($item['keywords'], social_media_normalize_list($profile['company_industry']));
        $asset = social_media_pick_asset_with_design($item['post_type'], $keywords, $item['design'], $usedAssetIds);
        if (!empty($asset['id'])) {
            $usedAssetIds[] = (int) $asset['id'];
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
            'rendered_video' => !empty($renderedVideo) ? $config['site_url'] . 'storage/social_posts/videos/' . $renderedVideo : '',
            'source_video' => !empty($sourceVideo) ? $config['site_url'] . 'storage/social_assets/' . $sourceVideo : '',
            'asset_preview' => !empty($asset['preview_name']) ? $config['site_url'] . 'storage/social_assets/' . $asset['preview_name'] : '',
            'asset_type' => !empty($asset['asset_type']) ? $asset['asset_type'] : '',
            'asset_title' => !empty($asset['title']) ? $asset['title'] : '',
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
