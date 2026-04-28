<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class=" fa-solid fa-users"></i> <?php print_r($titulo1) ?> <small><?php print_r($titulo2) ?></small></h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?php echo base_url(); ?>personal/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Agregar</a>
        <a href="<?php echo base_url(); ?>personal/eliminados" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-user-minus"></i> Eliminados</a>
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
                <th class="text-center">Télefono / Célular</th>
                <th class="text-center">Direccion</th>
                <th class="text-center"></th>
                <th class="text-center"></th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datos as $dato) { ?>
                <tr>
                  <td> <?php echo $dato['nombre'] ?> </td>
                  <td> <?php echo $dato['apellido'] ?> </td>
                  <td> <?php echo $dato['cedula'] ?> </td>
                  <td> <?php echo $dato['telefono'] . ' / ' . $dato['celular']; ?>
                  </td>
                  <td> <?php echo $dato['direccion'] ?> </td>
                  <td><a href="#" class="text-primary" onclick="visualizarPersonal(<?= $dato['id']; ?>)"><i class="fa-solid fa-eye"></i></a></td>
                  <td><a href="<?php echo base_url() . 'personal/editar/' . $dato['id']; ?>" class="text-warning"><i class="fa-solid fa-edit"></i></a></td>
                  <td><a href="<?php echo base_url() . 'personal/eliminar/' . $dato['id']; ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a></td>

                </tr>
              <?php } ?>

            </tbody>
          </table>
        </div>
      </div>
      <div>
        <h3>Leyenda:</h3>
        <p>
          <i class="fa-solid fa-eye"></i> <strong>Ver</strong> – Visualizar los detalles del registro |
           <i class="fa-solid fa-edit"></i> <strong>Editar</strong> – Modificar los datos del registro |
           <i class="fa-solid fa-trash"></i> <strong>Eliminar</strong> – Inactivar o eliminar el registro
        </p>
      </div>
    </div>
</main>


<?= $this->section('scripts') ?>
<script>
  function visualizarPersonal(id) {
    // Eliminar cualquier modal previo para evitar duplicados
    $('#visualizarModal').remove();
    // Agregar el modal dinámicamente al body
    $('body').append(`
        <div class="modal fade" id="visualizarModal" tabindex="-1" role="dialog" aria-labelledby="visualizarModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visualizarModalLabel"> Datos de la Escuela</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="visualizar-modal-body">
                        <!-- Aquí se cargará el formulario de edición vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

    // Llamada Ajax para cargar el contenido del modal
    $.ajax({
      url: baseUrl + '/personal/visualizar/' + id,
      type: "GET",
      success: function(response) {
        $('#visualizar-modal-body').html(response);
        $('#visualizarModal').modal('show');
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error("Error al obtener los datos:", textStatus, errorThrown);
        alert("Error al obtener los datos de la escuela");
      }
    });
  }
</script>

<?= $this->endSection() ?>