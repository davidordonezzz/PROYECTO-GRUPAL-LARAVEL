<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="current_password" class="form-label">Contraseña actual</label>
        <input type="password" id="current_password" name="current_password" class="form-control">
        @if($errors->updatePassword->has('current_password'))
            <small class="text-danger">{{ $errors->updatePassword->first('current_password') }}</small>
        @endif
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Nueva contraseña</label>
        <input type="password" id="password" name="password" class="form-control">
        @if($errors->updatePassword->has('password'))
            <small class="text-danger">{{ $errors->updatePassword->first('password') }}</small>
        @endif
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Guardar</button>
    @if (session('status') === 'password-updated')
        <span class="text-success ms-2">Guardado.</span>
    @endif
</form>
