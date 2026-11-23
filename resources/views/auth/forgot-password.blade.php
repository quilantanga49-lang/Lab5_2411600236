<x-guest-layout>
    <x-slot:title>Forgot password</x-slot:title>

    <h4 class="mb-1 fw-bold text-sari-dark">Reset your password</h4>
    <p class="text-muted small mb-4">Enter your email and we will send you a reset link.</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
        </div>

        <button type="submit" class="btn btn-sari w-100">
            <i class="bi bi-envelope"></i> Send password reset link
        </button>
    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a>
    </p>
</x-guest-layout>
