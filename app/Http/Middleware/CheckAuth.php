<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckAuth middleware
 *
 * Protects routes that require a logged-in session user.
 * Redirects to /login with an error flash if no auth_user is in session.
 */
class CheckAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('auth_user')) {
            return redirect()->route('login')
                ->with('error', 'Please sign in to access that page.');
        }

        return $next($request);
    }
}
