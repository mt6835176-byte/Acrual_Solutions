<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * UserController
 *
 * Handles the authenticated user's account pages.
 * All routes in this controller are protected by the 'auth.session'
 * middleware defined in bootstrap/app.php — no manual auth checks needed.
 *
 * Profile updates and order history are persisted in the PHP session
 * (no database required).
 */
class UserController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // Profile
    // ──────────────────────────────────────────────────────────────

    /** GET /account/profile — show the profile page. */
    public function profile(): View
    {
        $user = session('auth_user');

        return view('user.profile', compact('user'));
    }

    /** POST /account/profile — handle profile update. */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'bio'   => ['nullable', 'string', 'max:500'],
        ], [
            'name.required'  => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email'    => 'Please enter a valid email address.',
        ]);

        $user = session('auth_user');

        $name     = $request->input('name');
        $initials = collect(explode(' ', trim($name)))
            ->filter()
            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
            ->take(2)
            ->implode('');

        $updated = array_merge($user, [
            'name'     => $name,
            'email'    => $request->input('email'),
            'phone'    => $request->input('phone') ?: null,
            'bio'      => $request->input('bio') ?: null,
            'initials' => $initials ?: ($user['initials'] ?? 'U'),
        ]);

        session(['auth_user' => $updated]);

        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully.');
    }

    // ──────────────────────────────────────────────────────────────
    // My Orders
    // ──────────────────────────────────────────────────────────────

    /** GET /account/orders — show order history. */
    public function orders(): View
    {
        // Orders are appended to session('order_history') by PaymentController
        $orders = array_reverse(session('order_history', []));

        return view('user.orders', compact('orders'));
    }
}
