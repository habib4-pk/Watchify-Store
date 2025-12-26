<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Watch;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Mail\OrderStatusMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Exception;

/**
 * OrderController
 * Handles order management: list, show, update status
 * Supports both AJAX (JSON) and traditional (redirect) responses
 */
class OrderController extends Controller
{
    public function index()
    {
        try {
            $allOrders = Order::all();
            return view('admin.orders.index', compact('allOrders'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load orders.');
        }
    }

    public function show(Request $req)
    {
        try {
            $orderId = $req->id;
            $orderItems = OrderItem::where('order_id', $orderId)->get();

            $totalPrice = 0;
            foreach ($orderItems as $item) {
                $totalPrice += $item->price * $item->quantity;
            }

            if ($req->expectsJson()) {
                $items = [];
                foreach ($orderItems as $item) {
                    $watch = Watch::find($item->watch_id);
                    $items[] = [
                        'id' => $item->id,
                        'watch_name' => $watch ? $watch->name : 'Unknown',
                        'watch_image' => $watch ? $watch->image : null,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->price * $item->quantity
                    ];
                }
                return response()->json([
                    'success' => true,
                    'items' => $items,
                    'totalPrice' => $totalPrice
                ]);
            }

            return view('admin.orders.show', compact('orderItems', 'totalPrice'));
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unable to load order details.'], 500);
            }
            return redirect()->back()->with('error', 'Unable to load order details.');
        }
    }

    public function update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:Pending,Shipped,Completed,Cancelled,pending,shipped,completed,cancelled'
        ]);

        if ($validator->fails()) {
            if ($req->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid input.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        try {
            $id = $req->order_id;
            $order = Order::where('id', $id)->first();

            if (!$order) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
                }
                return redirect()->route('allOrders')->with('error', 'Order not found.');
            }

            $newStatus = strtolower($req->status);
            $oldStatus = strtolower($order->status);

            if ($oldStatus === $newStatus) {
                if ($req->expectsJson()) {
                    return response()->json(['success' => true, 'message' => 'Same status. No change needed.']);
                }
                return redirect()->route('allOrders')->with('success', 'Same Status. No Change!');
            }

            // --- Logic: Once completed, cannot be changed ---
            if ($oldStatus === 'completed') {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Completed orders cannot be modified.'], 400);
                }
                return redirect()->route('allOrders')->with('error', 'Completed orders cannot be modified.');
            }

            // --- Logic: Once cancelled, cannot be moved back to shipped or completed ---
            if ($oldStatus === 'cancelled') {
                if ($req->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cancelled orders cannot be reopened.'], 400);
                }
                return redirect()->route('allOrders')->with('error', 'Cancelled orders cannot be reopened or shipped.');
            }

            // Handle Stock Reversion if the order is being cancelled now
            if ($newStatus === 'cancelled') {
                $orderItems = OrderItem::where('order_id', $order->id)->get();
                foreach ($orderItems as $item) {
                    $watch = Watch::where('id', $item->watch_id)->first();
                    if ($watch) {
                        $watch->stock = $watch->stock + $item->quantity;
                        $watch->save();
                    }
                }
            }

            $order->status = $req->status; // Keep original casing from request
            $order->save();

            if ($req->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order status updated successfully!',
                    'newStatus' => $order->status
                ]);
            }
            return redirect()->route('allOrders')->with('success', 'Order status updated successfully!');
        } catch (Exception $e) {
            if ($req->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to update order status.'], 500);
            }
            return redirect()->route('allOrders')->with('error', 'Failed to update order status.');
        }
    }
}