<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">
      <i class="fa-solid fa-clipboard-check"></i> Calificaciones /
      <small><?= esc($titulo); ?></small>
    </h1>
  </div>
</div>

<br>

<div class="container-fluid px-4">

  <div class="row">

    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/registro') ?>" class="card modulo-card text-decoration-none">
        <div class="card-body text-center">
          <i class="fa-solid fa-pen-to-square fa-2x text-primary mb-3"></i>
          <h5>Registrar Calificaciones</h5>
          <p>Registro ordinario por curso, asignatura y período.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/tecnicas') ?>" class="card modulo-card text-decoration-none">
        <div class="card-body text-center">
          <i class="fa-solid fa-list-check fa-2x text-info mb-3"></i>
          <h5>Calificaciones Técnicas</h5>
          <p>Registro de módulos formativos por Resultados de Aprendizaje.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/configurarra') ?>" class="card modulo-card text-decoration-none">
        <div class="card-body text-center">
          <i class="fa-solid fa-list-check fa-2x text-info mb-3"></i>
          <h5>Calificaciones Técnicas</h5>
          <p>Registro de módulos formativos por Resultados de Aprendizaje.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/completivo') ?>" class="card modulo-card text-decoration-none">
        <div class="card-body text-center">
          <i class="fa-solid fa-rotate-right fa-2x text-warning mb-3"></i>
          <h5>Calificaciones Completivas</h5>
          <p>Registro de estudiantes enviados a completivo.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/extraordinario') ?>" class="card modulo-card text-decoration-none">
        <div class="card-body text-center">
          <i class="fa-solid fa-triangle-exclamation fa-2x text-danger mb-3"></i>
          <h5>Calificaciones Extraordinarias</h5>
          <p>Registro de evaluaciones extraordinarias.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/especiales') ?>" class="card modulo-card text-decoration-none">
        <div class="card-body text-center">
          <i class="fa-solid fa-star fa-2x text-secondary mb-3"></i>
          <h5>Calificaciones Especiales</h5>
          <p>Registro de casos especiales o situaciones particulares.</p>
        </div>
      </a>
    </div>

    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/reporte') ?>" class="card modulo-card text-decoration-none">
        <div class="card-body text-center">
          <i class="fa-solid fa-file-pdf fa-2x text-success mb-3"></i>
          <h5>Reporte de Calificaciones</h5>
          <p>Generar boletines o reportes temporales de prueba.</p>
        </div>
      </a>
    </div>

  </div>
</div>