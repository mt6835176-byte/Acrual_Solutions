@extends('layouts.app')

@section('title', 'Sign In')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-7 col-lg-5 col-xl-4">

            {{-- Logo --}}
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" style="text-decoration:none;">
                    <span style="font-size:1.6rem; font-weight:800; color:#1a1a2e; letter-spacing:-0.5px;">
                        <i class="bi bi-bag-heart-fill me-1" style="color:#6366f1;"></i>Shop<span style="color:#6366f1;">Inventory</span>
                    </span>
                </a>
                <p class="text-muted mt-1 mb-0" style="font-size:0.85rem;">Sign in to your account</p>
            </div>

            <div class="form-card shadow-sm">
                <div class="form-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;background:rgba(99,102,241,0.25);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#a5b4fc;">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <div>
                            <h4>Welcome back</h4>
                            <p>Enter your credentials to continue</p>
                        </div>
                    </div>
                </div>

                <div class="p-4">

                    {{-- Demo credentials hint --}}
                    <div class="rounded-3 p-3 mb-4" style="background:#ede9fe; border:1px solid #c4b5fd;">
                        <div style="font-size:0.78rem; color:#5b21b6; font-weight:600; margin-bottom:0.3rem;">
                            <i class="bi bi-info-circle me-1"></i>Demo Credentials
                        </div>
                        <div style="font-size:0.78rem; color:#6d28d9; font-family:monospace;">
                            admin@example.com / password<br>
                            user@example.com &nbsp;/ password
                        </div>
                    </div>

                    <form method="POST" action="{{ route('login.submit') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="you@example.com"
                                       autocomplete="email" autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       placeholder="••••••••"
                                       autocomplete="current-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-submit w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0" style="font-size:0.85rem; color:#6b7280;">
                        Don't have an account?
                        <a href="{{ route('register') }}" style="color:#6366f1; font-weight:600; text-decoration:none;">Create one</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
