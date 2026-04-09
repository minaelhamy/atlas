<?php
if (!checkloggedin()) {
    headerRedirect($link['LOGIN']);
}

$profile = social_media_get_profile($_SESSION['user']['id']);

if (empty($profile['company_name']) && empty($profile['company_description'])) {
    transfer($link['COMPANY_INTELLIGENCE'], __('Complete your Company Intelligence first so Atlas can choose the right website builder.'), 'error');
}

$launch = social_media_prepare_builder_login($_SESSION['user']['id']);
if (empty($launch['success'])) {
    $message = !empty($launch['error']) ? $launch['error'] : __('Could not prepare your builder account right now.');
    transfer($link['DASHBOARD'], $message, 'error');
}

headerRedirect($launch['login_url']);
