<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\MercadoPagoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class MercadoPagoController extends Controller
{
    public function pay(Request $request, Order $order, MercadoPagoService $mercadoPago): RedirectResponse
    {
        abort_unless($request->session()->has('visible_orders.'.$order->checkout_token), 404);
        abort_if($order->payment_status === 'paid' || $order->status === 'canceled', 422);

        try {
            return redirect()->away($mercadoPago->createPreference($order));
        } catch (Throwable $exception) {
            report($exception);

            return redirect()->route('orders.show', $order)->withErrors(['payment' => $exception->getMessage()]);
        }
    }

    public function result(Request $request, Order $order): RedirectResponse
    {
        $request->session()->put('visible_orders.'.$order->checkout_token, true);

        return redirect()->route('orders.show', $order)->with('payment_result', $request->string('result')->value());
    }

    public function webhook(Request $request, MercadoPagoService $mercadoPago, CheckoutService $checkout): Response
    {
        $dataId = (string) ($request->input('data.id') ?: $request->query('data_id'));
        if (! $mercadoPago->validSignature((string) $request->header('x-signature'), (string) $request->header('x-request-id'), strtolower($dataId))) {
            return response('Invalid signature', 401);
        }

        if (($request->input('type') ?: $request->query('type')) === 'payment' && $dataId !== '') {
            try {
                $mercadoPago->processPayment($dataId, $checkout);
            } catch (Throwable $exception) {
                Log::warning('Mercado Pago webhook processing failed.', ['payment_id' => $dataId, 'message' => $exception->getMessage()]);

                return response('Retry later', 500);
            }
        }

        return response('OK');
    }
}
