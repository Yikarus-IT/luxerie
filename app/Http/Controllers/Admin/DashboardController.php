<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\HomepageContent;
use App\Models\MediaAsset;
use App\Models\Product;
use App\Models\Order;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'activeProductCount' => Product::where('is_active', true)->count(),
            'customerCount' => \App\Models\Customer::count(),
            'orderCount' => \App\Models\Order::count(),
            'paidOrderCount' => Order::where('payment_status', 'paid')->count(),
            'paidRevenue' => Order::where('payment_status', 'paid')->sum('total'),
            'ordersNeedingAttention' => Order::whereIn('status', ['paid', 'processing'])->count(),
            'lowStockProducts' => Product::where('stock', '<=', 10)->orderBy('stock')->take(5)->get(),
            'draftChanges' => HomepageContent::whereNull('published_at')->orWhereColumn('updated_at', '>', 'published_at')->exists(),
            'mediaCount' => MediaAsset::count(),
            'mediaStorageMb' => round(Media::sum('size') / 1048576, 1),
            'recentOrders' => \App\Models\Order::latest()->take(5)->get(),
        ]);
    }
}
