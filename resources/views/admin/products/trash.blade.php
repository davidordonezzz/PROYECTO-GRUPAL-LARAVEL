@extends('layouts.admin')

@section('title', 'Papelera')

@section('actions')
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">Volver a productos</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Precio</th>
                        <th>Eliminado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>{{ number_format($product->price, 2, ',', '.') }} €</td>
                            <td>{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                </form>
                                <form action="{{ route('admin.products.force-delete', $product->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar PERMANENTEMENTE {{ $product->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar definitivo</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">La papelera está vacía</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="card-footer">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
