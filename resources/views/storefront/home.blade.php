@extends('layouts.storefront')

@section('title', 'Luxérie Clara — Tu luminosidad, tu esencia')

@section('content')
@if($isPreview)<div class="preview-banner">Vista previa del borrador — solo visible para administradores</div>@endif
<div class="home-sections">
<section class="hero campaign-hero" style="order:{{ data_get($homepageLayout, 'hero.order') }};{{ data_get($homepageLayout, 'hero.visible') ? '' : 'display:none' }}">
    <div class="hero-copy" data-reveal>
        <p class="eyebrow">{{ data_get($homepageContent, 'hero.eyebrow') }}</p>
        <h1>{{ data_get($homepageContent, 'hero.heading') }}<br><em>{{ data_get($homepageContent, 'hero.highlight') }}</em></h1>
        <p class="hero-intro">{{ data_get($homepageContent, 'hero.body') }}</p>
        <div class="actions">
            <a class="button button-dark" href="{{ route('shop') }}">{{ data_get($homepageContent, 'hero.button') }}</a>
            <a class="text-link" href="#beneficios">Conoce sus beneficios →</a>
        </div>
        <div class="hero-notes" aria-label="Características del producto">
            <span>Unifica</span><span>Hidrata</span><span>Ilumina</span>
        </div>
    </div>
    <div class="hero-art hero-photo" aria-label="Crema Luxérie Clara sostenida entre las manos">
        <img src="{{ $homepageImages['hero'] ?? asset('images/brand/hands-open-jar.jpeg') }}" alt="{{ $homepageImageAlts['hero'] ?? 'Manos sosteniendo un frasco abierto de crema Luxérie Clara' }}" fetchpriority="high" style="object-position:{{ $homepageImagePositions['hero'] ?? '50% 50%' }}">
        <div class="hero-image-caption"><span>Luxérie Clara</span><small>Tu luminosidad, tu esencia.</small></div>
    </div>
</section>

<section class="statement" id="about" data-reveal style="order:{{ data_get($homepageLayout, 'about.order') }};{{ data_get($homepageLayout, 'about.visible') ? '' : 'display:none' }}">
    <p class="eyebrow">{{ data_get($homepageContent, 'about.eyebrow') }}</p>
    <h2>{{ data_get($homepageContent, 'about.heading') }}</h2>
    <p>{{ data_get($homepageContent, 'about.body') }}</p>
</section>

<section class="story-section" id="beneficios" style="order:{{ data_get($homepageLayout, 'benefits.order') }};{{ data_get($homepageLayout, 'benefits.visible') ? '' : 'display:none' }}">
    <div class="section-heading" data-reveal>
        <div><p class="eyebrow">{{ data_get($homepageContent, 'benefits.eyebrow') }}</p><h2>{{ data_get($homepageContent, 'benefits.heading') }}</h2></div>
        <div class="slider-controls" aria-label="Controles del carrusel de beneficios">
            <button class="slider-button benefits-prev" type="button" aria-label="Imagen anterior">←</button>
            <button class="slider-button benefits-next" type="button" aria-label="Imagen siguiente">→</button>
        </div>
    </div>
    <div class="swiper benefits-swiper" data-reveal>
        <div class="swiper-wrapper">
            @php($benefitFallbacks = ['images/brand/luminosity-product.jpeg','images/brand/formula-model.jpeg','images/brand/skin-causes.jpeg','images/brand/application-texture.jpeg'])
            @foreach(data_get($homepageContent, 'benefits.captions') as $index => $caption)
                <article class="swiper-slide story-card story-card-artwork"><img src="{{ $homepageImages['benefit_'.$index] ?? asset($benefitFallbacks[$index % count($benefitFallbacks)]) }}" alt="{{ $homepageImageAlts['benefit_'.$index] ?? $caption }}" loading="lazy" style="object-position:{{ $homepageImagePositions['benefit_'.$index] ?? '50% 50%' }}"><p>{{ $caption }}</p></article>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<section class="product-focus" style="order:{{ data_get($homepageLayout, 'product.order') }};{{ data_get($homepageLayout, 'product.visible') ? '' : 'display:none' }}">
    <div class="product-focus-media" data-reveal>
        <div class="swiper product-swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide product-slide product-slide-contain"><img src="{{ $homepageImages['product_0'] ?? asset('images/brand/product-cutout.jpeg') }}" alt="{{ $homepageImageAlts['product_0'] ?? 'Frasco abierto de crema Luxérie Clara sobre fondo blanco' }}" loading="lazy"></div>
                <div class="swiper-slide product-slide product-slide-photo"><img src="{{ $homepageImages['product_1'] ?? asset('images/brand/hands-open-jar.jpeg') }}" alt="{{ $homepageImageAlts['product_1'] ?? 'Frasco abierto de Luxérie Clara sostenido entre las manos' }}" loading="lazy"></div>
                <div class="swiper-slide product-slide product-slide-artwork"><img src="{{ $homepageImages['product_2'] ?? asset('images/brand/product-benefits.jpeg') }}" alt="{{ $homepageImageAlts['product_2'] ?? 'Presentación y beneficios de la crema Luxérie Clara' }}" loading="lazy"></div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
    <div class="product-focus-copy" data-reveal>
        <p class="eyebrow">{{ data_get($homepageContent, 'product.eyebrow') }}</p>
        <h2>{{ data_get($homepageContent, 'product.heading') }}</h2>
        <p class="lead">{{ data_get($homepageContent, 'product.body') }}</p>
        <ul class="benefit-list">@foreach(data_get($homepageContent, 'product.benefits') as $benefit)<li>{{ $benefit }}</li>@endforeach</ul>
        <a class="button button-dark product-focus-action" href="{{ route('shop') }}">Ver producto</a>
        <small class="claim-note">Los resultados pueden variar según la piel y la constancia de uso.</small>
    </div>
