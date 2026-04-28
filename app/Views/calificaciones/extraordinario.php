<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">Calificaciones / <small><?= esc($titulo); ?></small></h1>
  </div>
</div>

<br>

<div class="form-calificaciones-wrapper">
  <form action="/guardar-extraordinario" method="POST" class="tabla-calificaciones-form">

    <div class="form-row mb-3">
      <div class="col-md-3">
        <label for="docente">Docente:</label>
        <?php if (session('tipo_usuario') == 3): ?>
          <input type="text" class="form-control" value="<?= session('nombre_completo'); ?>" disabled>
          <input type="hidden" name="docente" id="docente" value="<?= session('usuario_id'); ?>">
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

    <div class="tabla-calificaciones-container">
      <table class="tabla-calificaciones" id="tabla-estudiantes">
        <thead>
          <tr>
            <th>No.</th>
            <th>Alumno</th>
            <th>30%</th>
            <th>70%</th>
            <th>C.E.F.</th>
          </tr>
        </thead>
        <tbody>
          <!-- Se cargan dinámicamente con AJAX -->
        </tbody>
      </table>
    </div>

    <div class="form-calificaciones-footer">
      <button type="submit" class="btn-enviar-calif">Guardar Extraordinario</button>
    </div>
  </form>
</div>
