@extends('layouts.shop')

@section('title', $product->name)

@section('content')
    <div class="container py-4">
        {{-- Breadcrumb --}}
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Tienda</a></li>
                <li class="breadcrumb-item active">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            {{-- Imagen --}}
            <div class="col-md-6 mb-4">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid rounded">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                        <i class="bi bi-image text-white" style="font-size: 4rem;"></i>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="col-md-6">
                @if($product->brand)
                    <span class="badge bg-dark">{{ $product->brand->name }}</span>
                @endif

                <h2>{{ $product->name }}</h2>
                <p class="text-muted">SKU: {{ $product->sku }}</p>

                {{-- Categorías --}}
                @foreach($product->categories as $cat)
                    <span class="badge bg-secondary">{{ $cat->name }}</span>
                @endforeach

                <h3 class="text-primary mt-3">{{ number_format($product->price, 2, ',', '.') }} €</h3>

                {{-- Stock --}}
                <p class="mt-2">
                    @if($product->stock > 0)
                        <span class="text-success">En stock ({{ $product->stock }} disponibles)</span>
                    @else
                        <span class="text-danger">Agotado</span>
                    @endif
                </p>

                {{-- Descripción --}}
                @if($product->description)
                    <h5>Descripción</h5>
                    <p>{{ $product->description }}</p>
                @endif

                {{-- Comprar --}}
                @if($product->stock > 0)
                    <form action="{{ route('shop.buy', $product) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-auto">
                                <label for="quantity" class="form-label">Cantidad</label>
                                <input type="number" name="quantity" id="quantity" class="form-control"
                                       value="1" min="1" max="{{ $product->stock }}" style="width: 80px;">
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Comprar</button>
                            </div>
                        </div>

                        @guest
                            <div class="alert alert-info mt-3">
                                <a href="{{ route('login') }}">Inicia sesión</a> o
                                <a href="{{ route('register') }}">regístrate</a> para comprar.
                            </div>
                        @endguest
                    </form>
                @endif
            </div>
        </div>

        {{-- Productos relacionados --}}
        @if($related->count())
            <hr class="my-4">
            <h4>Productos relacionados</h4>
            <div class="row">
                @foreach($related as $rel)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            @if($rel->image_url)
                                <img src="{{ $rel->image_url }}" class="card-img-top" alt="{{ $rel->name }}"
                                     style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h6>{{ $rel->name }}</h6>
                                <p class="fw-bold text-primary">{{ number_format($rel->price, 2, ',', '.') }} €</p>
                                <a href="{{ route('shop.show', $rel) }}" class="btn btn-outline-primary btn-sm">Ver</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
