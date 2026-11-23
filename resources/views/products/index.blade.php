@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-sari-dark mb-1">Products</h1>
        <p class="text-muted mb-0">Manage every item on your store shelves.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-sari mt-2 mt-sm-0">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
            @if ($brand !== '') <input type="hidden" name="brand" value="{{ $brand }}"> @endif
            @if ($category !== '') <input type="hidden" name="category" value="{{ $category }}"> @endif
            <div class="col-md-8">
                <input type="search" name="search" value="{{ $search }}"
                       class="form-control" placeholder="Search by name, SKU, category, brand, or supplier...">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-outline-sari flex-fill">
                    <i class="bi bi-search"></i> Search
                </button>
                @if ($search !== '')
                    <a href="{{ route('products.index') }}" class="btn btn-light">Clear</a>
                @endif
            </div>
        </form>
        @if ($brand !== '' || $category !== '')
            <div class="mt-3 d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted small">Filtered by:</span>
                @if ($brand !== '')
                    <span class="badge bg-sari">Brand: {{ $brand }}</span>
                @endif
                @if ($category !== '')
                    <span class="badge bg-sari">Category: {{ $category }}</span>
                @endif
                <a href="{{ route('products.index') }}" class="small text-decoration-none">Clear filters</a>
            </div>
        @endif
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Inventory List <span class="badge bg-sari">{{ $products->total() }}</span></h5>
    </div>
    <div class="card-body p-0">
        @if ($products->isEmpty())
            <div class="alert alert-light m-3 mb-0">No products found. Try a different search or add a new product.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Reorder</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    @if ($product->supplier)
                                        <div class="small text-muted">{{ $product->supplier }}</div>
                                    @endif
                                </td>
                                <td><span class="badge bg-light text-dark border">{{ $product->category }}</span></td>
                                <td><span class="badge bg-white text-sari-dark border">{{ $product->brand ?? '—' }}</span></td>
                                <td class="text-center fw-bold">{{ $product->quantity }}</td>
                                <td class="text-center text-muted">{{ $product->reorder_level }}</td>
                                <td class="text-end">&#8369;{{ number_format($product->unit_price, 2) }}</td>
                                <td class="text-center">
                                    @if ($product->is_out_of_stock)
                                        <span class="badge bg-danger">Out of stock</span>
                                    @elseif ($product->is_low_stock)
                                        <span class="badge bg-warning text-dark">Low stock</span>
                                    @else
                                        <span class="badge bg-success">In stock</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-sari" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if (auth()->user()->isManager())
                                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline"
                                              onsubmit="return confirm('Move {{ addslashes($product->name) }} to Trash? You can restore it later.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Move to Trash">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
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
