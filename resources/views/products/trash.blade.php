@extends('layouts.app')

@section('title', 'Trash')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">Trash</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-sari-dark mb-1">Trash</h1>
        <p class="text-muted mb-0">Deleted products are kept here until you restore or permanently delete them.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-light mt-2 mt-sm-0">
        <i class="bi bi-arrow-left"></i> Back to Products
    </a>
</div>

@if (! auth()->user()->isManager())
    <div class="alert alert-warning">
        <i class="bi bi-shield-lock"></i> You are viewing as <strong>Staff</strong>. Only a manager can restore or permanently delete products.
    </div>
@endif

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-trash"></i> Deleted Products
            <span class="badge bg-secondary">{{ $products->total() }}</span>
        </h5>
    </div>
    <div class="card-body p-0">
        @if ($products->isEmpty())
            <div class="alert alert-light m-3 mb-0">
                <i class="bi bi-check-circle"></i> The trash is empty. No deleted products.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Deleted</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td><code>{{ $product->sku }}</code></td>
                                <td class="fw-semibold">{{ $product->name }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $product->category }}</span></td>
                                <td><span class="badge bg-white text-sari-dark border">{{ $product->brand ?? '—' }}</span></td>
                                <td class="small text-muted">{{ $product->deleted_at->format('M d, Y h:i A') }}</td>
                                <td class="text-end">
                                    @if (auth()->user()->isManager())
                                        <form method="POST" action="{{ route('products.restore', $product->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('products.force-delete', $product->id) }}" class="d-inline"
                                              onsubmit="return confirm('Permanently delete {{ addslashes($product->name) }}? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete permanently">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">Manager access required</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if ($products->hasPages())
        <div class="card-footer bg-white">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
