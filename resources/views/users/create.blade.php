@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="breadcrumb-item active">Add User</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-sari-dark mb-1">Add User</h1>
        <p class="text-muted mb-0">Create a new manager or staff account for your store.</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-light mt-2 mt-sm-0">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-person-plus"></i> New Account Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   placeholder="e.g. Maria Cashier" required autofocus>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   placeholder="e.g. cashier@sari.com" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="staff" {{ old('role', 'staff') === 'staff' ? 'selected' : '' }}>
                                    Staff — can manage products and stock
                                </option>
                                <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>
                                    Manager — full access incl. users and trash
                                </option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="At least 8 characters" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm password <span class="text-danger">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control" placeholder="Repeat the password" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Account options</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_verified"
                                       id="email_verified" value="1"
                                       {{ old('email_verified', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="email_verified">
                                    Email verified
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active"
                                       id="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active account
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-sari">
                            <i class="bi bi-person-plus"></i> Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
