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
        <h1 class="text-titles"><i class="fa-solid fa-school"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
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
                            <form method="POST" action="<?= base_url('asistencias/insertar'); ?>" autocomplete="off">

        <!-- Selección de Curso y Asignatura -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_curso" class="control-label">Curso:</label>
                    <select class="form-control" id="id_curso" name="id_curso" required>
                        <option value="" disabled selected>Seleccione un curso</option>
                        <?php foreach ($cursos as $curso): ?>
                            <option value="<?= esc($curso['id']); ?>">
                                <?= esc($curso['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_asignatura" class="control-label">Asignatura:</label>
                    <select class="form-control" id="id_asignatura" name="id_asignatura" required>
                        <option value="" disabled selected>Seleccione una asignatura</option>
                        <?php foreach ($asignaturas as $asignatura): ?>
                            <option value="<?= esc($asignatura['id']); ?>">
                                <?= esc($asignatura['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <!-- Tabla de Asistencia -->
        <div class="table-responsive">
            <table class="table table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Estudiante</th>
                        <th>Estado</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; foreach ($estudiantes as $estudiante): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td>
                                <?= esc($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?>
                                <input type="hidden" name="id_estudiante[]" value="<?= esc($estudiante['id']); ?>">
                            </td>
                            <td>
                                <select class="form-control" name="estado[]" required>
                                    <option value="Presente">Presente</option>
                                    <option value="Tarde">Tarde</option>
                                    <option value="Ausente">Ausente</option>
                                    <option value="Justificado">Justificado</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="observaciones[]" placeholder="Opcional">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk"></i> Guardar Asistencia
            </button>

            <a href="<?= base_url('asistencias'); ?>" class="btn btn-danger">
                <i class="fa-solid fa-ban"></i> Cancelar
            </a>
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