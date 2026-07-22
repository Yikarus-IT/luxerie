@extends('layouts.storefront')
@section('title', $product->name.' — Luxérie')
@section('content')
<section class="product-detail">
    <div class="product-detail-image">@if($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->name }}">@else<div class="product-placeholder product-placeholder-large"><span>Luxérie</span><small>{{ $product->size_label }}</small></div>@endif</div>
    <div class="product-detail-copy"><p class="eyebrow">{{ $product->category->name }}</p><h1>{{ $product->name }}</h1><p class="price">${{ number_format($product->price, 2) }} MXN</p><p class="lead">{{ $product->short_description }}</p><div class="prose">{!! nl2br(e($product->description)) !!}</div><button class="button button-dark button-full" type="button" disabled>Add to bag — next prototype</button><div class="detail-list"><span>Size <strong>{{ $product->size_label ?: '—' }}</strong></span><span>Availability <strong>{{ $product->stock > 0 ? 'In stock' : 'Unavailable' }}</strong></span></div></div>
</section>
@endsection
