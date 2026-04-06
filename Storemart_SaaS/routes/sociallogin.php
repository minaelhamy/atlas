<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\SocialLoginController;
use App\Helpers\helper;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




 //Login with Google
 Route::get('checklogin/google/callback-{logintype}', [SocialLoginController::class, 'check_login']);

 //Login with facebook
 Route::get('checklogin/facebook/callback-{logintype}', [SocialLoginController::class, 'check_login']);
Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::post('/social_login', [SocialLoginController::class, 'socialloginsettings']);
    Route::get('login/google-{type}', [SocialLoginController::class, 'redirectToGoogle']);
    Route::get('login/facebook-{type}', [SocialLoginController::class, 'redirectToFacebook']);
  
   
});
$domain = env('WEBSITE_HOST');

$parsedUrl = parse_url(url()->current());

$host = $parsedUrl['host'];
if (array_key_exists('host', $parsedUrl)) {
    // if it is a path based URL
    if ($host == env('WEBSITE_HOST')) {
        $domain = $domain;
        $prefix = '{vendor}';
    }
    // if it is a subdomain / custom domain
    else {
        $prefix = '';
    }
}

Route::group(['namespace' => "front", 'prefix' => $prefix, 'middleware' => 'FrontMiddleware'], function () {
    
     //Login with Google
    Route::get('/login/google-{type}', [SocialLoginController::class, 'redirectToGoogle']);

    // //Login with facebook
    Route::get('/login/facebook-{type}', [SocialLoginController::class, 'redirectToFacebook']);
});
