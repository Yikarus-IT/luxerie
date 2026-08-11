<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\HomepageContent;
use App\Models\MediaAsset;
use App\Models\Product;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
            'categoryCount' => Category::count(),
            'lowStockProducts' => Product::with('category')->where('stock', '<=', 10)->orderBy('stock')->take(5)->get(),
            'draftChanges' => HomepageContent::whereNull('published_at')->orWhereColumn('updated_at', '>', 'published_at')->exists(),
            'mediaCount' => MediaAsset::count(),
            'mediaStorageMb' => round(Media::sum('size') / 1048576, 1),
            'incompleteProductCount' => Product::whereNull('primary_media_id')->orWhereNull('barcode')->orWhereNull('seo_title')->count(),
            'marketplaceReadyCount' => Product::whereNotNull('barcode')->whereNotNull('weight_grams')->whereNotNull('primary_media_id')->count(),
            'recentActivities' => ActivityLog::with('user')->latest('id')->take(6)->get(),
        ]);
    }
}
