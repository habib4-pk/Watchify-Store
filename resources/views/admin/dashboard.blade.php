@extends('admin.layout')

@section('title', 'Dashboard Overview')

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
        margin-bottom: 30px; 
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

    /* --- Grid Layout for Dashboard Cards --- */
    .dashboard-grid { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); 
        gap: 30px; 
        margin-top: 10px;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .dashboard-text { 
        color: #64748b; 
        font-size: 12px; 
        font-weight: 800; 
        margin-bottom: 15px; 
        text-transform: uppercase;
        letter-spacing: 1.5px;
    }

    .dashboard-number { 
        color: #1e293b; 
        font-size: 32px; 
        font-weight: 700; 
    }
    
    /* --- Professional Border Indicators --- */
    .border-users { border-left: 5px solid #3b82f6; }
    .border-orders { border-left: 5px solid #10b981; }
    .border-watches { border-left: 5px solid #f59e0b; }
    .border-sales { border-left: 5px solid #ef4444; }
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


<div class="page-header" style="margin-bottom: 30px;">
    <h1 style="font-size: 24px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 1px;">Store Overview</h1>
</div>

<div class="dashboard-grid">
    <div class="dashboard-card border-users">
        <div class="dashboard-text">Member Base</div>
        <div class="dashboard-number">{{ $totalUsers }}</div>
    </div>

    <div class="dashboard-card border-orders">
        <div class="dashboard-text">Order Volume</div>
        <div class="dashboard-number">{{ $totalOrders }}</div>
    </div>

    <div class="dashboard-card border-watches">
        <div class="dashboard-text">Inventory Units</div>
        <div class="dashboard-number">{{ $totalWatches }}</div>
    </div>

    <div class="dashboard-card border-sales">
        <div class="dashboard-text">Revenue Generated</div>
        <div class="dashboard-number">Rs. {{ number_format($totalSales, 2) }}</div>
    </div>
</div>

@endsection