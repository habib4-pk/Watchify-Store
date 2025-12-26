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
            
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger mb-4">
                    {{ session('error') }}
                </div>
            @endif
            
            <div class="mb-3">
                <label class="form-label text-secondary">Watch Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label text-secondary">Price (Rs.)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" min="100" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary">Stock Quantity</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" min="1" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary">Discount %</label>
                    <input type="number" name="discount_percentage" value="{{ old('discount_percentage', 0) }}" min="0" max="100" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;">
                    <small class="text-secondary">0-100% off</small>
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
                <label class="form-label text-secondary">Primary Watch Image <span class="text-danger">*</span></label>
                <input type="file" name="image" id="primaryImage" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" accept="image/*" required>
                <small class="text-secondary">Allowed: jpeg, png, jpg, webp. Max: 2MB.</small>
                <div id="primaryPreview" class="mt-2"></div>
            </div>
            <div class="mb-4">
                <label class="form-label text-secondary">Additional Images (Optional)</label>
                <input type="file" name="additional_images[]" id="additionalImages" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" accept="image/*" multiple>
                <small class="text-secondary">Select multiple images. Max 10 images, 2MB each.</small>
                <div id="additionalPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-lg me-2"></i>Save Watch</button>
                <a href="{{ route('adminDashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.image-preview-card {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
    background: #21262d;
}
.image-preview-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-preview-card .badge-primary {
    position: absolute;
    top: 4px;
    left: 4px;
    font-size: 10px;
    background: #0d6efd;
    padding: 2px 6px;
    border-radius: 4px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Primary image preview
    const primaryInput = document.getElementById('primaryImage');
    const primaryPreview = document.getElementById('primaryPreview');
    
    primaryInput.addEventListener('change', function() {
        primaryPreview.innerHTML = '';
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                primaryPreview.innerHTML = `
                    <div class="image-preview-card">
                        <span class="badge-primary">Primary</span>
                        <img src="${e.target.result}" alt="Primary Preview">
                    </div>
                `;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Additional images preview
    const additionalInput = document.getElementById('additionalImages');
    const additionalPreview = document.getElementById('additionalPreview');
    
    additionalInput.addEventListener('change', function() {
        additionalPreview.innerHTML = '';
        if (this.files) {
            Array.from(this.files).forEach((file, index) => {
                if (index >= 10) return; // Max 10 images
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-card';
                    div.innerHTML = `<img src="${e.target.result}" alt="Preview ${index + 1}">`;
                    additionalPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    });
});
</script>

@endsection

