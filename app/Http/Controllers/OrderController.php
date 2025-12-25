<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Watch;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Mail\OrderStatusMail;
use Illuminate\Support\Facades\Mail;
use Exception;

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

            return view('admin.orders.show', compact('orderItems', 'totalPrice'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Unable to load order details.');
        }
    }

    public function update(Request $req)
    {
        try {
            $id = $req->order_id;
            $order = Order::where('id', $id)->first();

            if (!$order) {
                return redirect()->route('allOrders')->with('error', 'Order not found.');
            }

            $newStatus = strtolower($req->status);
            $oldStatus = strtolower($order->status);

            if ($oldStatus === $newStatus) {
                return redirect()->route('allOrders')->with('success', 'Same Status. No Change!');
            }

            // --- Logic: Once completed, cannot be changed ---
            if ($oldStatus === 'completed') {
                return redirect()->route('allOrders')->with('error', 'Completed orders cannot be modified.');
            }

            // --- Logic: Once cancelled, cannot be moved back to shipped or completed ---
            if ($oldStatus === 'cancelled') {
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


            return redirect()->route('allOrders')->with('success', 'Order status updated successfully!');
        } catch (Exception $e) {
            return redirect()->route('allOrders')->with('error', 'Failed to update order status.');
        }
    }
}