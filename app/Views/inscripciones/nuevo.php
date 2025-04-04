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
        <h1 class="text-titles"><i class="fa-solid fa-school"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
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
                                <form action="<?= base_url('inscripciones/registrar'); ?>" method="POST">
                                    <div class="row">
                                        <!-- 🔹 Seleccionar Responsable -->
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="id_responsable">Responsable del Pago</label>
                                                <select class="form-control select2" id="id_responsable" name="id_responsable" required>
                                                    <option value="">Seleccione un responsable</option>
                                                    <?php foreach ($responsables as $responsable): ?>
                                                        <option value="<?= esc($responsable['id']); ?>"
                                                            <?= (isset($id_responsable) && $responsable['id'] == $id_responsable) ? 'selected' : ''; ?>>
                                                            <?= esc($responsable['nombre']) . ' ' . esc($responsable['apellido'])
                                                                . ' - ' . esc($responsable['cedula']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="id_schoolYear">Año Escolar</label>
                                                <select class="form-control" id="id_schoolYear" name="id_schoolYear" required>
                                                    <?php foreach ($schoolYears as $schoolYear): ?>
                                                        <option value="<?= esc($schoolYear['id']); ?>">
                                                            <?= esc($schoolYear['codigo']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>


                                    <!-- 🔹 Opción de Pago Completo -->
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="pago_completo" name="pago_completo" value="1">
                                        <label class="form-check-label" for="pago_completo">
                                            Pagar todo el año (incluye mensualidades)
                                        </label>
                                    </div>

                                    <!-- 🔹 Tabla de Estudiantes -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Estudiante</th>
                                                    <th>Matrícula</th>
                                                    <th>Seleccionar Curso</th>
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
                                                                <select name="id_curso[]" class="form-control curso-select" required>
                                                                    <option value="">Seleccione un curso</option>
                                                                    <?php foreach ($cursos as $curso): ?>
                                                                        <option value="<?= esc($curso['id']); ?>">
                                                                            <?= esc($curso['nombreCurso']); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control monto-pago" name="monto[]"
                                                                    value="<?= esc(number_format($concepto_inscripcion['monto'], 2)) ?>" readonly>
                                                            </td>
                                                            <td>
                                                                <input type="checkbox" name="inscribir[]" value="<?= esc($estudiante['id']); ?>">
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                    
                                                        <td colspan="6">No hay estudiantes disponibles</td>
                                            
                                                    </tr>
                                                    
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- 🔹 Seleccionar Método de Pago -->
                                    <div class="form-group">
                                        <label for="metodo_pago">Método de Pago</label>
                                        <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                                            <option value="">Seleccione un método de pago</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                        </select>
                                    </div>

                                    <!-- 🔹 Monto Total -->
                                    <div class="form-group">
                                        <label for="total_pago">Total a Pagar</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">RD$</span>
                                            </div>
                                            <input type="text" class="form-control" id="total_pago" name="total_pago" value="0.00" readonly>
                                        </div>
                                    </div>

                                    <!-- 🔹 Botón de Enviar -->
                                    <button type="submit" id="btn-registrar-inscripcion" class="btn btn-primary btn-block" disabled>Registrar Inscripción</button>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        $('#id_responsable').select2({
            theme: "bootstrap-4",
            placeholder: "Buscar responsable por nombre o cédula",
            allowClear: false,
            minimumInputLength: 0,
            width: '100%'
        }).next('.select2-container').addClass('form-control');

        $('#id_responsable').on('change', function() {
            let id_responsable = $(this).val();

            if (id_responsable !== "") {
                $.ajax({
                    url: "<?= base_url('inscripciones/obtenerEstudiantes') ?>",
                    type: "GET",
                    data: {
                        id_responsable: id_responsable
                    },
                    dataType: "json",
                    success: function(response) {

                        let tablaEstudiantes = $('#estudiantes-list');
                        tablaEstudiantes.empty();

                        if (response.status === 'success') {

                            let estudiantes = response.data;

                            if (estudiantes.length > 0) {

                                $.each(estudiantes, function(index, estudiante) {

                                    let inscripcionStatus = estudiante.inscrito ? 'Inscrito' : 'No Inscrito';
                                    let inscripcionClass = estudiante.inscrito ? 'text-success' : 'text-danger';

                                    tablaEstudiantes.append(
                                        `<tr>
                                    <td>${estudiante.nombre} ${estudiante.apellido}</td>
                                    <td>${estudiante.matricula}</td>
                                    <td>
                                        <select name="id_curso[]" class="form-control curso-select" required>
                                            <option value="">Seleccione un curso</option>
                                            <?php foreach ($cursos as $curso): ?>
                                                <option value="<?= esc($curso['id']); ?>"><?= esc($curso['nombreCurso']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control monto-pago" name="monto[]" value="<?= esc(number_format($concepto_inscripcion['monto'], 2)) ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="inscribir[]" value="${estudiante.id}" ${estudiante.inscrito ? 'disabled' : ''}>
                                    </td>
                                    <td class="${inscripcionClass}">
                                        ${inscripcionStatus}
                                    </td>
                                </tr>`
                                    );
                                });

                            } else {
                                tablaEstudiantes.append('<tr><td colspan="6">No hay estudiantes registrados para este responsable.</td></tr>');
                            }

                        } else {
                            alert(response.message);
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Error al obtener los estudiantes. Intenta de nuevo.");
                    }
                });
            } else {
                $('#estudiantes-list').empty();
            }

        });

    });


    //Espera que el DOM esté cargado
    $(document).ready(function() {

        // Función para validar si hay algún estudiante seleccionado
        function validarSeleccion() {
            let totalSeleccionados = $("input[name='inscribir[]']:checked").length;

            // Si hay al menos un estudiante seleccionado, habilitamos el botón
            if (totalSeleccionados > 0) {
                $('#btn-registrar-inscripcion').prop('disabled', false);
            } else {
                $('#btn-registrar-inscripcion').prop('disabled', true);
            }
        }

        // Escuchamos el evento "change" en los checkboxes de inscribir[]
        $(document).on('change', "input[name='inscribir[]']", function() {
            validarSeleccion();
        });

        // ✅ También es buena idea validarlo justo después de cargar la tabla con AJAX
        $('#id_responsable').on('change', function() {
            $('#btn-registrar-inscripcion').prop('disabled', true); // lo desactivas hasta que seleccionen algo
        });

    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const totalInput = document.getElementById("total_pago");
        const pagarTodoCheckbox = document.getElementById("pago_completo");

        // Traemos los valores desde PHP al JS
        const costoInscripcion = <?= esc($concepto_inscripcion['monto']); ?>;
        const costoMensualidad = <?= esc($concepto_mensualidad['monto']); ?>;
        const cantidadMensualidades = <?= esc($cantidad_mensualidades); ?>;

        const costoAnualMensualidades = costoMensualidad * cantidadMensualidades;

        function actualizarTotal() {
            let total = 0;

            // 🔹 Obtener los estudiantes seleccionados
            const estudiantesSeleccionados = document.querySelectorAll("input[name='inscribir[]']:checked");

            estudiantesSeleccionados.forEach(function(checkbox) {
                // ✅ Si se paga el año completo
                if (pagarTodoCheckbox.checked) {
                    const subtotal = costoInscripcion + costoAnualMensualidades;
                    total += subtotal;

                } else {
                    // ✅ Si solo se paga la inscripción
                    total += costoInscripcion;
                }
            });

            // ✅ Mostrar el total en el input de forma legible con dos decimales
            totalInput.value = total.toFixed(2);
        }

        // 🔹 Escuchar cambios en los checkboxes de los estudiantes
        document.getElementById("estudiantes-list").addEventListener("change", function(e) {
            if (e.target.name === "inscribir[]") {
                actualizarTotal();
            }
        });

        // 🔹 Escuchar el cambio en el checkbox de "Pago Completo"
        pagarTodoCheckbox.addEventListener("change", function() {
            actualizarTotal();
        });

    });
</script>
<?= $this->endSection() ?>