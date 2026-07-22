<article class="product-card">
    <a class="product-image" href="{{ route('products.show', $product) }}">
        @if($product->image_url)<img src="{{ $product->image_url }}" alt="{{ $product->name }}">@else<div class="product-placeholder"><span>Luxérie</span><small>{{ $product->size_label }}</small></div>@endif
        @if($product->is_featured)<span class="badge">Featured</span>@endif
    </a>
    <div class="product-meta"><div><p>{{ $product->category->name }}</p><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3></div><strong>${{ number_format($product->price, 0) }}</strong></div>
    <p>{{ $product->short_description }}</p>
</article>
