
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-file"></i> Reportes/<small> <?php print_r($titulo) ?></small></h1>
    </div>
</div>
<main>
  <div class="container-fluid px-4">
    <h1 class="my-4 text-center">Reporte de Personal - Vista Previa</h1>

    <!-- Tabla de docentes -->
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover text-center">
        <thead class="thead-dark">
          <tr>
            <th>Categoría</th>
            <th>Masculino (M)</th>
            <th>Femenino (F)</th>
            <th>Total (T)</th>
            <th>Observaciones / Tipo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Docentes pagados por la escuela</td>
            <td>10</td>
            <td>12</td>
            <td>22</td>
            <td>Nombrados / Contratados</td>
          </tr>
          <tr>
            <td>Docentes nombrados</td>
            <td>5</td>
            <td>8</td>
            <td>13</td>
            <td>-</td>
          </tr>
          <tr>
            <td>Administrativos</td>
            <td>3</td>
            <td>7</td>
            <td>10</td>
            <td>-</td>
          </tr>
          <tr>
            <td>Técnicos</td>
            <td>4</td>
            <td>6</td>
            <td>10</td>
            <td>-</td>
          </tr>
        </tbody>
        <tfoot class="font-weight-bold">
          <tr>
            <td>Total general</td>
            <td>22</td>
            <td>33</td>
            <td>55</td>
            <td>-</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Botones de exportación -->
    <div class="text-center my-4">
      <button class="btn btn-danger mr-2"><i class="fas fa-file-pdf"></i> Exportar PDF</button>
      <button class="btn btn-primary mr-2"><i class="fas fa-file-word"></i> Exportar Word</button>
      <button class="btn btn-success"><i class="fas fa-file-excel"></i> Exportar Excel</button>
    </div>
  </div>
</main>

<!-- Iconos Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
