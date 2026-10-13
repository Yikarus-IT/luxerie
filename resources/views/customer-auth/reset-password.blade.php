@extends('layouts.storefront')

@section('title', 'Restablecer contraseña — Luxérie')

@section('content')
<section class="account-page"><div class="account-card"><p class="eyebrow">Tu cuenta Luxérie</p><h1>Elige una nueva contraseña.</h1><form method="POST" action="{{ route('customer.password.update') }}" class="account-form">@csrf<input type="hidden" name="token" value="{{ $token }}"><label>Correo electrónico<input type="email" name="email" value="{{ old('email', $email) }}" required autocomplete="email"></label><label>Nueva contraseña<input type="password" name="password" required autocomplete="new-password"></label><label>Confirmar contraseña<input type="password" name="password_confirmation" required autocomplete="new-password"></label>@if($errors->any())<p class="field-error">{{ $errors->first() }}</p>@endif<button class="button button-dark button-full" type="submit">Guardar contraseña</button></form></div></section>
@endsection
