<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CartManager;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function create(Request $request, CartManager $carts): View|RedirectResponse
    {
        $cart = $carts->current($request, false);
        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Tu carrito está vacío.']);
        }

        $request->session()->put('checkout_token', $request->session()->get('checkout_token', (string) Str::uuid()));

        return view('storefront.checkout', ['cart' => $cart, 'totals' => $carts->totals($cart)]);
    }

    public function store(Request $request, CartManager $carts, CheckoutService $checkout, MercadoPagoService $mercadoPago): RedirectResponse
    {
        $cart = $carts->current($request, false);
        abort_unless($cart && $cart->items->isNotEmpty(), 422);
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:160'], 'customer_email' => ['required', 'email', 'max:160'],
            'customer_phone' => ['required', 'string', 'max:40'], 'shipping_address_line_1' => ['required', 'string', 'max:255'],
            'shipping_address_line_2' => ['nullable', 'string', 'max:255'], 'shipping_neighborhood' => ['nullable', 'string', 'max:160'],
            'shipping_city' => ['required', 'string', 'max:120'], 'shipping_state' => ['required', 'string', 'max:120'],
            'shipping_postal_code' => ['required', 'regex:/^\d{5}$/'], 'customer_notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $data['shipping_country'] = 'MX';
        $token = $request->session()->get('checkout_token', (string) Str::uuid());
        $order = $checkout->createOrder($cart, $data, $token);
        $request->session()->put('visible_orders.'.$order->checkout_token, true);
        $request->session()->forget(['cart_token', 'checkout_token']);

        if ($mercadoPago->configured()) {
            try {
                return redirect()->away($mercadoPago->createPreference($order));
            } catch (Throwable $exception) {
                report($exception);

                return redirect()->route('orders.show', $order)->withErrors(['payment' => 'El pedido fue creado, pero no pudimos abrir Mercado Pago. Puedes intentarlo nuevamente.']);
            }
        }

        return redirect()->route('orders.show', $order);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($request->session()->has('visible_orders.'.$order->checkout_token), 404);

        return view('storefront.order', ['order' => $order->load('items', 'paymentAttempts')]);
    }
}
