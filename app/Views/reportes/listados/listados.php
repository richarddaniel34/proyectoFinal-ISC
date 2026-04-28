<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-file"></i> Reportes/ <?php print_r($titulo) ?><small></small></h1>
    </div>
</div>

<main>
    <div class="container-fluid px-4">


        <div class="row justify-content-center">

            <div class="col-md-3 col-sm-6 mb-4">
                <button class="btn btn-primary btn-block btn-lg" onclick="location.href='<?php echo base_url(); ?>reportes/listados/listado_personal'">
                    <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                   Listado del Personal
                </button>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <button class="btn btn-success btn-block btn-lg" onclick="location.href='#'">
                    <i class="fas fa-file-alt fa-2x mb-2"></i><br>
                    Listados de estudiantes
                </button>
            </div>

        </div>
    </div>
</main>