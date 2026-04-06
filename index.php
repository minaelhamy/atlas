<?php
/**
 * Atlas application bootstrap.
 */

function atlas_relative_request_path()
{
    $requestPath = parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
    $requestPath = $requestPath ?: '/';
    $requestPath = ltrim($requestPath, '/');

    $scriptDir = trim(str_replace("\\", "/", dirname(isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '')), '/');
    if ($scriptDir !== '' && strpos($requestPath, $scriptDir . '/') === 0) {
        $requestPath = substr($requestPath, strlen($scriptDir) + 1);
    } elseif ($requestPath === $scriptDir) {
        $requestPath = '';
    }

    return $requestPath;
}

function atlas_platform_static_candidates($basePath, $subPath)
{
    $subPath = ltrim($subPath, '/');
    $candidates = [
        $basePath . '/public/' . $subPath,
        $basePath . '/' . $subPath,
        $basePath . '/storage/app/public/' . $subPath,
    ];

    if (strpos($subPath, 'storage/') === 0) {
        $candidates[] = $basePath . '/storage/app/public/' . substr($subPath, 8);
    }

    return $candidates;
}

function atlas_serve_platform_asset($basePath, $subPath)
{
    foreach (atlas_platform_static_candidates($basePath, $subPath) as $candidate) {
        if (is_file($candidate)) {
            $mime = function_exists('mime_content_type') ? mime_content_type($candidate) : null;
            if ($mime) {
                header('Content-Type: ' . $mime);
            }
            header('Content-Length: ' . filesize($candidate));
            readfile($candidate);
            return true;
        }
    }

    return false;
}

function atlas_delegate_platform_front_controller($scriptName, $scriptFile)
{
    $_SERVER['SCRIPT_NAME'] = $scriptName;
    $_SERVER['PHP_SELF'] = $scriptName;
    $_SERVER['SCRIPT_FILENAME'] = $scriptFile;
    require $scriptFile;
    exit;
}

$atlasPlatformRequest = atlas_relative_request_path();
if (preg_match('#^(ecom|service)(?:/(.*))?$#', $atlasPlatformRequest, $matches)) {
    $mount = $matches[1];
    $subPath = isset($matches[2]) ? $matches[2] : '';

    $platformConfig = [
        'ecom' => [
            'base' => __DIR__ . '/Storemart_SaaS',
            'entry' => __DIR__ . '/Storemart_SaaS/public/index.php',
            'script' => '/ecom/index.php',
        ],
        'service' => [
            'base' => __DIR__ . '/BookingDo_SaaS',
            'entry' => __DIR__ . '/BookingDo_SaaS/index.php',
            'script' => '/service/index.php',
        ],
    ];

    $platform = $platformConfig[$mount];
    if ($subPath !== '' && atlas_serve_platform_asset($platform['base'], $subPath)) {
        exit;
    }

    atlas_delegate_platform_front_controller($platform['script'], $platform['entry']);
}

// Path to root directory of app.
define("ROOTPATH", dirname(__FILE__));
// Path to app folder.
define("APPPATH", ROOTPATH."/php/");


// Check if SSL enabled
if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
    $protocol = $_SERVER["HTTP_X_FORWARDED_PROTO"] == "https" ? "https://" : "http://";
else
    $protocol = !empty($_SERVER['HTTPS']) && $_SERVER["HTTPS"] != "off" ? "https://" : "http://";

// Define APPURL
$site_url = $protocol
    . $_SERVER["HTTP_HOST"]
    . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
    . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

define("SITEURL", $site_url);

require_once ROOTPATH . '/includes/config.php';

if (!isset($config['installed']) || !$config['installed']) {
    http_response_code(503);
    exit('Application is not configured. Set the required environment variables before serving Atlas.');
}

require_once ROOTPATH . '/includes/lib/AltoRouter.php';

// Start routing.
$router = new AltoRouter();
$bp = trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");
$router->setBasePath($bp ? "/".$bp : "");
/* Setup the URL routing. This is production ready. */
require_once APPPATH.'_route.php';

// API Routes
require_once ROOTPATH . '/includes/autoload.php';
define("TEMPLATE_PATH", ROOTPATH.'/templates/'.$config['tpl_name']);
define("TEMPLATE_URL", SITEURL.'/templates/'.$config['tpl_name']);

$config['app_url'] = get_site_url(SITEURL)."/php/";

/* Match the current request */
$match=$router->match();

if(isset($match['params']['lang'])) {
    if ($match['params']['lang'] != ""){
        change_user_lang($match['params']['lang']);
    }
}
if(file_exists(ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php')){
    require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
}else{
    require_once ROOTPATH . '/includes/lang/lang_english.php';
}

if($match) {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $_GET = array_merge($match['params'],$_GET);
    }

    sec_session_start();
    $mysqli = db_connect();

    if(get_option('enable_maintenance_mode')){

        $protocol = isset( $_SERVER['SERVER_PROTOCOL'] ) ? $_SERVER['SERVER_PROTOCOL'] : '';
        if ( ! in_array( $protocol, array( 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0' ), true ) ) {
            $protocol = 'HTTP/1.0';
        }
        header( "$protocol 503 Service Unavailable", true, 503 );
        header( 'Content-Type: text/html; charset=utf-8' );
        header( 'Retry-After: 30' );

        // current page
        define("CURRENT_PAGE", 'maintenance_mode');

        require APPPATH.'global/maintenance_mode.php';
        exit;
    }

    // current page
    define("CURRENT_PAGE", str_replace('.php', '', $match['target']));

    check_affiliate();

    if(isset($_GET['quickapp'])){
        $_COOKIE['quickapp'] = 1;
        setcookie('quickapp', 1, time() + (86400 * 30), "/");
    }

    /* get user data */
    $current_user = null;
    if(checkloggedin()) {
        $current_user = get_user_data(null, $_SESSION['user']['id']);

        /* check for free plan */
        if(CURRENT_PAGE != 'app/home' && strpos(CURRENT_PAGE, 'app/') !== false) {
            if ($current_user['group_id'] == 'free') {
                /* redirect to membership page if free plan is disabled */
                $free_plan = json_decode(get_option('free_membership_plan'), true);
                if (!$free_plan['status']) {
                    headerRedirect($config['site_url'] . 'membership/changeplan');
                }
            }
        }
        $current_user['plan'] = get_user_membership_detail($_SESSION['user']['id']);
    }

    require APPPATH.$match['target'];
}
else {
    // current page
    define("CURRENT_PAGE", '404');

    header("HTTP/1.0 404 Not Found");
    require APPPATH.'global/404.php';
}

/* close DB connection */
$mysqli->close();

ORM::set_db(null);
