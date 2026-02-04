@extends('layouts.admin')

@section('title', 'Detalle del Producto')

@section('actions')
<div class="btn-group">
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
        <i class="bi bi-pencil"></i> Editar
    </a>
</div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Columna izquierda: Imagen -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                         class="img-fluid rounded mb-3" style="max-height: 300px;">
                    <p class="text-muted small mb-0">
                        <i class="bi bi-cloud"></i> Almacenada en Cloudinary
                    </p>
                @else
                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center mb-3" 
                         style="height: 200px;">
                        <i class="bi bi-image text-white display-1"></i>
                    </div>
                    <p class="text-muted small mb-0">Sin imagen</p>
                @endif
            </div>
        </div>
        
        <!-- Estado del producto -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Estado</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Publicado:</span>
                    @if($product->active)
                        <span class="badge bg-success">Activo</span>
                    @else
                        <span class="badge bg-secondary">Inactivo</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Stock:</span>
                    @if($product->stock > 10)
                        <span class="badge badge-stock-ok">{{ $product->stock }} unidades</span>
                    @elseif($product->stock > 0)
                        <span class="badge badge-stock-low">{{ $product->stock }} unidades</span>
                    @else
                        <span class="badge badge-stock-out">Sin stock</span>
                    @endif
                </div>
                <hr>
                <div class="small text-muted">
                    <div><i class="bi bi-calendar-plus"></i> Creado: {{ $product->created_at->format('d/m/Y H:i') }}</div>
                    <div><i class="bi bi-calendar-check"></i> Actualizado: {{ $product->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Columna derecha: Información -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ $product->name }}</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- SKU -->
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Código SKU</label>
                        <div><code class="fs-5">{{ $product->sku }}</code></div>
                    </div>
                    
                    <!-- Precio -->
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Precio</label>
                        <div class="fs-4 fw-bold text-primary">{{ number_format($product->price, 2, ',', '.') }} €</div>
                    </div>
                    
                    <!-- Marca -->
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Marca</label>
                        <div>
                            @if($product->brand)
                                <span class="badge bg-dark fs-6">{{ $product->brand->name }}</span>
                            @else
                                <span class="text-muted">Sin marca asignada</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Categorías -->
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Categorías</label>
                        <div>
                            @forelse($product->categories as $category)
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            @empty
                                <span class="text-muted">Sin categorías</span>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Descripción -->
                    <div class="col-12">
                        <label class="form-label text-muted small">Descripción</label>
                        <div class="border rounded p-3 bg-light">
                            @if($product->description)
                                {!! nl2br(e($product->description)) !!}
                            @else
                                <span class="text-muted fst-italic">Sin descripción</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Información técnica (Cloudinary) -->
        @if($product->cloudinary_public_id)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-cloud me-2"></i>Información de Cloudinary</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-muted small">Public ID</label>
                        <div><code>{{ $product->cloudinary_public_id }}</code></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small">URL de la imagen</label>
                        <div>
                            <a href="{{ $product->image_url }}" target="_blank" class="text-break small">
                                {{ Str::limit($product->image_url, 50) }} <i class="bi bi-box-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Acciones -->
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Editar producto
                    </a>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1">
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
@endsection
