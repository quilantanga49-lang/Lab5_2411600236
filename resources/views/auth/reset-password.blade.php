<x-guest-layout>
    <x-slot:title>Reset password</x-slot:title>

    <h4 class="mb-1 fw-bold text-sari-dark">Choose a new password</h4>
    <p class="text-muted small mb-4">Make it strong and easy for you to remember.</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control" required readonly autocomplete="username">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New password</label>
            <input id="password" type="password" name="password" class="form-control" required autofocus autocomplete="new-password">
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">Confirm new password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
        </div>

        <button type="submit" class="btn btn-sari w-100">
            <i class="bi bi-key"></i> Reset password
        </button>
    </form>
</x-guest-layout>
