<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WatchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| JSON API endpoints for AJAX requests - SPA-like experience
|
*/

// ============================================================================
// CART API - Authenticated users only
// ============================================================================
Route::middleware(['auth', 'web'])->prefix('cart')->name('api.cart.')->group(function () {
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/increase', [CartController::class, 'increase'])->name('increase');
    Route::post('/decrease', [CartController::class, 'decrease'])->name('decrease');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::get('/count', [CartController::class, 'getCount'])->name('count');
});

// ============================================================================
// REVIEWS API - Authenticated users only
// ============================================================================
Route::middleware(['auth', 'web'])->prefix('reviews')->name('api.reviews.')->group(function () {
    Route::post('/store', [ReviewController::class, 'store'])->name('store');
    Route::post('/delete', [ReviewController::class, 'delete'])->name('delete');
});

// ============================================================================
// AUTH API
// ============================================================================
Route::prefix('auth')->name('api.auth.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth', 'web'])->name('logout');
});

// ============================================================================
// CHECKOUT API - Authenticated users only
// ============================================================================
Route::middleware(['auth', 'web'])->prefix('checkout')->name('api.checkout.')->group(function () {
    Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place');
});

// ============================================================================
// SEARCH API - Public
// ============================================================================
Route::get('/search', function (\Illuminate\Http\Request $request) {
    $query = trim($request->input('query', ''));
    $sort = $request->input('sort', 'newest');
    
    $watches = \App\Models\Watch::query();
    
    if ($query) {
        $watches->where('name', 'LIKE', "%{$query}%");
    }
    
    if ($sort == 'price_asc') $watches->orderBy('price', 'asc');
    elseif ($sort == 'price_desc') $watches->orderBy('price', 'desc');
    elseif ($sort == 'name_az') $watches->orderBy('name', 'asc');
    else $watches->orderBy('created_at', 'desc');
    
    return response()->json([
        'success' => true,
        'watches' => $watches->get()
    ]);
})->name('api.search');

// ============================================================================
// ADMIN API - Admin users only
// ============================================================================
Route::middleware(['auth', 'admin', 'web'])->prefix('admin')->name('api.admin.')->group(function () {
    // Products
    Route::post('/products/store', [WatchController::class, 'store'])->name('products.store');
    Route::post('/products/update', [WatchController::class, 'update'])->name('products.update');
    Route::post('/products/delete', [WatchController::class, 'destroy'])->name('products.destroy');
    
    // Orders
    Route::post('/orders/update-status', [OrderController::class, 'update'])->name('orders.update');
    
    // Users
    Route::post('/users/delete', [AdminController::class, 'destroy'])->name('users.destroy');
});
