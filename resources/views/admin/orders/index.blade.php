@extends('admin.layout')

@section('title', 'Manage Orders')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

<style>
    /* --- Admin Layout Styling --- */
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 24px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }
    
    /* --- Manual Dismiss Success Alert --- */
    .alert-success { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 15px 20px; 
        background: #dcfce7; 
        color: #15803d; 
        border-radius: 8px; 
        margin-bottom: 25px; 
        border: 1px solid #bbf7d0; 
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .close-btn { 
        background: none; 
        border: none; 
        color: #15803d; 
        font-size: 24px; 
        font-weight: bold; 
        cursor: pointer; 
        line-height: 1; 
        padding: 0;
        margin-left: 20px;
        transition: transform 0.2s ease;
    }
    .close-btn:hover { transform: scale(1.2); opacity: 0.7; }

    /* --- Table Design --- */
    .table-container { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { background: #1e293b; color: white; padding: 18px 15px; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; }
    td { padding: 18px 15px; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 14px; }
    tr:last-child td { border-bottom: none; }
    tr:hover { background-color: #f1f5f9; }

    /* --- Role-Based Status Badges --- */
    .badge { padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .bg-success { background: #dcfce7; color: #15803d; }
    .bg-warning { background: #fef9c3; color: #854d0e; }
    .bg-danger { background: #fee2e2; color: #b91c1c; }
    .bg-secondary { background: #f1f5f9; color: #475569; }

    /* --- Action Button --- */
    .btn-view { 
        background: #1e293b; 
        color: white; 
        text-decoration: none; 
        padding: 8px 16px; 
        border: none;
        border-radius: 4px; 
        font-size: 11px; 
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: background 0.2s; 
    }
    .btn-view:hover { background: #334155; }
</style>
@endsection

@section('content')

@if(session('success'))
    <div style="max-width: 500px; margin: 0 auto 25px; padding: 0 15px;">
        <div id="success-alert" class="alert-success" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #dcfce7; color: #15803d; border-radius: 8px; border: 1px solid #bbf7d0; font-size: 14px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <span>{{ session('success') }}</span>
            <button type="button" class="close-btn" onclick="document.getElementById('success-alert').remove()" style="background: none; border: none; color: #15803d; font-size: 24px; font-weight: bold; cursor: pointer; line-height: 1; padding: 0; margin-left: 20px; transition: transform 0.2s ease;">&times;</button>
        </div>
    </div>
@endif


<div class="page-header">
    <h1 class="page-title">Order Management</h1>
</div>

<div class="table-container">
    <table id="ordersTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Contact</th>
                <th>Location</th>
                <th>Total Revenue</th>
                <th>Order Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allOrders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $order->customer_name }}</strong></td>
                <td>{{ $order->phone_number }}</td>
                <td>{{ $order->city }}</td>
                <td style="font-weight: 700; color: #1e293b;">Rs. {{ number_format($order->total_amount, 2) }}</td>
                <td>
                    <span class="badge 
                        @if($order->status === 'completed') bg-success 
                        @elseif($order->status === 'pending') bg-warning 
                        @elseif($order->status === 'cancelled') bg-danger 
                        @else bg-secondary @endif">
                        {{ $order->status }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>
                  <form action="{{ route('orderDetails') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <button type="submit" class="btn-view">View Details</button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection