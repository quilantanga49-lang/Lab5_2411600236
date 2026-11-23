<div class="row g-3">
    <div class="col-md-6">
        <label for="name" class="form-label">Product name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $product->name) }}" placeholder="e.g. Coca-Cola 330ml" required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
        <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror"
               value="{{ old('sku', $product->sku) }}" placeholder="e.g. BEV-001" required>
        @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
        <input type="text" id="category" name="category" list="category-list"
               class="form-control @error('category') is-invalid @enderror"
               value="{{ old('category', $product->category) }}" placeholder="e.g. Beverages" required>
        <datalist id="category-list">
            @foreach ($categories as $category)
                <option value="{{ $category }}"></option>
            @endforeach
        </datalist>
        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
        <input type="text" id="brand" name="brand" list="brand-list"
               class="form-control @error('brand') is-invalid @enderror"
               value="{{ old('brand', $product->brand) }}" placeholder="e.g. Coca-Cola" required>
        <datalist id="brand-list">
            @foreach ($brands as $brand)
                <option value="{{ $brand }}"></option>
            @endforeach
        </datalist>
        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label for="supplier" class="form-label">Supplier</label>
        <input type="text" id="supplier" name="supplier" class="form-control @error('supplier') is-invalid @enderror"
               value="{{ old('supplier', $product->supplier) }}" placeholder="e.g. Metro Drinks Depot">
        @error('supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="quantity" class="form-label">Quantity on hand <span class="text-danger">*</span></label>
        <input type="number" id="quantity" name="quantity" min="0"
               class="form-control @error('quantity') is-invalid @enderror"
               value="{{ old('quantity', $product->quantity) }}" required>
        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="reorder_level" class="form-label">Reorder level <span class="text-danger">*</span></label>
        <input type="number" id="reorder_level" name="reorder_level" min="0"
               class="form-control @error('reorder_level') is-invalid @enderror"
               value="{{ old('reorder_level', $product->reorder_level) }}" required>
        @error('reorder_level') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-4">
        <label for="unit_price" class="form-label">Unit price (&#8369;) <span class="text-danger">*</span></label>
        <input type="number" id="unit_price" name="unit_price" min="0" step="0.01"
               class="form-control @error('unit_price') is-invalid @enderror"
               value="{{ old('unit_price', $product->unit_price) }}" required>
        @error('unit_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="3"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Optional product details...">{{ old('description', $product->description) }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-sari">
        <i class="bi bi-save"></i> {{ $button }}
    </button>
    <a href="{{ route('products.index') }}" class="btn btn-light">Cancel</a>
</div>
