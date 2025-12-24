<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Watch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderPlacedMail;
use Illuminate\Support\Facades\Mail;

class BuyerController extends Controller
{
    public function home(Request $req)
    {
        $sort = $req->input('sort');
        $allWatches = Watch::query();

        if ($sort == 'price_asc') {
            $allWatches->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $allWatches->orderBy('price', 'desc');
        } elseif ($sort == 'name_az') {
            $allWatches->orderBy('name', 'asc');
        } elseif ($sort == 'newest') {
            $allWatches->orderBy('created_at', 'desc');
        } else {
            $allWatches->orderBy('created_at', 'desc');
        }

        $allWatches = $allWatches->get();
        return view('buyer.home', compact('allWatches', 'sort'));
    }

    public function featured(Request $req)
    {
        $sort = $req->input('sort');
        $allWatches = Watch::where('featured', 'yes');

        if ($sort == 'price_asc') {
            $allWatches->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $allWatches->orderBy('price', 'desc');
        } elseif ($sort == 'name_az') {
            $allWatches->orderBy('name', 'asc');
        } elseif ($sort == 'newest') {
            $allWatches->orderBy('created_at', 'desc');
        } else {
            $allWatches->orderBy('created_at', 'desc');
        }

        $allWatches = $allWatches->get();
        return view('buyer.featured', compact('allWatches', 'sort'));
    }

    public function search(Request $req)
    {
        $query = $req->input('query');
        $sort = $req->input('sort');
        $allWatches = Watch::where('name', 'LIKE', "%{$query}%");

        if ($sort == 'price_asc') {
            $allWatches->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $allWatches->orderBy('price', 'desc');
        } elseif ($sort == 'name_az') {
            $allWatches->orderBy('name', 'asc');
        } elseif ($sort == 'newest') {
            $allWatches->orderBy('created_at', 'desc');
        } else {
            $allWatches->orderBy('created_at', 'desc');
        }

        $allWatches = $allWatches->get();
        return view('buyer.searchedResult', compact('allWatches', 'query', 'sort'));
    }

    public function details(Request $req)
    {
        $id = $req->id;
        $watch = Watch::where('id', $id)->first();
        return view('buyer.watchDetail', compact('watch'));
    }

    public function cart()
    {
        if (Auth::user()) {
            $user_id = Auth::id();
            $cart = Cart::where('user_id', $user_id)->get();
            $total = 0;
            foreach ($cart as $item) {
                $item->subtotal = $item->watch->price * $item->quantity;
                $total += $item->subtotal;
            }

            return view('buyer.cartItem', compact('cart', 'total'));
        } else {
            return redirect()->route('login');
        }
    }

    public function increase(Request $req)
    {
        $id = $req->increase;
        $cart = Cart::where('id', $id)->first();

        if (!$cart) {
            return redirect()->back()->with('error', 'No cart found with that ID.');
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

        return redirect()->back();
    }

    public function decrease(Request $req)
    {
        $id = $req->decrease;
        $cart = Cart::where('id', $id)->first();

        if (!$cart) {
            return redirect()->back()->with('error', 'No cart found with that ID.');
        }

        if ($cart->quantity > 1) {
            $cart->quantity = $cart->quantity - 1;
            $cart->save();
            
        } else {
            Cart::destroy($id);
        }

        return redirect()->back();
    }

    public function checkout()
    {
        if (Auth::user()) {
            $userId = Auth::id();
            $cart = Cart::where('user_id', $userId)->get();

            if ($cart->count() == 0) {
                return redirect()->route('cartItems')->with('error', 'Your cart is empty.');
            }

            $total = 0;
            foreach ($cart as $item) {
                $watch = Watch::find($item->watch_id);
                $total = $total + ($watch->price * $item->quantity);
            }

            return view('buyer.checkout', compact('cart', 'total'));
        } else {
            return redirect()->route('login');
        }
    }

    public function remove(Request $req)
    {
        $id = $req->remove;
        $cart = Cart::where('id', $id)->first();

        if (!$cart) {
            return redirect()->back()->with('error', 'No cart item with that ID.');
        }

        Cart::destroy($id);

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function addToCart(Request $req)
    {
        if (!Auth::user()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to your cart.');
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
    }

    public function placeOrder(Request $req)
    {
        $user_id = Auth::id();
        $cartItems = Cart::where('user_id', $user_id)->get();

        if ($cartItems->count() == 0) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

       
        foreach ($cartItems as $item) {
            $watch = Watch::find($item->watch_id);
            if ($watch->stock < $item->quantity) {
                return redirect()->back()->with('error', "Not enough stock for {$watch->name}. Only {$watch->stock} available, but you have {$item->quantity} in cart.");
            }
        }

        $total_price = 0;
        foreach ($cartItems as $item) {
            $total_price = $total_price + ($item->watch->price * $item->quantity);
        }

        $order = new Order;
        $order->user_id = $user_id;
        $order->customer_name = $req->customer_name;
        $order->street_address = $req->street_address;
        $order->city = $req->city;
        $order->postal_code = $req->postal_code;
        $order->phone_number = $req->phone_number;
        $order->status = "Pending";
        $order->total_amount = $total_price;
        $order->save();

        foreach ($cartItems as $item) {
            $watch = Watch::find($item->watch_id);
            $watch->stock = $watch->stock - $item->quantity;
            $watch->save();

            $orderItem = new OrderItem;
            $orderItem->order_id = $order->id;
            $orderItem->watch_id = $item->watch_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $watch->price;
            $orderItem->save();
        }

        $mailData = [
            'customer_name' => $req->customer_name,
            'order_id' => $order->id,
            'total_amount' => $total_price,
            'status' => $order->status,
        ];

        Mail::to($order->user->email)->send(new OrderPlacedMail($mailData));

        Cart::where('user_id', $user_id)->delete();

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }

    public function myOrders()
    {
        if (Auth::user()) {
            $user_id = Auth::id();

            $orders = Order::where('user_id', $user_id)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('buyer.myorders', compact('orders'));
        } else {
            return redirect()->route('login');
        }
    }

    public function aboutUs()
    {
        return view('buyer.aboutUs');
    }
}