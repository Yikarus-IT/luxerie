<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\MediaAssetController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop');
Route::get('/shop/{product:slug}', [StorefrontController::class, 'show'])->name('products.show');
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/items/{cartItem}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/pedido/{order:checkout_token}', [CheckoutController::class, 'show'])->name('orders.show');
Route::post('/pedido/{order:checkout_token}/pagar', [MercadoPagoController::class, 'pay'])->name('payments.pay');
Route::get('/pedido/{order:checkout_token}/resultado', [MercadoPagoController::class, 'result'])->name('payments.result');
Route::post('/mercado-pago/webhook', [MercadoPagoController::class, 'webhook'])->name('payments.webhook');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/admin/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('products', ProductController::class)->except('show')->middleware('manage:products');
    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update'])->middleware('manage:orders');
    Route::resource('customers', CustomerController::class)->middleware('manage:orders');
    Route::resource('media', MediaAssetController::class)->only(['index', 'store', 'update', 'destroy'])->middleware('manage:media');
    Route::get('homepage', [HomepageController::class, 'edit'])->name('homepage.edit')->middleware('manage:content');
    Route::put('homepage', [HomepageController::class, 'update'])->name('homepage.update')->middleware('manage:content');
    Route::get('homepage/preview', [StorefrontController::class, 'home'])->name('homepage.preview');
    Route::post('homepage/revisions/{revision}/restore', [HomepageController::class, 'restore'])->name('homepage.revisions.restore')->middleware('manage:content');
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit')->middleware('manage:content');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('manage:content');
    Route::resource('testimonials', TestimonialController::class)->only(['index', 'store', 'update', 'destroy'])->middleware('manage:content');
});
