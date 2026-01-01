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
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\AdminBargainController;
use App\Http\Controllers\Customer\CustomerBargainController;

Route::get('/kategori/{category}', [ShopController::class, 'showByCategory'])->name('category.show');
Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', [AdminLoginController::class, 'create'])
        ->middleware('guest') // 'guest' agar admin yang sudah login tidak bisa ke sini
        ->name('login');

    Route::post('/login', [AdminLoginController::class, 'store'])
        ->middleware('guest');

    Route::post('/logout', [AdminLoginController::class, 'destroy'])
        ->middleware('auth') // 'auth' karena hanya user yg login yg bisa logout
        ->name('logout');

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware(['auth', 'admin']) 
        ->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class);
    Route::resource('orders', AdminOrderController::class)->only([
        'index', 'show', 'update'
    ]);
    Route::put('/orders/{order}/reject', [AdminOrderController::class, 'rejectPayment'])->name('orders.reject'); 
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/bargains', [AdminBargainController::class, 'index'])
            ->name('bargains.index');

    Route::put('/bargains/{id}', [AdminBargainController::class, 'update'])
        ->name('bargains.update');
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/produk/{product}', [ShopController::class, 'show'])->name('product.show');
Route::get('/search', [ShopController::class, 'search'])->name('product.search');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/order-history', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/payment/{order}', [CheckoutController::class, 'uploadProof'])->name('checkout.payment.upload');
    Route::post('/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])->name('orders.complete');
    Route::get('/my-orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/bargains', [CustomerBargainController::class, 'store'])
        ->name('bargains.store');
    Route::get('/my-bargains', [CustomerBargainController::class, 'index'])
        ->name('customer.bargains.index');
    Route::post('/cart/bargain', [CartController::class, 'addFromBargain'])
    ->name('cart.add.bargain');
});

Route::get('/cart', [CartController::class, 'index'])->middleware('auth')->name('cart.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
