<div class="container-fluid">
    <!-- Encabezado -->
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-school"></i> Configuración /
            <small><?= esc($titulo) ?></small>
        </h1>
    </div>
</div>

<!-- Menú de botones -->
<div class="container-fluid text-center mb-4">
    <div class="row">
        <!-- Botón Grados -->
        <div class="col-md-4 mb-3">
            <a href="<?php echo base_url(); ?>grados-y-secciones/grados" class="btn btn-primary btn-lg w-100"><i class="fa-solid fa-layer-group"></i> grados</a>
        </div>
        <!-- Botón Cursos -->
        <div class="col-md-4 mb-3">
            <a href="<?php echo base_url(); ?>grados-y-secciones/cursos" class="btn btn-success btn-lg w-100"><i class="fa-solid fa-book"></i> Cursos</a>
        </div>
        <!-- Botón Configurar Cursos -->
        <div class="col-md-4 mb-3">
            <a href="<?php echo base_url(); ?>grados-y-secciones/configurar_cursos" class="btn btn-warning btn-lg w-100"><i class="fa-solid fa-book"></i> Configurar Cursos</a>

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

    <div class="row text-center">
        <div class="col-md-4 mb-3">
            <div class="card bg-light shadow-sm p-3">
                <h3><?= esc($cantidadGrados) ?></h3>
                <p>Grados registrados</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-light shadow-sm p-3">
                <h3><?= esc($cantidadSecciones) ?></h3>
                <p>Secciones creadas</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-light shadow-sm p-3">
                <h3><?= esc($cursosDisponibles) ?></h3>
                <p>Cursos disponibles</p>
            </div>
        </div>
    </div>
</div>