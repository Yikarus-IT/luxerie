@extends('layouts.storefront')

@section('title', 'Pedido '.$order->number.' — Luxérie')

@section('content')
<section class="commerce-page order-confirmation">
    <div class="commerce-heading"><p class="eyebrow">Seguimiento de pedido</p><h1>{{ $order->status_label }}</h1><p>Pedido <strong>{{ $order->number }}</strong> · creado el {{ $order->created_at->format('d/m/Y') }}.</p></div>
    @if(session('payment_result') === 'failure')<div class="notice error">El pago no se completó. Puedes intentarlo nuevamente.</div>@elseif(session('payment_result') === 'pending')<div class="notice warning">Mercado Pago está procesando tu pago. Actualizaremos el pedido al recibir la confirmación.</div>@elseif(session('payment_result') === 'success' && $order->payment_status !== 'paid')<div class="notice warning">Estamos verificando la confirmación del pago.</div>@endif
    @if($errors->any())<div class="notice error">{{ $errors->first() }}</div>@endif
    <div class="commerce-layout">
        <div class="form-panel">
            <span class="status {{ $order->status === 'canceled' || $order->payment_status === 'rejected' ? 'danger' : ($order->payment_status === 'paid' ? 'success' : 'warning') }}">{{ $order->status_label }}</span>
            @if($order->payment_status !== 'paid' && $order->status !== 'canceled')
                <p>Reservamos tu producto hasta {{ $order->reserved_until?->format('d/m/Y H:i') }}. @if($order->payment_status === 'rejected') El pago anterior no fue aprobado; puedes volver a intentarlo. @endif</p>
                <form method="POST" action="{{ route('payments.pay', $order) }}">@csrf<button class="button button-dark" type="submit">Pagar con Mercado Pago</button></form>
            @elseif($order->status === 'paid')<p>Tu pago fue confirmado. Comenzaremos a preparar tu pedido.</p>@endif
            <h3>Historial</h3>
            <ol class="order-timeline">@foreach($order->statusHistory->sortByDesc('created_at') as $entry)<li><strong>{{ match($entry->status) { 'pending_payment' => $order->payment_status === 'rejected' ? 'Pago rechazado' : 'Pendiente de pago', 'paid' => 'Pago confirmado', 'processing' => 'En preparación', 'shipped' => 'Enviado', 'delivered' => 'Entregado', 'canceled' => 'Cancelado', default => $entry->status } }}</strong><small>{{ $entry->created_at->format('d/m/Y H:i') }}</small>@if($entry->comment)<p>{{ $entry->comment }}</p>@endif</li>@endforeach</ol>
            <h3>Entrega</h3><p>{{ $order->customer_name }}<br>{{ $order->shipping_address_line_1 }} {{ $order->shipping_address_line_2 }}<br>{{ $order->shipping_neighborhood }}, {{ $order->shipping_city }}, {{ $order->shipping_state }}. C.P. {{ $order->shipping_postal_code }}</p>
        </div>
        <aside class="order-summary"><h2>Resumen</h2>@foreach($order->items as $item)<p><span>{{ $item->quantity }} × {{ $item->name }}</span><strong>${{ number_format($item->line_total, 2) }}</strong></p>@endforeach<p><span>Envío</span><strong>Incluido</strong></p><p class="order-total"><span>Total</span><strong>${{ number_format($order->total, 2) }} {{ $order->currency }}</strong></p></aside>
    </div>
</section>
@endsection
