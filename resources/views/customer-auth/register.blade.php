@extends('layouts.storefront')

@section('title', 'Crear cuenta — Luxérie')

@section('content')
<section class="account-page">
    <div class="account-card">
        <p class="eyebrow">Tu cuenta Luxérie</p>
        <h1>Crea tu cuenta.</h1>
        <p>Guarda tus datos y consulta tus futuras compras desde un solo lugar.</p>
        <form method="POST" action="{{ route('customer.register.store') }}" class="account-form">
            @csrf
            <label>Nombre completo<input type="text" name="name" value="{{ old('name') }}" required autocomplete="name"></label>
            <label>Correo electrónico<input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
            <label>Contraseña<input type="password" name="password" required autocomplete="new-password"></label>
            <label>Confirmar contraseña<input type="password" name="password_confirmation" required autocomplete="new-password"></label>
            @if($errors->any())<p class="field-error">{{ $errors->first() }}</p>@endif
            <button class="button button-dark button-full" type="submit">Crear cuenta</button>
        </form>
        <p class="account-switch">¿Ya tienes cuenta? <a href="{{ route('customer.login') }}">Inicia sesión</a></p>
    </div>
</section>
@endsection
