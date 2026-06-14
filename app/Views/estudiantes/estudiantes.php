<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class="fa-solid fa-user-graduate"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?php echo base_url(); ?>estudiantes/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Agregar</a>
        <a href="<?php echo base_url(); ?>estudiantes/eliminados" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-user-minus"></i> Eliminados</a>
      </p>
    </div>
    <div class="">
      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped tabla-basica" id="">
            <thead class="title-table">
              <tr>
                <th class="text-center">Nombre</th>
                <th class="text-center">Padre, Madre o Tutor</th>
                <th class="text-center">NUI</th>
                <th class="text-center">Fecha de Nacimiento</th>
                <th class="text-center">Edad</th>
                <th class="text-center"> Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datos as $dato) { ?>
                <tr>
                  <td >
                    <?= esc(trim($dato['matricula'].' - '.$dato['nombre'] . ' ' . $dato['apellido'])); ?>
                  </td>
                  <td class="text-center"> <?php echo $dato['matricula'] ?> </td>
                  <td class="text-center"> <?php echo $dato['numero_identidad'] ?> </td>
                  <td> <?php echo $dato['fecha_nac'] ?></td>
                  <td><?= $dato['edad'] !== null ? esc($dato['edad']) . ' años' : '—'; ?></td>
                  <td>
                    <a href="#" class="text-primary" onclick="visualizarPersonal(<?= $dato['id']; ?>)"><i class="fa-solid fa-eye"> </i></a>
                    <a href="<?php echo base_url() . '/estudiantes/editar/' . $dato['id']; ?>" class="text-warning"><i class="fa-solid fa-edit"></i> </a>
                    <a href="<?php echo base_url() . '/personal/eliminar/' . $dato['id']; ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a>
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
    </div>
</main>