@extends('layouts.app')

@section('title', 'Order Confirmed!')

@push('styles')
<style>
    .success-hero {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border-bottom: 1px solid #bbf7d0;
        padding: 3rem 0;
        text-align: center;
    }

    .success-icon-wrap {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.25rem;
        font-size: 2.5rem; color: #fff;
        box-shadow: 0 8px 24px rgba(34,197,94,0.3);
        animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
    }

    @keyframes popIn {
        0%   { transform: scale(0); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .success-hero h1 { font-size: 1.9rem; font-weight: 800; color: #14532d; margin-bottom: 0.4rem; }
    .success-hero p  { color: #16a34a; font-size: 0.95rem; }

    .order-ref {
        display: inline-block;
        background: #fff;
        border: 2px solid #22c55e;
        border-radius: 12px;
        padding: 0.5rem 1.25rem;
        font-size: 1.1rem;
        font-weight: 800;
        color: #15803d;
        letter-spacing: 0.1em;
        margin-top: 0.75rem;
    }

    .step-indicator { display:flex; align-items:center; justify-content:center; gap:0; margin-bottom:2rem; }
    .step { display:flex; flex-direction:column; align-items:center; gap:0.3rem; flex:1; }
    .step-circle { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:700; z-index:1; }
    .step-circle.done { background:#22c55e; color:#fff; }
    .step-label { font-size:0.72rem; font-weight:600; color:#6b7280; text-align:center; }
    .step-line { flex:1; height:2px; background:#22c55e; margin-top:-18px; }

    .detail-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; }
    .detail-card-header { background:linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff; padding:1rem 1.25rem; }
    .detail-card-header h5 { font-weight:700; margin:0; font-size:0.95rem; }
</style>
@endpush

@section('content')

{{-- Success hero --}}
<div class="success-hero">
    <div class="container">
        <div class="success-icon-wrap">
            <i class="bi bi-check-lg"></i>
        </div>
        <h1>Order Confirmed!</h1>
        <p>Thank you for your purchase. Your order has been placed successfully.</p>
        <div class="order-ref">
            <i class="bi bi-hash me-1"></i>{{ $order['id'] }}
        </div>
    </div>
</div>

<div class="container py-4">

    {{-- Step indicator --}}
    <div class="step-indicator" style="max-width:500px; margin:0 auto 2rem;">
        <div class="step"><div class="step-circle done"><i class="bi bi-check-lg"></i></div><div class="step-label">Cart</div></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle done"><i class="bi bi-check-lg"></i></div><div class="step-label">Order</div></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle done"><i class="bi bi-check-lg"></i></div><div class="step-label">Payment</div></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle done"><i class="bi bi-check-lg"></i></div><div class="step-label">Done</div></div>
    </div>

    <div class="row g-4 justify-content-center">
        <div class="col-lg-8">

            {{-- Order items --}}
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <h5><i class="bi bi-bag-check me-2"></i>Items Ordered</h5>
                </div>
                <div style="padding:1rem 1.25rem;">
                    @foreach($order['items'] as $item)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; border-bottom:1px solid #f3f4f6; font-size:0.88rem;">
                        <div>
                            <span style="font-weight:600; color:#111827;">{{ $item['name'] }}</span>
                            <span style="color:#9ca3af; margin-left:0.5rem;">× {{ $item['qty'] }}</span>
                        </div>
                        <span style="font-weight:700; color:#111827;">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                    </div>
                    @endforeach

                    <div style="margin-top:0.75rem; padding-top:0.75rem; border-top:1px solid #e5e7eb;">
                        <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6b7280; margin-bottom:0.3rem;">
                            <span>Subtotal</span><span>${{ number_format($order['subtotal'], 2) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6b7280; margin-bottom:0.3rem;">
                            <span>Shipping</span>
                            <span style="color:{{ $order['shipping'] == 0 ? '#16a34a' : '#374151' }};">
                                {{ $order['shipping'] == 0 ? 'FREE' : '$'.number_format($order['shipping'], 2) }}
                            </span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6b7280; margin-bottom:0.75rem;">
                            <span>Tax</span><span>${{ number_format($order['tax'], 2) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:1.05rem; font-weight:800; color:#111827;">
                            <span>Total Paid</span>
                            <span style="color:#16a34a;">${{ number_format($order['total'], 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping + payment info --}}
            <div class="row g-3 mb-4">
                <div class="col-sm-6">
                    <div class="detail-card h-100">
                        <div class="detail-card-header"><h5><i class="bi bi-geo-alt me-2"></i>Shipping To</h5></div>
                        <div style="padding:1rem 1.25rem; font-size:0.85rem; color:#374151; line-height:1.7;">
                            <strong>{{ $order['shipping_info']['full_name'] }}</strong><br>
                            {{ $order['shipping_info']['address'] }}<br>
                            {{ $order['shipping_info']['city'] }}, {{ $order['shipping_info']['zip'] }}<br>
                            <span style="color:#6b7280;">{{ $order['shipping_info']['phone'] }}</span><br>
                            <span style="color:#6b7280;">{{ $order['shipping_info']['email'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="detail-card h-100">
                        <div class="detail-card-header"><h5><i class="bi bi-credit-card me-2"></i>Payment</h5></div>
                        <div style="padding:1rem 1.25rem; font-size:0.85rem; color:#374151; line-height:1.7;">
                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                                <span style="background:#dcfce7; color:#15803d; font-size:0.72rem; font-weight:700; padding:3px 10px; border-radius:20px; text-transform:uppercase;">Confirmed</span>
                            </div>
                            <strong>{{ $order['payment_method'] }}</strong><br>
                            <span style="color:#6b7280;">Paid at: {{ $order['paid_at'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex gap-3 flex-wrap justify-content-center">
                <a href="{{ route('products.index') }}" class="btn btn-submit px-4">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Continue Shopping
                </a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4" style="border-radius:10px; font-weight:600;">
                    <i class="bi bi-house-door me-2"></i>Back to Home
                </a>
            </div>

        </div>
    </div>

</div>

@endsection
