<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * BuyerController (DEPRECATED)
 * 
 * This controller has been deprecated and split into focused controllers:
 * 
 * - CartController: Cart operations (add, remove, increase, decrease)
 * - CheckoutController: Checkout and order placement
 * - ShopController: Home, featured, search, product details, about
 * 
 * This file is kept for reference only. All functionality has been migrated
 * to the new controllers. You can safely delete this file after verifying
 * that all routes work correctly with the new controllers.
 * 
 * @deprecated Use CartController, CheckoutController, or ShopController instead
 * @see \App\Http\Controllers\CartController
 * @see \App\Http\Controllers\CheckoutController
 * @see \App\Http\Controllers\ShopController
 */
class BuyerController extends Controller
{
    // All methods have been migrated to:
    // - CartController: cart, addToCart, increase, decrease, remove
    // - CheckoutController: checkout, placeOrder, myOrders
    // - ShopController: home, featured, search, details, aboutUs
}