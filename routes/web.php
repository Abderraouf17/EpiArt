<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ShippingController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [WelcomeController::class, 'index']);

// Shop Routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/category/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/product/{product:slug}', [ShopController::class, 'show'])->name('shop.product');
Route::get('/shop/search', [ShopController::class, 'search'])->name('shop.search');

// Cart Routes (some require auth)
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [CartController::class, 'processCheckout'])->name('checkout.process');

// User Dashboard (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/orders/{order}/cancel', [DashboardController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/wishlist', [DashboardController::class, 'wishlist'])->name('wishlist.index');
    Route::post('/wishlist/add', [DashboardController::class, 'addToWishlist'])->name('wishlist.add');
    Route::post('/wishlist/remove', [DashboardController::class, 'removeFromWishlist'])->name('wishlist.remove');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes (requires admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users Management
    Route::resource('/users', UserController::class);
    
    // Products Management
    Route::resource('/products', ProductController::class);
    Route::delete('/product-images/{image}', [ProductController::class, 'deleteImage'])->name('product-images.destroy');
    
    // Categories Management
    Route::resource('/categories', CategoryController::class);
    
    // Users Management
    Route::get('/users/{user}/orders', [UserController::class, 'orders'])->name('users.orders');
    
    // Orders Management
    Route::resource('/orders', OrderController::class, ['except' => ['create', 'store', 'edit', 'update']]);
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Shipping Rules Management
    Route::resource('/shipping', ShippingController::class);
});

require __DIR__.'/auth.php';

