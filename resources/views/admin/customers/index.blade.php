@extends('layouts.admin')
@section('title', 'Clientes')
@section('heading', 'Clientes')
@section('content')
<div class="admin-actions"><p>Gestiona la información de clientes y su historial de compras.</p><a class="button button-dark" href="{{ route('admin.customers.create') }}">Nuevo cliente</a></div>
<section class="admin-panel table-wrap"><table><thead><tr><th>Cliente</th><th>Email</th><th>Teléfono</th><th>Pedidos</th><th></th></tr></thead><tbody>@foreach($customers as $customer)<tr><td data-label="Cliente"><strong>{{ $customer->name }}</strong></td><td data-label="Email">{{ $customer->email }}</td><td data-label="Teléfono">{{ $customer->phone ?? '—' }}</td><td data-label="Pedidos">{{ $customer->orders_count }}</td><td class="row-actions" data-label="Acciones"><a href="{{ route('admin.customers.show', $customer) }}">Ver</a><a href="{{ route('admin.customers.edit', $customer) }}">Editar</a><form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" data-confirm="¿Eliminar este cliente?">@csrf @method('DELETE')<button type="submit">Eliminar</button></form></td></tr>@endforeach</tbody></table></section>{{ $customers->links() }}
@endsection
