<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MediaAssetController extends Controller
{
    public function index(): View
    {
        return view('admin.media.index', ['assets' => MediaAsset::with('media')->latest()->paginate(12), 'usageTypes' => $this->usageTypes()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request, true);
        unset($validated['image']);
        $asset = MediaAsset::create([...$validated, 'user_id' => $request->user()->id]);
        $asset->addMediaFromRequest('image')->toMediaCollection('image');

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

        return back()->with('success', 'Imagen actualizada correctamente.');
    }

    public function destroy(MediaAsset $medium): RedirectResponse
    {
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
        ]);
    }

    private function usageTypes(): array
    {
        return ['general' => 'General', 'product' => 'Producto', 'photography' => 'Fotografía', 'artwork' => 'Arte promocional'];
    }
}
