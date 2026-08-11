<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('admin.faqs.index', ['faqs' => Faq::orderBy('sort_order')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $faq = Faq::create($this->validated($request));
        ActivityLog::record('created', $faq, 'Creó una pregunta frecuente.');

        return back()->with('success', 'Pregunta creada.');
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validated($request));
        ActivityLog::record('updated', $faq, 'Actualizó una pregunta frecuente.');

        return back()->with('success', 'Pregunta actualizada.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('success', 'Pregunta eliminada.');
    }

    private function validated(Request $request): array
    {
        $request->merge(['is_active' => $request->boolean('is_active')]);

        return $request->validate(['question' => ['required', 'string', 'max:240'], 'answer' => ['required', 'string'], 'sort_order' => ['required', 'integer', 'min:0'], 'is_active' => ['boolean']]);
    }
}
