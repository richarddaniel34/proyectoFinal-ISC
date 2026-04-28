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
                                <?php foreach ($estudiantes as $index => $estudiante): ?>
                                    <tr>
                                        <td><?= esc($estudiante['nombre'] . ' ' . $estudiante['apellido']); ?></td>
                                        <td><?= esc($estudiante['matricula']); ?></td>
                                        <td>
                                            <select class="form-control servicio-select"
                                                name="id_servicio[]"
                                                data-estudiante-id="<?= esc($estudiante['id']); ?>"
                                                required>
                                                <option value="">Seleccione un servicio</option>
                                                <?php foreach ($servicios as $servicio): ?>
                                                    <?php if ($servicio['id_escuela'] == $estudiante['id_escuela']): ?>
                                                        <?php
                                                        $nombreServicio = $servicio['nombre'];
                                                        $salida = '';
                                                        if ($servicio['nombre'] === 'Técnico Profesional' && !empty($servicio['salida_tecnica'])) {
                                                            $nombreServicio .= ' - ' . $servicio['salida_tecnica'];
                                                            $salida = $servicio['salida_tecnica'];
                                                        }
                                                        ?>
                                                        <option value="<?= esc($servicio['id_servicio']); ?>" data-salida="<?= esc($salida); ?>">
                                                            <?= esc($nombreServicio); ?>
                                                        </option>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>


                                            <!-- Hidden inputs -->
                                            <input type="hidden" name="id_curso[]" id="curso-<?= esc($estudiante['id']); ?>" value="">
                                            <input type="hidden" name="id_grado[]" value="<?= esc($estudiante['id_grado_nivel']); ?>">
                                            <input type="hidden" name="id_escuela[]" value="<?= esc($estudiante['id_escuela']); ?>">
                                            <input type="hidden" name="index[]" value="<?= $index; ?>">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-center" id="curso-visual-<?= esc($estudiante['id']); ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-end monto-pago" name="monto[]" value="<?= esc(number_format($concepto_inscripcion['monto'], 2)) ?>" readonly>
                                        </td>
                                        <td>
                                            <input type="checkbox" name="inscribir[]" value="<?= esc($estudiante['id']); ?>" data-index="<?= $index; ?>">
                                        </td>
                                        <td>
                                            <?= $estudiante['inscrito']
                                                ? '<span class="badge bg-success px-3 py-2">Inscrito</span>'
                                                : '<span class="badge bg-danger px-3 py-2">No Inscrito</span>'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-muted">No hay estudiantes disponibles</td>
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

        // Inicializar Select2 en el responsable
        $('#id_responsable').select2({
            theme: "bootstrap-4",
            placeholder: "Buscar responsable por nombre o cédula",
            allowClear: false,
            minimumInputLength: 0,
            width: '100%'
        }).next('.select2-container').addClass('form-control');

        const totalInput = document.getElementById("total_pago");
        const pagarTodoCheckbox = document.getElementById("pago_completo");
        const costoInscripcion = <?= esc($concepto_inscripcion['monto']); ?>;
        const costoMensualidad = <?= esc($concepto_mensualidad['monto']); ?>;
        const cantidadMensualidades = <?= esc($cantidad_mensualidades); ?>;
        const costoAnualMensualidades = costoMensualidad * cantidadMensualidades;

        // Validar si hay estudiantes seleccionados
        function validarSeleccion() {
            let totalSeleccionados = $("input[name='inscribir[]']:checked").length;
            $('#btn-registrar-inscripcion').prop('disabled', totalSeleccionados === 0);
        }

        // Calcular total
        function actualizarTotal() {
            let total = 0;
            $("input[name='inscribir[]']:checked").each(function() {
                total += pagarTodoCheckbox.checked ? costoInscripcion + costoAnualMensualidades : costoInscripcion;
            });
            totalInput.value = total.toFixed(2);
        }

        // Evento cambio en checkbox "Pago Completo"
        pagarTodoCheckbox.addEventListener("change", actualizarTotal);

        // Evento cambio en checkboxes de estudiantes
        $(document).on('change', "input[name='inscribir[]']", function() {
            validarSeleccion();
            actualizarTotal();
        });

        // 🔹 Cargar estudiantes al seleccionar responsable
        $('#id_responsable').on('change', function() {
            let id_responsable = $(this).val();
            let tablaEstudiantes = $('#estudiantes-list');
            tablaEstudiantes.empty();
            $('#btn-registrar-inscripcion').prop('disabled', true);

            if (id_responsable !== "") {
                $.ajax({
                    url: "<?= base_url('pagos/obtenerEstudiantes') ?>",
                    type: "GET",
                    data: {
                        id_responsable: id_responsable
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            let estudiantes = response.data;

                            if (estudiantes.length > 0) {
                                $.each(estudiantes, function(index, estudiante) {
                                    let inscripcionStatus = estudiante.inscrito ? 'Inscrito' : 'No Inscrito';
                                    let inscripcionClass = estudiante.inscrito ? 'text-success' : 'text-danger';

                                    // Construir options de servicios según escuela
                                    let opcionesServicios = '<option value="">Seleccione un servicio</option>';

                                    if (estudiante.servicios && estudiante.servicios.length > 0) {
                                        estudiante.servicios.forEach(function(serv) {
                                            const esTecnico = serv.nombre.toLowerCase().includes('técnico') || serv.nombre.toLowerCase().includes('tecnico');
                                            const salidaTxt = (serv.salida_nombre || serv.salida || '').trim();

                                            const text = esTecnico && salidaTxt ?
                                                `${serv.nombre}` // ya viene "Técnico Profesional - X"
                                                :
                                                serv.nombre;

                                            const value = (esTecnico && serv.salida_id) ?
                                                `${serv.id_servicio}|${serv.salida_id}` // 👈 combo técnico
                                                :
                                                `${serv.id_servicio}`; // 👈 no técnico

                                            opcionesServicios += `<option value="${value}">${text}</option>`;
                                        });
                                    }



                                    tablaEstudiantes.append(`
                                    <tr>
        <td>${estudiante.nombre} ${estudiante.apellido}</td>
        <td>${estudiante.matricula}</td>
        <td>
            <input type="hidden" name="id_grado[]" value="${estudiante.id_grado_nivel}">
            <input type="hidden" name="id_escuela[]" value="${estudiante.id_escuela}">
            <select class="form-control servicio-select" data-estudiante-id="${estudiante.id}" name="id_servicio[]" ${estudiante.inscrito ? 'disabled' : ''}>
                ${opcionesServicios}
            </select>
        </td>
        <td>
            <input type="text" class="form-control" id="curso-visual-${estudiante.id}" readonly>
        </td>
        <td>
            <input type="text" class="form-control monto-pago" name="monto[]" value="<?= esc(number_format($concepto_inscripcion['monto'], 2)) ?>" readonly>
        </td>
        <td>
            <input type="checkbox" name="inscribir[]" value="${estudiante.id}" ${estudiante.inscrito ? 'disabled' : ''}>
        </td>
        <td class="${inscripcionClass}">${inscripcionStatus}</td>
        <input type="hidden" id="curso-${estudiante.id}" name="id_curso[]">
    </tr>`);

                                });
                            } else {
                                tablaEstudiantes.append('<tr><td colspan="7">No hay estudiantes registrados para este responsable.</td></tr>');
                            }

                        } else if (response.status === 'empty') {
                            tablaEstudiantes.append('<tr><td colspan="7">No hay estudiantes registrados para este responsable.</td></tr>');
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Error al obtener los estudiantes. Intenta de nuevo.");
                    }
                });
            }
        });

        // 🔹 Cargar curso automáticamente al seleccionar servicio
        $(document).on('change', '.servicio-select', function() {
            let servicioCompuesto = $(this).val(); // "idServicio" o "idServicio|idSalida"
            let estudianteId = $(this).data('estudiante-id');
            let fila = $(this).closest('tr');
            let idGrado = fila.find("input[name='id_grado[]']").val();
            let idEscuela = fila.find("input[name='id_escuela[]']").val();
            let idSchoolYear = $('#id_schoolYear').val();

            if (servicioCompuesto && idGrado) {
                $.ajax({
                    url: "<?= base_url('gradossecciones/obtenerCursosPorServicioInscripcion') ?>",
                    type: "GET",
                    data: {
                        servicio_compuesto: servicioCompuesto, // 👈 clave
                        id_grado: idGrado,
                        id_escuela: idEscuela, // 👈 recomendado
                        id_schoolyear: idSchoolYear,
                        debug: 1 // 👈 activado
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success' && response.data.length > 0) {
                            response.data.forEach(c => {
                                c.disponibilidad_num = c.capacidad - c.ocupados;
                                c.disponibilidad = `${c.ocupados}/${c.capacidad}`;
                            });
                            let cursosDisponibles = response.data.filter(c => c.disponibilidad_num > 0);
                            if (cursosDisponibles.length > 0) {
                                let curso = cursosDisponibles[0];

                                console.log('Curso elegido:', cursosDisponibles[0]);

                                // 👇 fallback por si alguna clave cambia
                                const nombre = curso.nombre_curso || curso.nombre || curso.text || 'Curso sin nombre';

                                $('#curso-' + estudianteId).val(curso.id);
                                $('#curso-visual-' + estudianteId).val(`${nombre} (${curso.disponibilidad})`);
                            } else {
                                $('#curso-' + estudianteId).val('');
                                $('#curso-visual-' + estudianteId).val('');
                                alert('No hay cursos disponibles con cupo.');
                            }
                        } else {
                            $('#curso-' + estudianteId).val('');
                            $('#curso-visual-' + estudianteId).val('');
                            alert(response.message || 'No se encontraron cursos.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error AJAX:', xhr.responseText);
                        alert("Error al obtener el curso.");
                    }
                });
            }
        });






    });
</script>



<?= $this->endSection() ?>