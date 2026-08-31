<!DOCTYPE html>
<html lang="es-MX"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>@yield('title', 'Administración') — Luxérie</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="admin-shell">
<header class="admin-mobile-header"><a class="wordmark" href="{{ route('admin.dashboard') }}">Luxérie</a><button class="admin-menu-toggle" type="button" aria-expanded="false" aria-controls="admin-sidebar" aria-label="Abrir menú de administración"><span></span><span></span><span></span></button></header>
<button class="admin-sidebar-backdrop" type="button" data-admin-menu-close aria-label="Cerrar menú de administración" hidden></button>
<aside class="admin-sidebar" id="admin-sidebar">
    <button class="admin-sidebar-close" type="button" data-admin-menu-close aria-label="Cerrar menú">×</button><a class="wordmark wordmark-light" href="{{ route('admin.dashboard') }}">Luxérie</a><p>Administración</p>
    <nav>
        <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Resumen</a>
        <a class="{{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}" href="{{ route('admin.homepage.edit') }}">Página de inicio</a>
        <a class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}" href="{{ route('admin.testimonials.index') }}">Testimonios</a>
        <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Productos</a>
        <a class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Pedidos</a>
        <a class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">Clientes</a>
        <a class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">Medios</a>
        <a class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.edit') }}">Configuración</a>
        <a href="{{ route('home') }}" target="_blank">Ver tienda ↗</a>
    </nav>
    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Cerrar sesión</button></form>
</aside>
<main class="admin-main"><header class="admin-topbar"><div><p class="eyebrow">Espacio de trabajo Luxérie</p><h1>@yield('heading')</h1></div><span>{{ auth()->user()->name }}</span></header>@if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif @if($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif @yield('content')</main>
</body></html>
