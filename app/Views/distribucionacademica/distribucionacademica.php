<main>
    <div class="container-fluid px-4">

        <div class="page-header">
            <h1 class="text-titles">
                <i class="fa-solid fa-diagram-project"></i>
                <?= esc($titulo_1) ?> /
                <small><?= esc($titulo_2) ?>
                    - <?= esc($schoolYearActual['nombre']) ?>
                </small>
            </h1>
        </div>
        <!-- <p class="lead">Este módulo permite edicion </p>-->
    </div>
    <div class="container-fluid">
        <div class="mb-3">
            <a href="<?= base_url('distribucion-academica/nuevo') ?>" class="btn btn-primary text-light btn-bold">
                <i class="fa-solid fa-file-circle-plus"></i> <b> NUEVA ASIGNACIÓN </b>
            </a>
            <?php if (!empty($schoolYearAnterior) && !empty($asignacionesAnterior)): ?>
                <button type="button"
                    class="btn btn-success text-light btn-bold"
                    id="btnCopiarAnterior">
                    <i class="fa-solid fa-copy"></i>
                    <b> USAR REGISTROS <?= esc($schoolYearAnterior['nombre']) ?></b>
                </button>
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped text-center tabla-basica" id="tablaDistribucion">
                <thead class="title-table">
                    <tr>
                        <th class="text-center">Docente</th>
                        <th class="text-center">Asignatura</th>
                        <th class="text-center">Curso</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($asignaciones)): ?>
                        <?php foreach ($asignaciones as $a): ?>
                            <tr data-id="<?= esc($a['id']) ?>">

                                <td class="td-docente"
                                    data-current-id="<?= esc($a['id_docente']) ?>"
                                    data-current-name="<?= esc($a['docente']) ?>">
                                    <?= esc($a['docente']) ?>
                                </td>

                                <td><?= esc($a['asignatura']) ?></td>
                                <td><?= esc($a['curso']) ?></td>

                                <td>
                                    <button type="button"
                                        class="btn btn-primary btn-sm edit-row"
                                        title="Editar docente">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <button type="button"
                                        class="btn btn-success btn-sm save-row"
                                        style="display:none;"
                                        title="Guardar">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                    </button>

                                    <button type="button"
                                        class="btn btn-secondary btn-sm cancel-row"
                                        style="display:none;"
                                        title="Cancelar">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div>
            <h3>Leyenda:</h3>
            <p>
                <i class="fa-solid fa-edit"></i> <strong>Editar</strong> – Modifica los datos del registro |
                <i class="fa-solid fa-floppy-disk"></i> <strong>Guardar</strong> – Guarda los cambios en el Registro |
                <i class="fa-solid fa-xmark"></i> <strong>Cancelar</strong> – Cancela la edicion del registro
            </p>
        </div>
    </div>
</main>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {

        const urlDocentesAjax = "<?= base_url('distribucion-academica/docentes') ?>";
        const urlActualizarDocente = "<?= base_url('distribucion-academica/actualizar-docente') ?>";



        $('#tablaDistribucion').on('click', '.edit-row', function() {
            const $row = $(this).closest('tr');
            const $td = $row.find('.td-docente');

            const currentId = $td.data('current-id');
            const currentName = $td.data('current-name');

            $td.html('<select class="form-control select-docente" style="width:100%;"></select>');

            const $select = $td.find('.select-docente');

            iniciarSelect2Ajax(
                $select,
                urlDocentesAjax,
                'Seleccione un docente'
            );

            if (currentId && currentName) {
                const option = new Option(currentName, currentId, true, true);
                $select.append(option).trigger('change');
            }

            $row.find('.edit-row').hide();
            $row.find('.save-row, .cancel-row').show();
        });

        $('#tablaDistribucion').on('click', '.cancel-row', function() {
            const $row = $(this).closest('tr');
            const $td = $row.find('.td-docente');

            $td.text($td.data('current-name'));

            $row.find('.save-row, .cancel-row').hide();
            $row.find('.edit-row').show();
        });

        $('#tablaDistribucion').on('click', '.save-row', function() {
            const $row = $(this).closest('tr');
            const idDistribucion = $row.data('id');

            const $select = $row.find('.select-docente');
            const nuevoDocenteId = $select.val();
            const nuevoDocenteTexto = $select.find('option:selected').text();

            if (!nuevoDocenteId) {
                Swal.fire('Aviso', 'Seleccione un docente válido.', 'warning');
                return;
            }

            $.ajax({
                url: urlActualizarDocente,
                type: 'POST',
                dataType: 'json',
                data: {
                    id: idDistribucion,
                    id_docente: nuevoDocenteId
                },
                success: function(res) {
                    if (res.success) {
                        const $td = $row.find('.td-docente');

                        $td.text(nuevoDocenteTexto);
                        $td.data('current-id', nuevoDocenteId);
                        $td.data('current-name', nuevoDocenteTexto);

                        $row.find('.save-row, .cancel-row').hide();
                        $row.find('.edit-row').show();

                        Swal.fire('Éxito', 'Docente actualizado correctamente.', 'success');
                    } else {
                        Swal.fire('Error', res.message || 'No fue posible actualizar el docente.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('Error', 'Error en la petición. Intente de nuevo.', 'error');
                }
            });
        });

        $('#btnCopiarAnterior').on('click', function() {
            Swal.fire({
                title: '¿Copiar distribución anterior?',
                text: 'Se copiarán las asignaciones del año escolar anterior al año escolar actual. No se duplicarán registros existentes.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, copiar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('distribucion-academica/copiar-anterior') ?>",
                        type: "POST",
                        dataType: "json",
                        success: function(res) {
                            if (res.success) {
                                Swal.fire('Éxito', res.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                Swal.fire('Aviso', res.message, 'warning');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire('Error', 'No fue posible copiar la distribución.', 'error');
                        }
                    });
                }
            });
        });

    });
</script>

<?= $this->endSection() ?>