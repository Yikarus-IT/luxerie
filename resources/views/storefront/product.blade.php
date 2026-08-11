@extends('layouts.storefront')

@section('title', $product->seo_title ?: $product->name.' — Luxérie')
@section('meta_description', $product->seo_description ?: $product->short_description)

@section('content')
<section class="product-detail">
    <div class="product-detail-image">
        @if($productGallery->isNotEmpty())<div class="swiper product-detail-swiper"><div class="swiper-wrapper"><div class="swiper-slide"><img src="{{ $product->displayImageUrl() }}" alt="{{ $product->displayImageAlt() }}" fetchpriority="high" style="object-position:{{ $product->displayFocalPoint() }}"></div>@foreach($productGallery as $galleryImage)<div class="swiper-slide"><img src="{{ $galleryImage->image()?->getUrl() }}" alt="{{ $galleryImage->alt_text }}" loading="lazy" style="object-position:{{ $galleryImage->focal_x }}% {{ $galleryImage->focal_y }}%"></div>@endforeach</div><div class="swiper-pagination"></div></div>@else<img src="{{ $product->displayImageUrl() }}" alt="{{ $product->displayImageAlt() }}" fetchpriority="high" style="object-position:{{ $product->displayFocalPoint() }}">@endif
    </div>
    <div class="product-detail-copy">
        <a class="product-back-link text-link" href="{{ route('shop') }}">← Volver a la tienda</a>
        <p class="eyebrow">{{ $product->category->name }}</p>
        <h1>{{ $product->name }}</h1>
        <div class="product-price-row">
            <p class="price">${{ number_format($product->price, 2) }} MXN</p>
            @if($product->compare_at_price && $product->compare_at_price > $product->price)
                <del>${{ number_format($product->compare_at_price, 2) }} MXN</del>
            @endif
        </div>
        <p class="lead">{{ $product->short_description }}</p>
        <div class="prose">{!! nl2br(e($product->description)) !!}</div>
        @if($product->stock > 0)<form method="POST" action="{{ route('cart.store', $product) }}">@csrf<input type="hidden" name="quantity" value="1"><button class="button button-dark button-full product-cart-button" type="submit">Agregar al carrito</button></form>@else<button class="button button-dark button-full product-cart-button" type="button" disabled>Agotado</button>@endif
        <dl class="detail-list">
            <div><dt>Tamaño</dt><dd>{{ $product->size_label ?: '—' }}</dd></div>
            <div><dt>Disponibilidad</dt><dd>{{ $product->stock > 0 ? 'Disponible' : 'Agotado' }}</dd></div>
        </dl>
        @if($product->ingredients || $product->directions || $product->precautions)<div class="product-information">@if($product->ingredients)<details><summary>Ingredientes</summary><p>{{ $product->ingredients }}</p></details>@endif @if($product->directions)<details><summary>Modo de uso</summary><p>{{ $product->directions }}</p></details>@endif @if($product->precautions)<details><summary>Precauciones</summary><p>{{ $product->precautions }}</p></details>@endif</div>@endif
    </div>
</section>
@endsection
