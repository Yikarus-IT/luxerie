<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerAccountController extends Controller
{
    public function dashboard(Request $request): View
    {
        $customer = $request->user('customer');

        return view('customer-account.dashboard', [
            'customer' => $customer,
            'orders' => $customer->orders()->with('items')->latest()->paginate(10),
        ]);
    }

    public function edit(Request $request): View
    {
        return view('customer-account.profile', ['customer' => $request->user('customer')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $request->user('customer');
        $data = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone' => ['nullable', 'string', 'max:40'],
        ]);

        $customer->update($data);

        return redirect()->route('customer.account')->with('success', 'Tus datos se actualizaron correctamente.');
    }
}
