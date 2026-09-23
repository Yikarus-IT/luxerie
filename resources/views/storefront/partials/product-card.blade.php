<article class="product-card">
    <a class="product-image" href="{{ route('product.show', $product) }}">
        <img src="{{ $product->displayImageUrl() }}" alt="{{ $product->displayImageAlt() }}" loading="lazy" style="object-position:{{ $product->displayFocalPoint() }}">
        @if($product->is_featured)<span class="badge">Destacado</span>@endif
    </a>
    <div class="product-card-copy">
        <div class="product-meta"><div><p>{{ $product->category->name }}</p><h3><a href="{{ route('product.show', $product) }}">{{ $product->name }}</a></h3></div><strong>${{ number_format($product->price, 0) }} MXN</strong></div>
        <p class="product-card-description">{{ $product->short_description }}</p>
        <a class="product-card-link text-link" href="{{ route('product.show', $product) }}">Conocer el producto →</a>
    </div>
</article>
