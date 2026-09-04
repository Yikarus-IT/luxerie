<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('items', 'statusHistory.user', 'paymentAttempts')]);
    }

    public function update(Request $request, Order $order, CheckoutService $checkout): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:pending_payment,paid,processing,shipped,delivered,canceled'], 'comment' => ['nullable', 'string', 'max:500']]);
        if ($data['status'] === 'paid') {
            $checkout->markPaid($order, $data['comment']);
        } elseif ($data['status'] === 'canceled' && $order->status !== 'canceled') {
            $checkout->release($order, $data['comment'] ?: 'Pedido cancelado desde administración.');
        } else {
            $order->update(['status' => $data['status']]);
            $order->statusHistory()->create(['user_id' => $request->user()->id, 'status' => $data['status'], 'comment' => $data['comment'], 'created_at' => now()]);
        }

        return back()->with('success', 'Estado del pedido actualizado.');
    }
}
