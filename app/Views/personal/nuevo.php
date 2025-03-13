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
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/personal/insertar" autocomplete="off" class="">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="id_escuela" class="control-label"></label>
                                                <select class="form-control is-filled" id="id_escuela" name="id_escuela" required>
                                                    <option value="" selected disabled>Seleccione una Escuela</option>
                                                    <?php foreach ($escuelas as $escuela): ?>
                                                        <option value="<?= esc($escuela['id']); ?>">
                                                            <?= esc($escuela['codigo_gestion']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="nombre" class="control-label">Nombre(s):</label>
                                                <input class="form-control" type="text" value="<?= old('nombre'); ?>" id="nombre" name="nombre" autofocus required />
                                                <small>*</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="apellido" class="control-label">Apellido(s):</label>
                                                <input class="form-control" type="text" value="<?= old('apellido'); ?>" id="apellido" name="apellido" required />
                                                <?php if (session('errors.apellido')): ?>
                                                    <div class="invalid-feedback alert alert-danger">
                                                        <?= session('errors.apellido'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="form-group label-floating">
                                            <label for="sexo" class="control-label"></label>
                                            <select class="form-control <?= session('errors.sexo') ? 'is-invalid' : '' ?>" id="sexo" name="sexo" required>
                                                <option value="">Seleccione el sexo</option>
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
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="cedula" class="control-label">Cédula:</label>
                                                <input class="form-control <?= session('errors.cedula') ? 'is-invalid' : '' ?>"
                                                    type="text"
                                                    id="cedula"
                                                    name="cedula"
                                                    value="<?= old('cedula'); ?>" required />

                                                <?php if (session('errors.cedula')): ?>
                                                    <div class="invalid-feedback alert alert-danger">
                                                        <?= session('errors.cedula'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="telefono" class="control-label">Teléfono:</label>
                                                <input class="form-control" value="<?= old('telefono'); ?>" type="text" id="telefono" name="telefono" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="email" class="control-label">Email:</label>
                                                <input class="form-control" value="<?= old('email'); ?>" type="text" id="email" name="email" required />
                                                <small>*</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="direccion" class="control-label">Dirección:</label>
                                                <input class="form-control" value="<?= old('direccion'); ?>" type="text" id="direccion" name="direccion" required />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        
                                        
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="fecha_nac focuses" class="control-label">fecha de Nacimiento:</label>
                                                <input class="form-control is-filled" value="<?= old('fecha_nac'); ?>" type="date" id="fecha_nac" name="fecha_nac" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="condicion" class="control-label">Condición:</label>
                                                <select class="form-control is-filled" id="condicion" name="condicion" required>
                                                    <option value="" selected disabled>Selecciona una opción</option>
                                                    <?php foreach ($condiciones as $condicion): ?>
                                                        <option value="<?= esc($condicion['id']); ?>">
                                                            <?= esc($condicion['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="nombramiento" class="control-label">Nombramiento:</label>
                                                <select class="form-control is-filled" id="nombramiento" name="nombramiento" required>
                                                    <option value="" selected disabled>Selecciona una opción</option>v
                                                    <?php foreach ($nombramientos as $nombramiento): ?>
                                                        <option value="<?= esc($nombramiento['id']); ?>">
                                                            <?= esc($nombramiento['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="funcion" class="control-label">Función:</label>
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
                                    </div>

                                    <div class="row">
                                        
                                        

                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="grado-academico" class="control-label is-filled">Grado Academico:</label>
                                                <select class="form-control is-filled" id="grado_academico" name="grado_academico" required>
                                                    <<option value="" selected disabled>Selecciona una opción</option>
                                                        <?php foreach ($grados_academicos as $grado_academico): ?>
                                                            <option value="<?= esc($grado_academico['id']); ?>"><?= esc($grado_academico['grado_academico']); ?></option>
                                                        <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group  label-floating ">
                                                <label for="foto" class="control-label">Foto:</label>
                                                <div>
                                                    <input type="text" readonly="" value="Browse..." class="form-control" placeholder="Browse..." />
                                                    <input type="file" name="foto" id="foto" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="usuario" class="control-label">Nombre de Usuario:</label>
                                                <input class="form-control" value="<?= old('usuario'); ?>" type="text" id="usuario" name="usuario" required readonly />
                                                <small>*</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="direccion" class="control-label">Contraseña:</label>
                                                <input class="form-control" type="password" value="<?= old('clave'); ?>" id="clave" name="clave" autocomplete="new-password" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="confirmar_clave" class="control-label">Confirmar Contraseña:</label>
                                                <input class="form-control" type="password" value="<?= old('confirmar_clave'); ?>" id="confirmar_clave" autocomplete="new-password" name="confirmar_clave" />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="tipo_usuario" class="control-label is-filled">Tipo de Usuario:</label>
                                                <select class="form-control is-filled" id="tipo_usuario" name="tipo_usuario">
                                                    <option value="" selected disabled>Selecciona una opción</option>
                                                    <pre><?php print_r($tipos_usuarios); ?></pre>
                                                    <?php foreach ($tipos_usuarios as $tipo_usuario): ?>
                                                        <option value="<?= esc($tipo_usuario['id']); ?>"><?= esc($tipo_usuario['nombre']); ?></option>
                                                        <?= esc($tipo_usuario['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>


                                    </div>


                                    <p class="text-center">

                                        <button type="submit" class="btn btn-info btn-raised btn-sm">
                                            <i class="fa-solid fa-floppy-disk"></i> Guardar
                                        </button>

                                        <a
                                            href="<?php echo base_url(); ?>/asignatura"
                                            class="btn btn-danger btn-raised btn-sm">
                                            <i class="fa-solid fa-ban"></i> Cancelar
                                        </a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var validarUsuarioURL = "<?= base_url('usuarios/validarUsuario'); ?>";
</script>