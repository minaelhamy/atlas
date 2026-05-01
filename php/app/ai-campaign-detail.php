<?php
global $config, $link;

if (!$config['enable_ai_images']) {
    page_not_found();
}

if (!isset($current_user['id'])) {
    headerRedirect($link['LOGIN']);
}

$campaign_notice = '';
$campaign_error = '';
$campaignId = isset($_GET['campaign_id']) ? trim((string) $_GET['campaign_id']) : '';
if ($campaignId === '') {
    headerRedirect($link['AI_IMAGES_CAMPAIGN']);
}

$selected_campaign = hatchers_get_campaign_record_by_id($_SESSION['user']['id'], $campaignId);
if (empty($selected_campaign)) {
    headerRedirect($link['AI_IMAGES_CAMPAIGN']);
}

$grid_catalog = social_media_get_instagram_grid_catalog();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['campaign_action']) && $_POST['campaign_action'] === 'save_campaign_detail') {
    $_POST = validate_input($_POST);
    $saveResult = hatchers_update_campaign_detail($_SESSION['user']['id'], $campaignId, [
        'title' => isset($_POST['title']) ? $_POST['title'] : '',
        'description' => isset($_POST['description']) ? $_POST['description'] : '',
        'grid_style' => isset($_POST['grid_style']) ? $_POST['grid_style'] : '',
        'actor_role' => 'founder',
    ]);

    if (!empty($saveResult['success'])) {
        $campaign_notice = __('Campaign detail updated successfully.');
        $selected_campaign = hatchers_get_campaign_record_by_id($_SESSION['user']['id'], $campaignId);
    } else {
        $campaign_error = !empty($saveResult['error']) ? $saveResult['error'] : __('Something went wrong while updating this campaign.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['campaign_action']) && $_POST['campaign_action'] === 'duplicate_campaign') {
    $duplicateResult = hatchers_duplicate_campaign_record($_SESSION['user']['id'], $campaignId);
    if (!empty($duplicateResult['success']) && !empty($duplicateResult['record_id'])) {
        headerRedirect(hatchers_campaign_record_url($duplicateResult['record_id']));
    }
    $campaign_error = !empty($duplicateResult['error']) ? $duplicateResult['error'] : __('Something went wrong while duplicating this campaign.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['campaign_action']) && $_POST['campaign_action'] === 'archive_campaign') {
    $archiveResult = hatchers_archive_campaign_record($_SESSION['user']['id'], $campaignId);
    if (!empty($archiveResult['success'])) {
        headerRedirect($link['DASHBOARD']);
    }
    $campaign_error = !empty($archiveResult['error']) ? $archiveResult['error'] : __('Something went wrong while archiving this campaign.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['campaign_action']) && $_POST['campaign_action'] === 'restore_campaign') {
    $restoreResult = hatchers_restore_campaign_record($_SESSION['user']['id'], $campaignId);
    if (!empty($restoreResult['success'])) {
        headerRedirect(hatchers_campaign_record_url($campaignId));
    }
    $campaign_error = !empty($restoreResult['error']) ? $restoreResult['error'] : __('Something went wrong while restoring this campaign.');
}

$total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);
$membership = get_user_membership_detail($_SESSION['user']['id']);
$images_limit = $membership['settings']['ai_images_limit'];
$selected_campaign_form = !empty($selected_campaign['form_state']) && is_array($selected_campaign['form_state'])
    ? $selected_campaign['form_state']
    : [];
$campaign_posts = social_media_get_posts_for_campaign($_SESSION['user']['id'], $selected_campaign['id'], 24);
$recent_campaigns = hatchers_get_recent_campaign_records($_SESSION['user']['id'], 6);
$archived_campaigns = hatchers_get_archived_campaign_records($_SESSION['user']['id'], 6);

HtmlTemplate::display('ai-campaign-detail', array(
    'total_images_used' => $total_images_used,
    'images_limit' => $images_limit,
    'selected_campaign' => $selected_campaign,
    'selected_campaign_form' => $selected_campaign_form,
    'campaign_posts' => $campaign_posts,
    'recent_campaigns' => $recent_campaigns,
    'archived_campaigns' => $archived_campaigns,
    'grid_catalog' => $grid_catalog,
    'campaign_notice' => $campaign_notice,
    'campaign_error' => $campaign_error
));
