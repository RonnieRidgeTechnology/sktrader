<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function __construct(
        private CartService $cart
    ) {}

    public function index()
    {
        $items = $this->cart->getItems();
        $subtotal = $this->cart->getSubtotal();

        return Inertia::render('Cart/Index', [
            'title' => 'Shopping Cart',
            'cart' => [
                'items' => $items,
                'count' => $this->cart->getCount(),
                'subtotal' => round($subtotal, 2),
            ],
        ]);
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);
        $this->cart->add(
            (int) $validated['product_id'],
            (int) ($validated['quantity'] ?? 1)
        );

        return redirect()->back()->with('success', 'Added to cart.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:0|max:99',
        ]);
        $this->cart->update((int) $validated['product_id'], (int) $validated['quantity']);

        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, int $productId)
    {
        $this->cart->remove($productId);

        return redirect()->back()->with('success', 'Item removed from cart.');
    }
}
