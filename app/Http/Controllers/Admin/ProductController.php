<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use App\Models\Product;
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
        $product->update($this->validated($request, $product));

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', 'Producto eliminado correctamente.');
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
