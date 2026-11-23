<x-guest-layout>
    <x-slot:title>Confirm password</x-slot:title>

    <h4 class="mb-1 fw-bold text-sari-dark">Confirm your password</h4>
    <p class="text-muted small mb-4">Please confirm your password before continuing.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" name="password" class="form-control" required autofocus autocomplete="current-password">
        </div>

        <button type="submit" class="btn btn-sari w-100">
            <i class="bi bi-shield-lock"></i> Confirm password
        </button>
    </form>
</x-guest-layout>
