<?php
global $config;

// if disabled by admin
if(!$config['enable_ai_images']) {
    page_not_found();
}

if(isset($current_user['id']))
{
    $social_profile = social_media_get_profile($_SESSION['user']['id']);

    HtmlTemplate::display('ai-images-hub', array(
        'social_profile' => $social_profile,
    ));
}
else{
    headerRedirect($link['LOGIN']);
}
