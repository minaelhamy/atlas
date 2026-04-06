<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\addons\PayTabController;
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


Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        Route::middleware('VendorMiddleware')->group(function () {
            Route::group(
                ['prefix' => 'plan'],
                function () {
                    Route::post('buyplan/paytab', [PayTabController::class, 'paytabrequest']);
                }
            );
        });
    });
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

Route::group(['namespace' => "front", 'prefix' => $prefix, 'middleware' => 'frontmiddleware'], function () {
    Route::post('/paytabrequest', [PayTabController::class, 'front_paytabrequest']);
    Route::post('/paytabrequest-{booking_number}', [PayTabController::class, 'front_paytabrequest']);
});

Route::post('/checkpaymentstatus', [PayTabController::class, 'checkpaymentstatus']);
