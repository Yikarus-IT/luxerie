<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartManager
{
    public function current(Request $request, bool $create = true): ?Cart
    {
        if ($customer = $request->user('customer')) {
            return $this->forCustomer($request, $customer, $create);
        }

        $token = $request->session()->get('cart_token');
        $cart = $token ? Cart::where('token', $token)->where('status', 'open')->first() : null;
        if (! $cart && $create) {
            $cart = Cart::create(['token' => (string) Str::uuid(), 'status' => 'open', 'expires_at' => now()->addDays(30)]);
            $request->session()->put('cart_token', $cart->token);
        }

        return $cart?->load('items.product.category', 'items.product.primaryMedia.media');
    }

    public function claimForCustomer(Request $request, Customer $customer): ?Cart
    {
        return $this->forCustomer($request, $customer, true);
    }

    private function forCustomer(Request $request, Customer $customer, bool $create): ?Cart
    {
        $accountCart = Cart::where('customer_id', $customer->id)->where('status', 'open')->latest()->first();
        $token = $request->session()->get('cart_token');
        $guestCart = $token ? Cart::where('token', $token)->whereNull('customer_id')->where('status', 'open')->first() : null;

        if (! $accountCart && $guestCart) {
            $guestCart->update(['customer_id' => $customer->id]);
            $accountCart = $guestCart;
        } elseif ($accountCart && $guestCart) {
            if ($accountCart->items()->doesntExist() && $guestCart->items()->exists()) {
                $guestCart->items()->update(['cart_id' => $accountCart->id]);
            }
            $guestCart->update(['status' => 'merged']);
        }

        if (! $accountCart && $create) {
            $accountCart = Cart::create(['customer_id' => $customer->id, 'token' => (string) Str::uuid(), 'status' => 'open', 'expires_at' => now()->addDays(30)]);
        }
        if ($accountCart) {
            $request->session()->put('cart_token', $accountCart->token);
        }

        return $accountCart?->load('items.product.category', 'items.product.primaryMedia.media');
    }

    public function add(Request $request, Product $product, int $quantity): Cart
    {
        abort_unless($product->is_active && $product->category?->is_active, 404);
        $cart = $this->current($request);
        if ($cart->items->isNotEmpty() && ! $cart->items->contains('product_id', $product->id)) {
            throw ValidationException::withMessages(['cart' => 'Solo puedes comprar un tipo de producto por transacción.']);
        }

        $item = $cart->items->firstWhere('product_id', $product->id);
        $newQuantity = ($item?->quantity ?? 0) + $quantity;
        if ($newQuantity > $product->stock) {
            throw ValidationException::withMessages(['quantity' => "Solo hay {$product->stock} unidades disponibles."]);
        }

        $cart->items()->updateOrCreate(['product_id' => $product->id], ['quantity' => $newQuantity, 'unit_price' => $product->unitPriceForQuantity($newQuantity)]);
        $cart->update(['expires_at' => now()->addDays(30)]);

        return $this->current($request);
    }

    public function updateQuantity(Cart $cart, CartItem $item, int $quantity): void
    {
        if ($quantity > $item->product->stock) {
            throw ValidationException::withMessages(['quantity' => "Solo hay {$item->product->stock} unidades disponibles."]);
        }
        $item->update(['quantity' => $quantity, 'unit_price' => $item->product->unitPriceForQuantity($quantity)]);
        $cart->update(['expires_at' => now()->addDays(30)]);
    }

    public function totals(Cart $cart): array
    {
        foreach ($cart->items as $item) {
            $unitPrice = $item->product->unitPriceForQuantity($item->quantity);
            if ((float) $item->unit_price !== $unitPrice) {
                $item->update(['unit_price' => $unitPrice]);
            }
        }
        $cart->load('items.product');
        $subtotal = $cart->subtotal();

        return ['subtotal' => $subtotal, 'shipping' => 0.0, 'total' => $subtotal];
    }
}
