<main>
    <div class="container-fluid px-4">
        <div class="page-header">
            <h1 class="text-titles"><i class="fa-solid fa-book"></i> Configuración / <small><?= esc($titulo) ?></small></h1>
        </div>

        <!-- Mensajes flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <a href="<?= base_url('distribucion-asignaturas/nuevo') ?>" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i> Agregar
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped text-center" id="datatablesSimple">
                <thead class="title-table">
                    <tr>
                        <th>Docente</th>
                        <th>Asignatura</th>
                        <th>Curso</th>
                        <th>Periodo Académico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($asignaciones)): ?>
                        <?php foreach ($asignaciones as $a): ?>
                            <tr data-id="<?= $a['id'] ?>">
                                <td class="td-docente" data-current="<?= esc($a['docente']) ?>">
                                    <?= esc($a['docente']) ?>
                                </td>
                                <td><?= esc($a['asignatura']) ?></td>
                                <td><?= esc($a['curso']) ?></td>
                                <td><?= esc($a['periodo']) ?></td>
                                <td>
                                    <input type="checkbox" class="edit-row">
                                    <button class="btn btn-success btn-sm save-row" style="display:none;">✔️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No hay asignaciones registradas.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?= $this->section('scripts') ?>
<!-- Asegúrate de tener cargado Select2 -->

<script>
    $(document).ready(function() {
        // Inicializa DataTable
        var table = $('#datatablesSimple').DataTable({
            autoWidth: false,
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                paginate: {
                    first: "Primero",
                    last: "Último",
                    next: "Siguiente",
                    previous: "Anterior"
                },
                zeroRecords: "No se encontraron registros"
            }
        });

        // Editar fila
        $('#datatablesSimple').on('change', '.edit-row', function() {
            var $row = $(this).closest('tr');
            var $td = $row.find('.td-docente');
            var currentName = $td.data('current');

            if (this.checked) {
                $td.html('<select class="form-control select-docente"></select>');
                var $select = $td.find('select');

                // Inicializar Select2 con AJAX
                $select.select2({
                    placeholder: "Seleccione un docente",
                    allowClear: true,
                    width: 'resolve',
                    minimumInputLength: 0,
                    ajax: {
                        url: '<?= base_url('DistribucionAsignaturas/getDocentesAjax') ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term || '', // término de búsqueda
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: $.map(data.items, function(obj) {
                                    return {
                                        id: obj.id,
                                        text: obj.nombre_completo
                                    };
                                }),
                                pagination: {
                                    more: data.more
                                }
                            };
                        },
                        cache: true
                    }
                });

                // Preseleccionar el docente actual si existe
                if (currentName) {
                    var option = new Option(currentName, '', true, true);
                    $select.append(option).trigger('change');
                }

                $row.find('.save-row').show();
            } else {
                // Cancelar edición
                $td.text($td.data('current'));
                $row.find('.save-row').hide();
            }
        });

        // Guardar cambios
        $('#datatablesSimple').on('click', '.save-row', function() {
            var $row = $(this).closest('tr');
            var idDistribucion = $row.data('id');
            var $select = $row.find('.select-docente');
            var nuevoDocenteId = $select.val();
            var nuevoDocenteTexto = $select.find('option:selected').text();

            if (!nuevoDocenteId) {
                alert('Seleccione un docente válido.');
                return;
            }

            $.ajax({
                url: '<?= base_url('DistribucionAsignaturas/actualizarDocente') ?>',
                type: 'POST',
                data: {
                    id: idDistribucion,
                    id_docente: nuevoDocenteId
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $row.find('.td-docente').text(nuevoDocenteTexto).data('current', nuevoDocenteTexto);
                        $row.find('.edit-row').prop('checked', false);
                        $row.find('.save-row').hide();
                        alert('Docente actualizado correctamente.');
                    } else {
                        alert('Error al actualizar: ' + res.message);
                    }
                },
                error: function(xhr) {
                    console.error('Error al guardar:', xhr.responseText);
                    alert('Error en la petición. Intente de nuevo.');
                }
            });
        });
    });
</script>

<?= $this->endSection() ?>