@extends('layouts.storefront')

@section('title', 'Iniciar sesión — Luxérie')

@section('content')
<section class="account-page">
    <div class="account-card">
        <p class="eyebrow">Tu cuenta Luxérie</p>
        <h1>Qué gusto verte.</h1>
        <p>Inicia sesión para continuar con tu experiencia Luxérie.</p>
        @if(session('status'))<p class="notice success">{{ session('status') }}</p>@endif
        <form method="POST" action="{{ route('customer.login.store') }}" class="account-form">
            @csrf
            <label>Correo electrónico<input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"></label>
            <label>Contraseña<input type="password" name="password" required autocomplete="current-password"></label>
            <label class="check"><input type="checkbox" name="remember" value="1"> Mantener mi sesión iniciada</label>
            @if($errors->any())<p class="field-error">{{ $errors->first() }}</p>@endif
            <button class="button button-dark button-full" type="submit">Iniciar sesión</button>
        </form>
        <a class="text-link" href="{{ route('customer.password.request') }}">¿Olvidaste tu contraseña?</a>
        <p class="account-switch">¿Aún no tienes cuenta? <a href="{{ route('customer.register') }}">Crear cuenta</a></p>
    </div>
</section>
@endsection
