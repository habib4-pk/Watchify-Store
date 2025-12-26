@extends('admin.layout')

@section('title', 'Hero Banners')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #238636;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1">Hero Banners</h2>
        <p class="text-secondary mb-0">Manage your homepage slideshow banners</p>
    </div>
    <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addBannerModal">
        <i class="bi bi-plus-lg"></i>
        <span>Add Banner</span>
    </button>
</div>

<!-- Banners Grid -->
<div class="row g-4" id="bannersGrid">
    @forelse($banners as $banner)
        <div class="col-md-6 col-lg-4" data-id="{{ $banner->id }}">
            <div class="card border-0 rounded-4 h-100" style="background-color: #161b22;">
                <div class="position-relative">
                    <img src="{{ $banner->image_url }}" class="card-img-top rounded-top-4" alt="Banner" style="height: 180px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-3">
                    @if($banner->title)
                        <h6 class="text-white mb-1">{{ $banner->title }}</h6>
                    @endif
                    @if($banner->subtitle)
                        <p class="text-secondary small mb-2">{{ Str::limit($banner->subtitle, 50) }}</p>
                    @endif
                    @if($banner->button_text)
                        <span class="badge bg-primary">{{ $banner->button_text }}</span>
                    @endif
                </div>
                <div class="card-footer border-0 bg-transparent d-flex gap-2 p-3 pt-0">
                    <button class="btn btn-sm btn-outline-light flex-grow-1 toggle-btn" data-id="{{ $banner->id }}">
                        <i class="bi {{ $banner->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                        {{ $banner->is_active ? 'Hide' : 'Show' }}
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $banner->id }}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5 text-secondary">
                <i class="bi bi-image fs-1 mb-3 d-block opacity-50"></i>
                <p>No banners yet. Add your first hero banner!</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Add Banner Modal -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background-color: #161b22;">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white">Add Hero Banner</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBannerForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary">Banner Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" accept="image/*" required>
                        <small class="text-secondary">Recommended: 1920x600px. Max 5MB.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary">Title (Optional)</label>
                        <input type="text" name="title" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" placeholder="e.g., Summer Collection">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary">Subtitle (Optional)</label>
                        <input type="text" name="subtitle" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" placeholder="e.g., Up to 50% off">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label text-secondary">Button Text</label>
                            <input type="text" name="button_text" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" placeholder="e.g., Shop Now">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary">Button Link</label>
                            <input type="text" name="button_link" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" placeholder="e.g., /shop/featured">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                        Upload Banner
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';
    
    // Add Banner Form
    document.getElementById('addBannerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('uploadBtn');
        const spinner = btn.querySelector('.spinner-border');
        
        btn.disabled = true;
        spinner.classList.remove('d-none');
        
        try {
            const formData = new FormData(this);
            const response = await fetch('/admin/banners/store', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to upload banner');
            }
        } catch (error) {
            alert('Error uploading banner');
        } finally {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    });
    
    // Toggle Active
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const id = this.dataset.id;
            try {
                const response = await fetch('/admin/banners/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id })
                });
                const data = await response.json();
                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                alert('Error toggling banner');
            }
        });
    });
    
    // Delete Banner
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (!confirm('Delete this banner?')) return;
            const id = this.dataset.id;
            try {
                const response = await fetch('/admin/banners/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ id })
                });
                const data = await response.json();
                if (data.success) {
                    this.closest('.col-md-6').remove();
                }
            } catch (error) {
                alert('Error deleting banner');
            }
        });
    });
});
</script>

@endsection
