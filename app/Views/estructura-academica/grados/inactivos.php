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
         <a href="<?= base_url('estructura-academica/grados') ?>" class="btn btn-secondary">
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
                        class="accion-tabla text-success"
                        onclick="confirmarRestaurarGrado(<?= $grado['id']; ?>)"
                        title="Inactivar">
                        <i class="fas fa-trash-restore"></i>
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
    <div>
      <h3 text-titles>Leyenda:</h3>
      <p>
        <i class="fas fa-trash-restore"></i> <strong>Restaurar</strong> – Restaura el registro
      </p>
    </div>

  </div>


</main>


<?= $this->section('scripts') ?>


<script>
  document.addEventListener('click', function(e) {
    const boton = e.target.closest('.btn-restaurar'); // delegación
    if (!boton) return;

    const id = boton.getAttribute('data-id');
    if (!id) return;

    Swal.fire({
      title: '¿Estás seguro?',
      text: "¡Este grado se reactivará!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, inactivar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "<?= base_url('grados-y-secciones/grados/restaurar_grado/') ?>" + id;
      }
    });
  });
</script>

<?= $this->endSection() ?>