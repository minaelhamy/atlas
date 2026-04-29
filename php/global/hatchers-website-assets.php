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
$assetSlots = hatchers_resolve_website_autopilot_assets($user->id());

hatchers_json_response(200, [
    'success' => true,
    'created' => (bool) $result['created'],
    'founder_user_id' => $user->id(),
    'asset_slots' => $assetSlots,
    'intelligence_updated_at' => isset($intelligence['updated_at']) ? $intelligence['updated_at'] : date('Y-m-d H:i:s'),
]);
