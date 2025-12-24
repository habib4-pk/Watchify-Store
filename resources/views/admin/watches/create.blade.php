@extends('admin.layout')

@section('title', 'Add Watch')

@section('content')

<div class="mb-4">
    <a href="{{ url('/admin/watches') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-2"></i>Back to Watches
    </a>
</div>

<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-header border-bottom py-3" style="background-color: transparent; border-color: #30363d !important;">
        <h5 class="text-white mb-0 fw-semibold"><i class="bi bi-plus-circle me-2 text-primary"></i>Add New Watch</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('storeWatch') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label text-secondary">Watch Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-secondary">Price (Rs.)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" min="100" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary">Stock Quantity</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" min="1" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary">Description</label>
                <textarea name="description" rows="3" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary">Featured</label>
                <select name="featured" class="form-select border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
                    <option value="no" {{ old('featured') == 'no' ? 'selected' : '' }}>No</option>
                    <option value="yes" {{ old('featured') == 'yes' ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label text-secondary">Watch Image</label>
                <input type="file" name="image" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" accept="image/*" required>
                <small class="text-secondary">Allowed: jpeg, png, jpg. Max: 2MB.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-2"></i>Save Watch</button>
                <a href="{{ route('adminDashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
