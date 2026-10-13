@extends('layouts.storefront')

@section('title', 'Mis datos — Luxérie')

@section('content')
<section class="account-page">
    <div class="account-card">
        <p class="eyebrow">Mi cuenta</p>
        <h1>Mis datos</h1>
        <p>Usaremos esta información para facilitar tus próximas compras.</p>
        @if($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif
        <form class="account-form" method="POST" action="{{ route('customer.profile.update') }}">@csrf @method('PUT')
            <label>Nombre completo<input name="name" value="{{ old('name', $customer->name) }}" autocomplete="name" required></label>
            <label>Correo electrónico<input type="email" name="email" value="{{ old('email', $customer->email) }}" autocomplete="email" required></label>
            <label>Teléfono<input name="phone" value="{{ old('phone', $customer->phone) }}" autocomplete="tel"></label>
            <button class="button button-dark button-full" type="submit">Guardar cambios</button>
        </form>
        <a class="text-link" href="{{ route('customer.account') }}">Volver a mi cuenta</a>
    </div>
</section>
@endsection
