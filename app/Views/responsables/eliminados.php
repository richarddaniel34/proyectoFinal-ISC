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
      <a href="<?php echo base_url(); ?>responsables/" class=" btn btn-primary text-light "><i class="fa-solid fa-backward"></i> Regresar</a>
      </p>
    </div>
    <div class="">
      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center" id="datatablesSimple">
            <thead class="title-table">
              <tr>
                <th class="text-center">Nombre (s)</th>
                <th class="text-center">Apellido (s)</th>
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
                  <td> <?php echo $dato['nombre_padre'] ?></td>
                  <td> <?php echo $dato['apellido_padre']?></td>
                  <td> </td>
                  <td> </td>
                  <td> </td>
                  <td><a href="#" class="text-primary" onclick="visualizarPersonal(<?= $dato['id']; ?>)"><i class="fa-solid fa-eye"></i></a></td>
                  <td><a href="<?php echo base_url() . 'responsables/restaurar/' . $dato['id']; ?>" class="text-danger"><i class="fa-solid fa-rotate-left"></i></a></td>

                </tr>
              <?php } ?>

            </tbody>
          </table>

        </div>
      </div>
    </div>
</main>


