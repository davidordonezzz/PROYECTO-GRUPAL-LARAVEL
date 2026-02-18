<x-app-layout>
    <h3>Mi Perfil</h3>

    <div class="row mt-4">
        <div class="col-md-8">
            {{-- Actualizar datos --}}
            <div class="card mb-4">
                <div class="card-header">Información personal</div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Cambiar contraseña --}}
            <div class="card mb-4">
                <div class="card-header">Cambiar contraseña</div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Eliminar cuenta --}}
            <div class="card mb-4">
                <div class="card-header text-danger">Eliminar cuenta</div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
