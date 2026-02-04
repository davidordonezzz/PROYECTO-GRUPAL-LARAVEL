@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Tarjeta Productos -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Productos</h6>
                        <h3 class="mb-0">{{ \App\Models\Product::count() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-box-seam fs-3 text-primary"></i>
                    </div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none small">
                    Ver todos <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Tarjeta Marcas -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Marcas</h6>
                        <h3 class="mb-0">{{ \App\Models\Brand::count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-tag fs-3 text-success"></i>
                    </div>
                </div>
                <span class="text-muted small">Marcas registradas</span>
            </div>
        </div>
    </div>

    <!-- Tarjeta Categorías -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Categorías</h6>
                        <h3 class="mb-0">{{ \App\Models\Category::count() }}</h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-folder fs-3 text-warning"></i>
                    </div>
                </div>
                <span class="text-muted small">Categorías activas</span>
            </div>
        </div>
    </div>

    <!-- Tarjeta Usuarios -->
    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Usuarios</h6>
                        <h3 class="mb-0">{{ \App\Models\User::count() }}</h3>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people fs-3 text-info"></i>
                    </div>
                </div>
                <span class="text-muted small">Usuarios registrados</span>
            </div>
        </div>
    </div>
</div>

<!-- Últimos productos -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Últimos productos añadidos</h5>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Nuevo producto
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Product::with('brand')->latest()->take(5)->get() as $product)
                    <tr>
                        <td>
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                            @else
                                <div class="product-image bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-white"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td>{{ $product->brand->name ?? 'Sin marca' }}</td>
                        <td>{{ number_format($product->price, 2, ',', '.') }} €</td>
                        <td>
                            @if($product->stock > 10)
                                <span class="badge badge-stock-ok">{{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="badge badge-stock-low">{{ $product->stock }}</span>
                            @else
                                <span class="badge badge-stock-out">Sin stock</span>
                            @endif
                        </td>
                        <td>{{ $product->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            No hay productos registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
