@extends('layouts.admin')

@section('title', $product->name)

@section('actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">Editar</a>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    @else
                        <p class="text-muted">Sin imagen</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr><th>SKU</th><td><code>{{ $product->sku }}</code></td></tr>
                        <tr><th>Marca</th><td>{{ $product->brand->name ?? '-' }}</td></tr>
                        <tr><th>Precio</th><td class="text-success fw-bold">{{ number_format($product->price, 2, ',', '.') }} €</td></tr>
                        <tr><th>Stock</th><td>{{ $product->stock }}</td></tr>
                        <tr><th>Estado</th><td>{{ $product->active ? 'Activo' : 'Inactivo' }}</td></tr>
                        <tr>
                            <th>Categorías</th>
                            <td>
                                @foreach($product->categories as $cat)
                                    <span class="badge bg-secondary">{{ $cat->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Etiquetas</th>
                            <td>
                                @forelse($product->tags as $tag)
                                    <span class="badge" style="background-color: {{ $tag->color }};">{{ $tag->name }}</span>
                                @empty
                                    <span class="text-muted">Sin etiquetas</span>
                                @endforelse
                            </td>
                        </tr>
                        @if($product->description)
                        <tr><th>Descripción</th><td>{{ $product->description }}</td></tr>
                        @endif
                        <tr><th>Creado</th><td>{{ $product->created_at->format('d/m/Y H:i') }}</td></tr>
                        <tr><th>Actualizado</th><td>{{ $product->updated_at->format('d/m/Y H:i') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
