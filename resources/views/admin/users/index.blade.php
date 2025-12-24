@extends('admin.layout')

@section('title', 'Manage Users')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/orders-management.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
@endsection

@section('content')

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert-wrapper">
        <div id="success-alert" class="alert-success-box">
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button"
                    class="alert-close-btn"
                    onclick="document.getElementById('success-alert').remove()">
                &times;
            </button>
        </div>
    </div>
@endif

<div class="page-header">
    <h1 class="page-title">User Management</h1>
</div>

<div class="table-container" >
    <table class="custom-table orders-table" id="ordersTable" border="1px solid">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email Address</th>
                <th>Access Role</th>
                <th>Registered On</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($allUsers as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>

                <td class="customer-name">
                    <strong class="user-name">{{ $user->name }}</strong>
                </td>

                <td>{{ $user->email }}</td>

                <td>
                    @if($user->role === 'admin')
                        <span class="badge badge-admin">Administrator</span>
                    @else
                        <span class="badge badge-user">Standard User</span>
                    @endif
                </td>

                <td>{{ $user->created_at->format('d M Y') }}</td>

                <td>
                    @if($user->role !== 'admin')
                        <form action="{{ route('deleteUser') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" class="action-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <button type="submit" class="btn-delete">
                                Delete
                            </button>
                        </form>
                    @else
                        <span class="text-muted">System Protected</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
     <script>
    $(document).ready(function () {
        $('#ordersTable').DataTable(
        {
        "paging": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "lengthMenu": [ [2, 3, 5, 10], [2, 3, 5, 10] ],
        })
    })
</script>
</div>

@endsection
