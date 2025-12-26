<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HeroBannerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WatchController;
use App\Http\Controllers\WatchImageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Production-ready route structure with professional URLs:
|
| - /admin/*     - Admin panel routes (protected)
| - /shop/*      - Public shop browsing
| - /cart/*      - Shopping cart management
| - /checkout/*  - Checkout process
| - /orders/*    - Order history
| - /account/*   - User account management
| - /about       - Static pages
|
*/

// ============================================================================
// ADMIN ROUTES - /admin/*
// ============================================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [WatchController::class, 'index'])->name('index');
        Route::get('/create', [WatchController::class, 'add'])->name('create');
        Route::post('/store', [WatchController::class, 'store'])->name('store');
        Route::post('/edit', [WatchController::class, 'edit'])->name('edit');
        Route::post('/update', [WatchController::class, 'update'])->name('update');
        Route::post('/delete', [WatchController::class, 'destroy'])->name('destroy');
    });
    
    // Product Image Management
    Route::prefix('product-images')->name('images.')->group(function () {
        Route::post('/store', [WatchImageController::class, 'store'])->name('store');
        Route::post('/delete', [WatchImageController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [WatchImageController::class, 'updateOrder'])->name('reorder');
        Route::post('/set-primary', [WatchImageController::class, 'setPrimary'])->name('setPrimary');
    });
    
    // Order Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/details', [OrderController::class, 'show'])->name('show');
        Route::post('/update-status', [OrderController::class, 'update'])->name('update');
    });
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'showAllUsers'])->name('index');
        Route::post('/delete', [AdminController::class, 'destroy'])->name('destroy');
    });
    
    // Hero Banner Management
    Route::prefix('banners')->name('banners.')->group(function () {
        Route::get('/', [HeroBannerController::class, 'index'])->name('index');
        Route::post('/store', [HeroBannerController::class, 'store'])->name('store');
        Route::post('/update', [HeroBannerController::class, 'update'])->name('update');
        Route::post('/delete', [HeroBannerController::class, 'destroy'])->name('destroy');
        Route::post('/toggle', [HeroBannerController::class, 'toggleActive'])->name('toggle');
        Route::post('/reorder', [HeroBannerController::class, 'updateOrder'])->name('reorder');
    });
});

// Legacy admin route aliases for backward compatibility
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/watches', [WatchController::class, 'index'])->name('adminDashboard');
    Route::get('/admin/watches/add', [WatchController::class, 'add'])->name('addWatch');
    Route::post('/admin/watches/store', [WatchController::class, 'store'])->name('storeWatch');
    Route::post('/admin/watches/edit', [WatchController::class, 'edit'])->name('editWatch');
    Route::post('/admin/watches/update', [WatchController::class, 'update'])->name('updateWatch');
    Route::post('/admin/watches/delete', [WatchController::class, 'destroy'])->name('deleteWatch');
    Route::get('/admin/orders', [OrderController::class, 'index'])->name('allOrders');
    Route::post('/admin/orders/details', [OrderController::class, 'show'])->name('orderDetails');
    Route::post('/admin/orders/update-status', [OrderController::class, 'update'])->name('updateOrderStatus');
    Route::get('/admin/users', [AdminController::class, 'showAllUsers'])->name('allUsers');
    Route::post('/admin/users/delete', [AdminController::class, 'destroy'])->name('deleteUser');
});

// ============================================================================
// PUBLIC SHOP ROUTES - /shop/*
// ============================================================================
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'home'])->name('index');
    Route::get('/featured', [ShopController::class, 'featured'])->name('featured');
    Route::get('/product', [ShopController::class, 'details'])->name('product');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
});

// Legacy home route aliases for backward compatibility
Route::get('/home', fn() => redirect()->route('shop.index'))->name('home');
Route::get('/home/featured', fn() => redirect()->route('shop.featured'))->name('featured');
Route::get('/home/watches', fn() => redirect()->route('shop.product'))->name('watchDetails');
Route::get('/home/search', fn() => redirect()->route('shop.search'))->name('search');

// ============================================================================
// STATIC PAGES
// ============================================================================
Route::get('/about', [ShopController::class, 'aboutUs'])->name('about');
Route::get('/home/about-us', fn() => redirect()->route('about'))->name('aboutUs');

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================
Route::prefix('account')->name('account.')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Legacy auth route aliases for backward compatibility
Route::get('/login', fn() => redirect()->route('account.login'))->name('loginForm');
Route::get('/register', fn() => redirect()->route('account.register'))->name('registerForm');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// ============================================================================
// PROTECTED BUYER ROUTES (Require Authentication)
// ============================================================================
Route::middleware(['auth'])->group(function () {
    
    // Cart Management - /cart/*
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'show'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/increase', [CartController::class, 'increase'])->name('increase');
        Route::post('/decrease', [CartController::class, 'decrease'])->name('decrease');
        Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    });
    
    // Legacy cart route aliases
    Route::get('/home/cart', fn() => redirect()->route('cart.index'))->name('cartDetails');
    Route::post('/home/cart', [CartController::class, 'add'])->name('addToCart');
    Route::get('/home/cart-details', fn() => redirect()->route('cart.index'))->name('cartItems');
    Route::post('/home/cart-details/inc', [CartController::class, 'increase'])->name('increase');
    Route::post('/home/cart-details/dec', [CartController::class, 'decrease'])->name('decrease');
    Route::post('/home/cart-details/rem', [CartController::class, 'remove'])->name('remove');
    
    // Checkout - /checkout/*
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place');
    });
    
    // Legacy checkout route aliases
    Route::get('/home/checkout', fn() => redirect()->route('checkout.index'))->name('checkout');
    Route::post('/home/checkout/placeOrder', [CheckoutController::class, 'placeOrder'])->name('placeOrder');
    
    // Orders - /orders/*
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [CheckoutController::class, 'myOrders'])->name('index');
    });
    
    // Legacy orders route alias
    Route::get('/home/my-orders', fn() => redirect()->route('orders.index'))->name('myOrders');
    
    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::post('/store', [ReviewController::class, 'store'])->name('store');
        Route::post('/delete', [ReviewController::class, 'delete'])->name('delete');
    });
    
    // Legacy review route aliases
    Route::post('/review/store', [ReviewController::class, 'store'])->name('reviewStore');
    Route::post('/review/delete', [ReviewController::class, 'delete'])->name('reviewDelete');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});