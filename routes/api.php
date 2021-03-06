<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DealController;
use Illuminate\Support\Facades\Route;

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
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::get('redirect', [AuthController::class, 'redirectToProvider'])->name('redirect_to_provider');
Route::get('callback', [AuthController::class, 'handleProviderCallback'])->name('provider_callback');

Route::middleware('auth:api')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('data', [AuthController::class, 'user'])->name('user_data');


        Route::resource('deals', DealController::class)->except('create','edit');
    });
});
