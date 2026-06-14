<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">
      <i class="fas fa-chalkboard"></i>
      <?= esc($titulo_1) ?> / <small><?= esc($titulo_2) ?> / <?= esc($titulo_3) ?></small>
    </h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <h4 class="mt-4"></h4>
    <div>
      <p>
        <a href="<?= base_url('estructura-academica/cursos/nuevo') ?>" class="btn btn-primary">
          <i class="fa-solid fa-file-circle-plus"></i> <b>AGREGAR CURSO</b>
        </a>
        <a href="<?= base_url('estructura-academica') ?>" class="btn btn-secondary">
          <i class="fa-solid fa-circle-left"></i> <b>REGRESAR</b>
        </a>
      </p>
    </div>
    <div class="">
      <div class="tab-pane " id="list">
        <div class="table-responsive">
          <table class="table table-hover table-striped text-center tabla-basica" id="tablaCursos">
            <thead class="title-table">
              <tr>
                <th class="text-center">Grado</th>
                <th class="text-center">Sección</th>
                <th class="text-center">Curso</th>
                <th class="text-center">Código</th>
              </tr>
            </thead>
            <tbody>
              <?php

              foreach ($cursos as $dato): ?>

                <tr>
                  <td><?= esc($dato['grado']) ?></td>
                  <td><?= esc($dato['seccion']) ?></td>
                  <td><?= esc($dato['nombre_curso']) ?></td>
                  <td><?= esc($dato['codigo_curso']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    
  </div>
</main>