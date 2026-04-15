<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * AuthController
 *
 * Provides session-based demo authentication.
 * No database is used — credentials are validated against hardcoded
 * demo accounts so the UI can demonstrate logged-in / logged-out states
 * without requiring a database setup.
 *
 * Demo accounts:
 *   admin@example.com  / password
 *   user@example.com   / password
 */
class AuthController extends Controller
{
    /**
     * Hardcoded demo users (email => [name, role, password]).
     * In a real app these would come from the database.
     */
    private array $demoUsers = [
        'admin@example.com' => ['name' => 'Admin User',  'role' => 'Admin',    'initials' => 'AU'],
        'user@example.com'  => ['name' => 'Jane Smith',  'role' => 'Customer', 'initials' => 'JS'],
    ];

    private string $demoPassword = 'password';

    // ──────────────────────────────────────────────────────────────
    // Login
    // ──────────────────────────────────────────────────────────────

    /** Show the login form. */
    public function showLogin(): View|RedirectResponse
    {
        if (session('auth_user')) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /** Handle login form submission. */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Please enter your email address.',
            'email.email'       => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $email    = $request->input('email');
        $password = $request->input('password');

        // Validate against demo accounts
        if (isset($this->demoUsers[$email]) && $password === $this->demoPassword) {
            $user = array_merge(['email' => $email], $this->demoUsers[$email]);

            // Store user in session
            $request->session()->put('auth_user', $user);
            $request->session()->regenerate();

            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back, ' . $user['name'] . '!');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    // ──────────────────────────────────────────────────────────────
    // Register
    // ──────────────────────────────────────────────────────────────

    /** Show the registration form. */
    public function showRegister(): View|RedirectResponse
    {
        if (session('auth_user')) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /** Handle registration form submission (demo — auto-logs in). */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255'],
            'password'              => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'name.required'      => 'Please enter your full name.',
            'email.required'     => 'Please enter your email address.',
            'password.required'  => 'Please choose a password.',
            'password.min'       => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        // Demo: create a session user from the submitted data
        $name     = $request->input('name');
        $initials = collect(explode(' ', $name))
            ->map(fn($w) => strtoupper(substr($w, 0, 1)))
            ->take(2)
            ->implode('');

        $user = [
            'name'     => $name,
            'email'    => $request->input('email'),
            'role'     => 'Customer',
            'initials' => $initials ?: 'U',
        ];

        $request->session()->put('auth_user', $user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', 'Account created! Welcome, ' . $name . '.');
    }

    // ──────────────────────────────────────────────────────────────
    // Logout
    // ──────────────────────────────────────────────────────────────

    /** Destroy the session and redirect to login. */
    public function logout(Request $request): RedirectResponse
    {
        // Remove the auth user key first
        $request->session()->forget('auth_user');

        // Flush all session data (cart, orders, etc.)
        $request->session()->flush();

        // Regenerate the session ID to prevent session fixation
        $request->session()->regenerate(true);

        return redirect()->route('login')
            ->with('success', 'You have been signed out successfully.');
    }
}
