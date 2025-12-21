@extends('admin.layout')

@section('title', 'Edit Watch')

@section('styles')
    @include('admin.watches.form_styles') {{-- Optional: move common styles to a partial or paste here --}}
@endsection

@section('content')
<h1 class="page-title" style="margin-bottom: 20px;">Edit Watch</h1>

<div class="form-container">
    <form action="{{ route('updateWatch') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{ $watch->id }}" name="id">

        <div class="form-group">
            <label class="form-label">Watch Name</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $watch->name) }}" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Price (Rs.)</label>
                <input type="number" step="0.01" name="price" class="form-input" value="{{ old('price', $watch->price) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stock</label>
                <input type="number" name="stock" class="form-input" value="{{ old('stock', $watch->stock) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-input">{{ old('description', $watch->description) }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Featured</label>
            <select name="featured" class="form-input" required>
                <option value="no" {{ old('featured', $watch->featured) == 'no' ? 'selected' : '' }}>No</option>
                <option value="yes" {{ old('featured', $watch->featured) == 'yes' ? 'selected' : '' }}>Yes</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Current Image</label>
            <img src="{{ asset('storage/' . $watch->image) }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
        </div>

        <div class="form-group">
            <label class="form-label">Update Image (Optional)</label>
            <input type="file" name="image" class="form-input">
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-submit" style="background: #f59e0b;">Update Watch</button>
            <a href="{{ url('/admin/watches') }}" class="btn-cancel">Back to List</a>
        </div>
    </form>
</div>
@endsection
