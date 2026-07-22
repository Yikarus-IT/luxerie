<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('storefront.home', [
            'featuredProducts' => Product::published()->with('category')->where('is_featured', true)->latest()->take(3)->get(),
            'categories' => Category::where('is_active', true)->withCount(['products' => fn ($query) => $query->published()])->get(),
        ]);
    }

    public function shop(): View
    {
        return view('storefront.shop', [
            'products' => Product::published()->with('category')->latest()->paginate(9),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active && $product->category?->is_active, 404);

        return view('storefront.product', compact('product'));
    }
}
