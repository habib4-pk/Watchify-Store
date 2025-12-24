@extends('admin.layout')

@section('title', 'Users')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 mb-4" role="alert" style="background-color: #238636;">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold text-white mb-1">User Management</h2>
        <p class="text-secondary mb-0">Manage registered users and their access</p>
    </div>
</div>

<div class="card border-0 rounded-4" style="background-color: #161b22;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle mb-0" style="background-color: #161b22;">
                <thead style="background-color: #21262d;">
                    <tr>
                        <th class="border-0 text-secondary fw-semibold py-3 ps-4">#</th>
                        <th class="border-0 text-secondary fw-semibold py-3">User</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Email</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Role</th>
                        <th class="border-0 text-secondary fw-semibold py-3">Joined</th>
                        <th class="border-0 text-secondary fw-semibold py-3 pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allUsers as $user)
                    <tr style="border-bottom: 1px solid #30363d;">
                        <td class="ps-4 text-white">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #8957e5, #a371f7);">
                                    <span class="text-white fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                                <span class="text-white fw-medium">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #da3633;"><i class="bi bi-shield-check me-1"></i>Admin</span>
                            @else
                                <span class="badge rounded-pill px-3 py-2" style="background-color: #1f6feb;"><i class="bi bi-person me-1"></i>User</span>
                            @endif
                        </td>
                        <td class="text-secondary">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="pe-4">
                            @if($user->role !== 'admin')
                                <form action="{{ route('deleteUser') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="bi bi-trash me-1"></i>Delete</button>
                                </form>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2"><i class="bi bi-lock me-1"></i>Protected</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
