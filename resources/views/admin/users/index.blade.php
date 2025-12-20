@extends('admin.layout')

@section('title', 'Manage Users')

@section('styles')
<style>
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .page-title { font-size: 24px; font-weight: 700; color: #1e293b; }

    /* Container Card */
    .table-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    
    /* Custom Table Styling */
    .custom-table { width: 100%; border-collapse: collapse; text-align: left; }
    .custom-table th { background: #1e293b; color: #ffffff; padding: 14px 16px; font-size: 14px; font-weight: 600; }
    .custom-table td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: #475569; font-size: 14px; }
    .custom-table tr:hover { background-color: #f8fafc; }

    /* Role Badges */
    .badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .badge-admin { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-user { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    /* Action Buttons */
    .btn-delete { background-color: #ef4444; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; transition: 0.2s; font-size: 12px; }
    .btn-delete:hover { background-color: #dc2626; }
    
    .alert-success { background: #dcfce7; color: #15803d; padding: 12px 16px; border-radius: 6px; border: 1px solid #bbf7d0; margin-bottom: 20px; font-size: 14px; }
    .text-muted { color: #94a3b8; font-style: italic; }
</style>
@endsection

@section('content')

<div class="page-header">
    <h1 class="page-title">Manage Users</h1>
</div>

@if(session('success'))
    <div class="alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-card">
    <table class="custom-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($allUsers as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $user->name }}</strong></td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->role === 'admin')
                        <span class="badge badge-admin">Admin</span>
                    @else
                        <span class="badge badge-user">User</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    @if($user->role !== 'admin')
                        <form action="{{route('deleteUser')}}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
                            @csrf
                            <input type="hidden" name="id" value="{{ $user->id }}">
                            <button type="submit" class="btn-delete">
                                Delete
                            </button>
                        </form>
                    @else
                        <span class="text-muted">Protected</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection