<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Mail\PurchaseConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    // Crear orden de pago en PayPal
    public function create()
    {
        $purchase = session('purchase');

        if (!$purchase) {
            return redirect()->route('home')
                ->with('error', 'No hay productos seleccionados.');
        }

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            $order = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.success'),
                    "cancel_url" => route('paypal.cancel'),
                    "brand_name" => config('app.name'),
                    "shipping_preference" => "NO_SHIPPING"
                ],
                "purchase_units" => [
                    [
                        "amount" => [
                            "currency_code" => "EUR",
                            "value" => number_format($purchase['amount'], 2, '.', '')
                        ],
                        "description" => $purchase['product_name'] . ' (x' . $purchase['quantity'] . ')',
                    ]
                ]
            ]);

            if (isset($order['id']) && $order['id'] != null) {
                // Buscar el enlace de aprobación
                foreach ($order['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return redirect()->away($link['href']);
                    }
                }
            }

            return redirect()->route('shop.show', $purchase['product_id'])
                ->with('error', 'Error al crear el pago. Inténtalo de nuevo.');
        } catch (\Exception $e) {
            Log::error('PayPal Create Order Error: ' . $e->getMessage());
            return redirect()->route('shop.show', $purchase['product_id'])
                ->with('error', 'Error al conectar con PayPal. Por favor, inténtalo más tarde.');
        }
    }

    // Procesar pago exitoso
    public function success(Request $request)
    {
        $purchase = session('purchase');

        if (!$purchase) {
            return redirect()->route('home')
                ->with('error', 'Sesión expirada.');
        }

        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $token = $provider->getAccessToken();
            $provider->setAccessToken($token);

            // Capturar el pago
            $result = $provider->capturePaymentOrder($request->query('token'));

            if (isset($result['status']) && $result['status'] === 'COMPLETED') {

                DB::beginTransaction();

                try {
                    // Actualizar stock de forma atómica
                    $product = Product::lockForUpdate()->find($purchase['product_id']);

                    if (!$product || $product->stock < $purchase['quantity']) {
                        DB::rollBack();
                        session()->forget('purchase');
                        return redirect()->route('home')
                            ->with('error', 'Lo sentimos, el producto se agotó durante el proceso de pago.');
                    }

                    $product->decrement('stock', $purchase['quantity']);

                    // Extraer datos del pagador
                    $payerEmail = $result['payer']['email_address'] ?? null;

                    // Crear registro de transacción
                    $transaction = Transaction::create([
                        'user_id' => $purchase['user_id'],
                        'transaction_id' => $result['id'],
                        'payer_email' => $payerEmail,
                        'amount' => $purchase['amount'],
                        'currency' => 'EUR',
                        'status' => 'COMPLETED',
                        'payment_method' => 'paypal',
                        'paypal_response' => $result,
                        'paid_at' => now(),
                    ]);

                    // Crear item de la transacción
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $purchase['product_id'],
                        'quantity' => $purchase['quantity'],
                        'unit_price' => $purchase['unit_price'],
                        'subtotal' => $purchase['amount'],
                    ]);

                    DB::commit();

                    // Enviar email de confirmación
                    $user = Auth::user();
                    if ($user && $user->email) {
                        try {
                            Mail::to($user->email)->send(new PurchaseConfirmation($transaction, $product, $purchase));
                        } catch (\Exception $e) {
                            Log::error('Email Error: ' . $e->getMessage());
                        }
                    }

                    // Limpiar sesión
                    session()->forget('purchase');

                    // Redirigir a página de éxito
                    return redirect()->route('payment.success', $transaction->id)
                        ->with('success', '¡Compra realizada con éxito!');
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Transaction Error: ' . $e->getMessage());
                    throw $e;
                }
            }

            // Si el estado no es COMPLETED
            return redirect()->route('shop.show', $purchase['product_id'])
                ->with('error', 'El pago no se pudo completar. Estado: ' . ($result['status'] ?? 'desconocido'));
        } catch (\Exception $e) {
            Log::error('PayPal Success Error: ' . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Error al procesar el pago. Por favor, contacta con soporte.');
        }
    }

    // Pago cancelado
    public function cancel()
    {
        $purchase = session('purchase');

        session()->forget('purchase');

        $productId = $purchase['product_id'] ?? null;

        if ($productId) {
            return redirect()->route('shop.show', $productId)
                ->with('warning', 'Has cancelado el pago.');
        }

        return redirect()->route('home')
            ->with('warning', 'Has cancelado el pago.');
    }

    // Página de éxito
    public function showSuccess($transactionId)
    {
        $transaction = Transaction::with(['items.product', 'user'])
            ->findOrFail($transactionId);

        // Verificar que la transacción pertenece al usuario actual
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.success', compact('transaction'));
    }
}
