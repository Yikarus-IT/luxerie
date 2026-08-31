@extends('layouts.admin')
@section('title', $customer->exists ? 'Editar cliente' : 'Nuevo cliente')
@section('heading', $customer->exists ? 'Editar cliente' : 'Nuevo cliente')
@section('content')
<form class="admin-form" method="POST" action="{{ $customer->exists ? route('admin.customers.update', $customer) : route('admin.customers.store') }}">@csrf @if($customer->exists) @method('PUT') @endif
<section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">Información del cliente</p><h2>Datos de contacto</h2></div></div><div class="form-grid"><label class="span-2">Nombre<input name="name" value="{{ old('name', $customer->name) }}" required></label><label class="span-2">Email<input type="email" name="email" value="{{ old('email', $customer->email) }}" required></label><label>Teléfono<input name="phone" value="{{ old('phone', $customer->phone) }}"></label></div></section>
<section class="form-panel"><div class="panel-heading"><div><p class="eyebrow">Notas</p><h2>Información adicional</h2></div></div><div class="form-grid"><label class="span-2">Notas<textarea name="notes" rows="4">{{ old('notes', $customer->notes) }}</textarea></label></div></section>
<div class="form-actions"><a href="{{ route('admin.customers.index') }}">Cancelar</a><button class="button button-dark" type="submit">{{ $customer->exists ? 'Guardar cambios' : 'Crear cliente' }}</button></div></form>
@endsection
