<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">CALIFICACIONES/ <small><?= esc($titulo); ?></small></h1>
  </div>
</div>

<br>

<div class="form-calificaciones-wrapper">
  <form action="<?= base_url('calificaciones/guardarNotas') ?>" method="POST" class="tabla-calificaciones-form">
    <?= csrf_field() ?>
    <input type="hidden" name="id_distribucion_asignatura" id="id_distribucion_asignatura">

    <div class="form-row mb-3">
      <div class="col-md-3">
        <label for="docente">Docente:</label>
        <?php if (session('tipo_usuario') == 3): ?>
          <input type="text" class="form-control" value="<?= session('nombre_completo'); ?>" disabled>
          <input type="hidden" name="docente" id="docente" value="<?= session('usuario_data.personal_id'); ?>">
        <?php else: ?>
          <select id="docente" name="docente" class="form-control select2" required>
            <option value="">Seleccione un docente</option>
          </select>
        <?php endif; ?>
      </div>

      <div class="col-md-3">
        <label for="curso">Curso:</label>
        <select id="curso" name="curso" class="form-control select2" required>
          <option value="">Seleccione un curso</option>
        </select>
      </div>

      <div class="col-md-3">
        <label for="asignatura">Asignatura:</label>
        <select id="asignatura" name="asignatura" class="form-control select2" required>
          <option value="">Seleccione una asignatura</option>
        </select>
      </div>
    </div>

    <!-- Controles de bloqueo de periodos (solo admin/gestión) -->
    <?php if (in_array(session('tipo_usuario'), [1, 5])): ?>
      <div class="row mb-3">
        <?php foreach (['P1', 'P2', 'P3', 'P4'] as $p): ?>
          <?php $bloqueado = $estadoPeriodosDocente[$p]['bloqueado'] ?? true; ?>
          <div class="form-check form-check-inline">
            <input class="form-check-input chk-bloqueo" type="checkbox"
              id="bloq<?= $p ?>" data-periodo="<?= $p ?>"
              <?= $bloqueado ? 'checked' : '' ?>>
            <label class="form-check-label" for="bloq<?= $p ?>">Bloquear <?= $p ?></label>
          </div>
        <?php endforeach; ?>

        <div class="col-md-12 mt-3">
          <button type="button" id="guardar-configuracion" class="btn btn-success">
            <i class="fa fa-save"></i> Guardar Configuración
          </button>
        </div>
      </div>
    <?php endif; ?>

    <!-- Pestañas de periodos -->
    <ul class="nav nav-tabs mb-3" id="periodosTabs" role="tablist">
      <?php foreach (['P1', 'P2', 'P3', 'P4'] as $i => $p): ?>
        <li class="nav-item">
          <a class="nav-link <?= $i === 0 ? 'active' : '' ?>" id="tab-<?= $p ?>" data-toggle="tab" href="#content-<?= $p ?>" role="tab"><?= $p ?></a>
        </li>
      <?php endforeach; ?>
      <li class="nav-item">
        <a class="nav-link" id="tab-RES" data-toggle="tab" href="#content-RES" role="tab">Resumen PC</a>
      </li>
    </ul>

    <!-- Contenido de cada pestaña -->
    <div class="tab-content" id="periodosContent">
      <?php foreach (['P1', 'P2', 'P3', 'P4'] as $i => $p): ?>
        <div class="tab-pane fade <?= $i === 0 ? 'show active' : '' ?>" id="content-<?= $p ?>" role="tabpanel">
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

    <div class="form-calificaciones-footer mt-3">
      <button type="submit" class="btn btn-primary">Guardar Calificaciones</button>
    </div>
  </form>
</div>

<!-- Variables necesarias en HTML -->
<input type="hidden" id="tipo_usuario" value="<?= session('tipo_usuario') ?>">
<?php $funcion_usuario = session('funcion') ?? (session('usuario_data.funcion') ?? ''); ?>
<input type="hidden" id="funcion_usuario" value="<?= strtolower($funcion_usuario) ?>">

<!-- IMPORTANT: quitar el var_dump por seguridad -->
<!-- <pre><?= var_dump(session('usuario_data')) ?></pre> -->

<input type="hidden" id="asignatura_es_tecnica" value="0">

<input type="hidden" id="buscar-cursos-url" value="<?= base_url('calificaciones/buscar-cursos/') ?>">
<input type="hidden" id="buscar-asignaturas-url" value="<?= base_url('calificaciones/buscar-asignaturas/') ?>">
<input type="hidden" id="buscar-docentes-url" value="<?= base_url('calificaciones/buscar-docentes') ?>">
<input type="hidden" id="estado-periodo-url" value="<?= base_url('calificaciones/obtenerEstadoPeriodosDocente') ?>">
<input type="hidden" id="guardar-config-url" value="<?= base_url('calificaciones/guardar-configuracion-periodos') ?>">
<input type="hidden" id="schoolYear" value="<?= esc($id_schoolyear_actual) ?>">


<!-- NUEVO: endpoints para cargar filas por periodo -->
<input type="hidden" id="listado-periodo-url" value="<?= base_url('calificaciones/listado-periodo') ?>">

<input type="hidden" id="csrf-token" value="<?= csrf_token() ?>">
<input type="hidden" id="csrf-hash" value="<?= csrf_hash() ?>">
<input type="hidden" id="competenciasJson" value='<?= json_encode($competencias ?? []) ?>'>

<?= $this->section('scripts') ?>
<script>
  // Expone competencias como array de objetos {codigo_competencia:'C1', nombre:'...'}
  (function initCompetencias() {
    try {
      const raw = document.getElementById('competenciasJson').value;
      window.competencias = JSON.parse(raw || '[]');
    } catch (e) {
      window.competencias = [];
    }
  })();
</script>
<script src="<?= base_url('js/calificaciones.js') ?>"></script>
<?= $this->endSection() ?>