@extends('layouts.shop')

@section('title', 'Compra exitosa')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>

                        <h1 class="text-success mb-3">¡Compra realizada con éxito!</h1>
                        <p class="lead text-muted mb-4">Gracias por tu compra. Hemos enviado un correo de confirmación a tu
                            email.</p>

                        <div class="alert alert-info mb-4">
                            <strong>ID de transacción:</strong> <code>{{ $transaction->transaction_id }}</code>
                        </div>

                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h4 class="mb-3">Detalles de la compra</h4>

                                <div class="row text-start">
                                    <div class="col-md-6">
                                        <p><strong>Fecha:</strong><br>{{ $transaction->paid_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Método de
                                                pago:</strong><br>{{ strtoupper($transaction->payment_method) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Estado:</strong><br><span
                                                class="badge bg-success">{{ $transaction->status }}</span></p>
                                        <p><strong>Total:</strong><br><span
                                                class="text-primary fs-4">{{ number_format($transaction->amount, 2, ',', '.') }}
                                                {{ $transaction->currency }}</span></p>
                                    </div>
                                </div>

                                <hr>

                                <h5 class="mb-3">Productos</h5>
                                @foreach($transaction->items as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>{{ $item->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">Cantidad: {{ $item->quantity }} x
                                                {{ number_format($item->unit_price, 2, ',', '.') }} €</small>
                                        </div>
                                        <div class="text-end">
                                            <strong>{{ number_format($item->subtotal, 2, ',', '.') }} €</strong>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-house-fill me-2"></i>Volver a la tienda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection