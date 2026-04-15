@extends('layouts.app')

@section('title', 'Create Account')

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
                <p class="text-muted mt-1 mb-0" style="font-size:0.85rem;">Create your free account</p>
            </div>

            <div class="form-card shadow-sm">
                <div class="form-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;background:rgba(99,102,241,0.25);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#a5b4fc;">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div>
                            <h4>Create account</h4>
                            <p>Join ShopInventory today</p>
                        </div>
                    </div>
                </div>

                <div class="p-4">
                    <form method="POST" action="{{ route('register.submit') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Jane Smith"
                                       autocomplete="name" autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="you@example.com"
                                       autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password"
                                       placeholder="Min. 6 characters"
                                       autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password"
                                       class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation"
                                       placeholder="Repeat password"
                                       autocomplete="new-password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-submit w-100">
                            <i class="bi bi-person-check me-2"></i>Create Account
                        </button>
                    </form>

                    <p class="text-center mt-3 mb-0" style="font-size:0.85rem; color:#6b7280;">
                        Already have an account?
                        <a href="{{ route('login') }}" style="color:#6366f1; font-weight:600; text-decoration:none;">Sign in</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
