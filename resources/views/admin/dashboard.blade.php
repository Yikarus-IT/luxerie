@extends('layouts.admin')
@section('title', 'Overview')
@section('heading', 'Good morning.')
@section('content')
<section class="stat-grid"><article><span>Total products</span><strong>{{ $productCount }}</strong><small>Across the complete catalog</small></article><article><span>Visible products</span><strong>{{ $activeProductCount }}</strong><small>Published on the storefront</small></article><article><span>Categories</span><strong>{{ $categoryCount }}</strong><small>Product collections</small></article></section>
<section class="admin-panel"><div class="panel-heading"><div><p class="eyebrow">Inventory attention</p><h2>Low stock products</h2></div><a class="button button-small" href="{{ route('admin.products.create') }}">Add product</a></div><div class="table-wrap"><table><thead><tr><th>Product</th><th>Category</th><th>Stock</th><th>Status</th></tr></thead><tbody>@forelse($lowStockProducts as $product)<tr><td><a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a><small>{{ $product->sku }}</small></td><td>{{ $product->category->name }}</td><td>{{ $product->stock }}</td><td><span class="status {{ $product->stock === 0 ? 'danger' : 'warning' }}">{{ $product->stock === 0 ? 'Out of stock' : 'Low' }}</span></td></tr>@empty<tr><td colspan="4">All products are comfortably stocked.</td></tr>@endforelse</tbody></table></div></section>
@endsection
