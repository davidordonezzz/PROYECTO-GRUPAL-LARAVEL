@extends('layouts.shop')

@section('title', 'Pago completado')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <h1 class="text-success"><i class="bi bi-check-circle"></i></h1>
                        <h3>¡Pago completado!</h3>
                        <p class="text-muted">Tu compra se ha procesado correctamente.</p>

                        <table class="table text-start mt-3">
                            <tr><th>ID Transacción:</th><td>{{ $transaction->transaction_id }}</td></tr>
                            <tr><th>Importe:</th><td>{{ number_format($transaction->amount, 2, ',', '.') }} {{ $transaction->currency }}</td></tr>
                            <tr><th>Estado:</th><td><span class="badge bg-success">{{ $transaction->status }}</span></td></tr>
                            <tr><th>Fecha:</th><td>{{ $transaction->paid_at->format('d/m/Y H:i') }}</td></tr>
                            @if($transaction->payer_email)
                            <tr><th>Email PayPal:</th><td>{{ $transaction->payer_email }}</td></tr>
                            @endif
                        </table>

                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Seguir comprando</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
