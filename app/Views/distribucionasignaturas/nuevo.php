<div class="container-fluid">
    <div class="page-header text-left mb-4">
        <h1 class="text-titles">
            <i class="fa-solid fa-user-graduate"></i> Configuración /
            <small><?= esc($titulo) ?></small>
        </h1>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="<?= base_url('distribucionasignaturas/insertar') ?>" enctype="multipart/form-data" autocomplete="off" class="formulario-personalizado">
                        <?= csrf_field() ?>
                        <input type="hidden" name="asignaciones" id="asignaciones-json">

                        <div class="row g-3">
                            <!-- Docente -->
                            <div class="col-md-4">
                                <label for="id_personal" class="form-label fw-bold">Docente</label>
                                <select class="form-control" id="id_personal" name="id_personal">
                                    <option value="">-- Seleccione un docente --</option>
                                    <?php foreach ($personal as $profesor): ?>
                                        <option value="<?= $profesor['id'] ?>">
                                            <?= $profesor['nombre_completo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Asignatura -->
                            <div class="col-md-4">
                                <label for="asignatura" class="form-label fw-bold">Asignatura</label>
                                <select class="form-control select2" id="asignatura" name="asignatura" >
                                    <option value="">Seleccione una asignatura</option>
                                </select>
                            </div>

                            <!-- Periodo -->
                            <div class="col-md-4">
                                <label for="periodo_actual" class="form-label fw-bold">Periodo en Curso</label>
                                <input type="text" id="periodo_actual" class="form-control bg-light" name="periodo_actual"
                                    value="<?= esc($periodoActual['nombre'] ?? 'No disponible') ?>" readonly>
                            </div>

                            <!-- Cursos / Secciones -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Cursos / Secciones</label>
                                <div id="chips-cursos" class="chips-container mt-2">
                                    <?php foreach ($cursos as $curso): ?>
                                        <span class="chip" data-id="<?= $curso['id'] ?>" onclick="toggleChip(this)">
                                            <?= esc($curso['nombre_curso']) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Botón agregar -->
                            <div class="col-12 text-center mt-3">
                                <button type="button" class="btn btn-primary btn-sm px-4" id="btnAgregar">
                                    <i class="fas fa-plus-circle"></i> Agregar asignación
                                </button>
                            </div>


                            <!-- Lista de asignaciones en formato tabla -->
                            <div class="col-12 mt-4">
                                <table class="table table-striped table-bordered" id="tablaAsignaciones">
                                    <thead class="thead-dark text-center">
                                        <tr>
                                            <th>Docente</th>
                                            <th>Asignatura</th>
                                            <th>Cursos</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las filas se agregarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Botones finales -->
                            <div class="col-12 text-center mt-4">
                                <button type="submit" class="btn btn-success btn-raised btn-sm px-4">
                                    <i class="fa-solid fa-floppy-disk"></i> Guardar
                                </button>
                                <a href="#" class="btn btn-danger btn-raised btn-sm px-4">
                                    <i class="fa-solid fa-ban"></i> Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>







<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {

        /* ----- Select Docentes ----- */
        $(document).ready(function() {
            const $select = $('#id_personal');

            $select.select2({
                placeholder: "-- Seleccione un docente --",
                allowClear: true,
                width: '100%'
            });

            const $container = $select.next('.select2-container');

            // Aplica estilos al contenedor principal
            $container.addClass('form-control');
            $container.css({
                'background-color': 'white',
                'border': 'none',
                'border-radius': '0',
                'box-shadow': 'none',
                'height': '36px'
            });

            // Aplica estilos a la selección (el "input" que ves)
            $container.find('.select2-selection').css({
                'background-color': 'white',
                'border': 'none',
                'border-bottom': '1px solid #d2d2d2',
                'border-radius': '0',
                'box-shadow': 'none',
                'height': '36px',
                'line-height': '36px',
                'padding': '0 10px'
            });

            // Opcional: efecto al focus
            $container.find('.select2-selection').on('focus', function() {
                $(this).css('border-bottom', '2px solid #0b1065');
            });
        });

        /* ----- Select2 Asignaturas ----- */
        $('#asignatura').select2({
            placeholder: "Seleccione una asignatura",
            allowClear: true,
            minimumInputLength: 0,
            ajax: {
                url: '<?= base_url('DistribucionAsignaturas/getAsignaturasAjax') ?>',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    q: params.term || ''
                }),
                processResults: data => ({
                    results: data.items
                }),
                cache: true
            }
        }).on('select2:open', function() {
            const $input = $('.select2-container--open .select2-search__field');
            if ($input.val() === '') {
                $input.val(' ').trigger('input');
                setTimeout(() => $input.val('').trigger('input'), 100);
            }
        });

        /* ----- Chips de cursos ----- */
        window.toggleChip = chip => chip.classList.toggle('selected');

        /* ----- Agregar asignaciones ----- */
        let asignaciones = [];

        $('#btnAgregar').on('click', function() {
            const docente = $('#id_personal').val();
            const docenteText = $('#id_personal').select2('data')[0]?.text || '';
            const asignatura = $('#asignatura').val();
            const asignaturaText = $('#asignatura').select2('data')[0]?.text || '';
            const periodo = <?= json_encode($periodoActual['id'] ?? null) ?>;

            if (!docente || !asignatura) {
                Swal.fire('Error', 'Debe seleccionar un docente y una asignatura', 'warning');
                return;
            }

            const chips = document.querySelectorAll('.chip.selected');
            if (chips.length === 0) {
                Swal.fire('Error', 'Seleccione al menos un curso', 'warning');
                return;
            }

            let cursosSeleccionados = Array.from(chips).map(c => c.dataset.id);

            // Validación: ninguna asignatura puede repetirse en el mismo curso para cualquier docente
            for (let cursoId of cursosSeleccionados) {
                if (asignaciones.find(a => a.id_asignatura == asignatura && a.id_curso == cursoId)) {
                    Swal.fire('Error', 'Esta asignatura y/o Deocente ya están asignados en este curso', 'warning');
                    return;
                }
            }

            // Guardar asignaciones
            cursosSeleccionados.forEach(cursoId => {
                asignaciones.push({
                    id_personal: docente,
                    docenteText,
                    id_asignatura: asignatura,
                    asignaturaText,
                    id_curso: cursoId,
                    cursoText: document.querySelector(`.chip[data-id="${cursoId}"]`).textContent.trim(),
                    id_schoolyear: periodo
                });
            });

            // Actualizar input oculto
            $('#asignaciones-json').val(JSON.stringify(asignaciones));

            // Actualizar tabla
            renderTabla();

            // Limpiar selección
            $('#id_personal').val(null).trigger('change');
            $('#asignatura').val(null).trigger('change');
            chips.forEach(c => c.classList.remove('selected'));
        });

        /* ----- Renderizar tabla ----- */
        function renderTabla() {
            const tbody = $('#tablaAsignaciones tbody');
            tbody.empty();
            asignaciones.forEach((a, index) => {
                tbody.append(`
                <tr>
                    <td>${a.docenteText}<input type="hidden" name="id_personal[]" value="${a.id_personal}"></td>
                    <td>${a.asignaturaText}<input type="hidden" name="id_asignatura[]" value="${a.id_asignatura}"></td>
                    <td>${a.cursoText}<input type="hidden" name="id_curso[]" value="${a.id_curso}"></td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminar(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            });
        }

        /* ----- Confirmar eliminar ----- */
        window.confirmarEliminar = index => {
            Swal.fire({
                title: '¿Está seguro?',
                text: "La asignación será eliminada",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    asignaciones.splice(index, 1);
                    $('#asignaciones-json').val(JSON.stringify(asignaciones));
                    renderTabla();
                }
            });
        }

    });
</script>

<?= $this->endSection() ?>