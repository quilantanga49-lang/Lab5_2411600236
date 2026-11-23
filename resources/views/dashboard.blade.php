@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-sari-dark mb-1">Store Dashboard</h1>
        <p class="text-muted mb-0">Real-time inventory overview for your sari-sari store.</p>
    </div>
    <a href="{{ route('products.create') }}" class="btn btn-sari mt-2 mt-sm-0">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Total Products</div>
                        <div class="h2 fw-bold mb-0">{{ $totalProducts }}</div>
                    </div>
                    <span class="stat-icon bg-green"><i class="bi bi-box-seam"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Low Stock Items</div>
                        <div class="h2 fw-bold mb-0 text-warning">{{ $lowStockCount }}</div>
                    </div>
                    <span class="stat-icon bg-orange"><i class="bi bi-exclamation-triangle"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Out of Stock</div>
                        <div class="h2 fw-bold mb-0 text-danger">{{ $outOfStockCount }}</div>
                    </div>
                    <span class="stat-icon bg-red"><i class="bi bi-x-octagon"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small">Inventory Value</div>
                        <div class="h4 fw-bold mb-0 mt-2 text-sari-green">&#8369;{{ number_format($inventoryValue, 2) }}</div>
                    </div>
                    <span class="stat-icon bg-green"><i class="bi bi-cash-coin"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 mb-4" id="low-stock">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-circle text-warning"></i> Low Stock Alerts</h5>
                <a href="{{ route('products.index') }}" class="small text-decoration-none">View all</a>
            </div>
            <div class="card-body p-0">
                @if ($lowStockProducts->isEmpty())
                    <div class="alert alert-success m-3 mb-0">
                        <i class="bi bi-check-circle"></i> All products are above their reorder level.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th class="text-center">On Hand</th>
                                    <th class="text-center">Reorder At</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lowStockProducts as $product)
                                    <tr>
                                        <td class="fw-semibold">{{ $product->name }}</td>
                                        <td><code>{{ $product->sku }}</code></td>
                                        <td class="text-center">
                                            <span class="badge {{ $product->is_out_of_stock ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                {{ $product->quantity }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $product->reorder_level }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-sari">Restock</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history"></i> Recent Stock Movements</h5>
            </div>
            <div class="card-body p-0">
                @if ($recentTransactions->isEmpty())
                    <p class="text-muted p-3 mb-0">No stock movements recorded yet.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th class="text-center">Qty</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->product?->name ?? 'Deleted product' }}</td>
                                        <td>
                                            <span class="badge {{ $transaction->type === 'in' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $transaction->type === 'in' ? 'Stock In' : 'Stock Out' }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $transaction->quantity }}</td>
                                        <td>{{ $transaction->reference ?? '—' }}</td>
                                        <td class="small text-muted">{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart"></i> Products by Category</h5>
            </div>
            <div class="card-body">
                @php $max = $categoryTotals->max() ?: 1; @endphp
                @foreach ($categoryTotals as $category => $count)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-semibold">{{ $category }}</span>
                            <span class="text-muted">{{ $count }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ round(($count / $max) * 100) }}%"
                                 aria-valuenow="{{ $count }}" aria-valuemin="0" aria-valuemax="{{ $max }}"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-plus-square"></i> Recently Added</h5>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse ($recentProducts as $product)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold small">{{ $product->name }}</div>
                                <div class="text-muted small">{{ $product->category }} &middot; {{ $product->sku }}</div>
                            </div>
                            <span class="badge bg-light text-dark border">{{ $product->quantity }} pcs</span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">No products yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
