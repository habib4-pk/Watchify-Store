@extends('admin.layout')

@section('title', 'Order Details')

@section('styles')
<style>
    .order-info-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 30px; }
    .order-info-card h1 { margin-bottom: 20px; font-size: 22px; color: #1e293b; }
    .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 20px; }
    .info-item label { display: block; font-size: 12px; color: #94a3b8; text-transform: uppercase; font-weight: bold; }
    .info-item p { font-size: 16px; color: #1e293b; margin-top: 5px; }

    /* Form Styling */
    .status-form { display: flex; align-items: flex-end; gap: 10px; padding-top: 20px; border-top: 1px solid #f1f5f9; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    select { padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; background: white; min-width: 150px; }
    .btn-update { background: #1e293b; color: white; border: none; padding: 9px 20px; border-radius: 6px; cursor: pointer; font-weight: 500; }
    .btn-update:hover { background: #334155; }

    /* Table for Items */
    .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .items-table th { background: #f8fafc; color: #64748b; text-align: left; padding: 12px; border-bottom: 2px solid #e2e8f0; }
    .items-table td { padding: 12px; border-bottom: 1px solid #f1f5f9; }
</style>
@endsection

@section('content')

@if(count($orderItems) == 0)
    <div class="order-info-card"><p>No order items found.</p></div>
@else
    <div class="order-info-card">
        <h1>Order #{{ $orderItems[0]->order->id }} Details</h1>
        
        <div class="info-grid">
            <div class="info-item">
                <label>Customer Name</label>
                <p>{{ $orderItems[0]->order->customer_name }}</p>
            </div>
            <div class="info-item">
                <label>Total Revenue</label>
                <p><strong>Rs. {{ number_format($totalPrice, 2) }}</strong></p>
            </div>
            <div class="info-item">
                <label>Order Date</label>
                <p>{{ $orderItems[0]->order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <form action="{{ route('updateOrderStatus') }}" method="POST" class="status-form">
            @csrf
            <input type="hidden" name="order_id" value="{{ $orderItems[0]->order->id }}">
            <div class="form-group">
                <label for="status">Update Order Status</label>
                <select name="status" id="status">
                    @php $currentStatus = $orderItems[0]->order->status; @endphp
                    <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="shipped" {{ $currentStatus == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn-update">Update Status</button>
        </form>
    </div>

    <div class="order-info-card">
        <h3 style="margin-bottom: 15px;">Items in this order</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Watch Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $item->watch->name ?? 'N/A' }}</strong></td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs. {{ number_format($item->price, 2) }}</td>
                        <td>Rs. {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection