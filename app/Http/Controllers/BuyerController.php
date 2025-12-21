<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Watch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;



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


        if ($watch->stock < 1) {
            return redirect()->back()->with('error', 'Not enough stock to increase quantity.');
        }

        // Proceed as you did
        $cart->quantity += 1;
        $watch->stock -= 1;
        $watch->save();
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

        $watch = Watch::where('id', $cart->watch_id)->first();
        if (!$watch) {
            return redirect()->back()->with('error', 'Watch not found.');
        }

        if ($cart->quantity > 1) {
            $cart->quantity -= 1;
            $watch->stock += 1;

            $cart->save();
            $watch->save();
        } else {

            $watch->stock += 1;
            $watch->save();

            Cart::destroy($id);
        }

        return redirect()->back();
    }


    public function checkout()
    {
        if (Auth::user()) {

            $userId = Auth::id();
            $cart = Cart::where('user_id', $userId)->get();

            if ($cart->isEmpty()) {
                return redirect()->route('cartItems')->with('error', 'Your cart is empty.');
            }

            $total = 0;
            foreach ($cart as $item) {
                $watch = Watch::find($item->watch_id);
                $total += $watch->price * $item->quantity;
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

        $watch = Watch::where('id', $cart->watch_id)->first();

        if (!$watch) {
            return redirect()->back()->with('error', 'Watch not found.');
        }

        $quantity = $cart->quantity;
        $watch->stock += $quantity;
        $watch->save();

        Cart::destroy($id);

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function addToCart(Request $req)
    {
        if (Auth::user()) {

            $id = $req->id;
            $user_id = Auth::id();

            $cart = Cart::where('watch_id', $id)->where('user_id', $user_id)->first();

            $watch = Watch::where('id', $id)->first();

            if ($watch->stock > 0) {

                if ($cart) {
                    $cart->quantity += 1;
                    $watch->stock -= 1;

                    $cart->save();
                    $watch->save();

                    return redirect()->back()->with('success', 'Item quantity increased in cart.');
                } else {
                    $cart = new Cart;
                    $cart->user_id = $user_id;
                    $cart->watch_id = $id;
                    $cart->quantity = 1;
                    $cart->save();

                    $watch->stock -= 1;
                    $watch->save();

                    return redirect()->back()->with('success', 'Item added to cart.');
                }
            } else {
                return redirect()->back()->with('error', 'Not in stock any more');
            }
        } else {
            return redirect()->route('login');
        }
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

    public function placeOrder(Request $req)
    {
        $user_id = Auth::id();
        $cartItems = Cart::where('user_id', $user_id)->get();

        $total_price = 0;

        foreach ($cartItems as $item) {
            $total_price += $item->watch->price * $item->quantity;
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
            $orderItem = new OrderItem;

            $orderItem->order_id = $order->id;
            $orderItem->watch_id = $item->watch_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->watch->price;

            $orderItem->save();
        }

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
