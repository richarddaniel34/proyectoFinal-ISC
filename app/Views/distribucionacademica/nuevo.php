<script>
    var baseUrl = "<?= base_url(); ?>";
</script>


<div class="container-fluid">
    <div class="page-header text-left mb-4">
        <h1 class="text-titles">
            <i class="fa-solid fa-diagram-project"></i>
             <?= esc($titulo_1) ?> / <small><?= esc($titulo_2) ?></small>
        </h1>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">


                    <form method="POST" action="<?= base_url('distribucion-academica/insertar') ?>" autocomplete="off" class="formulario-personalizado">
                        <input type="hidden" name="asignaciones" id="asignaciones-json">

                        <?= csrf_field() ?>
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <span class="nav-link active">DISTRIBUCION ACADEMICA</span>
                            </li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div class="tab-pane fade show active" id="datos-basicos">
                                <p class="lead">Los campos marcados con (<span class="text-danger">*</span>) son obligatorios</p>
                                <div class="row">
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group label-floating">
                                            <label for="nombre" class="control-label">Docente: <small class="obligatorio-formulario">*</small></label>
                                            <select class="form-control" id="id_personal" name="id_personal">
                                                <option value="">-- Seleccione un docente --</option>
                                                <?php foreach ($personal as $profesor): ?>
                                                    <option value="<?= $profesor['id'] ?>">
                                                        <?= $profesor['nombre_completo'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-4">
                                        <div class="form-group label-floating">
                                            <label for="nombre" class="control-label">Asignatura: <small class="obligatorio-formulario">*</small></label>
                                            <select class="form-control select2" id="asignatura" name="asignatura">
                                                <option value="">Seleccione una asignatura</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="form-group label-floating">
                                            <label for="periodo_actual" class="form-label fw-bold">Año escolar en curso</label>
                                            <input type="text"
                                                id="periodo_actual"
                                                class="form-control bg-light"
                                                name="periodo_actual"
                                                value="<?= esc($schoolYearActual['nombre'] ?? 'No disponible') ?>"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                </div>
                            </div>

                            <div class="row">
                                <!-- Botón agregar -->
                                <div class="col-12 text-center mt-3">
                                    <button type="button" class="btn btn-primary text-light" id="btnAgregar">
                                        <i class="fas fa-plus-circle"></i> <b>AGREGAR ASIGNACIÓN</b>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
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
                            </div>

                            <div class="row">
                                <!-- Botones finales -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-success text-light">
                                        <i class="fa-solid fa-floppy-disk"></i> <b>GUARDAR</b>
                                    </button>
                                     <a
                                            href="#"
                                            onclick="mostrarModalCancelar('<?= base_url('distribucion-academica') ?>')"
                                            class="btn btn-danger text-light">
                                            <i class="fa-solid fa-ban"></i> <b>CANCELAR</b>
                                        </a>
                                    
                                </div>
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

        iniciarSelect2Ajax(
            '#id_personal',
            '<?= base_url('distribucion-academica/docentes') ?>',
            '-- Seleccione un docente --'
        );

        iniciarSelect2Ajax(
            '#asignatura',
            '<?= base_url('distribucion-academica/asignaturas') ?>',
            'Seleccione una asignatura'
        );

        $('#asignatura').on('select2:select', function(e) {
            const data = e.params.data;
            const tipo = parseInt(data.tipo_asignatura);
            const nombre = (data.text || '').toLowerCase();

            console.log(e.params.data);

            $('.chip').removeClass('selected');

            if (tipo === 2) {
                $('.chip').hide();

                $('.chip').filter(function() {
                    const curso = $(this).text().toLowerCase();

                    return curso.includes('4to') ||
                        curso.includes('5to') ||
                        curso.includes('6to');
                }).show();

                return;
            }

            if (nombre.includes('francés') || nombre.includes('frances')) {
                $('.chip').hide();

                $('.chip').filter(function() {
                    const curso = $(this).text().toLowerCase();

                    return curso.includes('1ro') ||
                        curso.includes('2do') ||
                        curso.includes('3ro');
                }).show();

                return;
            }

            $('.chip').show();
        });

        let asignaciones = [];

        window.toggleChip = function(chip) {
            chip.classList.toggle('selected');
        };

        $('#btnAgregar').on('click', function() {
            const docente = $('#id_personal').val();
            const docenteText = $('#id_personal').select2('data')[0]?.text || '';

            const asignatura = $('#asignatura').val();
            const asignaturaText = $('#asignatura').select2('data')[0]?.text || '';

            const idSchoolYear = <?= json_encode($schoolYearActual['id'] ?? null) ?>;

            const chipsSeleccionados = $('.chip.selected');

            if (!docente || !asignatura) {
                Swal.fire('Aviso', 'Debe seleccionar un docente y una asignatura.', 'warning');
                return;
            }

            if (chipsSeleccionados.length === 0) {
                Swal.fire('Aviso', 'Seleccione al menos un curso.', 'warning');
                return;
            }

            for (const chip of chipsSeleccionados) {
                const cursoId = $(chip).data('id');

                const existe = asignaciones.some(function(a) {
                    return a.id_asignatura == asignatura && a.id_curso == cursoId;
                });

                if (existe) {
                    Swal.fire(
                        'Aviso',
                        'Esta asignatura ya está asignada en uno de los cursos seleccionados.',
                        'warning'
                    );
                    return;
                }
            }

            chipsSeleccionados.each(function() {
                const cursoId = $(this).data('id');
                const cursoText = $(this).text().trim();

                asignaciones.push({
                    id_personal: docente,
                    docenteText: docenteText,
                    id_asignatura: asignatura,
                    asignaturaText: asignaturaText,
                    id_curso: cursoId,
                    cursoText: cursoText,
                    id_schoolyear: idSchoolYear
                });
            });

            actualizarAsignaciones();
            limpiarFormularioAsignacion();
        });

        function renderTabla() {
            const tbody = $('#tablaAsignaciones tbody');
            tbody.empty();

            asignaciones.forEach(function(a, index) {
                tbody.append(`
                <tr>
                    <td>
                        ${a.docenteText}
                        <input type="hidden" name="id_personal[]" value="${a.id_personal}">
                    </td>
                    <td>
                        ${a.asignaturaText}
                        <input type="hidden" name="id_asignatura[]" value="${a.id_asignatura}">
                    </td>
                    <td>
                        ${a.cursoText}
                        <input type="hidden" name="id_curso[]" value="${a.id_curso}">
                    </td>
                    <td class="text-center">
                        <button type="button" 
                                class="btn btn-danger btn-sm btn-eliminar-asignacion" 
                                data-index="${index}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            });
        }

        function actualizarAsignaciones() {
            $('#asignaciones-json').val(JSON.stringify(asignaciones));
            renderTabla();
        }

        function limpiarFormularioAsignacion() {
            $('#id_personal').val(null).trigger('change');
            $('#asignatura').val(null).trigger('change');
            $('.chip.selected').removeClass('selected');
        }

        $('#tablaAsignaciones').on('click', '.btn-eliminar-asignacion', function() {
            const index = $(this).data('index');

            Swal.fire({
                title: '¿Está seguro?',
                text: 'La asignación será eliminada.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (result.isConfirmed) {
                    asignaciones.splice(index, 1);
                    actualizarAsignaciones();
                }
            });
        });

    });
</script>

<?= $this->endSection() ?>