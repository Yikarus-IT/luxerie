<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentAttempt;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MercadoPagoService
{
    public function configured(): bool
    {
        return filled(config('services.mercado_pago.access_token'));
    }

    public function createPreference(Order $order): string
    {
        if (! $this->configured()) {
            throw new RuntimeException('Mercado Pago todavía no está configurado.');
        }

        $order->loadMissing('items');
        $attempt = $order->paymentAttempts()->where('provider', 'mercado_pago')->latest()->first()
            ?? $order->paymentAttempts()->create(['provider' => 'mercado_pago', 'status' => 'awaiting_initialization', 'amount' => $order->total, 'currency' => $order->currency, 'idempotency_key' => (string) Str::uuid()]);

        if ($attempt->external_id && data_get($attempt->response_payload, 'init_point')) {
            return $this->checkoutUrl($attempt);
        }

        $payload = [
            'items' => $order->items->map(fn ($item) => ['id' => $item->sku, 'title' => $item->name, 'quantity' => $item->quantity, 'currency_id' => $order->currency, 'unit_price' => (float) $item->unit_price])->values()->all(),
            'payer' => ['name' => $order->customer_name, 'email' => $order->customer_email, 'phone' => ['number' => $order->customer_phone]],
            'shipments' => ['cost' => (float) $order->shipping_total, 'mode' => 'not_specified'],
            'external_reference' => $order->number,
            'back_urls' => ['success' => route('payments.result', ['order' => $order, 'result' => 'success']), 'pending' => route('payments.result', ['order' => $order, 'result' => 'pending']), 'failure' => route('payments.result', ['order' => $order, 'result' => 'failure'])],
            'notification_url' => route('payments.webhook', ['source_news' => 'webhooks']),
            'auto_return' => 'approved',
            'statement_descriptor' => 'LUXERIE',
            'metadata' => ['order_id' => $order->id, 'order_number' => $order->number],
        ];

        $attempt->update(['status' => 'initializing', 'request_payload' => $payload]);
        $response = $this->client()->withHeader('X-Idempotency-Key', $attempt->idempotency_key)->post('/checkout/preferences', $payload);
        if ($response->failed() || ! $response->json('id')) {
            $attempt->update(['status' => 'failed', 'response_payload' => $response->json(), 'failure_message' => $response->body()]);
            throw new RuntimeException('No pudimos iniciar el pago. Intenta nuevamente.');
        }

        $attempt->update(['external_id' => $response->json('id'), 'status' => 'ready', 'response_payload' => $response->json()]);

        return $this->checkoutUrl($attempt->fresh());
    }

    public function processPayment(string $paymentId, CheckoutService $checkout): ?Order
    {
        $response = $this->client()->get('/v1/payments/'.urlencode($paymentId));
        if ($response->failed()) {
            throw new RuntimeException('No se pudo verificar el pago recibido.');
        }

        $data = $response->json();
        $order = Order::where('number', data_get($data, 'external_reference'))->first();
        if (! $order || (float) data_get($data, 'transaction_amount') !== (float) $order->total || data_get($data, 'currency_id') !== $order->currency) {
            return null;
        }

        $attempt = $order->paymentAttempts()->where('provider', 'mercado_pago')->latest()->first();
        $attempt?->update(['external_id' => (string) $paymentId, 'status' => (string) data_get($data, 'status', 'unknown'), 'response_payload' => $data, 'processed_at' => now()]);
        if (data_get($data, 'status') === 'approved') {
            $checkout->markPaid($order, 'Pago confirmado automáticamente por Mercado Pago.');
        }

        return $order->fresh();
    }

    public function validSignature(string $signature, string $requestId, string $dataId): bool
    {
        $secret = (string) config('services.mercado_pago.webhook_secret');
        if ($secret === '' || $signature === '' || $requestId === '' || $dataId === '') {
            return false;
        }

        parse_str(str_replace(',', '&', $signature), $parts);
        $ts = $parts['ts'] ?? null;
        $hash = $parts['v1'] ?? null;
        if (! $ts || ! $hash || abs(now()->timestamp - (int) $ts) > 300) {
            return false;
        }

        $manifest = "id:{$dataId};request-id:{$requestId};ts:{$ts};";

        return hash_equals($hash, hash_hmac('sha256', $manifest, $secret));
    }

    private function checkoutUrl(PaymentAttempt $attempt): string
    {
        $key = config('services.mercado_pago.sandbox') ? 'sandbox_init_point' : 'init_point';

        return (string) (data_get($attempt->response_payload, $key) ?: data_get($attempt->response_payload, 'init_point'));
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl('https://api.mercadopago.com')->acceptJson()->asJson()->withToken((string) config('services.mercado_pago.access_token'))->timeout(15)->retry(2, 250, throw: false);
    }
}
