<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-book"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-md-10 offset-md-1">
                                <form method="POST" action="<?= base_url('distribucionacademica/insertar'); ?>">
                                    <div class="row">
                                        <!-- Escuela -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_escuela">Escuela:</label>
                                                <select class="form-control" id="id_escuela" name="id_escuela">
                                                    <option value="">Seleccione una escuela</option>
                                                    <?php foreach ($escuelas as $escuela): ?>
                                                        <option value="<?= esc($escuela['id']); ?>">
                                                            <?= esc($escuela['codigo_gestion'] . ' - ' . $escuela['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Docente -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_personal">Docente:</label>
                                                <select class="form-control" id="id_personal" name="id_personal" required>
                                                    <option value="">Seleccione un docente</option>
                                                    <?php foreach ($docentes as $docente): ?>
                                                        <option value="<?= esc($docente['id']); ?>"><?= esc($docente['nombre_completo']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <!-- Curso -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_curso">Curso:</label>
                                                <select class="form-control" id="id_curso" name="id_curso" required>
                                                    <option value="">Seleccione una escuela primero</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Periodo Académico -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="id_periodo_academico">Periodo Académico:</label>
                                                <select class="form-control" id="id_periodo_academico" name="id_periodo_academico" required>
                                                    <option value="">Seleccione un periodo</option>
                                                    <?php foreach ($periodos as $periodo): ?>
                                                        <option value="<?= esc($periodo['id']); ?>"><?= esc($periodo['codigo']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botones -->
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Guardar
                                        </button>
                                        <a href="<?= base_url('/distribucion_academica'); ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.getElementById('id_escuela').addEventListener('change', function() {
            let idEscuela = this.value;
            let selectDocente = document.getElementById('id_personal');

            if (idEscuela === "") {
                selectDocente.innerHTML = '<option value="">Seleccione un docente</option>';
                return;
            }

            fetch('<?= base_url("distribucionacademica/obtenerDocentesPorEscuela") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id_escuela=' + encodeURIComponent(idEscuela)
                })
                .then(response => response.json())
                .then(data => {
                    selectDocente.innerHTML = '<option value="">Seleccione un docente</option>';
                    data.forEach(docente => {
                        selectDocente.innerHTML += `<option value="${docente.id}">${docente.nombre_completo}</option>`;
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    </script>


