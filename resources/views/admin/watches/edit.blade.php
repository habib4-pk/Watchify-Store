@extends('admin.layout')

@section('title', 'Edit Watch')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/watch-admin.css') }}">
@endsection

@section('content')
<h1 class="page-title mb-20">Edit Watch</h1>

<div class="form-container">
    <form action="{{ route('updateWatch') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{ $watch->id }}" name="id">

        <div class="form-group">
            <label class="form-label">Watch Name</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $watch->name) }}" required>
        </div>

        <div class="grid-2-gap">
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
            <img src="{{ asset('storage/' . $watch->image) }}" class="watch-img" alt="Current Image">
        </div>

        <div class="form-group">
            <label class="form-label">Update Image (Optional)</label>
            <input type="file" name="image" class="form-input">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit btn-warning">Update Watch</button>
            <a href="{{ url('/admin/watches') }}" class="btn-cancel">Back to List</a>
        </div>
    </form>
</div>
<script src="{{ asset('js/fields.js') }}"></script>
@endsection
