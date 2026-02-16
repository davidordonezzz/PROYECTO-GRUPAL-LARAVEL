<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\PayPalController;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ── Tienda pública ──────────────────────────────────────
Route::get('/tienda', [ShopController::class, 'index'])->name('home');
Route::get('/contacto', [ShopController::class, 'contact'])->name('shop.contact');
Route::get('/tienda/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/tienda/{product}/comprar', [ShopController::class, 'buy'])
    ->name('shop.buy')
    ->middleware('auth');

// ── PayPal ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/paypal/create', [PayPalController::class, 'create'])->name('paypal.create');
    Route::get('/paypal/success', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancel'])->name('paypal.cancel');
    Route::get('/payment/success/{transaction}', [PayPalController::class, 'showSuccess'])
        ->name('payment.success');
});

// ── Dashboard ───────────────────────────────────────────
Route::get('/dashboard', function () {
    if (optional(Auth::user())->is_admin) {
        return redirect()->route('admin.products.index');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// ── Perfil de usuario ───────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ── Panel Admin (solo administradores) ─────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('products/trash', [ProductController::class, 'trash'])
        ->name('products.trash');

    Route::patch('products/{product}/restore', [ProductController::class, 'restore'])
        ->name('products.restore');

    Route::delete('products/{product}/force-delete', [ProductController::class, 'forceDelete'])
        ->name('products.force-delete');

    Route::resource('products', ProductController::class);
});

// ── Test Telegram ───────────────────────────────────────
Route::get('/test-telegram', function (TelegramService $telegram) {
    $telegram->notifyAdmin("🔔 <b>Prueba de notificación</b>\n\n✅ El bot funciona correctamente!");
    return "Mensaje enviado a Telegram. Revisa tu chat.";
});
