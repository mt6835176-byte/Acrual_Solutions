@extends('layouts.app')

@section('title', 'Payment')

@push('styles')
<style>
    .payment-method-card {
        border:2px solid #e5e7eb; border-radius:14px; padding:1.25rem 1.5rem;
        cursor:pointer; transition:border-color 0.2s, background 0.2s;
        margin-bottom:0.75rem;
    }
    .payment-method-card:hover { border-color:#c7d2fe; background:#fafafe; }
    .payment-method-card.selected { border-color:#6366f1; background:#f5f3ff; }

    .payment-method-card input[type="radio"] { display:none; }

    .method-icon {
        width:44px; height:44px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
        font-size:1.3rem; flex-shrink:0;
    }

    #card-fields { display:none; }
    #card-fields.show { display:block; }

    .card-input-group { position:relative; }
    .card-input-group .card-icon {
        position:absolute; right:12px; top:50%; transform:translateY(-50%);
        color:#9ca3af; font-size:1.1rem; pointer-events:none;
    }

    .step-indicator { display:flex; align-items:center; justify-content:center; gap:0; margin-bottom:2rem; }
    .step { display:flex; flex-direction:column; align-items:center; gap:0.3rem; flex:1; }
    .step-circle { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:700; z-index:1; }
    .step-circle.done { background:#6366f1; color:#fff; }
    .step-circle.active { background:#6366f1; color:#fff; box-shadow:0 0 0 4px rgba(99,102,241,0.2); }
    .step-circle.pending { background:#e5e7eb; color:#9ca3af; }
    .step-label { font-size:0.72rem; font-weight:600; color:#6b7280; text-align:center; }
    .step-line { flex:1; height:2px; background:#e5e7eb; margin-top:-18px; }
    .step-line.done { background:#6366f1; }

    .secure-badge { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:0.6rem 0.9rem; font-size:0.78rem; color:#15803d; display:flex; align-items:center; gap:0.5rem; }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#818cf8;">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" style="color:#818cf8;">Cart</a></li>
                <li class="breadcrumb-item"><a href="{{ route('order.summary') }}" style="color:#818cf8;">Order</a></li>
                <li class="breadcrumb-item active" style="color:#94a3b8;">Payment</li>
            </ol>
        </nav>
        <h1><i class="bi bi-credit-card me-2" style="color:#818cf8;"></i>Payment</h1>
        <p>Choose your payment method to complete the order</p>
    </div>
</div>

<div class="container py-4">

    {{-- Step indicator --}}
    <div class="step-indicator" style="max-width:500px; margin:0 auto 2rem;">
        <div class="step"><div class="step-circle done"><i class="bi bi-check-lg"></i></div><div class="step-label">Cart</div></div>
        <div class="step-line done"></div>
        <div class="step"><div class="step-circle done"><i class="bi bi-check-lg"></i></div><div class="step-label">Order</div></div>
        <div class="step-line done"></div>
        <div class="step"><div class="step-circle active">3</div><div class="step-label">Payment</div></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-circle pending">4</div><div class="step-label">Done</div></div>
    </div>

    <form method="POST" action="{{ route('payment.process') }}" id="paymentForm" novalidate>
        @csrf

        <div class="row g-4 align-items-start justify-content-center">

            {{-- ── Left: payment methods ── --}}
            <div class="col-lg-7">

                <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; padding:1.5rem;">
                    <h5 style="font-weight:700; color:#111827; margin-bottom:1.25rem;">
                        <i class="bi bi-wallet2 me-2 text-primary"></i>Select Payment Method
                    </h5>

                    @error('payment_method')
                        <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
                    @enderror

                    {{-- Cash on Delivery --}}
                    <label class="payment-method-card d-flex align-items-center gap-3" id="cod-card">
                        <input type="radio" name="payment_method" value="cod" id="cod" checked>
                        <div class="method-icon" style="background:#fef3c7; color:#d97706;">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:700; font-size:0.95rem; color:#111827;">Cash on Delivery</div>
                            <div style="font-size:0.78rem; color:#6b7280;">Pay when your order arrives at your door</div>
                        </div>
                        <div id="cod-check" style="color:#6366f1; font-size:1.2rem;"><i class="bi bi-check-circle-fill"></i></div>
                    </label>

                    {{-- Card Payment --}}
                    <label class="payment-method-card d-flex align-items-center gap-3" id="card-card">
                        <input type="radio" name="payment_method" value="card" id="card">
                        <div class="method-icon" style="background:#dbeafe; color:#2563eb;">
                            <i class="bi bi-credit-card-2-front"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:700; font-size:0.95rem; color:#111827;">Card Payment</div>
                            <div style="font-size:0.78rem; color:#6b7280;">Visa, Mastercard, Amex — UI demo only</div>
                        </div>
                        <div id="card-check" style="color:#e5e7eb; font-size:1.2rem;"><i class="bi bi-circle"></i></div>
                    </label>

                    {{-- Card fields (shown when card is selected) --}}
                    <div id="card-fields" class="mt-3 p-3 rounded-3" style="background:#f9fafb; border:1px solid #e5e7eb;">
                        <div class="mb-3">
                            <label class="form-label">Cardholder Name</label>
                            <input type="text" name="card_name"
                                   class="form-control @error('card_name') is-invalid @enderror"
                                   value="{{ old('card_name') }}"
                                   placeholder="Jane Smith">
                            @error('card_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Card Number</label>
                            <div class="card-input-group">
                                <input type="text" name="card_number"
                                       class="form-control @error('card_number') is-invalid @enderror"
                                       value="{{ old('card_number') }}"
                                       placeholder="1234 5678 9012 3456"
                                       maxlength="16"
                                       inputmode="numeric">
                                <span class="card-icon"><i class="bi bi-credit-card"></i></span>
                                @error('card_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Expiry (MM/YY)</label>
                                <input type="text" name="card_expiry"
                                       class="form-control @error('card_expiry') is-invalid @enderror"
                                       value="{{ old('card_expiry') }}"
                                       placeholder="08/27" maxlength="5">
                                @error('card_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-6">
                                <label class="form-label">CVV</label>
                                <input type="text" name="card_cvv"
                                       class="form-control @error('card_cvv') is-invalid @enderror"
                                       value="{{ old('card_cvv') }}"
                                       placeholder="123" maxlength="4"
                                       inputmode="numeric">
                                @error('card_cvv')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mt-2" style="font-size:0.75rem; color:#9ca3af;">
                            <i class="bi bi-info-circle me-1"></i>This is a UI demo — no real card data is processed.
                        </div>
                    </div>

                    <div class="secure-badge mt-3">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                        <span>Your payment information is <strong>secure</strong>. This is a demo application.</span>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 mt-4" style="font-size:1rem; padding:0.8rem;">
                        <i class="bi bi-lock-fill me-2"></i>Confirm & Pay ${{ number_format($order['total'], 2) }}
                    </button>
                </div>

            </div>

            {{-- ── Right: order recap ── --}}
            <div class="col-lg-5">
                <div style="background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden;">
                    <div style="background:linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff; padding:1rem 1.25rem;">
                        <div style="font-weight:700; font-size:0.95rem;">
                            <i class="bi bi-receipt me-2"></i>Order #{{ $order['id'] }}
                        </div>
                        <div style="font-size:0.75rem; color:#94a3b8; margin-top:0.2rem;">{{ $order['created_at'] }}</div>
                    </div>
                    <div style="padding:1rem 1.25rem;">
                        @foreach($order['items'] as $item)
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; border-bottom:1px solid #f3f4f6; font-size:0.85rem;">
                            <span style="color:#374151; font-weight:500;">{{ $item['name'] }} <span style="color:#9ca3af;">×{{ $item['qty'] }}</span></span>
                            <span style="font-weight:700; color:#111827;">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                        </div>
                        @endforeach

                        <div style="margin-top:0.75rem;">
                            <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6b7280; margin-bottom:0.35rem;">
                                <span>Subtotal</span><span>${{ number_format($order['subtotal'], 2) }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6b7280; margin-bottom:0.35rem;">
                                <span>Shipping</span>
                                <span style="color:{{ $order['shipping'] == 0 ? '#16a34a' : '#374151' }};">
                                    {{ $order['shipping'] == 0 ? 'FREE' : '$'.number_format($order['shipping'], 2) }}
                                </span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:0.82rem; color:#6b7280; margin-bottom:0.75rem;">
                                <span>Tax</span><span>${{ number_format($order['tax'], 2) }}</span>
                            </div>
                            <div style="display:flex; justify-content:space-between; font-size:1.05rem; font-weight:800; color:#111827; border-top:1px solid #e5e7eb; padding-top:0.75rem;">
                                <span>Total</span>
                                <span style="color:#6366f1;">${{ number_format($order['total'], 2) }}</span>
                            </div>
                        </div>

                        <div style="margin-top:1rem; padding:0.75rem; background:#f9fafb; border-radius:10px; font-size:0.78rem; color:#6b7280;">
                            <div style="font-weight:600; color:#374151; margin-bottom:0.3rem;"><i class="bi bi-geo-alt me-1"></i>Shipping to</div>
                            {{ $order['shipping_info']['full_name'] }}<br>
                            {{ $order['shipping_info']['address'] }}, {{ $order['shipping_info']['city'] }} {{ $order['shipping_info']['zip'] }}<br>
                            {{ $order['shipping_info']['phone'] }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const codRadio   = document.getElementById('cod');
    const cardRadio  = document.getElementById('card');
    const codCard    = document.getElementById('cod-card');
    const cardCard   = document.getElementById('card-card');
    const cardFields = document.getElementById('card-fields');
    const codCheck   = document.getElementById('cod-check');
    const cardCheck  = document.getElementById('card-check');

    function updateUI() {
        if (cardRadio.checked) {
            cardCard.classList.add('selected');
            codCard.classList.remove('selected');
            cardFields.classList.add('show');
            cardCheck.innerHTML = '<i class="bi bi-check-circle-fill" style="color:#6366f1;"></i>';
            codCheck.innerHTML  = '<i class="bi bi-circle" style="color:#e5e7eb;"></i>';
        } else {
            codCard.classList.add('selected');
            cardCard.classList.remove('selected');
            cardFields.classList.remove('show');
            codCheck.innerHTML  = '<i class="bi bi-check-circle-fill" style="color:#6366f1;"></i>';
            cardCheck.innerHTML = '<i class="bi bi-circle" style="color:#e5e7eb;"></i>';
        }
    }

    codRadio.addEventListener('change', updateUI);
    cardRadio.addEventListener('change', updateUI);

    // Restore card state if validation failed and card was selected
    @if(old('payment_method') === 'card')
        cardRadio.checked = true;
        updateUI();
    @endif

    // Auto-format card number with spaces (display only)
    const cardNumInput = document.querySelector('input[name="card_number"]');
    if (cardNumInput) {
        cardNumInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 16);
        });
    }

    // Auto-format expiry MM/YY
    const expiryInput = document.querySelector('input[name="card_expiry"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, '').slice(0, 4);
            if (v.length >= 3) v = v.slice(0,2) + '/' + v.slice(2);
            this.value = v;
        });
    }
});
</script>
@endpush
