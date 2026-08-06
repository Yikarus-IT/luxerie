<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContent;
use App\Models\MediaAsset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function edit(): View
    {
        $homepage = HomepageContent::first();

        return view('admin.homepage.edit', [
            'content' => $homepage?->resolvedContent() ?? HomepageContent::defaults(),
            'selectedMedia' => $homepage?->media ?? [],
            'mediaAssets' => MediaAsset::with('media')->latest()->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content.hero.eyebrow' => ['required', 'string', 'max:120'],
            'content.hero.heading' => ['required', 'string', 'max:160'],
            'content.hero.highlight' => ['required', 'string', 'max:160'],
            'content.hero.body' => ['required', 'string', 'max:400'],
            'content.hero.button' => ['required', 'string', 'max:80'],
            'content.about.eyebrow' => ['required', 'string', 'max:120'],
            'content.about.heading' => ['required', 'string', 'max:220'],
            'content.about.body' => ['required', 'string', 'max:500'],
            'content.benefits.eyebrow' => ['required', 'string', 'max:120'],
            'content.benefits.heading' => ['required', 'string', 'max:220'],
            'content.benefits.captions' => ['required', 'array', 'size:4'],
            'content.benefits.captions.*' => ['required', 'string', 'max:120'],
            'content.product.eyebrow' => ['required', 'string', 'max:120'],
            'content.product.heading' => ['required', 'string', 'max:160'],
            'content.product.body' => ['required', 'string', 'max:400'],
            'content.product.benefits' => ['required', 'array', 'size:3'],
            'content.product.benefits.*' => ['required', 'string', 'max:200'],
            'content.ritual.eyebrow' => ['required', 'string', 'max:120'],
            'content.ritual.heading' => ['required', 'string', 'max:200'],
            'content.ritual.note' => ['required', 'string', 'max:300'],
            'content.testimonial.eyebrow' => ['required', 'string', 'max:120'],
            'content.testimonial.quote' => ['required', 'string', 'max:240'],
            'content.testimonial.body' => ['required', 'string', 'max:400'],
            'content.testimonial.note' => ['required', 'string', 'max:300'],
            'media' => ['nullable', 'array'],
            'media.*' => ['nullable', 'integer', 'exists:media_assets,id'],
        ]);

        HomepageContent::query()->updateOrCreate(['id' => 1], [
            'content' => $validated['content'],
            'media' => array_filter($validated['media'] ?? []),
            'updated_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Página de inicio actualizada correctamente.');
    }
}
