<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-book"></i> Configuración / 
            <small>Administrador</small>
        </h1>
    </div>
</div>

<main>
    <div class="container-fluid px-4">
        
        <!-- Cards de resumen -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <h5>Total Docentes</h5>
                        <h2>12</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <h5>Total Administrativos</h5>
                        <h2>7</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body text-center">
                        <h5>Total Apoyo</h5>
                        <h2>3</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body text-center">
                        <h5>Total Personal</h5>
                        <h2>22</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de ejemplo de configuración -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-table"></i> Listado de Usuarios
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Rol</th>
                            <th>Escuela</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Juan Pérez</td>
                            <td>Docente</td>
                            <td>Escuela 1</td>
                            <td>Activo</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>María López</td>
                            <td>Administrativo</td>
                            <td>Escuela 1</td>
                            <td>Activo</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Carlos García</td>
                            <td>Apoyo</td>
                            <td>Escuela 2</td>
                            <td>Activo</td>
                        </tr>
                        <!-- Más filas de ejemplo -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sección de acciones -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-primary">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-plus fa-2x mb-2"></i>
                        <h5>Agregar Usuario</h5>
                        <p>Registrar un nuevo docente, administrativo o personal de apoyo.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-success">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-edit fa-2x mb-2"></i>
                        <h5>Editar Usuario</h5>
                        <p>Modificar información de usuarios existentes.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-danger">
                    <div class="card-body text-center">
                        <i class="fa-solid fa-trash fa-2x mb-2"></i>
                        <h5>Eliminar Usuario</h5>
                        <p>Eliminar registros que ya no sean necesarios.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos de ejemplo -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fa-solid fa-chart-bar"></i> Estadísticas de Personal
            </div>
            <div class="card-body">
                <canvas id="chartPersonal" width="100%" height="40"></canvas>
            </div>
        </div>

    </div>
</main>
