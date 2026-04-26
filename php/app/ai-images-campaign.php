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
    $selected_campaign = hatchers_get_campaign_record_by_id($_SESSION['user']['id'], isset($_GET['campaign_id']) ? $_GET['campaign_id'] : '');
    $prefill_campaign_notes = '';
    $selected_campaign_form = [];
    $campaign_posts = [];
    if (!empty($selected_campaign)) {
        $prefill_campaign_notes = trim((string) ($selected_campaign['title'] ?? ''));
        $description = trim((string) ($selected_campaign['description'] ?? ''));
        if ($description !== '') {
            $prefill_campaign_notes .= ($prefill_campaign_notes !== '' ? "\n\n" : '') . $description;
        }
        $selected_campaign_form = !empty($selected_campaign['form_state']) && is_array($selected_campaign['form_state'])
            ? $selected_campaign['form_state']
            : [];
        $campaign_posts = social_media_get_posts_for_campaign($_SESSION['user']['id'], $selected_campaign['id'], 18);
    }

    HtmlTemplate::display('ai-images', array(
        'total_images_used' => $total_images_used,
        'images_limit' => $images_limit,
        'social_posts' => $social_posts,
        'social_profile' => $social_profile,
        'campaign_catalog' => $campaign_catalog,
        'focus_options' => $focus_options,
        'content_angle_options' => $content_angle_options,
        'use_case_options' => $use_case_options,
        'funnel_stage_catalog' => $funnel_stage_catalog,
        'selected_campaign' => $selected_campaign,
        'prefill_campaign_notes' => $prefill_campaign_notes,
        'selected_campaign_form' => $selected_campaign_form,
        'campaign_posts' => $campaign_posts
    ));
} else {
    headerRedirect($link['LOGIN']);
}
