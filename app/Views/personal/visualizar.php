    <input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />

    <div class="container">
        <div class="row">
            <!-- Sección de Imagen -->
            <div class="col-md-3">
                <div class="image-placeholder">
                    <img src="<?= base_url($datos['foto']); ?>" class="img-fluid mb-2">
                </div>
            </div>
        
            <!-- Sección de inputs junto a la imagen -->
            <div class="col-md-9">
                <div>
                    <div class="form-row">
                        <div class="form-group col-sm-4">
                        <label class="control-label">Nombre:</label>
                            <input type="text" value="<?php echo $datos['nombre']; ?>" name="nombre" class="form-control" readonly>
                        </div>
                        <div class="form-group col-sm-4">
                        <label class="control-label">Apellido(s):</label>
                            <input type="text" value="<?php echo $datos['apellido']; ?>" name="apellido" class="form-control" readonly/>
                        </div>
                        <div class="form-group col-sm-4">
                        <label class="control-label">Cédula:</label>
                            <input type="text" value="<?php echo $datos['cedula']; ?>" name="cedula" class="form-control" readonly/>
                        </div>
                        <div class="form-group col-sm-4">
                        <label class="control-label">Telefono:</label>
                            <input type="text" value="<?php echo $datos['telefono']; ?>" name="telefono" class="form-control" readonly/>
                        </div>
                        
                    </div>
            </div>
        </div>
    </div>

        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>


    <!-- Agrega más campos para editar los datos -->

    