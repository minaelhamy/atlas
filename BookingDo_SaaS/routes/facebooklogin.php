<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\FacebookLoginController;

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



//Login with facebook
Route::get('checklogin/facebook/callback-{logintype}', [FacebookLoginController::class, 'check_login']);
Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {

    Route::post('/facebook_login', [FacebookLoginController::class, 'facebookloginsettings']);


    //Login with facebook
    Route::get('login/facebook-{type}', [FacebookLoginController::class, 'redirectToFacebook']);
});

$domain = @env('WEBSITE_HOST');
$parsedUrl = parse_url(url()->current());
$host = $parsedUrl['host'];
if (array_key_exists('host', $parsedUrl)) {
    // if it is a path based URL
    if ($host == @env('WEBSITE_HOST')) {
        $domain = $domain;
        $prefix = '{vendor_slug}';
    }
    // if it is a subdomain / custom domain
    else {
        $prefix = '';
    }
}

Route::group(['namespace' => "web", 'prefix' => $prefix, 'middleware' => 'FrontMiddleware'], function () {


    //Login with facebook
    Route::get('/login/facebook-{type}', [FacebookLoginController::class, 'redirectToFacebook']);
});
