@extends('admin.layout')

@section('title', 'Add New Watch')

@section('styles')
    @include('admin.watches.form_styles') {{-- Optional: move common styles to a partial or paste here --}}
@endsection

@section('content')
<h1 class="page-title" style="margin-bottom: 20px;">Add New Watch</h1>

@if ($errors->any())
    <div class="error-box">
        <strong>Please fix the following errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-container">
    <form action="{{ route('storeWatch') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Watch Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Price (Rs.)</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock" value="{{ old('stock') }}" class="form-input" required>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" rows="4" class="form-input">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Watch Image</label>
            <input type="file" name="image" class="form-input" accept="image/*" required>
            <p style="font-size: 12px; color: #64748b; margin-top: 5px;">Allowed: jpeg, png, jpg. Max: 2MB.</p>
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn-submit">Save Watch</button>
            <a href="{{ route('adminDashboard') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection