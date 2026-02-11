<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentConfirmation;

class PaymentController extends Controller
{
    /**
     * Crear orden de pago en PayPal
     */
    public function createPayment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        // Datos del pago
        $amount = $request->amount ?? 100.00; // Puedes obtenerlo del carrito

        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('payment.success'),
                "cancel_url" => route('payment.cancel'),
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => $amount
                    ],
                    "description" => "Compra en Mi Tienda"
                ]
            ]
        ]);

        if (isset($order['id']) && $order['id'] != null) {
            // Redirigir al usuario a PayPal
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
        }

        return redirect()->route('payment.cancel')
            ->with('error', 'Error al crear la orden de pago');
    }

    /**
     * Capturar pago exitoso
     */
    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $orderId = $request->query('token');
        $response = $provider->capturePaymentOrder($orderId);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // Guardar transacción en la base de datos
            $transaction = Transaction::create([
                'user_id' => 1, //PENDIENTE
                'transaction_id' => $response['id'],
                'payer_email' => $response['payer']['email_address'] ?? null,
                'amount' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
                'currency' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
                'status' => 'COMPLETED',
                'paypal_response' => $response,
                'paid_at' => now(),
            ]);

            // Enviar email de confirmación
           // Mail::to(auth()->user()->email)->send(new PaymentConfirmation($transaction)); PENDIENTE

            return view('payment.success', compact('transaction'));
        }

        return redirect()->route('payment.cancel')
            ->with('error', 'El pago no se pudo completar');
    }

    /**
     * Pago cancelado
     */
    public function paymentCancel()
    {
        return view('payment.cancel');
    }
}
