<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TecnoOutlet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <h1><i class="bi bi-cpu"></i></h1>
                        <h2>TecnoOutlet</h2>
                        <p class="text-muted">Tienda de informática</p>
                        <hr>
                        <div class="d-grid gap-2">
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-shop"></i> Ver tienda
                            </a>

                            @auth
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-gear"></i> Panel Admin
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100">
                                        Cerrar sesión
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary">Iniciar sesión</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary">Registrarse</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
