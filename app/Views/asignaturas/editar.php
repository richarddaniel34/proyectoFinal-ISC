<form method="POST" action="<?php echo base_url('asignaturas/actualizar/' . $datos['id']); ?>" autocomlete="off" class="formulario-personalizado">
<input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />
    <div class="row">
        <div class="col-12 col-sm-3">
            <div class="form-group label-floating">
                <label for="nombre" class="control-label">Nombre asignatura:</label>
                <input class="form-control" type="text" value="<?php echo $datos['nombre']; ?>" id="nombre" name="nombre" autofocus oninput="generarCodigo()" required />
                <small>*</small>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="form-group label-floating">
                <label for="codigo-asignatura" class="control-label">Codigo Asignatura:</label>
                <input class="form-control" type="text" value="<?php echo $datos['codigo_asignatura']; ?>" id="codigo_asignatura" value=" " name="codigo_asignatura" required />
            </div>
        </div>
        <div class="form-group label-floating col-sm-4">
            <label for="tipo_asignatura" class="control-label">tipo Asignatura:</label>
            <select class="form-control" id="tipo_asignatura" name="tipo_asignatura" required>
                <?php foreach ($tipo_asignaturas as $tipo_asignatura): ?>
                    <option value="<?= esc($tipo_asignatura['id']); ?>"
                        <?= $tipo_asignatura['id'] == $datos['tipo_asignatura'] ? 'selected' : ''; ?>>
                        <?= esc($tipo_asignatura['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>




    </div>

    <p class="text-center">

        <button type="submit" class="btn btn-success text-light">
            <i class="fa-solid fa-floppy-disk"></i> Guardar
        </button>
        <button type="button" class="btn btn-danger text-light" 
        data-dismiss="modal"> <i class="fa-solid fa-ban"></i> Cerrar</button>
    </p>
</form>