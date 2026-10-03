@extends('layouts.storefront')

@section('title', 'Luxérie Clara — Tu luminosidad, tu esencia')

@php
    $banners = [
        ['file' => 'hero-banner.jpeg', 'alt' => 'Luxérie Clara: tu rutina para una piel más uniforme'],
        ['file' => 'routine-banner.jpeg', 'alt' => 'Cuida tu piel todos los días con Luxérie Clara'],
        ['file' => 'ingredients-banner.jpeg', 'alt' => 'Ingredientes naturales de la crema Luxérie Clara'],
        ['file' => 'hydration-banner.jpeg', 'alt' => 'Hidratación profunda para una piel más uniforme'],
        ['file' => 'texture-banner.jpeg', 'alt' => 'Textura ligera y rápida absorción'],
        ['file' => 'usage-banner.jpeg', 'alt' => 'Modo de uso de Luxérie Clara'],
        ['file' => 'results-banner.jpeg', 'alt' => 'Resultados visibles desde las primeras semanas'],
        ['file' => 'formula-banner.jpeg', 'alt' => 'Fórmula con ingredientes naturales'],
    ];
@endphp

@section('content')
<div class="luxerie-banner-page">
    <section class="luxerie-banner luxerie-banner-first" id="about" data-reveal>
        <img src="{{ asset('images/luxerie/'.$banners[0]['file']) }}" alt="{{ $banners[0]['alt'] }}" fetchpriority="high">
    </section>

    <section class="statement luxerie-statement" data-reveal>
        <p class="eyebrow">El enfoque Luxérie</p>
        <h2>Una rutina sencilla que convierte el cuidado diario en un momento para ti.</h2>
        <p>Su textura cremosa se integra con suavidad a tu ritual de mañana y noche, dejando una sensación hidratada y confortable.</p>
    </section>

    @foreach(array_slice($banners, 1) as $index => $banner)
        <section class="luxerie-banner luxerie-banner-motion-{{ $index % 4 }}" @if($index === 4) id="ritual" @endif data-reveal>
            <img src="{{ asset('images/luxerie/'.$banner['file']) }}" alt="{{ $banner['alt'] }}" loading="lazy">
        </section>
    @endforeach

    <section class="luxerie-purchase" data-reveal>
        <div>
            <p class="eyebrow">Hazlo parte de tu rutina</p>
            <h1>Tu piel, tu momento.</h1>
            <p>Descubre Luxérie Clara y transforma el cuidado diario en un ritual sencillo.</p>
        </div>
        <a class="button button-dark" href="{{ route('shop') }}">Comprar Luxérie Clara</a>
    </section>

    @if($featuredProducts->isNotEmpty())
        <section class="collection luxerie-collection" data-reveal>
            <div class="section-heading">
                <div>
                    <p class="eyebrow">{{ $featuredProducts->count() === 1 ? 'Nuestro esencial' : 'Los esenciales' }}</p>
                    <h2>{{ $featuredProducts->count() === 1 ? 'Una crema creada para tu ritual diario.' : 'Hechos para tu tocador y para tu piel.' }}</h2>
                </div>
                <a class="text-link" href="{{ route('shop') }}">Ver producto →</a>
            </div>
            <div class="product-grid">
                @foreach($featuredProducts as $product)
                    @include('storefront.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
