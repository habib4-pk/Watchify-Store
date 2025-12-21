<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $allOrders = Order::all();

        return view('admin.orders.index', compact('allOrders'));
    }

    public function show(Request $req)
    {
        $orderId = $req->id;

        $orderItems = OrderItem::where('order_id', $orderId)->get();

        $totalPrice = 0;

        foreach ($orderItems as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        return view('admin.orders.show', compact('orderItems', 'totalPrice'));
    }

    public function update(Request $req)
    {
        $id = $req->order_id;

        $order = Order::where('id', $id)->first();

        $order->status = $req->status;

        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
