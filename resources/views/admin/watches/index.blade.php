@extends('admin.layout')

@section('title', 'Manage Watches')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-title { font-size: 24px; font-weight: 700; color: #1e293b; }
    
    .btn-add { background: #3b82f6; color: white; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; transition: 0.3s; }
    .btn-add:hover { background: #2563eb; }

    /* Table Styling */
    .table-container { background: white; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden; }
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { background: #1e293b; color: white; text-align: left; padding: 15px; font-size: 14px; }
    .custom-table td { padding: 15px; border-bottom: 1px solid #f1f5f9; font-size: 14px; color: #475569; }
    
    .watch-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0; }
    .desc-text { max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Action Buttons */
    .actions { display: flex; gap: 8px; }
    .btn-edit { background: #f59e0b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
    .btn-delete { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
    
    .alert-success { background: #dcfce7; color: #15803d; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #bbf7d0; }
</style>
@endsection

@section('content')

@if(session('success'))
    <div style="max-width: 500px; margin: 0 auto 25px; padding: 0 15px;">
        <div id="success-alert" class="alert-success" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 20px; background: #dcfce7; color: #15803d; border-radius: 8px; border: 1px solid #bbf7d0; font-size: 14px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <span>{{ session('success') }}</span>
            <button type="button" class="close-btn" onclick="document.getElementById('success-alert').remove()" style="background: none; border: none; color: #15803d; font-size: 24px; font-weight: bold; cursor: pointer; line-height: 1; padding: 0; margin-left: 20px; transition: transform 0.2s ease;">&times;</button>
        </div>
    </div>
@endif

<div class="page-header">
    <h1 class="page-title">Manage Watches</h1>
    <a href="{{ route('addWatch') }}" class="btn-add">Add New Watch</a>
</div>



<div class="table-container">
    <table class="custom-table">
        <thead>
    <tr>
        <th>#</th>
        <th>Image</th>
        <th>Name</th>
        <th>Price (Rs.)</th>
        <th>Stock</th>
        <th>Featured</th> {{-- New column header --}}
        <th>Description</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    @foreach($allWatches as $watch)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            @if($watch->image)
                <img src="{{ asset('storage/' . $watch->image) }}" class="watch-img" alt="Watch">
            @endif
        </td>
        <td><strong>{{ $watch->name }}</strong></td>
        <td>{{ number_format($watch->price, 2) }}</td>
        <td>{{ $watch->stock }}</td>
        <td>{{ $watch->featured === 'yes' ? 'Yes' : 'No' }}</td> {{-- Display Yes or No --}}
        <td class="desc-text">{{ $watch->description }}</td>
        <td class="actions">
            <form action="{{ route('editWatch') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $watch->id }}">
                <button type="submit" class="btn-edit">Edit</button>
            </form>

            <form action="{{ route('deleteWatch') }}" method="POST" onsubmit="return confirm('Delete this watch?');">
                @csrf
                <input type="hidden" value="{{$watch->id}}" name="id">
                <button type="submit" class="btn-delete">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</tbody>

    </table>
</div>
@endsection