<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?= session()->getFlashdata('success') ?>',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: '<?= session()->getFlashdata('error') ?>',
            confirmButtonColor: '#d33',
            confirmButtonText: 'Entendido'
        });
    </script>
<?php endif; ?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-school"></i> Pagos/ <small><?= esc($titulo) ?></small></h1>
    </div>
</div>
<br>

<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <form action="<?= base_url('pagos/registrar_inscripcion'); ?>" method="POST" class="formulario-personalizado">

                <!-- 🧾 Encabezado -->
                <h4 class="mb-4 text-center text-primary">
                    <i class="fas fa-file-invoice-dollar"></i> Registro de Pago de Inscripción
                </h4>

                <div class="row">
                    <!-- Responsable -->
                    <div class="col-md-6 mb-3">
                        <label for="id_responsable" class="form-label fw-bold">Responsable del Pago</label>
                        <select class="form-control select2" id="id_responsable" name="id_responsable" required>
                            <option value="">Seleccione un responsable</option>
                            <?php foreach ($responsables as $responsable): ?>
                                <option value="<?= esc($responsable['id']); ?>"
                                    <?= (isset($id_responsable) && $responsable['id'] == $id_responsable) ? 'selected' : ''; ?>>
                                    <?= esc($responsable['nombre']) . ' ' . esc($responsable['apellido']) . ' - ' . esc($responsable['cedula']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small id="infoResponsable" class="form-text text-muted"></small>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="form-group ">
                            <label for="id_schoolYear">Año Escolar</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control"
                                    value="<?= esc($schoolYearActivo['nombre'] ?? 'No disponible'); ?>" readonly>
                                <input type="hidden" id="id_schoolYear" name="id_schoolYear" value="<?= esc($schoolYearActivo['id'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Pago completo -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="pago_completo" name="pago_completo" value="1">
                    <label class="form-check-label fw-semibold" for="pago_completo">
                        Pagar todo el año (incluye mensualidades)
                    </label>
                </div>

                <!-- Tabla de estudiantes -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Estudiante</th>
                                <th>Matrícula</th>
                                <th>Servicio</th>
                                <th>Curso</th>
                                <th>Monto</th>
                                <th>Inscribir</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody id="estudiantes-list">
<?php if (!empty($estudiantes)): ?>
    <?php foreach ($estudiantes as $estudiante): ?>
        <tr>
            <td><?= esc($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?></td>
            <td><?= esc($estudiante['matricula']); ?></td>

            <td>
                <select class="form-control servicio-select"
                    data-id="<?= $estudiante['id']; ?>"
                    name="estudiantes[<?= $estudiante['id']; ?>][id_servicio]"
                    <?= $estudiante['inscrito'] ? 'disabled' : '' ?>>
                    
                    <option value="">Seleccione</option>

                    <?php foreach ($servicios as $servicio): ?>
                        <?php if ($servicio['id_escuela'] == $estudiante['id_escuela']): ?>
                            <option value="<?= $servicio['id_servicio']; ?>">
                                <?= esc($servicio['nombre']); ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <!-- 🔹 Datos ocultos -->
                <input type="hidden" name="estudiantes[<?= $estudiante['id']; ?>][id_grado]" value="<?= $estudiante['id_grado_nivel']; ?>">
                <input type="hidden" name="estudiantes[<?= $estudiante['id']; ?>][id_escuela]" value="<?= $estudiante['id_escuela']; ?>">
                <input type="hidden" id="curso-<?= $estudiante['id']; ?>" name="estudiantes[<?= $estudiante['id']; ?>][id_curso]">
            </td>

            <td>
                <input type="text" class="form-control text-center" id="curso-visual-<?= $estudiante['id']; ?>" readonly>
            </td>

            <td>
                <input type="text" class="form-control text-end"
                    value="<?= number_format($concepto_inscripcion['monto'], 2); ?>" readonly>
            </td>

            <td>
                <input type="checkbox"
                    class="check-inscribir"
                    data-id="<?= $estudiante['id']; ?>"
                    name="estudiantes[<?= $estudiante['id']; ?>][inscribir]"
                    value="1"
                    <?= $estudiante['inscrito'] ? 'disabled' : '' ?>>
            </td>

            <td>
                <?= $estudiante['inscrito']
                    ? '<span class="badge bg-success">Inscrito</span>'
                    : '<span class="badge bg-danger">No Inscrito</span>'; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7">No hay estudiantes</td>
    </tr>
<?php endif; ?>
</tbody>
                    </table>
                </div>

                <!-- Método de pago -->
                <div class="form-group mb-3">
                    <label for="metodo_pago" class="fw-bold">Método de Pago</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                        </div>
                        <input type="text" class="form-control" id="metodo_pago" name="metodo_pago" value="Efectivo" readonly>
                    </div>
                    <small class="form-text text-muted">*La institución solo acepta pagos en efectivo.</small>
                </div>

                <!-- Total -->
                <div class="form-group mb-3">
                    <label for="total_pago" class="fw-bold">Total a Pagar</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">RD$</span>
                        </div>
                        <input type="text" class="form-control text-end" id="total_pago" name="total_pago" value="0.00" readonly>
                    </div>
                </div>

                <!-- Resumen -->
                <div class="alert alert-info mt-3" id="resumen-inscripcion">
                    <strong>Resumen:</strong>
                    <ul id="resumen-detalle" class="list-unstyled mb-0">
                        <li>Seleccione estudiantes para ver el total.</li>
                    </ul>
                </div>

                <!-- Botón -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <button type="submit" id="btn-registrar-inscripcion" class="btn btn-success btn-lg w-100" disabled>
                            <i class="fas fa-check-circle"></i> Registrar Inscripción
                        </button>
                    </div>
                    <div class="col-md-6">
                        <a href="<?= base_url('pagos'); ?>" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-times-circle"></i> Cancelar
                        </a>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>


<script>
    const costoMensualidad = <?= esc($concepto_mensualidad['monto']); ?>;
    const cantidadMensualidades = <?= esc($cantidad_mensualidades); ?>;
    const costoAnualPorEstudiante = costoMensualidad * cantidadMensualidades;
</script>

<?= $this->section('scripts') ?>


<script>
$(document).ready(function() {

    // 🔹 Select2 responsable
    $('#id_responsable').select2({
        theme: "bootstrap-4",
        placeholder: "Buscar responsable",
        width: '100%'
    }).next('.select2-container').addClass('form-control');

    const totalInput = document.getElementById("total_pago");
    const pagarTodoCheckbox = document.getElementById("pago_completo");

    const costoInscripcion = <?= esc($concepto_inscripcion['monto']); ?>;
    const costoMensualidad = <?= esc($concepto_mensualidad['monto']); ?>;
    const cantidadMensualidades = <?= esc($cantidad_mensualidades); ?>;

    const costoAnualMensualidades = costoMensualidad * cantidadMensualidades;

    // 🧠 Validar selección
    function validarSeleccion() {
        let total = $(".check-inscribir:checked").length;
        $('#btn-registrar-inscripcion').prop('disabled', total === 0);
    }

    // 💰 Calcular total
    function actualizarTotal() {
        let total = 0;

        $(".check-inscribir:checked").each(function () {
            total += pagarTodoCheckbox.checked 
                ? costoInscripcion + costoAnualMensualidades 
                : costoInscripcion;
        });

        totalInput.value = total.toFixed(2);
    }

    // Eventos
    $(document).on('change', '.check-inscribir', function () {
        validarSeleccion();
        actualizarTotal();
    });

    $('#pago_completo').on('change', actualizarTotal);

    // 🔹 Cargar estudiantes
    $('#id_responsable').on('change', function() {

        let id_responsable = $(this).val();
        let tabla = $('#estudiantes-list');

        tabla.empty();
        $('#btn-registrar-inscripcion').prop('disabled', true);

        if (!id_responsable) return;

        $.ajax({
            url: "<?= base_url('pagos/obtenerEstudiantes') ?>",
            type: "GET",
            data: { id_responsable },
            dataType: "json",
            success: function(response) {

                if (response.status !== 'success' || response.data.length === 0) {
                    tabla.append('<tr><td colspan="7">No hay estudiantes</td></tr>');
                    return;
                }

                response.data.forEach(est => {

                    let estado = est.inscrito ? 'Inscrito' : 'No Inscrito';
                    let clase = est.inscrito ? 'text-success' : 'text-danger';

                    // 🔹 Servicios
                    let opciones = `<option value="">Seleccione</option>`;

                    if (est.servicios) {
                        est.servicios.forEach(serv => {

                            const esTecnico = serv.nombre.toLowerCase().includes('tecnico');
                            const salida = (serv.salida_nombre || '').trim();

                            const text = (esTecnico && salida)
                                ? `${serv.nombre}`
                                : serv.nombre;

                            const value = (esTecnico && serv.salida_id)
                                ? `${serv.id_servicio}|${serv.salida_id}`
                                : serv.id_servicio;

                            opciones += `<option value="${value}">${text}</option>`;
                        });
                    }

                    tabla.append(`
<tr>
    <td>${est.nombre} ${est.apellido}</td>
    <td>${est.matricula}</td>

    <td>
        <select class="form-control servicio-select"
            data-id="${est.id}"
            name="estudiantes[${est.id}][id_servicio]"
            ${est.inscrito ? 'disabled' : ''}>
            ${opciones}
        </select>

        <input type="hidden" name="estudiantes[${est.id}][id_grado]" value="${est.id_grado_nivel}">
        <input type="hidden" name="estudiantes[${est.id}][id_escuela]" value="${est.id_escuela}">
        <input type="hidden" id="curso-${est.id}" name="estudiantes[${est.id}][id_curso]">
    </td>

    <td>
        <input type="text" class="form-control text-center"
            id="curso-visual-${est.id}" readonly>
    </td>

    <td>
        <input type="text" class="form-control text-end"
            value="<?= number_format($concepto_inscripcion['monto'], 2); ?>" readonly>
    </td>

    <td>
        <input type="checkbox"
            class="check-inscribir"
            data-id="${est.id}"
            name="estudiantes[${est.id}][inscribir]"
            value="1"
            ${est.inscrito ? 'disabled' : ''}>
    </td>

    <td class="${clase}">${estado}</td>
</tr>
                    `);
                });

            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert("Error cargando estudiantes");
            }
        });
    });

    // 🔹 Asignación automática de curso (MEJORADA)
    $(document).on('change', '.servicio-select', function() {

        let estudianteId = $(this).data('id');
        let fila = $(this).closest('tr');

        let servicio = $(this).val();
        let idGrado = fila.find(`input[name="estudiantes[${estudianteId}][id_grado]"]`).val();
        let idEscuela = fila.find(`input[name="estudiantes[${estudianteId}][id_escuela]"]`).val();
        let idSchoolYear = $('#id_schoolYear').val();

        if (!servicio) return;

        $.ajax({
            url: "<?= base_url('gradossecciones/obtenerCursosPorServicioInscripcion') ?>",
            type: "GET",
            data: {
                servicio_compuesto: servicio,
                id_grado: idGrado,
                id_escuela: idEscuela,
                id_schoolyear: idSchoolYear
            },
            dataType: "json",
            success: function(response) {

                if (response.status !== 'success' || response.data.length === 0) {
                    alert('No hay cursos');
                    return;
                }

                // 🔥 algoritmo inteligente
                response.data.forEach(c => {
                    c.disponibles = c.capacidad - c.ocupados;
                });

                let mejor = response.data
                    .filter(c => c.disponibles > 0)
                    .sort((a, b) => b.disponibles - a.disponibles)[0];

                if (!mejor) {
                    alert('No hay cupo disponible');
                    return;
                }

                let nombre = mejor.nombre_curso || 'Curso';

                $(`#curso-${estudianteId}`).val(mejor.id);
                $(`#curso-visual-${estudianteId}`)
                    .val(`${nombre} (${mejor.ocupados}/${mejor.capacidad})`);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert("Error al obtener cursos");
            }
        });
    });

});
</script>



<?= $this->endSection() ?>