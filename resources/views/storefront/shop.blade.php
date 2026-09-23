@extends('layouts.storefront')
@section('title', 'Tienda — Luxérie')
@section('content')
<header class="page-hero"><div class="page-hero-inner"><p class="eyebrow">{{ $products->total() === 1 ? 'Nuestro esencial' : 'La colección' }}</p><h1>Cuidado, con intención.</h1><p>{{ $products->total() === 1 ? 'Una crema creada para convertir el cuidado diario de la piel en un ritual.' : 'Esenciales diarios diseñados para convertir el cuidado eficaz de la piel en un ritual.' }}</p></div></header>
<section class="collection shop-collection">
    <div class="product-grid {{ $products->total() === 1 ? 'product-grid-single' : '' }}">
        @forelse($products as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @empty
            <div class="catalog-empty"><p class="eyebrow">Próximamente</p><h2>Estamos preparando nuestro primer esencial.</h2><p>Vuelve pronto para descubrir Luxérie Clara.</p></div>
        @endforelse
    </div>
    {{ $products->links() }}
</section>
@endsection
