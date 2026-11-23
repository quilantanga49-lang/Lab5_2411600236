<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/brands', [CatalogController::class, 'brands'])->name('brands.index');
    Route::get('/categories', [CatalogController::class, 'categories'])->name('categories.index');

    Route::resource('products', ProductController::class);
    Route::post('products/{product}/stock', [ProductController::class, 'adjustStock'])
        ->name('products.stock');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Catch invalid or extra URL segments (e.g. /login/...//...//.../***)
// and return the user to the matching page instead of a 404.
Route::fallback(function () {
    $segments = explode('/', trim(request()->path(), '/'));
    $base = $segments[0] ?? '';

    // Keep product detail pages when extra segments are appended.
    if ($base === 'products' && isset($segments[1]) && ctype_digit($segments[1])) {
        return redirect()->route('products.show', (int) $segments[1]);
    }

    $pages = [
        'login'           => 'login',
        'register'        => 'register',
        'forgot-password' => 'password.request',
        'reset-password'  => 'password.reset',
        'dashboard'       => 'dashboard',
        'products'        => 'products.index',
        'profile'         => 'profile.edit',
    ];

    if (isset($pages[$base])) {
        return redirect()->route($pages[$base]);
    }

    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
