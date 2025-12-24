@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #238636;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1">Welcome back!</h2>
        <p class="text-secondary mb-0">Here's what's happening with your store today.</p>
    </div>
    <a href="{{ url('/admin/watches/create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i>
        <span>Add Watch</span>
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- Total Users Card -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 rounded-4" style="background: linear-gradient(135deg, #238636 0%, #2ea043 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 p-2" style="background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-people fs-4 text-white"></i>
                    </div>
                    <i class="bi bi-arrow-up-right text-white-50"></i>
                </div>
                <h3 class="display-6 fw-bold text-white mb-1">{{ $totalUsers }}</h3>
                <p class="text-white-50 mb-0 small">Total Users</p>
            </div>
        </div>
    </div>

    <!-- Total Orders Card -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 rounded-4" style="background: linear-gradient(135deg, #1f6feb 0%, #388bfd 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 p-2" style="background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-bag-check fs-4 text-white"></i>
                    </div>
                    <i class="bi bi-arrow-up-right text-white-50"></i>
                </div>
                <h3 class="display-6 fw-bold text-white mb-1">{{ $totalOrders }}</h3>
                <p class="text-white-50 mb-0 small">Total Orders</p>
            </div>
        </div>
    </div>

    <!-- Total Watches Card -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 rounded-4" style="background: linear-gradient(135deg, #8957e5 0%, #a371f7 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 p-2" style="background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-smartwatch fs-4 text-white"></i>
                    </div>
                    <i class="bi bi-arrow-up-right text-white-50"></i>
                </div>
                <h3 class="display-6 fw-bold text-white mb-1">{{ $totalWatches }}</h3>
                <p class="text-white-50 mb-0 small">Total Watches</p>
            </div>
        </div>
    </div>

    <!-- Revenue Card -->
    <div class="col-6 col-lg-3">
        <div class="card h-100 border-0 rounded-4" style="background: linear-gradient(135deg, #f0883e 0%, #f69a54 100%);">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="rounded-3 p-2" style="background-color: rgba(255,255,255,0.2);">
                        <i class="bi bi-currency-dollar fs-4 text-white"></i>
                    </div>
                    <i class="bi bi-arrow-up-right text-white-50"></i>
                </div>
                <h4 class="h4 fw-bold text-white mb-1">Rs. {{ number_format($totalSales, 0) }}</h4>
                <p class="text-white-50 mb-0 small">Total Revenue</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-header border-bottom py-3" style="background-color: transparent; border-color: #30363d !important;">
        <h5 class="text-white mb-0 fw-semibold">Quick Actions</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ url('/admin/watches') }}" class="btn btn-outline-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3">
                    <i class="bi bi-smartwatch fs-4"></i>
                    <span class="small">View Watches</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('allOrders') }}" class="btn btn-outline-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3">
                    <i class="bi bi-bag-check fs-4"></i>
                    <span class="small">View Orders</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('allUsers') }}" class="btn btn-outline-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3">
                    <i class="bi bi-people fs-4"></i>
                    <span class="small">View Users</span>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ url('/') }}" target="_blank" class="btn btn-outline-light w-100 py-3 d-flex flex-column align-items-center gap-2 rounded-3">
                    <i class="bi bi-globe fs-4"></i>
                    <span class="small">Visit Store</span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection