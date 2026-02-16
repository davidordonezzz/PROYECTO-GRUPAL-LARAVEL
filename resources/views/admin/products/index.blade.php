@extends('layouts.admin')

@section('title', 'Productos')

@section('actions')
    <a href="{{ route('admin.products.trash') }}" class="btn btn-outline-secondary btn-sm">Papelera</a>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Nuevo producto</a>
@endsection

@section('content')
    {{-- Filtros --}}
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" placeholder="Buscar..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="brand_id">
                        <option value="">Todas las marcas</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category_id">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="" style="width:45px; height:45px; object-fit:cover;" class="rounded">
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><a href="{{ route('admin.products.show', $product) }}">{{ $product->name }}</a></td>
                            <td><code>{{ $product->sku }}</code></td>
                            <td>{{ $product->brand->name ?? '-' }}</td>
                            <td>{{ number_format($product->price, 2, ',', '.') }} €</td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">0</span>
                                @endif
                            </td>
                            <td>
                                @if($product->active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info">Ver</a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar {{ $product->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No hay productos</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="card-footer">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection
