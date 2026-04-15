<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * OrderController
 *
 * Handles the order summary step between cart and payment.
 *
 * The pending order is stored in session under 'pending_order' so it
 * survives the redirect to the payment page.
 */
class OrderController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // Order summary
    // ──────────────────────────────────────────────────────────────

    /**
     * Show the order summary page.
     * Requires a non-empty cart; redirects to cart otherwise.
     */
    public function summary(): View|RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Add products before placing an order.');
        }

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = $subtotal >= 50 ? 0.0 : 5.99;
        $tax      = round($subtotal * 0.08, 2);   // 8 % tax
        $total    = round($subtotal + $shipping + $tax, 2);

        $user = session('auth_user');

        return view('order.summary', compact('cart', 'subtotal', 'shipping', 'tax', 'total', 'user'));
    }

    // ──────────────────────────────────────────────────────────────
    // Place order (move to payment)
    // ──────────────────────────────────────────────────────────────

    /**
     * Validate the shipping form, build the pending order, and redirect
     * to the payment page.
     */
    public function place(Request $request): RedirectResponse
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email'],
            'phone'     => ['required', 'string', 'max:30'],
            'address'   => ['required', 'string', 'max:500'],
            'city'      => ['required', 'string', 'max:100'],
            'zip'       => ['required', 'string', 'max:20'],
        ], [
            'full_name.required' => 'Please enter your full name.',
            'email.required'     => 'Please enter your email address.',
            'phone.required'     => 'Please enter your phone number.',
            'address.required'   => 'Please enter your delivery address.',
            'city.required'      => 'Please enter your city.',
            'zip.required'       => 'Please enter your ZIP / postal code.',
        ]);

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = $subtotal >= 50 ? 0.0 : 5.99;
        $tax      = round($subtotal * 0.08, 2);
        $total    = round($subtotal + $shipping + $tax, 2);

        // Build the pending order and store in session
        $order = [
            'id'       => strtoupper(Str::random(8)),   // e.g. "A3F9K2BX"
            'items'    => $cart,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'tax'      => $tax,
            'total'    => $total,
            'shipping_info' => $request->only('full_name', 'email', 'phone', 'address', 'city', 'zip'),
            'created_at' => now()->toDateTimeString(),
        ];

        session(['pending_order' => $order]);

        return redirect()->route('payment.index');
    }
}
