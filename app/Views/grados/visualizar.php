    <input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />

    <div class="container">
        <div class="row">
            <!-- Sección de Imagen -->
            <div class="col-md-3">
                <div class="image-placeholder">
                    <img src="<?= base_url($datos['logo']); ?>" class="img-fluid mb-2">
                </div>
            </div>
        
            <!-- Sección de inputs junto a la imagen -->
            <div class="col-md-9">
                <div>
                    <div class="form-row">
                        <div class="form-group col-sm-4">
                        <label class="control-label">Nombre:</label>
                            <input type="text" value="<?php echo $datos['nombre']; ?>" name="nombre" class="form-control" placeholder="Input 1">
                        </div>
                        
                        <div class="form-group col-sm-4">
                            <label class="control-label">Modalidad:</label>
                            <select class="form-control" id="modalidad" name="modalidad" required>
                            <?php foreach ($modalidades as $modalidad): ?>
                                <option value="<?= esc($modalidad['id']); ?>" 
                                    <?= $modalidad['id'] == $datos['modalidad'] ? 'selected' : ''; ?>>
                                    <?= esc($modalidad['nombre']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Codigo Sigerd:</label>
                            <input type="text" value="<?php echo $datos['codigo-sigerd']; ?>" name="codigo-sigerd" class="form-control" placeholder="Input 2">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-row">
                    <div class="form-group col-sm-4">
                        <label class="control-label">Código de plantel:</label>
                            <input type="text" value="<?php echo $datos['codigo-plantel']; ?>" name="codigo-plantel" class="form-control" placeholder="Input 1">
                        </div>
                        <div class="form-group col-sm-4">
                        <label class="control-label">RNC:</label>
                            <input type="text" value="<?php echo $datos['rnc']; ?>" name="rnc" class="form-control" placeholder="Input 1">
                        </div>
                        <div class="form-group col-sm-4">
                        <label class="control-label">Regional de Educacion:</label>
                            <input type="text" value="<?php echo $datos['regional']; ?>" name="regional" class="form-control" placeholder="Input 1">
                        </div>
                    </div>
                </div>

                
            </div>
        </div>

        <!-- Sección de inputs adicionales (debajo de la imagen y las primeras filas de inputs) -->
        <div>
                    <div class="form-row">
                        <div class="form-group col-sm-3">
                            <label class="control-label">Distrito Educativo:</label>
                            <input type="text" value="<?php echo $datos['distrito']; ?>" name="distrito" class="form-control" placeholder="Input 1">
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label">email:</label>
                            <input type="text" value="<?php echo $datos['email']; ?>" name="email" class="form-control" placeholder="Input 1">
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label">Teléfono:</label>
                            <input type="text" value="<?php echo $datos['telefono']; ?>" name="telefono" class="form-control" placeholder="Input 1">
                        </div>
                        <div class="form-group col-sm-3">
                            <label class="control-label">Redes:</label>
                            <input type="text" value="<?php echo $datos['redes']; ?>" name="redes" class="form-control" placeholder="Input 1">
                        </div>
                    </div>
                </div>
        <div>
            <div class="form-row">
                <div class="form-group col-sm-3">
                    <label class="control-label">Dirección:</label>
                    <input type="text" value="<?php echo $datos['direccion']; ?>" name="direccion" class="form-control" placeholder="Input 1">
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label">Web:</label>
                    <input type="text" value="<?php echo $datos['web']; ?>" name="web" class="form-control" placeholder="Input 1">
                </div>
            </div>
        </div>
    </div>

        
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>


    <!-- Agrega más campos para editar los datos -->

    