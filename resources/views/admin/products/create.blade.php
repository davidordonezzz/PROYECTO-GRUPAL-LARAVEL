@extends('layouts.admin')

@section('title', 'Nuevo Producto')

@section('actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    @include('admin.products._form')
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Crear producto</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@endsection
