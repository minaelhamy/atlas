<?php

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

    $bootstrapped = true;
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
        'founder_photo' => '',
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
        'carousel' => [
            'label' => 'Carousel',
            'width' => 1080,
            'height' => 1350,
            'asset_type' => 'image',
            'headline_limit' => 80,
            'body_limit' => 220,
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

    return $orm->find_array();
}

function social_media_get_asset($id)
{
    social_media_bootstrap();
    global $config;

    return ORM::for_table($config['db']['pre'] . 'social_media_assets')->find_one($id);
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
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestAsset = $asset;
        }
    }

    return $bestAsset ?: $assets[array_rand($assets)];
}

function social_media_generate_batch($user_id, $brief = '')
{
    $profile = social_media_get_profile($user_id);
    $companyContext = social_media_get_company_context_text($user_id);
    $historyContext = social_media_get_recent_chat_context($user_id);
    $competitorSnapshots = social_media_get_competitor_snapshots($profile);

    $system = "You are Atlas Social Strategist. Generate practical, brand-aware social media packages for founders. Return valid JSON only.";

    $competitorText = json_encode($competitorSnapshots);
    $userPrompt = "Create exactly 9 social media ideas for this company.\n"
        . "Need exactly 3 post, 3 carousel, and 3 reel items.\n"
        . "Each item must include: post_type, title, hook, caption, cta, hashtags, visual_brief, keywords.\n"
        . "Carousel items must include slides (5 short slides).\n"
        . "Reel items must include reel_script (hook, beats, cta) and an overlay_text under 55 chars.\n"
        . "Post and carousel overlay_text must be short and punchy.\n"
        . "Company context:\n{$companyContext}\n\n"
        . "Recent company history from agents:\n{$historyContext}\n\n"
        . "Competitor research:\n{$competitorText}\n\n"
        . "Campaign brief:\n{$brief}\n\n"
        . "JSON shape: {\"items\":[...]}";

    $items = social_media_generate_batch_via_openai($system, $userPrompt, $user_id);
    if (empty($items)) {
        $items = social_media_generate_fallback_batch($profile, $brief);
    }

    return social_media_normalize_generated_items($items, $profile);
}

function social_media_generate_batch_via_openai($system, $userPrompt, $user_id)
{
    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/OpenAi.php';
    require_once ROOTPATH . '/includes/lib/orhanerday/open-ai/src/Url.php';

    $openAi = new Orhanerday\OpenAi\OpenAi(get_api_key());
    $response = $openAi->chat([
        'model' => normalize_openai_model(get_default_openai_chat_model()),
        'messages' => [
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $userPrompt],
        ],
        'temperature' => 0.9,
        'user' => $user_id,
    ]);

    $decoded = json_decode($response, true);
    if (empty($decoded['choices'][0]['message']['content'])) {
        return [];
    }

    $json = social_media_extract_json($decoded['choices'][0]['message']['content']);
    if (empty($json['items']) || !is_array($json['items'])) {
        return [];
    }

    return $json['items'];
}

function social_media_extract_json($text)
{
    $text = trim((string) $text);
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
    $themes = [
        'Founder insight', 'Pain-point education', 'Customer transformation',
        'Behind the scenes', 'Myth busting', 'Competitive positioning',
        'How-to framework', 'Case study', 'Trend reaction'
    ];
    $types = ['post', 'post', 'post', 'carousel', 'carousel', 'carousel', 'reel', 'reel', 'reel'];
    $items = [];

    foreach ($types as $index => $type) {
        $theme = $themes[$index];
        $items[] = [
            'post_type' => $type,
            'title' => $theme . ' for ' . $company,
            'hook' => $theme . ': what ' . $audience . ' needs to know about ' . $industry,
            'overlay_text' => $theme,
            'caption' => "Teach {$audience} something specific about {$company}. {$brief}",
            'cta' => 'Send us a DM for the full playbook.',
            'hashtags' => ['#' . preg_replace('/\s+/', '', ucwords($industry)), '#Founders', '#Marketing'],
            'visual_brief' => 'Bold branded layout with high contrast copy and space for logo.',
            'keywords' => [$industry, $theme, $company],
            'slides' => $type === 'carousel' ? [
                'Hook',
                'Why it matters',
                'Common mistake',
                'Better framework',
                'Call to action',
            ] : [],
            'reel_script' => $type === 'reel' ? [
                'Hook in first 2 seconds',
                '3 fast insight beats',
                'CTA to comment or DM',
            ] : [],
        ];
    }

    return $items;
}

