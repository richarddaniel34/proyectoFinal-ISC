<?php
$mensaje = session()->getFlashdata('mensaje');
$error = session()->getFlashdata('error');
?>

<?php if ($mensaje): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: <?= json_encode($mensaje) ?>,
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
<?php endif; ?>

<?php if ($error): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Registro no permitido',
                text: <?= json_encode($error) ?>,
                confirmButtonText: 'Aceptar'
            });
        });
    </script>
<?php endif; ?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-list-check"></i>
            CALIFICACIONES TÉCNICAS /
            <small><?= esc($titulo); ?></small>
        </h1>
    </div>
</div>

<div class="alert alert-info py-2">
    Solo se muestran las calificaciones registradas en el período seleccionado.
    Los RA sin calificación en este período aparecerán vacíos.
</div>
<br>

<?php
$tipo_usuario = session('tipo_usuario');
$esDocente = ($tipo_usuario == 3);
?>

<div class="form-calificaciones-wrapper">

    <form action="<?= base_url('calificaciones/guardarNotasTecnicas') ?>" method="POST" class="tabla-calificaciones-form formulario-personalizado">
        <?= csrf_field() ?>

        <input type="hidden" name="id_distribucion_asignatura" id="id_distribucion_asignatura">
        <div id="inscripciones-container"></div>

        <div class="card card-calificaciones mb-3">
            <div class="card-body">

                <div class="form-row mb-0">


                    <div class="col-12 col-sm-3">
                        <div class="form-group label-floating">
                            <label for="docente" class="control-label">Docente:</label>

                            <?php if ($esDocente): ?>
                                <input type="text"
                                    class="form-control"
                                    value="<?= esc(session('nombre_completo')); ?>"
                                    readonly>

                                <input type="hidden"
                                    name="docente"
                                    id="docente"
                                    value="<?= esc(session('usuario_data.personal_id')); ?>">
                            <?php else: ?>
                                <select id="docente"
                                    name="docente"
                                    class="form-control select2"
                                    required>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="form-group label-floating">
                            <label for="curso" class="control-label">Curso:</label>
                            <select id="curso"
                                name="curso"
                                class="form-control select2"
                                required>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="form-group label-floating">
                            <label for="asignatura" class="control-label">Módulo Técnico:</label>
                            <select id="asignatura"
                                name="asignatura"
                                class="form-control select2"
                                required>
                                <option value="">Seleccione un módulo</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden"
                        name="id_schoolyear"
                        id="schoolYear"
                        value="<?= esc($id_schoolyear_actual) ?>">

                    <div class="col-12 col-sm-3">
                        <div class="form-group label-floating">
                            <label for="periodo" class="control-label">Período:</label>

                            <select id="periodo"
                                name="periodo"
                                class="form-control"
                                required>

                                <option value="">Seleccione un período</option>

                                <?php foreach ($periodos as $periodo): ?>
                                    <option value="<?= $periodo['id'] ?>">
                                        <?= esc($periodo['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="tabla-calificaciones-container">
            <table class="tabla-calificaciones table table-striped table-bordered tabla-tecnica" id="tabla-tecnica">
                <thead>
                    <tr>
                        <th rowspan="3" class="col-numero">#</th>
                        <th rowspan="3" class="col-estudiante">Estudiante</th>

                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <th colspan="3" class="ra-titulo">%RA-<?= $i ?></th>
                        <?php endfor; ?>

                        <th rowspan="3" class="col-total">Total</th>
                    </tr>

                    <tr>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <th colspan="3" class="ra-config">
                                <div class="ra-config-grid">
                                    <div>
                                        <input type="number"
                                            id="valor-ra-<?= $i ?>"
                                            class="form-control form-control-sm valor-ra"
                                            name="ra[<?= $i ?>][valor]"
                                            data-ra="<?= $i ?>"
                                            min="0"
                                            max="100">
                                        <span>Valor</span>
                                    </div>

                                    <div>
                                        <input type="number"
                                            class="form-control form-control-sm minimo-ra"
                                            name="ra[<?= $i ?>][minimo]"
                                            id="minimo-ra-<?= $i ?>"
                                            readonly>
                                        <span>70%</span>
                                    </div>
                                </div>
                            </th>
                        <?php endfor; ?>
                    </tr>

                    <tr>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <th class="subcol-ra">CRA</th>
                            <th class="subcol-ra">RP1</th>
                            <th class="subcol-ra">RP2</th>
                        <?php endfor; ?>
                    </tr>
                </thead>

                <tbody>
                    <!-- Luego esto se llena con JS/AJAX -->
                </tbody>
            </table>
        </div>

        <?php if ($esDocente): ?>
            <div class="form-calificaciones-footer mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Guardar Calificaciones Técnicas
                </button>
            </div>
        <?php endif; ?>

    </form>

</div>

<input type="hidden" id="tipo_usuario" value="<?= esc($tipo_usuario) ?>">
<input type="hidden" id="asignatura_es_tecnica" value="1">

<input type="hidden" id="obtener-notas-tecnicas-url" value="<?= base_url('calificaciones/obtenerNotasTecnicas') ?>">

<input type="hidden" id="buscar-cursos-url" value="<?= base_url('calificaciones/buscar-cursos/') ?>">
<input type="hidden" id="buscar-asignaturas-url" value="<?= base_url('calificaciones/buscar-asignaturas/') ?>">
<input type="hidden" id="buscar-docentes-url" value="<?= base_url('calificaciones/buscar-docentes') ?>">

<input type="hidden" name="id_schoolyear" id="schoolYear" value="<?= esc($id_schoolyear_actual) ?>">
<input type="hidden" id="estudiantes-curso-url" value="<?= base_url('calificaciones/estudiantes-por-curso/') ?>">
<input type="hidden" id="obtener-notas-tecnicas-url" value="<?= base_url('calificaciones/obtenerNotasTecnicas') ?>">
<input type="hidden" id="obtener-ra-config-url" value="<?= base_url('calificaciones/obtener') ?>">

<input type="hidden" id="csrf-token" value="<?= csrf_token() ?>">
<input type="hidden" id="csrf-hash" value="<?= csrf_hash() ?>">

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/modules/calificaciones/tecnicas.js') ?>"></script>
<?= $this->endSection() ?>