<script>
    var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-book"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>

<main>
    <div class="container-fluid px-4">
        <h4 class="mt-4"></h4>
        <div>
            <p>
                <a href="<?php echo base_url(); ?>docenteguia/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-plus"></i> AGREGAR</a>
            </p>
        </div>
        <div class="">
            <div class="tab-pane " id="list">
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-center" id="datatablesSimple">
                        <thead class="title-table">
                            <tr>
                                <th>Docente</th>
                                <th>Curso</th>
                                <th>Periodo Académico</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($asignaciones)): ?>
                                <?php foreach ($asignaciones as $a): ?>
                                    <tr
                                        data-id="<?= esc($a['id']) ?>"
                                        data-id-personal="<?= esc($a['id_personal']) ?>"
                                        data-id-curso="<?= esc($a['id_curso']) ?>"
                                        data-id-schoolyear="<?= esc($a['id_schoolyear']) ?>"
                                        data-nombre-personal="<?= esc($a['nombre_personal']) ?>"
                                        data-nombre-curso="<?= esc($a['nombre_curso']) ?>"
                                        data-nombre-schoolyear="<?= esc($a['nombre_schoolyear']) ?>">
                                        <td><?= esc($a['nombre_personal'] ?? 'N/A') ?></td>
                                        <td><?= esc($a['nombre_curso'] ?? 'N/A') ?></td>
                                        <td><?= esc($a['nombre_schoolyear'] ?? 'N/A') ?></td>
                                        <td>
                                            <a href="#" class="text-warning" onclick="editarDocenteGuia(<?= esc($a['id']); ?>)" title="Editar asignación">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No hay asignaciones registradas</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>


                </div>
            </div>
            <div>
                <h3>Leyenda:</h3>
                <p>
                    <i class="fa-solid fa-edit"></i> <strong>Editar</strong> – Modificar los datos del registro
                </p>
            </div>
        </div>
</main>