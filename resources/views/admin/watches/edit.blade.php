@extends('admin.layout')

@section('title', 'Edit Watch')

@section('content')

<div class="mb-4">
    <a href="{{ url('/admin/watches') }}" class="btn btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-left me-2"></i>Back to Watches
    </a>
</div>

<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-header border-bottom py-3" style="background-color: transparent; border-color: #30363d !important;">
        <h5 class="text-white mb-0 fw-semibold"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Watch</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('updateWatch') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" value="{{ $watch->id }}" name="id">
            <div class="mb-3">
                <label class="form-label text-secondary">Watch Name</label>
                <input type="text" name="name" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" value="{{ old('name', $watch->name) }}" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label text-secondary">Price (Rs.)</label>
                    <input type="number" step="0.01" name="price" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" min="100" value="{{ old('price', $watch->price) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-secondary">Stock</label>
                    <input type="number" name="stock" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" min="1" value="{{ old('stock', $watch->stock) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary">Description</label>
                <textarea name="description" rows="3" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;">{{ old('description', $watch->description) }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary">Featured</label>
                <select name="featured" class="form-select border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
                    <option value="no" {{ old('featured', $watch->featured) == 'no' ? 'selected' : '' }}>No</option>
                    <option value="yes" {{ old('featured', $watch->featured) == 'yes' ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label text-secondary">Current Image</label>
                <div class="rounded-3 p-3" style="background-color: #21262d;">
                    <img src="{{ asset( $watch->image) }}" class="rounded-3" alt="Current" style="max-width: 120px;">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label text-secondary">Update Image (Optional)</label>
                <input type="file" name="image" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-arrow-repeat me-2"></i>Update Watch</button>
                <a href="{{ url('/admin/watches') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
