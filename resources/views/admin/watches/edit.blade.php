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
                <div class="col-md-4">
                    <label class="form-label text-secondary">Price (Rs.)</label>
                    <input type="number" step="0.01" name="price" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" min="100" value="{{ old('price', $watch->price) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary">Stock</label>
                    <input type="number" name="stock" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" min="0" value="{{ old('stock', $watch->stock) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-secondary">Discount %</label>
                    <input type="number" name="discount_percentage" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" min="0" max="100" value="{{ old('discount_percentage', $watch->discount_percentage ?? 0) }}">
                    <small class="text-secondary">0-100% off</small>
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
            
            <!-- Image Gallery Section -->
            <div class="mb-4">
                <label class="form-label text-secondary d-flex justify-content-between align-items-center">
                    <span>Product Images</span>
                    <small class="text-muted">Drag to reorder • Click star to set primary</small>
                </label>
                <div class="rounded-3 p-3" style="background-color: #21262d;">
                    <div id="imageGallery" class="d-flex flex-wrap gap-3">
                        @forelse($watch->images as $img)
                            <div class="image-card" data-id="{{ $img->id }}" draggable="true">
                                <img src="{{ $img->image_url }}" alt="Watch Image">
                                @if($img->is_primary)
                                    <span class="badge-primary-img"><i class="bi bi-star-fill"></i></span>
                                @else
                                    <button type="button" class="btn-set-primary" data-id="{{ $img->id }}" title="Set as primary">
                                        <i class="bi bi-star"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn-delete-img" data-id="{{ $img->id }}" title="Delete image">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        @empty
                            <!-- Fallback to main image column -->
                            @if($watch->image)
                                <div class="image-card no-actions">
                                    <img src="{{ $watch->image }}" alt="Current Image">
                                    <span class="badge-primary-img"><i class="bi bi-star-fill"></i></span>
                                </div>
                            @else
                                <p class="text-muted m-0">No images uploaded yet.</p>
                            @endif
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Add More Images -->
            <div class="mb-4">
                <label class="form-label text-secondary">Add More Images</label>
                <input type="file" name="additional_images[]" id="additionalImages" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" accept="image/*" multiple>
                <small class="text-secondary">Select multiple images to add. Max 2MB each.</small>
                <div id="additionalPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
            </div>
            
            <!-- Update Primary Image -->
            <div class="mb-4">
                <label class="form-label text-secondary">Replace Primary Image (Optional)</label>
                <input type="file" name="image" class="form-control border-0 rounded-3" style="background-color: #21262d; color: #fff;" accept="image/*">
                <small class="text-secondary">Upload a new file to replace the current primary image.</small>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><i class="bi bi-arrow-repeat me-2"></i>Update Watch</button>
                <a href="{{ url('/admin/watches') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.image-card {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    background: #0d1117;
    cursor: grab;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.image-card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
}
.image-card.dragging {
    opacity: 0.5;
    transform: scale(1.1);
}
.image-card.no-actions {
    cursor: default;
}
.image-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.badge-primary-img {
    position: absolute;
    top: 6px;
    left: 6px;
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #000;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
}
.btn-set-primary {
    position: absolute;
    top: 6px;
    left: 6px;
    background: rgba(255,255,255,0.2);
    border: none;
    color: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s ease;
}
.image-card:hover .btn-set-primary {
    opacity: 1;
}
.btn-set-primary:hover {
    background: linear-gradient(135deg, #ffc107, #ff9800);
    color: #000;
}
.btn-delete-img {
    position: absolute;
    top: 6px;
    right: 6px;
    background: rgba(220, 53, 69, 0.8);
    border: none;
    color: #fff;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s ease;
}
.image-card:hover .btn-delete-img {
    opacity: 1;
}
.btn-delete-img:hover {
    background: #dc3545;
}
.image-preview-card {
    position: relative;
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    background: #21262d;
}
.image-preview-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const watchId = {{ $watch->id }};
    const csrfToken = '{{ csrf_token() }}';
    let isProcessingPreview = false;
    
    // Preview additional images before upload
    const additionalInput = document.getElementById('additionalImages');
    const additionalPreview = document.getElementById('additionalPreview');
    
    additionalInput.addEventListener('change', function() {
        // Prevent duplicate processing
        if (isProcessingPreview) return;
        
        isProcessingPreview = true;
        additionalPreview.innerHTML = '';
        
        if (this.files && this.files.length > 0) {
            Array.from(this.files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'image-preview-card';
                    div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    additionalPreview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
        
        // Reset flag after a short delay to allow next selection
        setTimeout(() => { isProcessingPreview = false; }, 100);
    });
    
    // Set Primary Image
    document.querySelectorAll('.btn-set-primary').forEach(btn => {
        btn.addEventListener('click', async function() {
            const imageId = this.dataset.id;
            try {
                // Use FormData instead of JSON for Laravel web routes
                const formData = new FormData();
                formData.append('image_id', imageId);
                
                const response = await fetch('/admin/product-images/set-primary', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to set primary image');
                }
            } catch (error) {
                console.error('Error setting primary image:', error);
                alert('Error setting primary image');
            }
        });
    });
    
    // Delete Image
    document.querySelectorAll('.btn-delete-img').forEach(btn => {
        btn.addEventListener('click', async function() {
            if (!confirm('Are you sure you want to delete this image?')) return;
            
            const imageId = this.dataset.id;
            try {
                // Use FormData instead of JSON for Laravel web routes
                const formData = new FormData();
                formData.append('image_id', imageId);
                
                const response = await fetch('/admin/product-images/delete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    this.closest('.image-card').remove();
                } else {
                    alert(data.message || 'Failed to delete image');
                }
            } catch (error) {
                console.error('Error deleting image:', error);
                alert('Error deleting image');
            }
        });
    });
    
    // Drag and Drop Reordering
    const gallery = document.getElementById('imageGallery');
    let draggedItem = null;
    
    gallery.querySelectorAll('.image-card[draggable="true"]').forEach(card => {
        card.addEventListener('dragstart', function(e) {
            draggedItem = this;
            this.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });
        
        card.addEventListener('dragend', function() {
            this.classList.remove('dragging');
            updateImageOrder();
        });
        
        card.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        });
        
        card.addEventListener('drop', function(e) {
            e.preventDefault();
            if (draggedItem !== this) {
                const allCards = [...gallery.querySelectorAll('.image-card')];
                const draggedIndex = allCards.indexOf(draggedItem);
                const targetIndex = allCards.indexOf(this);
                
                if (draggedIndex < targetIndex) {
                    this.after(draggedItem);
                } else {
                    this.before(draggedItem);
                }
            }
        });
    });
    
    async function updateImageOrder() {
        const order = [...gallery.querySelectorAll('.image-card[data-id]')]
            .map(card => parseInt(card.dataset.id));
        
        if (order.length === 0) return;
        
        try {
            // Use FormData instead of JSON for Laravel web routes
            const formData = new FormData();
            formData.append('watch_id', watchId);
            order.forEach((id, index) => {
                formData.append(`order[${index}]`, id);
            });
            
            const response = await fetch('/admin/product-images/reorder', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await response.json();
            if (!data.success) {
                console.error('Failed to update order:', data.message);
            }
        } catch (error) {
            console.error('Error updating order:', error);
        }
    }
});
</script>
@endsection
