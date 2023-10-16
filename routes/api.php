<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Web\ArticleController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/auth/logout', [AuthController::class, 'silentLogout'])
    ->name('AuthLogoutPost');

Route::get('/articles/search', [ArticleController::class, 'searchTake'])
    ->middleware('throttle:60,1')
    ->name('Api.ArticleSearch');

Route::group(['domain' => 'news.' . Config::get('app.url')], function () {
    Route::get('/news/search')->name('Api.NewsSearch');
});

Route::get('/events/search', [EventController::class, 'searchTake'])
    ->middleware('throttle:60,1')
    ->name('Api.EventSearch');
