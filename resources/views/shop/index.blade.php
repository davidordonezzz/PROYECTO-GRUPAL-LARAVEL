@extends('layouts.shop')

@section('title', 'Tienda')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Tienda</h2>

        {{-- Filtros --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('home') }}" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Buscar..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="brand_id" class="form-select">
                            <option value="">Todas las marcas</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Productos --}}
        @if($products->count())
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card h-100">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                     style="height: 200px;">
                                    <i class="bi bi-image text-white" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                @if($product->brand)
                                    <small class="text-muted">{{ $product->brand->name }}</small>
                                @endif
                                <h6>{{ $product->name }}</h6>
                                <p class="fw-bold text-primary mb-1">{{ number_format($product->price, 2, ',', '.') }} €</p>
                                <small>
                                    @if($product->stock > 0)
                                        <span class="text-success">En stock ({{ $product->stock }})</span>
                                    @else
                                        <span class="text-danger">Agotado</span>
                                    @endif
                                </small>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('shop.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">
                                    Ver producto
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $products->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <h5 class="text-muted">No se encontraron productos</h5>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">Ver todos</a>
            </div>
        @endif
    </div>
@endsection
