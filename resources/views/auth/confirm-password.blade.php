<x-guest-layout>
    <h5 class="text-center mb-3">Confirmar contraseña</h5>
    <p class="text-muted small">Confirma tu contraseña antes de continuar.</p>

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Confirmar</button>
    </form>
</x-guest-layout>
