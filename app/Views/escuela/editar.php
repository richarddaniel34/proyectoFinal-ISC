<pre><?php print_r(session()->getFlashdata('errors')); ?></pre>

<form method="post" action="<?php echo base_url('/escuela/actualizar/' . $datos['id']); ?>" enctype="multipart/form-data">
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
                        <div class="form-group col-sm-4 <?= session('errors.nombre') ? 'is-invalid' : '' ?>">
                            <label class="control-label">Nombre:</label>
                            <input type="text" value="<?php echo $datos['nombre']; ?>" name="nombre" class="form-control" placeholder="Input 1">
                            <?php if (session('errors.nombre')): ?>
                                <div class="invalid-feedback alert alert-danger">
                                    <?= session('errors.nombre'); ?>
                                </div>
                            <?php endif; ?>
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
                            <label class="control-label">Código SIGERD:</label>
                            <input type="text"
                                value="<?= old('codigo-sigerd', session('codigo-sigerd') ?? $datos['codigo-sigerd']); ?>"
                                name="codigo-sigerd"
                                class="form-control <?= session('errors.codigo-sigerd') ? 'is-invalid' : '' ?>"
                                placeholder="Ingrese el código SIGERD">

                            <?php if (session('errors.codigo-sigerd')): ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.codigo-sigerd'); ?>
                                </div>
                            <?php endif; ?>
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
                            <input type="text" value="<?php echo esc($datos['rnc']); ?>"
                                name="rnc"
                                class="form-control <?= session()->has('errors') && isset(session('errors')['rnc']) ? 'is-invalid' : '' ?>"
                                placeholder="Input 1">

                            <?php if (session()->has('errors') && isset(session('errors')['rnc'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc(session('errors')['rnc']); ?>
                                </div>
                            <?php endif; ?>
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
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>


        <!-- Agrega más campos para editar los datos -->


</form>




<?php if (session()->getFlashdata('errors')): ?>
    <!-- Flag oculto para que `modal.js` detecte si hay errores -->
    <div id="modal_error_flag" style="display: none;"></div>

    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'error',
                title: '¡Errores en el formulario!',
                html: `<ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>`,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#d33'
            });
        });
    </script>
<?php endif; ?>