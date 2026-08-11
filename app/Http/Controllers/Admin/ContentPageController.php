<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContentPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContentPageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', ['pages' => ContentPage::latest()->paginate(15)]);
    }

    public function create(): View
    {
        return view('admin.pages.form', ['page' => new ContentPage]);
    }

    public function edit(ContentPage $page): View
    {
        return view('admin.pages.form', compact('page'));
    }

    public function store(Request $request): RedirectResponse
    {
        $page = ContentPage::create($this->validated($request));
        ActivityLog::record('created', $page, "Creó la página {$page->title}.");

        return redirect()->route('admin.pages.index')->with('success', 'Página creada.');
    }

    public function update(Request $request, ContentPage $page): RedirectResponse
    {
        $page->update($this->validated($request, $page));
        ActivityLog::record('updated', $page, "Actualizó la página {$page->title}.");

        return redirect()->route('admin.pages.index')->with('success', 'Página actualizada.');
    }

    public function destroy(ContentPage $page): RedirectResponse
    {
        ActivityLog::record('deleted', ContentPage::class, "Eliminó la página {$page->title}.");
        $page->delete();

        return back()->with('success', 'Página eliminada.');
    }

    private function validated(Request $request, ?ContentPage $page = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('title')), 'is_active' => $request->boolean('is_active')]);

        return $request->validate(['title' => ['required', 'string', 'max:160'], 'slug' => ['required', 'string', 'max:180', Rule::unique('content_pages')->ignore($page)], 'excerpt' => ['nullable', 'string', 'max:300'], 'body' => ['required', 'string'], 'seo_title' => ['nullable', 'string', 'max:70'], 'seo_description' => ['nullable', 'string', 'max:160'], 'is_active' => ['boolean']]);
    }
}
