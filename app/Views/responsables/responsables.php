<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class=" fa-solid fa-users"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?php echo base_url(); ?>responsables/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Agregar</a>
        <a href="<?php echo base_url(); ?>responsables/eliminados" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-user-minus"></i> Eliminados</a>
      </p>
    </div>
    <div class="">
      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-left" id="datatablesSimple">
            <thead class="title-table">
              <tr>
                <th class="text-center">Nombre y Apellidos (s)</th>
                <th class="text-center">Cedula</th>
                <th class="text-center">Celular</th>
                <th class="text-center">Direccion</th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datos as $dato) { ?>
                <tr>
                <td class="text-start"> 
                  <b>Padre:</b> <?php echo $dato['nombre_padre']?> <br>
                  <b>Madre:</b> <?php echo $dato['nombre_madre']?> <br>
                  <b>Tutor:</b> <?php echo $dato['nombre_tutor']?>
                 </td>
                  <td>
                    <b>Padre:</b> <?php echo $dato['cedula_padre']?> <br>
                    <b>Madre:</b> <?php echo $dato['cedula_madre']?> <br>
                    <b>Tutor:</b> <?php echo $dato['cedula_tutor']?>
                  </td>
                  <td>  </td>
                  <td>  </td>
                  
                  <td><a href="#" class="text-primary" onclick="visualizarPersonal(<?= $dato['id']; ?>)"><i class="fa-solid fa-eye"></i></a></td>
                  <td><a href="<?php echo base_url() . 'responsables/editar/' . $dato['id']; ?>" class="text-warning"><i class="fa-solid fa-edit"></i></a></td>
                  <td><a href="<?php echo base_url() . 'responsables/eliminar/' . $dato['id']; ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a></td>

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