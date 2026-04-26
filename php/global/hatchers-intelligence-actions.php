<?php

$rawBody = file_get_contents('php://input');
hatchers_verify_signed_body($rawBody);

$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    hatchers_json_response(422, ['success' => false, 'error' => 'Invalid JSON payload.']);
}

$result = hatchers_find_or_create_user($payload);
$user = $result['user'];
hatchers_update_founder_intelligence($user->id(), $payload);

$actionResult = hatchers_execute_external_action($user->id(), $payload);
if (empty($actionResult['success'])) {
    hatchers_json_response(422, [
        'success' => false,
        'created' => (bool) $result['created'],
        'user_id' => $user->id(),
        'error' => isset($actionResult['error']) ? $actionResult['error'] : 'Atlas could not execute that action.',
    ]);
}

hatchers_json_response(200, [
    'success' => true,
    'created' => (bool) $result['created'],
    'user_id' => $user->id(),
    'record_id' => isset($actionResult['record_id']) ? $actionResult['record_id'] : 0,
    'title' => isset($actionResult['title']) ? $actionResult['title'] : '',
    'edit_url' => isset($actionResult['edit_url']) ? $actionResult['edit_url'] : hatchers_campaign_edit_url(),
    'actions' => hatchers_get_founder_action_plan($user->id(), 6),
]);
