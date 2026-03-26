<?php
global $config;

if (!$config['enable_ai_images']) {
    page_not_found();
}

if (isset($current_user['id'])) {
    $total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);

    $membership = get_user_membership_detail($_SESSION['user']['id']);
    $images_limit = $membership['settings']['ai_images_limit'];

    $social_posts = social_media_get_recent_posts($_SESSION['user']['id'], 18);
    $social_profile = social_media_get_profile($_SESSION['user']['id']);
    $campaign_catalog = social_media_get_campaign_catalog();
    $focus_options = social_media_get_selection_options($campaign_catalog, 'focus');
    $content_angle_options = social_media_get_selection_options($campaign_catalog, 'content_examples');
    $use_case_options = social_media_get_selection_options($campaign_catalog, 'when_to_use');
    $funnel_stage_catalog = social_media_get_funnel_stage_catalog();

    HtmlTemplate::display('ai-images', array(
        'total_images_used' => $total_images_used,
        'images_limit' => $images_limit,
        'social_posts' => $social_posts,
        'social_profile' => $social_profile,
        'campaign_catalog' => $campaign_catalog,
        'focus_options' => $focus_options,
        'content_angle_options' => $content_angle_options,
        'use_case_options' => $use_case_options,
        'funnel_stage_catalog' => $funnel_stage_catalog
    ));
} else {
    headerRedirect($link['LOGIN']);
}
