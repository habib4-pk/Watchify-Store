<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Watch;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * CartController
 * Handles all shopping cart operations: view, add, increase, decrease, remove
 */
class CartController extends Controller
{
    /**
     * Display the user's cart
     */
    public function show()
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('account.login')->with('error', 'Please login to view your cart.');
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
            
            return view('buyer.cart', compact('cart', 'total'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Could not load cart.');
        }
    }

    /**
     * Add a watch to the cart
     */
    public function add(Request $req)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('account.login')->with('error', 'Please log in to add items to your cart.');
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

    /**
     * Increase cart item quantity
     */
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

    /**
     * Decrease cart item quantity
     */
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

    /**
     * Remove item from cart
     */
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
}
