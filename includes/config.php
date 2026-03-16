<?php
require_once __DIR__ . '/env.php';

$config['db']['host'] = atlas_env('DB_HOST', 'localhost');
$config['db']['name'] = atlas_env('DB_NAME', '');
$config['db']['user'] = atlas_env('DB_USER', '');
$config['db']['pass'] = atlas_env('DB_PASS', '');
$config['db']['pre'] = atlas_env('DB_PREFIX', 'qa_');
$config['db']['port'] = atlas_env('DB_PORT', '');

$config['version'] = atlas_env('APP_VERSION', '4.6');
$config['installed'] = atlas_env('APP_INSTALLED', '1');
?>
