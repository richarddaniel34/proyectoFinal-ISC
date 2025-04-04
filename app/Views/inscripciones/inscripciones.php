<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class="fa-solid fa-calendar-days"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?php echo base_url(); ?>inscripciones/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Agregar</a>
        <a href="<?php echo base_url(); ?>schoolyear/eliminados" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-user-minus"></i> Eliminados</a>
      </p>
    </div>
    <div class="">
      <div class="tab-pane " id="list">
        <div class="table-responsive">
        <table class="table table-hover table-striped text-center" id="datatablesSimple">
  <thead class="title-table">
    <tr>
      <th class="text-center">Responsable</th>
      <th class="text-center">Estudiante</th>
      <th class="text-center">Concepto</th>
      <th class="text-center">Monto</th>
      <th class="text-center">Método de Pago</th>
      <th class="text-center">Estado</th>
      <th class="text-center">Fecha de Pago</th>
      <th class="text-center">Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($pagos)) : ?>
      <?php foreach ($pagos as $pago) : ?>
        <tr>
          <td><?= esc($pago['responsable']) ?></td>
          <td><?= esc($pago['estudiante']) ?></td>
          <td><?= esc($pago['concepto_pago']) ?></td>
          <td>RD$ <?= esc(number_format($pago['monto'], 2)) ?></td>
          <td><?= esc($pago['metodo_pago']) ?></td>
          <td>
            <?php if ($pago['estado'] === 'Pago') : ?>
              <span class="badge bg-success">Pago</span>
            <?php elseif ($pago['estado'] === 'Pendiente') : ?>
              <span class="badge bg-warning text-dark">Pendiente</span>
            <?php else : ?>
              <span class="badge bg-danger">Anulado</span>
            <?php endif; ?>
          </td>
          <td><?= esc(date('d/m/Y', strtotime($pago['fecha_pago']))) ?></td>
          <!-- En la tabla donde muestras las inscripciones o pagos, añade una columna para acciones -->
          <td>
              <a href="<?= base_url('inscripciones/verFactura/' . $pago['id_factura']) ?>" class="btn btn-info btn-sm" title="Ver Factura">
                  <i class="fas fa-file-invoice"></i>
              </a>
              <a href="<?= base_url('inscripciones/imprimirFactura/' . $pago['id_factura']) ?>" class="btn btn-primary btn-sm" target="_blank" title="Imprimir Factura">
                  <i class="fas fa-print"></i>
              </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="8">No hay pagos registrados.</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>



          <!--
                    <nav aria-label="Pagination">
                        <ul class="pagination pagination-sm">
                          <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">«</a>
                          </li>
                          <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">1</a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">2</a></li>
                          <li class="page-item"><a class="page-link" href="#">3</a></li>
                          <li class="page-item"><a class="page-link" href="#">4</a></li>
                          <li class="page-item"><a class="page-link" href="#">5</a></li>
                          <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                              <span aria-hidden="true">»</span>
                              <span class="visually-hidden">Next</span>
                            </a>
                          </li>
                        </ul>
                    </nav>
                        -->
        </div>
      </div>
    </div>
</main>