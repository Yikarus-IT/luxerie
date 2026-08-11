<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HomepageContent;
use App\Models\HomepageRevision;
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
            'layout' => $homepage?->resolvedLayout() ?? HomepageContent::defaultLayout(),
            'homepage' => $homepage,
            'revisions' => HomepageRevision::with('user')->latest('id')->take(10)->get(),
            'mediaAssets' => MediaAsset::with('media')->latest()->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->merge([
            'action' => $request->input('action', 'publish'),
            'layout' => $request->input('layout', HomepageContent::defaultLayout()),
        ]);

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
            'content.benefits.captions' => ['required', 'array', 'min:1', 'max:8'],
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
            'layout' => ['required', 'array'],
            'layout.*.visible' => ['nullable', 'boolean'],
            'layout.*.order' => ['required', 'integer', 'min:0', 'max:100'],
            'action' => ['required', 'in:draft,publish,schedule'],
            'scheduled_for' => ['nullable', 'required_if:action,schedule', 'date', 'after:now'],
        ]);

        $layout = collect(HomepageContent::defaultLayout())->mapWithKeys(fn ($defaults, $key) => [$key => ['visible' => $request->boolean("layout.$key.visible"), 'order' => (int) data_get($validated, "layout.$key.order", $defaults['order'])]])->all();
        $homepage = HomepageContent::query()->firstOrNew(['id' => 1]);
        $homepage->fill([
            'content' => $validated['content'],
            'media' => array_filter($validated['media'] ?? []),
            'layout' => $layout,
            'updated_by' => $request->user()->id,
        ]);

        if ($validated['action'] === 'publish') {
            if ($homepage->published_content) {
                HomepageRevision::create(['content' => $homepage->published_content, 'media' => $homepage->published_media, 'layout' => $homepage->published_layout, 'user_id' => $request->user()->id, 'created_at' => now()]);
            }
            $homepage->published_content = $validated['content'];
            $homepage->published_media = array_filter($validated['media'] ?? []);
            $homepage->published_layout = $layout;
            $homepage->published_at = now();
            $homepage->scheduled_content = null;
            $homepage->scheduled_media = null;
            $homepage->scheduled_layout = null;
            $homepage->scheduled_for = null;
        } elseif ($validated['action'] === 'schedule') {
            $homepage->scheduled_content = $validated['content'];
            $homepage->scheduled_media = array_filter($validated['media'] ?? []);
            $homepage->scheduled_layout = $layout;
            $homepage->scheduled_for = $validated['scheduled_for'];
        }
        $homepage->save();
        $messages = ['publish' => 'Publicó la página de inicio.', 'schedule' => 'Programó la página de inicio.', 'draft' => 'Guardó un borrador de la página de inicio.'];
        ActivityLog::record($validated['action'] === 'draft' ? 'updated' : $validated['action'].'d', $homepage, $messages[$validated['action']]);

        return back()->with('success', ['publish' => 'Página de inicio publicada.', 'schedule' => 'Publicación programada.', 'draft' => 'Borrador guardado.'][$validated['action']]);
    }

    public function restore(HomepageRevision $revision): RedirectResponse
    {
        $homepage = HomepageContent::firstOrFail();
        $homepage->update(['content' => $revision->content, 'media' => $revision->media, 'layout' => $revision->layout, 'updated_by' => auth()->id()]);
        ActivityLog::record('restored', $homepage, "Restauró la revisión {$revision->id} como borrador.");

        return back()->with('success', 'Revisión restaurada como borrador. Revísala antes de publicar.');
    }
}
