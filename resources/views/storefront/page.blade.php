@extends('layouts.storefront')
@section('title', $page->seo_title ?: $page->title.' — Luxérie')
@section('meta_description', $page->seo_description ?: $page->excerpt)
@section('content')
<header class="page-hero"><div class="page-hero-inner"><p class="eyebrow">Luxérie</p><h1>{{ $page->title }}</h1>@if($page->excerpt)<p>{{ $page->excerpt }}</p>@endif</div></header>
<article class="content-page prose-content">{!! nl2br(e($page->body)) !!}</article>
@endsection
