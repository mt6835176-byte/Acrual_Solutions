<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CartController
 *
 * Manages the shopping cart stored in the user's session.
 *
 * Session structure:
 *   cart = [
 *     'product_id' => [
 *       'id'        => string,
 *       'name'      => string,
 *       'price'     => float,
 *       'image_url' => string|null,
 *       'qty'       => int,
 *     ],
 *     ...
 *   ]
 */
class CartController extends Controller
{
    public function __construct(private ProductService $service) {}

    // ──────────────────────────────────────────────────────────────
    // View cart
    // ──────────────────────────────────────────────────────────────

    /** Display the cart page. */
    public function index(): View
    {
        $cart  = session('cart', []);
        $total = $this->cartTotal($cart);

        return view('cart.index', compact('cart', 'total'));
    }

    // ──────────────────────────────────────────────────────────────
    // Add to cart
    // ──────────────────────────────────────────────────────────────

    /** Add a product to the cart (or increment its quantity). */
    public function add(Request $request, string $id): RedirectResponse
    {
        $product = $this->service->findById($id);

        if (! $product) {
            return back()->with('error', 'Product not found.');
        }

        if ((int) $product['quantity'] <= 0) {
            return back()->with('error', 'This product is out of stock.');
        }

        $cart = session('cart', []);

        if (isset($cart[$id])) {
            // Already in cart — increment qty (cap at available stock)
            $maxQty       = (int) $product['quantity'];
            $cart[$id]['qty'] = min($cart[$id]['qty'] + 1, $maxQty);
        } else {
            $cart[$id] = [
                'id'        => $product['id'],
                'name'      => $product['name'],
                'price'     => (float) $product['price'],
                'image_url' => $product['image_url'] ?? null,
                'qty'       => 1,
            ];
        }

        session(['cart' => $cart]);

        return back()->with('success', '"' . $product['name'] . '" added to cart.');
    }

    // ──────────────────────────────────────────────────────────────
    // Update quantity
    // ──────────────────────────────────────────────────────────────

    /** Update the quantity of a cart item. */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'qty' => ['required', 'integer', 'min:1', 'max:999'],
        ]);

        $cart = session('cart', []);

        if (! isset($cart[$id])) {
            return redirect()->route('cart.index')->with('error', 'Item not found in cart.');
        }

        // Verify stock is still available
        $product = $this->service->findById($id);
        $maxQty  = $product ? (int) $product['quantity'] : 999;
        $newQty  = min((int) $request->input('qty'), $maxQty);

        $cart[$id]['qty'] = max(1, $newQty);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    // ──────────────────────────────────────────────────────────────
    // Remove item
    // ──────────────────────────────────────────────────────────────

    /** Remove a single item from the cart. */
    public function remove(string $id): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$id]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    // ──────────────────────────────────────────────────────────────
    // Clear cart
    // ──────────────────────────────────────────────────────────────

    /** Empty the entire cart. */
    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    // ──────────────────────────────────────────────────────────────
    // Helper
    // ──────────────────────────────────────────────────────────────

    /** Calculate the cart grand total. */
    private function cartTotal(array $cart): float
    {
        return array_reduce($cart, fn($carry, $item) => $carry + ($item['price'] * $item['qty']), 0.0);
    }
}
