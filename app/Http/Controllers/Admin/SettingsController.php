<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => SiteSetting::resolved()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->validate(['brand_name' => ['required', 'string', 'max:80'], 'tagline' => ['required', 'string', 'max:160'], 'announcement' => ['nullable', 'string', 'max:240'], 'email' => ['required', 'email', 'max:160'], 'phone' => ['nullable', 'string', 'max:40'], 'instagram' => ['nullable', 'url', 'max:255'], 'facebook' => ['nullable', 'url', 'max:255'], 'seo_title' => ['required', 'string', 'max:70'], 'seo_description' => ['required', 'string', 'max:160'], 'standard_shipping_cost' => ['required', 'numeric', 'min:0']]);
        $settings = SiteSetting::updateOrCreate(['id' => 1], ['values' => $values, 'updated_by' => $request->user()->id]);

        return back()->with('success', 'Configuración actualizada.');
    }
}
