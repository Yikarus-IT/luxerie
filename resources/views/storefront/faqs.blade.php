@extends('layouts.storefront')
@section('title', 'Preguntas frecuentes — Luxérie')
@section('content')
<header class="page-hero"><div class="page-hero-inner"><p class="eyebrow">Estamos para ayudarte</p><h1>Preguntas frecuentes</h1><p>Información sobre producto, pedidos y cuidado.</p></div></header>
<section class="faq-page">@forelse($faqs as $faq)<details><summary>{{ $faq->question }}</summary><p>{{ $faq->answer }}</p></details>@empty<p>Aún no hay preguntas publicadas.</p>@endforelse</section>
@endsection
