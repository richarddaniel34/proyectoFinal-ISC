<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-file"></i> <?php print_r($titulo) ?><small></small></h1>
    </div>
    <p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Esse voluptas reiciendis tempora voluptatum eius porro ipsa quae voluptates officiis sapiente sunt dolorem, velit quos a qui nobis sed, dignissimos possimus!</p>
</div>


<main>
    <div class="container-fluid px-4">


        <div class="row justify-content-center">

            <div class="col-md-3 col-sm-6 mb-4">
                <button class="btn btn-primary btn-block btn-lg" onclick="location.href='<?php echo base_url(); ?>reportes/estadisticas/'">
                    <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                   Estadísticas
                </button>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <button class="btn btn-success btn-block btn-lg" onclick="location.href='<?php echo base_url(); ?>reportes/listados/'">
                    <i class="fas fa-file-alt fa-2x mb-2"></i><br>
                    Listados
                </button>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <button class="btn btn-warning btn-block btn-lg text-white" onclick="location.href='#'">
                    <i class="fas fa-users fa-2x mb-2"></i><br>
                    Reporte de Personal
                </button>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <button class="btn btn-info btn-block btn-lg text-white" onclick="location.href='#'">
                    <i class="fas fa-school fa-2x mb-2"></i><br>
                    Reporte de Estudiantes
                </button>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <button class="btn btn-secondary btn-block btn-lg" onclick="location.href='#'">
                    <i class="fas fa-calendar-alt fa-2x mb-2"></i><br>
                    Reporte de Periodos
                </button>
            </div>

        </div>
    </div>
</main>

<!-- Font Awesome para los iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">