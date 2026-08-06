<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Luxérie — cuidado consciente para tus rituales diarios.">
    <title>@yield('title', 'Luxérie')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="storefront">
    <div class="announcement">Envío de cortesía en México en compras mayores a $900 MXN</div>
    <header class="site-header">
        <a class="wordmark" href="{{ route('home') }}">Luxérie</a>
        <nav class="desktop-navigation" aria-label="Navegación principal">
            <a href="{{ route('shop') }}">Tienda</a>
            <a href="{{ route('home') }}#ritual">El ritual</a>
            <a href="{{ route('home') }}#about">Nuestro enfoque</a>
        </nav>
        <div class="header-tools">
            <a class="header-action" href="{{ route('shop') }}">Carrito <span>0</span></a>
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
            <a href="{{ route('shop') }}">Carrito <span>0</span></a>
        </nav>
    </div>
    <main>@yield('content')</main>
    <footer class="site-footer">
        <div class="footer-brand"><a class="wordmark wordmark-light" href="{{ route('home') }}">Luxérie</a><p>Belleza, con intención.</p></div>
        <div class="footer-links"><strong>Explora</strong><a href="{{ route('shop') }}">Ver producto</a><a href="{{ route('home') }}#about">Nuestro enfoque</a><a href="{{ route('home') }}#ritual">El ritual</a></div>
        <div class="footer-links"><strong>Ayuda</strong><a href="mailto:hola@luxerie.mx">hola@luxerie.mx</a><a href="{{ route('login') }}">Administración</a></div>
        <p class="copyright">© {{ date('Y') }} Luxérie. Tienda prototipo.</p>
    </footer>
</body>
</html>
