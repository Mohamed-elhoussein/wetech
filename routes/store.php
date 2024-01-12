<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', [StoreController::class, 'index'])->name('index');
Route::get('/{category}/products', [StoreController::class, 'by_category'])->name('by_category');
Route::get('/products', [StoreController::class, 'listing_products'])->name('products');
Route::get('/products/{product}/confirmation', [StoreController::class, 'confirmation'])->name('confirmation');
Route::get('/search', [StoreController::class, 'search'])->name('search');
Route::post('/order/{product}', [StoreController::class, 'order'])->name('order');
Route::get('my-orders', [StoreController::class, 'my_orders'])->name('my_orders');
Route::get('/cart', [StoreController::class, 'cart'])->name('cart');
Route::post('/cart', [StoreController::class, 'add_to_cart'])->name('add_to_cart');
Route::delete('/cart', [StoreController::class, 'remove_from_cart'])->name('remove_from_cart');
Route::post('checkout', [StoreController::class, 'checkout'])->name('checkout');

Route::get('/reset', function () {
  Schema::disableForeignKeyConstraints();

  $user = auth()->user();

  // delete carts
  $user->carts()->delete();

  // delete payments
  $user->payments()->delete();

  // delete orders
  $user->orders()->delete();

  Schema::enableForeignKeyConstraints();
});

Route::middleware('guest')->get('user/login', [StoreController::class, 'showLoginForm'])->name('login');
Route::middleware('guest')->post('user/login', [StoreController::class, 'login'])->name('login');
Route::middleware('guest')->get('user/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::middleware('guest')->post('user/register', [RegisterController::class, 'store'])->name('register');
Route::middleware('auth')->post('user/logout', [StoreController::class, 'logout'])->name('logout');
