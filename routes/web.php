<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


Route::get('/kategori/{category}', [ShopController::class, 'showByCategory'])->name('category.show');
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminLoginController::class, 'create'])
        ->middleware('guest') // 'guest' agar admin yg sudah login tdk bisa ke sini
        ->name('login');

    Route::post('/login', [AdminLoginController::class, 'store'])
        ->middleware('guest');

    Route::post('/logout', [AdminLoginController::class, 'destroy'])
        ->middleware('auth') // 'auth' karena hanya user yg login yg bisa logout
        ->name('logout');

    // Rute untuk dashboard admin (dilindungi middleware 'auth' DAN 'admin')
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware(['auth', 'admin']) 
        ->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::resource('orders', AdminOrderController::class)->only([
        'index', 'show', 'update'
    ]);
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/produk/{product}', [ShopController::class, 'show'])->name('product.show');
Route::get('/search', [ShopController::class, 'search'])->name('product.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/order-history', [OrderController::class, 'index'])->name('orders.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/cart', [CartController::class, 'index'])->middleware('auth')->name('cart.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

require __DIR__.'/auth.php';
