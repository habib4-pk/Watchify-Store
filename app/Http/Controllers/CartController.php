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
 * Supports both AJAX (JSON) and traditional (redirect) responses
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
     * Get cart item count for AJAX
     */
    public function getCount(Request $req)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['success' => false, 'count' => 0]);
            }
            
            $count = Cart::where('user_id', Auth::id())->sum('quantity');
            return response()->json(['success' => true, 'count' => $count]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'count' => 0]);
        }
    }

    /**
     * Helper to get cart summary data
     */
    private function getCartSummary()
    {
        $cart = Cart::where('user_id', Auth::id())->get();
        $total = 0;
        $itemCount = 0;
        $cartCount = 0;
        
        foreach ($cart as $item) {
            if ($item->watch) {
                $total += $item->watch->price * $item->quantity;
                $itemCount++;
                $cartCount += $item->quantity;
            }
        }
        
        return [
            'total' => $total,
            'itemCount' => $itemCount,
            'cartCount' => $cartCount
        ];
    }

    /**
     * Add a watch to the cart
     */
    public function add(Request $req)
    {
        try {
            if (!Auth::check()) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Please log in to add items to your cart.'], 401);
                }
                return redirect()->route('account.login')->with('error', 'Please log in to add items to your cart.');
            }

            $validator = Validator::make($req->all(), [
                'id' => 'required|integer|exists:watches,id'
            ]);

            if ($validator->fails()) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid watch ID.'], 400);
                }
                return redirect()->back()->with('error', 'Invalid watch ID.');
            }

            $id = $req->id;
            $user_id = Auth::id();

            $cart = Cart::where('watch_id', $id)->where('user_id', $user_id)->first();
            $watch = Watch::where('id', $id)->first();

            if (!$watch) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Watch not found.'], 404);
                }
                return redirect()->back()->with('error', 'Watch not found.');
            }

            if ($watch->stock < 1) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Not in stock any more.'], 400);
                }
                return redirect()->back()->with('error', 'Not in stock any more.');
            }

            $message = '';
            if ($cart) {
                if ($cart->quantity + 1 > $watch->stock) {
                    $errorMsg = "Only {$watch->stock} units available. You already have {$cart->quantity} in your cart.";
                    if ($req->expectsJson()) {
                        return response()->json(['success' => false, 'message' => $errorMsg], 400);
                    }
                    return redirect()->back()->with('error', $errorMsg);
                }
                $cart->quantity = $cart->quantity + 1;
                $cart->save();
                $message = 'Item quantity increased in cart.';
            } else {
                $cart = new Cart;
                $cart->user_id = $user_id;
                $cart->watch_id = $id;
                $cart->quantity = 1;
                $cart->save();
                $message = 'Item added to cart.';
            }

            if ($req->expectsJson()) {
                $summary = $this->getCartSummary();
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'cartCount' => $summary['cartCount']
                ]);
            }
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to add item to cart.'], 500);
            }
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
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid cart item.'], 400);
                }
                return redirect()->back()->with('error', 'Invalid cart item.');
            }

            $id = $req->increase;
            $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$cart) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cart item not found or unauthorized.'], 404);
                }
                return redirect()->back()->with('error', 'Cart item not found or unauthorized.');
            }

            $watch = Watch::where('id', $cart->watch_id)->first();
            
            if (!$watch) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Watch not found.'], 404);
                }
                return redirect()->back()->with('error', 'Watch not found.');
            }

            if ($cart->quantity + 1 > $watch->stock) {
                $errorMsg = "Cannot add more. Only {$watch->stock} units available.";
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 400);
                }
                return redirect()->back()->with('error', $errorMsg);
            }

            $cart->quantity = $cart->quantity + 1;
            $cart->save();

            if ($req->expectsJson()) {
                $summary = $this->getCartSummary();
                $subtotal = $watch->price * $cart->quantity;
                return response()->json([
                    'success' => true,
                    'message' => 'Quantity increased.',
                    'quantity' => $cart->quantity,
                    'subtotal' => $subtotal,
                    'total' => $summary['total'],
                    'cartCount' => $summary['cartCount']
                ]);
            }
            return redirect()->back()->with('success', 'Quantity increased.');
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to increase quantity.'], 500);
            }
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
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid cart item.'], 400);
                }
                return redirect()->back()->with('error', 'Invalid cart item.');
            }

            $id = $req->decrease;
            $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$cart) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cart item not found or unauthorized.'], 404);
                }
                return redirect()->back()->with('error', 'Cart item not found or unauthorized.');
            }

            $watch = Watch::where('id', $cart->watch_id)->first();
            $removed = false;
            $quantity = 0;
            $subtotal = 0;

            if ($cart->quantity > 1) {
                $cart->quantity = $cart->quantity - 1;
                $cart->save();
                $quantity = $cart->quantity;
                $subtotal = $watch ? $watch->price * $quantity : 0;
                $message = 'Quantity decreased.';
            } else {
                Cart::destroy($id);
                $removed = true;
                $message = 'Item removed from cart.';
            }

            if ($req->expectsJson()) {
                $summary = $this->getCartSummary();
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'removed' => $removed,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                    'total' => $summary['total'],
                    'itemCount' => $summary['itemCount'],
                    'cartCount' => $summary['cartCount']
                ]);
            }
            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to decrease quantity.'], 500);
            }
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
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid cart item.'], 400);
                }
                return redirect()->back()->with('error', 'Invalid cart item.');
            }

            $id = $req->remove;
            $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

            if (!$cart) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cart item not found or unauthorized.'], 404);
                }
                return redirect()->back()->with('error', 'Cart item not found or unauthorized.');
            }

            Cart::destroy($id);

            if ($req->expectsJson()) {
                $summary = $this->getCartSummary();
                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from cart.',
                    'total' => $summary['total'],
                    'itemCount' => $summary['itemCount'],
                    'cartCount' => $summary['cartCount']
                ]);
            }
            return redirect()->back()->with('success', 'Item removed from cart.');
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to remove item.'], 500);
            }
            return redirect()->back()->with('error', 'Failed to remove item.');
        }
    }
}
