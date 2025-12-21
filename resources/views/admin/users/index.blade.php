@extends('admin.layout')

@section('title', 'Manage Users')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">

<style>
    /* --- Admin Layout Styling --- */
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .page-title { font-size: 24px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 1px; }

    /* --- Manual Dismiss Success Alert --- */
    .alert-success { 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
        padding: 15px 20px; 
        background: #dcfce7; 
        color: #15803d; 
        border-radius: 8px; 
        margin-bottom: 25px; 
        border: 1px solid #bbf7d0; 
        font-size: 14px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .close-btn { 
        background: none; 
        border: none; 
        color: #15803d; 
        font-size: 24px; 
        font-weight: bold; 
        cursor: pointer; 
        line-height: 1; 
        padding: 0;
        margin-left: 20px;
        transition: transform 0.2s ease;
    }
    .close-btn:hover { transform: scale(1.2); opacity: 0.7; }

    /* --- Container and Table Styling --- */
    .table-card { 
        background: white; 
        border-radius: 8px; 
        overflow: hidden; 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); 
        border: 1px solid #e2e8f0; 
    }
    
    .custom-table { width: 100%; border-collapse: collapse; text-align: left; }
    
    .custom-table th { 
        background: #1e293b; 
        color: #ffffff; 
        padding: 18px 15px; 
        font-size: 11px; 
        font-weight: 600; 
        text-transform: uppercase; 
        letter-spacing: 1.5px; 
    }
    
    .custom-table td { 
        padding: 18px 15px; 
        border-bottom: 1px solid #f1f5f9; 
        color: #475569; 
        font-size: 14px; 
    }
    
    .custom-table tr:last-child td { border-bottom: none; }
    .custom-table tr:hover { background-color: #f8fafc; }

    /* --- Role Badges --- */
    .badge { 
        padding: 6px 12px; 
        border-radius: 4px; 
        font-size: 10px; 
        font-weight: 800; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        display: inline-block; 
    }
    
    .badge-admin { background-color: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-user { background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    /* --- Action Buttons --- */
    .btn-delete { 
        background-color: #ef4444; 
        color: white; 
        border: none; 
        padding: 8px 14px; 
        border-radius: 4px; 
        cursor: pointer; 
        transition: background 0.2s; 
        font-size: 11px; 
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .btn-delete:hover { background-color: #dc2626; }
    
    .text-muted { color: #94a3b8; font-size: 12px; font-style: italic; font-weight: 500; }
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
    <h1 class="page-title">User Management</h1>
</div>

<div class="table-card">
    <table class="custom-table">
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
                <td><strong style="color: #1e293b;">{{ $user->name }}</strong></td>
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
                        <form action="{{ route('deleteUser') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
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
</div>

@endsection