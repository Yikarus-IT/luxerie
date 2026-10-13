<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerAddressController extends Controller
{
    public function index(Request $request): View
    {
        return view('customer-account.addresses', ['addresses' => $request->user('customer')->addresses()->latest('is_default')->latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $customer = $request->user('customer');
        $data = $request->validate($this->rules());
        $data['country'] = 'MX';
        $data['is_default'] = $customer->addresses()->doesntExist();
        $customer->addresses()->create($data);

        return back()->with('success', 'Dirección guardada correctamente.');
    }

    public function destroy(Request $request, CustomerAddress $address): RedirectResponse
    {
        abort_unless($address->customer_id === $request->user('customer')->id, 404);
        $address->delete();

        return back()->with('success', 'Dirección eliminada.');
    }

    public function makeDefault(Request $request, CustomerAddress $address): RedirectResponse
    {
        $customer = $request->user('customer');
        abort_unless($address->customer_id === $customer->id, 404);
        $customer->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('success', 'Dirección predeterminada actualizada.');
    }

    private function rules(): array
    {
        return ['label' => ['required', 'string', 'max:80'], 'recipient_name' => ['required', 'string', 'max:160'], 'phone' => ['nullable', 'string', 'max:40'], 'address_line_1' => ['required', 'string', 'max:255'], 'address_line_2' => ['nullable', 'string', 'max:255'], 'neighborhood' => ['nullable', 'string', 'max:160'], 'city' => ['required', 'string', 'max:120'], 'state' => ['required', 'string', 'max:120'], 'postal_code' => ['required', 'regex:/^\d{5}$/']];
    }
}
