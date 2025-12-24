<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WatchController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Router;

//For Admin

Route::middleware(['auth', 'admin'])->group(function () {

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);

    // Watches Routes
    Route::get('admin/watches', [WatchController::class, 'index'])->name('adminDashboard');
    Route::get('admin/watches/add', [WatchController::class, 'add'])->name('addWatch');
    Route::post('admin/watches/store', [WatchController::class, 'store'])->name('storeWatch');
    Route::post('admin/watches/delete', [WatchController::class, 'destroy'])->name('deleteWatch');
    Route::post('admin/watches/update', [WatchController::class, 'update'])->name('updateWatch');
    Route::post('admin/watches/edit', [WatchController::class, 'edit'])->name('editWatch');

    // Orders Routes
    // List all orders
    Route::get('admin/orders', [OrderController::class, 'index'])->name('allOrders');

    // View details (POST to hide ID)
    Route::post('admin/orders/details', [OrderController::class, 'show'])->name('orderDetails');

    // Update status
    Route::post('admin/orders/update-status', [OrderController::class, 'update'])->name('updateOrderStatus');

    // Users Routes
    Route::get('/admin/users', [AdminController::class, 'showAllUsers'])->name('allUsers');
    Route::post('/admin/users/delete', [AdminController::class, 'destroy'])->name('deleteUser');
});


//For Buyer

Route::get("/home", [BuyerController::class, 'home'])->name('home');
Route::get("/home/featured", [BuyerController::class, 'featured'])->name('featured');
Route::get("/home/watches", [BuyerController::class, 'details'])->name('watchDetails');
Route::get("/home/search", [BuyerController::class, 'search'])->name('search');
Route::get("/home/cart", [BuyerController::class, 'addToCart'])->name('cartDetails');

Route::post("/home/cart", [BuyerController::class, 'addToCart'])->name('addToCart');
Route::get('/home/cart-details', [BuyerController::class, 'cart'])->name('cartItems');
Route::post('/home/cart-details/inc', [BuyerController::class, 'increase'])->name('increase');
Route::post('/home/cart-details/dec', [BuyerController::class, 'decrease'])->name('decrease');
Route::post('/home/cart-details/rem', [BuyerController::class, 'remove'])->name('remove');
Route::get('/home/checkout', [BuyerController::class, 'checkout'])->name('checkout');
Route::post('/home/checkout/placeOrder', [BuyerController::class, 'placeOrder'])->name('placeOrder');

Route::get("/home/about-us", [BuyerController::class, 'aboutUs'])->name('aboutUs');


Route::get("/home/my-orders", [BuyerController::class, 'myOrders'])->name('myOrders');




//Authentication

Route::get('/login', [AuthController::class, 'loginForm'])->name('loginForm');
Route::get('/register', [AuthController::class, 'registerForm'])->name('registerForm');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
