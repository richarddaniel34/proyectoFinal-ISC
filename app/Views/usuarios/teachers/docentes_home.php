<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-chalkboard-user"></i> Dashboard / 
            <small>Docente</small>
        </h1>
    </div>
</div>

<main>
    <div class="container-fluid px-4">

        <!-- Tarjetas de resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <h5>Mis Cursos</h5>
                        <h2>5</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <h5>Estudiantes</h5>
                        <h2>120</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body text-center">
                        <h5>Asistencias Pendientes</h5>
                        <h2>8</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body text-center">
                        <h5>Evaluaciones</h5>
                        <h2>3</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de cursos -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-book"></i> Mis Cursos
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Curso</th>
                            <th>Sección</th>
                            <th>Estudiantes</th>
                            <th>Asistencias</th>
                            <th>Evaluaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Matemáticas</td>
                            <td>A</td>
                            <td>25</td>
                            <td>5 pendientes</td>
                            <td>2 completadas</td>
                        </tr>
                        <tr>
                            <td>Ciencias</td>
                            <td>B</td>
                            <td>30</td>
                            <td>3 pendientes</td>
                            <td>1 completada</td>
                        </tr>
                        <tr>
                            <td>Historia</td>
                            <td>C</td>
                            <td>28</td>
                            <td>0 pendientes</td>
                            <td>0 completadas</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary text-center">
                    <div class="card-body">
                        <i class="fa-solid fa-pen fa-2x mb-2"></i>
                        <h5>Registrar Asistencia</h5>
                        <p>Marcar asistencia de los estudiantes del día.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success text-center">
                    <div class="card-body">
                        <i class="fa-solid fa-file-alt fa-2x mb-2"></i>
                        <h5>Registrar Evaluación</h5>
                        <p>Ingresar notas y observaciones de la evaluación.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-warning text-center">
                    <div class="card-body">
                        <i class="fa-solid fa-users fa-2x mb-2"></i>
                        <h5>Ver Estudiantes</h5>
                        <p>Consultar lista de estudiantes y su progreso.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de progreso de la clase -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-chart-line"></i> Progreso General de Mis Cursos
            </div>
            <div class="card-body">
                <canvas id="chartCursos" width="100%" height="40"></canvas>
            </div>
        </div>

    </div>
</main>
