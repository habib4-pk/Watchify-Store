@extends('buyer.layout')

@section('title', 'Session Expired')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="display-1 text-warning">419</h1>
            <h2 class="mb-4">Session Expired</h2>
            <p class="text-muted mb-4">Your session has expired. Please refresh and try again.</p>
            <a href="{{ url()->previous() }}" class="btn btn-primary me-2" onclick="event.preventDefault(); window.location.reload();">Refresh Page</a>
            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Go Home</a>
        </div>
    </div>
</div>
@endsection
