
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class=" fa-solid fa-users"></i> <?php print_r($titulo1) ?> <small><?php print_r($titulo2) ?></small></h1>
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

                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/personal/insertar" autocomplete="off" class="formulario-personalizado" id="formulario-tabs">
                                    <?= csrf_field() ?>
                                    <!-- Pestañas -->
                                    <ul class="nav nav-tabs" id="registroTabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="basica-tab" data-toggle="tab" href="#basica">Información Básica y de Contacto </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="nomina-tab" data-toggle="tab" href="#nomina-info">Información de Nombramiento</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="nomina-tab" data-toggle="tab" href="#sesion-info">Usuario y contraseña</a>
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
                                                        <input class="form-control" type="text" value="<?= old('nombre'); ?>" id="nombre" name="nombre" autofocus required />
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="apellido" class="control-label">Apellido(s): <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control" type="text" value="<?= old('apellido'); ?>" id="apellido" name="apellido" required />
                                                        <?php if (session('errors.apellido')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.apellido'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="id_nacionalidad" class="control-label">Nacionalidad: <small class="obligatorio-formulario">*</small></label>
                                                        <select class="form-control <?= session('errors.id_nacionalidad') ? 'is-invalid' : '' ?>"
                                                            id="id_nacionalidad" name="id_nacionalidad" required>
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($nacionalidades as $nac): ?>
                                                                <option value="<?= esc($nac['id']); ?>"
                                                                    <?= old('id_nacionalidad') == $nac['id'] ? 'selected' : ''; ?>>
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
                                                    <div class="form-group">
                                                        <label for="cedula" class="control-label">
                                                            Cédula o pasaporte: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input required
                                                            class="form-control <?= session('errors.cedula') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="cedula"
                                                            name="cedula"
                                                            value="<?= old('cedula'); ?>" />

                                                        <?php if (session('errors.cedula')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.cedula'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>



                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="sexo" class="control-label">Sexo: <small class="obligatorio-formulario">*</small></label>
                                                        <select class="form-control <?= session('errors.sexo') ? 'is-invalid' : '' ?>" id="sexo" name="sexo" required>
                                                            <option value="">Seleccione una opción</option>
                                                            <?php foreach ($sexoOpciones as $opcion): ?>
                                                                <option value="<?= esc($opcion); ?>" <?= old('sexo') == $opcion ? 'selected' : ''; ?>>
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
                                                        <input class="form-control" value="<?= old('celular'); ?>" type="text" id="celular" name="celular" required />
                                                        <div class="invalid-feedback">Formato: 000-000-0000</div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="telefono" class="control-label">Teléfono:</label>
                                                        <input class="form-control" value="<?= old('telefono'); ?>" type="text" id="telefono" name="telefono" />
                                                        <div class="invalid-feedback">Formato: 000-000-0000</div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="email" class="control-label">Email:</label>
                                                        <input class="form-control" value="<?= old('email'); ?>" type="text" id="email" name="email" />
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="direccion" class="control-label">Dirección: <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control" value="<?= old('direccion'); ?>" type="text" id="direccion" name="direccion" required />

                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group ">
                                                        <label for="fecha_nac focuses" class="control-label">fecha de Nacimiento: <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control is-filled" value="<?= old('fecha_nac'); ?>" type="date" id="fecha_nac" name="fecha_nac" required />

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
                                                <button type="button" class="btn btn-danger btn-cancelar" id="btn-cancelar-1">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>
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
                                                            <option value="" selected disabled>--Selecciona una opción--</option>
                                                            <?php foreach ($condiciones as $condicion): ?>
                                                                <option value="<?= esc($condicion['id']); ?>">
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
                                                            <option value="" selected disabled>Selecciona una opción</option>
                                                            <?php foreach ($nombramientos as $nombramiento): ?>
                                                                <option value="<?= esc($nombramiento['id']); ?>">
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
                                                            <option value="" selected disabled>Selecciona una opción</option>
                                                            <?php foreach ($nombramientos as $nombramiento): ?>
                                                                <option value="<?= esc($nombramiento['id']); ?>">
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
                                                            <<option value="" selected disabled>Selecciona una opción</option>
                                                                <?php foreach ($grados_academicos as $grado_academico): ?>
                                                                    <option value="<?= esc($grado_academico['id']); ?>"><?= esc($grado_academico['grado_academico']); ?></option>
                                                                <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-secondary" onclick="siguiente('basica')"><i class="fa-solid fa-backward"></i> Atrás</button>
                                                <button type="button" class="btn btn-primary" onclick="siguiente('sesion-info')">Siguiente <i class="fa-solid fa-forward"></i></button>
                                                <button type="button" class="btn btn-danger btn-cancelar" id="btn-cancelar-2">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>
                                            </div>


                                        </div>

                                        <!-- 
                                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                           -->

                                        <!-- Tutor -->
                                        <div class="tab-pane fade" id="sesion-info">
                                            <h3>Datos para inicio de sesion</h3>
                                           <p class="lead">
                                                Complete correctamente los campos marcados con (<span class="text-danger">*</span>); son obligatorios para poder continuar.
                                            </p>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="usuario" class="control-label">Nombre de Usuario:</label>
                                                        <input class="form-control" value="<?= old('usuario'); ?>" type="text" id="usuario" name="usuario" required readonly />
                                                        <small class="indicacion-formulario">El Usuario se genera automaticamente</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="direccion" class="control-label">Contraseña:</label>
                                                        <input class="form-control" type="password" value="<?= old('clave'); ?>" id="clave" name="clave" autocomplete="new-password" required readonly />
                                                        <small class="indicacion-formulario">La Contraseña se genera automaticamente</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="tipo_usuario" class="control-label is-filled">
                                                            Tipo de Usuario: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <select class="form-control is-filled" id="tipo_usuario" name="tipo_usuario" required>
                                                            <option value="0">Sin usuario</option>
                                                            <?php foreach ($tipos_usuarios as $tipo_usuario): ?>
                                                                <?php
                                                                // Mostrar "Administrador" solo si el usuario logueado también es administrador
                                                                $esAdminActual = session()->get('tipo_usuario') == 5;
                                                                $esAdminOption = $tipo_usuario['id'] == 5;

                                                                if ($esAdminOption && !$esAdminActual) continue;
                                                                ?>
                                                                <option value="<?= esc($tipo_usuario['id']); ?>">
                                                                    <?= esc($tipo_usuario['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-secondary" id="btnSiguiente" onclick="siguiente('nomina-info')"><i class="fa-solid fa-backward"></i> Atrás</button>

                                                <button type="button" class="btn btn-danger btn-cancelar" id="btn-cancelar">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>

                                                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                                            </div>


                                        </div>
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
    // Todos los botones que tengan la clase 'btn-cancelar'
    document.querySelectorAll('.btn-cancelar').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault(); // evita redirección inmediata
            Swal.fire({
                title: '¿Está seguro que desea cancelar?',
                text: "Los cambios que haya hecho no se guardarán.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, continuar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirige a la página base
                    window.location.href = "<?= base_url('personal') ?>";
                }
            });
        });
    });
</script>



<?= $this->endSection() ?>