@extends('layouts.admin')
@section('title', 'Usuarios')
@section('heading', 'Equipo y permisos')
@section('content')
<div class="management-grid">
    <form class="form-panel compact-form" method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <h2>Nuevo usuario</h2>
        <label>Nombre<input name="name" required></label>
        <label>Correo<input type="email" name="email" required></label>
        <label>Contraseña<input type="password" name="password" minlength="8" required></label>
        <label>Rol<select name="role"><option value="content_editor">Editor de contenido</option><option value="product_manager">Gestor de productos</option><option value="read_only">Solo lectura</option><option value="administrator">Administrador</option></select></label>
        <button class="button button-dark">Crear usuario</button>
    </form>
    <section class="admin-panel">
        <h2>Usuarios</h2>
        @foreach($users as $user)
            <form class="management-row" method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')
                <label>Nombre<input name="name" value="{{ $user->name }}" required></label>
                <label>Correo<input type="email" name="email" value="{{ $user->email }}" required></label>
                <label>Nueva contraseña<input type="password" name="password" minlength="8" placeholder="Dejar vacía para conservar"></label>
                <label>Rol<select name="role">@foreach(['administrator'=>'Administrador','content_editor'=>'Editor de contenido','product_manager'=>'Gestor de productos','read_only'=>'Solo lectura'] as $key=>$label)<option value="{{ $key }}" @selected($user->role === $key)>{{ $label }}</option>@endforeach</select></label>
                <button class="button button-small">Guardar</button>
                @if($user->id !== auth()->id())<button class="text-link" type="submit" form="delete-user-{{ $user->id }}">Eliminar</button>@endif
            </form>
            @if($user->id !== auth()->id())
                <form id="delete-user-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm="¿Eliminar este usuario?">@csrf @method('DELETE')</form>
            @endif
        @endforeach
    </section>
</div>
@endsection
