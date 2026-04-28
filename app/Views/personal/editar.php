<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class=" fa-solid fa-users"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>
<br>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-xs-12 col-md-10 offset-md-1">
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/personal/actualizar" autocomplete="off" class="formulario-personalizado" id="formulario-tabs">


                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />
                                    <!-- Pestañas -->
                                    <ul class="nav nav-tabs" id="registroTabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="basica-tab" data-toggle="tab" href="#basica">Información Básica y de Contacto </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="nomina-tab" data-toggle="tab" href="#nomina-info">Información de Nombramiento</a>
                                        </li>
                                    </ul>

                                    <!-- Contenido de las pestañas -->
                                    <div class="tab-content mt-3">
                                        <!-- INFORMACION BASICA Y DE CONTACTO -->
                                        <div class="tab-pane fade show active" id="basica">
                                            <h3>Información Básica y de contacto</h3>
                                            <p class="lead">
                                                Complete correctamente los campos marcados con (<span class="text-danger">*</span>); son obligatorios para poder continuar.
                                            </p>
                                            <div class="row">

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="nombre" class="control-label">Nombre(s): <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control" type="text" value="<?= old('nombre', $datos['nombre'] ?? '') ?>" id="nombre" name="nombre"
                                                            autofocus required />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="apellido" class="control-label">Apellido(s): <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control" type="text" value="<?= old('apellido', $datos['apellido'] ?? '') ?>" id="apellido" name="apellido" required />
                                                        <small>*</small>
                                                        <?php if (session('errors.apellido')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.apellido'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="id_nacionalidad" class="control-label">
                                                            Nacionalidad: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <select class="form-control <?= session('errors.id_nacionalidad') ? 'is-invalid' : '' ?>"
                                                            id="id_nacionalidad" name="id_nacionalidad" required>
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($nacionalidades as $nac): ?>
                                                                <option value="<?= esc($nac['id']); ?>"
                                                                    <?= (old('id_nacionalidad') ? old('id_nacionalidad') : $datos['id_nacionalidad']) == $nac['id'] ? 'selected' : ''; ?>>
                                                                    <?= esc($nac['gentilicio']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <?php if (session('errors.id_nacionalidad')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.id_nacionalidad'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>


                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="cedula" class="control-label">Cédula o pasaporte: <small class="obligatorio-formulario">*</small></label>
                                                        <input type="hidden" name="cedula" value="<?= esc($personal['cedula']) ?>">
                                                        <input class="form-control <?= session('errors.cedula') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="cedula"
                                                            name="cedula"
                                                            value="<?= old('cedula', $datos['cedula'] ?? '') ?>" required />
                                                        <?php if (session('errors.cedula')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.cedula'); ?>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="invalid-feedback">Formato: 000-0000000-0</div>
                                                    </div>
                                                </div>



                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="sexo" class="control-label">Sexo: <small class="obligatorio-formulario">*</small></label>
                                                        <select class="form-control <?= session('errors.sexo') ? 'is-invalid' : '' ?>" id="sexo" name="sexo" required>
                                                            <option value="">Seleccione el sexo</option>
                                                            <?php foreach ($sexoOpciones as $opcion): ?>
                                                                <option value="<?= esc($opcion); ?>"
                                                                    <?= old('sexo', $datos['sexo'] ?? '') == $opcion ? 'selected' : ''; ?>>
                                                                    <?= esc($opcion); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <?php if (session('errors.sexo')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.sexo'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="celular" class="control-label">Célular: <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control" value="<?= old('celular', $datos['celular'] ?? '') ?>" type="text" id="celular" name="celular" />
                                                        <div class="invalid-feedback">Formato: 000-000-0000</div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="telefono" class="control-label">Teléfono:</label>
                                                        <input class="form-control" value="<?= old('telefono', $datos['telefono'] ?? '') ?>" type="text" id="telefono" name="telefono" required />
                                                        <div class="invalid-feedback">Formato: 000-000-0000</div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="email" class="control-label">Email:</label>
                                                        <input class="form-control" value="<?= old('email', $datos['email'] ?? '') ?>" type="text" id="email" name="email" required />
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="direccion" class="control-label">Dirección: <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control" value="<?= old('direccion', $datos['direccion'] ?? '') ?>" type="text" id="direccion" name="direccion" required />

                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="fecha_nac focuses" class="control-label">fecha de Nacimiento: <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control is-filled" value="<?= old('fecha_nac', $datos['fecha_nac'] ?? '') ?>" type="date" id="fecha_nac" name="fecha_nac" />

                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="foto" class="control-label">Foto:</label>
                                                        <input type="file" name="foto" id="foto" class="form-control file-input" />
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-primary" id="btnSiguiente" onclick="siguiente('nomina-info')">Siguiente <i class="fa-solid fa-forward"></i></button>
                                                <a href="<?= base_url(); ?>/personal" class="btn btn-danger btn-raised">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </a>
                                            </div>

                                            <br>

                                        </div>


                                        <!-- 
                                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                           -->


                                        <!--===================== NOMINA ===================== -->
                                        <div class="tab-pane fade" id="nomina-info">
                                            <h3>Datos de Nomina</h3>
                                            <p class="lead">
                                                Complete correctamente los campos marcados con (<span class="text-danger">*</span>); son obligatorios para poder continuar.
                                            </p>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="condicion" class="control-label">Condición: <small class="obligatorio-formulario">*</small></label>
                                                        <select class="form-control is-filled" id="condicion" name="condicion" required>
                                                            <option value="" disabled <?= empty($personal['condicion']) ? 'selected' : '' ?>>Selecciona una opción</option>
                                                            <?php foreach ($condiciones as $condicion): ?>
                                                                <option value="<?= esc($condicion['id']); ?>" <?= $condicion['id'] == $personal['condicion'] ? 'selected' : '' ?>>
                                                                    <?= esc($condicion['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="nombramiento" class="control-label">Nombramiento: <small class="obligatorio-formulario">*</small></label>
                                                        <select class="form-control is-filled" id="nombramiento" name="nombramiento" required>
                                                            <option value="" selected disabled>Selecciona una opción</option>v
                                                            <?php foreach ($nombramientos as $nombramiento): ?>
                                                                <option value="<?= esc($nombramiento['id']); ?>" <?= $nombramiento['id'] == $personal['nombramiento'] ? 'selected' : '' ?>>
                                                                    <?= esc($nombramiento['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="funcion" class="control-label">Función: <small class="obligatorio-formulario">*</small></label>
                                                        <select class="form-control is-filled" id="funcion" name="funcion" required>
                                                            <option value="" selected disabled>Selecciona una opción</option>v
                                                            <?php foreach ($nombramientos as $nombramiento): ?>
                                                                <option value="<?= esc($nombramiento['id']); ?>" <?= $nombramiento['id'] == $personal['funcion'] ? 'selected' : '' ?>>
                                                                    <?= esc($nombramiento['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="grado-academico" class="control-label is-filled">Grado Academico: <small class="obligatorio-formulario">*</small></label>
                                                        <select class="form-control is-filled" id="grado_academico" name="grado_academico" required>
                                                            <option value="" selected disabled>Selecciona una opción</option>


                                                            <?php foreach ($grados_academicos as $grado_academico): ?>
                                                                <option value="<?= esc($grado_academico['id']); ?>" <?= $grado_academico['id'] == $personal['grado_academico'] ? 'selected' : '' ?>>
                                                                    <?= esc($grado_academico['grado_academico']); ?>
                                                                </option>
                                                            <?php endforeach; ?>



                                                        </select>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-secondary" onclick="siguiente('basica')"><i class="fa-solid fa-backward"></i> Atrás</button>
                                                <a
                                                    href="<?php echo base_url(); ?>/personal"
                                                    class="btn btn-danger btn-raised">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </a>

                                                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                                            </div>


                                        </div>

                                        <!-- 
                                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                           -->

                                        <!-- Tutor -->

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->section('scripts') ?>

<script>
    var validarUsuarioURL = "<?= base_url('usuarios/validarUsuario'); ?>";
</script>


<script>
    function siguiente(tab) {
        $('#registroTabs a[href="#' + tab + '"]').tab('show');
    }
</script>



<?= $this->endSection() ?>