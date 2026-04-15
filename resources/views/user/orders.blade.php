@extends('layouts.app')

@section('title', 'My Orders')

@push('styles')
<style>
    .account-sidebar {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        position: sticky;
        top: 80px;
    }

    .account-sidebar-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
        padding: 1.5rem;
        text-align: center;
        color: #fff;
    }

    .account-avatar-lg {
        width: 72px; height: 72px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800; color: #fff;
        margin: 0 auto 0.75rem;
        border: 3px solid rgba(255,255,255,0.2);
    }

    .account-sidebar-name { font-size: 1rem; font-weight: 700; margin-bottom: 0.2rem; }
    .account-sidebar-role { font-size: 0.75rem; color: #94a3b8; background: rgba(255,255,255,0.1); border-radius: 20px; padding: 2px 10px; display: inline-block; }

    .account-nav { padding: 0.5rem; }
    .account-nav-link { display: flex; align-items: center; gap: 0.65rem; padding: 0.65rem 0.9rem; border-radius: 10px; font-size: 0.88rem; font-weight: 500; color: #4b5563; text-decoration: none; transition: background 0.15s, color 0.15s; margin-bottom: 0.15rem; }
    .account-nav-link:hover { background: #f3f4f6; color: #6366f1; }
    .account-nav-link.active { background: #ede9fe; color: #6366f1; font-weight: 600; }
    .account-nav-link i { font-size: 1rem; width: 18px; text-align: center; }
    .account-nav-divider { border-top: 1px solid #f3f4f6; margin: 0.4rem 0.5rem; }

    .order-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        overflow: hidden;
        transition: box-shadow 0.2s, border-color 0.2s;
        margin-bottom: 1rem;
    }

    .order-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); border-color: #c7d2fe; }

    .order-card-header {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.9rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .order-id { font-size: 0.88rem; font-weight: 700; color: #111827; }
    .order-date { font-size: 0.75rem; color: #9ca3af; }

    .status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .status-confirmed { background: #dcfce7; color: #15803d; }
    .status-pending   { background: #fef3c7; color: #92400e; }

    .order-items-preview { padding: 0.9rem 1.25rem; }

    .order-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.4rem 0;
        font-size: 0.85rem;
        border-bottom: 1px solid #f9fafb;
    }

    .order-item-row:last-child { border-bottom: none; }

    .order-footer {
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        padding: 0.75rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .order-total { font-size: 1rem; font-weight: 800; color: #6366f1; }

    .payment-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #dbeafe;
        color: #1d4ed8;
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#818cf8;">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.profile') }}" style="color:#818cf8;">My Account</a></li>
                <li class="breadcrumb-item active" style="color:#94a3b8;">My Orders</li>
            </ol>
        </nav>
        <h1><i class="bi bi-bag-check me-2" style="color:#818cf8;"></i>My Orders</h1>
        <p>{{ count($orders) }} {{ Str::plural('order', count($orders)) }} placed</p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4 align-items-start">

        {{-- ── Sidebar ── --}}
        @php $user = session('auth_user'); @endphp
        <div class="col-lg-3 col-md-4">
            <div class="account-sidebar">
                <div class="account-sidebar-header">
                    <div class="account-avatar-lg">{{ $user['initials'] ?? 'U' }}</div>
                    <div class="account-sidebar-name">{{ $user['name'] }}</div>
                    <div class="account-sidebar-role">{{ $user['role'] ?? 'Customer' }}</div>
                </div>
                <nav class="account-nav">
                    <a href="{{ route('user.profile') }}" class="account-nav-link">
                        <i class="bi bi-person-fill"></i>Profile
                    </a>
                    <a href="{{ route('user.orders') }}" class="account-nav-link active">
                        <i class="bi bi-bag-check"></i>My Orders
                        @if(count($orders) > 0)
                            <span class="ms-auto badge rounded-pill" style="background:#6366f1; color:#fff; font-size:0.68rem;">{{ count($orders) }}</span>
                        @endif
                    </a>
                    <a href="{{ route('cart.index') }}" class="account-nav-link">
                        <i class="bi bi-bag"></i>Cart
                    </a>
                    <div class="account-nav-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="account-nav-link w-100 border-0 bg-transparent text-start" style="color:#ef4444; cursor:pointer;">
                            <i class="bi bi-box-arrow-right" style="color:#ef4444;"></i>Sign Out
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- ── Orders list ── --}}
        <div class="col-lg-9 col-md-8">

            @if(empty($orders))
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-bag-x"></i></div>
                <h4 style="font-weight:700; color:#111827; margin-bottom:0.5rem;">No orders yet</h4>
                <p class="text-muted mb-4">You haven't placed any orders. Start shopping!</p>
                <a href="{{ route('products.index') }}" class="btn btn-submit px-4">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Browse Products
                </a>
            </div>
            @else

            {{-- Summary stats --}}
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1rem; text-align:center;">
                        <div style="font-size:1.4rem; font-weight:800; color:#6366f1;">{{ count($orders) }}</div>
                        <div style="font-size:0.75rem; color:#6b7280; font-weight:500;">Total Orders</div>
                    </div>
                </div>
                <div class="col-4">
                    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1rem; text-align:center;">
                        <div style="font-size:1.4rem; font-weight:800; color:#16a34a;">
                            ${{ number_format(collect($orders)->sum('total'), 2) }}
                        </div>
                        <div style="font-size:0.75rem; color:#6b7280; font-weight:500;">Total Spent</div>
                    </div>
                </div>
                <div class="col-4">
                    <div style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; padding:1rem; text-align:center;">
                        <div style="font-size:1.4rem; font-weight:800; color:#d97706;">
                            {{ collect($orders)->sum(fn($o) => collect($o['items'])->sum('qty')) }}
                        </div>
                        <div style="font-size:0.75rem; color:#6b7280; font-weight:500;">Items Bought</div>
                    </div>
                </div>
            </div>

            {{-- Order cards --}}
            @foreach($orders as $order)
            <div class="order-card">

                {{-- Header --}}
                <div class="order-card-header">
                    <div>
                        <div class="order-id">
                            <i class="bi bi-hash me-1" style="color:#6366f1;"></i>{{ $order['id'] }}
                        </div>
                        <div class="order-date">
                            <i class="bi bi-calendar3 me-1"></i>{{ $order['created_at'] }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="payment-chip">
                            <i class="bi bi-{{ $order['payment_method'] === 'Cash on Delivery' ? 'cash-coin' : 'credit-card' }}"></i>
                            {{ $order['payment_method'] }}
                        </span>
                        <span class="status-badge status-confirmed">
                            <i class="bi bi-check-circle me-1"></i>{{ ucfirst($order['status']) }}
                        </span>
                    </div>
                </div>

                {{-- Items --}}
                <div class="order-items-preview">
                    @foreach($order['items'] as $item)
                    <div class="order-item-row">
                        <div>
                            <span style="font-weight:600; color:#111827;">{{ $item['name'] }}</span>
                            <span style="color:#9ca3af; font-size:0.78rem; margin-left:0.4rem;">× {{ $item['qty'] }}</span>
                        </div>
                        <span style="font-weight:700; color:#374151;">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="order-footer">
                    <div style="font-size:0.78rem; color:#6b7280;">
                        <i class="bi bi-geo-alt me-1"></i>
                        {{ $order['shipping_info']['city'] }}, {{ $order['shipping_info']['zip'] }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-truck me-1"></i>
                        {{ $order['shipping'] == 0 ? 'Free shipping' : '$'.number_format($order['shipping'], 2).' shipping' }}
                    </div>
                    <div class="order-total">
                        Total: ${{ number_format($order['total'], 2) }}
                    </div>
                </div>

            </div>
            @endforeach

            @endif
        </div>

    </div>
</div>

@endsection
