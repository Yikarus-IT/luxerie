<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Luxérie — considered skincare for everyday rituals.">
    <title>@yield('title', 'Luxérie')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="storefront">
    <div class="announcement">Complimentary shipping in Mexico on orders over $900 MXN</div>
    <header class="site-header">
        <a class="wordmark" href="{{ route('home') }}">Luxérie</a>
        <nav aria-label="Main navigation">
            <a href="{{ route('shop') }}">Shop</a>
            <a href="{{ route('home') }}#ritual">The ritual</a>
            <a href="{{ route('home') }}#about">Our approach</a>
        </nav>
        <a class="header-action" href="{{ route('shop') }}">Bag <span>0</span></a>
    </header>
    <main>@yield('content')</main>
    <footer class="site-footer">
        <div><a class="wordmark wordmark-light" href="{{ route('home') }}">Luxérie</a><p>Beauty, considered.</p></div>
        <div><strong>Explore</strong><a href="{{ route('shop') }}">Shop all</a><a href="#about">Our approach</a></div>
        <div><strong>Support</strong><a href="mailto:hola@luxerie.mx">hola@luxerie.mx</a><a href="{{ route('login') }}">Administration</a></div>
        <p class="copyright">© {{ date('Y') }} Luxérie. Prototype storefront.</p>
    </footer>
</body>
</html>
