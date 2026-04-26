<?php

function hatchers_launch_header($name)
{
    $key = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
    return isset($_SERVER[$key]) ? trim((string) $_SERVER[$key]) : '';
}

function hatchers_launch_redirect($path)
{
    header('Location: ' . $path);
    exit;
}

$sharedSecret = trim((string) get_env_setting('HATCHERS_SHARED_SECRET', get_env_setting('WEBSITE_PLATFORM_SHARED_SECRET', '')));
if ($sharedSecret === '') {
    die('HATCHERS_SHARED_SECRET is not configured.');
}

$username = trim((string) ($_GET['username'] ?? ''));
$email = trim((string) ($_GET['email'] ?? ''));
$role = trim((string) ($_GET['role'] ?? ''));
$target = trim((string) ($_GET['target'] ?? ''));
$expires = (int) ($_GET['expires'] ?? 0);
$signature = trim((string) ($_GET['signature'] ?? ''));

if ($expires < time()) {
    hatchers_launch_redirect(site_url('login'));
}

$expected = hash_hmac('sha256', implode('|', [$username, $email, $role, $target, (string) $expires]), $sharedSecret);
if ($signature === '' || !hash_equals($expected, $signature)) {
    hatchers_launch_redirect(site_url('login'));
}

global $config;

if ($role === 'admin') {
    $admin = null;
    if ($email !== '') {
        $admin = ORM::for_table($config['db']['pre'] . 'admins')->where('email', $email)->find_one();
    }
    if (empty($admin) && $username !== '') {
        $admin = ORM::for_table($config['db']['pre'] . 'admins')->where('username', $username)->find_one();
    }

    if (empty($admin)) {
        hatchers_launch_redirect(site_url('admin'));
    }

    $user_browser = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['admin']['id'] = preg_replace("/[^0-9]+/", "", $admin['id']);
    $_SESSION['admin']['username'] = preg_replace("/[^a-zA-Z0-9_\\-]+/", "", $admin['username']);
    $_SESSION['admin']['login_string'] = hash('sha512', $admin['password_hash'] . $user_browser);
    $_SESSION['admin']['permission'] = $admin['permission'];
    setcookie("qarm", $_SESSION['admin']['id'] . "." . $_SESSION['admin']['login_string'], time() + 86400 * 30, "/");

    hatchers_launch_redirect($target !== '' ? $target : site_url('admin'));
}

$user = null;
if ($email !== '') {
    $user = ORM::for_table($config['db']['pre'] . 'user')->where('email', $email)->find_one();
}
if (empty($user) && $username !== '') {
    $user = ORM::for_table($config['db']['pre'] . 'user')->where('username', $username)->find_one();
}

if (empty($user)) {
    hatchers_launch_redirect(site_url('login'));
}

create_user_session($user['id'], $user['username'], $user['password_hash'], $user['user_type']);
hatchers_launch_redirect($target !== '' ? $target : site_url('dashboard'));
