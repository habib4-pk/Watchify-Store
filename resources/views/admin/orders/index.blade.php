@extends('admin.layout')

@section('title', 'Manage Orders')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/orders-management.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')

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


<div class="page-header">
    <h1 class="page-title">Order Management</h1>
</div>

<div class="table-container">
    <table id="ordersTable" class="orders-table" border="1px solid">
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

                <td class="customer-name">
                    {{ $order->customer_name }}
                </td>

                <td>{{ $order->phone_number }}</td>

                <td>{{ $order->city }}</td>

                <td class="order-amount">
                    Rs. {{ number_format($order->total_amount, 2) }}
                </td>

                <td>
                    <span class="badge
                        @if($order->status === 'completed') badge-success
                        @elseif($order->status === 'pending') badge-warning
                        @elseif($order->status === 'cancelled') badge-danger
                        @else badge-secondary
                        @endif">
                        {{ $order->status }}
                    </span>
                </td>

                <td>{{ $order->created_at->format('d M Y') }}</td>

                <td>
                    <form action="{{ route('orderDetails') }}" method="POST" class="action-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $order->id }}">
                        <button type="submit" class="btn-view">
                            View Details
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
    $(document).ready(function () {
        $('#ordersTable').DataTable(
        {
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "lengthMenu": [ [1, 3, 5, 10], [1, 3, 5, 10] ],
        })
    })
</script>
</div>

@endsection
