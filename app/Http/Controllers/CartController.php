<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request, CartManager $carts): View
    {
        $cart = $carts->current($request);

        return view('storefront.cart', ['cart' => $cart, 'totals' => $carts->totals($cart)]);
    }

    public function store(Request $request, Product $product, CartManager $carts): RedirectResponse
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:20']]);
        $carts->add($request, $product, $data['quantity']);

        return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito.');
    }

    public function update(Request $request, CartItem $cartItem, CartManager $carts): RedirectResponse
    {
        $cart = $carts->current($request, false);
        abort_unless($cart && $cartItem->cart_id === $cart->id, 404);
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:'.$cartItem->product->stock]]);
        $cartItem->update(['quantity' => $data['quantity'], 'unit_price' => $cartItem->product->price]);

        return back()->with('success', 'Carrito actualizado.');
    }

    public function destroy(Request $request, CartItem $cartItem, CartManager $carts): RedirectResponse
    {
        $cart = $carts->current($request, false);
        abort_unless($cart && $cartItem->cart_id === $cart->id, 404);
        $cartItem->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }
}
