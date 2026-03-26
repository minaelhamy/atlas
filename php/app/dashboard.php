<?php
global $config, $link;
if (isset($current_user['id'])) {
    $start = date('Y-m-01');
    $end = date_create(date('Y-m-t'))->modify('+1 day')->format('Y-m-d');

    $days = $word_used = [];

    $period = new \DatePeriod(date_create($start), \DateInterval::createFromDateString('1 day'), date_create($end));
    /** @var \DateTime $dt */
    foreach ($period as $dt) {
        $days[] = date('d M', $dt->getTimestamp());
        $word_used[date('d M', $dt->getTimestamp())] = 0;
    }

    $total_words_used = get_user_option($_SESSION['user']['id'], 'total_words_used', 0);

    $total_images_used = get_user_option($_SESSION['user']['id'], 'total_images_used', 0);

    $total_speech_used = get_user_option($_SESSION['user']['id'], 'total_speech_used', 0);

    $total_documents_created = ORM::for_table($config['db']['pre'] . 'ai_documents')
        ->where('user_id', $_SESSION['user']['id'])
        ->count();

    $membership = get_user_membership_detail($_SESSION['user']['id']);
    $membership_name = $membership['name'];
    $membership_settings = $membership['settings'];
    $company_intelligence = social_media_get_company_intelligence($_SESSION['user']['id']);

    $top_agents = [];
    if ($config['enable_ai_chat']) {
        $agentSql = "SELECT b.id, b.name, b.role, b.image, COUNT(c.id) AS usage_count
            FROM `".$config['db']['pre']."ai_chat` c
            INNER JOIN `".$config['db']['pre']."ai_chat_bots` b ON b.id = c.bot_id
            WHERE c.user_id = " . (int) $_SESSION['user']['id'] . " AND c.bot_id IS NOT NULL AND b.active = 1
            GROUP BY b.id, b.name, b.role, b.image
            ORDER BY usage_count DESC, b.position ASC, b.id ASC
            LIMIT 2";

        $agentRows = ORM::for_table($config['db']['pre'] . 'ai_chat')->raw_query($agentSql)->find_many();
        foreach ($agentRows as $agent) {
            $top_agents[] = [
                'id' => $agent['id'],
                'name' => $agent['name'],
                'role' => $agent['role'],
                'usage_count' => (int) $agent['usage_count'],
                'link' => $link['AI_CHAT'] . '/' . $agent['id'],
                'image' => !empty($agent['image'])
                    ? $config['site_url'] . 'storage/chat-bots/' . $agent['image']
                    : get_avatar_url_by_name($agent['name'])
            ];
        }
    }

    $recent_social_posts = [];
    if (get_option('enable_ai_images')) {
        $recentPosts = social_media_get_recent_posts($_SESSION['user']['id'], 2);
        foreach ($recentPosts as $post) {
            $recent_social_posts[] = [
                'id' => $post['id'],
                'title' => $post['title'],
                'overlay_text' => $post['overlay_text'],
                'created_at' => !empty($post['created_at']) ? timeAgo($post['created_at']) : '',
                'preview_url' => !empty($post['preview_image']) ? $config['site_url'] . 'storage/social_posts/' . $post['preview_image'] : '',
                'link' => $link['ALL_IMAGES']
            ];
        }
    }

    $recent_generated_content = [];
    $docRows = ORM::for_table($config['db']['pre'] . 'ai_documents')
        ->where('user_id', $_SESSION['user']['id'])
        ->order_by_desc('id')
        ->limit(2)
        ->find_many();

    foreach ($docRows as $docRow) {
        $recent_generated_content[] = [
            'id' => $docRow['id'],
            'title' => $docRow['title'],
            'content' => strlimiter(strip_tags((string) $docRow['content']), 90),
            'created_at' => !empty($docRow['created_at']) ? timeAgo($docRow['created_at']) : '',
            'link' => $link['ALL_DOCUMENTS']
        ];
    }


    $sql = "SELECT DATE(`date`) AS created, SUM(`words`) AS used_words 
                FROM " . $config['db']['pre'] . "word_used 
                WHERE 
                    `user_id` = {$_SESSION['user']['id']} 
                    AND `date` BETWEEN '$start' AND '$end'
                GROUP BY DATE(`date`)";

    $result = ORM::for_table($config['db']['pre'] . 'word_used')
        ->raw_query($sql)
        ->find_many();

    foreach ($result as $data) {
        $word_used[date('d M', strtotime($data['created']))] = $data['used_words'];
    }

    HtmlTemplate::display('dashboard', array(
        'membership_name' => $membership_name,
        'membership_settings' => $membership_settings,
        'total_documents_created' => $total_documents_created,
        'total_speech_used' => $total_speech_used,
        'total_words_used' => $total_words_used ?: 0,
        'total_images_used' => $total_images_used ?: 0,
        'company_intelligence' => $company_intelligence,
        'top_agents' => $top_agents,
        'recent_social_posts' => $recent_social_posts,
        'recent_generated_content' => $recent_generated_content
    ));
} else {
    headerRedirect($link['LOGIN']);
}
