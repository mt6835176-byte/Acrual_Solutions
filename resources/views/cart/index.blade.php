@extends('layouts.app')

@section('title', 'Your Cart')

@push('styles')
<style>
    .cart-table { background:#fff; border-radius:16px; overflow:hidden; border:1px solid #e5e7eb; }
    .cart-table th { background:#f9fafb; font-size:0.78rem; font-weight:700; color:#6b7280; text-transform:uppercase; letter-spacing:0.05em; padding:0.85rem 1.2rem; border-bottom:1px solid #e5e7eb; }
    .cart-table td { padding:1rem 1.2rem; border-bottom:1px solid #f3f4f6; vertical-align:middle; }
    .cart-table tr:last-child td { border-bottom:none; }

    .cart-product-img {
        width:64px; height:64px; border-radius:10px; object-fit:cover;
        background:#f1f5f9; border:1px solid #e5e7eb; flex-shrink:0;
    }
    .cart-product-img-placeholder {
        width:64px; height:64px; border-radius:10px; background:#f1f5f9;
        border:1px solid #e5e7eb; display:flex; align-items:center;
        justify-content:center; color:#cbd5e1; font-size:1.4rem; flex-shrink:0;
    }

    .qty-input {
        width:70px; text-align:center; border:1.5px solid #e5e7eb;
        border-radius:8px; padding:0.35rem 0.5rem; font-size:0.9rem;
        font-weight:600; color:#111827;
    }
    .qty-input:focus { border-color:#6366f1; outline:none; box-shadow:0 0 0 3px rgba(99,102,241,0.1); }

    .btn-remove {
        background:none; border:none; color:#9ca3af; font-size:1.1rem;
        padding:0.3rem; border-radius:6px; transition:color 0.15s, background 0.15s; cursor:pointer;
    }
    .btn-remove:hover { color:#ef4444; background:#fee2e2; }

    .summary-card {
        background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:1.5rem;
        position:sticky; top:80px;
    }

    .summary-row { display:flex; justify-content:space-between; align-items:center; font-size:0.88rem; margin-bottom:0.6rem; }
    .summary-row.total { font-size:1.05rem; font-weight:800; color:#111827; border-top:1px solid #e5e7eb; padding-top:0.75rem; margin-top:0.5rem; }

    .btn-checkout {
        background:linear-gradient(135deg,#6366f1,#8b5cf6);
        color:#fff; font-weight:700; font-size:0.95rem;
        padding:0.75rem 1.5rem; border-radius:12px; border:none;
        width:100%; transition:opacity 0.2s, transform 0.15s;
        text-decoration:none; display:block; text-align:center;
    }
    .btn-checkout:hover { opacity:0.92; transform:translateY(-1px); color:#fff; }

    .free-shipping-bar { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.6rem 0.9rem; font-size:0.8rem; color:#15803d; margin-bottom:1rem; }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#818cf8;">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" style="color:#818cf8;">Shop</a></li>
                <li class="breadcrumb-item active" style="color:#94a3b8;">Cart</li>
            </ol>
        </nav>
        <h1><i class="bi bi-bag me-2" style="color:#818cf8;"></i>Your Cart</h1>
        <p>{{ count($cart) }} {{ Str::plural('item', count($cart)) }} in your cart</p>
    </div>
</div>

<div class="container py-4">

    @if(empty($cart))
    {{-- Empty cart --}}
    <div class="empty-state" style="max-width:480px; margin:2rem auto;">
        <div class="empty-state-icon"><i class="bi bi-bag-x"></i></div>
        <h4 style="font-weight:700; color:#111827; margin-bottom:0.5rem;">Your cart is empty</h4>
        <p class="text-muted mb-4">Looks like you haven't added anything yet.</p>
        <a href="{{ route('products.index') }}" class="btn btn-submit px-4">
            <i class="bi bi-grid-3x3-gap me-2"></i>Browse Products
        </a>
    </div>
    @else

    <div class="row g-4 align-items-start">

        {{-- ── Cart items ── --}}
        <div class="col-lg-8">

            {{-- Clear cart --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span style="font-size:0.85rem; color:#6b7280;">
                    <i class="bi bi-bag me-1"></i>{{ count($cart) }} {{ Str::plural('item', count($cart)) }}
                </span>
                <form method="POST" action="{{ route('cart.clear') }}" class="m-0"
                      onsubmit="return confirm('Clear your entire cart?')">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none; border:none; color:#9ca3af; font-size:0.82rem; font-weight:500; cursor:pointer; padding:0;">
                        <i class="bi bi-trash me-1"></i>Clear cart
                    </button>
                </form>
            </div>

            <div class="cart-table">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $item)
                        <tr>
                            {{-- Product info --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if(!empty($item['image_url']))
                                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                             class="cart-product-img"
                                             onerror="this.outerHTML='<div class=\'cart-product-img-placeholder\'><i class=\'bi bi-image\'></i></div>'">
                                    @else
                                        <div class="cart-product-img-placeholder"><i class="bi bi-image"></i></div>
                                    @endif
                                    <div>
                                        <div style="font-weight:600; font-size:0.9rem; color:#111827;">{{ $item['name'] }}</div>
                                        <div style="font-size:0.75rem; color:#9ca3af;">Unit price: ${{ number_format($item['price'], 2) }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Qty update --}}
                            <td class="text-center">
                                <form method="POST" action="{{ route('cart.update', $id) }}" class="d-inline-flex align-items-center gap-1">
                                    @csrf @method('PATCH')
                                    <input type="number" name="qty" value="{{ $item['qty'] }}"
                                           min="1" max="999" class="qty-input"
                                           onchange="this.form.submit()">
                                </form>
                            </td>

                            {{-- Unit price --}}
                            <td class="text-end" style="font-weight:600; color:#374151;">
                                ${{ number_format($item['price'], 2) }}
                            </td>

                            {{-- Line total --}}
                            <td class="text-end" style="font-weight:700; color:#111827;">
                                ${{ number_format($item['price'] * $item['qty'], 2) }}
                            </td>

                            {{-- Remove --}}
                            <td class="text-center">
                                <form method="POST" action="{{ route('cart.remove', $id) }}" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-remove" title="Remove">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('products.index') }}" style="font-size:0.85rem; color:#6366f1; text-decoration:none; font-weight:500;">
                    <i class="bi bi-arrow-left me-1"></i>Continue Shopping
                </a>
            </div>
        </div>

        {{-- ── Order summary sidebar ── --}}
        <div class="col-lg-4">
            <div class="summary-card">
                <h5 style="font-weight:700; margin-bottom:1.25rem; color:#111827;">Order Summary</h5>

                @php
                    $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
                    $shipping = $subtotal >= 50 ? 0 : 5.99;
                    $tax      = round($subtotal * 0.08, 2);
                    $grandTotal = round($subtotal + $shipping + $tax, 2);
                @endphp

                @if($shipping == 0)
                <div class="free-shipping-bar">
                    <i class="bi bi-truck me-1"></i><strong>Free shipping</strong> applied on orders over $50!
                </div>
                @else
                <div style="background:#fef3c7; border:1px solid #fde68a; border-radius:10px; padding:0.6rem 0.9rem; font-size:0.8rem; color:#92400e; margin-bottom:1rem;">
                    <i class="bi bi-truck me-1"></i>Add <strong>${{ number_format(50 - $subtotal, 2) }}</strong> more for free shipping
                </div>
                @endif

                <div class="summary-row"><span style="color:#6b7280;">Subtotal</span><span style="font-weight:600;">${{ number_format($subtotal, 2) }}</span></div>
                <div class="summary-row"><span style="color:#6b7280;">Shipping</span><span style="font-weight:600; color:{{ $shipping == 0 ? '#16a34a' : '#374151' }};">{{ $shipping == 0 ? 'FREE' : '$'.number_format($shipping, 2) }}</span></div>
                <div class="summary-row"><span style="color:#6b7280;">Tax (8%)</span><span style="font-weight:600;">${{ number_format($tax, 2) }}</span></div>
                <div class="summary-row total"><span>Total</span><span style="color:#6366f1;">${{ number_format($grandTotal, 2) }}</span></div>

                <a href="{{ route('order.summary') }}" class="btn-checkout mt-3">
                    <i class="bi bi-bag-check me-2"></i>Proceed to Order
                </a>

                <div class="mt-3 text-center" style="font-size:0.75rem; color:#9ca3af;">
                    <i class="bi bi-shield-lock me-1"></i>Secure checkout &nbsp;·&nbsp;
                    <i class="bi bi-arrow-counterclockwise me-1"></i>30-day returns
                </div>
            </div>
        </div>

    </div>
    @endif

</div>

@endsection
