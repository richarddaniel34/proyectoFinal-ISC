<form action="<?= site_url('usuarios/resetearClave') ?>" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="id_usuario" value="<?= esc($usuario['id']) ?>">

    <div class="form-group">
        <label>Usuario</label>
        <input type="text"
            class="form-control"
            value="<?= esc($usuario['usuario']) ?>"
            readonly>
    </div>

    <div class="form-group">
        <label>Nueva contraseña</label>
        <input type="password"
            class="form-control"
            value="<?= esc($usuario['usuario']) ?>"
            readonly>
        <small class="text-muted">
            La contraseña será igual al nombre de usuario.
        </small>
    </div>

    <div class="alert alert-warning">
        Al restaurar la contraseña, el usuario deberá cambiarla en su próximo inicio de sesión.
    </div>

    <div class="modal-footer px-0 pb-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancelar
        </button>

        <button type="submit" class="btn btn-warning">
            Restaurar contraseña
        </button>
    </div>
</form>