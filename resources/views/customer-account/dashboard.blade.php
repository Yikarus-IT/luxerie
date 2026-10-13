@extends('layouts.storefront')

@section('title', 'Mi cuenta — Luxérie')

@section('content')
<section class="account-dashboard">
    <div class="account-dashboard-heading">
        <div><p class="eyebrow">Mi cuenta</p><h1>Hola, {{ $customer->first_name }}</h1><p>Desde aquí puedes consultar tus compras y administrar tus datos.</p></div>
        <div class="account-dashboard-actions"><a class="button" href="{{ route('customer.profile.edit') }}">Editar mis datos</a><a class="text-link" href="{{ route('customer.addresses.index') }}">Mis direcciones</a></div>
    </div>

    @if(session('success'))<div class="notice success">{{ session('success') }}</div>@endif

    <section class="account-orders">
        <div class="account-section-heading"><div><p class="eyebrow">Historial</p><h2>Mis compras</h2></div><a class="text-link" href="{{ route('shop') }}">Ver producto</a></div>
        @forelse($orders as $order)
            <article class="account-order">
                <div><span>Pedido</span><strong>{{ $order->number }}</strong><small>{{ $order->created_at->translatedFormat('d \d\e F \d\e Y') }}</small></div>
                <div><span>Estado</span><strong>{{ match($order->status) { 'paid' => 'Pagado', 'canceled' => 'Cancelado', default => 'Pendiente de pago' } }}</strong><small>{{ $order->items->sum('quantity') }} producto{{ $order->items->sum('quantity') === 1 ? '' : 's' }}</small></div>
                <div><span>Total</span><strong>${{ number_format($order->total, 2) }} MXN</strong><a class="text-link" href="{{ route('orders.show', $order) }}">Ver pedido</a></div>
            </article>
        @empty
            <div class="account-empty"><h2>Aún no tienes compras</h2><p>Cuando realices una compra con esta cuenta, aparecerá aquí.</p><a class="button button-dark" href="{{ route('shop') }}">Conocer Luxérie Clara</a></div>
        @endforelse
        @if($orders->hasPages())<div class="account-pagination">{{ $orders->links() }}</div>@endif
    </section>
</section>
@endsection
