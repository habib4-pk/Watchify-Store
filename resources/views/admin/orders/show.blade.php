@extends('admin.layout')

@section('title', 'Order Management - Details')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/orders-management.css') }}">
@endsection

@section('content')

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert-wrapper">
        <div id="success-alert" class="alert-success-box">
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button"
                    class="alert-close-btn"
                    onclick="document.getElementById('success-alert').remove()">
                &times;
            </button>
        </div>
    </div>
@endif

{{-- No Items --}}
@if(count($orderItems) == 0)
    <div class="order-info-card">
        <p class="empty-message">No order items found for this reference.</p>
    </div>

@else

{{-- Order Info --}}
<div class="order-info-card">
    <h1 class="card-title">
        Order #{{ str_pad($orderItems[0]->order->id, 6, '0', STR_PAD_LEFT) }} Details
    </h1>

    <div class="info-grid">

        <div class="info-item">
            <label class="info-label">Customer Identity</label>
            <p class="info-value">
                {{ $orderItems[0]->order->customer_name }}
            </p>
        </div>

        <div class="info-item">
            <label class="info-label">Total Revenue</label>
            <p class="info-value info-value-amount">
                Rs. {{ number_format($totalPrice, 2) }}
            </p>
        </div>

        <div class="info-item">
            <label class="info-label">Transaction Date</label>
            <p class="info-value">
                {{ $orderItems[0]->order->created_at->format('d M Y, h:i A') }}
            </p>
        </div>

    </div>

    {{-- Status Update --}}
    <form action="{{ route('updateOrderStatus') }}" method="POST" class="status-form">
        @csrf
        <input type="hidden" name="order_id" value="{{ $orderItems[0]->order->id }}">

        <div class="form-group">
            <label for="status" class="form-label">Modify Order Status</label>
            <select name="status" id="status" class="status-select">
                @php $currentStatus = $orderItems[0]->order->status; @endphp
                <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="shipped" {{ $currentStatus == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn-update">
            Update Status
        </button>
    </form>
</div>

{{-- Items Table --}}
<div class="order-info-card">
    <h3 class="card-title card-title-small">
        Itemized Manifest
    </h3>

    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Watch Model</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($orderItems as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td class="item-name">
                    {{ $item->watch->name ?? 'Unknown Model' }}
                </td>

                <td>{{ $item->quantity }}</td>

                <td>
                    Rs. {{ number_format($item->price, 2) }}
                </td>

                <td class="item-subtotal">
                    Rs. {{ number_format($item->quantity * $item->price, 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif

@endsection
