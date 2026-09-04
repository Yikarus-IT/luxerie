<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\InventoryReservation;
use App\Models\Order;
use App\Models\Product;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

class CommerceTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_a_product_and_create_an_order(): void
    {
        $product = $this->product();
        $this->post(route('cart.store', $product), ['quantity' => 2])->assertRedirect(route('cart.index'));
        $this->get(route('cart.index'))->assertOk()->assertSee($product->name)->assertSee('2');
        $this->get(route('checkout.create'))->assertOk();

        $this->post(route('checkout.store'), $this->customer())->assertRedirect();

        $order = Order::firstOrFail();
        $this->assertSame('pending_payment', $order->status);
        $this->assertSame(2, $order->items()->firstOrFail()->quantity);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertDatabaseHas('payment_attempts', ['order_id' => $order->id, 'provider' => 'mercado_pago', 'status' => 'awaiting_initialization']);
        $this->assertDatabaseHas('inventory_reservations', ['order_id' => $order->id, 'quantity' => 2, 'status' => 'active']);
    }

    public function test_cart_rejects_more_units_than_available(): void
    {
        $product = $this->product();
        $this->post(route('cart.store', $product), ['quantity' => 6])->assertSessionHasErrors('quantity');
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_expired_reservation_restores_inventory(): void
    {
        $product = $this->product();
        $this->post(route('cart.store', $product), ['quantity' => 2]);
        $token = (string) Str::uuid();
        $this->withSession(['checkout_token' => $token])->post(route('checkout.store'), $this->customer());
        $order = Order::firstOrFail();
        $order->update(['reserved_until' => now()->subMinute()]);
        InventoryReservation::where('order_id', $order->id)->update(['expires_at' => now()->subMinute()]);

        $this->assertSame(1, app(CheckoutService::class)->releaseExpired());
        $this->assertSame(5, $product->fresh()->stock);
        $this->assertSame('canceled', $order->fresh()->status);
    }

    public function test_paid_order_commits_inventory_and_cannot_expire(): void
    {
        $product = $this->product();
        $this->post(route('cart.store', $product), ['quantity' => 1]);
        $this->post(route('checkout.store'), $this->customer());
        $order = Order::firstOrFail();

        app(CheckoutService::class)->markPaid($order);
        $order->update(['reserved_until' => now()->subMinute()]);

        $this->assertSame(0, app(CheckoutService::class)->releaseExpired());
        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertDatabaseHas('inventory_reservations', ['order_id' => $order->id, 'status' => 'committed']);
    }

    public function test_mercado_pago_preference_uses_order_snapshot(): void
    {
        config(['services.mercado_pago.access_token' => 'TEST-token', 'services.mercado_pago.sandbox' => true]);
        Http::fake(['api.mercadopago.com/checkout/preferences' => Http::response(['id' => 'pref-123', 'init_point' => 'https://mercadopago.test/live', 'sandbox_init_point' => 'https://mercadopago.test/sandbox'])]);
        $product = $this->product();
        $this->post(route('cart.store', $product), ['quantity' => 1]);
        $this->post(route('checkout.store'), $this->customer());
        $order = Order::firstOrFail();

        $url = app(MercadoPagoService::class)->createPreference($order);

        $this->assertSame('https://mercadopago.test/sandbox', $url);
        Http::assertSent(fn ($request) => $request['external_reference'] === $order->number && $request['items'][0]['quantity'] === 1 && $request->hasHeader('X-Idempotency-Key'));
    }

    public function test_signed_webhook_confirms_an_approved_payment(): void
    {
        config(['services.mercado_pago.access_token' => 'TEST-token', 'services.mercado_pago.webhook_secret' => 'secret']);
        $product = $this->product();
        $this->post(route('cart.store', $product), ['quantity' => 1]);
        $this->post(route('checkout.store'), $this->customer());
        $order = Order::firstOrFail();
        Http::fake(['api.mercadopago.com/v1/payments/987' => Http::response(['id' => 987, 'status' => 'approved', 'external_reference' => $order->number, 'transaction_amount' => (float) $order->total, 'currency_id' => 'MXN'])]);
        $timestamp = now()->timestamp;
        $requestId = 'request-123';
        $signature = 'ts='.$timestamp.',v1='.hash_hmac('sha256', "id:987;request-id:{$requestId};ts:{$timestamp};", 'secret');

        $this->withHeaders(['x-signature' => $signature, 'x-request-id' => $requestId])->postJson(route('payments.webhook'), ['type' => 'payment', 'data' => ['id' => '987']])->assertOk();

        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertDatabaseHas('inventory_reservations', ['order_id' => $order->id, 'status' => 'committed']);
    }

    private function product(): Product
    {
        $category = Category::create(['name' => 'Cremas', 'slug' => 'cremas', 'is_active' => true]);

        return Product::create(['category_id' => $category->id, 'name' => 'Luxérie Clara', 'slug' => 'luxerie-clara', 'sku' => 'LUX-001', 'short_description' => 'Crema facial', 'description' => 'Descripción', 'price' => 499, 'stock' => 5, 'is_active' => true]);
    }

    private function customer(): array
    {
        return ['customer_name' => 'Ana Cliente', 'customer_email' => 'ana@example.com', 'customer_phone' => '5512345678', 'shipping_address_line_1' => 'Reforma 100', 'shipping_city' => 'Ciudad de México', 'shipping_state' => 'Ciudad de México', 'shipping_postal_code' => '06600'];
    }
}
