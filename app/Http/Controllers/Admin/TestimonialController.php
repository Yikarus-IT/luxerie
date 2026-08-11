<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MediaAsset;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.testimonials.index', ['testimonials' => Testimonial::with('mediaAsset.media')->orderBy('sort_order')->get(), 'mediaAssets' => MediaAsset::with('media')->latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $item = Testimonial::create($this->validated($request));
        ActivityLog::record('created', $item, 'Creó un testimonio.');

        return back()->with('success', 'Testimonio creado.');
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $testimonial->update($this->validated($request));
        ActivityLog::record('updated', $testimonial, 'Actualizó un testimonio.');

        return back()->with('success', 'Testimonio actualizado.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return back()->with('success', 'Testimonio eliminado.');
    }

    private function validated(Request $request): array
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);

        return $request->validate(['name' => ['nullable', 'string', 'max:120'], 'quote' => ['required', 'string', 'max:800'], 'media_asset_id' => ['nullable', 'exists:media_assets,id'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_active' => ['boolean']]);
    }
}
