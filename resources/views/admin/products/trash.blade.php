@extends('layouts.admin')

@section('title', 'Papelera de Productos')

@section('actions')
<a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Volver al listado
</a>
@endsection

@section('content')
<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    Los productos en la papelera pueden ser restaurados o eliminados permanentemente.
    <strong>El borrado permanente no se puede deshacer.</strong>
</div>

<!-- Tabla de productos eliminados -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-trash me-2"></i>Productos eliminados</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">Imagen</th>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Eliminado el</th>
                        <th style="width: 200px;">Acciones</th>
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
                            <span class="fw-medium">{{ $product->name }}</span>
                        </td>
                        <td><code>{{ $product->sku }}</code></td>
                        <td>{{ $product->brand->name ?? '-' }}</td>
                        <td>{{ number_format($product->price, 2, ',', '.') }} €</td>
                        <td>
                            <span class="text-muted">
                                {{ $product->deleted_at->format('d/m/Y H:i') }}
                            </span>
                            <br>
                            <small class="text-muted">{{ $product->deleted_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <!-- Restaurar -->
                                <form action="{{ route('admin.products.restore', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success" title="Restaurar">
                                        <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                    </button>
                                </form>
                                
                                <!-- Eliminar permanentemente -->
                                <button type="button" class="btn btn-outline-danger" 
                                        title="Eliminar permanentemente" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#forceDeleteModal{{ $product->id }}">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                            
                            <!-- Modal de confirmación de eliminación permanente -->
                            <div class="modal fade" id="forceDeleteModal{{ $product->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle me-2"></i>Eliminar permanentemente
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro de que quieres eliminar <strong>permanentemente</strong> el producto <strong>{{ $product->name }}</strong>?</p>
                                            <div class="alert alert-danger">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <strong>¡Atención!</strong> Esta acción NO se puede deshacer.
                                                @if($product->cloudinary_public_id)
                                                    <br>La imagen también será eliminada de Cloudinary.
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('admin.products.forceDelete', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash"></i> Eliminar permanentemente
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
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-trash fs-1 d-block mb-3"></i>
                                <p class="mb-0">La papelera está vacía</p>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-link">
                                    Volver al listado de productos
                                </a>
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
                {{ $products->total() }} producto(s) en la papelera
            </div>
            {{ $products->links() }}
        </div>
    </div>
    @endif
</div>
@endsection
