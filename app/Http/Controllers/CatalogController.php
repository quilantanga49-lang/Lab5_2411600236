<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class CatalogController extends Controller
{
    /**
     * Show all brands with the number of products under each brand.
     */
    public function brands(): View
    {
        $brands = Product::query()
            ->selectRaw('brand, COUNT(*) AS product_count, SUM(quantity) AS total_stock')
            ->whereNotNull('brand')
            ->groupBy('brand')
            ->orderBy('brand')
            ->get();

        return view('catalog.brands', compact('brands'));
    }

    /**
     * Show all categories with the number of products under each category.
     */
    public function categories(): View
    {
        $categories = Product::query()
            ->selectRaw('category, COUNT(*) AS product_count, SUM(quantity) AS total_stock')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return view('catalog.categories', compact('categories'));
    }
}
