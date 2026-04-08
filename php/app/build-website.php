<?php
if (!checkloggedin()) {
    headerRedirect($link['LOGIN']);
}

$profile = social_media_get_profile($_SESSION['user']['id']);

if (empty($profile['company_name']) && empty($profile['company_description'])) {
    transfer($link['COMPANY_INTELLIGENCE'], __('Complete your Company Intelligence first so Atlas can choose the right website builder.'), 'error');
}

$target = social_media_get_builder_launch_target($_SESSION['user']['id']);
$destination = !empty($target['url']) ? $target['url'] : 'https://servio.hatchers.ai';

headerRedirect($destination);
