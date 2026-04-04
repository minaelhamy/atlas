<?php
global $config, $link;

if (isset($current_user['id'])) {
    website_builder_ensure_tables();

    $social_profile = social_media_get_profile($_SESSION['user']['id']);
    $company_intelligence = social_media_get_company_intelligence($_SESSION['user']['id']);
    $templates = website_builder_template_catalog();
    $existing_site = website_builder_get_primary_site($_SESSION['user']['id']);
    $profile_ready = !empty($social_profile['company_name']) && (!empty($social_profile['company_description']) || !empty($company_intelligence['company_summary']));
    $website_error = '';
    $selected_type = !empty($_GET['type']) ? validate_input($_GET['type']) : '';
    $selected_type = in_array($selected_type, ['ecommerce', 'service'], true) ? $selected_type : '';
    $selected_template_key = !empty($_GET['template']) ? validate_input($_GET['template']) : '';
    $selected_template = website_builder_get_template($selected_template_key);
    if (!empty($selected_template) && $selected_type === '') {
        $selected_type = $selected_template['type'];
    }
    if (!empty($selected_template) && !empty($selected_type) && $selected_template['type'] !== $selected_type) {
        $selected_template = null;
        $selected_template_key = '';
    }

    $setup_defaults = !empty($selected_type)
        ? website_builder_generate_structured_fields($_SESSION['user']['id'], $selected_type, ['website_title', 'subdomain', 'first_item_title', 'first_item_description', 'first_item_price', 'first_item_duration'])
        : [];

    HtmlTemplate::display('website-builder', [
        'social_profile' => $social_profile,
        'company_intelligence' => $company_intelligence,
        'templates' => $templates,
        'existing_site' => $existing_site,
        'profile_ready' => $profile_ready,
        'website_error' => $website_error,
        'selected_type' => $selected_type,
        'selected_template' => $selected_template,
        'setup_defaults' => $setup_defaults,
    ]);
} else {
    headerRedirect($link['LOGIN']);
}
