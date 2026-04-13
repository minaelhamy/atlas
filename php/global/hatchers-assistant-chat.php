<?php

$rawBody = file_get_contents('php://input');
hatchers_verify_signed_body($rawBody);

$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    hatchers_json_response(422, ['success' => false, 'error' => 'Invalid JSON payload.']);
}

$message = trim((string) ($payload['message'] ?? ''));
if ($message === '') {
    hatchers_json_response(422, ['success' => false, 'error' => 'Message is required.']);
}

$result = hatchers_find_or_create_user($payload);
$user = $result['user'];
$intelligence = hatchers_update_founder_intelligence($user->id(), $payload);
$history = hatchers_get_assistant_history($user->id());

$writeAction = hatchers_handle_write_action_message($user->id(), $message, [
    'role' => trim((string) ($payload['role'] ?? '')),
    'app' => trim((string) ($payload['app'] ?? 'atlas')),
]);

if (!empty($writeAction['handled'])) {
    $reply = trim((string) ($writeAction['reply'] ?? ''));
    if ($reply === '') {
        $reply = "I'm Atlas. I can help with confirmed write actions and founder guidance across Hatchers.";
    }

    hatchers_record_assistant_exchange($user->id(), trim((string) ($payload['app'] ?? 'atlas')), $message, $reply);

    hatchers_json_response(200, [
        'success' => true,
        'created' => (bool) $result['created'],
        'founder_user_id' => $user->id(),
        'reply' => $reply,
        'write_action' => [
            'handled' => true,
            'executed' => !empty($writeAction['executed']),
        ],
        'actions' => hatchers_get_founder_action_plan($user->id(), 5),
        'intelligence_updated_at' => isset($intelligence['updated_at']) ? $intelligence['updated_at'] : date('Y-m-d H:i:s'),
    ]);
}

$messages = [];
foreach (array_slice($history, -6) as $entry) {
    if (!empty($entry['user'])) {
        $messages[] = ['role' => 'user', 'content' => $entry['user']];
    }
    if (!empty($entry['assistant'])) {
        $messages[] = ['role' => 'assistant', 'content' => $entry['assistant']];
    }
}
$messages[] = ['role' => 'user', 'content' => $message];

$response = hatchers_call_openai_responses(
    $messages,
    hatchers_build_assistant_instructions($user->id(), $payload)
);

if (!$response['ok']) {
    hatchers_json_response(502, [
        'success' => false,
        'error' => $response['error'],
        'founder_user_id' => $user->id(),
    ]);
}

$reply = hatchers_extract_response_text($response['data']);
if ($reply === '') {
    $reply = "I'm Atlas. I can help you use Hatchers tools and point you to the next best step.";
}

hatchers_record_assistant_exchange($user->id(), trim((string) ($payload['app'] ?? 'atlas')), $message, $reply);

hatchers_json_response(200, [
    'success' => true,
    'created' => (bool) $result['created'],
    'founder_user_id' => $user->id(),
    'reply' => $reply,
    'actions' => hatchers_get_founder_action_plan($user->id(), 5),
    'intelligence_updated_at' => isset($intelligence['updated_at']) ? $intelligence['updated_at'] : date('Y-m-d H:i:s'),
]);
