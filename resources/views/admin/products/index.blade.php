@extends('layouts.admin')
@section('title', 'Products')
@section('heading', 'Products')
@section('content')
<div class="admin-actions"><p>Manage pricing, visibility, inventory, and product copy.</p><a class="button button-dark" href="{{ route('admin.products.create') }}">New product</a></div>
<section class="admin-panel table-wrap"><table><thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Visibility</th><th></th></tr></thead><tbody>@foreach($products as $product)<tr><td><strong>{{ $product->name }}</strong><small>{{ $product->sku }}</small></td><td>{{ $product->category->name }}</td><td>${{ number_format($product->price, 2) }}</td><td>{{ $product->stock }}</td><td><span class="status {{ $product->is_active ? 'success' : '' }}">{{ $product->is_active ? 'Published' : 'Draft' }}</span></td><td class="row-actions"><a href="{{ route('admin.products.edit', $product) }}">Edit</a><form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Delete this product?">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>@endforeach</tbody></table></section>{{ $products->links() }}
@endsection
