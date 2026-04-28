<select id="prueba-select" class="form-control" style="width: 100%;">
    <option value="1">Opción 1</option>
    <option value="2">Opción 2</option>
</select>

<div class="col-12 col-sm-4">
    <div class="form-group">
        <label for="id_responsable">Responsable del Pago</label>
        <select class="form-control select2" id="id_responsable" name="id_responsable" required>
            <option value="">Seleccione un responsable</option>
            <?php foreach ($responsables as $responsable): ?>
                <option value="<?= esc($responsable['id']); ?>"
                    <?= (isset($id_responsable) && $responsable['id'] == $id_responsable) ? 'selected' : ''; ?>>
                    <?= esc($responsable['nombre_padre']) . ' ' . esc($responsable['apellido_padre'])
                        . ' - ' . esc($responsable['cedula_padre']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<script>
$(document).ready(function() {

    $('#id_responsable').select2({
        theme: "bootstrap-4",
        placeholder: "Buscar responsable por nombre o cédula",
        allowClear: false, // para evitar el cierre automático
        minimumInputLength: 0, // para mostrar todo al hacer clic
        width: '100%', // se adapta al contenedor
        dropdownParent: $('#id_responsable').parent()
    });

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
                                            <input type="checkbox" name="inscribir[]" value="${estudiante.id}">
                                        </td>
                                    </tr>`
                                );
                            });

                        } else {
                            tablaEstudiantes.append('<tr><td colspan="5">No hay estudiantes registrados para este responsable.</td></tr>');
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