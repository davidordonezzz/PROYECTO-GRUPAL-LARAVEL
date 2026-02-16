<p class="text-muted">Una vez eliminada tu cuenta, todos tus datos se borrarán permanentemente.</p>

<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
    Eliminar cuenta
</button>

<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-header">
                    <h5 class="modal-title">¿Eliminar cuenta?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Introduce tu contraseña para confirmar:</p>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    @if($errors->userDeletion->has('password'))
                        <small class="text-danger">{{ $errors->userDeletion->first('password') }}</small>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
    });
</script>
@endif
