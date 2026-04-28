<script>
  var baseUrl = "<?= base_url(); ?>";
</script>




<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">
      <i class="fa-solid fa-graduation-cap"></i>
      Configuración / <small><?= esc($titulo) ?></small>
    </h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <div class="mb-3">
      <a href="<?= base_url('grados-y-secciones/grados') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Regresar
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-striped text-center" id="datatablesSimple">
        <thead class="title-table">
          <tr>
            <th class="text-center">Grado</th>
            <th class="text-center">Activo</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($grados)): ?>
            <?php foreach ($grados as $grado): ?>
              <tr>
                <td><?= esc($grado['nombre_grado']); ?></td>
                <td>
                  <?= $grado['activo'] ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-danger">No</span>'; ?>
                </td>
                <td>
                  
                  <a href="javascript:void(0);"
                    class="text-danger btn-inactivar"
                    data-id="<?= $grado['id'] ?>">
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