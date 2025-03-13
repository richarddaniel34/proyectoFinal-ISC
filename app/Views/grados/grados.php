<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class="fa-solid fa-graduation-cap"></i> <i class="fa-solid fa-a"></i><i class="fa-solid fa-b"></i><i class="fa-solid fa-c"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?php echo base_url(); ?>grados/nuevo" class=" btn btn-primary" id="plus-user"><i class="fa-solid fa-graduation-cap"></i><i class="fa-solid fa-plus"></i> Agregar Grado</a>
        <a href="<?php echo base_url(); ?>grados/nuevoCurso" class=" btn btn-primary" id="plus-user"><i class="fa-solid fa-a"></i><i class="fa-solid fa-b"></i><i class="fa-solid fa-plus"></i> Agregar Curso</a>
        <a href="<?php echo base_url(); ?>grados/eliminados" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-user-minus"></i> Eliminados</a>
      </p>
    </div>
    <div class="">



    <div class="tab-pane" id="list">
    <div class="table-responsive">
        <table class="table table-hover table-striped text-center" id="datatablesSimple">
            <thead class="title-table">
                <tr>
                    <th class="text-center">Grado</th>
                    <th class="text-center">Secciones</th>
                    <th class="text-center">Curso</th>
                    <th class="text-center">Código</th>
                    <th class="text-center"></th>
                    <th class="text-center"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $currentGrado = ''; // Para rastrear el grado actual
                foreach ($datos as $dato) {
                    // Si el grado actual es diferente del grado anterior, crea una nueva fila con rowspan
                    if ($currentGrado !== $dato['grado_nombre']) {
                        $currentGrado = $dato['grado_nombre'];
                        // Calcula cuántas filas tiene este grado
                        $rowspan = count(array_filter($datos, fn($d) => $d['grado_nombre'] === $currentGrado));
                        ?>
                        <tr>
                            <td rowspan="<?= $rowspan; ?>"><?= $dato['grado_nombre']; ?></td>
                            <td><?= $dato['seccion']; ?></td>
                            <td><?= $dato['curso']; ?></td>
                            <td><?= $dato['codigoCurso']; ?></td>
                            <td><a href="#" class="text-warning" onclick="editarGrado(<?= $dato['id_grado']; ?>)"><i class="fa-solid fa-edit"></i></a></td>
                            <td><a href="<?= base_url() . '/grado/eliminar/' . $dato['id_grado']; ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                    <?php } else { 
                        // Para las filas siguientes, crea solo las columnas que no son del grado
                        ?>
                        <tr>
                          <td></td>
                            <td><?= $dato['seccion']; ?></td>
                            <td><?= $dato['curso']; ?></td>
                            <td><?= $dato['codigoCurso']; ?></td>
                            <td><a href="#" class="text-warning" onclick="editarGrado(<?= $dato['id_grado']; ?>)"><i class="fa-solid fa-edit"></i></a></td>
                            <td><a href="<?= base_url() . '/grado/eliminar/' . $dato['id_grado']; ?>" class="text-danger"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>
</div>

    </div>
</main>