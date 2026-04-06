<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\addons\QuestionAnswerController;

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
            Route::get('/question_answer', [QuestionAnswerController::class, 'question_answer']);
            Route::post('/product_answer', [QuestionAnswerController::class, 'product_answer']);
            Route::get('/question_answer/delete-{id}', [QuestionAnswerController::class, 'delete']);
            Route::get('/question_answer/bulk_delete', [QuestionAnswerController::class, 'bulk_delete']);
            Route::get('/service_question_answer', [QuestionAnswerController::class, 'services_question_answer']);
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
Route::group(['namespace' => "front", 'prefix' => $prefix, 'middleware' => 'FrontMiddleware'], function () {
    Route::post('/product_question_answer', [QuestionAnswerController::class, 'product_question_answer']);
});
