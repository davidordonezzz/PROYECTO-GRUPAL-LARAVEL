<x-app-layout>
    <h3>Dashboard</h3>
    <p>¡Bienvenido, {{ Auth::user()->name }}!</p>

    <div class="row mt-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Tienda</h5>
                    <a href="{{ route('home') }}" class="btn btn-primary">Ir a la tienda</a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Mi Perfil</h5>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Editar perfil</a>
                </div>
            </div>
        </div>
        @if(Auth::user()->isAdmin())
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Administración</h5>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Panel admin</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
