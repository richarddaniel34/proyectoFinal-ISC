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
        <a href="<?php echo base_url(); ?>asignaturas/nuevo" class=" btn btn-primary text-light" id="plus-user"><i class="fa-solid fa-file-circle-plus"></i> AGREGAR ASIGNATURA</a>
        <a href="<?php echo base_url(); ?>asignaturas/inactivas" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-file-circle-minus"></i> ASIGNATURAS INACTIVAS</a>
        <a href="<?php echo base_url(); ?>distribucion-asignaturas" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-diagram-project"></i> DISTRIBUCIÓN ACADEMICA</a>
      </p>
    </div>
    <div class="">



      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center" id="datatablesSimple">
            <thead class="title-table">
              <tr>
                <th class="text-center">Nombre Asignatura</th>
                <th class="text-center">Codigo Asignatura</th>
                <th class="text-center">Tipo asignatura</th>
                <th class="text-center"></th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datos as $dato) { ?>
                <tr>
                  <td> <?php echo $dato['nombre'] ?> </td>
                  <td> <?php echo $dato['codigo_asignatura'] ?> </td>
                  <td> <?php echo $dato['tipo_asignatura_nombre'] ?> </td>
                  <td><a href="#" class="text-warning" onclick="editarAsignatura(<?= $dato['id']; ?>)"><i class="fa-solid fa-edit"></i></a></td>
                  <td>
                  <a href="#"
                    class="text-danger"
                    onclick="confirmarInactivarAsignatura(<?= $dato['id']; ?>)">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                  </td>

                </tr>
              <?php } ?>

            </tbody>
          </table>
        </div>
      </div>
       <div>
        <h3>Leyenda:</h3>
        <p>
           <i class="fa-solid fa-edit"></i> <strong>Editar</strong> – Modifica los datos del registro |
           <i class="fa-solid fa-trash"></i> <strong>Inactivar</strong> – Inactiva el registro
        </p>
      </div>
    </div>
</main>