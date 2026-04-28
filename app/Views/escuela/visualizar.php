<div class="container-fluid">
  <div class="page-header">
    <h1 class="text-titles"><i class="fa-solid fa-school"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
  </div>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Logo -->
        <div class="col-md-3 logo-col text-center">
            <img src="<?= base_url($datos['logo']); ?>" alt="Logo Escuela" class="img-fluid img-logo">
            <p></p>
            <p><a href="<?php echo base_url(); ?>escuela" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-rotate-left"></i> Volver</a></p>
        </div>

    

        <!-- Contenido -->
        <div class="col-md-9">

            <!-- Datos Generales -->
            <div class="seccion-titulo titulo-tabla-visualizacion">Datos Generales</div>
            <table class="table tabla-info">
                <tbody>
                    <tr>
                        <td><strong>Nombre:</strong></td>
                        <td><?= esc($datos['nombre']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Modalidad:</strong></td>
                        <td>
                            <?php 
                                $mod = array_filter($modalidad, fn($m) => $m['id'] == $datos['id_modalidad']);
                                echo esc(reset($mod)['nombre'] ?? 'N/A');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?= esc($datos['email']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Teléfono:</strong></td>
                        <td><?= esc($datos['telefono']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Redes Sociales:</strong></td>
                        <td><?= esc($datos['redes']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Dirección:</strong></td>
                        <td><?= esc($datos['direccion']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Web:</strong></td>
                        <td><?= esc($datos['web']); ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Datos administrativos y de gestión -->
            <div class="seccion-titulo">Datos Administrativos y de Gestión</div>
            <table class="table tabla-info">
                <tbody>
                    <tr>
                        <td><strong>Código SIGERD:</strong></td>
                        <td><?= esc($datos['codigo_gestion']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Código de Plantel:</strong></td>
                        <td><?= esc($datos['codigo_plantel']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>RNC:</strong></td>
                        <td><?= esc($datos['rnc']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Regional de Educación:</strong></td>
                        <td><?= esc($datos['regional_educacion']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Distrito Educativo:</strong></td>
                        <td><?= esc($datos['distrito_educativo']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Director/a Distrito Educativo:</strong></td>
                        <td><?= esc($datos['director_distrito']); ?></td>
                    </tr>
                    <tr>
                        <td><strong>DTecnico de Acreditacion y Titulacion:</strong></td>
                        <td><?= esc($datos['tecnico_acreditacion']); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>