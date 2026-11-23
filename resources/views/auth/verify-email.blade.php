<x-guest-layout>
    <x-slot:title>Verify email</x-slot:title>

    <h4 class="mb-1 fw-bold text-sari-dark">Verify your email</h4>
    <p class="text-muted small mb-4">Please check your email for the verification link before continuing.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">A new verification link has been sent to your email.</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-sari w-100">
            <i class="bi bi-envelope-paper"></i> Resend verification email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-link w-100">Log out</button>
    </form>
</x-guest-layout>
