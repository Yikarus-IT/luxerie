@extends('layouts.storefront')
@section('title', 'Shop — Luxérie')
@section('content')
<header class="page-hero"><p class="eyebrow">The collection</p><h1>Care, with intention.</h1><p>Daily essentials designed to turn effective skincare into a ritual.</p></header>
<section class="collection shop-collection"><div class="product-grid">@foreach($products as $product) @include('storefront.partials.product-card', ['product' => $product]) @endforeach</div>{{ $products->links() }}</section>
@endsection
