<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HomepageContent;
use App\Models\MediaAsset;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function index(Request $request): View
    {
        $assets = MediaAsset::with('media')->when($request->filled('q'), fn ($query) => $query->where(fn ($nested) => $nested->where('title', 'like', '%'.$request->string('q').'%')->orWhere('alt_text', 'like', '%'.$request->string('q').'%')))->when($request->filled('type'), fn ($query) => $query->where('usage_type', $request->string('type')))->latest()->paginate(12)->withQueryString();
        $assets->getCollection()->each(fn ($asset) => $asset->setAttribute('in_use', $this->isUsed($asset->id)));

        return view('admin.media.index', ['assets' => $assets, 'usageTypes' => $this->usageTypes()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request, true);
        unset($validated['image']);
        $asset = MediaAsset::create([...$validated, 'user_id' => $request->user()->id]);
        $asset->addMediaFromRequest('image')->toMediaCollection('image');
        ActivityLog::record('created', $asset, 'Subió una imagen a la biblioteca.');

        return back()->with('success', 'Imagen agregada a la biblioteca.');
    }

    public function update(Request $request, MediaAsset $medium): RedirectResponse
    {
        $validated = $this->validated($request);
        unset($validated['image']);
        $medium->update($validated);

        if ($request->hasFile('image')) {
            $medium->addMediaFromRequest('image')->toMediaCollection('image');
        }
        ActivityLog::record('updated', $medium, 'Actualizó una imagen de la biblioteca.');

        return back()->with('success', 'Imagen actualizada correctamente.');
    }

    public function destroy(MediaAsset $medium): RedirectResponse
    {
        if ($this->isUsed($medium->id)) {
            return back()->withErrors(['image' => 'No se puede eliminar porque está siendo utilizada en el sitio.']);
        }
        $medium->delete();

        return back()->with('success', 'Imagen eliminada de la biblioteca.');
    }

    private function validated(Request $request, bool $imageRequired = false): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'alt_text' => ['required', 'string', 'max:255'],
            'usage_type' => ['required', Rule::in(array_keys($this->usageTypes()))],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'focal_x' => ['nullable', 'integer', 'min:0', 'max:100'],
            'focal_y' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);
    }

    private function isUsed(int $id): bool
    {
        $homepage = HomepageContent::first();
        $homepageIds = array_merge(array_values($homepage?->media ?? []), array_values($homepage?->published_media ?? []));

        return in_array($id, $homepageIds)
            || Product::where('primary_media_id', $id)->exists()
            || Product::all()->contains(fn ($product) => in_array($id, $product->gallery_media_ids ?? []))
            || Testimonial::where('media_asset_id', $id)->exists();
    }

    private function usageTypes(): array
    {
        return ['general' => 'General', 'product' => 'Producto', 'photography' => 'Fotografía', 'artwork' => 'Arte promocional'];
    }
}
