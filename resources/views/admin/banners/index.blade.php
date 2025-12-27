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
                    <button class="btn btn-sm btn-outline-danger delete-btn w-100" data-id="{{ $banner->id }}">
                        <i class="bi bi-trash"></i> Delete
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

<!-- Toast Container for Notifications -->
<div id="toastContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>

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
                        <small class="text-secondary">Recommended: 1920x600px. Max 10MB.</small>
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
    let isUploading = false;
    let isDeleting = false; // Prevent double delete confirmation
    
    // Toast Notification Function
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();
        
        const bgColors = {
            success: '#238636',
            error: '#da3633',
            warning: '#9e6a03',
            info: '#0969da'
        };
        
        const icons = {
            success: 'bi-check-circle',
            error: 'bi-x-circle',
            warning: 'bi-exclamation-triangle',
            info: 'bi-info-circle'
        };
        
        const toastHTML = `
            <div id="${toastId}" class="toast align-items-center border-0 mb-2" role="alert" style="background-color: ${bgColors[type]}; color: white;">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center gap-2">
                        <i class="bi ${icons[type]}"></i>
                        <span>${message}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 3000 });
        toast.show();
        
        // Remove from DOM after hidden
        toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
    }
    
    // Add Banner Form
    const form = document.getElementById('addBannerForm');
    const bannersGrid = document.getElementById('bannersGrid');
    const fileInput = form.querySelector('input[name="image"]');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        // Prevent double submission
        if (isUploading) {
            console.log('Upload already in progress, ignoring...');
            return false;
        }
        
        // Validate file is selected
        if (!fileInput.files || fileInput.files.length === 0) {
            showToast('Please select an image file', 'error');
            return false;
        }
        
        isUploading = true;
        
        const btn = document.getElementById('uploadBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Uploading...';
        
        try {
            // Create FormData BEFORE disabling file input (disabled inputs are excluded from FormData)
            const formData = new FormData(this);
            fileInput.disabled = true; // Disable after FormData creation to prevent changes
            const response = await fetch('/admin/banners/store', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                body: formData
            });
            const data = await response.json();
            
            if (data.success && data.banner) {
                // Close modal
                const modalEl = document.getElementById('addBannerModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                
                // Remove empty state if present
                const emptyState = bannersGrid.querySelector('.col-12');
                if (emptyState && emptyState.querySelector('.text-secondary')) {
                    emptyState.remove();
                }
                
                // Create new banner card
                const banner = data.banner;
                const newCard = document.createElement('div');
                newCard.className = 'col-md-6 col-lg-4';
                newCard.setAttribute('data-id', banner.id);
                newCard.innerHTML = `
                    <div class="card border-0 rounded-4 h-100" style="background-color: #161b22;">
                        <div class="position-relative">
                            <img src="${banner.image_url}" class="card-img-top rounded-top-4" alt="Banner" style="height: 180px; object-fit: cover;">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success">Active</span>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            ${banner.title ? `<h6 class="text-white mb-1">${banner.title}</h6>` : ''}
                            ${banner.subtitle ? `<p class="text-secondary small mb-2">${banner.subtitle}</p>` : ''}
                            ${banner.button_text ? `<span class="badge bg-primary">${banner.button_text}</span>` : ''}
                        </div>
                        <div class="card-footer border-0 bg-transparent d-flex gap-2 p-3 pt-0">
                            <button class="btn btn-sm btn-outline-danger delete-btn w-100" data-id="${banner.id}">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                `;
                
                // Insert at beginning
                bannersGrid.insertBefore(newCard, bannersGrid.firstChild);
                
                // Reset form before showing success
                form.reset();
                
                // Show success toast
                showToast('Banner added successfully!', 'success');
            } else {
                showToast(data.message || 'Failed to upload banner', 'error');
            }
        } catch (error) {
            console.error('Upload error:', error);
            showToast('Error uploading banner. Please try again.', 'error');
        } finally {
            isUploading = false;
            btn.disabled = false;
            fileInput.disabled = false;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm d-none" role="status"></span> Upload Banner';
        }
        
        return false;
    });

    // Use Event Delegation for Delete button
    // This ensures dynamically added buttons work correctly
    bannersGrid.addEventListener('click', async function(e) {
        const deleteBtn = e.target.closest('.delete-btn');
        
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();
            
            // Prevent double confirmation dialog
            if (isDeleting) {
                console.log('Delete already in progress, ignoring...');
                return;
            }
            
            if (!confirm('Are you sure you want to delete this banner? This action cannot be undone.')) return;
            
            isDeleting = true;
            const id = deleteBtn.dataset.id;
            const card = deleteBtn.closest('[data-id]');
            
            // Show loading state
            deleteBtn.disabled = true;
            const originalHTML = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
            
            try {
                // Use FormData instead of JSON for Laravel web routes
                const formData = new FormData();
                formData.append('id', id);
                
                const response = await fetch('/admin/banners/delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Animate removal
                    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    
                    setTimeout(() => {
                        card.remove();
                        
                        // Check if this was the last banner
                        const remainingBanners = bannersGrid.querySelectorAll('[data-id]');
                        if (remainingBanners.length === 0) {
                            // Show empty state
                            const emptyStateHTML = `
                                <div class="col-12">
                                    <div class="text-center py-5 text-secondary">
                                        <i class="bi bi-image fs-1 mb-3 d-block opacity-50"></i>
                                        <p>No banners yet. Add your first hero banner!</p>
                                    </div>
                                </div>
                            `;
                            bannersGrid.innerHTML = emptyStateHTML;
                        }
                        
                        // Reset delete flag
                        isDeleting = false;
                    }, 300);
                    
                    showToast('Banner deleted successfully!', 'success');
                } else {
                    showToast(data.message || 'Failed to delete banner', 'error');
                    deleteBtn.innerHTML = originalHTML;
                    deleteBtn.disabled = false;
                    isDeleting = false;
                }
            } catch (error) {
                console.error('Delete error:', error);
                showToast('Error deleting banner. Please try again.', 'error');
                deleteBtn.innerHTML = originalHTML;
                deleteBtn.disabled = false;
                isDeleting = false;
            }
        }
    });
    
    // Reset form when modal closes
    const modalEl = document.getElementById('addBannerModal');
    modalEl.addEventListener('hidden.bs.modal', function() {
        isUploading = false;
        form.reset();
        const btn = document.getElementById('uploadBtn');
        const fileInputEl = form.querySelector('input[name="image"]');
        btn.disabled = false;
        fileInputEl.disabled = false;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm d-none" role="status"></span> Upload Banner';
    });
    
    // Prevent form submission on Enter key in text fields (extra safety)
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            return false;
        }
    });
});
</script>

@endsection
