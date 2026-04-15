<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * PaymentController
 *
 * Handles the payment step (UI-only — no real payment gateway).
 *
 * Supported methods:
 *   - cod   : Cash on Delivery
 *   - card  : Card Payment (UI demo, no real processing)
 */
class PaymentController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // Payment page
    // ──────────────────────────────────────────────────────────────

    /**
     * Show the payment options page.
     * Requires a pending order in session; redirects to cart otherwise.
     */
    public function index(): View|RedirectResponse
    {
        $order = session('pending_order');

        if (! $order) {
            return redirect()->route('cart.index')
                ->with('error', 'No pending order found. Please start from your cart.');
        }

        return view('payment.index', compact('order'));
    }

    // ──────────────────────────────────────────────────────────────
    // Process payment
    // ──────────────────────────────────────────────────────────────

    /**
     * "Process" the payment (demo — always succeeds), clear the cart,
     * store the completed order, and redirect to the success page.
     */
    public function process(Request $request): RedirectResponse
    {
        $order = session('pending_order');

        if (! $order) {
            return redirect()->route('cart.index')
                ->with('error', 'No pending order found.');
        }

        $request->validate([
            'payment_method' => ['required', 'in:cod,card'],
        ], [
            'payment_method.required' => 'Please select a payment method.',
            'payment_method.in'       => 'Invalid payment method selected.',
        ]);

        // For card payments, validate card fields (UI only — not processed)
        if ($request->input('payment_method') === 'card') {
            $request->validate([
                'card_name'   => ['required', 'string', 'max:255'],
                'card_number' => ['required', 'digits:16'],
                'card_expiry' => ['required', 'string', 'regex:/^\d{2}\/\d{2}$/'],
                'card_cvv'    => ['required', 'digits_between:3,4'],
            ], [
                'card_name.required'    => 'Please enter the cardholder name.',
                'card_number.required'  => 'Please enter your card number.',
                'card_number.digits'    => 'Card number must be 16 digits.',
                'card_expiry.required'  => 'Please enter the expiry date (MM/YY).',
                'card_expiry.regex'     => 'Expiry must be in MM/YY format.',
                'card_cvv.required'     => 'Please enter the CVV.',
                'card_cvv.digits_between' => 'CVV must be 3 or 4 digits.',
            ]);
        }

        // Mark order as completed
        $completedOrder = array_merge($order, [
            'payment_method' => $request->input('payment_method') === 'cod'
                ? 'Cash on Delivery'
                : 'Card Payment',
            'status'     => 'confirmed',
            'paid_at'    => now()->toDateTimeString(),
        ]);

        // Store completed order and clear cart + pending order
        session(['completed_order' => $completedOrder]);

        // Append to order history so My Orders page can display it
        $history   = session('order_history', []);
        $history[] = $completedOrder;
        session(['order_history' => $history]);

        session()->forget(['cart', 'pending_order']);

        return redirect()->route('payment.success');
    }

    // ──────────────────────────────────────────────────────────────
    // Success page
    // ──────────────────────────────────────────────────────────────

    /**
     * Show the order success / confirmation page.
     */
    public function success(): View|RedirectResponse
    {
        $order = session('completed_order');

        if (! $order) {
            return redirect()->route('home');
        }

        return view('payment.success', compact('order'));
    }
}
