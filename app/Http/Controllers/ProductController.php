<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a paginated list of products with optional search.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $brand = trim((string) $request->input('brand', ''));
        $category = trim((string) $request->input('category', ''));

        $products = Product::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('supplier', 'like', "%{$search}%");
                });
            })
            ->when($brand !== '', fn ($query) => $query->where('brand', $brand))
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact('products', 'search', 'brand', 'category'));
    }

    public function create()
    {
        return view('products.create', [
            'product' => new Product(),
            'categories' => $this->categories(),
            'brands' => $this->brands(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product added to the inventory successfully.');
    }

    public function show(Product $product)
    {
        return view('products.show', [
            'product' => $product->load('transactions.user'),
        ]);
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => $this->categories(),
            'brands' => $this->brands(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate($this->rules($product->id), $this->messages());

        $product->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeManager();

        // Soft delete: the product is moved to the Trash, not permanently removed.
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product moved to Trash. You can restore it anytime.');
    }

    /**
     * Show soft-deleted products.
     */
    public function trash()
    {
        $products = Product::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate(12);

        return view('products.trash', compact('products'));
    }

    /**
     * Restore a soft-deleted product.
     */
    public function restore(string $id)
    {
        $this->authorizeManager();

        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.trash')
            ->with('success', 'Product restored to the inventory successfully.');
    }

    /**
     * Permanently delete a product from the Trash.
     */
    public function forceDelete(string $id)
    {
        $this->authorizeManager();

        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return redirect()->route('products.trash')
            ->with('success', 'Product permanently deleted from the Trash.');
    }

    private function authorizeManager(): void
    {
        if (! Auth::user()?->isManager()) {
            abort(403, 'Only a manager can modify the inventory trash.');
        }
    }

    /**
     * Record a stock-in or stock-out movement.
     */
    public function adjustStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => ['required', 'in:in,out'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'reference' => ['nullable', 'string', 'max:100'],
        ], [
            'type.in' => 'Select a valid stock movement type.',
            'quantity.min' => 'Quantity must be at least 1.',
        ]);

        $product->adjustStock(
            $validated['type'],
            (int) $validated['quantity'],
            Auth::id(),
            $validated['reference'] ?? null
        );

        return redirect()->route('products.show', $product)
            ->with('success', 'Stock movement recorded successfully.');
    }

    private function rules(?int $productId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'.($productId ? ','.$productId : '')],
            'description' => ['nullable', 'string', 'max:1000'],
            'category' => ['required', 'string', 'max:80'],
            'brand' => ['required', 'string', 'max:80'],
            'quantity' => ['required', 'integer', 'min:0', 'max:100000'],
            'reorder_level' => ['required', 'integer', 'min:0', 'max:100000'],
            'unit_price' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'supplier' => ['nullable', 'string', 'max:120'],
        ];
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'This SKU is already used by another product.',
            'category.required' => 'Choose or enter a category.',
            'brand.required' => 'Choose or enter a brand.',
            'quantity.required' => 'Enter the current stock quantity.',
            'quantity.min' => 'Quantity cannot be negative.',
            'reorder_level.required' => 'Enter a reorder level.',
            'unit_price.required' => 'Enter the unit price.',
            'unit_price.min' => 'Unit price cannot be negative.',
        ];
    }

    private function categories(): array
    {
        return Product::query()
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->all();
    }

    private function brands(): array
    {
        return Product::query()
            ->distinct()
            ->whereNotNull('brand')
            ->orderBy('brand')
            ->pluck('brand')
            ->all();
    }
}
