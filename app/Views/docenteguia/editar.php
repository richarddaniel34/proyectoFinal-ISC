<form id="formEditarDocente" method="POST" action="<?php echo base_url('docenteguia/actualizar/' . $datos['id']); ?>" autocomlete="off" class="formulario-personalizado">
    <input type="hidden" name="id_asignacion" value="<?= esc($datos['id']) ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="edit_docente">Docente</label>
                <select id="edit_docente" name="id_personal" class="form-control">
                    <option value="">-- Seleccione un docente --</option>
                    <?php foreach ($docentes as $docente): ?>
                        <option value="<?= esc($docente['id']) ?>" <?= $datos['id_personal'] == $docente['id'] ? 'selected' : '' ?>>
                            <?= esc($docente['nombre_completo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="edit_curso">Curso</label>
                <input type="text" id="edit_curso" class="form-control" value="<?= esc($datos['nombre_curso']) ?>" readonly>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="edit_periodo" class="form-label">Periodo Académico</label>
                <input type="text" id="edit_periodo" class="form-control" value="<?= esc($datos['nombre_schoolyear']) ?>" readonly>
            </div>
        </div>
    </div>

    <input type="hidden" name="id_curso" value="<?= esc($datos['id_curso']) ?>">
    <input type="hidden" name="id_schoolyear" value="<?= esc($datos['id_schoolyear']) ?>">



    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </div>
</form>