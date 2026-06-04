<script>
    const baseUrl = "<?= base_url() ?>";
</script>


<?php if (session()->getFlashdata('error')): ?>
  <script>
    Swal.fire({
      icon: 'error',
      title: 'Registro no permitido',
      text: <?= json_encode(session()->getFlashdata('error')) ?>,
      confirmButtonColor: '#d33'
    });
  </script>
<?php endif; ?>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class="fa-solid fa-calendar-days"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
  </div>

</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?php echo base_url(); ?>schoolyear/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-plus"></i>Agregar</a>
      </p>
    </div>
    <div class="">
      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center" id="datatablesSimple">
            <thead class="title-table">
              <tr>
                <th class="text-center">Nombre</th>
                <th class="text-center">Fecha de Inicio</th>
                <th class="text-center">Fecha de Termino</th>
                <th class="text-center">Código</th>
                <th class="text-center">Estado</th>
                <th class="text-center"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($datos as $dato) { ?>
                <tr>
                  <td> <?php echo $dato['nombre'] ?> </td>
                  <td> <?php echo $dato['fecha_inicio'] ?> </td>
                  <td> <?php echo $dato['fecha_termino'] ?> </td>
                  <td> <?php echo $dato['codigo'] ?> </td>
                  <td> <?php echo $dato['estado'] ?> </td>
                  <td>
                    <?php if (strtolower($dato['estado']) === 'finalizado'): ?>
                      <a class="text-secondary" style="pointer-events: none; opacity: 0.5;" title="Este período ya está finalizado."><i class="fa-solid fa-edit"></i></a>
                    <?php else: ?>
                      <a href="#" class="text-warning" onclick="editarYear(<?= $dato['id']; ?>)" title="Editar período"><i class="fa-solid fa-edit"></i></a>
                    <?php endif; ?>
                  </td>

                </tr>
              <?php } ?>

            </tbody>
          </table>


        
        </div>
      </div>
    </div>
</main>