<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">
      <i class="fa-solid fa-money-bill-1-wave"></i>
      <?php print_r($titulo_1) ?>/ <small><?php print_r($titulo_2) ?></small>
    </h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">

    <div class="pagos-home-card">

      <div class="pagos-home-toolbar">
        <div>
          <h4>Panel de pagos</h4>
          <p>Consulta los pagos registrados y accede a los procesos principales.</p>
        </div>

        <div class="pagos-home-actions">
          <a href="<?= base_url('pagos/nueva_inscripcion'); ?>" class="btn btn-success btn-sistema">
            <i class="fa-solid fa-file-signature"></i>
            Nueva inscripción
          </a>

          <a href="<?= base_url('pagos/otros_pagos'); ?>" class="btn btn-primary btn-sistema">
            <i class="fa-solid fa-receipt"></i>
            Mensualidad
          </a>
        </div>
      </div>

      <div class="tab-pane" id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center tabla-basica">
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
                    <td>
                      <a href="<?= base_url('pagos/verFactura/' . $pago['id_factura']) ?>"
                        class="btn btn-info btn-sm"
                        title="Ver Factura">
                        <i class="fas fa-file-invoice"></i>
                      </a>

                      <a href="<?= base_url('pagos/imprimirFactura/' . $pago['id_factura']) ?>"
                        class="btn btn-primary btn-sm"
                        onclick="abrirFactura(event, this.href)"
                        title="Imprimir Factura">
                        <i class="fas fa-print"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="8" class="text-muted py-4">
                    No hay pagos registrados.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>

          </table>
        </div>
      </div>

      <div>
        <h3>Leyenda:</h3>
        <p>
          <i class="fas fa-file-invoice"></i> <strong>Ver detalle de transacción</strong> |
          <i class="fas fa-print"></i> <strong>Imprimir factura</strong>
        </p>
      </div>

    </div>

  </div>
</main>

<?= $this->section('scripts') ?>
<script>
  function abrirFactura(e, url) {
    e.preventDefault();

    window.open(
      url,
      'Factura',
      'width=800,height=600,scrollbars=yes,resizable=yes'
    );
  }
</script>
<?= $this->endSection() ?>