<?php

function hatchers_sync_respond($status, array $payload)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($payload);
    exit;
}

function hatchers_sync_header($name)
{
    $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
    return isset($_SERVER[$key]) ? trim((string) $_SERVER[$key]) : '';
}

function hatchers_sync_find_user(array $identifiers)
{
    global $config;

    $pairs = [];
    foreach (['username', 'previous_username', 'email', 'previous_email'] as $field) {
        $value = trim((string) ($identifiers[$field] ?? ''));
        if ($value !== '') {
            $pairs[] = [$field === 'email' || $field === 'previous_email' ? 'email' : 'username', $value];
        }
    }

    foreach ($pairs as $pair) {
        $user = ORM::for_table($config['db']['pre'] . 'user')
            ->where($pair[0], $pair[1])
            ->find_one();
        if (!empty($user)) {
            return $user;
        }
    }

    return null;
}

$sharedSecret = trim((string) get_env_setting('HATCHERS_SHARED_SECRET', get_env_setting('WEBSITE_PLATFORM_SHARED_SECRET', '')));
if ($sharedSecret === '') {
    hatchers_sync_respond(500, ['success' => false, 'error' => 'HATCHERS_SHARED_SECRET is not configured.']);
}

$rawBody = file_get_contents('php://input');
$signature = hatchers_sync_header('X-Hatchers-Signature');
$expected = hash_hmac('sha256', $rawBody, $sharedSecret);
if ($signature === '' || !hash_equals($expected, $signature)) {
    hatchers_sync_respond(403, ['success' => false, 'error' => 'Invalid sync signature.']);
}

$payload = json_decode($rawBody, true);
if (!is_array($payload)) {
    hatchers_sync_respond(422, ['success' => false, 'error' => 'Invalid JSON payload.']);
}

$username = trim((string) ($payload['username'] ?? ''));
$email = trim((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$name = trim((string) ($payload['name'] ?? ''));

if ($username === '') {
    hatchers_sync_respond(422, ['success' => false, 'error' => 'Username is required.']);
}

$user = hatchers_sync_find_user($payload);
$isNew = empty($user);

global $config;
$existingUsername = ORM::for_table($config['db']['pre'] . 'user')
    ->where('username', $username)
    ->find_one();
if (!empty($existingUsername) && ($isNew || (int) $existingUsername['id'] !== (int) $user['id'])) {
    hatchers_sync_respond(422, ['success' => false, 'error' => 'Username already exists in Atlas.']);
}

if ($email !== '') {
    $existingEmail = ORM::for_table($config['db']['pre'] . 'user')
        ->where('email', $email)
        ->find_one();
    if (!empty($existingEmail) && ($isNew || (int) $existingEmail['id'] !== (int) $user['id'])) {
        hatchers_sync_respond(422, ['success' => false, 'error' => 'Email already exists in Atlas.']);
    }
}

$now = date('Y-m-d H:i:s');
if ($isNew) {
    $location = getLocationInfoByIp();
    $user = ORM::for_table($config['db']['pre'] . 'user')->create();
    $user->status = '1';
    $user->group_id = get_option('default_user_plan');
    $user->created_at = $now;
    $user->country = isset($location['country']) ? $location['country'] : '';
    $user->country_code = isset($location['countryCode']) ? $location['countryCode'] : '';
    $user->referral_key = uniqid(get_random_string(5));
    $user->user_type = 'user';
}

$user->name = $name !== '' ? $name : $username;
$user->username = $username;
$user->email = $email !== '' ? $email : null;
$user->updated_at = $now;

if ($password !== '') {
    $user->password_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
}

$user->save();

if (!empty($payload['company_brief'])) {
    update_user_option($user->id(), 'hatchers_company_brief', trim((string) $payload['company_brief']));
}
if (!empty($payload['phone'])) {
    update_user_option($user->id(), 'hatchers_phone', trim((string) $payload['phone']));
}
update_user_option($user->id(), 'hatchers_source', 'lms');

hatchers_sync_respond(200, [
    'success' => true,
    'created' => $isNew,
    'user_id' => $user->id(),
]);
