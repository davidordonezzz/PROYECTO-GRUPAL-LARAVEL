<div class="col-md-8">
    <label for="name" class="form-label">Nombre *</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-4">
    <label for="sku" class="form-label">SKU *</label>
    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku ?? '') }}" required>
    @error('sku') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-12">
    <label for="description" class="form-label">Descripción</label>
    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-3">
    <label for="price" class="form-label">Precio (€) *</label>
    <input type="number" class="form-control" id="price" name="price" step="0.01" min="0.01"
           value="{{ old('price', $product->price ?? '') }}" required>
    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-3">
    <label for="stock" class="form-label">Stock *</label>
    <input type="number" class="form-control" id="stock" name="stock" min="0"
           value="{{ old('stock', $product->stock ?? 0) }}" required>
    @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-3">
    <label for="brand_id" class="form-label">Marca *</label>
    <select class="form-select" id="brand_id" name="brand_id" required>
        <option value="">Seleccionar...</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
    @error('brand_id') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-3">
    <label for="active" class="form-label">Estado</label>
    <select class="form-select" id="active" name="active">
        <option value="1" {{ old('active', $product->active ?? true) ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('active', $product->active ?? true) ? '' : 'selected' }}>Inactivo</option>
    </select>
</div>

<div class="col-md-6">
    <label for="categories" class="form-label">Categorías *</label>
    @php
        $selectedCategories = old('categories', isset($product) ? $product->categories->pluck('id')->toArray() : []);
    @endphp
    <select class="form-select" id="categories" name="categories[]" multiple size="4" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Mantén Ctrl para seleccionar varias.</small>
    @error('categories') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-6">
    <label for="tags" class="form-label">Etiquetas</label>
    @php
        $selectedTags = old('tags', isset($product) ? $product->tags->pluck('id')->toArray() : []);
    @endphp
    <select class="form-select" id="tags" name="tags[]" multiple size="4">
        @foreach($tags as $tag)
            <option value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
    <small class="text-muted">Mantén Ctrl para seleccionar varias.</small>
    @error('tags') <small class="text-danger">{{ $message }}</small> @enderror
</div>

<div class="col-md-6">
    <label for="image" class="form-label">Imagen</label>
    <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    <small class="text-muted">JPG, PNG o WebP. Máx. 2 MB.</small>
    @error('image') <small class="text-danger">{{ $message }}</small> @enderror

    @if(isset($product) && $product->image_url)
        <div class="mt-2">
            <small class="text-muted">Imagen actual:</small><br>
            <img src="{{ $product->image_url }}" alt="" style="max-height: 100px;" class="rounded mt-1">
        </div>
    @endif
</div>
