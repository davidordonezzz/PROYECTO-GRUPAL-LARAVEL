@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('actions')
<a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-left"></i> Volver al listado
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-pencil me-2"></i>Editar Producto</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <!-- Nombre -->
                        <div class="col-md-8">
                            <label for="name" class="form-label">Nombre del producto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- SKU -->
                        <div class="col-md-4">
                            <label for="sku" class="form-label">Código SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Descripción -->
                        <div class="col-12">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Precio -->
                        <div class="col-md-4">
                            <label for="price" class="form-label">Precio (€) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                <span class="input-group-text">€</span>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Stock -->
                        <div class="col-md-4">
                            <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                            <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Marca -->
                        <div class="col-md-4">
                            <label for="brand_id" class="form-label">Marca <span class="text-danger">*</span></label>
                            <select class="form-select @error('brand_id') is-invalid @enderror" 
                                    id="brand_id" name="brand_id" required>
                                <option value="">Selecciona una marca</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('brand_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Categorías -->
                        <div class="col-md-8">
                            <label class="form-label">Categorías <span class="text-danger">*</span></label>
                            @php
                                $selectedCategories = old('categories', $product->categories->pluck('id')->toArray());
                            @endphp
                            <div class="border rounded p-3 @error('categories') border-danger @enderror" style="max-height: 200px; overflow-y: auto;">
                                @foreach($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="categories[]" value="{{ $category->id }}" 
                                               id="cat{{ $category->id }}"
                                               {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cat{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('categories')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Activo -->
                        <div class="col-md-4">
                            <label class="form-label d-block">Estado</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="active" name="active" value="1" 
                                       {{ old('active', $product->active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">Producto activo</label>
                            </div>
                        </div>
                        
                        <!-- Imagen actual -->
                        @if($product->image_url)
                        <div class="col-12">
                            <label class="form-label">Imagen actual</label>
                            <div class="d-flex align-items-start gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="img-thumbnail" style="max-height: 150px;">
                                <div class="text-muted small">
                                    <p class="mb-1">La imagen actual se reemplazará si subes una nueva.</p>
                                    <p class="mb-0">ID Cloudinary: <code>{{ $product->cloudinary_public_id ?? 'N/A' }}</code></p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Nueva imagen -->
                        <div class="col-12">
                            <label for="image" class="form-label">{{ $product->image_url ? 'Cambiar imagen' : 'Imagen del producto' }}</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Formatos permitidos: JPEG, PNG, JPG, GIF, WEBP. Máximo 2MB.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Preview de imagen -->
                            <div id="imagePreview" class="mt-3 d-none">
                                <p class="text-muted small mb-2">Nueva imagen:</p>
                                <img src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Botones -->
                    <div class="d-flex justify-content-between">
                        <div class="text-muted small">
                            <i class="bi bi-clock"></i> Última actualización: {{ $product->updated_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-lg"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Guardar cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Preview de imagen antes de subir
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('d-none');
        }
    });
</script>
@endpush
