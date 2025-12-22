<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\LiveDropController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\JwtAuth;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([JwtAuth::class])->group(function () {
        Route::put('/credentials', [AuthController::class, 'updateCredentials']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Example admin-only endpoint (for future admin APIs)
        Route::get('/admin-check', function () {
            return response()->json(['ok' => true]);
        })->middleware([AdminOnly::class]);
    });
});

// Public product catalog (used by front-end)
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

// Public content/data
Route::get('/live-drop', [LiveDropController::class, 'show']);
Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/contact-messages', [ContactMessageController::class, 'store']);

// Authenticated customer APIs
Route::middleware([JwtAuth::class])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);

    // Addresses
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{uuid}', [AddressController::class, 'update']);
    Route::delete('/addresses/{uuid}', [AddressController::class, 'destroy']);
    Route::post('/addresses/{uuid}/primary', [AddressController::class, 'setPrimary']);

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::delete('/wishlist/{uuid}', [WishlistController::class, 'destroy']);
    Route::delete('/wishlist', [WishlistController::class, 'clear']);

    // Reviews
    Route::get('/reviews/mine', [ReviewController::class, 'mine']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::delete('/reviews/{uuid}', [ReviewController::class, 'destroy']);

    // Cart
    Route::get('/cart', [CartController::class, 'show']);
    Route::patch('/cart/shipping', [CartController::class, 'setShipping']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::patch('/cart/items/{uuid}', [CartController::class, 'updateItem']);
    Route::delete('/cart/items/{uuid}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clear']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{uuid}', [OrderController::class, 'show']);
    Route::patch('/orders/{uuid}/received', [OrderController::class, 'markReceived']);
    Route::post('/orders/checkout', [OrderController::class, 'checkout']);
});

// Admin dashboard APIs
Route::prefix('admin')->middleware([JwtAuth::class, AdminOnly::class])->group(function () {
    Route::get('/products', [ProductController::class, 'adminIndex']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{uuid}', [ProductController::class, 'update']);
    Route::delete('/products/{uuid}', [ProductController::class, 'destroy']);

    Route::get('/orders', [OrderController::class, 'adminIndex']);
    Route::patch('/orders/{uuid}/ship', [OrderController::class, 'adminMarkShipped']);
    Route::patch('/orders/{uuid}/deliver', [OrderController::class, 'adminMarkDelivered']);

    Route::put('/live-drop', [LiveDropController::class, 'update']);
    Route::get('/contact-messages', [ContactMessageController::class, 'adminIndex']);
    Route::delete('/contact-messages/{uuid}', [ContactMessageController::class, 'destroy']);
});
