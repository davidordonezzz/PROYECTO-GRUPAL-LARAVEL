<x-guest-layout>
    <h5 class="text-center mb-3">Recuperar contraseña</h5>
    <p class="text-muted small">Introduce tu email y te enviaremos un enlace para restablecer tu contraseña.</p>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
        </div>
        <button type="submit" class="btn btn-primary w-100">Enviar enlace</button>
    </form>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}">Volver al login</a>
    </div>
</x-guest-layout>
