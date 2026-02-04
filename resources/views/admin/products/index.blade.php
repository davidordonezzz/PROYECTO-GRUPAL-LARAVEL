@extends('layouts.admin')

@section('title', 'Gestión de Productos')

@section('actions')
<div class="btn-group">
    <a href="{{ route('admin.products.trash') }}" class="btn btn-outline-secondary">
        <i class="bi bi-trash"></i> Papelera
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo producto
    </a>
</div>
@endsection

@section('content')
<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3">
            <!-- Búsqueda -->
            <div class="col-md-4">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Nombre o SKU...">
            </div>
            
            <!-- Filtro por marca -->
            <div class="col-md-3">
                <label for="brand_id" class="form-label">Marca</label>
                <select class="form-select" id="brand_id" name="brand_id">
                    <option value="">Todas las marcas</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtro por categoría -->
            <div class="col-md-3">
                <label for="category_id" class="form-label">Categoría</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Botones -->
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtrar
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de productos -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">Imagen</th>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Marca</th>
                        <th>Categorías</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th style="width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
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
                            <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none fw-medium">
                                {{ $product->name }}
                            </a>
                        </td>
                        <td><code>{{ $product->sku }}</code></td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>
                            @foreach($product->categories as $category)
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            @endforeach
                        </td>
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
                        <td>
                            @if($product->active)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.products.show', $product) }}" 
                                   class="btn btn-outline-info" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="btn btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        title="Eliminar" data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal{{ $product->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Modal de confirmación de eliminación -->
                            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmar eliminación</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que quieres eliminar el producto <strong>{{ $product->name }}</strong>?</p>
                                            <p class="text-muted small">El producto se moverá a la papelera y podrás restaurarlo más tarde.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <p class="mb-0">No se encontraron productos</p>
                                @if(request()->hasAny(['search', 'brand_id', 'category_id']))
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-link">
                                        Limpiar filtros
                                    </a>
                                @else
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3">
                                        <i class="bi bi-plus-lg"></i> Crear primer producto
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Paginación -->
    @if($products->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Mostrando {{ $products->firstItem() }} - {{ $products->lastItem() }} de {{ $products->total() }} productos
            </div>
            {{ $products->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
