@extends('admin.layout')

@section('title', 'Manage Orders')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .page-title { font-size: 24px; color: #1e293b; }
    
    /* Table Styling */
    .table-container { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { background: #1e293b; color: white; padding: 15px; font-weight: 600; font-size: 14px; }
    td { padding: 15px; border-bottom: 1px solid #e2e8f0; color: #475569; font-size: 14px; }
    tr:hover { background-color: #f8fafc; }

    /* Custom Badges */
    .badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: capitalize; }
    .bg-success { background: #dcfce7; color: #15803d; }
    .bg-warning { background: #fef9c3; color: #854d0e; }
    .bg-danger { background: #fee2e2; color: #b91c1c; }
    .bg-secondary { background: #f1f5f9; color: #475569; }

    /* Buttons */
    .btn-view { background: #38bdf8; color: white; text-decoration: none; padding: 6px 12px; border-radius: 4px; font-size: 13px; transition: 0.2s; }
    .btn-view:hover { background: #0ea5e9; }
    
    .alert-success { padding: 15px; background: #dcfce7; color: #15803d; border-radius: 6px; margin-bottom: 20px; border: 1px solid #bbf7d0; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Manage Orders</h1>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

<div class="table-container">
    <table id="ordersTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>City</th>
                <th>Total (Rs.)</th>
                <th>Status</th>
                <th>Order Date</th>
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
                <td>{{ number_format($order->total_amount, 2) }}</td>
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
                  <form action="{{route('orderDetails')}}" action="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{$order->id}}">
                    <button type="submit" class="btn-view">View</button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection