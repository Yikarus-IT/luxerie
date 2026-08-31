@extends('layouts.admin')
@section('title', 'Productos')
@section('heading', 'Productos')
@section('content')
<div class="admin-actions"><p>Administra precios, visibilidad, inventario y contenido de los productos.</p><a class="button button-dark" href="{{ route('admin.products.create') }}">Nuevo producto</a></div>
<section class="admin-panel table-wrap"><table><thead><tr><th>Producto</th><th>Precio</th><th>Existencias</th><th>Visibilidad</th><th></th></tr></thead><tbody>@foreach($products as $product)<tr><td data-label="Producto"><strong>{{ $product->name }}</strong><small>{{ $product->sku }}</small></td><td data-label="Precio">${{ number_format($product->price, 2) }}</td><td data-label="Existencias">{{ $product->stock }}</td><td data-label="Visibilidad"><span class="status {{ $product->is_active ? 'success' : '' }}">{{ $product->is_active ? 'Publicado' : 'Borrador' }}</span></td><td class="row-actions" data-label="Acciones"><a href="{{ route('admin.products.edit', $product) }}">Editar</a><form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="¿Eliminar este producto?">@csrf @method('DELETE')<button type="submit">Eliminar</button></form></td></tr>@endforeach</tbody></table></section>{{ $products->links() }}
@endsection
