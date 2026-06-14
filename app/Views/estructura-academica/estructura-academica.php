<div class="container-fluid">
    <!-- Encabezado -->
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fas fa-sitemap"></i> <?= esc($titulo_1) ?> /
            <small><?= esc($titulo_2) ?> - <?= esc($schoolYearActual) ?></small>
        </h1>
    </div>
</div>

<!-- Menú de botones -->
<div class="container-fluid text-center mb-4">
    <div class="row">
        <!-- Botón Grados -->
        <div class="col-md-4 mb-3">
            <a href="<?php echo base_url(); ?>estructura-academica/grados" class="btn btn-primary btn-lg w-100"><i class="fa-solid fa-layer-group"></i> <b>GRADOS</b></a>
        </div>
        <!-- Botón Cursos -->
        <div class="col-md-4 mb-3">
            <a href="<?php echo base_url(); ?>estructura-academica/cursos" class="btn btn-success btn-lg w-100"><i class="fas fa-chalkboard"></i> <b>CURSOS</b></a>
        </div>
        <!-- Botón Configurar Cursos -->
        <div class="col-md-4 mb-3">
            <a href="<?php echo base_url(); ?>estructura-academica/configurarCursos" class="btn btn-warning btn-lg w-100"><i class="fa-solid fa-a"></i><i class="fa-solid fa-b"></i><i class="fa-solid fa-c"></i> <b>CONFIGURAR CURSOS</b></a>

        </div>
    </div>
</div>

<!-- Sección de estadísticas -->
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-chart-bar"></i> Estadísticas
        </h1>
    </div>

    <div class="full-box text-center" style="padding: 30px 10px;">
        <article class="full-box tile">
            <div class="full-box tile-title text-center text-titles text-uppercase">
                <b>GRADOS REGISTRADOS</b>
            </div>
            <div class="full-box tile-icon text-center">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="full-box tile-number text-titles">
                <p class="full-box"><?= esc($cantidadGrados) ?></p>
                <small></small>
            </div>
        </article>
        <article class="full-box tile">
            <div class="full-box tile-title text-center text-titles text-uppercase">
                <b>SECCIONES CREADAS</b>
            </div>
            <div class="full-box tile-icon text-center">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="full-box tile-number text-titles">
                <p class="full-box"><?= esc($cantidadSecciones) ?></p>
                <small></small>
            </div>
        </article>
        <article class="full-box tile">
            <div class="full-box tile-title text-center text-titles text-uppercase">
                <b>CURSOS CONFIGURADOS</b>
            </div>
            <div class="full-box tile-icon text-center">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="full-box tile-number text-titles">
                <p class="full-box"><?= esc($cursosDisponibles) ?></p>
                <small><?= esc($schoolYearActual) ?></small>
            </div>
        </article>
    </div>


</div>