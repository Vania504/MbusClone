<?php

use App\Http\Controllers\BusController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PasswordResetRequestController;
use App\Http\Controllers\RequestOrderController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StatusController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('resetRequest', [PasswordResetRequestController::class, 'sendPasswordResetEmail']);
Route::post('changePassword', [ChangePasswordController::class, 'passwordResetProcess']);

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::post('refreshToken', 'refreshToken');
});
Route::controller(MessageController::class)->group(function () {
    Route::get('message', 'index');
    Route::get('messages/{status}', 'list');
    Route::get('message/{id}', 'show');
    Route::post('message', 'store');
    Route::post('message/{id}', 'update');
});
Route::controller(RequestOrderController::class)->group(function () {
    Route::get('requestOrder', 'index');
    Route::get('requestOrders/{status}', 'list');
    Route::get('requestOrder/{id}', 'show');
    Route::post('requestOrder', 'store');
    Route::post('requestOrder/{id}', 'update');
});
Route::controller(StatusController::class)->group(function () {
    Route::get('statuses/{type}', 'listStatus');
    Route::get('status/{id}', 'show');
    Route::post('status', 'store');
    Route::post('status/{id}', 'update');
});
Route::controller(SettingController::class)->group(function () {
    Route::get('settings', 'index');
    Route::get('settings/{type}', 'list');
    Route::get('setting/{id}', 'show');
    Route::post('settings', 'store');
    Route::post('settings/{id}', 'update');
    Route::delete('settings/{id}', 'destroy');
});

Route::controller(BusController::class)->group(function () {
    Route::get('buses', 'index');
    Route::get('bus', 'list');
    Route::get('bus/{id}', 'show');
    Route::post('bus', 'store');
    Route::post('bus/{id}', 'update');
});

Route::controller(ImageController::class)->group(function () {
    Route::get('image/{type}', 'list');
    Route::post('image', 'store');
    Route::post('image/{id}', 'update');
    Route::delete('image/{id}', 'destroy');
});

Route::controller(RouteController::class)->group(function () {
    Route::get('routes', 'index');
    Route::get('carousel/{count}', 'carousel');
    Route::get('route', 'list');
    Route::get('route/{id}', 'show');
    Route::post('route', 'store');
    Route::post('route/{id}', 'update');
    Route::delete('route/{id}', 'destroy');
});

Route::controller(ContentController::class)->group(function () {
    Route::get('contents', 'index');
    Route::get('content', 'list');
    Route::get('content/{id}', 'show');
    Route::post('content', 'store');
    Route::post('content/{id}', 'update');
    Route::delete('content/{id}', 'destroy');
});

Route::controller(SearchController::class)->group(function () {
    Route::get('start_city', 'startCity');
    Route::get('next_city/{first_city}', 'nextCity');
    Route::get('search/{first_city}/{last_city}', 'search');
});



