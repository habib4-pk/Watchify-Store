@extends('admin.layout')

@section('title', 'Order Details')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #238636;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

@if(count($orderItems) == 0)
<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox display-1 text-secondary mb-3"></i>
        <h5 class="text-white">No Items Found</h5>
        <p class="text-secondary mb-4">No order items found for this reference.</p>
        <a href="{{ route('allOrders') }}" class="btn btn-primary"><i class="bi bi-arrow-left me-2"></i>Back to Orders</a>
    </div>
</div>
@else

<div class="mb-4">
    <a href="{{ route('allOrders') }}" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-2"></i>Back to Orders</a>
</div>

<div class="card border-0 rounded-4 mb-4" style="background-color: #161b22;">
    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between" style="background-color: transparent; border-color: #30363d !important;">
        <h5 class="text-white mb-0 fw-semibold"><i class="bi bi-receipt me-2 text-primary"></i>Order #{{ str_pad($orderItems[0]->order->id, 6, '0', STR_PAD_LEFT) }}</h5>
        @php $currentStatus = $orderItems[0]->order->status; @endphp
        @if($currentStatus === 'completed')
            <span class="badge rounded-pill px-3 py-2" style="background-color: #238636;"><i class="bi bi-check-circle me-1"></i>Completed</span>
        @elseif($currentStatus === 'pending')
            <span class="badge rounded-pill px-3 py-2" style="background-color: #f0883e;"><i class="bi bi-clock me-1"></i>Pending</span>
        @elseif($currentStatus === 'shipped')
            <span class="badge rounded-pill px-3 py-2" style="background-color: #1f6feb;"><i class="bi bi-truck me-1"></i>Shipped</span>
        @else
            <span class="badge rounded-pill px-3 py-2" style="background-color: #da3633;"><i class="bi bi-x-circle me-1"></i>Cancelled</span>
        @endif
    </div>
    <div class="card-body p-4">
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="rounded-3 p-3" style="background-color: #21262d;">
                    <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-person text-primary"></i><span class="text-secondary small">Customer</span></div>
                    <h5 class="text-white fw-semibold mb-0">{{ $orderItems[0]->order->customer_name }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rounded-3 p-3" style="background-color: #21262d;">
                    <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-currency-dollar text-success"></i><span class="text-secondary small">Total</span></div>
                    <h5 class="text-white fw-bold mb-0">Rs. {{ number_format($totalPrice, 2) }}</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="rounded-3 p-3" style="background-color: #21262d;">
                    <div class="d-flex align-items-center gap-2 mb-2"><i class="bi bi-calendar text-info"></i><span class="text-secondary small">Date</span></div>
                    <h5 class="text-white fw-semibold mb-0">{{ $orderItems[0]->order->created_at->format('d M Y') }}</h5>
                </div>
            </div>
        </div>
        <div class="rounded-3 p-4" style="background-color: #21262d;">
            <h6 class="text-white fw-semibold mb-3"><i class="bi bi-arrow-repeat me-2 text-primary"></i>Update Status</h6>
            <form action="{{ route('updateOrderStatus') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <input type="hidden" name="order_id" value="{{ $orderItems[0]->order->id }}">
                <div class="col-md-6">
                    <select name="status" class="form-select border-0 rounded-3" style="background-color: #0d1117; color: #fff;">
                        <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="shipped" {{ $currentStatus == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="completed" {{ $currentStatus == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $currentStatus == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check2 me-2"></i>Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-header border-bottom py-3" style="background-color: transparent; border-color: #30363d !important;">
        <h5 class="text-white mb-0 fw-semibold"><i class="bi bi-list-ul me-2 text-primary"></i>Order Items</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="background-color: #161b22;">
                <thead style="background-color: #21262d;"><tr>
                    <th class="border-0 text-secondary fw-semibold py-3 ps-4">#</th>
                    <th class="border-0 text-secondary fw-semibold py-3">Watch</th>
                    <th class="border-0 text-secondary fw-semibold py-3">Qty</th>
                    <th class="border-0 text-secondary fw-semibold py-3">Price</th>
                    <th class="border-0 text-secondary fw-semibold py-3 pe-4">Subtotal</th>
                </tr></thead>
                <tbody>
                    @foreach($orderItems as $item)
                    <tr style="border-bottom: 1px solid #30363d;">
                        <td class="ps-4 text-white">{{ $loop->iteration }}</td>
                        <td class="text-white fw-medium">{{ $item->watch->name ?? 'Unknown' }}</td>
                        <td class="text-secondary">{{ $item->quantity }}</td>
                        <td class="text-secondary">Rs. {{ number_format($item->price, 2) }}</td>
                        <td class="text-white fw-semibold pe-4">Rs. {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background-color: #21262d;"><tr>
                    <td colspan="4" class="text-end text-secondary fw-semibold ps-4 py-3">Grand Total:</td>
                    <td class="text-white fw-bold pe-4 py-3">Rs. {{ number_format($totalPrice, 2) }}</td>
                </tr></tfoot>
            </table>
        </div>
    </div>
</div>

@endif

@endsection
