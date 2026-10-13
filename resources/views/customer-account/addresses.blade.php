@extends('layouts.storefront')

@section('title', 'Mis direcciones — Luxérie')

@section('content')
<section class="account-dashboard">
    <div class="account-section-heading"><div><p class="eyebrow">Mi cuenta</p><h1>Mis direcciones</h1></div><a class="text-link" href="{{ route('customer.account') }}">Volver a mi cuenta</a></div>
    @if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif
    <div class="address-management">
        <form class="account-card address-form" method="POST" action="{{ route('customer.addresses.store') }}">@csrf
            <p class="eyebrow">Nueva dirección</p><h2>Guardar dirección</h2>
            <label>Nombre para identificarla<input name="label" value="{{ old('label') }}" placeholder="Casa, oficina..." required></label>
            <label>Nombre de quien recibe<input name="recipient_name" value="{{ old('recipient_name', auth('customer')->user()->name) }}" required></label>
            <label>Teléfono<input name="phone" value="{{ old('phone', auth('customer')->user()->phone) }}"></label>
            <label>Calle y número<input name="address_line_1" value="{{ old('address_line_1') }}" required></label>
            <label>Interior o referencias<input name="address_line_2" value="{{ old('address_line_2') }}"></label>
            <label>Colonia<input name="neighborhood" value="{{ old('neighborhood') }}"></label>
            <label>Ciudad o municipio<input name="city" value="{{ old('city') }}" required></label>
            <label>Estado<input name="state" value="{{ old('state') }}" required></label>
            <label>Código postal<input name="postal_code" value="{{ old('postal_code') }}" inputmode="numeric" pattern="[0-9]{5}" required></label>
            <button class="button button-dark" type="submit">Guardar dirección</button>
        </form>
        <div class="saved-addresses">
            @forelse($addresses as $address)
                <article class="saved-address"><div><strong>{{ $address->label }}</strong>@if($address->is_default)<span class="status success">Predeterminada</span>@endif<p>{{ $address->recipient_name }}<br>{{ $address->address_line_1 }}@if($address->address_line_2), {{ $address->address_line_2 }}@endif<br>{{ $address->neighborhood }}{{ $address->neighborhood ? ', ' : '' }}{{ $address->city }}, {{ $address->state }}. CP {{ $address->postal_code }}</p></div><div class="address-actions">@unless($address->is_default)<form method="POST" action="{{ route('customer.addresses.default', $address) }}">@csrf @method('PATCH')<button class="text-link" type="submit">Usar como predeterminada</button></form>@endunless<form method="POST" action="{{ route('customer.addresses.destroy', $address) }}">@csrf @method('DELETE')<button class="text-link" type="submit">Eliminar</button></form></div></article>
            @empty
                <p class="account-empty">Aún no tienes direcciones guardadas.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
