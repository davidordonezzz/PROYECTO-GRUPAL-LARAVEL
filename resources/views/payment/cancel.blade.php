@extends('layouts.shop')

@section('title', 'Pago cancelado')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body p-4">
                        <h1 class="text-danger"><i class="bi bi-x-circle"></i></h1>
                        <h3>Pago cancelado</h3>
                        <p class="text-muted">No se ha realizado ningún cargo.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Volver a la tienda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
