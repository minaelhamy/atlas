<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\ProductQuestionAnswerController;

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

//product_question_answer
Route::group(['namespace' => 'admin', 'prefix' => 'admin'], function () {
    Route::group(['middleware' => 'AuthMiddleware'], function () {
        Route::middleware('VendorMiddleware')->group(function () {
            Route::get('/question_answer', [ProductQuestionAnswerController::class, 'question_answer']);
            Route::post('/product_answer', [ProductQuestionAnswerController::class, 'product_answer']);
            Route::get('/question_answer/delete-{id}', [ProductQuestionAnswerController::class, 'delete']);
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
    Route::post('/product_question_answer', [ProductQuestionAnswerController::class, 'product_question_answer']);
});
