<?php
global $config, $link;

if (isset($current_user['id'])) {
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
    ]);
} else {
    headerRedirect($link['LOGIN']);
}
