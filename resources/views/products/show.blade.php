@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold text-sari-dark mb-0">{{ $product->name }}</h1>
    </div>
    <div class="d-flex gap-2 mt-2 mt-sm-0">
        <a href="{{ route('products.edit', $product) }}" class="btn btn-sari">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <form method="POST" action="{{ route('products.destroy', $product) }}"
              onsubmit="return confirm('Delete {{ addslashes($product->name) }} from the inventory?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Product Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="text-muted small">SKU</div>
                        <div class="fw-semibold"><code>{{ $product->sku }}</code></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Category</div>
                        <div class="fw-semibold">{{ $product->category }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Supplier</div>
                        <div class="fw-semibold">{{ $product->supplier ?? '—' }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Unit Price</div>
                        <div class="fw-semibold text-sari-green">&#8369;{{ number_format($product->unit_price, 2) }}</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Quantity on Hand</div>
                        <div class="h4 fw-bold mb-0">{{ $product->quantity }} <span class="fs-6 text-muted">pcs</span></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Reorder Level</div>
                        <div class="h4 fw-bold mb-0">{{ $product->reorder_level }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Description</div>
                        <div>{{ $product->description ?? 'No description provided.' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">Stock Status</div>
                        <div class="mt-1">
                            @if ($product->is_out_of_stock)
                                <span class="badge bg-danger fs-6">Out of stock</span>
                            @elseif ($product->is_low_stock)
                                <span class="badge bg-warning text-dark fs-6">Low stock &mdash; reorder soon</span>
                            @else
                                <span class="badge bg-success fs-6">In stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-arrow-left-right"></i> Record Stock Movement</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-light border small mb-3">
                    Current stock value: <strong>&#8369;{{ number_format($product->stock_value, 2) }}</strong>
                </div>
                <form method="POST" action="{{ route('products.stock', $product) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-6">
                            <label for="type" class="form-label">Movement</label>
                            <select id="type" name="type" class="form-select" required>
                                <option value="in">Stock In</option>
                                <option value="out">Stock Out</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="qty" class="form-label">Quantity</label>
                            <input id="qty" type="number" name="quantity" min="1" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label for="reference" class="form-label">Reference (optional)</label>
                            <input id="reference" type="text" name="reference" maxlength="100"
                                   class="form-control" placeholder="e.g. PO-2026-014">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-outline-sari w-100">
                                <i class="bi bi-check2-circle"></i> Save Movement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history"></i> Stock History</h5>
    </div>
    <div class="card-body p-0">
        @if ($product->transactions->isEmpty())
            <p class="text-muted p-3 mb-0">No stock movements recorded for this product.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th class="text-center">Quantity</th>
                            <th>Reference</th>
                            <th>Recorded by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->transactions as $transaction)
                            <tr>
                                <td class="small">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <span class="badge {{ $transaction->type === 'in' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $transaction->type === 'in' ? 'Stock In' : 'Stock Out' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $transaction->quantity }}</td>
                                <td>{{ $transaction->reference ?? '—' }}</td>
                                <td>{{ $transaction->user?->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
