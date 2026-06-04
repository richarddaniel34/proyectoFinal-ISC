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
    <h1 class="text-titles">CALIFICACIONES/ <small><?= esc($titulo); ?></small></h1>
  </div>
</div>

<br>

<?php
$tipo_usuario = session('tipo_usuario');
$esDocente = ($tipo_usuario == 3);
?>

<div class="form-calificaciones-wrapper">
  <form action="<?= base_url('calificaciones/guardarNotas') ?>" method="POST" class="tabla-calificaciones-form">
    <?= csrf_field() ?>

    <input type="hidden" name="id_distribucion_asignatura" id="id_distribucion_asignatura">
    <div id="inscripciones-container"></div>

    <div class="form-row mb-3">

      <div class="col-12 col-sm-4">
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

      <div class="col-12 col-sm-4">
        <div class="form-group label-floating">
          <label for="curso" class="control-label">Curso:</label>
          <select id="curso" name="curso" class="form-control select2" required>
          </select>
        </div>
      </div>

      <div class="col-12 col-sm-4">
        <div class="form-group label-floating">
          <label for="asignatura" class="control-label">Asignatura:</label>
          <select id="asignatura" name="asignatura" class="form-control select2" required>
            <option value="">Seleccione una asignatura</option>
          </select>
        </div>

      </div>

    </div>

    <ul class="nav nav-tabs mb-3" id="periodosTabs" role="tablist">
      <?php foreach (['P1', 'P2', 'P3', 'P4'] as $i => $p): ?>
        <li class="nav-item">
          <a class="nav-link <?= $i === 0 ? 'active' : '' ?>"
            id="tab-<?= $p ?>"
            data-toggle="tab"
            href="#content-<?= $p ?>"
            role="tab">
            <?= $p ?>
          </a>
        </li>
      <?php endforeach; ?>

      <li class="nav-item">
        <a class="nav-link" id="tab-RES" data-toggle="tab" href="#content-RES" role="tab">
          Resumen PC
        </a>
      </li>
    </ul>

    <div class="tab-content" id="periodosContent">
      <?php foreach (['P1', 'P2', 'P3', 'P4'] as $i => $p): ?>
        <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>"
          id="content-<?= $p ?>"
          role="tabpanel">

          <div class="tabla-calificaciones-container">
            <table class="tabla-calificaciones table table-striped" id="tabla-<?= $p ?>">
              <thead></thead>
              <tbody></tbody>
            </table>
          </div>

        </div>
      <?php endforeach; ?>

      <div class="tab-pane fade" id="content-RES" role="tabpanel">
        <div class="tabla-calificaciones-container">
          <table class="tabla-calificaciones table table-striped" id="tabla-RES">
            <thead></thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <?php if ($esDocente): ?>
      <div class="form-calificaciones-footer mt-3">
        <button type="submit" class="btn btn-primary">
          Guardar Calificaciones
        </button>
      </div>
    <?php endif; ?>

  </form>
</div>

<input type="hidden" id="tipo_usuario" value="<?= esc($tipo_usuario) ?>">

<?php $funcion_usuario = session('funcion') ?? (session('usuario_data.funcion') ?? ''); ?>
<input type="hidden" id="funcion_usuario" value="<?= esc(strtolower($funcion_usuario)) ?>">

<input type="hidden" id="asignatura_es_tecnica" value="0">

<input type="hidden" id="buscar-cursos-url" value="<?= base_url('calificaciones/buscar-cursos/') ?>">
<input type="hidden" id="buscar-asignaturas-url" value="<?= base_url('calificaciones/buscar-asignaturas/') ?>">
<input type="hidden" id="buscar-docentes-url" value="<?= base_url('calificaciones/buscar-docentes') ?>">

<input type="hidden" id="schoolYear" value="<?= esc($id_schoolyear_actual) ?>">
<input type="hidden" id="listado-periodo-url" value="<?= base_url('calificaciones/listado-periodo') ?>">
<input type="hidden" id="estudiantes-curso-url" value="<?= base_url('calificaciones/estudiantes-por-curso/') ?>">
<input type="hidden" id="obtener-notas-url" value="<?= base_url('calificaciones/obtenerNotas') ?>">

<input type="hidden" id="csrf-token" value="<?= csrf_token() ?>">
<input type="hidden" id="csrf-hash" value="<?= csrf_hash() ?>">
<input type="hidden" id="competenciasJson" value='<?= json_encode($competencias ?? []) ?>'>

<?= $this->section('scripts') ?>
<script>
  (function initCompetencias() {
    try {
      const raw = document.getElementById('competenciasJson').value;
      window.competencias = JSON.parse(raw || '[]');
    } catch (e) {
      window.competencias = [];
    }
  })();
</script>

<script src="<?= base_url('js/modules/calificaciones/calificaciones.js') ?>"></script>
<?= $this->endSection() ?>