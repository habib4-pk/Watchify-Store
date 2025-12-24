@extends('admin.layout')

@section('title', 'Manage Watches')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/watch-admin.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

@endsection

@section('content')

@if(session('success'))
    <div class="alert-wrapper">
        <div id="success-alert" class="alert-success">
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button" class="close-btn" onclick="document.getElementById('success-alert').remove()">
                &times;
            </button>
        </div>
    </div>
@endif

<div class="page-header">
    <h1 class="page-title">Manage Watches</h1>
    <a href="{{ route('addWatch') }}" class="btn-add">Add New Watch</a>
</div>

<div class="table-container">
    <table class="custom-table" id="items-table" border="1px solod">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Name</th>
                <th>Price (Rs.)</th>
                <th>Stock</th>
                <th>Featured</th>
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
                <td>{{ $watch->featured === 'yes' ? 'Yes' : 'No' }}</td>
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
    <script>
    $(document).ready(function () {
        $('#items-table').DataTable(
        {
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": false,
        "lengthMenu": [ [ 3, 5, 10], [ 3, 5, 10] ],
        })
    })
</script>
</div>
@endsection
