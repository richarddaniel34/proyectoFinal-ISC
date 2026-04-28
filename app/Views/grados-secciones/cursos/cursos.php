<script>
  var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles">
      <i class="fa-solid fa-graduation-cap"></i>
      <i class="fa-solid fa-a"></i><i class="fa-solid fa-b"></i><i class="fa-solid fa-c"></i>
      Configuración / <small><?= esc($titulo) ?></small>
    </h1>
  </div>
</div>

<main>
  <div class="container-fluid px-4">
    <div class="mb-3">
      <a href="<?= base_url('grados-y-secciones/cursos/curso_nuevo') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Agregar Curso 
      </a>
      <a href="<?= base_url('grados-y-secciones') ?>" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Volver
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-striped text-center" id="datatablesSimple">
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
          $currentGrado = '';
          foreach ($cursos as $dato):
              if ($currentGrado !== $dato['grado']):
                  $currentGrado = $dato['grado'];
                  $rowspan = count(array_filter($cursos, fn($d) => $d['grado'] === $currentGrado));
          ?>
            <tr>
              <td rowspan="<?= $rowspan; ?>"><?= esc($dato['grado']) ?></td>
              <td><?= esc($dato['seccion']) ?></td>
              <td><?= esc($dato['nombre_curso']) ?></td>
              <td><?= esc($dato['codigo_curso']) ?></td>
            </tr>
          <?php else: ?>
            <tr>
              <td></td>
              <td><?= esc($dato['seccion']) ?></td>
              <td><?= esc($dato['nombre_curso']) ?></td>
              <td><?= esc($dato['codigo_curso']) ?></td>
            </tr>
          <?php endif; endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
