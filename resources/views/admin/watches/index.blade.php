@extends('admin.layout')

@section('title', 'Watches')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #238636;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1">Watch Inventory</h2>
        <p class="text-secondary mb-0">Manage your watch products</p>
    </div>
    <a href="{{ route('addWatch') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i>Add New Watch
    </a>
</div>

<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="background-color: #161b22;">
                <thead style="background-color: #21262d;">
                    <tr>
                        <th class="border-0 text-secondary fw-semibold py-3 ps-4">#</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Image</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Name</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Price</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Stock</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Featured</th>
                        <th class="border-0 text-secondary fw-semibold py-3 pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allWatches as $watch)
                    <tr style="border-bottom: 1px solid #30363d;">
                        <td class="ps-4 text-white">{{ $loop->iteration }}</td>
                        <td>
                            @if($watch->image)
                                <img src="{{ asset('photos/' . $watch->image) }}" class="rounded-3" alt="{{ $watch->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #21262d;">
                                    <i class="bi bi-image text-secondary"></i>
                                </div>
                            @endif
                        </td>
                        <td class="text-white fw-medium">{{ $watch->name }}</td>
                        <td class="text-white">Rs. {{ number_format($watch->price, 0) }}</td>
                        <td>
                            @if($watch->stock > 10)
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #238636;">{{ $watch->stock }}</span>
                            @elseif($watch->stock > 0)
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #f0883e;">{{ $watch->stock }}</span>
                            @else
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #da3633;">0</span>
                            @endif
                        </td>
                        <td>
                            @if($watch->featured === 'yes')
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #8957e5;"><i class="bi bi-star-fill"></i></span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">No</span>
                            @endif
                        </td>
                        <td class="pe-4">
                            <div class="d-flex gap-2">
                                <form action="{{ route('editWatch') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $watch->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3"><i class="bi bi-pencil"></i></button>
                                </form>
                                <form action="{{ route('deleteWatch') }}" method="POST" onsubmit="return confirm('Delete this watch?');">
                                    @csrf
                                    <input type="hidden" value="{{ $watch->id }}" name="id">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
