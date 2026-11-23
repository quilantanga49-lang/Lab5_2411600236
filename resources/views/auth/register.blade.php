<x-guest-layout>
    <x-slot:title>Register</x-slot:title>

    <h4 class="mb-1 fw-bold text-sari-dark">Create account</h4>
    <p class="text-muted small mb-4">Register a new store user.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus autocomplete="name">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autocomplete="username">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-sari w-100">
            <i class="bi bi-person-plus"></i> Create account
        </button>
    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        Already registered? <a href="{{ route('login') }}" class="text-decoration-none">Log in</a>
    </p>
</x-guest-layout>
