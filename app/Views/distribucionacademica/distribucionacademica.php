<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-book"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>


<!-- Botón para agregar nueva distribución 
<div class="text-right mb-3">
    <a href="<?= base_url('/distribucion_academica/nuevo'); ?>" class="btn btn-success">
        <i class="fas fa-plus"></i> Nueva Distribución
    </a>
</div>
-->
<main>
    <div class="container-fluid px-4">
        <h4 class="mt-4"></h4>
        <div>
            <p>
                <a href="<?php echo base_url(); ?>distribucionacademica/nuevo" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Agregar</a>
                <a href="<?php echo base_url(); ?>asignatura/eliminados" class="btn btn-danger text-light" id="minus-user"><i class="fa-solid fa-user-minus"></i> Eliminados</a>
            </p>
        </div>
        <div class="">



            <div class="tab-pane " id="list">
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-center" id="datatablesSimple">
                        <thead class="title-table">
                            <tr>
                                <th>Escuela</th>
                                <th>Docente</th>
                                <th>Curso</th>
                                <th>Periodo Académico</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($datos as $dato) { ?>
                                <tr>
                                    <td><?= esc($dato['escuela_nombre']); ?></td>
                                    <td><?= esc($dato['docente']); ?></td>
                                    <td><?= esc($dato['curso_nombre']); ?></td>
                                    <td><?= esc($dato['periodo_academico']); ?></td>
                                    
                                    <td><a href="#" class="text-warning" onclick="editarAsignatura(<?= $dato['id']; ?>)"><i class="fa-solid fa-edit"></i></a></td>
                                   

                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>


                    <!--
                    <nav aria-label="Pagination">
                        <ul class="pagination pagination-sm">
                          <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">«</a>
                          </li>
                          <li class="page-item active" aria-current="page">
                            <a class="page-link" href="#">1</a>
                          </li>
                          <li class="page-item"><a class="page-link" href="#">2</a></li>
                          <li class="page-item"><a class="page-link" href="#">3</a></li>
                          <li class="page-item"><a class="page-link" href="#">4</a></li>
                          <li class="page-item"><a class="page-link" href="#">5</a></li>
                          <li class="page-item">
                            <a class="page-link" href="#" aria-label="Next">
                              <span aria-hidden="true">»</span>
                              <span class="visually-hidden">Next</span>
                            </a>
                          </li>
                        </ul>
                    </nav>
                        -->
                </div>
            </div>
        </div>
</main>
