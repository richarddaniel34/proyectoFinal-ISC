<div class="container-fluid">
    <!-- Barra de botones tipo “sidebar” horizontal -->
    <div class="row my-3">
        <div class="col-md-12 d-flex flex-wrap justify-content-start gap-2">
            <a href="#" class="btn btn-primary"><i class="fa-solid fa-home"></i> Inicio</a>
            <a href="#" class="btn btn-success"><i class="fa-solid fa-book"></i> Mis Cursos</a>
            <a href="#" class="btn btn-warning"><i class="fa-solid fa-file-alt"></i> Calificaciones</a>
            <a href="#" class="btn btn-info"><i class="fa-solid fa-user"></i> Perfil</a>
            <a href="<?= base_url('logout') ?>" class="btn btn-danger"><i class="fa-solid fa-sign-out-alt"></i> Cerrar Sesión</a>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="row">
        <div class="col-md-12">
            <div class="content p-4 bg-light rounded">

                <h2>Bienvenido, <?= session('usuario') ?>!</h2>
                <p>Este es tu dashboard como estudiante.</p>

                <!-- Información y foto -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h4>Mi Información</h4>
                        <p>Matricula: <?= session('usuario') ?></p>
                        <p>Escuela: <?= session('nombre_escuela') ?></p>
                        <p>Tipo de Usuario: Estudiante</p>
                    </div>
                    <div class="col-md-6 text-center">
                        <h4>Foto de Perfil</h4>
                        <img src="<?= session('foto') ?>" alt="Foto de perfil" class="img-fluid " width="150">
                    </div>
                </div>

                <!-- Calificaciones -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h4>Mis Calificaciones</h4>
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Asignatura</th>
                                    <th>Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Matemáticas</td>
                                    <td>A</td>
                                </tr>
                                <tr>
                                    <td>Ciencias</td>
                                    <td>B+</td>
                                </tr>
                                <tr>
                                    <td>Historia</td>
                                    <td>A-</td>
                                </tr>
                                <tr>
                                    <td>Lengua Española</td>
                                    <td>B</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mis Cursos -->
               

            </div>
        </div>
    </div>
</div>
