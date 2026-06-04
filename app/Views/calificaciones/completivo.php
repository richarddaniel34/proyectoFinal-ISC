<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">REGISTRO DE COMPLETIVA / <small><?= esc($titulo); ?></small></h1>
  </div>
</div>

<br>

<?php
$tipo_usuario = session('tipo_usuario');
$esDocente = ($tipo_usuario == 3);
?>

<div class="form-calificaciones-wrapper">
  <form action="<?= base_url('calificaciones/guardarCompletivo') ?>" method="POST" class="tabla-calificaciones-form">

    <input type="hidden" name="id_distribucion_asignatura" id="id_distribucion_asignatura">
    <div id="inscripciones-container"></div>
    <div class="form-row mb-3">

      <div class="col-md-3">
        <label for="docente">Docente:</label>

        <?php if ($esDocente): ?>
          <input type="text" class="form-control" value="<?= esc(session('nombre_completo')); ?>" readonly>
          <input type="hidden" name="docente" id="docente" value="<?= esc(session('usuario_data.personal_id')); ?>">
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

      <div class="col-md-3">
        <label for="btn-cargar" style="visibility: hidden;">Cargar</label>
        <button type="button" id="btn-cargar" class="btn btn-primary btn-block mt-3">
          <i class="fa fa-download"></i> Cargar
        </button>
      </div>
    </div>

    <div class="tabla-calificaciones-container mt-3">
      <table class="tabla-calificaciones table table-striped" id="tabla-completivo">
        <thead></thead>
        <tbody></tbody>
      </table>
    </div>

    <div class="form-calificaciones-footer">
      <button type="submit" class="btn-enviar-calif">Guardar Completiva</button>
    </div>
  </form>
</div>



<input type="hidden" id="buscar-cursos-url" value="<?= base_url('calificaciones/buscar-cursos/') ?>">
<input type="hidden" id="buscar-asignaturas-url" value="<?= base_url('calificaciones/buscar-asignaturas/') ?>">
<input type="hidden" id="buscar-docentes-url" value="<?= base_url('calificaciones/buscar-docentes') ?>">
<input type="hidden" id="estudiantes-completivo-url" value="<?= base_url('calificaciones/estudiantes-completivo') ?>">
<input type="hidden" id="tipo_usuario" value="<?= esc($tipo_usuario) ?>">

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

<script src="<?= base_url('js/modules/calificaciones/completivo.js') ?>"></script>
<?= $this->endSection() ?>