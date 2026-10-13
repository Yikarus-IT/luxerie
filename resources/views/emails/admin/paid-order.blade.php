<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"></head>
<body style="margin:0;background:#f4eee7;color:#241a20;font-family:Arial,Helvetica,sans-serif">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4eee7;padding:32px 12px"><tr><td align="center">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:680px;background:#fffdf8;border:1px solid #e7ddd4">
    <tr><td style="padding:34px 40px;background:#241a20;color:#fff"><div style="font-family:Georgia,serif;font-size:30px;font-style:italic">Luxérie Clara</div><div style="margin-top:8px;color:#d9c8cf;font-size:12px;letter-spacing:1.5px;text-transform:uppercase">Nueva compra confirmada</div></td></tr>
    <tr><td style="padding:36px 40px">
        <h1 style="margin:0 0 10px;font:normal 34px/1.15 Georgia,serif">Pedido pagado</h1>
        <p style="margin:0 0 30px;color:#695c62">Mercado Pago confirmó la compra <strong>{{ $order->number }}</strong> el {{ $order->paid_at?->format('d/m/Y H:i') }}.</p>

        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:30px;border-collapse:collapse">
            @foreach($order->items as $item)
            <tr>
                <td style="width:84px;padding:14px 14px 14px 0;border-top:1px solid #e7ddd4">@if($item->image_url)<img src="{{ str_starts_with($item->image_url, 'http') ? $item->image_url : url($item->image_url) }}" alt="{{ $item->name }}" width="76" style="display:block;width:76px;height:76px;object-fit:contain;background:#f7f1e8">@endif</td>
                <td style="padding:14px 0;border-top:1px solid #e7ddd4"><strong>{{ $item->name }}</strong><br><span style="color:#695c62">Cantidad: {{ $item->quantity }} · ${{ number_format($item->unit_price, 2) }} c/u</span></td>
                <td align="right" style="padding:14px 0;border-top:1px solid #e7ddd4"><strong>${{ number_format($item->line_total, 2) }}</strong></td>
            </tr>
            @endforeach
            <tr><td colspan="2" style="padding:18px 0;border-top:2px solid #241a20;font-size:17px"><strong>Total cobrado</strong><br><small style="color:#695c62">Envío incluido</small></td><td align="right" style="padding:18px 0;border-top:2px solid #241a20;font-size:20px"><strong>${{ number_format($order->total, 2) }} {{ $order->currency }}</strong></td></tr>
        </table>

        <h2 style="margin:0 0 14px;font:normal 24px Georgia,serif">Datos del cliente</h2>
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f7f1e8;padding:20px;margin-bottom:28px"><tr><td style="line-height:1.7">
            <strong>{{ $order->customer_name }}</strong><br>
            {{ $order->customer_email }}<br>
            {{ $order->customer_phone }}
        </td></tr></table>

        <h2 style="margin:0 0 14px;font:normal 24px Georgia,serif">Dirección de entrega</h2>
        <p style="margin:0;line-height:1.7">{{ $order->shipping_address_line_1 }}@if($order->shipping_address_line_2), {{ $order->shipping_address_line_2 }}@endif<br>@if($order->shipping_neighborhood){{ $order->shipping_neighborhood }}, @endif{{ $order->shipping_city }}, {{ $order->shipping_state }}<br>C.P. {{ $order->shipping_postal_code }}, {{ $order->shipping_country }}</p>
        @if($order->customer_notes)<p style="margin:24px 0 0;padding:16px;border-left:3px solid #763b50;background:#faf6f1"><strong>Notas:</strong><br>{{ $order->customer_notes }}</p>@endif

        <p style="margin:30px 0 0"><a href="{{ route('admin.orders.show', $order) }}" style="display:inline-block;padding:13px 20px;background:#241a20;color:#fff;text-decoration:none;font-size:12px;font-weight:bold;letter-spacing:1px;text-transform:uppercase">Abrir pedido en administración</a></p>
    </td></tr>
    <tr><td style="padding:20px 40px;background:#eee4dc;color:#766970;font-size:11px">Aviso automático de Luxérie Clara. Pedido {{ $order->number }}.</td></tr>
</table>
</td></tr></table>
</body>
</html>
