<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductWebController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ── Home ──────────────────────────────────────────────────────────
Route::get('/', function () {
    $service  = app(\App\Services\ProductService::class);
    $products = $service->getAll();

    $stats = [
        'total'       => count($products),
        'in_stock'    => collect($products)->where('quantity', '>', 0)->count(),
        'out_stock'   => collect($products)->where('quantity', '<=', 0)->count(),
        'total_value' => collect($products)->sum(fn($p) => (float)$p['price'] * (int)$p['quantity']),
    ];

    $latest = collect($products)->sortByDesc('created_at')->take(4)->values()->all();

    return view('welcome', compact('stats', 'latest'));
})->name('home');

// ── Auth 
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login'])->name('login.submit');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');

// ── Products
Route::get('/products',        [ProductWebController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductWebController::class, 'create'])->name('products.create');
Route::post('/products',       [ProductWebController::class, 'store'])->name('products.store');

// ── Cart 
Route::get('/cart',                  [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}',        [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}',    [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}',   [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear',         [CartController::class, 'clear'])->name('cart.clear');

// ── Order 
Route::get('/order/summary',  [OrderController::class, 'summary'])->name('order.summary');
Route::post('/order/place',   [OrderController::class, 'place'])->name('order.place');

// ── Payment 
Route::get('/payment',         [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/process',[PaymentController::class, 'process'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');

// ── User account 
Route::middleware('auth.session')->group(function () {
    Route::get('/account/profile',  [UserController::class, 'profile'])->name('user.profile');
    Route::post('/account/profile', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::get('/account/orders',   [UserController::class, 'orders'])->name('user.orders');
});
