<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('language/{locale}', [LanguageController::class, 'switchLang'])->name('language.switch');
Route::post('darkmode/toggle',
    [App\Http\Controllers\DarkModeController::class, 'toggle'])->name('adminlte.darkmode.toggle');

// Test sayfası
Route::get('/test/notifications', function () {
    return view('test.notification-test');
})->name('test.notifications');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/profile', function () {
        return view('adminlte::auth.profile');
    })->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Kategori ve Ürün Yönetimi route'ları
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::post('products/{product}/increase-stock', [ProductController::class, 'increaseStock'])->name('products.increase-stock');
    Route::post('products/{product}/decrease-stock', [ProductController::class, 'decreaseStock'])->name('products.decrease-stock');

    // Satış işlemleri route'ları
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove-item');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Satış geçmişi ve raporlar
    Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SalesController::class, 'show'])->name('sales.show');
    Route::get('/sales/reports', [SalesController::class, 'reports'])->name('sales.reports');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});
require __DIR__ . '/auth.php';
