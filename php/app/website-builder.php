<?php
global $config, $link;

if (isset($current_user['id'])) {
    try {
        website_builder_ensure_tables();

        $social_profile = social_media_get_profile($_SESSION['user']['id']);
        $company_intelligence = social_media_get_company_intelligence($_SESSION['user']['id']);
        $existing_site = website_builder_get_primary_site($_SESSION['user']['id']);
        if (website_builder_site_is_launched($existing_site)) {
            headerRedirect($link['YOUR_WEBSITE_DASHBOARD'] . '/' . $existing_site['id']);
            exit;
        }

        $profile_status = website_builder_company_profile_status($social_profile, $company_intelligence);
        $profile_ready = $profile_status['ready'];
        $website_error = '';
        $selected_type = website_builder_infer_site_type($social_profile, $company_intelligence);
        $selected_template_key = !empty($_GET['template']) ? validate_input($_GET['template']) : '';
        $selected_template = website_builder_get_template($selected_template_key);
        if (!empty($selected_template) && $selected_template['type'] !== $selected_type) {
            $selected_template = null;
            $selected_template_key = '';
        }
        $templates = website_builder_templates_for_type($selected_type);

        $setup_defaults = !empty($selected_type)
            ? website_builder_generate_structured_fields($_SESSION['user']['id'], $selected_type, ['website_title', 'subdomain', 'first_item_title', 'first_item_description', 'first_item_price', 'first_item_duration'])
            : [];

        $website_debug = [
            'selected_type' => $selected_type,
            'selected_template_key' => $selected_template_key,
            'selected_template_title' => !empty($selected_template['title']) ? $selected_template['title'] : '',
            'template_count' => is_array($templates) ? count($templates) : 0,
            'template_keys' => is_array($templates) ? array_map(function ($template) {
                return !empty($template['key']) ? $template['key'] : '[missing-key]';
            }, $templates) : [],
            'profile_ready' => !empty($profile_ready),
            'missing_profile_fields' => !empty($profile_status['missing']) ? $profile_status['missing'] : [],
            'existing_site_id' => !empty($existing_site['id']) ? $existing_site['id'] : 0,
            'existing_site_status' => !empty($existing_site['status']) ? $existing_site['status'] : '',
            'debug_enabled' => 1,
        ];

        HtmlTemplate::display('website-builder', [
            'social_profile' => $social_profile,
            'company_intelligence' => $company_intelligence,
            'templates' => $templates,
            'existing_site' => $existing_site,
            'profile_status' => $profile_status,
            'profile_ready' => $profile_ready,
            'website_error' => $website_error,
            'selected_type' => $selected_type,
            'selected_template' => $selected_template,
            'setup_defaults' => $setup_defaults,
            'website_debug' => $website_debug,
        ]);
    } catch (Throwable $e) {
        http_response_code(200);
        error_log('Website builder fatal: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        $safeMessage = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        $safeFile = htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8');
        $safeLine = (int) $e->getLine();
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Website Builder Debug</title><style>body{font-family:Arial,sans-serif;background:#f7f4ee;color:#1d1d1f;padding:40px} .box{max-width:980px;margin:0 auto;background:#fff;border:1px solid #e8e1d7;border-radius:20px;padding:24px;box-shadow:0 12px 36px rgba(0,0,0,.06)} code,pre{background:#f7f4ee;padding:2px 6px;border-radius:8px} pre{display:block;padding:14px;overflow:auto}</style></head><body><div class="box"><h1>Website Builder Debug</h1><p>The builder crashed while rendering. This debug view is temporary so we can identify the exact issue on the live server.</p><p><strong>Error:</strong> ' . $safeMessage . '</p><p><strong>File:</strong> ' . $safeFile . '</p><p><strong>Line:</strong> ' . $safeLine . '</p></div></body></html>';
    }
} else {
    headerRedirect($link['LOGIN']);
}
