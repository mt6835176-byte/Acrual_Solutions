@extends('layouts.app')

@section('title', 'Order Summary')

@push('styles')
<style>
    .order-card { background:#fff; border:1px solid #e5e7eb; border-radius:16px; overflow:hidden; }
    .order-card-header { background:linear-gradient(135deg,#1a1a2e,#0f3460); color:#fff; padding:1.25rem 1.5rem; }
    .order-card-header h5 { font-weight:700; margin:0; font-size:1rem; }
    .order-card-body { padding:1.5rem; }

    .order-item { display:flex; align-items:center; gap:0.9rem; padding:0.75rem 0; border-bottom:1px solid #f3f4f6; }
    .order-item:last-child { border-bottom:none; }
    .order-item-img { width:52px; height:52px; border-radius:8px; object-fit:cover; background:#f1f5f9; border:1px solid #e5e7eb; flex-shrink:0; }
    .order-item-placeholder { width:52px; height:52px; border-radius:8px; background:#f1f5f9; border:1px solid #e5e7eb; display:flex; align-items:center; justify-content:center; color:#cbd5e1; font-size:1.2rem; flex-shrink:0; }

    .step-indicator { display:flex; align-items:center; justify-content:center; gap:0; margin-bottom:2rem; }
    .step { display:flex; flex-direction:column; align-items:center; gap:0.3rem; flex:1; position:relative; }
    .step-circle { width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.85rem; font-weight:700; z-index:1; }
    .step-circle.done { background:#6366f1; color:#fff; }
    .step-circle.active { background:#6366f1; color:#fff; box-shadow:0 0 0 4px rgba(99,102,241,0.2); }
    .step-circle.pending { background:#e5e7eb; color:#9ca3af; }
    .step-label { font-size:0.72rem; font-weight:600; color:#6b7280; text-align:center; }
    .step-line { flex:1; height:2px; background:#e5e7eb; margin-top:-18px; }
    .step-line.done { background:#6366f1; }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#818cf8;">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" style="color:#818cf8;">Cart</a></li>
                <li class="breadcrumb-item active" style="color:#94a3b8;">Order Summary</li>
            </ol>
        </nav>
        <h1><i class="bi bi-clipboard-check me-2" style="color:#818cf8;"></i>Order Summary</h1>
        <p>Review your order and enter shipping details</p>
    </div>
</div>

<div class="container py-4">

    {{-- Step indicator --}}
    <div class="step-indicator" style="max-width:500px; margin:0 auto 2rem;">
        <div class="step">
            <div class="step-circle done"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Cart</div>
        </div>
        <div class="step-line done"></div>
        <div class="step">
            <div class="step-circle active">2</div>
            <div class="step-label">Order</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-circle pending">3</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-circle pending">4</div>
            <div class="step-label">Done</div>
        </div>
    </div>

    <form method="POST" action="{{ route('order.place') }}" novalidate>
        @csrf

        <div class="row g-4 align-items-start">

            {{-- ── Left: shipping form ── --}}
            <div class="col-lg-7">
                <div class="order-card">
                    <div class="order-card-header">
                        <h5><i class="bi bi-geo-alt me-2"></i>Shipping Information</h5>
                    </div>
                    <div class="order-card-body">

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name"
                                       class="form-control @error('full_name') is-invalid @enderror"
                                       value="{{ old('full_name', $user['name'] ?? '') }}"
                                       placeholder="Jane Smith">
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user['email'] ?? '') }}"
                                       placeholder="you@example.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}"
                                       placeholder="+1 555 000 0000">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" name="city"
                                       class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city') }}"
                                       placeholder="New York">
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label">Address <span class="text-danger">*</span></label>
                                <input type="text" name="address"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address') }}"
                                       placeholder="123 Main Street, Apt 4B">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">ZIP / Postal <span class="text-danger">*</span></label>
                                <input type="text" name="zip"
                                       class="form-control @error('zip') is-invalid @enderror"
                                       value="{{ old('zip') }}"
                                       placeholder="10001">
                                @error('zip')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Right: order summary ── --}}
            <div class="col-lg-5">
                <div class="order-card mb-3">
                    <div class="order-card-header">
                        <h5><i class="bi bi-receipt me-2"></i>Items ({{ count($cart) }})</h5>
                    </div>
                    <div class="order-card-body" style="padding:1rem 1.25rem;">
                        @foreach($cart as $item)
                        <div class="order-item">
                            @if(!empty($item['image_url']))
                                <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="order-item-img"
                                     onerror="this.outerHTML='<div class=\'order-item-placeholder\'><i class=\'bi bi-image\'></i></div>'">
                            @else
                                <div class="order-item-placeholder"><i class="bi bi-image"></i></div>
                            @endif
                            <div class="flex-grow-1">
                                <div style="font-size:0.88rem; font-weight:600; color:#111827;">{{ $item['name'] }}</div>
                                <div style="font-size:0.75rem; color:#9ca3af;">Qty: {{ $item['qty'] }} × ${{ number_format($item['price'], 2) }}</div>
                            </div>
                            <div style="font-weight:700; font-size:0.9rem; color:#111827; white-space:nowrap;">
                                ${{ number_format($item['price'] * $item['qty'], 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Totals --}}
                <div class="order-card">
                    <div class="order-card-body">
                        <div style="display:flex; justify-content:space-between; font-size:0.88rem; margin-bottom:0.5rem;">
                            <span style="color:#6b7280;">Subtotal</span>
                            <span style="font-weight:600;">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.88rem; margin-bottom:0.5rem;">
                            <span style="color:#6b7280;">Shipping</span>
                            <span style="font-weight:600; color:{{ $shipping == 0 ? '#16a34a' : '#374151' }};">{{ $shipping == 0 ? 'FREE' : '$'.number_format($shipping, 2) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:0.88rem; margin-bottom:0.75rem;">
                            <span style="color:#6b7280;">Tax (8%)</span>
                            <span style="font-weight:600;">${{ number_format($tax, 2) }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:1.05rem; font-weight:800; color:#111827; border-top:1px solid #e5e7eb; padding-top:0.75rem;">
                            <span>Total</span>
                            <span style="color:#6366f1;">${{ number_format($total, 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-submit w-100 mt-3">
                            <i class="bi bi-credit-card me-2"></i>Proceed to Payment
                        </button>

                        <a href="{{ route('cart.index') }}" class="d-block text-center mt-2"
                           style="font-size:0.82rem; color:#9ca3af; text-decoration:none;">
                            <i class="bi bi-arrow-left me-1"></i>Back to Cart
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </form>
</div>

@endsection
