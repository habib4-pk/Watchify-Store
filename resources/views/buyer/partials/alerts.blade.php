{{-- 
    Alerts Partial
    Flash message alerts for inline display (not global fixed position)
    Used by: pages that need inline alerts instead of global floating ones
--}}

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
