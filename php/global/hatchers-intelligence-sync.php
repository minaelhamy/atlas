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
hatchers_push_os_snapshot($user->id(), trim((string) ($payload['current_page'] ?? 'company-intelligence')), [
    'activity' => trim((string) ($payload['sync_summary'] ?? 'Atlas intelligence synced.')),
]);

hatchers_json_response(200, [
    'success' => true,
    'created' => (bool) $result['created'],
    'user_id' => $user->id(),
    'updated_at' => isset($intelligence['updated_at']) ? $intelligence['updated_at'] : date('Y-m-d H:i:s'),
]);
