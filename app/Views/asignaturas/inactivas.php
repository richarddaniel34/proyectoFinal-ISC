<script>
  var baseUrl = "<?= base_url(); ?>";
</script>


<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class="fa-solid fa-book"></i> <?php print_r($titulo_1) ?>/ <small><?php print_r($titulo) ?></small></h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?= base_url('asignaturas/') ?>" class="btn btn-secondary">
          <i class="fa-solid fa-circle-left"></i> <b>REGRESAR</b>
        </a>
        </p>
    </div>
    <div class="">



      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center tabla-basica" id="">
            <thead class="title-table">
              <tr>
                <th class="text-center">Nombre Asignatura</th>
                <th class="text-center">Codigo Asignatura</th>
                <th class="text-center">Tipo asignatura</th>
                <th class="text-center"> Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datos as $dato) { ?>
                <tr>
                  <td> <?php echo $dato['nombre'] ?> </td>
                  <td> <?php echo $dato['codigo_asignatura'] ?> </td>
                  <td> <?php echo $dato['tipo_asignatura_nombre'] ?> </td>
                  <td>
                    <a href="#"
                      class="text-success"
                      onclick="confirmarRestaurarAsignatura(<?= $dato['id']; ?>)">
                      <i class="fa-solid fa-trash-can-arrow-up"></i> 
                    </a>
                  </td>
                </tr>
              <?php } ?>

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
      <div>
        <h3>Leyenda:</h3>
        <p>
          <i class="fa-solid fa-trash-can-arrow-up"></i>  <strong>Restaurar</strong> – Restaurar el registro
        </p>
      </div>
    </div>
</main>

<?= $this->section('scripts') ?>

<script>
  function confirmarRestaurarAsignatura(id) {
    mostrarModalRestaurar({
      titulo: 'RESTAURAR ASIGNATURA',
      mensaje: '¿Está seguro que desea restaurar esta asignatura?',
      url: `${baseUrl}/asignaturas/restaurar/${id}`
    });
  }
</script>

<?= $this->endSection() ?>