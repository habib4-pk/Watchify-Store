@extends('admin.layout')

@section('title', 'Orders')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #238636;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1">Order Management</h2>
        <p class="text-secondary mb-0">Manage and track all customer orders</p>
    </div>
</div>

<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="background-color: #161b22;">
                <thead style="background-color: #21262d;">
                    <tr>
                        <th class="border-0 text-secondary fw-semibold py-3 ps-4">#</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Customer</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Contact</th>
                        <th class="border-0 text-secondary fw-semibold py-3">City</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Amount</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Status</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Date</th>
                        <th class="border-0 text-secondary fw-semibold py-3 pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allOrders as $order)
                    <tr style="border-bottom: 1px solid #30363d;">
                        <td class="ps-4 text-white">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <span class="text-white small fw-bold">{{ strtoupper(substr($order->customer_name, 0, 1)) }}</span>
                                </div>
                                <span class="text-white fw-medium">{{ $order->customer_name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $order->phone_number }}</td>
                        <td class="text-secondary">{{ $order->city }}</td>
                        <td class="text-white fw-semibold">Rs. {{ number_format($order->total_amount, 0) }}</td>
                        <td>
                            @if($order->status === 'completed')
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #238636;"><i class="bi bi-check-circle me-1"></i>Completed</span>
                            @elseif($order->status === 'pending')
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #f0883e;"><i class="bi bi-clock me-1"></i>Pending</span>
                            @elseif($order->status === 'shipped')
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #1f6feb;"><i class="bi bi-truck me-1"></i>Shipped</span>
                            @elseif($order->status === 'cancelled')
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #da3633;"><i class="bi bi-x-circle me-1"></i>Cancelled</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="pe-4">
                            <form action="{{ route('orderDetails') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $order->id }}">
                                <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="bi bi-eye me-1"></i>View</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
