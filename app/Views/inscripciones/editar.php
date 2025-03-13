<form method="POST" action="<?php echo base_url('schoolyear/actualizar/' . $datos['id']); ?>" autocomlete="off" class="formP">
    <input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />
    <div class="row">
        <div class="col-12 col-sm-3">
            <div class="form-group label-floating">
                <label for="nombre" class="control-label">Nombre:</label>
                <input class="form-control" type="text" id="nombre" name="nombre" autofocus value="<?php echo $datos['nombre']; ?> " />
                <small>*</small>
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="form-group label-floating">
                <label for="fecha_inicio" class="control-label">fecha de Inicio:</label>
                <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio"
                    value="<?php echo isset($datos['fecha_inicio']) ? date('Y-m-d', strtotime($datos['fecha_inicio'])) : ''; ?>" required />
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="form-group label-floating">
                <label for="fecha_termino" class="control-label">fecha de Termino:</label>
                <input class="form-control" type="date" id="fecha_termino" value=" " name="fecha_termino" />
            </div>
        </div>
        <div class="col-12 col-sm-3">
            <div class="form-group label-floating">
                <label for="codigo" class="control-label">Codigo:</label>
                <input class="form-control" type="text" id="codigo" name="codigo" value="<?php echo $datos['codigo']; ?> " />
                <small>*</small>
            </div>
        </div>
    </div>
    <p class="text-center">

        <button type="submit" class="btn btn-info btn-raised btn-sm">
            <i class="fa-solid fa-floppy-disk"></i> Guardar
        </button>
        <button type="button" class="btn btn-danger btn-raised btn-sm" data-dismiss="modal">Cerrar</button>
    </p>
</form>