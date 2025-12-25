@extends('buyer.layout')

@section('title', 'My Orders')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ secure_asset('css/buyer/cart.css') }}">
<style>
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-badge.processing {
        background: #cfe2ff;
        color: #084298;
    }
    
    .status-badge.shipped {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-badge.delivered {
        background: #d4edda;
        color: #155724;
    }
    
    .status-badge.cancelled {
        background: #f8d7da;
        color: #721c24;
    }
    
    .watch-img {
        max-width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .watch-img-error {
        opacity: 0.5;
        background: #f8f9fa;
    }
    
    .order-card {
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.3s;
    }
    
    .order-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .empty-history {
        text-align: center;
        padding: 80px 20px;
    }
    
    .empty-history-icon {
        font-size: 80px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    
    .order-actions {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #dee2e6;
    }
    
    .btn-reorder,
    .btn-track {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        cursor: pointer;
        margin-right: 10px;
        transition: all 0.3s;
    }
    
    .btn-reorder {
        background: #007bff;
        color: white;
    }
    
    .btn-reorder:hover {
        background: #0056b3;
    }
    
    .btn-track {
        background: #17a2b8;
        color: white;
    }
    
    .btn-track:hover {
        background: #117a8b;
    }
    
    .order-timeline {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .timeline-item {
        display: flex;
        align-items: center;
        font-size: 13px;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    
    .timeline-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #28a745;
        margin-right: 10px;
    }
    
    .shipping-info {
        margin-top: 15px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 8px;
        font-size: 14px;
    }
    
    .shipping-info h5 {
        margin: 0 0 10px 0;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
    }
    
    .shipping-info p {
        margin: 5px 0;
        color: #6c757d;
    }
</style>
@endsection

@section('content')

<div class="orders-container">
    <div class="orders-header">
        <h1 class="page-title">Order History</h1>
        <a href="{{ route('home') }}" class="continue-shopping-link">
            ← Continue Shopping
        </a>
    </div>

    @if(count($orders) > 0)
        @foreach($orders as $order)
        <div class="order-card" data-order-id="{{ $order->id }}">
            <div class="order-header">
                <div class="header-left">
                    <span>Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    <span class="separator">|</span>
                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="status-badge {{ strtolower($order->status) }}">
                    {{ $order->status }}
                </div>
            </div>

            <div class="order-content">
                @foreach($order->orderItems as $item)
                <div class="item-row">
                    @if(isset($item->watch) && $item->watch)
                        <img src="{{ $item->watch->image }}" 
                             class="watch-img" 
                             alt="{{ $item->watch->name }}"
                             onerror="this.classList.add('watch-img-error'); this.src='{{ secure_asset('images/placeholder-watch.jpg') }}'">
                        
                        <div class="item-details">
                            <p class="item-name">{{ $item->watch->name }}</p>
                            <p class="item-meta">Quantity: {{ $item->quantity }}</p>
                            <p class="item-meta">Unit Price: Rs. {{ number_format($item->price) }}</p>
                            <p class="item-meta">Subtotal: Rs. {{ number_format($item->price * $item->quantity) }}</p>
                        </div>
                    @else
                        <div class="item-details">
                            <p class="item-name" style="color: #6c757d;">Product no longer available</p>
                            <p class="item-meta">Quantity: {{ $item->quantity }}</p>
                            <p class="item-meta">Unit Price: Rs. {{ number_format($item->price) }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Shipping Information --}}
            @if($order->customer_name || $order->street_address)
            <div class="shipping-info">
                <h5>📦 Shipping Details</h5>
                <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                <p><strong>Address:</strong> {{ $order->street_address }}, {{ $order->city }}, {{ $order->postal_code }}</p>
                <p><strong>Phone:</strong> {{ $order->phone_number }}</p>
            </div>
            @endif

            {{-- Order Timeline --}}
            <div class="order-timeline">
                <div class="timeline-item">
                    <span class="timeline-dot"></span>
                    <span>Order Placed - {{ $order->created_at->format('M d, Y h:i A') }}</span>
                </div>
                @if($order->status !== 'Pending')
                <div class="timeline-item">
                    <span class="timeline-dot"></span>
                    <span>{{ $order->status }} - {{ $order->updated_at->format('M d, Y h:i A') }}</span>
                </div>
                @endif
            </div>

            <div class="order-footer">
                <div class="total-paid">
                    <span class="total-label">Total Paid:</span>
                    Rs. {{ number_format($order->total_amount) }}
                </div>
                
                {{-- Order Actions --}}
                <div class="order-actions">
                    @if(in_array($order->status, ['Delivered']))
                        <button class="btn-reorder" onclick="reorderItems({{ $order->id }})">
                            🔄 Reorder
                        </button>
                    @endif
                    
                    @if(in_array($order->status, ['Processing', 'Shipped']))
                        <button class="btn-track" onclick="trackOrder({{ $order->id }})">
                            📍 Track Order
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        {{-- Pagination info --}}
        <div style="text-align: center; margin-top: 30px; color: #6c757d; font-size: 14px;">
            Showing {{ count($orders) }} {{ count($orders) == 1 ? 'order' : 'orders' }}
        </div>
    @else
        <div class="empty-history">
            <div class="empty-history-icon">📦</div>
            <h2>No Orders Yet</h2>
            <p>Your collection history is currently empty.</p>
            <p style="color: #6c757d; margin-bottom: 30px;">
                Start exploring our curated selection of luxury timepieces.
            </p>
            <a href="{{ route('home') }}" class="btn-shop">Begin Your Collection</a>
        </div>
    @endif
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Reorder Items
    window.reorderItems = function(orderId) {
        if (!orderId) {
            alert('Invalid order ID.');
            return;
        }
        
        if (confirm('Would you like to add all items from this order to your cart?')) {
            // In a real application, you would make an AJAX call here
            alert('Reorder functionality coming soon! This would add all items to your cart.');
            // Example implementation:
            // fetch('/orders/' + orderId + '/reorder', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
            //     }
            // }).then(response => {
            //     if (response.ok) {
            //         window.location.href = '{{ route("cartItems") }}';
            //     }
            // });
        }
    };
    
    // Track Order
    window.trackOrder = function(orderId) {
        if (!orderId) {
            alert('Invalid order ID.');
            return;
        }
        
        // In a real application, this would show tracking information
        alert('Order Tracking:\n\nOrder #' + String(orderId).padStart(6, '0') + ' is being processed.\n\nTracking information will be available once the order ships.');
        
        // Example implementation:
        // window.location.href = '/orders/' + orderId + '/track';
    };
    
    // Handle image errors
    const orderImages = document.querySelectorAll('.watch-img');
    orderImages.forEach(img => {
        img.addEventListener('error', function() {
            if (!this.classList.contains('watch-img-error')) {
                this.classList.add('watch-img-error');
                this.src = '{{ secure_asset("images/placeholder-watch.jpg") }}';
                this.alt = 'Image not available';
            }
        });
    });
    
    // Add loading state to action buttons
    const actionButtons = document.querySelectorAll('.btn-reorder, .btn-track');
    actionButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.disabled = true;
            const originalText = this.textContent;
            this.textContent = 'Processing...';
            
            setTimeout(() => {
                this.disabled = false;
                this.textContent = originalText;
            }, 3000);
        });
    });
    
    // Filter orders by status (optional enhancement)
    window.filterOrdersByStatus = function(status) {
        const orderCards = document.querySelectorAll('.order-card');
        
        orderCards.forEach(card => {
            const statusBadge = card.querySelector('.status-badge');
            const cardStatus = statusBadge.textContent.trim().toLowerCase();
            
            if (status === 'all' || cardStatus === status.toLowerCase()) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    };
    
    // Highlight recent orders (placed in last 24 hours)
    const orderCards = document.querySelectorAll('.order-card');
    const now = new Date();
    
    orderCards.forEach(card => {
        const orderDate = card.querySelector('.header-left span:nth-child(3)').textContent;
        // Additional logic could be added here to highlight recent orders
    });
});
</script>
@endsection