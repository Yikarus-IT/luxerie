<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContentPage;
use App\Models\Faq;
use App\Models\HomepageContent;
use App\Models\MediaAsset;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        $homepage = HomepageContent::first();
        $preview = request()->routeIs('admin.homepage.preview');
        $scheduledIsLive = ! $preview && $homepage?->scheduled_for?->isPast();
        $content = $homepage ? ($preview ? $homepage->resolvedContent() : ($scheduledIsLive ? array_replace_recursive(HomepageContent::defaults(), $homepage->scheduled_content ?? []) : $homepage->resolvedPublishedContent())) : HomepageContent::defaults();
        $selectedMedia = $homepage ? (($preview ? $homepage->media : ($scheduledIsLive ? $homepage->scheduled_media : $homepage->published_media)) ?? []) : [];
        $homepageLayout = $scheduledIsLive ? array_replace_recursive(HomepageContent::defaultLayout(), $homepage->scheduled_layout ?? []) : ($homepage?->resolvedLayout(! $preview) ?? HomepageContent::defaultLayout());
        $assets = MediaAsset::with('media')->whereIn('id', array_values($selectedMedia))->get()->keyBy('id');
        $homepageImages = collect($selectedMedia)->mapWithKeys(function ($assetId, $slot) use ($assets) {
            $url = $assets->get($assetId)?->image()?->getUrl();

            return $url ? [$slot => $url] : [];
        })->all();
        $homepageImageAlts = collect($selectedMedia)->mapWithKeys(function ($assetId, $slot) use ($assets) {
            $alt = $assets->get($assetId)?->alt_text;

            return $alt ? [$slot => $alt] : [];
        })->all();
        $homepageImagePositions = collect($selectedMedia)->mapWithKeys(fn ($assetId, $slot) => $assets->has($assetId) ? [$slot => $assets[$assetId]->focal_x.'% '.$assets[$assetId]->focal_y.'%'] : [])->all();

        return view('storefront.home', [
            'featuredProducts' => Product::published()->with(['category', 'primaryMedia.media'])->where('is_featured', true)->latest()->take(3)->get(),
            'categories' => Category::where('is_active', true)->withCount(['products' => fn ($query) => $query->published()])->get(),
            'homepageContent' => $content,
            'homepageImages' => $homepageImages,
            'homepageImageAlts' => $homepageImageAlts,
            'homepageImagePositions' => $homepageImagePositions,
            'homepageLayout' => $homepageLayout,
            'isPreview' => $preview,
            'featuredTestimonial' => Testimonial::with('mediaAsset.media')->where('is_active', true)->orderBy('sort_order')->first(),
        ]);
    }

    public function page(ContentPage $page): View
    {
        abort_unless($page->is_active, 404);

        return view('storefront.page', compact('page'));
    }

    public function faqs(): View
    {
        return view('storefront.faqs', ['faqs' => Faq::where('is_active', true)->orderBy('sort_order')->get()]);
    }

    public function shop(): View
    {
        return view('storefront.shop', [
            'products' => Product::published()->with(['category', 'primaryMedia.media'])->latest()->paginate(9),
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active && $product->category?->is_active, 404);
        $product->load('primaryMedia.media');
        $productGallery = MediaAsset::with('media')->whereIn('id', $product->gallery_media_ids ?? [])->get();

        return view('storefront.product', compact('product', 'productGallery'));
    }
}
