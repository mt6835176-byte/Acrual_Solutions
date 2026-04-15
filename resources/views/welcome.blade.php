@extends('layouts.app')

@section('title', 'Dashboard — ShopInventory')

@push('styles')
<style>
    /* ── Dashboard hero ───────────────────────────────── */
    .dash-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 55%, #0f3460 100%);
        padding: 3rem 0 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .dash-hero::before {
        content: '';
        position: absolute;
        top: -60px; right: -60px;
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(99,102,241,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }

    .dash-hero::after {
        content: '';
        position: absolute;
        bottom: -80px; left: -40px;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(139,92,246,0.14) 0%, transparent 70%);
        border-radius: 50%;
    }

    .dash-hero-content { position: relative; z-index: 1; }

    .dash-hero h1 {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0.4rem;
    }

    .dash-hero p { color: #94a3b8; font-size: 0.95rem; margin: 0; }

    .dash-hero-actions { display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1.5rem; }

    .btn-hero-primary {
        background: #6366f1;
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s, transform 0.15s;
    }

    .btn-hero-primary:hover { background: #4f46e5; color: #fff; transform: translateY(-2px); }

    .btn-hero-outline {
        background: rgba(255,255,255,0.08);
        color: #e2e8f0;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.65rem 1.5rem;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.18);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s;
    }

    .btn-hero-outline:hover { background: rgba(255,255,255,0.14); color: #fff; }

    /* ── Stat cards ───────────────────────────────────── */
    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 1.4rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .stat-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.08); transform: translateY(-2px); }

    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .stat-icon.indigo  { background: #ede9fe; color: #6366f1; }
    .stat-icon.green   { background: #dcfce7; color: #16a34a; }
    .stat-icon.red     { background: #fee2e2; color: #dc2626; }
    .stat-icon.amber   { background: #fef3c7; color: #d97706; }

    .stat-value {
        font-size: 1.65rem;
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.5px;
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .stat-label { font-size: 0.8rem; color: #6b7280; font-weight: 500; }

    /* ── Section headings ─────────────────────────────── */
    .section-heading {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0;
    }

    .section-sub { font-size: 0.82rem; color: #9ca3af; }

    /* ── Mini product card ────────────────────────────── */
    .mini-product-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
        height: 100%;
    }

    .mini-product-card:hover {
        box-shadow: 0 12px 28px rgba(99,102,241,0.1);
        transform: translateY(-4px);
        border-color: #c7d2fe;
    }

    .mini-img-wrap {
        height: 160px;
        overflow: hidden;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        position: relative;
    }

    .mini-img-wrap img { width:100%; height:100%; object-fit:cover; transition: transform 0.3s; }
    .mini-product-card:hover .mini-img-wrap img { transform: scale(1.06); }

    .mini-img-placeholder {
        height: 160px;
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: flex; align-items: center; justify-content: center;
        color: #cbd5e1; font-size: 2.2rem;
    }

    .mini-card-body { padding: 0.9rem 1rem 0.75rem; }

    .mini-card-name {
        font-size: 0.9rem; font-weight: 700; color: #111827;
        margin-bottom: 0.2rem; line-height: 1.3;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .mini-card-price { font-size: 1.05rem; font-weight: 800; color: #6366f1; }

    .mini-card-footer {
        padding: 0.6rem 1rem;
        border-top: 1px solid #f3f4f6;
        background: #fafafa;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* ── Feature tiles ────────────────────────────────── */
    .feature-tile {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: box-shadow 0.2s, transform 0.2s;
    }

    .feature-tile:hover { box-shadow: 0 8px 24px rgba(0,0,0,0.07); transform: translateY(-3px); }

    .feature-tile-icon {
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }

    /* ── CTA banner ───────────────────────────────────── */
    .cta-banner {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .cta-banner::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.07);
        border-radius: 50%;
    }

    .cta-banner h3 { font-size: 1.4rem; font-weight: 800; margin-bottom: 0.5rem; }
    .cta-banner p  { color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-bottom: 1.25rem; }

    .btn-cta-white {
        background: #fff;
        color: #6366f1;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 0.6rem 1.5rem;
        border-radius: 10px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: opacity 0.2s;
    }

    .btn-cta-white:hover { opacity: 0.9; color: #4f46e5; }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════
     HERO
════════════════════════════════════════════════════ --}}
<div class="dash-hero">
    <div class="container dash-hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7">
                @if(session('auth_user'))
                    <p style="color:#818cf8; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem; letter-spacing:0.04em; text-transform:uppercase;">
                        <i class="bi bi-hand-wave me-1"></i>Welcome back
                    </p>
                    <h1>Hello, {{ session('auth_user')['name'] }} 👋</h1>
                    <p>Here's what's happening with your inventory today.</p>
                @else
                    <p style="color:#818cf8; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem; letter-spacing:0.04em; text-transform:uppercase;">
                        <i class="bi bi-lightning-charge me-1"></i>E-Commerce Inventory System
                    </p>
                    <h1>Manage Your Products<br>with Confidence</h1>
                    <p>A clean, fast, production-ready inventory system built with Laravel.</p>
                @endif

                <div class="dash-hero-actions">
                    <a href="{{ route('products.index') }}" class="btn-hero-primary">
                        <i class="bi bi-grid-3x3-gap"></i>Browse Products
                    </a>
                    @if(session('auth_user'))
                        <a href="{{ route('products.create') }}" class="btn-hero-outline">
                            <i class="bi bi-plus-circle"></i>Add Product
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-hero-outline">
                            <i class="bi bi-box-arrow-in-right"></i>Sign In
                        </a>
                    @endif
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-flex justify-content-end">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; max-width:280px;">
                    @foreach([
                        ['bi-box-seam',    '#ede9fe', '#6366f1', 'Products',   $stats['total']],
                        ['bi-check-circle','#dcfce7', '#16a34a', 'In Stock',   $stats['in_stock']],
                        ['bi-bag-check',   '#fef3c7', '#d97706', 'Orders',     '—'],
                        ['bi-people',      '#dbeafe', '#2563eb', 'Customers',  '—'],
                    ] as $tile)
                    <div style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:1rem; text-align:center;">
                        <div style="width:40px;height:40px;background:{{ $tile[1] }};border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 0.5rem;font-size:1.1rem;color:{{ $tile[2] }};">
                            <i class="bi {{ $tile[0] }}"></i>
                        </div>
                        <div style="font-size:1.2rem;font-weight:800;color:#fff;">{{ $tile[4] }}</div>
                        <div style="font-size:0.72rem;color:#94a3b8;font-weight:500;">{{ $tile[3] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MAIN CONTENT
════════════════════════════════════════════════════ --}}
<div class="container py-4">

    {{-- ── Stat cards ── --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon indigo"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total Products</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['in_stock'] }}</div>
                    <div class="stat-label">In Stock</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon red"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="stat-value">{{ $stats['out_stock'] }}</div>
                    <div class="stat-label">Out of Stock</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon amber"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="stat-value">${{ number_format($stats['total_value'], 0) }}</div>
                    <div class="stat-label">Inventory Value</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- ── Left column: latest products ── --}}
        <div class="col-lg-8">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="section-heading">Latest Products</div>
                    <div class="section-sub">Most recently added to inventory</div>
                </div>
                <a href="{{ route('products.index') }}"
                   style="font-size:0.82rem; font-weight:600; color:#6366f1; text-decoration:none;">
                    View all <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            @if(count($latest) > 0)
            <div class="row row-cols-1 row-cols-sm-2 g-3">
                @foreach($latest as $product)
                <div class="col">
                    <div class="mini-product-card">
                        <div class="mini-img-wrap">
                            @if(!empty($product['image_url']))
                                <img src="{{ $product['image_url'] }}" alt="{{ $product['name'] }}"
                                     onerror="this.parentElement.innerHTML='<div class=\'mini-img-placeholder\'><i class=\'bi bi-image\'></i></div>'">
                            @else
                                <div class="mini-img-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                            <span style="position:absolute;top:8px;right:8px;font-size:0.68rem;font-weight:700;padding:3px 8px;border-radius:20px;text-transform:uppercase;letter-spacing:0.04em;
                                {{ (int)$product['quantity'] > 0 ? 'background:#dcfce7;color:#15803d;' : 'background:#fee2e2;color:#dc2626;' }}">
                                {{ (int)$product['quantity'] > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                        </div>
                        <div class="mini-card-body">
                            <div class="mini-card-name">{{ $product['name'] }}</div>
                            <div class="mini-card-price">${{ number_format((float)$product['price'], 2) }}</div>
                        </div>
                        <div class="mini-card-footer">
                            <span style="font-size:0.75rem; color:#9ca3af;">
                                <i class="bi bi-box me-1"></i>{{ (int)$product['quantity'] }} units
                            </span>
                            <span style="font-size:0.72rem; color:#d1d5db;">
                                {{ \Carbon\Carbon::parse($product['created_at'])->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div style="background:#fff; border:2px dashed #e5e7eb; border-radius:16px; padding:3rem 2rem; text-align:center;">
                <div style="font-size:3rem; color:#e5e7eb; margin-bottom:1rem;"><i class="bi bi-box-seam"></i></div>
                <p style="color:#9ca3af; font-size:0.9rem; margin-bottom:1rem;">No products yet. Add your first one!</p>
                <a href="{{ route('products.create') }}" class="btn-hero-primary" style="display:inline-flex;">
                    <i class="bi bi-plus-circle"></i>Add Product
                </a>
            </div>
            @endif
        </div>

        {{-- ── Right column: quick actions + features ── --}}
        <div class="col-lg-4">

            {{-- Quick Actions --}}
            <div class="section-heading mb-1">Quick Actions</div>
            <div class="section-sub mb-3">Common tasks at a glance</div>

            <div class="d-flex flex-column gap-2 mb-4">
                <a href="{{ route('products.create') }}"
                   style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:0.85rem 1rem; text-decoration:none; display:flex; align-items:center; gap:0.75rem; transition:box-shadow 0.15s, border-color 0.15s;"
                   onmouseover="this.style.borderColor='#c7d2fe'; this.style.boxShadow='0 4px 12px rgba(99,102,241,0.1)'"
                   onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    <div style="width:38px;height:38px;background:#ede9fe;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#6366f1;font-size:1rem;flex-shrink:0;">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <div>
                        <div style="font-size:0.88rem; font-weight:600; color:#111827;">Add New Product</div>
                        <div style="font-size:0.75rem; color:#9ca3af;">Create a product listing</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto" style="color:#d1d5db; font-size:0.8rem;"></i>
                </a>

                <a href="{{ route('products.index') }}"
                   style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:0.85rem 1rem; text-decoration:none; display:flex; align-items:center; gap:0.75rem; transition:box-shadow 0.15s, border-color 0.15s;"
                   onmouseover="this.style.borderColor='#c7d2fe'; this.style.boxShadow='0 4px 12px rgba(99,102,241,0.1)'"
                   onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    <div style="width:38px;height:38px;background:#dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#2563eb;font-size:1rem;flex-shrink:0;">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </div>
                    <div>
                        <div style="font-size:0.88rem; font-weight:600; color:#111827;">Browse Inventory</div>
                        <div style="font-size:0.75rem; color:#9ca3af;">View all {{ $stats['total'] }} products</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto" style="color:#d1d5db; font-size:0.8rem;"></i>
                </a>

                @if(!session('auth_user'))
                <a href="{{ route('login') }}"
                   style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:0.85rem 1rem; text-decoration:none; display:flex; align-items:center; gap:0.75rem; transition:box-shadow 0.15s, border-color 0.15s;"
                   onmouseover="this.style.borderColor='#c7d2fe'; this.style.boxShadow='0 4px 12px rgba(99,102,241,0.1)'"
                   onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                    <div style="width:38px;height:38px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:1rem;flex-shrink:0;">
                        <i class="bi bi-box-arrow-in-right"></i>
                    </div>
                    <div>
                        <div style="font-size:0.88rem; font-weight:600; color:#111827;">Sign In</div>
                        <div style="font-size:0.75rem; color:#9ca3af;">Access your account</div>
                    </div>
                    <i class="bi bi-chevron-right ms-auto" style="color:#d1d5db; font-size:0.8rem;"></i>
                </a>
                @endif
            </div>

            {{-- System info card --}}
            <div style="background:#fff; border:1px solid #e5e7eb; border-radius:14px; padding:1.1rem 1.2rem;">
                <div style="font-size:0.78rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.75rem;">
                    System Info
                </div>
                @foreach([
                    ['bi-hdd',          '#ede9fe', '#6366f1', 'Storage',   'JSON File'],
                    ['bi-code-slash',   '#dbeafe', '#2563eb', 'Framework', 'Laravel'],
                    ['bi-palette',      '#fef3c7', '#d97706', 'UI',        'Bootstrap 5'],
                    ['bi-shield-check', '#dcfce7', '#16a34a', 'Auth',      'Session-based'],
                ] as $info)
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:28px;height:28px;background:{{ $info[1] }};border-radius:7px;display:flex;align-items:center;justify-content:center;color:{{ $info[2] }};font-size:0.8rem;flex-shrink:0;">
                        <i class="bi {{ $info[0] }}"></i>
                    </div>
                    <span style="font-size:0.8rem; color:#6b7280; flex:1;">{{ $info[3] }}</span>
                    <span style="font-size:0.8rem; font-weight:600; color:#374151;">{{ $info[4] }}</span>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    {{-- ── CTA banner ── --}}
    <div class="cta-banner mt-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3><i class="bi bi-rocket-takeoff me-2"></i>Ready to grow your inventory?</h3>
                <p>Add products, track stock levels, and manage your e-commerce catalogue — all in one place.</p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('products.create') }}" class="btn-cta-white">
                        <i class="bi bi-plus-circle"></i>Add Product
                    </a>
                    <a href="{{ route('products.index') }}"
                       style="background:rgba(255,255,255,0.12); color:#fff; font-weight:600; font-size:0.9rem; padding:0.6rem 1.5rem; border-radius:10px; border:1px solid rgba(255,255,255,0.25); text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; transition:background 0.2s;"
                       onmouseover="this.style.background='rgba(255,255,255,0.2)'"
                       onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                        <i class="bi bi-grid-3x3-gap"></i>View All Products
                    </a>
                </div>
            </div>
            <div class="col-md-4 d-none d-md-flex justify-content-end align-items-center">
                <div style="font-size:6rem; opacity:0.15; line-height:1;">
                    <i class="bi bi-bag-heart"></i>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
