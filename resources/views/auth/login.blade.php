<x-guest-layout>
    <x-slot:title>Log in</x-slot:title>

    <h4 class="mb-1 fw-bold text-sari-dark">Welcome back</h4>
    <p class="text-muted small mb-4">Log in to manage your store inventory.</p>

    @if (session('status'))
        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <label class="form-check-label small">
                <input class="form-check-input" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>
            @if (Route::has('password.request'))
                <a class="small text-decoration-none" href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn btn-sari w-100">
            <i class="bi bi-box-arrow-in-right"></i> Log in
        </button>
    </form>

    @if (Route::has('register'))
        <p class="text-center text-muted small mt-4 mb-0">
            New to the system? <a href="{{ route('register') }}" class="text-decoration-none">Create an account</a>
        </p>
    @endif

    <div class="alert alert-light border small mt-4 mb-0">
        <strong>Demo login:</strong> admin@sari.com &nbsp; / &nbsp; password
    </div>
</x-guest-layout>