</section>

<section class="collection" style="order:{{ data_get($homepageLayout, 'collection.order') }};{{ data_get($homepageLayout, 'collection.visible') ? '' : 'display:none' }}">
    <div class="section-heading" data-reveal><div><p class="eyebrow">{{ $featuredProducts->count() === 1 ? 'Nuestro esencial' : 'Los esenciales' }}</p><h2>{{ $featuredProducts->count() === 1 ? 'Una crema creada para tu ritual diario.' : 'Hechos para tu tocador y para tu piel.' }}</h2></div><a class="text-link" href="{{ route('shop') }}">{{ $featuredProducts->count() === 1 ? 'Conocer el producto' : 'Ver todos los productos' }} →</a></div>
    <div class="product-grid {{ $featuredProducts->count() === 1 ? 'product-grid-single' : '' }}" data-reveal>
        @forelse($featuredProducts as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @empty
            <p>Aún no hay productos destacados. Agrega uno desde el área de administración.</p>
        @endforelse
    </div>
</section>

<section class="ritual" id="ritual" style="order:{{ data_get($homepageLayout, 'ritual.order') }};{{ data_get($homepageLayout, 'ritual.visible') ? '' : 'display:none' }}">
    <div class="ritual-visual ritual-photo" data-reveal><img src="{{ $homepageImages['ritual'] ?? asset('images/brand/usage-guide.jpeg') }}" alt="{{ $homepageImageAlts['ritual'] ?? 'Modo de uso de Luxérie Clara paso a paso' }}" loading="lazy"></div>
    <div class="ritual-copy" data-reveal><p class="eyebrow">{{ data_get($homepageContent, 'ritual.eyebrow') }}</p><h2>{{ data_get($homepageContent, 'ritual.heading') }}</h2><ol><li><span>01</span><div><strong>Prepara</strong><p>Comienza con el rostro y las manos limpios y secos.</p></div></li><li><span>02</span><div><strong>Aplica</strong><p>Distribuye una pequeña cantidad con movimientos suaves.</p></div></li><li><span>03</span><div><strong>Protege</strong><p>Durante el día, termina tu rutina con protector solar.</p></div></li></ol><p class="ritual-note">{{ data_get($homepageContent, 'ritual.note') }}</p></div>
</section>

<section class="testimonial-section" style="order:{{ data_get($homepageLayout, 'testimonial.order') }};{{ data_get($homepageLayout, 'testimonial.visible') ? '' : 'display:none' }}">
    <div class="testimonial-copy" data-reveal><p class="eyebrow">{{ data_get($homepageContent, 'testimonial.eyebrow') }}</p><blockquote>{{ $featuredTestimonial?->quote ?? data_get($homepageContent, 'testimonial.quote') }}</blockquote><p>{{ $featuredTestimonial?->name ? '— '.$featuredTestimonial->name : data_get($homepageContent, 'testimonial.body') }}</p><small>{{ data_get($homepageContent, 'testimonial.note') }}</small></div>
    <div class="testimonial-image" data-reveal><img src="{{ $featuredTestimonial?->mediaAsset?->image()?->getUrl() ?? $homepageImages['testimonial'] ?? asset('images/brand/testimonial.jpeg') }}" alt="{{ $featuredTestimonial?->mediaAsset?->alt_text ?? $homepageImageAlts['testimonial'] ?? 'Mujer compartiendo su experiencia con Luxérie Clara' }}" loading="lazy"></div>
</section>
</div>
@endsection
