<x-guest-layout>
    <h5 class="text-center mb-3">Verificar email</h5>
    <p class="text-muted small">Haz clic en el enlace que te hemos enviado por email. Si no lo has recibido, te enviamos otro.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">Se ha enviado un nuevo enlace de verificación.</div>
    @endif

    <div class="d-flex justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Reenviar email</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Cerrar sesión</button>
        </form>
    </div>
</x-guest-layout>
