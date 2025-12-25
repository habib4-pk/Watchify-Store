@extends('buyer.layout')

@section('title', 'Secure Checkout')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/cart-checkout.css') }}">
<link rel="stylesheet" href="{{ asset('css/alert.css') }}">
<style>
    /* Validation Styles */
    .field-group.has-error input {
        border-color: #dc3545;
        background-color: #fff5f5;
    }
    
    .field-group.has-error label {
        color: #dc3545;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    .field-hint {
        color: #6c757d;
        font-size: 0.8rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    .required-star {
        color: #dc3545;
        margin-left: 2px;
    }
    
    input:invalid:not(:placeholder-shown) {
        border-color: #dc3545;
    }
    
    input:valid:not(:placeholder-shown) {
        border-color: #28a745;
    }
</style>
@endsection

@section('content')

@if(session('success'))
<div class="alert-container">
    <div id="success-alert" class="alert-success">
        <span>{{ session('success') }}</span>
        <button type="button" class="alert-close" onclick="document.getElementById('success-alert').remove()">&times;</button>
    </div>
</div>
@endif

@if(session('error'))
<div class="alert-container">
    <div id="error-alert" class="alert-error">
        <span>{{ session('error') }}</span>
        <button type="button" class="alert-close" onclick="document.getElementById('error-alert').remove()">&times;</button>
    </div>
</div>
@endif

{{-- Display All Validation Errors --}}
@if($errors->any())
<div class="alert-container">
    <div id="validation-alert" class="alert-error">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 10px 0 0 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="alert-close" onclick="document.getElementById('validation-alert').remove()">&times;</button>
    </div>
</div>
@endif

<div class="checkout-wrapper checkout-stacked">
    <!-- Order Summary on Top -->
    <div class="order-summary-top">
        <h3 class="section-title"><i class="fas fa-shopping-bag"></i> Order Summary</h3>
        
        <ul class="order-items-list">
            @foreach ($cart as $item)
            <li class="summary-item">
                <img src="{{ asset('photos/' . $item->watch->image) }}" alt="{{ $item->watch->name }}">
                <div class="summary-item-info">
                    <p>{{ $item->watch->name }}</p>
                    <span class="item-qty">Qty: {{ $item->quantity }}</span>
                </div>
                <div class="item-price">
                    Rs. {{ number_format($item->watch->price * $item->quantity, 2) }}
                </div>
            </li>
            @endforeach
        </ul>

        <div class="summary-total-box">
            <div class="summary-detail-row">
                <span>Subtotal</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
            <div class="summary-detail-row">
                <span>Shipping</span>
                <span class="complimentary-text">Complimentary</span>
            </div>
            <div class="total-row">
                <span>Total</span>
                <span>Rs. {{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Method Section -->
    <div class="payment-method-section">
        <h3 class="section-title"><i class="fas fa-credit-card"></i> Payment Method</h3>
        <p class="payment-text"><i class="fas fa-money-bill-wave"></i> Cash on Delivery</p>
    </div>

    <!-- Shipping Form Below -->
    <div class="form-section">
        <h3 class="section-title"><i class="fas fa-truck"></i> Shipping Information</h3>
        
        <form action="{{ route('placeOrder') }}" method="POST" id="checkoutForm" novalidate>
            @csrf
            <input type="hidden" name="payment_method" value="cod">
            
            <div class="input-grid">
                {{-- Full Name --}}
                <div class="field-group full-width @error('customer_name') has-error @enderror">
                    <label for="customer_name">
                        Full Name<span class="required-star">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="customer_name" 
                        name="customer_name" 
                        value="{{ old('customer_name') }}" 
                        required
                        minlength="3"
                        maxlength="100"
                        pattern="^[a-zA-Z\s]+$"
                        placeholder="e.g. Umer Nisar"
                        title="Only letters and spaces allowed (3-100 characters)">
                    @error('customer_name')
                        <span class="error-message">{{ $message }}</span>
                    @else
                        <span class="field-hint">Only letters and spaces (3-100 characters)</span>
                    @enderror
                </div>

                {{-- Street Address --}}
                <div class="field-group full-width @error('street_address') has-error @enderror">
                    <label for="street_address">
                        Street Address<span class="required-star">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="street_address" 
                        name="street_address" 
                        value="{{ old('street_address') }}" 
                        required
                        minlength="10"
                        maxlength="255"
                        placeholder="House #, Street name, Area"
                        title="Enter complete address (minimum 10 characters)">
                    @error('street_address')
                        <span class="error-message">{{ $message }}</span>
                    @else
                        <span class="field-hint">Minimum 10 characters required</span>
                    @enderror
                </div>

                {{-- City --}}
                <div class="field-group @error('city') has-error @enderror">
                    <label for="city">
                        City<span class="required-star">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="city" 
                        name="city" 
                        value="{{ old('city') }}" 
                        required
                        minlength="2"
                        maxlength="100"
                        pattern="^[a-zA-Z\s]+$"
                        placeholder="e.g. Wah Cantt"
                        title="Only letters and spaces allowed">
                    @error('city')
                        <span class="error-message">{{ $message }}</span>
                    @else
                        <span class="field-hint">Letters and spaces only</span>
                    @enderror
                </div>

                {{-- Postal Code --}}
                <div class="field-group @error('postal_code') has-error @enderror">
                    <label for="postal_code">
                        Postal Code<span class="required-star">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="postal_code" 
                        name="postal_code" 
                        value="{{ old('postal_code') }}" 
                        required
                        minlength="4"
                        maxlength="10"
                        pattern="^[a-zA-Z0-9\s\-]+$"
                        placeholder="e.g. 47040"
                        title="4-10 characters (letters, numbers, spaces, hyphens)">
                    @error('postal_code')
                        <span class="error-message">{{ $message }}</span>
                    @else
                        <span class="field-hint">4-10 alphanumeric characters</span>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div class="field-group full-width @error('phone_number') has-error @enderror">
                    <label for="phone_number">
                        Phone Number<span class="required-star">*</span>
                    </label>
                    <input 
                        type="tel" 
                        id="phone_number" 
                        name="phone_number" 
                        value="{{ old('phone_number') }}" 
                        required
                        minlength="10"
                        maxlength="15"
                        pattern="^[0-9+\-\s()]+$"
                        placeholder="03XX-XXXXXXX or +92 3XX XXXXXXX"
                        title="10-15 digits (can include +, -, spaces, parentheses)">
                    @error('phone_number')
                        <span class="error-message">{{ $message }}</span>
                    @else
                        <span class="field-hint">10-15 digits (format: +92 3XX XXXXXXX)</span>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-place-order" id="submitBtn">
                <i class="fas fa-lock"></i> Complete Purchase
            </button>
        </form>
        
        <p class="secure-text">🔒 Secure SSL Encrypted Checkout</p>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Real-time validation
    const inputs = form.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('has-error')) {
                validateField(this);
            }
        });
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fix all errors before submitting.');
            return false;
        }
        
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
    });
    
    function validateField(field) {
        const fieldGroup = field.closest('.field-group');
        const errorSpan = fieldGroup.querySelector('.error-message');
        
        // Remove existing error state
        fieldGroup.classList.remove('has-error');
        if (errorSpan && !errorSpan.classList.contains('field-hint')) {
            errorSpan.remove();
        }
        
        // Check validity
        if (!field.validity.valid) {
            fieldGroup.classList.add('has-error');
            
            let errorMessage = '';
            
            if (field.validity.valueMissing) {
                errorMessage = 'This field is required.';
            } else if (field.validity.tooShort) {
                errorMessage = `Minimum ${field.minLength} characters required.`;
            } else if (field.validity.tooLong) {
                errorMessage = `Maximum ${field.maxLength} characters allowed.`;
            } else if (field.validity.patternMismatch) {
                errorMessage = field.title || 'Invalid format.';
            }
            
            // Create error message element
            if (errorMessage) {
                const hint = fieldGroup.querySelector('.field-hint');
                if (hint) {
                    const error = document.createElement('span');
                    error.className = 'error-message';
                    error.textContent = errorMessage;
                    hint.replaceWith(error);
                }
            }
            
            return false;
        }
        
        return true;
    }
    
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[id$="-alert"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection

@endsection