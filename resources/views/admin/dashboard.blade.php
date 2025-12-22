@extends('admin.layout')

@section('title', 'Dashboard Overview')

@section('content')

@if(session('success'))
<div class="alert-container">
<div class="alert-success">
<span>{{ session('success') }}</span>
<button type="button" class="close-btn">×</button>
</div>
</div>
@endif

<div class="page-header">
<h1>Store Overview</h1>
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