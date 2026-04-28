<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">CALIFICACIONES/ <small><?= esc($titulo); ?></small></h1>
  </div>
</div>

<br>

<div class="container mt-4">
  <div class="row">
    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/registro') ?>" class="btn btn-outline-primary btn-block">📝 Registro de Calificaciones</a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/completivo') ?>" class="btn btn-outline-secondary btn-block">🔁 Registro Completivo</a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/extraordinario') ?>" class="btn btn-outline-danger btn-block">🧪 Registro Extraordinario</a>
    </div>
    <div class="col-md-4 mb-3">
      <a href="<?= base_url('calificaciones/reporte') ?>" class="btn btn-outline-success btn-block">📊 Reporte de Calificaciones</a>
<!--Quiero usar ese calificaciones reporte, para generar uno temporal con el curso que guarde ayer y confirmar si esta bien-->
    </div>
  </div>
</div>


