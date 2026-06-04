<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class="fa-solid fa-school"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?php echo base_url(); ?>/escuela/" class=" btn btn-primary text-light "><i class="fa-solid fa-backward"></i> Regresar</a>

      </p>
    </div>
    <div class="">



      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center" id="datatablesSimple">
            <thead class="title-table">
              <tr>
                <th class="text-center">Nombre Escuela</th>
                <th class="text-center">Modalidad</th>
                <th class="text-center">Codigo SIGERD</th>
                <th class="text-center">Codigo del Plantel</th>
                <th class="text-center">RNC</th>
                <th class="text-center">email</th>
                <th class="text-center">Telefono</th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datos as $dato) { ?>
                <tr>
                  <td><?= $dato['nombre']; ?> </td>
                  <td><?= $dato['id_nivel']; ?> </td>
                  <td><?= $dato['id_modalidad']; ?></td>
                  <td><?= $dato['codigo_gestion']; ?> </td>
                  <td><?= $dato['codigo_plantel']; ?></td>
                  <td><?= $dato['rnc']; ?></td>
                  <td><?= $dato['email']; ?></td>
                  <td><?= $dato['telefono']; ?></td>

                  <td><a href="<?php echo base_url(); ?>/escuela/editar" class="text-primary"><i class="fa-solid fa-eye"></i></a><?php echo $dato['id']; ?></td>
                  <td><a href="<?php echo base_url() . '/escuela/restaurar/' . $dato['id']; ?>" class="text-danger"><i class="fa-solid fa-rotate-left"></i></a></td>

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
    </div>
</main>