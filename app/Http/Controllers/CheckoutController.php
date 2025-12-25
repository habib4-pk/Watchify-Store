<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Watch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * CheckoutController
 * Handles checkout process and order placement
 */
class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('account.login')->with('error', 'Please login to checkout.');
            }

            $userId = Auth::id();
            $cart = Cart::where('user_id', $userId)->get();

            if ($cart->count() == 0) {
                return redirect()->route('cartItems')->with('error', 'Your cart is empty.');
            }

            $total = 0;
            foreach ($cart as $item) {
                $watch = Watch::find($item->watch_id);
                if (!$watch) {
                    return redirect()->route('cartItems')->with('error', 'Some items in your cart are no longer available.');
                }
                $total = $total + ($watch->price * $item->quantity);
            }

            return view('buyer.checkout', compact('cart', 'total'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to process checkout.');
        }
    }

    /**
     * Process and place the order
     */
    public function placeOrder(Request $req)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('account.login')->with('error', 'Please login to place an order.');
            }

            $validator = Validator::make($req->all(), [
                'customer_name' => 'required|string|min:3|max:100|regex:/^[a-zA-Z\s]+$/',
                'street_address' => 'required|string|min:10|max:255',
                'city' => 'required|string|min:2|max:100|regex:/^[a-zA-Z\s]+$/',
                'postal_code' => 'required|string|min:4|max:10|regex:/^[a-zA-Z0-9\s-]+$/',
                'phone_number' => 'required|string|min:10|max:15|regex:/^[0-9+\-\s()]+$/'
            ], [
                'customer_name.required' => 'Customer name is required.',
                'customer_name.regex' => 'Customer name can only contain letters and spaces.',
                'customer_name.min' => 'Customer name must be at least 3 characters.',
                'street_address.required' => 'Street address is required.',
                'street_address.min' => 'Street address must be at least 10 characters.',
                'city.required' => 'City is required.',
                'city.regex' => 'City name can only contain letters and spaces.',
                'postal_code.required' => 'Postal code is required.',
                'postal_code.regex' => 'Invalid postal code format.',
                'phone_number.required' => 'Phone number is required.',
                'phone_number.regex' => 'Invalid phone number format.',
                'phone_number.min' => 'Phone number must be at least 10 digits.'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fix the validation errors.');
            }

            $user_id = Auth::id();
            $cartItems = Cart::where('user_id', $user_id)->get();

            if ($cartItems->count() == 0) {
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            // Validate stock availability
            foreach ($cartItems as $item) {
                $watch = Watch::find($item->watch_id);
                if (!$watch) {
                    return redirect()->back()->with('error', 'Some items in your cart are no longer available.');
                }
                if ($watch->stock < $item->quantity) {
                    return redirect()->back()->with('error', "Not enough stock for {$watch->name}. Only {$watch->stock} available.");
                }
            }

            // Calculate total
            $total_price = 0;
            foreach ($cartItems as $item) {
                $total_price = $total_price + ($item->watch->price * $item->quantity);
            }

            // Create order
            $order = new Order;
            $order->user_id = $user_id;
            $order->customer_name = strip_tags(trim($req->customer_name));
            $order->street_address = strip_tags(trim($req->street_address));
            $order->city = strip_tags(trim($req->city));
            $order->postal_code = strip_tags(trim($req->postal_code));
            $order->phone_number = strip_tags(trim($req->phone_number));
            $order->status = "Pending";
            $order->total_amount = $total_price;
            $order->save();

            // Create order items and update stock
            foreach ($cartItems as $item) {
                $watch = Watch::where('id', $item->watch_id)->first();
                $watch->stock = $watch->stock - $item->quantity;
                $watch->save();

                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                $orderItem->watch_id = $item->watch_id;
                $orderItem->quantity = $item->quantity;
                $orderItem->price = $watch->price;
                $orderItem->save();
            }

            // Clear cart
            Cart::where('user_id', $user_id)->delete();

            return redirect()->route('myOrders')->with('success', 'Order placed successfully! Thank you for your purchase.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    /**
     * Display user's orders
     */
    public function myOrders()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('account.login')->with('error', 'Please login to view your orders.');
            }

            $user_id = Auth::id();
            $orders = Order::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
            return view('buyer.orders', compact('orders'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Could not load your orders.');
        }
    }
}
