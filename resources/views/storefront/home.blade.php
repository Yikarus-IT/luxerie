@extends('layouts.storefront')

@section('title', 'Luxérie — Beauty, considered')

@section('content')
<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow">Skincare for unhurried moments</p>
        <h1>Your daily ritual,<br><em>made luminous.</em></h1>
        <p class="hero-intro">Thoughtful creams that pair effective care with beautiful texture—created to make consistency feel like luxury.</p>
        <div class="actions"><a class="button button-dark" href="{{ route('shop') }}">Discover the collection</a><a class="text-link" href="#about">Our philosophy →</a></div>
    </div>
    <div class="hero-art" aria-label="Placeholder for upcoming Luxérie product photography">
        <div class="orb orb-one"></div><div class="orb orb-two"></div>
        <div class="jar"><span>Luxérie</span><small>CRÈME VISAGE</small></div>
        <p>Photography<br>coming soon</p>
    </div>
</section>

<section class="statement" id="about">
    <p class="eyebrow">The Luxérie approach</p>
    <h2>High-performance care should still feel deeply personal.</h2>
    <p>We design each formula around an intentional purpose, a pleasurable texture, and a ritual you will want to return to.</p>
</section>

<section class="collection">
    <div class="section-heading"><div><p class="eyebrow">The essentials</p><h2>Made for your shelf—and your skin.</h2></div><a class="text-link" href="{{ route('shop') }}">Shop all products →</a></div>
    <div class="product-grid">
        @forelse($featuredProducts as $product)
            @include('storefront.partials.product-card', ['product' => $product])
        @empty
            <p>No featured products yet. Add one from the administration area.</p>
        @endforelse
    </div>
</section>

<section class="ritual" id="ritual">
    <div class="ritual-visual"><span>01</span><p>A quiet moment<br>for your skin.</p></div>
    <div class="ritual-copy"><p class="eyebrow">A simple ritual</p><h2>Cleanse. Treat.<br>Seal in care.</h2><ol><li><span>01</span><div><strong>Prepare</strong><p>Begin with clean, slightly damp skin.</p></div></li><li><span>02</span><div><strong>Apply</strong><p>Warm a small amount between your fingertips.</p></div></li><li><span>03</span><div><strong>Take your time</strong><p>Press gently into the face, neck, and hands.</p></div></li></ol></div>
</section>
@endsection
