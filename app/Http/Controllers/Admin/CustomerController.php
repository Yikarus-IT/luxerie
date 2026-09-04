<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('admin.customers.index', [
            'customers' => Customer::withCount('orders')->latest()->paginate(20),
        ]);
    }

    public function show(Customer $customer): View
    {
        return view('admin.customers.show', [
            'customer' => $customer->load(['orders' => fn ($q) => $q->latest()->with('items')]),
        ]);
    }

    public function create(): View
    {
        return view('admin.customers.form', ['customer' => new Customer]);
    }

    public function store(Request $request): RedirectResponse
    {
        Customer::create($this->validated($request));

        return redirect()->route('admin.customers.index')->with('success', 'Cliente creado correctamente.');
    }

    public function edit(Customer $customer): View
    {
        return view('admin.customers.form', compact('customer'));
    }

    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validated($request, $customer));

        return redirect()->route('admin.customers.index')->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->orders()->exists()) {
            return back()->withErrors(['customer' => 'No se puede eliminar porque tiene pedidos asociados.']);
        }

        $customer->delete();

        return back()->with('success', 'Cliente eliminado correctamente.');
    }

    private function validated(Request $request, ?Customer $customer = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180', Rule::unique('customers')->ignore($customer)],
            'phone' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
