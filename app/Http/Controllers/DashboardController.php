<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $products = Product::all();

        $totalProducts = $products->count();
        $lowStockCount = $products->filter(fn (Product $product) => $product->is_low_stock)->count();
        $outOfStockCount = $products->filter(fn (Product $product) => $product->is_out_of_stock)->count();
        $inventoryValue = $products->sum(fn (Product $product) => $product->stock_value);

        $lowStockProducts = Product::lowStock()->orderBy('quantity')->take(6)->get();
        $recentProducts = Product::latest()->take(5)->get();

        $categoryTotals = $products->groupBy('category')
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        $brandTotals = $products->groupBy('brand')
            ->map(fn ($group) => $group->count())
            ->sortDesc();

        $totalCategories = $categoryTotals->count();
        $totalBrands = $brandTotals->count();

        $recentTransactions = InventoryTransaction::with(['product', 'user'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'lowStockCount',
            'outOfStockCount',
            'inventoryValue',
            'lowStockProducts',
            'recentProducts',
            'categoryTotals',
            'brandTotals',
            'totalCategories',
            'totalBrands',
            'recentTransactions'
        ));
    }
}
