<?php

$rawBody = file_get_contents('php://input');
hatchers_verify_signed_body($rawBody);

$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    hatchers_json_response(422, ['success' => false, 'error' => 'Invalid JSON payload.']);
}

$result = hatchers_find_or_create_user($payload);
$user = $result['user'];
$intelligence = hatchers_update_founder_intelligence($user->id(), $payload);
global $config;

$conversations = ORM::for_table($config['db']['pre'] . 'ai_chat_conversations')
    ->where('user_id', $user->id())
    ->order_by_desc('updated_at')
    ->limit(12)
    ->find_array();

$defaultChats = ORM::for_table($config['db']['pre'] . 'ai_chat')
    ->where('user_id', $user->id())
    ->where_null('conversation_id')
    ->count();

if ($defaultChats) {
    array_unshift($conversations, [
        'id' => 'default',
        'title' => __('New Conversation'),
        'last_message' => '...',
        'updated_at' => date('Y-m-d H:i:s'),
        'bot_id' => null,
    ]);
}

if (empty($conversations)) {
    $conversations[] = [
        'id' => '',
        'title' => __('New Conversation'),
        'last_message' => '...',
        'updated_at' => date('Y-m-d H:i:s'),
        'bot_id' => null,
    ];
}

$recentCampaigns = [];
foreach (hatchers_get_recent_campaign_records($user->id(), 12) as $campaign) {
    $campaignId = trim((string) ($campaign['id'] ?? ''));
    $recentCampaigns[] = [
        'id' => $campaignId,
        'title' => trim((string) ($campaign['title'] ?? __('Campaign'))),
        'description' => trim((string) ($campaign['description'] ?? '')),
        'generated_posts_count' => count(social_media_get_posts_for_campaign($user->id(), $campaignId, 24)),
        'updated_at' => trim((string) ($campaign['updated_at'] ?? '')),
        'target_path' => '/ai-images/campaign-detail?campaign_id=' . rawurlencode($campaignId),
    ];
}

$archivedCampaigns = [];
foreach (hatchers_get_archived_campaign_records($user->id(), 12) as $campaign) {
    $campaignId = trim((string) ($campaign['id'] ?? ''));
    $archivedCampaigns[] = [
        'id' => $campaignId,
        'title' => trim((string) ($campaign['title'] ?? __('Campaign'))),
        'description' => trim((string) ($campaign['description'] ?? '')),
        'generated_posts_count' => count(social_media_get_posts_for_campaign($user->id(), $campaignId, 24)),
        'updated_at' => trim((string) ($campaign['updated_at'] ?? '')),
        'target_path' => '/ai-images/campaign-detail?campaign_id=' . rawurlencode($campaignId),
    ];
}

$mediaOutputs = [];
foreach (social_media_get_recent_posts($user->id(), 18) as $post) {
    $campaign = !empty($post['campaign']) && is_array($post['campaign']) ? $post['campaign'] : [];
    $mediaOutputs[] = [
        'id' => (int) ($post['id'] ?? 0),
        'title' => trim((string) ($post['title'] ?? ($post['overlay_text'] ?? __('Media output')))),
        'post_type' => trim((string) ($post['post_type'] ?? 'post')),
        'preview_image' => trim((string) ($post['preview_image'] ?? '')),
        'rendered_video' => trim((string) ($post['rendered_video'] ?? '')),
        'campaign_title' => trim((string) ($campaign['title'] ?? '')),
        'campaign_id' => trim((string) ($campaign['id'] ?? '')),
        'updated_at' => trim((string) ($post['updated_at'] ?? ($post['created_at'] ?? ''))),
        'target_path' => '/all-images',
    ];
}

$documents = [];
$documentRows = ORM::for_table($config['db']['pre'] . 'ai_documents')
    ->where('user_id', $user->id())
    ->order_by_desc('id')
    ->limit(10)
    ->find_many();

foreach ($documentRows as $row) {
    $documents[] = [
        'id' => (int) $row['id'],
        'title' => trim((string) $row['title']),
        'template' => trim((string) $row['template']),
        'content' => strlimiter(strip_tags((string) $row['content']), 120),
        'updated_at' => trim((string) ($row['updated_at'] ?: $row['created_at'])),
        'target_path' => '/all-documents/' . rawurlencode((string) $row['id']),
    ];
}

hatchers_json_response(200, [
    'success' => true,
    'created' => (bool) $result['created'],
    'user_id' => $user->id(),
    'updated_at' => isset($intelligence['updated_at']) ? $intelligence['updated_at'] : date('Y-m-d H:i:s'),
    'conversations' => $conversations,
    'recent_campaigns' => $recentCampaigns,
    'archived_campaigns' => $archivedCampaigns,
    'media_outputs' => $mediaOutputs,
    'documents' => $documents,
]);
