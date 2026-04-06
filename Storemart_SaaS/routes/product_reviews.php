<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\addons\ProductReviewsController;

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
    Route::post('/rattingmodal', [ProductReviewsController::class, 'rattingmodal']);
});
