@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-sari-dark mb-1">Categories</h1>
        <p class="text-muted mb-0">Browse product categories in your sari-sari store.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-sari mt-2 mt-sm-0">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

<div class="row g-3">
    @forelse ($categories as $category)
        <div class="col-6 col-md-4 col-xl-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <span class="stat-icon bg-orange mx-auto mb-3"><i class="bi bi-grid"></i></span>
                    <h5 class="fw-bold mb-1">{{ $category->category }}</h5>
                    <div class="text-muted small mb-3">
                        {{ $category->product_count }} {{ Str::plural('product', $category->product_count) }}
                        &middot; {{ $category->total_stock }} pcs
                    </div>
                    <a href="{{ route('products.index', ['category' => $category->category]) }}" class="btn btn-sm btn-outline-sari">
                        <i class="bi bi-eye"></i> View products
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-light">No categories recorded yet. Add a product to get started.</div>
        </div>
    @endforelse
</div>
@endsection
