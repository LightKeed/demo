<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\MediaController;

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

Route::controller(MediaController::class)->prefix('media')->name('Media.')->group(function () {
    Route::get('/images/{path?}', 'image')->where('path', '.*')->name('Image');
    Route::get('/{path}', 'show')->where('path', '.*')->name('Show');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('HomeIndex');
    Route::get('/search', 'search')->name('SearchIndex')->middleware('throttle:30,1');
    Route::get('/{path}', 'show')->name('HomePath');
    Route::get('/{path}/{subPath}', 'show')->name('HomeSubPath');
    Route::get('/{path}/{subPath}/{subChild}', 'show')->name('HomeSubChild');
    Route::get('/{path}/{subPath}/{subChild}/{page}', 'show')->name('HomePage');
});

Route::group(['domain' => 'news.' . Config::get('app.url')], function () {
    Route::get('/{path}')->name('NewsIndex');
});

Route::group(['domain' => 'events.' . Config::get('app.url')], function () {
    Route::get('/{path}')->name('EventIndex');
});

