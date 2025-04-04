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
        <h1 class="text-titles"><i class="fa-solid fa-user-graduate"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
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
                                <form method="POST" action="<?php echo base_url(); ?>estudiantes/insertar" enctype="multipart/form-data" autocomlete="off">
                                    <?= csrf_field() ?>
                                    <!-- Pestañas -->

                                    <ul class="nav nav-tabs" id="registroTabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="Datos-basicos-tab" data-toggle="tab" href="#basicos">Datos Básicos</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="responsables-tab" data-toggle="tab" href="#responsable">Responsables</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="datos-medicos-tab" data-toggle="tab" href="#salud">Datos Medicos</a>
                                        </li>
                                    </ul>

                                    <!-- Contenido de las pestañas -->
                                    <div class="tab-content mt-3">
                                        <!-- Padre -->
                                        <div class="tab-pane fade show active" id="basicos">
                                            <h3>DATOS BASICOS DEL ESTUDIANTE</h3>
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
                                                        <input class="form-control" type="text" id="nombre" name="nombre" />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido" class="control-label">Apellido(s):</label>
                                                        <input class="form-control" type="text" id="apellido" name="apellido" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="estado_padres" class="control-label">Sexo:</label>
                                                        <select id="sexo" name="sexo" class="form-control">
                                                            <option value="">--SELECCIONE UNA OPCION--</option>
                                                            <option value="M">M</option>
                                                            <option value="F">F</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="fecha_nac" class="control-label">fecha de Nacimiento:</label>
                                                        <input class="form-control" type="date" id="fecha_nac" name="fecha_nac" />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating is-filled">
                                                        <label for="lugar_nacimiento" class="control-label">Lugar de Nacimiento:</label>
                                                        <input class="form-control" type="text" id="lugar_nacimiento" name="lugar_nacimiento" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="provincia" class="control-label">Provincia:</label>
                                                        <input class="form-control" type="text" id="provincia" name="provincia" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="numero_identidad" class="control-label">Numero Unico de Identidad (NUI):</label>
                                                        <input class="form-control" type="text" id="numero_identidad" name="numero_identidad" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="sigerd_id" class="control-label">ID SIGERD:</label>
                                                        <input class="form-control" type="text" id="sigerd_id" name="sigerd_id" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion" class="control-label">Dirección:</label>
                                                        <input class="form-control" type="text" id="direccion" name="direccion" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="escuela_procedencia" class="control-label">Escuela de Procedencia:</label>
                                                        <input class="form-control" type="text" id="escuela_procedencia" name="escuela_procedencia" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="imagen" class="control-label"></label>
                                                        <div>
                                                            <input type="text" readonly="" class="form-control" placeholder="Imagen..." />
                                                            <input type="file" name="imagen" id="imagen" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="matricula" class="control-label">Matricula:</label>
                                                        <input class="form-control" type="text" id="matricula" name="matricula" value="<?= esc($matricula); ?>" readonly />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="matricula" class="control-label">Contraseña:</label>
                                                        <input class="form-control" type="password" id="clave" name="clave" value="<?= esc($matricula); ?>" readonly />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="tipo_usuario" class="control-label is-filled">Tipo de Usuario:</label>
                                                        <select class="form-control" id="tipo_usuario" name="tipo_usuario">
                                                            <?php foreach ($tipos_usuarios as $tipo_usuario): ?>
                                                                <option value="<?= esc($tipo_usuario['id']); ?>"><?= esc($tipo_usuario['nombre']); ?></option>
                                                                <?= esc($tipo_usuario['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="siguiente('responsable')">Siguiente</button>
                                            <a href="<?php echo base_url(); ?>estudiantes" class=" btn btn-danger text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Cancelar</a>
                                        </div>

                                        <!-- Madre -->
                                        <div class="tab-pane fade" id="responsable">
                                            <h3>RESPONSABLES</h3>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="padre" class="control-label">Padre:</label>
                                                        <select id="padre" name="padre" class="form-control select2">
                                                            <option value="">Seleccione al padre</option>
                                                        </select>
                                                        <small>*</small>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="parentesco_padre" class="control-label">Parentesco Padre</label>
                                                        <select name="parentesco_padre" class="form-control" >
                                                            <option value="">Seleccionar parentesco</option>
                                                            <?php foreach ($parentescos as $parentesco): ?>
                                                                <option value="<?= esc($parentesco) ?>"><?= esc($parentesco) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="observaciones_padre" class="control-label">Observaciones:</label>
                                                        <input class="form-control" type="text" id="observaciones_padre" name="observaciones_padre" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="madre" class="control-label">Madre:</label>
                                                        <select id="madre" name="madre" class="form-control select2">
                                                            <option value="">Selecione a la madre</option>
                                                        </select>
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="parentesco_madre" class="control-label">Parentesco Padre</label>
                                                        <select name="parentesco_madre" class="form-control" required>
                                                            <option value="">Seleccionar parentesco</option>
                                                            <?php foreach ($parentescos as $parentesco): ?>
                                                                <option value="<?= esc($parentesco) ?>"><?= esc($parentesco) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="observaciones_madre" class="control-label">Observaciones:</label>
                                                        <input class="form-control" type="text" id="observaciones_madre" name="observaciones_madre" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                            <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="tutor" class="control-label">tutor:</label>
                                                        <select id="tutor" name="tutor" class="form-control select2">
                                                            <option value="">Selecione al tutor</option>
                                                        </select>
                                                        <small>Opcional</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="parentesco_tutor" class="control-label">Parentesco Padre</label>
                                                        <select name="parentesco_tutor" class="form-control">
                                                            <option value="">Seleccionar parentesco</option>
                                                            <?php foreach ($parentescos as $parentesco): ?>
                                                                <option value="<?= esc($parentesco) ?>"><?= esc($parentesco) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="observaciones_tutor" class="control-label">Observaciones:</label>
                                                        <input class="form-control" type="text" id="observaciones_tutor" name="observaciones_tutor" />
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="estado_padres" class="control-label">Estado Padres:</label>
                                                        <select id="estado_padres" name="estado_padres" class="form-control">
                                                            <option value="">--SELECCIONE UNA OPCION--</option>
                                                            <option value="casados y viven juntos">Casados y Viven Juntos</option>
                                                            <option value="casados y no viven juntos">Casados y no viven Juntos</option>
                                                            <option value="separados">Separados</option>
                                                            <option value="divorciados">Divorciados</option>
                                                            <option value="union libre">Unión Libre</option>
                                                            <option value="familia en tramites viaje">Familia en trámites de viaje</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="casa_estudiante" class="control-label">Con quien vice el estudiante:</label>
                                                        <select id="casa_estudiante" name="casa_estudiante" class="form-control">
                                                            <option value="">--SELECCIONE UNA OPCION--</option>
                                                            <option value="con ambos padres">Con ambos Padres</option>
                                                            <option value="Padre">Padre</option>
                                                            <option value="madre">Madre</option>
                                                            <option value="tutor">tutor</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="siguiente('basicos')">Atrás</button>
                                            <button type="button" class="btn btn-primary" onclick="siguiente('salud')">Siguiente</button>
                                            <a href="<?php echo base_url(); ?>estudiantes" class=" btn btn-danger text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Cancelar</a>
                                        </div>

                                        <!-- Tutor -->
                                        <div class="tab-pane fade" id="salud">
                                            <h3>DATOS MEDICOS Y DE SALUD</h3>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="tipo_sangre">Tipo de Sangre:</label>
                                                        <select class="form-control" id="tipo_sangre" name="tipo_sangre" required>
                                                            <option value=" ">Seleccione...</option>
                                                            <option value="A+">A+</option>
                                                            <option value="A-">A-</option>
                                                            <option value="B+">B+</option>
                                                            <option value="B-">B-</option>
                                                            <option value="O+">O+</option>
                                                            <option value="O-">O-</option>
                                                            <option value="AB+">AB+</option>
                                                            <option value="AB-">AB-</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="alergias">Alergias:</label>
                                                        <textarea class="form-control" id="alergias" name="alergias" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="condicion_medica">Condición Médica:</label>
                                                        <textarea class="form-control" id="condicion_medica" name="condicion_medica" rows="3"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="medicamentos">Medicamentos que usa:</label>
                                                        <textarea class="form-control" id="medicamentos" name="medicamentos" rows="3"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="siguiente('responsables')">Atrás</button>
                                            <button type="submit" class="btn btn-success">Guardar</button>
                                            <a href="<?php echo base_url(); ?>estudiantes" class=" btn btn-danger text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Cancelar</a>
                                        </div>



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
    $(document).ready(function() {
        $('#padre').select2({
            theme: "bootstrap-4",
            placeholder: "---Seleccione al padre---",
            allowClear: false,
            minimumInputLength: 0, // Permite mostrar todas las opciones al hacer clic
            width: '100%',
            ajax: {
                url: "<?= base_url('responsables/buscar'); ?>",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term || '' // Si no hay búsqueda, traer todos
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });
        $('.select2-container').addClass('form-control');
    });
</script>

<script>
    $(document).ready(function() {
        $('#madre').select2({
            theme: "bootstrap-4",
            placeholder: "---Seleccione a la madre---",
            allowClear: false,
            minimumInputLength: 0, // Permite mostrar todas las opciones al hacer clic
            width: '100%',
            ajax: {
                url: "<?= base_url('responsables/buscar'); ?>",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term || '' // Si no hay búsqueda, traer todos
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Agrega un evento para el cambio de selección
        $('#madre').on('change', function() {
            // Muestra en consola el valor seleccionado
            console.log("ID seleccionado para la madre:", $(this).val());
        });

        $('.select2-container').addClass('form-control');
    });
</script>


<script>
    $(document).ready(function() {
        $('#tutor').select2({
            theme: "bootstrap-4",
            placeholder: "---Seleccione al tutor---",
            allowClear: false,
            minimumInputLength: 0, // Permite mostrar todas las opciones al hacer clic
            width: '100%',
            ajax: {
                url: "<?= base_url('responsables/buscar'); ?>",
                type: "GET",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term || '' // Si no hay búsqueda, traer todos
                    };
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                cache: true
            }
        });
        $('.select2-container').addClass('form-control');
    });
</script>

<?= $this->endSection() ?>