<!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>@yield('title', 'Admin') — Luxérie</title>@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="admin-shell">
<aside class="admin-sidebar"><a class="wordmark wordmark-light" href="{{ route('admin.dashboard') }}">Luxérie</a><p>Administration</p><nav><a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Overview</a><a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">Products</a><a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">Categories</a><a href="{{ route('home') }}" target="_blank">View storefront ↗</a></nav><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Sign out</button></form></aside>
<main class="admin-main">
    <header class="admin-topbar"><div><p class="eyebrow">Luxérie workspace</p><h1>@yield('heading')</h1></div><span>{{ auth()->user()->name }}</span></header>
    @if(session('success'))
        <div class="notice success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="notice error">{{ $errors->first() }}</div>
    @endif
    @yield('content')
</main>
</body></html>
