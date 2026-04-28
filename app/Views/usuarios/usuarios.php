

<script>
    var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-school"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>

<main>
    <div class="container-fluid px-4">
        <h4 class="mt-4"></h4>
        <div>
            <p>
                <a href="<?php echo base_url(); ?>/escuela/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Agregar</a>
                <a href="<?php echo base_url(); ?>escuela/eliminados" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-user-minus"></i> Eliminados</a>
            </p>
        </div>
        <div class="">



            <div class="tab-pane " id="list">
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-center" id="datatablesSimple">
                        <thead class="title-table">
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Tipo de Usuario</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($usuarios)): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= esc($usuario['id']) ?></td>
                                        <td><?= esc($usuario['usuario']) ?></td>
                                        <td><?= esc($usuario['id_tipo_usuario']) ?></td>
                                        <td>
                                            <a href="<?= site_url('usuarios/cambioClave/' . $usuario['id']) ?>" class="btn btn-warning">Editar Contraseña</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No hay usuarios registrados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?php if (session()->get('errors')): ?>
                        <input type="hidden" id="error_modal_flag" value="<?= session()->get('error_id'); ?>">
                    <?php endif; ?>

                    <pre><?php print_r(session()->getFlashdata('errors')); ?></pre>



                </div>
            </div>
        </div>
</main>