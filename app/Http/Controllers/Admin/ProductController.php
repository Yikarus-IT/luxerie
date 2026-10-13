<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\Product;
use App\Models\InventoryAdjustment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', ['products' => Product::latest()->paginate(12)]);
    }

    public function create(): View
    {
        return view('admin.products.form', ['product' => new Product, 'mediaAssets' => MediaAsset::with('media')->latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = Product::create($this->validated($request));

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', ['product' => $product, 'mediaAssets' => MediaAsset::with('media')->latest()->get()]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product);
        DB::transaction(function () use ($request, $product, $data): void {
            $lockedProduct = Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
            $before = $lockedProduct->stock;
            $lockedProduct->update($data);
            if ($before !== (int) $data['stock']) {
                InventoryAdjustment::create(['product_id' => $lockedProduct->id, 'user_id' => $request->user()->id, 'quantity_change' => (int) $data['stock'] - $before, 'stock_before' => $before, 'stock_after' => (int) $data['stock'], 'reason' => 'Actualización desde la ficha del producto.']);
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate(['quantity_change' => ['required', 'integer', 'not_in:0', 'between:-99999,99999'], 'reason' => ['required', 'string', 'max:300']]);

        DB::transaction(function () use ($request, $product, $data): void {
            $lockedProduct = Product::whereKey($product->id)->lockForUpdate()->firstOrFail();
            $before = $lockedProduct->stock;
            $after = $before + $data['quantity_change'];
            abort_if($after < 0, 422, 'El ajuste dejaría el inventario en un valor negativo.');
            $lockedProduct->update(['stock' => $after]);
            InventoryAdjustment::create(['product_id' => $lockedProduct->id, 'user_id' => $request->user()->id, 'quantity_change' => $data['quantity_change'], 'stock_before' => $before, 'stock_after' => $after, 'reason' => $data['reason']]);
        });

        return back()->with('success', 'Inventario actualizado y registrado.');
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $request->merge([
            'slug' => $request->filled('slug') ? Str::slug($request->string('slug')) : Str::slug($request->string('name')),
            'is_active' => $request->boolean('is_active'),
            'is_featured' => $request->boolean('is_featured'),
            'gallery_media_ids' => array_values(array_filter($request->input('gallery_media_ids', []))),
        ]);

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:140', Rule::unique('products')->ignore($product)],
            'sku' => ['required', 'string', 'max:60', Rule::unique('products')->ignore($product)],
            'short_description' => ['required', 'string', 'max:240'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'bulk_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'bulk_min_quantity' => ['nullable', 'integer', 'min:2', 'required_with:bulk_price'],
            'compare_at_price' => ['nullable', 'numeric', 'gte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'size_label' => ['nullable', 'string', 'max:60'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'primary_media_id' => ['nullable', 'exists:media_assets,id'],
            'gallery_media_ids' => ['nullable', 'array', 'max:8'],
            'gallery_media_ids.*' => ['integer', 'exists:media_assets,id'],
            'ingredients' => ['nullable', 'string'], 'directions' => ['nullable', 'string'], 'precautions' => ['nullable', 'string'],
            'barcode' => ['nullable', 'string', 'max:80'], 'weight_grams' => ['nullable', 'numeric', 'min:0'],
            'package_length_cm' => ['nullable', 'numeric', 'min:0'], 'package_width_cm' => ['nullable', 'numeric', 'min:0'], 'package_height_cm' => ['nullable', 'numeric', 'min:0'],
            'seo_title' => ['nullable', 'string', 'max:70'], 'seo_description' => ['nullable', 'string', 'max:160'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
        ]);
    }
}
