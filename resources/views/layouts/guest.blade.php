<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Login' }} | {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="guest-body">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="w-100" style="max-width: 460px;">
            <div class="text-center mb-4">
                <a href="{{ route('login') }}" class="text-decoration-none">
                    <div class="guest-logo mx-auto mb-2"><i class="bi bi-shop"></i></div>
                    <h3 class="fw-bold text-sari-dark mb-0">Sari-Sari Store</h3>
                    <span class="text-muted small">Inventory Management System</span>
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{ $slot }}
                </div>
            </div>

            <p class="text-center text-muted small mt-3 mb-0">
                Secure login powered by Laravel Breeze
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
