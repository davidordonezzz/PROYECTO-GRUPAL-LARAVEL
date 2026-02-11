<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }

        .order-details {
            background-color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .product-info {
            margin: 15px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
        }

        .total {
            font-size: 1.3em;
            font-weight: bold;
            color: #0d6efd;
            margin-top: 15px;
            text-align: right;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 0.9em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px 0;
        }

        .label {
            font-weight: bold;
            color: #495057;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>¡Gracias por tu compra!</h1>
    </div>

    <div class="content">
        <p>Hola <strong>{{ $transaction->user->name ?? 'Cliente' }}</strong>,</p>

        <p>Tu compra se ha procesado correctamente. A continuación encontrarás los detalles de tu pedido:</p>

        <div class="order-details">
            <h2 style="margin-top: 0; color: #0d6efd;">Detalles del pedido</h2>

            <table>
                <tr>
                    <td class="label">ID de transacción:</td>
                    <td><code>{{ $transaction->transaction_id }}</code></td>
                </tr>
                <tr>
                    <td class="label">Fecha de compra:</td>
                    <td>{{ $transaction->paid_at->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Método de pago:</td>
                    <td>{{ strtoupper($transaction->payment_method) }}</td>
                </tr>
                <tr>
                    <td class="label">Estado:</td>
                    <td><span style="color: #198754; font-weight: bold;">{{ $transaction->status }}</span></td>
                </tr>
            </table>
        </div>

        <div class="product-info">
            <h3 style="margin-top: 0;">Producto comprado</h3>
            <table>
                <tr>
                    <td class="label">Producto:</td>
                    <td>{{ $product->name }}</td>
                </tr>
                @if($product->brand)
                    <tr>
                        <td class="label">Marca:</td>
                        <td>{{ $product->brand->name }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="label">SKU:</td>
                    <td>{{ $product->sku }}</td>
                </tr>
                <tr>
                    <td class="label">Cantidad:</td>
                    <td>{{ $purchase['quantity'] }}</td>
                </tr>
                <tr>
                    <td class="label">Precio unitario:</td>
                    <td>{{ number_format($purchase['unit_price'], 2, ',', '.') }} €</td>
                </tr>
            </table>

            <div class="total">
                Total pagado: {{ number_format($transaction->amount, 2, ',', '.') }} {{ $transaction->currency }}
            </div>
        </div>

        <p>Si tienes alguna pregunta sobre tu pedido, no dudes en contactarnos.</p>

        <p>¡Gracias por confiar en nosotros!</p>
    </div>

    <div class="footer">
        <p>Este es un correo automático, por favor no respondas a este mensaje.</p>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
    </div>
</body>

</html>