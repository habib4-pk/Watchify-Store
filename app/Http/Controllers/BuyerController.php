<?php

namespace App\Http\Controllers;

use App\Models\Watch;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Console\View\Components\Warn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BuyerController extends Controller
{

    public function home()
    {
        $allWatches = Watch::all();
        return view('buyer.home', compact('allWatches'));
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

        if ($cart) {
            $cart->quantity += 1;
            $cart->save();
            return redirect()->back();
        } else {
            dd('no cart found with that id');
        }
    }

    public function decrease(Request $req)
    {
        $id = $req->decrease;
        $cart = Cart::where('id', $id)->first();

        if ($cart) {
            if ($cart->quantity > 1) {
                $cart->quantity -= 1;
                $cart->save();
                return redirect()->back();
            } else {
                Cart::destroy($id);
                return redirect()->back();
            }
        } else {
            dd('no cart found with that id');
        }
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

        if ($cart) {
            Cart::destroy($id);
            return redirect()->back();
        } else {
            dd('no cart item with that id');
        }
    }

    public function addToCart(Request $req)
    {

        if (Auth::user()) {

            $id = $req->id;
            $user_id = Auth::id();

            $cart = Cart::where('watch_id', $id)->where('user_id', $user_id)->first();

            if ($cart) {
                $cart->quantity += 1;
                $cart->save();
                return redirect()->back();
            } else {
                $cart = new Cart;
                $cart->user_id = $user_id;
                $cart->watch_id = $id;
                $cart->save();
                return redirect()->back();
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


        if(Auth::user()){

            $user_id = Auth::id();

        $orders = Order::where('user_id', $user_id)
            ->orderBy('created_at', 'desc')
            ->get();

       


            return view('buyer.myorders',compact('orders'));


        

        }else{
            return redirect()->route('login');
        }

        


    }


    public function aboutUs()
    {
        return view('buyer.aboutUs');
    }
}
