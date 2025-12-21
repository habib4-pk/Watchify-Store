@extends('admin.layout')

@section('title', 'Order Management - Details')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

<style>
    /* --- Admin Layout Styling --- */
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
    
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

    /* --- Info Cards --- */
    .order-info-card { 
        background: white; 
        padding: 30px; 
        border-radius: 8px; 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
        margin-bottom: 30px; 
        border: 1px solid #e2e8f0;
    }
    
    .card-title { 
        margin-bottom: 25px; 
        font-size: 20px; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 15px;
    }

    .info-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
        gap: 30px; 
        margin-bottom: 25px; 
    }

    .info-item label { 
        display: block; 
        font-size: 11px; 
        color: #64748b; 
        text-transform: uppercase; 
        font-weight: 800; 
        letter-spacing: 1px;
    }

    .info-item p { 
        font-size: 15px; 
        color: #1e293b; 
        margin-top: 8px; 
        font-weight: 600;
    }

    /* --- Status Form Styling --- */
    .status-form { 
        display: flex; 
        align-items: flex-end; 
        gap: 15px; 
        padding-top: 25px; 
        border-top: 1px solid #f1f5f9; 
    }

    .form-group { display: flex; flex-direction: column; gap: 8px; }
    
    .form-group label {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: #64748b;
    }

    select { 
        padding: 10px 15px; 
        border: 1px solid #cbd5e1; 
        border-radius: 6px; 
        background: #f8fafc; 
        min-width: 200px; 
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #1e293b;
    }

    .btn-update { 
        background: #1e293b; 
        color: white; 
        border: none; 
        padding: 11px 25px; 
        border-radius: 6px; 
        cursor: pointer; 
        font-weight: 700; 
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background 0.2s;
    }
    .btn-update:hover { background: #334155; }

    /* --- Items Table --- */
    .items-table { width: 100%; border-collapse: collapse; }
    .items-table th { 
        background: #f8fafc; 
        color: #64748b; 
        text-align: left; 
        padding: 15px; 
        border-bottom: 2px solid #e2e8f0; 
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .items-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #475569; }
    tr:hover td { background-color: #fbfcfd; }
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


@if(count($orderItems) == 0)
    <div class="order-info-card">
        <p style="text-align: center; color: #64748b;">No order items found for this reference.</p>
    </div>
@else
    <div class="order-info-card">
        <h1 class="card-title">Order #{{ str_pad($orderItems[0]->order->id, 6, '0', STR_PAD_LEFT) }} Details</h1>
        
        <div class="info-grid">
            <div class="info-item">
                <label>Customer Identity</label>
                <p>{{ $orderItems[0]->order->customer_name }}</p>
            </div>
            <div class="info-item">
                <label>Total Revenue</label>
                <p style="color: #1e293b; font-size: 18px;">Rs. {{ number_format($totalPrice, 2) }}</p>
            </div>
            <div class="info-item">
                <label>Transaction Date</label>
                <p>{{ $orderItems[0]->order->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <form action="{{ route('updateOrderStatus') }}" method="POST" class="status-form">
            @csrf
            <input type="hidden" name="order_id" value="{{ $orderItems[0]->order->id }}">
            <div class="form-group">
                <label for="status">Modify Order Status</label>
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
        <h3 class="card-title" style="font-size: 16px; border-bottom: none; margin-bottom: 10px;">Itemized Manifest</h3>
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
                        <td><strong style="color: #1e293b;">{{ $item->watch->name ?? 'Unknown Model' }}</strong></td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rs. {{ number_format($item->price, 2) }}</td>
                        <td style="font-weight: 700; color: #1e293b;">Rs. {{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection