<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', $siteSettings['seo_description'])">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ request()->url() }}">
    <title>@yield('title', $siteSettings['seo_title'])</title>
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('title', $siteSettings['seo_title'])">
    <meta property="og:description" content="@yield('meta_description', $siteSettings['seo_description'])">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:locale" content="es_MX">
    
    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ request()->url() }}">
    <meta name="twitter:title" content="@yield('title', $siteSettings['seo_title'])">
    <meta name="twitter:description" content="@yield('meta_description', $siteSettings['seo_description'])">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.jpg'))">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="storefront">
    @if($siteSettings['announcement'])<div class="announcement" role="note">{{ $siteSettings['announcement'] }}</div>@endif
    <header class="site-header">
        <a class="wordmark" href="{{ route('home') }}">{{ $siteSettings['brand_name'] }}</a>
        <nav class="desktop-navigation" aria-label="Navegación principal">
            <a href="{{ route('shop') }}">Tienda</a>
            <a href="{{ route('home') }}#ritual">El ritual</a>
            <a href="{{ route('home') }}#about">Nuestro enfoque</a>
        </nav>
        <div class="header-tools">
            <a class="header-action" href="{{ route('cart.index') }}">Carrito <span>{{ $cartItemCount }}</span></a>
            <button class="mobile-menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-navigation" aria-label="Abrir menú">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>
    <div class="mobile-navigation" id="mobile-navigation" role="dialog" aria-modal="true" aria-label="Menú de navegación" hidden>
        <button class="mobile-navigation-backdrop" type="button" data-menu-close aria-label="Cerrar menú"></button>
        <nav aria-label="Navegación móvil">
            <p class="eyebrow">Menú</p>
            <a href="{{ route('shop') }}">Tienda</a>
            <a href="{{ route('home') }}#ritual">El ritual</a>
            <a href="{{ route('home') }}#about">Nuestro enfoque</a>
            <a href="{{ route('cart.index') }}">Carrito <span>{{ $cartItemCount }}</span></a>
        </nav>
    </div>
    <main>@yield('content')</main>
    <footer class="site-footer">
        <div class="footer-brand"><a class="wordmark wordmark-light" href="{{ route('home') }}">{{ $siteSettings['brand_name'] }}</a><p>{{ $siteSettings['tagline'] }}</p></div>
        <div class="footer-links"><strong>Explora</strong><a href="{{ route('shop') }}">Ver producto</a><a href="{{ route('home') }}#about">Nuestro enfoque</a><a href="{{ route('home') }}#ritual">El ritual</a></div>
        <div class="footer-links"><strong>Ayuda</strong><a href="{{ route('faqs') }}">Preguntas frecuentes</a><a href="mailto:{{ $siteSettings['email'] }}">{{ $siteSettings['email'] }}</a><a href="{{ route('login') }}">Administración</a></div>
        <p class="copyright">© {{ date('Y') }} {{ $siteSettings['brand_name'] }}. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
