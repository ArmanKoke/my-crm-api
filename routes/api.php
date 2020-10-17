<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('profile', [UserProfileController::class, 'show'])->name('profile');
    });

    Route::fallback(function () { //works only on get mb delete and use middleware instead
        return response()->json(['message' => 'No such route'], 404);
    });
});
