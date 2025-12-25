<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Watch;
use App\Models\Cart;
use App\Models\User;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Exception;

class BuyerController extends Controller
{
    public function details(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'id' => 'required|integer|exists:watches,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid watch ID.');
            }

            $id = $req->id;
            $watch = Watch::where('id', $id)->first();

            if (!$watch) {
                return redirect()->back()->with('error', 'Watch not found.');
            }

            $reviews = Review::where('watch_id', $id)->orderBy('created_at', 'desc')->get();

            foreach ($reviews as $review) {
                $review->user = User::where('id', $review->user_id)->first();
            }

            $totalRating = 0;
            $count = count($reviews);

            foreach ($reviews as $review) {
                $totalRating += $review->rating;
            }

            $avgRating = ($count > 0) ? ($totalRating / $count) : 0;

            return view('buyer.watchDetail', compact('watch', 'reviews', 'avgRating'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong while fetching details.');
        }
    }

    public function cart()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to view your cart.');
            }

            $user_id = Auth::id();
            $cart = Cart::where('user_id', $user_id)->get();
            $total = 0;
            
            foreach ($cart as $item) {
                if ($item->watch) {
                    $item->subtotal = $item->watch->price * $item->quantity;
                    $total += $item->subtotal;
                }
            }
            
            return view('buyer.cartItem', compact('cart', 'total'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Could not load cart.');
        }
    }

    public function increase(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'increase' => 'required|integer|exists:carts,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid cart item.');
            }

            $id = $req->increase;
            $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$cart) {
                return redirect()->back()->with('error', 'Cart item not found or unauthorized.');
            }

            $watch = Watch::where('id', $cart->watch_id)->first();
            
            if (!$watch) {
                return redirect()->back()->with('error', 'Watch not found.');
            }

            if ($cart->quantity + 1 > $watch->stock) {
                return redirect()->back()->with('error', "Cannot add more. Only {$watch->stock} units available.");
            }

            $cart->quantity = $cart->quantity + 1;
            $cart->save();

            return redirect()->back()->with('success', 'Quantity increased.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to increase quantity.');
        }
    }

    public function decrease(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'decrease' => 'required|integer|exists:carts,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid cart item.');
            }

            $id = $req->decrease;
            $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$cart) {
                return redirect()->back()->with('error', 'Cart item not found or unauthorized.');
            }

            if ($cart->quantity > 1) {
                $cart->quantity = $cart->quantity - 1;
                $cart->save();
                return redirect()->back()->with('success', 'Quantity decreased.');
            } else {
                Cart::destroy($id);
                return redirect()->back()->with('success', 'Item removed from cart.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to decrease quantity.');
        }
    }

    public function checkout()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to checkout.');
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

    public function remove(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'remove' => 'required|integer|exists:carts,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid cart item.');
            }

            $id = $req->remove;
            $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$cart) {
                return redirect()->back()->with('error', 'Cart item not found or unauthorized.');
            }

            Cart::destroy($id);
            return redirect()->back()->with('success', 'Item removed from cart.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove item.');
        }
    }

    public function addToCart(Request $req)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please log in to add items to your cart.');
            }

            $validator = Validator::make($req->all(), [
                'id' => 'required|integer|exists:watches,id'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid watch ID.');
            }

            $id = $req->id;
            $user_id = Auth::id();

            $cart = Cart::where('watch_id', $id)->where('user_id', $user_id)->first();
            $watch = Watch::where('id', $id)->first();

            if (!$watch) {
                return redirect()->back()->with('error', 'Watch not found.');
            }

            if ($watch->stock < 1) {
                return redirect()->back()->with('error', 'Not in stock any more.');
            }

            if ($cart) {
                if ($cart->quantity + 1 > $watch->stock) {
                    return redirect()->back()->with('error', "Only {$watch->stock} units available. You already have {$cart->quantity} in your cart.");
                }
                $cart->quantity = $cart->quantity + 1;
                $cart->save();
                return redirect()->back()->with('success', 'Item quantity increased in cart.');
            } else {
                $cart = new Cart;
                $cart->user_id = $user_id;
                $cart->watch_id = $id;
                $cart->quantity = 1;
                $cart->save();
                return redirect()->back()->with('success', 'Item added to cart.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to add item to cart.');
        }
    }

    public function placeOrder(Request $req)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to place an order.');
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

            foreach ($cartItems as $item) {
                $watch = Watch::find($item->watch_id);
                if (!$watch) {
                    return redirect()->back()->with('error', 'Some items in your cart are no longer available.');
                }
                if ($watch->stock < $item->quantity) {
                    return redirect()->back()->with('error', "Not enough stock for {$watch->name}. Only {$watch->stock} available.");
                }
            }

            $total_price = 0;
            foreach ($cartItems as $item) {
                $total_price = $total_price + ($item->watch->price * $item->quantity);
            }

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


            Cart::where('user_id', $user_id)->delete();

            return redirect()->route('home')->with('success', 'Order placed successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to place order. Please try again.');
        }
    }

    public function myOrders()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')->with('error', 'Please login to view your orders.');
            }

            $user_id = Auth::id();
            $orders = Order::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();
            return view('buyer.myorders', compact('orders'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Could not load your orders.');
        }
    }

    public function home(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'sort' => 'nullable|string|in:price_asc,price_desc,name_az,newest'
            ]);

            if ($validator->fails()) {
                $sort = null;
            } else {
                $sort = $req->input('sort');
            }

            $query = Watch::query();

            if ($sort == 'price_asc') $query->orderBy('price', 'asc');
            elseif ($sort == 'price_desc') $query->orderBy('price', 'desc');
            elseif ($sort == 'name_az') $query->orderBy('name', 'asc');
            else $query->orderBy('created_at', 'desc');

            $allWatches = $query->get();
            return view('buyer.home', compact('allWatches', 'sort'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load watches.');
        }
    }

    public function featured(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'sort' => 'nullable|string|in:price_asc,price_desc,name_az,newest'
            ]);

            if ($validator->fails()) {
                $sort = null;
            } else {
                $sort = $req->input('sort');
            }

            $query = Watch::where('featured', 'yes');

            if ($sort == 'price_asc') $query->orderBy('price', 'asc');
            elseif ($sort == 'price_desc') $query->orderBy('price', 'desc');
            elseif ($sort == 'name_az') $query->orderBy('name', 'asc');
            else $query->orderBy('created_at', 'desc');

            $allWatches = $query->get();
            return view('buyer.featured', compact('allWatches', 'sort'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load featured watches.');
        }
    }

    public function search(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'query' => 'required|string|min:1|max:100',
                'sort' => 'nullable|string|in:price_asc,price_desc,name_az,newest'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid search query.');
            }

            $queryStr = strip_tags(trim($req->input('query')));
            $sort = $req->input('sort');
            
            $query = Watch::where('name', 'LIKE', "%{$queryStr}%");

            if ($sort == 'price_asc') $query->orderBy('price', 'asc');
            elseif ($sort == 'price_desc') $query->orderBy('price', 'desc');
            elseif ($sort == 'name_az') $query->orderBy('name', 'asc');
            else $query->orderBy('created_at', 'desc');

            $allWatches = $query->get();
            return view('buyer.searchedResult', compact('allWatches', 'queryStr', 'sort'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Search failed. Please try again.');
        }
    }

    public function aboutUs()
    {
        return view('buyer.aboutUs');
    }
}