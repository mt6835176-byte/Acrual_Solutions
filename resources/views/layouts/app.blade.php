<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ShopInventory') — E-Commerce Inventory</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
          crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Base ─────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f2f5;
            color: #1a1a2e;
        }

        /* ── Top announcement bar ─────────────────────────── */
        .announcement-bar {
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.03em;
            padding: 7px 0;
            text-align: center;
        }

        /* ── Navbar ───────────────────────────────────────── */
        .navbar-main {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.6rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }

        .navbar-brand-text {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #1a1a2e !important;
            text-decoration: none;
            line-height: 1;
        }

        .navbar-brand-text span { color: #6366f1; }

        /* Nav links */
        .nav-link-custom {
            color: #4b5563 !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.45rem 0.9rem !important;
            border-radius: 8px;
            transition: background 0.15s, color 0.15s;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
        }

        .nav-link-custom:hover,
        .nav-link-custom.active {
            background: #f3f4f6;
            color: #6366f1 !important;
        }

        .nav-link-custom.active {
            font-weight: 600;
        }

        /* Cart icon button */
        .nav-cart-btn {
            position: relative;
            background: #f3f4f6;
            border: none;
            border-radius: 10px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            font-size: 1.1rem;
            transition: background 0.15s, color 0.15s;
            text-decoration: none;
        }

        .nav-cart-btn:hover { background: #ede9fe; color: #6366f1; }

        .cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        /* User avatar / dropdown trigger */
        .nav-user-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #f3f4f6;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.35rem 0.75rem 0.35rem 0.45rem;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
            text-decoration: none;
            /* reset button defaults */
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            outline: none;
        }

        .nav-user-btn:hover,
        .nav-user-btn.show {
            background: #ede9fe;
            border-color: #c4b5fd;
        }

        /* suppress Bootstrap's default dropdown caret */
        .nav-user-btn.dropdown-toggle::after { display: none; }

        /* Separate chevron button that opens the dropdown */
        .nav-chevron-btn {
            width: 28px;
            height: 28px;
            background: #f3f4f6;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.65rem;
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
            padding: 0;
            margin-left: -4px; /* tuck it right next to the user pill */
            flex-shrink: 0;
        }

        .nav-chevron-btn:hover,
        .nav-chevron-btn.show {
            background: #ede9fe;
            border-color: #c4b5fd;
            color: #6366f1;
        }

        /* suppress Bootstrap caret on chevron button too */
        .nav-chevron-btn.dropdown-toggle::after { display: none; }

        .nav-user-avatar {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .nav-user-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #374151;
            max-width: 90px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .nav-user-role {
            font-size: 0.68rem;
            color: #9ca3af;
            line-height: 1;
        }

        /* Dropdown menu */
        .dropdown-menu-custom {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 0.5rem;
            min-width: 240px;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #374151;
            text-decoration: none;
            transition: background 0.12s;
            width: 100%;
        }

        .dropdown-item-custom:hover { background: #f3f4f6; color: #6366f1; }
        .dropdown-item-custom i { font-size: 1rem; color: #9ca3af; width: 18px; text-align: center; flex-shrink: 0; }
        .dropdown-item-custom:hover i { color: #6366f1; }

        .dropdown-divider-custom {
            border-top: 1px solid #f3f4f6;
            margin: 0.35rem 0;
        }

        .dropdown-header-custom {
            padding: 0.5rem 0.75rem 0.35rem;
            font-size: 0.72rem;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        /* Add Product CTA button */
        .btn-nav-add {
            background: #6366f1;
            color: #fff !important;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.45rem 1rem;
            border-radius: 9px;
            border: none;
            transition: background 0.2s, transform 0.1s;
            white-space: nowrap;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-nav-add:hover { background: #4f46e5; transform: translateY(-1px); }

        /* Mobile offcanvas nav */
        .offcanvas-nav .nav-link-custom {
            font-size: 1rem;
            padding: 0.65rem 0.9rem !important;
        }

        /* suppress Bootstrap's default dropdown caret on our custom trigger */
        .page-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            color: #fff;
            padding: 2.5rem 0 2rem;
        }

        .page-hero h1 {
            font-size: 1.9rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }

        .page-hero p {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        .hero-stat {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 0.6rem 1.2rem;
            font-size: 0.82rem;
            color: #cbd5e1;
        }

        .hero-stat strong {
            color: #fff;
            font-size: 1.1rem;
            display: block;
        }

        /* ── Product cards ────────────────────────────────── */
        .product-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.12);
            border-color: #c7d2fe;
        }

        .product-img-wrap {
            position: relative;
            height: 220px;
            overflow: hidden;
            background: #f8fafc;
        }

        .product-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease;
        }

        .product-card:hover .product-img-wrap img {
            transform: scale(1.05);
        }

        .product-img-placeholder {
            height: 220px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            gap: 0.5rem;
        }

        .product-img-placeholder i {
            font-size: 2.8rem;
        }

        .product-img-placeholder span {
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .stock-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .stock-badge.in-stock {
            background: #dcfce7;
            color: #15803d;
        }

        .stock-badge.out-of-stock {
            background: #fee2e2;
            color: #dc2626;
        }

        .product-card-body {
            padding: 1.1rem 1.2rem 0.8rem;
        }

        .product-category-tag {
            font-size: 0.7rem;
            font-weight: 600;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 0.3rem;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.4rem;
            line-height: 1.35;
        }

        .product-desc {
            font-size: 0.82rem;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 0.9rem;
            min-height: 2.4em;
        }

        .product-price {
            font-size: 1.35rem;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.5px;
        }

        .product-price-cents {
            font-size: 0.85rem;
            font-weight: 600;
            color: #6b7280;
            vertical-align: super;
        }

        .product-card-footer {
            padding: 0.75rem 1.2rem;
            border-top: 1px solid #f3f4f6;
            background: #fafafa;
        }

        .btn-add-cart {
            background: #6366f1;
            color: #fff;
            font-weight: 600;
            font-size: 0.82rem;
            padding: 0.45rem 1rem;
            border-radius: 8px;
            border: none;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .btn-add-cart:hover {
            background: #4f46e5;
            color: #fff;
        }

        .qty-display {
            font-size: 0.78rem;
            color: #9ca3af;
            font-weight: 500;
        }

        /* ── Empty state ──────────────────────────────────── */
        .empty-state {
            background: #fff;
            border: 2px dashed #e5e7eb;
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #ede9fe, #dbeafe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 2.5rem;
            color: #6366f1;
        }

        /* ── Form page ────────────────────────────────────── */
        .form-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
        }

        .form-card-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
            padding: 1.5rem 1.75rem;
            color: #fff;
        }

        .form-card-header h4 {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0;
        }

        .form-card-header p {
            font-size: 0.82rem;
            color: #94a3b8;
            margin: 0.25rem 0 0;
        }

        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.4rem;
        }

        .form-control, .form-select {
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.9rem;
            padding: 0.55rem 0.85rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .input-group-text {
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px 0 0 10px;
            color: #6b7280;
            font-weight: 600;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .btn-submit {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 0.65rem 1.75rem;
            border-radius: 10px;
            border: none;
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn-submit:hover {
            opacity: 0.92;
            transform: translateY(-1px);
            color: #fff;
        }

        /* ── Alerts ───────────────────────────────────────── */
        .alert {
            border-radius: 12px;
            border: none;
            font-size: 0.88rem;
            font-weight: 500;
        }

        .alert-success {
            background: #f0fdf4;
            color: #15803d;
            border-left: 4px solid #22c55e;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border-left: 4px solid #ef4444;
        }

        /* ── Footer ───────────────────────────────────────── */
        .site-footer {
            background: #0f172a;
            color: #64748b;
            padding: 3rem 0 1.5rem;
            margin-top: 4rem;
            font-size: 0.82rem;
        }

        .site-footer a {
            color: #94a3b8;
            text-decoration: none;
        }

        .site-footer a:hover {
            color: #6366f1;
        }

        /* ── Breadcrumb ───────────────────────────────────── */
        .breadcrumb {
            font-size: 0.82rem;
            background: transparent;
            padding: 0;
            margin-bottom: 1.25rem;
        }

        .breadcrumb-item a {
            color: #6366f1;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #6b7280;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #d1d5db;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- Announcement Bar -->
    <div class="announcement-bar">
        <i class="bi bi-lightning-charge-fill me-1"></i>
        Free shipping on orders over $50 &nbsp;·&nbsp; New arrivals every week &nbsp;·&nbsp; 30-day returns
    </div>

    <!-- ═══════════════════════════════════════════════════
         NAVBAR
    ════════════════════════════════════════════════════ -->
    <nav class="navbar-main" role="navigation" aria-label="Main navigation">
        <div class="container d-flex align-items-center justify-content-between gap-3">

            {{-- ── Brand ── --}}
            <a href="{{ url('/') }}" class="navbar-brand-text flex-shrink-0">
                <i class="bi bi-bag-heart-fill me-1" style="color:#6366f1;"></i>Shop<span>Inventory</span>
            </a>

            {{-- ── Centre nav links (desktop) ── --}}
            <ul class="nav d-none d-lg-flex align-items-center gap-1 mb-0 flex-grow-1 justify-content-center">
                <li class="nav-item">
                    <a href="{{ url('/') }}"
                       class="nav-link-custom {{ request()->is('/') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}"
                       class="nav-link-custom {{ request()->routeIs('products.index') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap"></i>Shop
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.create') }}"
                       class="nav-link-custom {{ request()->routeIs('products.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-circle"></i>Add Product
                    </a>
                </li>
                <li class="nav-item">
                    @php $cartCount = collect(session('cart', []))->sum('qty'); @endphp
                    <a href="{{ route('cart.index') }}"
                       class="nav-link-custom {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                        <i class="bi bi-bag"></i>Cart
                        @if($cartCount > 0)
                            <span class="badge rounded-pill ms-1" style="background:#6366f1;color:#fff;font-size:0.68rem;padding:2px 7px;">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>

            {{-- ── Right-side actions ── --}}
            <div class="d-flex align-items-center gap-2 flex-shrink-0">

                {{-- Cart icon --}}
                @php $cartCount = collect(session('cart', []))->sum('qty'); @endphp
                <a href="{{ route('cart.index') }}" class="nav-cart-btn" title="View Cart">
                    <i class="bi bi-bag"></i>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                    @endif
                </a>

                @if(session('auth_user'))
                {{-- ── LOGGED IN ── --}}
                @php $authUser = session('auth_user'); @endphp

                {{-- Clicking the name/avatar goes directly to profile --}}
                <a href="{{ route('user.profile') }}"
                   class="nav-user-btn"
                   style="text-decoration:none;">
                    <div class="nav-user-avatar">{{ $authUser['initials'] ?? 'U' }}</div>
                    <div class="d-none d-sm-block">
                        <div class="nav-user-name">{{ $authUser['name'] }}</div>
                        <div class="nav-user-role">{{ $authUser['role'] ?? 'User' }}</div>
                    </div>
                </a>

                {{-- Separate chevron button opens the dropdown menu --}}
                <div class="dropdown">
                    <button type="button"
                            class="nav-chevron-btn dropdown-toggle"
                            id="userDropdown"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="true"
                            aria-expanded="false"
                            title="Account menu">
                        <i class="bi bi-chevron-down"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-custom"
                        aria-labelledby="userDropdown">

                        {{-- User info header --}}
                        <li>
                            <div style="padding:0.75rem 0.9rem 0.6rem; border-bottom:1px solid #f3f4f6; margin-bottom:0.35rem;">
                                <div style="display:flex; align-items:center; gap:0.65rem;">
                                    <div class="nav-user-avatar"
                                         style="width:36px;height:36px;border-radius:10px;font-size:0.8rem;flex-shrink:0;">
                                        {{ $authUser['initials'] ?? 'U' }}
                                    </div>
                                    <div>
                                        <div style="font-size:0.88rem;font-weight:700;color:#111827;line-height:1.2;">
                                            {{ $authUser['name'] }}
                                        </div>
                                        <div style="font-size:0.72rem;color:#9ca3af;">
                                            {{ $authUser['email'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        {{-- Profile --}}
                        <li>
                            <a class="dropdown-item-custom" href="{{ route('user.profile') }}">
                                <i class="bi bi-person-circle"></i>
                                <div>
                                    <div style="font-weight:600;line-height:1.2;">Profile</div>
                                    <div style="font-size:0.72rem;color:#9ca3af;font-weight:400;">View &amp; edit your details</div>
                                </div>
                            </a>
                        </li>

                        {{-- My Orders --}}
                        <li>
                            <a class="dropdown-item-custom" href="{{ route('user.orders') }}">
                                <i class="bi bi-bag-check"></i>
                                <div>
                                    <div style="font-weight:600;line-height:1.2;">My Orders</div>
                                    <div style="font-size:0.72rem;color:#9ca3af;font-weight:400;">
                                        @php $orderCount = count(session('order_history', [])); @endphp
                                        {{ $orderCount }} {{ Str::plural('order', $orderCount) }} placed
                                    </div>
                                </div>
                                @if($orderCount > 0)
                                    <span class="ms-auto badge rounded-pill"
                                          style="background:#6366f1;color:#fff;font-size:0.65rem;padding:3px 7px;">
                                        {{ $orderCount }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        {{-- Cart --}}
                        <li>
                            <a class="dropdown-item-custom" href="{{ route('cart.index') }}">
                                <i class="bi bi-bag"></i>
                                <div>
                                    <div style="font-weight:600;line-height:1.2;">Cart</div>
                                    <div style="font-size:0.72rem;color:#9ca3af;font-weight:400;">
                                        @php $ddCartCount = collect(session('cart', []))->sum('qty'); @endphp
                                        {{ $ddCartCount }} {{ Str::plural('item', $ddCartCount) }}
                                    </div>
                                </div>
                                @if($ddCartCount > 0)
                                    <span class="ms-auto badge rounded-pill"
                                          style="background:#ef4444;color:#fff;font-size:0.65rem;padding:3px 7px;">
                                        {{ $ddCartCount }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <li><div class="dropdown-divider-custom"></div></li>

                        {{-- Manage --}}
                        <li><div class="dropdown-header-custom">Manage</div></li>
                        <li>
                            <a class="dropdown-item-custom" href="{{ route('products.index') }}">
                                <i class="bi bi-grid-3x3-gap"></i>All Products
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item-custom" href="{{ route('products.create') }}">
                                <i class="bi bi-plus-circle"></i>Add Product
                            </a>
                        </li>

                        <li><div class="dropdown-divider-custom"></div></li>

                        {{-- Logout --}}
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit"
                                        class="dropdown-item-custom w-100 border-0 bg-transparent text-start"
                                        style="color:#ef4444;cursor:pointer;">
                                    <i class="bi bi-box-arrow-right" style="color:#ef4444;"></i>Sign Out
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>
                @else
                {{-- ── LOGGED OUT: Login + Sign Up buttons ── --}}
                <a href="{{ route('login') }}" class="nav-link-custom d-none d-sm-flex">
                    <i class="bi bi-box-arrow-in-right"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn-nav-add">
                    <i class="bi bi-person-plus"></i>Sign Up
                </a>
                @endif

                {{-- Mobile hamburger --}}
                <button class="d-lg-none btn p-0 ms-1"
                        style="background:none; border:none; font-size:1.4rem; color:#4b5563;"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mobileNav"
                        aria-label="Open menu">
                    <i class="bi bi-list"></i>
                </button>

            </div>
        </div>
    </nav>

    {{-- ── Mobile offcanvas nav ── --}}
    <div class="offcanvas offcanvas-end offcanvas-nav" tabindex="-1" id="mobileNav"
         aria-labelledby="mobileNavLabel" style="max-width:290px;">
        <div class="offcanvas-header border-bottom" style="padding:1rem 1.25rem;">
            <span class="navbar-brand-text" id="mobileNavLabel">
                <i class="bi bi-bag-heart-fill me-1" style="color:#6366f1;"></i>Shop<span>Inventory</span>
            </span>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-3">

            @if(session('auth_user'))
            @php $authUser = session('auth_user'); @endphp
            {{-- Logged-in user strip --}}
            <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-3" style="background:#f3f4f6;">
                <div class="nav-user-avatar" style="width:38px;height:38px;font-size:0.9rem;">{{ $authUser['initials'] ?? 'U' }}</div>
                <div>
                    <div style="font-weight:700; font-size:0.9rem; color:#111827;">{{ $authUser['name'] }}</div>
                    <div style="font-size:0.75rem; color:#9ca3af;">{{ $authUser['email'] ?? '' }}</div>
                </div>
            </div>
            @else
            {{-- Guest strip --}}
            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('login') }}" class="btn btn-outline-secondary flex-fill" style="border-radius:9px; font-weight:600; font-size:0.85rem;">Login</a>
                <a href="{{ route('register') }}" class="btn-nav-add flex-fill justify-content-center" style="border-radius:9px;">Sign Up</a>
            </div>
            @endif

            <ul class="nav flex-column gap-1 mb-3">
                <li><a href="{{ url('/') }}" class="nav-link-custom {{ request()->is('/') ? 'active' : '' }}"><i class="bi bi-house-door"></i>Home</a></li>
                <li><a href="{{ route('products.index') }}" class="nav-link-custom {{ request()->routeIs('products.index') ? 'active' : '' }}"><i class="bi bi-grid-3x3-gap"></i>Shop / Products</a></li>
                <li><a href="{{ route('products.create') }}" class="nav-link-custom {{ request()->routeIs('products.create') ? 'active' : '' }}"><i class="bi bi-plus-circle"></i>Add Product</a></li>
                <li><a href="{{ route('cart.index') }}" class="nav-link-custom {{ request()->routeIs('cart.*') ? 'active' : '' }}"><i class="bi bi-bag"></i>Cart @if($cartCount > 0)<span class="ms-auto badge rounded-pill" style="background:#6366f1;color:#fff;font-size:0.7rem;">{{ $cartCount }}</span>@endif</a></li>
            </ul>

            @if(session('auth_user'))
            <div style="border-top:1px solid #e5e7eb; padding-top:1rem;">
                <ul class="nav flex-column gap-1">
                    <li><a href="{{ route('user.profile') }}" class="nav-link-custom {{ request()->routeIs('user.profile') ? 'active' : '' }}"><i class="bi bi-person-circle"></i>Profile</a></li>
                    <li>
                        <a href="{{ route('user.orders') }}" class="nav-link-custom {{ request()->routeIs('user.orders') ? 'active' : '' }}">
                            <i class="bi bi-bag-check"></i>My Orders
                            @php $oc = count(session('order_history', [])); @endphp
                            @if($oc > 0)<span class="ms-auto badge rounded-pill" style="background:#6366f1;color:#fff;font-size:0.68rem;">{{ $oc }}</span>@endif
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="nav-link-custom w-100 border-0 bg-transparent text-start" style="color:#ef4444; cursor:pointer;">
                                <i class="bi bi-box-arrow-right" style="color:#ef4444;"></i>Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
            @endif

        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success') || session('error'))
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
    @endif

    <!-- Page Content -->
    @yield('content')

    <!-- ═══════════════════════════════════════════════════
         FOOTER
    ════════════════════════════════════════════════════ -->
    <footer class="site-footer">
        <div class="container">

            {{-- Top section: brand + columns --}}
            <div class="row g-4 pb-4" style="border-bottom: 1px solid rgba(255,255,255,0.08);">

                {{-- Brand column --}}
                <div class="col-lg-4 col-md-6">
                    <a href="{{ url('/') }}" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; margin-bottom:0.9rem;">
                        <i class="bi bi-bag-heart-fill" style="color:#6366f1; font-size:1.4rem;"></i>
                        <span style="font-size:1.25rem; font-weight:800; color:#fff; letter-spacing:-0.5px;">
                            Shop<span style="color:#6366f1;">Inventory</span>
                        </span>
                    </a>
                    <p style="font-size:0.83rem; color:#64748b; line-height:1.7; max-width:280px; margin-bottom:1rem;">
                        A production-ready e-commerce product inventory system built with Laravel and Bootstrap 5.
                    </p>
                    {{-- Tech badges --}}
                    <div class="d-flex flex-wrap gap-2">
                        <span style="background:rgba(99,102,241,0.15); border:1px solid rgba(99,102,241,0.3); color:#a5b4fc; font-size:0.72rem; font-weight:600; padding:3px 10px; border-radius:20px; letter-spacing:0.03em;">
                            <i class="bi bi-code-slash me-1"></i>Laravel
                        </span>
                        <span style="background:rgba(99,102,241,0.15); border:1px solid rgba(99,102,241,0.3); color:#a5b4fc; font-size:0.72rem; font-weight:600; padding:3px 10px; border-radius:20px; letter-spacing:0.03em;">
                            <i class="bi bi-bootstrap me-1"></i>Bootstrap 5
                        </span>
                        <span style="background:rgba(99,102,241,0.15); border:1px solid rgba(99,102,241,0.3); color:#a5b4fc; font-size:0.72rem; font-weight:600; padding:3px 10px; border-radius:20px; letter-spacing:0.03em;">
                            <i class="bi bi-filetype-php me-1"></i>PHP 8.3
                        </span>
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 style="font-size:0.72rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.9rem;">
                        Quick Links
                    </h6>
                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.5rem;">
                        @foreach([
                            ['/', 'bi-house-door', 'Home'],
                            [route('products.index'), 'bi-grid-3x3-gap', 'Shop'],
                            [route('cart.index'), 'bi-bag', 'Cart'],
                            [route('products.create'), 'bi-plus-circle', 'Add Product'],
                        ] as [$href, $icon, $label])
                        <li>
                            <a href="{{ $href }}" style="font-size:0.83rem; color:#64748b; text-decoration:none; display:flex; align-items:center; gap:0.45rem; transition:color 0.15s;"
                               onmouseover="this.style.color='#a5b4fc'" onmouseout="this.style.color='#64748b'">
                                <i class="bi {{ $icon }}" style="font-size:0.8rem; width:14px;"></i>{{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Account links --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 style="font-size:0.72rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.9rem;">
                        Account
                    </h6>
                    <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:0.5rem;">
                        @foreach([
                            [route('login'), 'bi-box-arrow-in-right', 'Sign In'],
                            [route('register'), 'bi-person-plus', 'Register'],
                            [route('user.profile'), 'bi-person-circle', 'Profile'],
                            [route('user.orders'), 'bi-bag-check', 'My Orders'],
                        ] as [$href, $icon, $label])
                        <li>
                            <a href="{{ $href }}" style="font-size:0.83rem; color:#64748b; text-decoration:none; display:flex; align-items:center; gap:0.45rem; transition:color 0.15s;"
                               onmouseover="this.style.color='#a5b4fc'" onmouseout="this.style.color='#64748b'">
                                <i class="bi {{ $icon }}" style="font-size:0.8rem; width:14px;"></i>{{ $label }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- API / Tech info --}}
                <div class="col-lg-4 col-md-6">
                    <h6 style="font-size:0.72rem; font-weight:700; color:#94a3b8; text-transform:uppercase; letter-spacing:0.08em; margin-bottom:0.9rem;">
                        REST API Endpoints
                    </h6>
                    <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.07); border-radius:10px; padding:0.85rem 1rem; font-family:monospace; font-size:0.75rem; line-height:1.9;">
                        @foreach([
                            ['POST',   '#22c55e', '/api/products'],
                            ['GET',    '#60a5fa', '/api/products/{id}'],
                            ['PUT',    '#f59e0b', '/api/products/{id}'],
                            ['DELETE', '#f87171', '/api/products/{id}'],
                        ] as [$method, $color, $path])
                        <div>
                            <span style="color:{{ $color }}; font-weight:700; display:inline-block; width:52px;">{{ $method }}</span>
                            <span style="color:#94a3b8;">{{ $path }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Bottom bar --}}
            <div class="row align-items-center pt-3 g-2">
                <div class="col-md-6 text-center text-md-start">
                    <span style="font-size:0.8rem; color:#475569;">
                        &copy; {{ date('Y') }}
                        <span style="color:#fff; font-weight:600;">ShopInventory</span>.
                        All rights reserved.
                    </span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span style="font-size:0.8rem; color:#475569;">
                        Developed by
                        <span style="color:#a5b4fc; font-weight:700;">Manoj Kumar</span>
                        &nbsp;&middot;&nbsp;
                        Built with
                        <span style="color:#a5b4fc; font-weight:700;">
                            <i class="bi bi-code-slash me-1"></i>Laravel
                        </span>
                    </span>
                </div>
            </div>

        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc4s9bIOgUxi8T/jzmFXFMrWCU3FA0e6bMknOZBZFWhX"
            crossorigin="anonymous"></script>

    <script>
        // Initialise Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el, { trigger: 'hover' });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
