<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HomepageContent;
use App\Models\MediaAsset;
use App\Models\Product;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $homepage = HomepageContent::first();
        $content = $homepage?->resolvedContent() ?? HomepageContent::defaults();
        $selectedMedia = $homepage?->media ?? [];
        $assets = MediaAsset::with('media')->whereIn('id', array_values($selectedMedia))->get()->keyBy('id');
        $homepageImages = collect($selectedMedia)->mapWithKeys(function ($assetId, $slot) use ($assets) {
            $url = $assets->get($assetId)?->image()?->getUrl();

            return $url ? [$slot => $url] : [];
        })->all();
        $homepageImageAlts = collect($selectedMedia)->mapWithKeys(function ($assetId, $slot) use ($assets) {
            $alt = $assets->get($assetId)?->alt_text;

            return $alt ? [$slot => $alt] : [];
        })->all();

        return view('storefront.home', [
            'featuredProducts' => Product::published()->with('category')->where('is_featured', true)->latest()->take(3)->get(),
            'categories' => Category::where('is_active', true)->withCount(['products' => fn ($query) => $query->published()])->get(),
            'homepageContent' => $content,
            'homepageImages' => $homepageImages,
            'homepageImageAlts' => $homepageImageAlts,
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
