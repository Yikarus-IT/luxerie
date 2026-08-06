@extends('layouts.storefront')

@section('title', $product->name.' — Luxérie')

@section('content')
<section class="product-detail">
    <div class="product-detail-image">
        <img src="{{ $product->image_url ?: asset('images/brand/product-cutout.jpeg') }}" alt="{{ $product->name }}" fetchpriority="high">
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
        <button class="button button-dark button-full product-cart-button" type="button" disabled>Agregar al carrito — próximo prototipo</button>
        <dl class="detail-list">
            <div><dt>Tamaño</dt><dd>{{ $product->size_label ?: '—' }}</dd></div>
            <div><dt>Disponibilidad</dt><dd>{{ $product->stock > 0 ? 'Disponible' : 'Agotado' }}</dd></div>
        </dl>
    </div>
</section>
@endsection
