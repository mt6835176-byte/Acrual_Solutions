@extends('layouts.app')

@section('title', 'All Products')

@section('content')

{{-- Page Hero --}}
<div class="page-hero">
    <div class="container">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h1><i class="bi bi-bag-heart me-2" style="color:#818cf8;"></i>Product Inventory</h1>
                <p>Manage your e-commerce product catalogue</p>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="hero-stat">
                    <strong>{{ count($products) }}</strong>
                    Total Products
                </div>
                <div class="hero-stat">
                    <strong>{{ collect($products)->where('quantity', '>', 0)->count() }}</strong>
                    In Stock
                </div>
                <a href="{{ route('products.create') }}" class="btn btn-nav-add">
                    <i class="bi bi-plus-lg me-1"></i>Add Product
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Product Grid --}}
<div class="container py-4">

    @forelse($products as $product)
        @if($loop->first)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @endif

        <div class="col">
            <div class="product-card">

                {{-- Image --}}
                <div class="product-img-wrap">
                    @if(!empty($product['image_url']))
                        <img src="{{ $product['image_url'] }}"
                             alt="{{ $product['name'] }}"
                             onerror="this.parentElement.innerHTML='<div class=\'product-img-placeholder\'><i class=\'bi bi-image\'></i><span>No Image</span></div>'">
                    @else
                        <div class="product-img-placeholder">
                            <i class="bi bi-image"></i>
                            <span>No Image</span>
                        </div>
                    @endif

                    {{-- Stock badge --}}
                    @if((int)$product['quantity'] > 0)
                        <span class="stock-badge in-stock">
                            <i class="bi bi-check2 me-1"></i>In Stock
                        </span>
                    @else
                        <span class="stock-badge out-of-stock">
                            <i class="bi bi-x me-1"></i>Out of Stock
                        </span>
                    @endif
                </div>

                {{-- Body --}}
                <div class="product-card-body">
                    <div class="product-category-tag">
                        <i class="bi bi-tag me-1"></i>Inventory Item
                    </div>
                    <div class="product-name">{{ $product['name'] }}</div>
                    <div class="product-desc">
                        @if(!empty($product['description']))
                            {{ Str::limit($product['description'], 90) }}
                        @else
                            <span class="fst-italic" style="color:#d1d5db;">No description available.</span>
                        @endif
                    </div>

                    <div class="d-flex align-items-baseline gap-1">
                        <span class="product-price">
                            ${{ number_format(floor((float)$product['price']), 0) }}<span class="product-price-cents">.{{ substr(number_format((float)$product['price'], 2), -2) }}</span>
                        </span>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="product-card-footer d-flex align-items-center justify-content-between">
                    <span class="qty-display">
                        <i class="bi bi-box me-1"></i>{{ (int)$product['quantity'] }} units
                    </span>
                    @if((int)$product['quantity'] > 0)
                        <form method="POST" action="{{ route('cart.add', $product['id']) }}" class="m-0">
                            @csrf
                            <button type="submit" class="btn-add-cart">
                                <i class="bi bi-cart-plus me-1"></i>Add to Cart
                            </button>
                        </form>
                    @else
                        <span style="font-size:0.78rem; color:#ef4444; font-weight:600;">
                            <i class="bi bi-x-circle me-1"></i>Out of Stock
                        </span>
                    @endif
                </div>

            </div>
        </div>

        @if($loop->last)
        </div>
        @endif

    @empty
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-bag-x"></i>
            </div>
            <h4 class="fw-700 mb-2" style="font-weight:700; color:#111827;">No products yet</h4>
            <p class="text-muted mb-4" style="max-width:360px; margin:0 auto 1.5rem;">
                Your inventory is empty. Add your first product to get started.
            </p>
            <a href="{{ route('products.create') }}" class="btn btn-submit px-4 py-2">
                <i class="bi bi-plus-circle me-2"></i>Add Your First Product
            </a>
        </div>
    @endforelse

</div>

@endsection
