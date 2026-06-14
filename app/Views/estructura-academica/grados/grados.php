<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">
      <i class="fa-solid fa-layer-group"></i>
      <?= esc($titulo_1) ?> / <small><?= esc($titulo_2) ?> / <?= esc($titulo_3) ?></small>
    </h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?= base_url('estructura-academica/grados/inactivos') ?>" class="btn btn-danger">
          <i class="fa-solid fa-file-circle-minus"></i> <b>GRADOS INACTIVOS</b>
        </a>
        <a href="<?= base_url('estructura-academica') ?>" class="btn btn-secondary">
          <i class="fa-solid fa-circle-left"></i> <b>REGRESAR</b>
        </a>
      </p>
    </div>
    <div class="">
      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center tabla-basica" id="tablaCursos">
            <thead class="title-table">
              <tr>
                <th class="text-center">GRADO</th>
                <th class="text-center"> ACCIONES</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($grados)): ?>
                <?php foreach ($grados as $grado): ?>
                  <tr>
                    <td><?= esc($grado['nombre_grado']); ?></td>
                    <td>
                      <a href="#"
                        class="accion-tabla text-danger"
                        onclick="confirmarInactivarGrado(<?= $grado['id']; ?>)"
                        title="Inactivar">
                        <i class="fa-solid fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="3">No hay grados registrados.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div>
    <h3 text-titles>Leyenda:</h3>
    <p>
      <i class="fa-solid fa-trash"></i> <strong>Inactivar</strong> –
      Inactiva el registro. <br> Esta acción no afecta registros existentes.
    </p>
  </div>

  </div>


</main>


<?= $this->section('scripts') ?>


<script>

</script>

<?= $this->endSection() ?>