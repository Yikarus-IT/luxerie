<article class="product-card">
    <a class="product-image" href="{{ route('products.show', $product) }}">
        <img src="{{ $product->image_url ?: asset('images/brand/product-cutout.jpeg') }}" alt="{{ $product->name }}" loading="lazy">
        @if($product->is_featured)<span class="badge">Destacado</span>@endif
    </a>
    <div class="product-card-copy">
        <div class="product-meta"><div><p>{{ $product->category->name }}</p><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3></div><strong>${{ number_format($product->price, 0) }} MXN</strong></div>
        <p class="product-card-description">{{ $product->short_description }}</p>
        <a class="product-card-link text-link" href="{{ route('products.show', $product) }}">Conocer el producto →</a>
    </div>
</article>
