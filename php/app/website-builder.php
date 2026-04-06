<?php
global $link;

if (!isset($current_user['id'])) {
    headerRedirect($link['LOGIN']);
    exit;
}

$social_profile = social_media_get_profile($_SESSION['user']['id']);
$company_intelligence = social_media_get_company_intelligence($_SESSION['user']['id']);
$profile_status = website_builder_company_profile_status($social_profile, $company_intelligence);
$profile_ready = !empty($profile_status['ready']);
$platform_target = website_platform_get_target($social_profile, $company_intelligence);
$launch_url = '';
$public_url = '';

if ($profile_ready) {
    $launch_url = website_platform_generate_launch_url($current_user, $social_profile, $company_intelligence);
    $public_url = website_platform_generate_public_url($current_user, $social_profile, $company_intelligence);
}

HtmlTemplate::display('website-builder', [
    'social_profile' => $social_profile,
    'company_intelligence' => $company_intelligence,
    'profile_status' => $profile_status,
    'profile_ready' => $profile_ready,
    'platform_target' => $platform_target,
    'website_launch_url' => $launch_url,
    'website_public_url' => $public_url,
    'website_auto_launch' => $profile_ready && empty($_GET['stay']),
]);
