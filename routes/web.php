<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PaymentController;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ── Tienda pública ──────────────────────────────────────
Route::get('/tienda', [ShopController::class, 'index'])->name('home');
Route::get('/tienda/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/tienda/{product}/comprar', [ShopController::class, 'buy'])->name('shop.buy')->middleware('auth');

// ── Pagos (PayPal - Hugo) ───────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/payment/create', [PaymentController::class, 'createPayment'])->name('payment.create');
    Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
});

// Dashboard → redirige según rol
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.products.index');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Panel Admin (solo administradores) ───────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Papelera de productos (antes del resource para que no colisione)
    Route::get('products/trash', [ProductController::class, 'trash'])
        ->name('products.trash');

    Route::patch('products/{product}/restore', [ProductController::class, 'restore'])
        ->name('products.restore');

    Route::delete('products/{product}/force-delete', [ProductController::class, 'forceDelete'])
        ->name('products.force-delete');

    // CRUD principal de productos
    Route::resource('products', ProductController::class);
});

Route::get('/test-telegram', function(TelegramService $telegram) {
    $telegram->notifyAdmin("🔔 <b>Prueba de notificación</b>\n\n✅ El bot funciona correctamente!");
    return "Mensaje enviado a Telegram. Revisa tu chat.";
});
