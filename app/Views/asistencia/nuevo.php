<script>
    var baseUrl = "<?= base_url(); ?>";
</script>

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
        <h1 class="text-titles"><i class="fa-solid fa-book"></i> <?php print_r($titulo_1) ?>/ <small><?php print_r($titulo_2) ?></small></h1>
    </div>
</div>

<?php
$tipo_usuario = session('tipo_usuario');
$esDocente = ($tipo_usuario == 3);
?>

<br>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-md-10 offset-md-1">
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>asistencia/insertar" autocomlete="off" class="formulario-personalizado">


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
                                                            <input type="text" class="form-control" value="<?= esc(session('nombre_completo')); ?>" readonly>
                                                            <input type="hidden" name="docente" id="docente" value="<?= esc(session('usuario_data.personal_id')); ?>">
                                                        <?php else: ?>
                                                            <select id="docente" name="docente" class="form-control select2" required>
                                                            </select>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="curso" class="control-label">Curso:</label>
                                                        <select id="curso" name="curso" class="form-control select2" required>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="asignatura" class="control-label">Asignatura:</label>
                                                        <select id="asignatura" name="asignatura" class="form-control select2" required>
                                                            <option value="">Seleccione una asignatura</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="asignatura" class="control-label">Fecha:</label>
                                                        <input class="form-control" type="date" name="fecha" id="fecha" required>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="tabla-calificaciones-container">
                                                <table class="tabla-asistencia table table-striped table-bordered" id="tabla-asistencia">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>NOMBRE DEL ESTUDIANTE</th>
                                                            <th>PRESENTE</th>
                                                            <th>AUSENTE</th>
                                                            <th>EXCUSA</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="tbody-asistencia">
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">
                                                                Seleccione docente, curso y asignatura para cargar los estudiantes.
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="acciones">
                                                <button type="submit" class="btn-guardar">
                                                    Guardar Asistencia
                                                </button>
                                            </div>
                                </form>
                            </div>
                            <input type="hidden" id="tipo_usuario" value="<?= esc($tipo_usuario) ?>">

                            <input type="hidden" id="buscar-cursos-url" value="<?= base_url('asistencia/buscar-cursos/') ?>">
                            <input type="hidden" id="buscar-asignaturas-url" value="<?= base_url('asistencia/buscar-asignaturas/') ?>">
                            <input type="hidden" id="buscar-docentes-url" value="<?= base_url('asistencia/buscar-docentes') ?>">

                            <input type="hidden" id="schoolYear" value="<?= esc($id_schoolyear_actual) ?>">
                            <input type="hidden" id="estudiantes-curso-url" value="<?= base_url('asistencia/estudiantes-por-curso/') ?>">

                            <input type="hidden" id="csrf-token" value="<?= csrf_token() ?>">
                            <input type="hidden" id="csrf-hash" value="<?= csrf_hash() ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>

<script src="<?= base_url('js/modules/asistencia/asistencia.js') ?>"></script>

<?= $this->endSection() ?>