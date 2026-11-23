@extends('layouts.app')

@section('title', 'Deleted Users')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Deleted Users</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-sari-dark mb-1">Deleted Users</h1>
        <p class="text-muted mb-0">Deleted accounts are kept here until you restore or permanently delete them.</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-light mt-2 mt-sm-0">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
</div>

@if (! auth()->user()->isManager())
    <div class="alert alert-warning">
        <i class="bi bi-shield-lock"></i> You are viewing as <strong>Staff</strong>. Only a manager can restore or permanently delete user accounts.
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold"><i class="bi bi-trash"></i> Trashed Accounts
            <span class="badge bg-secondary">{{ $users->total() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if ($users->isEmpty())
            <div class="alert alert-light m-3 mb-0">
                <i class="bi bi-check-circle"></i> No deleted users. All accounts are active.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Deleted</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $user->isManager() ? 'warning text-dark' : 'info' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="small text-muted">{{ $user->deleted_at->format('M d, Y h:i A') }}</td>
                                <td class="text-end">
                                    @if (auth()->user()->isManager())
                                        <form method="POST" action="{{ route('users.restore', $user->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('users.force-delete', $user->id) }}" class="d-inline"
                                              onsubmit="return confirm('Permanently delete {{ addslashes($user->name) }}? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete permanently">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Manager access required</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if ($users->hasPages())
        <div class="card-footer bg-white">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
