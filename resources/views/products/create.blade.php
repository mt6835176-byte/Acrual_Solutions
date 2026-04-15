@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')

{{-- Page Hero --}}
<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index') }}" style="color:#818cf8;">
                        <i class="bi bi-grid-3x3-gap me-1"></i>Products
                    </a>
                </li>
                <li class="breadcrumb-item active" style="color:#94a3b8;">Add Product</li>
            </ol>
        </nav>
        <h1><i class="bi bi-plus-circle me-2" style="color:#818cf8;"></i>Add New Product</h1>
        <p>Fill in the details below to add a product to your inventory</p>
    </div>
</div>

{{-- Form --}}
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="form-card shadow-sm">

                {{-- Card Header --}}
                <div class="form-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:44px; height:44px; background:rgba(99,102,241,0.25); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; color:#a5b4fc;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h4>Product Details</h4>
                            <p>Fields marked <span style="color:#f87171;">*</span> are required</p>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-4">
                    <form method="POST" action="{{ route('products.store') }}" novalidate>
                        @csrf

                        {{-- Product Name --}}
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                Product Name <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Wireless Mechanical Keyboard"
                                   maxlength="255" autofocus>
                            @error('name')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Price + Quantity --}}
                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="price" class="form-label">
                                    Price <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price"
                                           value="{{ old('price') }}"
                                           placeholder="0.00"
                                           step="0.01" min="0.01">
                                    @error('price')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="quantity" class="form-label">
                                    Stock Quantity <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-box"></i></span>
                                    <input type="number"
                                           class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity" name="quantity"
                                           value="{{ old('quantity') }}"
                                           placeholder="0"
                                           min="0">
                                    @error('quantity')
                                        <div class="invalid-feedback">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Description
                                <span class="text-muted fw-normal ms-1" style="font-size:0.78rem;">(optional)</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description"
                                      rows="3"
                                      placeholder="Describe the product — features, materials, dimensions...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Image URL --}}
                        <div class="mb-4">
                            <label for="image_url" class="form-label">
                                Product Image URL
                                <span class="text-muted fw-normal ms-1" style="font-size:0.78rem;">(optional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="url"
                                       class="form-control @error('image_url') is-invalid @enderror"
                                       id="image_url" name="image_url"
                                       value="{{ old('image_url') }}"
                                       placeholder="https://example.com/product-image.jpg"
                                       maxlength="2048">
                                @error('image_url')
                                    <div class="invalid-feedback">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-text mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Paste a direct link to the product image (JPEG, PNG, WebP).
                            </div>
                        </div>

                        {{-- Divider --}}
                        <hr style="border-color:#f3f4f6; margin: 1.5rem 0;">

                        {{-- Actions --}}
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <button type="submit" class="btn btn-submit">
                                <i class="bi bi-check-circle me-2"></i>Save Product
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" style="border-radius:10px; font-weight:600; font-size:0.9rem;">
                                <i class="bi bi-arrow-left me-2"></i>Back to Inventory
                            </a>
                        </div>

                    </form>
                </div>

            </div>

            {{-- Tips card --}}
            <div class="mt-3 p-3 rounded-3" style="background:#ede9fe; border:1px solid #c4b5fd;">
                <div class="d-flex gap-2 align-items-start">
                    <i class="bi bi-lightbulb-fill mt-1" style="color:#7c3aed; flex-shrink:0;"></i>
                    <div style="font-size:0.82rem; color:#5b21b6;">
                        <strong>Tips:</strong> Use a clear product name, set an accurate stock quantity, and add a product image URL for the best display in the inventory grid.
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live image preview when URL is entered
    const imageInput = document.getElementById('image_url');
    if (imageInput) {
        imageInput.addEventListener('blur', function () {
            const url = this.value.trim();
            const existing = document.getElementById('img-preview');
            if (existing) existing.remove();
            if (url) {
                const preview = document.createElement('div');
                preview.id = 'img-preview';
                preview.style.cssText = 'margin-top:0.6rem;';
                preview.innerHTML = `<img src="${url}" alt="Preview"
                    style="max-height:120px; border-radius:10px; border:1px solid #e5e7eb; object-fit:cover;"
                    onerror="this.parentElement.remove()">`;
                this.closest('.mb-4').appendChild(preview);
            }
        });
    }
</script>
@endpush
