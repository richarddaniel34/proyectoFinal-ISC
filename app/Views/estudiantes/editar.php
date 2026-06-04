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
                                <form method="POST" action="<?php echo base_url(); ?>estudiantes/actualizar" enctype="multipart/form-data" autocomplete="off" class="formulario-personalizado" id="formulario-tabs" onsubmit="return validarFormularioCompleto();">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />
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
                                        <!-- ESTUDIANTES -->
                                        <div class="tab-pane fade show active" id="basicos">
                                            <h3>DATOS BASICOS DEL ESTUDIANTE</h3>
                                            <p class="lead">Los campos marcados con (<span class="text-danger">*</span>) son obligatorios</p>


                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="id_grado" class="control-label">
                                                            Grado al que Ingresa: <small class="obligatorio-formulario">*</small>
                                                        </label>


                                                        <select class="form-control" id="id_grado" name="id_grado">
                                                            <option value="">--Seleccione una opción--</option>

                                                            <?php foreach ($grados as $grado): ?>
                                                                <option value="<?= esc($grado['id']); ?>"
                                                                    <?= old('id_grado', $datos['id_grado'] ?? '') == $grado['id'] ? 'selected' : ''; ?>>

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

                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="nombre"
                                                            name="nombre"
                                                            value="<?= old('nombre', $datos['nombre'] ?? ''); ?>"
                                                            required />

                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido" class="control-label">
                                                            Apellido(s): <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="apellido"
                                                            name="apellido"
                                                            value="<?= old('apellido', $datos['apellido'] ?? ''); ?>" required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="sexo" class="control-label">
                                                            Sexo: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <select class="form-control" id="sexo" name="sexo" required>
                                                            <option value="">--Seleccione una opción--</option>
                                                            <option value="M" <?= old('sexo', $datos['sexo'] ?? '') === 'M' ? 'selected' : ''; ?>>Masculino</option>
                                                            <option value="F" <?= old('sexo', $datos['sexo'] ?? '') === 'F' ? 'selected' : ''; ?>>Femenino</option>
                                                        </select>
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="fecha_nac" class="control-label">
                                                            Fecha de Nacimiento: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input
                                                            class="form-control"
                                                            type="date"
                                                            id="fecha_nac"
                                                            name="fecha_nac"
                                                            value="<?= old('fecha_nac', $datos['fecha_nac'] ?? ''); ?>"
                                                            required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating is-filled">
                                                        <label for="lugar_nacimiento" class="control-label">
                                                            Lugar de Nacimiento: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="lugar_nacimiento"
                                                            name="lugar_nacimiento"
                                                            value="<?= old('lugar_nacimiento', $datos['lugar_nacimiento'] ?? ''); ?>"
                                                            required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="provincia" class="control-label">
                                                            Provincia: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="provincia"
                                                            name="provincia"
                                                            value="<?= old('provincia', $datos['provincia'] ?? ''); ?>"
                                                            required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group">
                                                        <label for="id_nacionalidad" class="control-label">
                                                            Nacionalidad: <small class="obligatorio-formulario">*</small>
                                                        </label>
                                                        <select class="form-control" id="id_nacionalidad" name="id_nacionalidad">
                                                            <option value="">--Seleccione una opción--</option>
                                                            <?php foreach ($nacionalidades as $nac): ?>
                                                                <option value="<?= esc($nac['id']); ?>"
                                                                    <?= old('id_nacionalidad', $datos['id_nacionalidad'] ?? '') == $nac['id'] ? 'selected' : ''; ?>>
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
                                                            value="<?= old('numero_identidad', $datos['numero_identidad'] ?? ''); ?>"
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
                                                            value="<?= old('sigerd_id', $datos['sigerd_id'] ?? ''); ?>"
                                                            name="sigerd_id" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion" class="control-label">Dirección: <small class="obligatorio-formulario">*</small></label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="direccion"
                                                            name="direccion"
                                                            value="<?= old('direccion', $datos['direccion'] ?? ''); ?>"
                                                            required />
                                                        <div class="error-message text-danger small mt-1"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="escuela_procedencia" class="control-label">Escuela de Procedencia:</label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="escuela_procedencia"
                                                            value="<?= old('escuela_procedencia', $datos['escuela_procedencia'] ?? ''); ?>"
                                                            name="escuela_procedencia" />
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

                                                            <?php if (!empty($padreData)): ?>
                                                                <option value="<?= esc($padreData['id']); ?>" selected>
                                                                    <?= esc(trim($padreData['nombre'] . ' ' . $padreData['apellido'])); ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        </select>
                                                        <div class="error-message text-danger mt-1"></div>
                                                    </div>
                                                </div>

                                                <!--SELECT DE PARENTESCO PADRE-->

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="observaciones_padre" class="control-label">Observaciones:</label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="observaciones_padre"
                                                            value="<?= old('observaciones_padre', $datos['observaciones_padre'] ?? ''); ?>"
                                                            name="observaciones_padre" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="madre" class="control-label">Madre:</label>
                                                        <select id="madre" name="madre" class="form-control select2">
                                                            <option value="">--Seleccione una opcion--</option>

                                                            <?php if (!empty($madreData)): ?>
                                                                <option value="<?= esc($madreData['id']); ?>" selected>
                                                                    <?= esc(trim($madreData['nombre'] . ' ' . $madreData['apellido'])); ?>
                                                                </option>
                                                            <?php endif; ?>
                                                        </select>
                                                        <div class="error-message text-danger mt-1"></div>
                                                    </div>
                                                </div>
                                                <!--SELECT DE PARENTESCO MADRE-->

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="observaciones_madre" class="control-label">Observaciones:</label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="observaciones_madre"
                                                            value="<?= old('observaciones_madre', $datos['observaciones_madre'] ?? ''); ?>"
                                                            name="observaciones_madre" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="tutor" class="control-label">tutor:</label>
                                                        <select id="tutor" name="tutor" class="form-control select2">
                                                            <option value="">--Seleccione una opcion--</option>

                                                            <?php if (!empty($tutorData)): ?>
                                                                <option value="<?= esc($tutorData['id']); ?>" selected>
                                                                    <?= esc(trim($tutorData['nombre'] . ' ' . $tutorData['apellido'])); ?>
                                                                </option>
                                                            <?php endif; ?>
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
                                                                <option value="<?= esc($parentesco) ?>"
                                                                    <?= old('parentesco_tutor', $parentescoTutor ?? '') == $parentesco ? 'selected' : ''; ?>>
                                                                    <?= esc($parentesco) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="observaciones_tutor" class="control-label">Observaciones:</label>
                                                        <input
                                                            class="form-control"
                                                            type="text"
                                                            id="observaciones_tutor"
                                                            value="<?= old('observaciones_tutor', $datos['observaciones_tutor'] ?? ''); ?>"
                                                            name="observaciones_tutor" />
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="estado_padres" class="control-label">Estado Padres:</label>
                                                        <select id="estado_padres" name="estado_padres" class="form-control">
                                                            <option value="">--Seleccione una opción--</option>
                                                            <?php foreach ($estadosPadres as $valor => $texto): ?>
                                                                <option value="<?= esc($valor); ?>"
                                                                    <?= old('estado_padres', $datos['estado_padres'] ?? '') == $valor ? 'selected' : ''; ?>>
                                                                    <?= esc($texto); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="casa_estudiante" class="control-label">Con quien vice el estudiante:</label>
                                                        <select id="casa_estudiante" name="casa_estudiante" class="form-control">
                                                            <option value="">--Seleccione una opción--</option>
                                                            <?php foreach ($convivenciaEstudiante as $valor => $texto): ?>
                                                                <option value="<?= esc($valor); ?>"
                                                                    <?= old('casa_estudiante', $datos['casa_estudiante'] ?? '') == $valor ? 'selected' : ''; ?>>
                                                                    <?= esc($texto); ?>
                                                                </option>
                                                            <?php endforeach; ?>
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
                                                            <?php foreach ($tipoSangreEstudiante as $valor => $texto): ?>
                                                                <option value="<?= esc($valor); ?>"
                                                                    <?= old('tipo_sangre', $datos['tipo_sangre'] ?? '') == $valor ? 'selected' : ''; ?>>
                                                                    <?= esc($texto); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="alergias">Alergias:</label>
                                                        <textarea class="form-control" name="alergias" id="alergias" rows="3"><?= old('alergias', $datos['alergias'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="condicion_medica">Condición Médica:</label>
                                                        <textarea class="form-control" id="condicion_medica" name="condicion_medica" rows="3"><?= old('condicion_medica', $datos['condicion_medica'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label class="control-label" for="medicamentos">Medicamentos que usa:</label>
                                                        <textarea class="form-control" id="medicamentos" name="medicamentos" rows="3"><?= old('medicamentos', $datos['medicamentos'] ?? ''); ?></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-center mt-4">

                                                <button type="button" class="btn btn-primary" onclick="if(validarPestanaDatosBasicos()){ siguiente('responsable'); }">
                                                    Siguiente
                                                    <i class="fa-solid fa-forward"></i></button>

                                                <button type="submit" class="btn btn-success"> <i class="fa-solid fa-floppy-disk"></i> Actuaizar</button>
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