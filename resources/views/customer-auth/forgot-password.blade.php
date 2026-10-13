@extends('layouts.storefront')

@section('title', 'Recuperar contraseña — Luxérie')

@section('content')
<section class="account-page"><div class="account-card"><p class="eyebrow">Tu cuenta Luxérie</p><h1>Recupera tu acceso.</h1><p>Te enviaremos un enlace para restablecer tu contraseña.</p>@if(session('status'))<p class="notice success">{{ session('status') }}</p>@endif @if(session('error'))<p class="notice error">{{ session('error') }}</p>@endif<form method="POST" action="{{ route('customer.password.email') }}" class="account-form">@csrf<label>Correo electrónico<input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"></label>@error('email')<p class="field-error">{{ $message }}</p>@enderror<button class="button button-dark button-full" type="submit">Enviar enlace</button></form><a class="text-link" href="{{ route('customer.login') }}">← Volver a iniciar sesión</a></div></section>
@endsection
