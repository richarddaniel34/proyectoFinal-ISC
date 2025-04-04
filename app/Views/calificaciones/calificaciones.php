

<div class="container mt-4">
    <h2 class="text-center mb-4"><?= esc($titulo); ?></h2>

    <div class="row mb-4">
        <!-- Asignatura -->
        <div class="col-md-4">
            <label for="asignatura">Asignatura:</label>
            <select class="form-control" id="asignatura" name="asignatura" required>
                <option value="">Seleccione una asignatura</option>
               
            </select>
        </div>

        <!-- Competencia -->
        <div class="col-md-4">
            <label for="competencia">Competencia:</label>
            <select class="form-control" id="competencia" name="competencia" required>
                <option value="">Seleccione una competencia</option>
                <?php foreach ($competencias as $competencia): ?>
                    <option value="<?= esc($competencia['id']); ?>"><?= esc($competencia['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Periodo -->
        <div class="col-md-4">
            <label for="periodo">Periodo:</label>
            <select class="form-control" id="periodo" name="periodo" required>
                <option value="">Seleccione un periodo</option>
                <?php foreach ($periodos as $periodo): ?>
                    <option value="<?= esc($periodo['id']); ?>"><?= esc($periodo['nombre']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Botón para buscar estudiantes inscritos en el curso -->
    <div class="text-center mb-4">
        <button class="btn btn-primary" id="btnBuscarEstudiantes">Buscar Estudiantes</button>
    </div>

    <!-- Tabla de estudiantes para ingresar notas -->
    <form action="<?= base_url('calificaciones/guardarNotas'); ?>" method="post">
        <div class="table-responsive">
            <table class="table table-bordered" id="tabla-estudiantes">
                <thead class="thead-dark">
                    <tr>
                        <th>Estudiante</th>
                        <th>Matrícula</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Se cargan dinámicamente los estudiantes -->
                    <tr>
                        <td colspan="3" class="text-center">Seleccione filtros y haga clic en "Buscar Estudiantes".</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Botón para guardar las calificaciones -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success">Guardar Calificaciones</button>
        </div>
    </form>
</div>


