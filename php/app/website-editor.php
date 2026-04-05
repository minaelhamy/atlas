<?php
global $config, $link;

if (isset($current_user['id'])) {
    website_builder_ensure_tables();

    $siteId = !empty($matches['id']) ? (int) $matches['id'] : 0;
    $site = $siteId ? website_builder_get_site($siteId, $_SESSION['user']['id']) : website_builder_get_primary_site($_SESSION['user']['id']);

    if (empty($site)) {
        transfer($link['YOUR_WEBSITE'], __('Create your first website draft to continue.'), __('Website draft required'));
        exit;
    }

    if (isset($_POST['save_website_product'])) {
        website_builder_save_product($site['id'], [
            'title' => validate_input($_POST['product_title']),
            'description' => validate_input($_POST['product_description']),
            'price' => validate_input($_POST['product_price']),
            'currency' => validate_input($_POST['product_currency']),
            'status' => 'active',
        ], !empty($_POST['product_id']) ? (int) $_POST['product_id'] : 0);
        transfer(website_builder_get_editor_url($site['id'], ['page' => !empty($_GET['page']) ? validate_input($_GET['page']) : 'home']), __('Product saved successfully.'), __('Catalog updated'));
        exit;
    }

    if (isset($_POST['save_website_service'])) {
        $schedule = [];
        foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] as $day) {
            $schedule[$day] = [
                'enabled' => !empty($_POST['service_schedule'][$day]['enabled']) ? 1 : 0,
                'start' => !empty($_POST['service_schedule'][$day]['start']) ? validate_input($_POST['service_schedule'][$day]['start']) : '',
                'end' => !empty($_POST['service_schedule'][$day]['end']) ? validate_input($_POST['service_schedule'][$day]['end']) : '',
            ];
        }
        website_builder_save_service($site['id'], [
            'title' => validate_input($_POST['service_title']),
            'description' => validate_input($_POST['service_description']),
            'duration_minutes' => validate_input($_POST['service_duration_minutes']),
            'price' => validate_input($_POST['service_price']),
            'currency' => validate_input($_POST['service_currency']),
            'availability_note' => validate_input($_POST['service_availability_note']),
            'schedule' => $schedule,
            'slot_interval_minutes' => validate_input($_POST['service_slot_interval_minutes']),
            'booking_buffer_minutes' => validate_input($_POST['service_booking_buffer_minutes']),
            'min_notice_hours' => validate_input($_POST['service_min_notice_hours']),
            'max_advance_days' => validate_input($_POST['service_max_advance_days']),
            'blocked_dates' => validate_input($_POST['service_blocked_dates']),
            'status' => 'active',
        ], !empty($_POST['service_id']) ? (int) $_POST['service_id'] : 0);
        transfer(website_builder_get_editor_url($site['id'], ['page' => !empty($_GET['page']) ? validate_input($_GET['page']) : 'home']), __('Service saved successfully.'), __('Services updated'));
        exit;
    }

    if (isset($_POST['delete_website_product']) && !empty($_POST['product_id'])) {
        website_builder_delete_product($site['id'], (int) $_POST['product_id']);
        transfer(website_builder_get_editor_url($site['id']), __('Product removed successfully.'), __('Catalog updated'));
        exit;
    }

    if (isset($_POST['delete_website_service']) && !empty($_POST['service_id'])) {
        website_builder_delete_service($site['id'], (int) $_POST['service_id']);
        transfer(website_builder_get_editor_url($site['id']), __('Service removed successfully.'), __('Services updated'));
        exit;
    }

    if (isset($_POST['save_website_page'])) {
        $pageKey = !empty($_POST['page_key']) ? validate_input($_POST['page_key']) : 'home';
        $page = website_builder_get_page($site['id'], $pageKey);

        if (!empty($page)) {
            $content = $page['content'];

            if ($pageKey === 'home') {
                $content['hero'] = [
                    'eyebrow' => validate_input($_POST['hero_eyebrow']),
                    'title' => validate_input($_POST['hero_title']),
                    'subtitle' => validate_input($_POST['hero_subtitle']),
                    'primary_cta' => validate_input($_POST['hero_primary_cta']),
                    'secondary_cta' => validate_input($_POST['hero_secondary_cta']),
                ];
                $content['offerings'] = website_builder_split_values(isset($_POST['offerings']) ? $_POST['offerings'] : '', 8);
                $content['proof'] = website_builder_split_values(isset($_POST['proof']) ? $_POST['proof'] : '', 8);
                $content['faq'] = [
                    [
                        'question' => validate_input($_POST['faq_question_1']),
                        'answer' => validate_input($_POST['faq_answer_1']),
                    ],
                    [
                        'question' => validate_input($_POST['faq_question_2']),
                        'answer' => validate_input($_POST['faq_answer_2']),
                    ],
                ];
            } else {
                $content['title'] = validate_input($_POST['section_title']);
                $content['body'] = validate_input($_POST['section_body']);
            }

            website_builder_update_page($site['id'], $pageKey, $content, $page['title']);
            transfer(website_builder_get_editor_url($site['id'], ['page' => $pageKey]), __('Website section saved successfully.'), __('Draft updated'));
            exit;
        }
    }

    $pages = website_builder_get_site_pages($site['id']);
    $products = website_builder_get_products($site['id']);
    $services = website_builder_get_services($site['id']);
    $wallet_summary = website_builder_get_wallet_summary($site['id']);
    $selectedPageKey = !empty($_GET['page']) ? validate_input($_GET['page']) : (!empty($pages[0]['page_key']) ? $pages[0]['page_key'] : 'home');
    $selectedPage = website_builder_get_page($site['id'], $selectedPageKey);
    if (empty($selectedPage) && !empty($pages[0])) {
        $selectedPage = $pages[0];
        $selectedPageKey = $selectedPage['page_key'];
    }
    $social_profile = social_media_get_profile($_SESSION['user']['id']);
    $company_intelligence = social_media_get_company_intelligence($_SESSION['user']['id']);

    HtmlTemplate::display('website-editor', [
        'site' => $site,
        'pages' => $pages,
        'selected_page' => $selectedPage,
        'products' => $products,
        'services' => $services,
        'wallet_summary' => $wallet_summary,
        'social_profile' => $social_profile,
        'company_intelligence' => $company_intelligence,
    ]);
} else {
    headerRedirect($link['LOGIN']);
}
