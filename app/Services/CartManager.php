<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CartManager
{
    public function current(Request $request, bool $create = true): ?Cart
    {
        $token = $request->session()->get('cart_token');
        $cart = $token ? Cart::where('token', $token)->where('status', 'open')->first() : null;

        if (! $cart && $create) {
            $cart = Cart::create(['token' => (string) Str::uuid(), 'status' => 'open', 'expires_at' => now()->addDays(30)]);
            $request->session()->put('cart_token', $cart->token);
        }

        return $cart?->load('items.product.category', 'items.product.primaryMedia.media');
    }

    public function add(Request $request, Product $product, int $quantity): Cart
    {
        abort_unless($product->is_active && $product->category?->is_active, 404);
        $cart = $this->current($request);
        $item = $cart->items->firstWhere('product_id', $product->id);
        $newQuantity = ($item?->quantity ?? 0) + $quantity;

        if ($newQuantity > $product->stock) {
            throw ValidationException::withMessages(['quantity' => "Solo hay {$product->stock} unidades disponibles."]);
        }

        $cart->items()->updateOrCreate(['product_id' => $product->id], ['quantity' => $newQuantity, 'unit_price' => $product->price]);
        $cart->update(['expires_at' => now()->addDays(30)]);

        return $this->current($request);
    }

    public function totals(Cart $cart): array
    {
        $subtotal = $cart->subtotal();
        $settings = SiteSetting::resolved();
        $shipping = $subtotal >= (float) $settings['free_shipping_threshold'] ? 0 : (float) $settings['standard_shipping_cost'];

        return ['subtotal' => $subtotal, 'shipping' => $shipping, 'total' => $subtotal + $shipping];
    }
}
