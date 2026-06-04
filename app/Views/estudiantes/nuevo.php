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
        <h1 class="text-titles"><i class="fa-solid fa-user-graduate"></i> Registro/ <small><?php print_r($titulo) ?></small></h1>
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
                                <form method="POST" action="<?php echo base_url(); ?>estudiantes/insertar" enctype="multipart/form-data" autocomplete="off" class="formulario-personalizado" id="formulario-tabs" onsubmit="return validarFormularioCompleto();">
                                    <?= csrf_field() ?>
                                    <!-- Pestañas -->

                                    <ul class="nav nav-tabs" id="registroTabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="Datos-basicos-tab" data-toggle="tab" href="#datos-basicos">Datos Básicos</a>
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
                                        <!-- ESTUDIANTES -->
                                        <div class="tab-pane fade show active" id="datos-basicos">
                                            <h3>DATOS BASICOS DEL ESTUDIANTE</h3>
                                            <p class="lead">Los campos marcados con (<span class="text-danger">*</span>) son obligatorios</p>


                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="id_grado" class="control-label">
                                                            Grado al que Ingresa: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <select class="form-control is-filled" id="id_grado" name="id_grado">
                                                            <option value="" disabled <?= old('id_grado') ? '' : 'selected'; ?>>--Seleccione una opción--</option>
                                                            <?php foreach ($grados as $grado): ?>
                                                                <option value="<?= esc($grado['id']); ?>" <?= old('id_grado') == $grado['id'] ? 'selected' : ''; ?>>
                                                                    <?= esc($grado['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                    <input type="hidden" name="id_escuela" value="<?= esc(session()->get('id_escuela')); ?>">
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="nombre" class="control-label">
                                                            Nombre(s): <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input class="form-control" type="text" id="nombre" name="nombre" value="<?= old('nombre'); ?>" required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido" class="control-label">
                                                            Apellido(s): <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input class="form-control" type="text" id="apellido" name="apellido" value="<?= old('apellido'); ?>" required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="sexo" class="control-label">
                                                            Sexo: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <select id="sexo" name="sexo" class="form-control">
                                                            <option value="" disabled <?= old('sexo') ? '' : 'selected'; ?>>--Seleccione una opción--</option>
                                                            <option value="M" <?= old('sexo') == 'M' ? 'selected' : ''; ?>>M</option>
                                                            <option value="F" <?= old('sexo') == 'F' ? 'selected' : ''; ?>>F</option>
                                                        </select>
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="fecha_nac" class="control-label">
                                                            Fecha de Nacimiento: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input class="form-control" type="date" id="fecha_nac" name="fecha_nac" value="<?= old('fecha_nac'); ?>" required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating is-filled">
                                                        <label for="lugar_nacimiento" class="control-label">
                                                            Lugar de Nacimiento: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input class="form-control" type="text" id="lugar_nacimiento" name="lugar_nacimiento" value="<?= old('lugar_nacimiento'); ?>" required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="provincia" class="control-label">
                                                            Provincia: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input class="form-control" type="text" id="provincia" name="provincia" value="<?= old('provincia'); ?>" required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="id_nacionalidad" class="control-label">
                                                            Nacionalidad: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <select class="form-control <?= session('errors.id_nacionalidad') ? 'is-invalid' : '' ?>" id="id_nacionalidad" name="id_nacionalidad">
                                                            <option value="" disabled <?= old('id_nacionalidad') ? '' : 'selected'; ?>>--Seleccione una opción--</option>
                                                            <?php foreach ($nacionalidades as $nac): ?>
                                                                <option value="<?= esc($nac['id']); ?>" <?= old('id_nacionalidad') == $nac['id'] ? 'selected' : ''; ?>>
                                                                    <?= esc($nac['gentilicio']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <div class="error-message text-danger small mt-1"></div>

                                                        <?php if (session('errors.id_nacionalidad')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.id_nacionalidad'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="numero_identidad" class="control-label">
                                                            Numero Unico de Identidad (NUI): <small class="text-primary">(solo dominicanos)</small>
                                                        </label>

                                                        <input
                                                            required
                                                            class="form-control <?= session('errors.numero_identidad') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="numero_identidad"
                                                            name="numero_identidad"
                                                            value="<?= old('numero_identidad'); ?>"
                                                            <?= (isset($nacionalidad) && $nacionalidad != 1) ? 'disabled' : '' ?> />

                                                        <div class="error-message text-danger mt-1"></div>

                                                        <?php if (session('errors.numero_identidad')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.numero_identidad'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="sigerd_id" class="control-label">ID SIGERD:</label>

                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="sigerd_id"
                                                            name="sigerd_id" />

                                                        <small class="error-message text-danger"></small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion" class="control-label">Dirección: <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control" type="text" id="direccion" name="direccion" required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="escuela_procedencia" class="control-label">Escuela de Procedencia:</label>
                                                        <input class="form-control" type="text" id="escuela_procedencia" name="escuela_procedencia" />
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="foto" class="control-label">Foto:</label>
                                                        <input type="file" name="foto" id="foto" class="form-control file-input" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="matricula" class="control-label">Matricula:</label>
                                                        <input class="form-control" type="text" id="matricula" name="matricula" value="<?= esc($matricula); ?>" readonly />
                                                    </div>
                                                </div>
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
                                            <br>
                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-primary" onclick="if(validarPestanaDatosBasicos()){ siguiente('responsable'); }">
                                                    Siguiente
                                                    <i class="fa-solid fa-forward"></i></button>

                                                <button type="button" class="btn btn-danger btn-cancelar" id="btn-cancelar-1">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Responsables -->
                                        <div class="tab-pane fade" id="responsable">
                                            <h3>RESPONSABLES</h3>
                                            <p class="lead">Es necesario un registro como minimo para porder avanzar</p>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="padre" class="control-label">Padre:</label>
                                                        <select id="padre" name="padre" class="form-control select2">
                                                            <option value="">--Seleccione una opcion--</option>
                                                        </select>
                                                        <div class="error-message text-danger mt-1"></div>
                                                    </div>
                                                </div>

                                                <!--SELECT DE PARENTESCO PADRE-->

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
                                                            <option value="">--Seleccione una opcion--</option>
                                                        </select>
                                                        <div class="error-message text-danger mt-1"></div>
                                                    </div>
                                                </div>
                                                <!--SELECT DE PARENTESCO MADRE-->

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
                                                            <option value="">--Seleccione una opcion--</option>
                                                        </select>
                                                        <small>Opcional</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="parentesco_tutor" class="control-label">Parentesco</label>
                                                        <select name="parentesco_tutor" class="form-control">
                                                            <option value="">--Seleccione una opcion--</option>
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
                                                            <option value="">--Seleccione una opción--</option>
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
                                                            <option value="">--Seleccione una opción--</option>
                                                            <option value="con ambos padres">Con ambos Padres</option>
                                                            <option value="Padre">Padre</option>
                                                            <option value="madre">Madre</option>
                                                            <option value="tutor">tutor</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-secondary" onclick="siguiente('basicos')"><i class="fa-solid fa-backward"></i> Atrás</button>
                                                <button type="button" class="btn btn-primary" onclick="if(validarPestanaResponsables()){ siguiente('salud'); }">Siguiente <i class="fa-solid fa-forward"></i></button>
                                                <button type="button" class="btn btn-danger btn-cancelar" id="btn-cancelar-1">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Datos medicos -->
                                        <div class="tab-pane fade" id="salud">
                                            <h3>DATOS MEDICOS Y DE SALUD</h3>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="tipo_sangre">Tipo de Sangre:</label>
                                                        <select class="form-control" id="tipo_sangre" name="tipo_sangre">
                                                            <option value="">--Seleccione una opcion--</option>
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

                                            <div class="text-center mt-4">

                                                <button type="button" class="btn btn-secondary" onclick="siguiente('responsables')"><i class="fa-solid fa-backward"></i> Atrás</button>
                                                <button type="submit" class="btn btn-success"> <i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                                                <button type="button" class="btn btn-danger btn-cancelar" id="btn-cancelar-1">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </button>
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
</div>
</div>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {
        // IDs de los selects que usarán AJAX
        const selectsAjax = ['#padre', '#madre', '#tutor'];

        selectsAjax.forEach(function(selector) {
            const $select = $(selector);

            $select.select2({
                theme: 'bootstrap4', // tema base, compatible
                placeholder: "--Seleccione una opción--",
                allowClear: false,
                minimumInputLength: 0,
                width: '100%',
                ajax: {
                    url: "<?= base_url('responsables/buscar'); ?>",
                    type: "GET",
                    dataType: "json",
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term || ''
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

            // Aplicar los estilos personalizados del formulario
            const select2Container = $select.next('.select2-container');
            select2Container.addClass('form-control'); // agrega clase base
            select2Container.css({
                'background-color': 'white',
                'border': 'none',
                'border-bottom': '1px solid #d2d2d2',
                'border-radius': '0',
                'box-shadow': 'none',
                'color': '#000',
                'font-size': '14px',
                'height': '36px',
                'line-height': '1.2',
                'padding': '6px 10px'
            });

            // Opcional: manejar focus/hover similar a tus selects normales
            select2Container.find('.select2-selection').on('focus', function() {
                $(this).css('border-bottom', '2px solid #0b1065');
            });

            // Ejemplo: evento adicional para #madre
            if (selector === '#madre') {
                $select.on('change', function() {
                    console.log("ID seleccionado para la madre:", $(this).val());
                });
            }
        });
    });

    /**MODAL DE CONFIRMACION PARA CANCELACION DE UN REGISTRO */

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
                    window.location.href = "<?= base_url('estudiantes') ?>";
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>