function social_media_normalize_generated_items($items, $profile)
{
    $bucketed = ['post' => [], 'carousel' => [], 'reel' => []];

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
        $item['slides'] = !empty($item['slides']) && is_array($item['slides']) ? array_values($item['slides']) : [];
        $item['reel_script'] = !empty($item['reel_script']) && is_array($item['reel_script']) ? array_values($item['reel_script']) : [];

        $bucketed[$type][] = $item;
    }

    $normalized = [];
    foreach (['post', 'carousel', 'reel'] as $type) {
        while (count($bucketed[$type]) < 3) {
            $fallback = social_media_generate_fallback_batch($profile);
            foreach ($fallback as $item) {
                if ($item['post_type'] === $type) {
                    $bucketed[$type][] = $item;
                    if (count($bucketed[$type]) >= 3) {
                        break;
                    }
                }
            }
        }
        $normalized = array_merge($normalized, array_slice($bucketed[$type], 0, 3));
    }

    return $normalized;
}

function social_media_font_path()
{
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

function social_media_ffmpeg_path()
{
    $envPath = get_env_setting('FFMPEG_PATH');
    if (!empty($envPath) && file_exists($envPath)) {
        return $envPath;
    }

    if (social_media_shell_available('shell_exec')) {
        $path = trim((string) shell_exec('command -v ffmpeg 2>/dev/null'));
        if ($path !== '') {
            return $path;
        }
    }

    return '';
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

function social_media_open_asset_background($asset, $width, $height)
{
    $canvas = imagecreatetruecolor($width, $height);
    imagealphablending($canvas, true);
    imagesavealpha($canvas, true);

    $assetPath = '';
    if (!empty($asset['file_name']) && $asset['asset_type'] === 'image') {
        $assetPath = ROOTPATH . '/storage/social_assets/' . $asset['file_name'];
    } elseif (!empty($asset['preview_name'])) {
        $assetPath = ROOTPATH . '/storage/social_assets/' . $asset['preview_name'];
    }

    if ($assetPath && file_exists($assetPath)) {
        $info = getimagesize($assetPath);
        if (!empty($info['mime'])) {
            if ($info['mime'] === 'image/jpeg') {
                $source = imagecreatefromjpeg($assetPath);
            } elseif ($info['mime'] === 'image/png') {
                $source = imagecreatefrompng($assetPath);
            } elseif ($info['mime'] === 'image/gif') {
                $source = imagecreatefromgif($assetPath);
            } else {
                $source = null;
            }

            if ($source) {
                $srcWidth = imagesx($source);
                $srcHeight = imagesy($source);
                imagecopyresampled($canvas, $source, 0, 0, 0, 0, $width, $height, $srcWidth, $srcHeight);
                imagedestroy($source);
                return $canvas;
            }
        }
    }

    $top = imagecolorallocate($canvas, 30, 38, 56);
    $bottom = imagecolorallocate($canvas, 8, 12, 20);
    imagefilledrectangle($canvas, 0, 0, $width, $height / 2, $top);
    imagefilledrectangle($canvas, 0, $height / 2, $width, $height, $bottom);
    return $canvas;
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

function social_media_render_preview($post, $asset, $profile)
{
    $formats = social_media_get_format_map();
    $format = $formats[$post['post_type']];
    $width = $format['width'];
    $height = $format['height'];
    $canvas = social_media_open_asset_background($asset ?: [], $width, $height);

    $overlay = imagecolorallocatealpha($canvas, 7, 12, 21, 50);
    imagefilledrectangle($canvas, 0, 0, $width, $height, $overlay);

    $white = imagecolorallocate($canvas, 255, 255, 255);
    $muted = imagecolorallocate($canvas, 215, 224, 235);
    $accent = imagecolorallocate($canvas, 255, 173, 51);
    $fontPath = social_media_font_path();

    $padding = 90;
    $position = !empty($asset['text_position']) ? $asset['text_position'] : 'center';
    if ($position === 'top') {
        $topY = 120;
    } elseif ($position === 'bottom') {
        $topY = (int) floor($height * 0.52);
    } else {
        $topY = (int) floor($height * 0.32);
    }

    if (!empty($profile['company_logo'])) {
        $logoPath = ROOTPATH . '/storage/company/' . $profile['company_logo'];
        if (file_exists($logoPath)) {
            $logo = social_media_load_image_resource($logoPath);
            if ($logo) {
                $logoCanvas = imagecreatetruecolor(120, 120);
                imagealphablending($logoCanvas, false);
                imagesavealpha($logoCanvas, true);
                $transparent = imagecolorallocatealpha($logoCanvas, 0, 0, 0, 127);
                imagefill($logoCanvas, 0, 0, $transparent);
                imagecopyresampled($logoCanvas, $logo, 0, 0, 0, 0, 120, 120, imagesx($logo), imagesy($logo));
                imagecopy($canvas, $logoCanvas, $padding, 40, 0, 0, 120, 120);
                imagedestroy($logo);
                imagedestroy($logoCanvas);
            }
        }
    }

    $pillText = strtoupper($format['label']);
    if ($fontPath && function_exists('imagettftext')) {
        imagettftext($canvas, 26, 0, $padding, $topY, $accent, $fontPath, $pillText);
    } else {
        imagestring($canvas, 5, $padding, $topY - 20, $pillText, $accent);
    }

    $topY += 60;
    $topY = social_media_render_text_block(
        $canvas,
        $post['overlay_text'],
        $padding,
        $topY,
        $width - ($padding * 2),
        $post['post_type'] === 'reel' ? 44 : 52,
        $white,
        $fontPath
    );

    $topY += 20;
    $topY = social_media_render_text_block(
        $canvas,
        $post['hook'],
        $padding,
        $topY,
        $width - ($padding * 2),
        $post['post_type'] === 'reel' ? 22 : 24,
        $muted,
        $fontPath
    );

    $footerY = $height - 120;
    $brand = !empty($profile['company_name']) ? $profile['company_name'] : 'Atlas';
    if ($fontPath && function_exists('imagettftext')) {
        imagettftext($canvas, 24, 0, $padding, $footerY, $white, $fontPath, $brand);
        imagettftext($canvas, 18, 0, $padding, $footerY + 40, $muted, $fontPath, trim((string) $post['cta']));
    } else {
        imagestring($canvas, 5, $padding, $footerY - 18, $brand, $white);
        imagestring($canvas, 4, $padding, $footerY + 8, trim((string) $post['cta']), $muted);
    }

    $targetDir = ROOTPATH . '/storage/social_posts/';
    social_media_make_directory($targetDir);
    $fileName = uniqid('social_') . '.jpg';
    imagejpeg($canvas, $targetDir . $fileName, 90);
    imagedestroy($canvas);

    return $fileName;
}

function social_media_store_generated_posts($user_id, $items, $brief = '')
{
    social_media_bootstrap();
    global $config;

    $profile = social_media_get_profile($user_id);
    $batchKey = uniqid('batch_');
    $stored = [];

    foreach ($items as $item) {
        $keywords = array_merge($item['keywords'], social_media_normalize_list($profile['company_industry']));
        $asset = social_media_pick_asset($item['post_type'], $keywords);
        $preview = social_media_render_preview($item, $asset, $profile);
        $renderedVideo = '';
        if ($item['post_type'] === 'reel' && !empty($asset['asset_type']) && $asset['asset_type'] === 'video') {
            $renderedVideo = social_media_render_reel_video($asset, $preview);
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
            'asset' => $asset ?: [],
            'rendered_video' => $renderedVideo,
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
            'preview_image' => $config['site_url'] . 'storage/social_posts/' . $preview,
            'rendered_video' => !empty($renderedVideo) ? $config['site_url'] . 'storage/social_posts/videos/' . $renderedVideo : '',
            'asset_preview' => !empty($asset['preview_name']) ? $config['site_url'] . 'storage/social_assets/' . $asset['preview_name'] : '',
            'asset_type' => !empty($asset['asset_type']) ? $asset['asset_type'] : '',
            'asset_title' => !empty($asset['title']) ? $asset['title'] : '',
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
    $asset->status = !empty($payload['status']) ? 1 : 0;
    $asset->width = $width;
    $asset->height = $height;
    if (empty($asset['id'])) {
        $asset->created_at = date('Y-m-d H:i:s');
    }
    $asset->updated_at = date('Y-m-d H:i:s');
    $asset->save();

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
