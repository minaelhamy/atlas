<?php
global $config;

// if disabled by admin
if(!$config['enable_ai_images']) {
    page_not_found();
}

if (isset($current_user['id'])) {

    if (!isset($_GET['page']))
        $page = 1;
    else
        $page = $_GET['page'];

    $limit = 25;

    social_media_bootstrap();

    $orm = ORM::for_table($config['db']['pre'] . 'social_media_posts')
        ->where('user_id', $_SESSION['user']['id'])
        ->order_by_desc('id');

    $total = $orm->count();

    $rows = $orm
        ->limit($limit)
        ->offset(($page - 1) * $limit)
        ->find_many();

    $images = array();
    foreach ($rows as $row) {
        $meta = !empty($row['metadata']) ? json_decode($row['metadata'], true) : [];
        $images[$row['id']]['id'] = $row['id'];
        $images[$row['id']]['title'] = $row['title'];
        $images[$row['id']]['description'] = strip_tags($row['caption']);
        $images[$row['id']]['image'] = $row['preview_image'];
        $images[$row['id']]['post_type'] = $row['post_type'];
        $images[$row['id']]['rendered_video'] = !empty($meta['rendered_video']) ? $meta['rendered_video'] : '';
        $images[$row['id']]['hashtags'] = !empty($meta['hashtags']) && is_array($meta['hashtags']) ? implode(' ', $meta['hashtags']) : '';
        $images[$row['id']]['date'] = date('d M, Y', strtotime($row['created_at']));
        $images[$row['id']]['time'] = date('H:i:s', strtotime($row['created_at']));
    }

    $pagging = pagenav($total, $page, $limit, $link['ALL_IMAGES']);

    $start = date('Y-m-01');
    $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');

    $total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);

    $membership = get_user_membership_detail($_SESSION['user']['id']);
    $images_limit = $membership['settings']['ai_images_limit'];

    HtmlTemplate::display('all-images', array(
        'images' => $images,
        'pagging' => $pagging,
        'show_paging' => (int)($total > $limit),
        'total_images_used' => $total_images_used,
        'images_limit' => $images_limit
    ));
} else {
    headerRedirect($link['LOGIN']);
}
