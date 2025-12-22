<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\JwtAuth;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([JwtAuth::class])->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Example admin-only endpoint (for future admin APIs)
        Route::get('/admin-check', function () {
            return response()->json(['ok' => true]);
        })->middleware([AdminOnly::class]);
    });
});
