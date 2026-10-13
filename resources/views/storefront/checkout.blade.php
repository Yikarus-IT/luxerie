@extends('layouts.storefront')

@section('title', 'Checkout — Luxérie')

@section('content')
<section class="commerce-page">
    <div class="commerce-heading"><p class="eyebrow">Compra segura</p><h1>Datos de envío</h1></div>
    @if($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif
    <form class="commerce-layout checkout-form" method="POST" action="{{ route('checkout.store') }}">@csrf
        <div class="form-panel">@if($addresses->isNotEmpty())<label class="span-2">Usar una dirección guardada<select name="shipping_address_id" data-shipping-address><option value="">Escribir otra dirección</option>@foreach($addresses as $address)<option value="{{ $address->id }}" data-name="{{ $address->recipient_name }}" data-phone="{{ $address->phone }}" data-line1="{{ $address->address_line_1 }}" data-line2="{{ $address->address_line_2 }}" data-neighborhood="{{ $address->neighborhood }}" data-city="{{ $address->city }}" data-state="{{ $address->state }}" data-postal-code="{{ $address->postal_code }}" @selected(old('shipping_address_id') == $address->id)>{{ $address->label }}{{ $address->is_default ? ' (predeterminada)' : '' }}</option>@endforeach</select></label>@endif<div class="form-grid">
            <label>Nombre completo<input name="customer_name" value="{{ old('customer_name', $customer?->name) }}" autocomplete="name" required></label>
            <label>Correo electrónico<input type="email" name="customer_email" value="{{ old('customer_email', $customer?->email) }}" autocomplete="email" required></label>
            <label>Teléfono<input name="customer_phone" value="{{ old('customer_phone', $customer?->phone) }}" autocomplete="tel" required></label>
            <label>Código postal<input name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" inputmode="numeric" pattern="[0-9]{5}" autocomplete="postal-code" required></label>
            <label class="span-2">Calle y número<input name="shipping_address_line_1" value="{{ old('shipping_address_line_1') }}" autocomplete="address-line1" required></label>
            <label class="span-2">Interior o referencias<input name="shipping_address_line_2" value="{{ old('shipping_address_line_2') }}" autocomplete="address-line2"></label>
            <label>Colonia<input name="shipping_neighborhood" value="{{ old('shipping_neighborhood') }}"></label>
            <label>Ciudad o municipio<input name="shipping_city" value="{{ old('shipping_city') }}" autocomplete="address-level2" required></label>
            <label>Estado<input name="shipping_state" value="{{ old('shipping_state') }}" autocomplete="address-level1" required></label>
            <label class="span-2">Notas para tu pedido<textarea name="customer_notes" rows="3">{{ old('customer_notes') }}</textarea></label>
        </div></div></div>
        <aside class="order-summary">
            <h2>Tu pedido</h2>
            @foreach($cart->items as $item)<p><span>{{ $item->quantity }} × {{ $item->product->name }}</span><strong>${{ number_format($item->quantity * $item->unit_price, 2) }}</strong></p>@endforeach
            <p><span>Envío</span><strong>Incluido</strong></p>
            <p class="order-total"><span>Total</span><strong>${{ number_format($totals['total'], 2) }} MXN</strong></p>
            <p class="payment-note">Pagarás de forma segura en Mercado Pago. Luxérie no almacena los datos de tu tarjeta.</p>
            <button class="button button-dark button-full" type="submit">Crear pedido y continuar</button>
        </aside>
    </form>
</section>
@endsection
