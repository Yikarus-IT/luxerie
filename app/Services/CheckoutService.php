<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    public function __construct(private readonly CartManager $carts) {}

    public function createOrder(Cart $cart, array $customer, string $checkoutToken): Order
    {
        if ($existing = Order::where('checkout_token', $checkoutToken)->first()) {
            return $existing;
        }

        $cart->load('items.product.primaryMedia.media');
        if ($cart->items->isEmpty()) {
            throw ValidationException::withMessages(['cart' => 'Tu carrito está vacío.']);
        }

        return DB::transaction(function () use ($cart, $customer, $checkoutToken) {
            $lockedProducts = Product::whereIn('id', $cart->items->pluck('product_id'))->lockForUpdate()->get()->keyBy('id');
            foreach ($cart->items as $item) {
                $product = $lockedProducts->get($item->product_id);
                if (! $product?->is_active || $product->stock < $item->quantity) {
                    throw ValidationException::withMessages(['cart' => "No hay suficientes existencias de {$item->product?->name}."]);
                }
                $item->update(['unit_price' => $product->price]);
            }

            $cart->refresh()->load('items.product.primaryMedia.media');
            $totals = $this->carts->totals($cart);
            $reservedUntil = now()->addMinutes(30);
            $order = Order::create([...$customer, 'number' => $this->orderNumber(), 'cart_id' => $cart->id, 'checkout_token' => $checkoutToken, 'status' => 'pending_payment', 'payment_status' => 'pending', 'fulfillment_status' => 'unfulfilled', 'subtotal' => $totals['subtotal'], 'shipping_total' => $totals['shipping'], 'discount_total' => 0, 'total' => $totals['total'], 'currency' => 'MXN', 'reserved_until' => $reservedUntil]);

            foreach ($cart->items as $item) {
                $product = $lockedProducts[$item->product_id];
                $product->decrement('stock', $item->quantity);
                $order->items()->create(['product_id' => $product->id, 'sku' => $product->sku, 'name' => $product->name, 'size_label' => $product->size_label, 'quantity' => $item->quantity, 'unit_price' => $product->price, 'line_total' => (float) $product->price * $item->quantity, 'image_url' => $product->displayImageUrl()]);
                $order->reservations()->create(['product_id' => $product->id, 'quantity' => $item->quantity, 'status' => 'active', 'expires_at' => $reservedUntil]);
            }

            $order->statusHistory()->create(['status' => 'pending_payment', 'comment' => 'Pedido creado; inventario reservado durante 30 minutos.', 'created_at' => now()]);
            $order->paymentAttempts()->create(['provider' => 'mercado_pago', 'status' => 'awaiting_initialization', 'amount' => $order->total, 'currency' => 'MXN', 'idempotency_key' => (string) Str::uuid()]);
            $cart->update(['status' => 'converted']);

            return $order->load('items', 'paymentAttempts');
        });
    }

    public function release(Order $order, string $reason = 'Reserva liberada.'): void
    {
        DB::transaction(function () use ($order, $reason) {
            $order->load('reservations');
            foreach ($order->reservations->where('status', 'active') as $reservation) {
                Product::whereKey($reservation->product_id)->lockForUpdate()->increment('stock', $reservation->quantity);
                $reservation->update(['status' => 'released', 'released_at' => now()]);
            }
            $order->update(['status' => 'canceled', 'payment_status' => $order->payment_status === 'pending' ? 'expired' : $order->payment_status, 'reserved_until' => null]);
            $order->statusHistory()->create(['user_id' => auth()->id(), 'status' => 'canceled', 'comment' => $reason, 'created_at' => now()]);
        });
    }

    public function markPaid(Order $order, ?string $comment = null): void
    {
        DB::transaction(function () use ($order, $comment) {
            $lockedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            if ($lockedOrder->payment_status === 'paid') {
                return;
            }

            $lockedOrder->reservations()->where('status', 'active')->update(['status' => 'committed']);
            $lockedOrder->update(['status' => 'paid', 'payment_status' => 'paid', 'paid_at' => now(), 'reserved_until' => null]);
            $lockedOrder->statusHistory()->create(['user_id' => auth()->id(), 'status' => 'paid', 'comment' => $comment ?: 'Pago confirmado.', 'created_at' => now()]);
        });
    }

    public function releaseExpired(): int
    {
        $orders = Order::where('status', 'pending_payment')->where('reserved_until', '<=', now())->get();
        $orders->each(fn (Order $order) => $this->release($order, 'La reserva expiró antes de recibir el pago.'));

        return $orders->count();
    }

    private function orderNumber(): string
    {
        do {
            $number = 'LUX-'.now()->format('ymd').'-'.strtoupper(Str::random(6));
        } while (Order::where('number', $number)->exists());

        return $number;
    }
}
