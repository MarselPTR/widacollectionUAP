<?php

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\JwtAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/body');
});

Route::view('/body', 'pages.body');
Route::view('/login', 'pages.login');
Route::view('/register', 'pages.register');

Route::view('/cart', 'pages.cart');
Route::view('/checkout', 'pages.checkout');
Route::view('/success', 'pages.success');
Route::view('/product-detail', 'pages.product-detail');

// Dynamic detail URL using identifier (slug) for custom products
Route::get('/products/{slug}', function (string $slug) {
    $product = DB::table('custom_products')->select('public_id')->where('slug', $slug)->first();
    abort_if(!$product, 404);

    return redirect('/product-detail?id=' . urlencode($product->public_id));
});

Route::view('/profile', 'pages.profile');
Route::view('/edit-profile', 'pages.edit-profile');
Route::view('/orders', 'pages.orders');
Route::view('/wishlist', 'pages.wishlist');

Route::middleware([JwtAuth::class, AdminOnly::class])->group(function () {
    Route::view('/admin', 'pages.admin');
    Route::view('/profile-admin', 'pages.profile-admin');
});
