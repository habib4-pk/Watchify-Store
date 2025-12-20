@extends('admin.layout')

@section('title', 'Dashboard Overview')

@section('styles')
<style>
    /* Grid Layout for Cards */
    .dashboard-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); 
        gap: 25px; 
        margin-top: 10px;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
    }

    .dashboard-text { color: #64748b; font-size: 14px; font-weight: 600; margin-bottom: 5px; }
    .dashboard-number { color: #1e293b; font-size: 28px; font-weight: 700; }
    
    /* Simple Indicator Colors */
    .border-users { border-bottom: 4px solid #3b82f6; }
    .border-orders { border-bottom: 4px solid #10b981; }
    .border-watches { border-bottom: 4px solid #f59e0b; }
    .border-sales { border-bottom: 4px solid #ef4444; }
</style>
@endsection

@section('content')
<div class="dashboard-grid">
    <div class="dashboard-card border-users">
        <div class="dashboard-text">Total Users</div>
        <div class="dashboard-number">{{ $totalUsers }}</div>
    </div>

    <div class="dashboard-card border-orders">
        <div class="dashboard-text">Total Orders</div>
        <div class="dashboard-number">{{ $totalOrders }}</div>
    </div>

    <div class="dashboard-card border-watches">
        <div class="dashboard-text">Total Watches</div>
        <div class="dashboard-number">{{ $totalWatches }}</div>
    </div>

    <div class="dashboard-card border-sales">
        <div class="dashboard-text">Total Sales</div>
        <div class="dashboard-number">Rs. {{ number_format($totalSales, 2) }}</div>
    </div>
</div>
@endsection