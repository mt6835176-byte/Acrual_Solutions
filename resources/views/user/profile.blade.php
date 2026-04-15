@extends('layouts.app')

@section('title', 'My Profile')

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
        width: 72px;
        height: 72px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 800;
        color: #fff;
        margin: 0 auto 0.75rem;
        border: 3px solid rgba(255,255,255,0.2);
    }

    .account-sidebar-name {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }

    .account-sidebar-role {
        font-size: 0.75rem;
        color: #94a3b8;
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 2px 10px;
        display: inline-block;
    }

    .account-nav { padding: 0.5rem; }

    .account-nav-link {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.65rem 0.9rem;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 500;
        color: #4b5563;
        text-decoration: none;
        transition: background 0.15s, color 0.15s;
        margin-bottom: 0.15rem;
    }

    .account-nav-link:hover { background: #f3f4f6; color: #6366f1; }
    .account-nav-link.active { background: #ede9fe; color: #6366f1; font-weight: 600; }
    .account-nav-link i { font-size: 1rem; width: 18px; text-align: center; }

    .account-nav-divider { border-top: 1px solid #f3f4f6; margin: 0.4rem 0.5rem; }

    .profile-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
    }

    .profile-card-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
        padding: 1.25rem 1.5rem;
        color: #fff;
    }

    .profile-card-header h5 { font-weight: 700; margin: 0; font-size: 1rem; }
    .profile-card-header p  { font-size: 0.78rem; color: #94a3b8; margin: 0.2rem 0 0; }

    .info-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: #ede9fe;
        color: #6366f1;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
</style>
@endpush

@section('content')

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" style="color:#818cf8;">Home</a></li>
                <li class="breadcrumb-item active" style="color:#94a3b8;">My Profile</li>
            </ol>
        </nav>
        <h1><i class="bi bi-person-circle me-2" style="color:#818cf8;"></i>My Account</h1>
        <p>Manage your profile and account settings</p>
    </div>
</div>

<div class="container py-4">
    <div class="row g-4 align-items-start">

        {{-- ── Sidebar ── --}}
        <div class="col-lg-3 col-md-4">
            <div class="account-sidebar">
                <div class="account-sidebar-header">
                    <div class="account-avatar-lg">{{ $user['initials'] ?? 'U' }}</div>
                    <div class="account-sidebar-name">{{ $user['name'] }}</div>
                    <div class="account-sidebar-role">{{ $user['role'] ?? 'Customer' }}</div>
                </div>
                <nav class="account-nav">
                    <a href="{{ route('user.profile') }}" class="account-nav-link active">
                        <i class="bi bi-person-fill"></i>Profile
                    </a>
                    <a href="{{ route('user.orders') }}" class="account-nav-link">
                        <i class="bi bi-bag-check"></i>My Orders
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

        {{-- ── Profile form ── --}}
        <div class="col-lg-9 col-md-8">

            {{-- Account info card (read-only summary) --}}
            <div class="profile-card mb-4">
                <div class="profile-card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5><i class="bi bi-person-badge me-2"></i>Account Overview</h5>
                            <p>Your current account information</p>
                        </div>
                        <span class="info-badge"><i class="bi bi-shield-check"></i>{{ $user['role'] ?? 'Customer' }}</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div style="font-size:0.72rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.3rem;">Full Name</div>
                            <div style="font-size:0.95rem; font-weight:600; color:#111827;">{{ $user['name'] }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div style="font-size:0.72rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.3rem;">Email</div>
                            <div style="font-size:0.95rem; font-weight:600; color:#111827;">{{ $user['email'] }}</div>
                        </div>
                        @if(!empty($user['phone']))
                        <div class="col-sm-6">
                            <div style="font-size:0.72rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.3rem;">Phone</div>
                            <div style="font-size:0.95rem; font-weight:600; color:#111827;">{{ $user['phone'] }}</div>
                        </div>
                        @endif
                        @if(!empty($user['bio']))
                        <div class="col-12">
                            <div style="font-size:0.72rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.3rem;">Bio</div>
                            <div style="font-size:0.88rem; color:#374151; line-height:1.6;">{{ $user['bio'] }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Edit form --}}
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5><i class="bi bi-pencil-square me-2"></i>Edit Profile</h5>
                    <p>Update your personal information</p>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('user.profile.update') }}" novalidate>
                        @csrf

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user['name']) }}"
                                           placeholder="Jane Smith">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user['email']) }}"
                                           placeholder="you@example.com">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label">Phone <span class="text-muted fw-normal" style="font-size:0.78rem;">(optional)</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $user['phone'] ?? '') }}"
                                           placeholder="+1 555 000 0000">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label class="form-label">Role</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield"></i></span>
                                    <input type="text" class="form-control" value="{{ $user['role'] ?? 'Customer' }}" disabled style="background:#f9fafb; color:#9ca3af;">
                                </div>
                                <div class="form-text">Role is managed by the system.</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Bio <span class="text-muted fw-normal" style="font-size:0.78rem;">(optional)</span></label>
                                <textarea name="bio"
                                          class="form-control @error('bio') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Tell us a little about yourself..."
                                          maxlength="500">{{ old('bio', $user['bio'] ?? '') }}</textarea>
                                @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text">Max 500 characters.</div>
                            </div>
                        </div>

                        <hr style="border-color:#f3f4f6; margin:1.5rem 0;">

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-submit">
                                <i class="bi bi-check-circle me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="border-radius:10px; font-weight:600; font-size:0.9rem;">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
