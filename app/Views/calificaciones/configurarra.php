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
            <i class="fa-solid fa-clipboard-check"></i> <?= esc($titulo_1); ?> /
            <small><?= esc($titulo_2); ?></small>
        </h1>
    </div>
</div>


<br>

<?php
$tipo_usuario = session('tipo_usuario');
$esDocente = ($tipo_usuario == 3);
?>

<div class="form-calificaciones-wrapper">

    <form action="<?= base_url('calificaciones/guardarra') ?>" method="POST" class="tabla-calificaciones-form">
        <?= csrf_field() ?>

        <input type="hidden" name="id_distribucion_asignatura" id="id_distribucion_asignatura">
        <input type="hidden" name="id_schoolyear" id="schoolYear" value="<?= esc($id_schoolyear_actual) ?>">

        <div class="card card-calificaciones mb-3">
            <div class="card-body">

                <div class="form-row mb-0">

                    <div class="col-12 col-sm-4">
                        <div class="form-group label-floating">
                            <label for="docente" class="control-label">Docente:</label>

                            <?php if ($esDocente): ?>
                                <input type="text" class="form-control" value="<?= esc(session('nombre_completo')); ?>" readonly>
                                <input type="hidden" name="docente" id="docente" value="<?= esc(session('usuario_data.personal_id')); ?>">
                            <?php else: ?>
                                <select id="docente" name="docente" class="form-control select2" required></select>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <div class="form-group label-floating">
                            <label for="curso" class="control-label">Curso:</label>
                            <select id="curso" name="curso" class="form-control select2" required></select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <div class="form-group label-floating">
                            <label for="asignatura" class="control-label">Módulo Técnico:</label>
                            <select id="asignatura" name="asignatura" class="form-control select2" required>
                                <option value="">Seleccione un módulo</option>
                            </select>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="card card-calificaciones">
            <div class="card-body">

                <h5 class="mb-3">
                    <i class="fa-solid fa-sliders"></i>
                    Configuración de Resultados de Aprendizaje
                </h5>

                <div class="tabla-calificaciones-container">
                    <table class="tabla-calificaciones table table-striped table-bordered" id="tabla-config-ra">
                        <thead>
                            <tr>
                                <th>Resultado de Aprendizaje</th>
                                <th>Valor asignado</th>
                                <th>Mínimo aprobatorio 70%</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php for ($i = 1; $i <= 10; $i++): ?>
                                <tr>
                                    <td>
                                        <strong>%RA-<?= $i ?></strong>
                                        <input type="hidden" name="ra[<?= $i ?>][numero_ra]" value="<?= $i ?>">
                                    </td>

                                    <td>
                                        <input type="number"
                                            class="form-control valor-ra"
                                            name="ra[<?= $i ?>][valor]"
                                            data-ra="<?= $i ?>"
                                            min="0"
                                            max="100"
                                            step="1">
                                    </td>

                                    <td>
                                        <input type="number"
                                            class="form-control minimo-ra"
                                            name="ra[<?= $i ?>][minimo]"
                                            id="minimo-ra-<?= $i ?>"
                                            readonly>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge badge-secondary">Pendiente</span>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="alert alert-warning text-center mb-0" id="alert-total-ra">
                            <strong>Total configurado:</strong>
                            <span id="total-ra">0</span> / 100
                        </div>
                    </div>
                </div>

                <div class="form-calificaciones-footer mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Guardar Configuración
                    </button>
                </div>

            </div>
        </div>

    </form>

</div>

<input type="hidden" id="tipo_usuario" value="<?= esc($tipo_usuario) ?>">

<input type="hidden" id="buscar-cursos-url" value="<?= base_url('calificaciones/buscar-cursos/') ?>">
<input type="hidden" id="buscar-asignaturas-url" value="<?= base_url('calificaciones/buscar-asignaturas/') ?>">
<input type="hidden" id="buscar-docentes-url" value="<?= base_url('calificaciones/buscar-docentes') ?>">
<input type="hidden" id="obtener-ra-config-url" value="<?= base_url('configuracion-ra/obtener') ?>">

<input type="hidden" id="csrf-token" value="<?= csrf_token() ?>">
<input type="hidden" id="csrf-hash" value="<?= csrf_hash() ?>">

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/modules/calificaciones/configuracion-ra-tecnica.js') ?>"></script>
<?= $this->endSection() ?>