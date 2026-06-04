<div class="container-fluid">
    <div class="page-header text-left mb-4">
        <h1 class="text-titles">
            <i class="fa-solid fa-user-graduate"></i> Inscripciones /
            <small><?= esc($titulo) ?></small>
        </h1>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-10 offset-md-1">
            <div class="formulario-personalizado">
                <div class="row">
                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <label for="schoolyear">Año Escolar:</label>
                            <input type="text" class="form-control"
                                id="schoolyear"
                                name="schoolyear"
                                value="<?= esc($schoolYear['nombre'] ?? 'No definido') ?>"
                                readonly />
                            <input type="hidden"
                                id="id_schoolyear"
                                name="id_schoolyear"
                                value="<?= esc($schoolYear['id'] ?? '') ?>" />
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <label for="id_servicio">Servicio:
                                <small class="obligatorio-formulario">*</small>
                            </label>
                            <select class="form-control" id="id_servicio" name="id_servicio" required>
                                <option value="">--Seleccione el Servicio--</option>
                                <?php foreach ($servicios as $servicio): ?>
                                    <option
                                        value="<?= esc($servicio['id_servicio']) ?>"
                                        data-salida="<?= esc($servicio['nombre_salida'] ?? '') ?>">
                                        <?= esc($servicio['nombre_servicio']) ?>
                                        <?= $servicio['nombre_salida'] ? ' - ' . esc($servicio['nombre_salida']) : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-sm-4">
                        <div class="form-group">
                            <label for="id_cursos_base">Curso:
                                <small class="obligatorio-formulario">*</small>
                            </label>
                            <select class="form-control" id="id_cursos_base" name="id_cursos_base" required>
                                <option value="">--Seleccione primero un Servicio--</option>
                            </select>
                        </div>
                    </div>
                </div>

                <br>
                <div class="row text-center">
                    <button id="btn-cargar" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Cargar Estudiantes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table id="tabla-estudiantes" class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th><input type="checkbox" id="check-todos"></th>
                            <th>Estudiante</th>
                            <th>Curso</th>
                            <th>Condición Inicial</th>
                            <th>Estado</th>
                            <th>Condición Final</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center">Seleccione un curso y presione "Cargar"</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-3">
                <button id="btn-guardar" class="btn btn-success">
                    <i class="fas fa-save"></i> Guardar cambios seleccionados
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const selectServicio = document.getElementById('id_servicio');
        const selectCursos = document.getElementById('id_cursos_base');

        selectServicio.addEventListener('change', function() {
            const servicioId = this.value;
            const textoSeleccionado = this.options[this.selectedIndex].text;

            selectCursos.innerHTML = '<option value="">Cargando cursos...</option>';

            if (!servicioId) {
                selectCursos.innerHTML = '<option value="">--Seleccione primero un Servicio--</option>';
                return;
            }

            // Extraer la "salida" si existe
            let salida = null;
            if (textoSeleccionado.includes(' - ')) {
                salida = textoSeleccionado.split(' - ')[1].trim();
            }
            let url = `<?= base_url('inscripciones/obtenerCursosPorServicioRelacion') ?>?id_servicio=${servicioId}`;
            if (salida) url += `&salida=${encodeURIComponent(salida)}`;

            fetch(url)
                .then(res => res.json())
                .then(cursos => {
                    selectCursos.innerHTML = '<option value="">--Seleccione el Curso--</option>';
                    cursos.forEach(curso => {
                        const option = document.createElement('option');
                        option.value = curso.id;
                        option.textContent = curso.nombre_curso;
                        selectCursos.appendChild(option);
                    });
                })
                .catch(err => {
                    console.error('Error al cargar cursos:', err);
                    selectCursos.innerHTML = '<option value="">Error al cargar cursos</option>';
                });
        });

        let tabla;

        $('#btn-cargar').on('click', function() {
            const cursoId = $('#id_cursos_base').val();
            const schoolYearId = $('#id_schoolyear').val();

            if (!cursoId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Seleccione un curso',
                    text: 'Debe seleccionar primero un curso para cargar los estudiantes.'
                });
                return;
            }

            if ($.fn.DataTable.isDataTable('#tabla-estudiantes')) tabla.destroy();

            tabla = $('#tabla-estudiantes').DataTable({
                ajax: {
                    url: '<?= base_url("inscripciones/obtenerInscripcionesPorCurso") ?>',
                    type: 'GET',
                    data: {
                        id_curso: cursoId,
                        id_schoolyear: schoolYearId
                    },
                    dataSrc: ''
                },
                columns: [{
                        data: 'id_inscripcion',
                        render: data => `<input type="checkbox" class="check-fila" data-id="${data}">`,
                        orderable: false
                    },
                    {
                        data: 'estudiante'
                    },
                    {
                        data: 'nombre_curso',
                        render: (data, type, row) => `<select class="form-control curso" data-id="${row.id_inscripcion}" data-curso-actual="${row.id_curso}" disabled><option value="${row.id_curso}" selected>${data}</option></select>`
                    },
                    {
                        data: 'condicion_inicial'
                    },
                    {
                        data: 'estado',
                        render: (data, type, row) => {
                            const opciones = ['Normal', 'Prematricula', 'Pendiente de Pago'];
                            return `<select class="form-control estado" data-id="${row.id_inscripcion}" disabled>${opciones.map(op => `<option value="${op}" ${op === data ? 'selected' : ''}>${op}</option>`).join('')}</select>`;
                        }
                    },
                    {
                        data: 'condicion_final',
                        render: (data, type, row) => {
                            const opciones = ['No definido', 'Promovido', 'Aplazado', 'Reprobado', 'Retirado'];
                            return `<select class="form-control condicion_final" data-id="${row.id_inscripcion}" disabled>${opciones.map(op => `<option value="${op}" ${op === data ? 'selected' : ''}>${op}</option>`).join('')}</select>`;
                        }
                    }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                responsive: true,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        title: 'Listado de estudiantes por curso',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        title: 'Listado de estudiantes por curso',
                        orientation: 'landscape',
                        pageSize: 'LETTER',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        title: 'Listado de estudiantes por curso',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    }
                ],
                lengthMenu: [5, 10, 25, 50],
                drawCallback: function() {
                    $('#tabla-estudiantes .check-fila').off('change').on('change', function() {
                        const check = $(this);
                        const fila = check.closest('tr');
                        const selectCurso = fila.find('.curso');
                        const selectEstado = fila.find('.estado');
                        const selectCondFinal = fila.find('.condicion_final');
                        const idCursoActual = selectCurso.data('curso-actual');

                        if (check.is(':checked')) {
                            selectCurso.prop('disabled', false);
                            selectEstado.prop('disabled', false);
                            selectCondFinal.prop('disabled', false);

                            fetch(`<?= base_url('inscripciones/obtenerSeccionesDelMismoGrado') ?>?id_curso=${idCursoActual}`)
                                .then(res => res.json())
                                .then(secciones => {
                                    let opciones = `<option value="${idCursoActual}" selected>${selectCurso.find('option:selected').text()}</option>`;
                                    opciones += secciones.map(s => `<option value="${s.id}">${s.nombre_curso}</option>`).join('');
                                    selectCurso.html(opciones);
                                })
                                .catch(err => {
                                    console.error('Error al obtener secciones:', err);
                                });

                        } else {
                            selectCurso.prop('disabled', true);
                            selectEstado.prop('disabled', true);
                            selectCondFinal.prop('disabled', true);
                            selectCurso.html(`<option value="${idCursoActual}" selected>${selectCurso.find('option:selected').text()}</option>`);
                        }
                    });
                }
            });
        });

        $('#btn-guardar').on('click', function() {
            const cambios = [];

            $('#tabla-estudiantes tbody tr').each(function() {
                const fila = $(this);
                const check = fila.find('.check-fila');
                if (check.is(':checked')) {
                    cambios.push({
                        id_inscripcion: check.data('id'),
                        curso: fila.find('.curso').val(),
                        estado: fila.find('.estado').val(),
                        condicion_final: fila.find('.condicion_final').val()
                    });
                }
            });

            if (cambios.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Nada seleccionado',
                    text: 'Seleccione al menos un registro para actualizar.'
                });
                return;
            }

            $.ajax({
                url: '<?= base_url("inscripciones/actualizarInscripciones") ?>',
                type: 'POST',
                data: {
                    inscripciones: JSON.stringify(cambios)
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cambios guardados',
                        text: 'Los cambios se han guardado correctamente.'
                    });
                    tabla.ajax.reload();
                },
                error: function(err) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al guardar los cambios.'
                    });
                }
            });
        });
    });
</script>





<?= $this->endSection() ?>