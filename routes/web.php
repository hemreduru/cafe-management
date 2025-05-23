<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Kategori yönetimi route'ları
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    Route::post('products/{product}/increase-stock', [ProductController::class, 'increaseStock'])->name('products.increase-stock');
    Route::post('products/{product}/decrease-stock', [ProductController::class, 'decreaseStock'])->name('products.decrease-stock');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/items/{cartItem}', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove-item');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Sales routes
    Route::get('/sales/statistics', [App\Http\Controllers\SalesStatisticsController::class, 'index'])->name('sales.statistics');
    Route::get('/sales/statistics/datatable', [App\Http\Controllers\SalesStatisticsController::class, 'datatable'])->name('sales.statistics.datatable');
    Route::get('/sales/statistics/details/{productId}', [App\Http\Controllers\SalesStatisticsController::class, 'details'])->name('sales.statistics.details');
    Route::get('/sales', [App\Http\Controllers\SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [App\Http\Controllers\SaleController::class, 'show'])->name('sales.show');
    Route::delete('/sales/{sale}', [App\Http\Controllers\SaleController::class, 'destroy'])->name('sales.destroy');
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
