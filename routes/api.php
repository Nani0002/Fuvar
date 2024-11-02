<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiJobController;
use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [ApiAuthController::class, "login"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [ApiAuthController::class, "logout"]);
    Route::resource('/jobs', ApiJobController::class);
});

Route::middleware('auth')->group(function () {
    Route::post('/dismiss_message/{id}', [JobController::class, "dismiss"]);
    Route::post('/read_messages', [JobController::class, "read"]);
});
