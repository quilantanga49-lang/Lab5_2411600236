@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-sari-dark mb-1">Users</h1>
        <p class="text-muted mb-0">Manage accounts, roles, and access for your sari-sari store.</p>
    </div>
    @if (auth()->user()->isManager())
        <a href="{{ route('users.trash') }}" class="btn btn-light mt-2 mt-sm-0">
            <i class="bi bi-trash"></i> Deleted Users
            @if ($deletedCount > 0) <span class="badge bg-danger">{{ $deletedCount }}</span> @endif
        </a>
    @endif
</div>

@if (! auth()->user()->isManager())
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> You are viewing as <strong>Staff</strong>. Only a manager can change roles, status, or delete user accounts.
    </div>
@endif

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-8">
                <input type="search" name="search" value="{{ $search }}"
                       class="form-control" placeholder="Search by name, email, or role...">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-outline-sari flex-fill">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-light">Clear</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-people"></i> User Accounts
            <span class="badge bg-sari">{{ $users->total() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Email Verified</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="stat-icon bg-{{ $user->isManager() ? 'orange' : 'green' }}"
                                          style="width:34px;height:34px;font-size:14px;">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <div>
                                        <div class="fw-semibold">
                                            {{ $user->name }}
                                            @if ($user->id === auth()->id())
                                                <span class="badge bg-info text-dark ms-1">You</span>
                                            @endif
                                        </div>
                                        <div class="text-muted small">ID #{{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if (auth()->user()->isManager())
                                    <form method="POST" action="{{ route('users.role', $user) }}" class="d-flex align-items-center gap-1">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="form-select form-select-sm" style="width:auto"
                                                onchange="this.form.submit()" aria-label="Role for {{ $user->name }}">
                                            <option value="manager" {{ $user->isManager() ? 'selected' : '' }}>Manager</option>
                                            <option value="staff" {{ $user->isStaff() ? 'selected' : '' }}>Staff</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="badge bg-{{ $user->isManager() ? 'warning text-dark' : 'info' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if ($user->email_verified_at)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Verified</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-clock"></i> Not Verified</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->isActive() ? 'success' : 'danger' }}">
                                    <i class="bi bi-{{ $user->isActive() ? 'check-circle' : 'x-circle' }}"></i>
                                    {{ $user->isActive() ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if (auth()->user()->isManager())
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.status', $user) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                    title="{{ $user->isActive() ? 'Deactivate' : 'Activate' }}">
                                                <i class="bi bi-{{ $user->isActive() ? 'pause-circle' : 'play-circle' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                                              onsubmit="return confirm('Move {{ addslashes($user->name) }} to Trash? You can restore the account later.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Move to Trash">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Current account</span>
                                    @endif
                                @else
                                    <span class="text-muted small">View only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($users->hasPages())
        <div class="card-footer bg-white">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
