<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/body.html');
});

Route::view('/body.html', 'pages.body');
Route::view('/login.html', 'pages.login');
Route::view('/register.html', 'pages.register');

Route::view('/cart.html', 'pages.cart');
Route::view('/checkout.html', 'pages.checkout');
Route::view('/success.html', 'pages.success');
Route::view('/product-detail.html', 'pages.product-detail');

Route::view('/profile.html', 'pages.profile');
Route::view('/edit-profile.html', 'pages.edit-profile');
Route::view('/orders.html', 'pages.orders');
Route::view('/wishlist.html', 'pages.wishlist');

Route::view('/admin.html', 'pages.admin');
Route::view('/profile-admin.html', 'pages.profile-admin');
