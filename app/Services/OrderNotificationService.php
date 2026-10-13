<?php

namespace App\Services;

use App\Mail\PaidOrderAdminMail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class OrderNotificationService
{
    public function paid(Order $order): void
    {
        $recipient = (string) config('services.order_notifications.recipient');
        if (! config('services.order_notifications.enabled') || $recipient === '') {
            return;
        }

        try {
            Mail::to($recipient)->send(new PaidOrderAdminMail($order->loadMissing('items')));
        } catch (Throwable $exception) {
            Log::error('No se pudo enviar el aviso de compra confirmada.', [
                'order_id' => $order->id,
                'order_number' => $order->number,
                'recipient' => $recipient,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
