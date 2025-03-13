<form method="POST" action="<?php echo base_url('personal/actualizar/' . $personal['id']); ?>" autocomlete="off" class="formP">
    <input type="hidden" name="id" value="<?php echo $personal['id']; ?>" />

    <div class="container">
        <div class="row">
            <!-- Sección de Imagen -->
            <div class="col-md-3">
                <div class="image-placeholder">
                    <img src="<?= base_url($personal['foto']); ?>" class="img-fluid mb-2">
                </div>
            </div>

            <!-- Sección de inputs junto a la imagen -->
            <div class="col-md-9">
                <div>
                    <div class="form-row">
                        <div class="form-group col-sm-4">
                            <label class="control-label">Nombre (s):</label>
                            <input type="text" value="<?php echo $personal['nombre']; ?>" name="nombre" class="form-control">
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Apellido(s):</label>
                            <input type="text" value="<?php echo $personal['apellido']; ?>" name="apellido" class="form-control" />
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Cédula:</label>
                            <input type="text" value="<?php echo $personal['cedula']; ?>" name="cedula" class="form-control" />
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Telefono:</label>
                            <input type="text" value="<?php echo $personal['telefono']; ?>" name="telefono" class="form-control" />
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Dirección:</label>
                            <input type="text" value="<?php echo $personal['direccion']; ?>" name="direccion" class="form-control" />
                        </div>
                        <div class="form-group col-sm-4">
                            <label class="control-label">Email:</label>
                            <input type="text" value="<?php echo $personal['email']; ?>" name="email" class="form-control" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="form-row">
            <div class="col-12 col-sm-3">
                <div class="form-group">
                    <label for="fecha_nac" class="control-label">Fecha de Nacimiento:</label>
                    <input class="form-control" type="date" id="fecha_nac" name="fecha_nac"
                        value="<?php echo isset($personal['fecha_nac']) ? date('Y-m-d', strtotime($personal['fecha_nac'])) : ''; ?>" />
                </div>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label">Condición:</label>
                <select class="form-control" id="condicion" name="condicion" required>
                    <?php foreach ($condiciones as $condicion): ?>
                        <option value="<?= esc($condicion['id']); ?>"
                            <?= $condicion['id'] == $personal['condicion'] ? 'selected' : ''; ?>>
                            <?= esc($condicion['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label">Nombramiento:</label>
                <select class="form-control" id="nombramiento" name="nombramiento" required>
                    <?php foreach ($nombramientos as $nombramiento): ?>
                        <option value="<?= esc($nombramiento['id']); ?>"
                            <?= $nombramiento['id'] == $personal['nombramiento'] ? 'selected' : ''; ?>>
                            <?= esc($nombramiento['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label">Función:</label>
                <select class="form-control" id="funcion" name="funcion" required>
                    <?php foreach ($nombramientos as $nombramiento): ?>
                        <option value="<?= esc($nombramiento['id']); ?>"
                            <?= $nombramiento['id'] == $personal['funcion'] ? 'selected' : ''; ?>>
                            <?= esc($nombramiento['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label">Función:</label>
                <select class="form-control" id="tipo_trabajo" name="tipo_trabajo" required>
                    <?php foreach ($tipo_trabajos as $tipo_trabajo): ?>
                        <option value="<?= esc($tipo_trabajo['id']); ?>"
                            <?= $tipo_trabajo['id'] == $personal['tipo_trabajo'] ? 'selected' : ''; ?>>
                            <?= esc($tipo_trabajo['nombre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label class="control-label">Nivel Academico:</label>
                <select class="form-control" id="grado_academico" name="grado_academico" required>
                    <?php foreach ($grados_academicos as $grado_academico): ?>
                        <option value="<?= esc($grado_academico['id']); ?>"
                            <?= $grado_academico['id'] == $personal['grado_academico'] ? 'selected' : ''; ?>>
                            <?= esc($grado_academico['grado_academico']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-sm-3">
                <div class="form-group ">
                    <label for="logo" class="control-label">Logo</label>
                    <div>
                        <input type="text" readonly="" class="form-control" placeholder="Browse..." />
                        <input type="file" name="logo" id="logo" />
                    </div>
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