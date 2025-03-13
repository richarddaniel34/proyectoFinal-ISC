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
        <h1 class="text-titles"><i class="fa-solid fa-money-bill-wave"></i> Pagos / <small><?= esc($titulo) ?></small></h1>
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
                                <form action="<?= base_url('pagos/registrarMensualidades'); ?>" method="POST">

                                    <div class="row">
                                        <!-- 🔹 Seleccionar Responsable -->
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="id_responsable">Responsable del Pago</label>
                                                <select class="form-control select2" id="id_responsable" name="id_responsable" required>
                                                    <option value="">Seleccione un responsable</option>
                                                    <?php foreach ($responsables as $responsable): ?>
                                                        <option value="<?= esc($responsable['id']); ?>">
                                                            <?= esc($responsable['nombre_padre']) . ' ' . esc($responsable['apellido_padre']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- 🔹 Año Escolar -->
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="id_schoolYear">Año Escolar</label>
                                                <select class="form-control" id="id_schoolYear" name="id_schoolYear" required>
                                                    <option value="">Seleccione un año escolar</option>
                                                    <?php foreach ($schoolYears as $schoolYear): ?>
                                                        <option value="<?= esc($schoolYear['id']); ?>">
                                                            <?= esc($schoolYear['codigo']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 🔹 Tabla de Mensualidades -->
                                    <div class="table-responsive mt-4">
                                        <table class="table table-bordered text-center">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Estudiante</th>
                                                    <th>Curso</th>
                                                    <th>Meses Pendientes</th>
                                                    <th>Total por Estudiante</th>
                                                    <th>Seleccionar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="estudiantes-mensualidades-list">
                                                <!-- Se cargan dinámicamente -->
                                                <tr>
                                                    <td colspan="5">Seleccione un responsable y un año escolar para ver las mensualidades pendientes.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- 🔹 Método de Pago -->
                                    <div class="form-group">
                                        <label for="metodo_pago">Método de Pago</label>
                                        <select class="form-control" id="metodo_pago" name="metodo_pago" required>
                                            <option value="">Seleccione un método de pago</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                        </select>
                                    </div>

                                    <!-- 🔹 Total a Pagar -->
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
                                    <button type="submit" class="btn btn-success btn-block">Registrar Pago</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {

        // Inicializar Select2
        $('#id_responsable').select2({
            theme: "bootstrap-4",
            placeholder: "Buscar responsable...",
            allowClear: true,
            width: '100%'
        }).next('.select2-container').addClass('form-control');

        // Eventos al cambiar responsable o año escolar
        $('#id_responsable, #id_schoolYear').on('change', function() {
            let id_responsable = $('#id_responsable').val();
            let id_schoolYear = $('#id_schoolYear').val();

            if (id_responsable && id_schoolYear) {
                cargarMensualidadesPendientes(id_responsable, id_schoolYear);
            }
        });

        // 🔥 Función para cargar mensualidades pendientes
        function cargarMensualidadesPendientes(id_responsable, id_schoolYear) {
            $.ajax({
                url: "<?= base_url('inscripciones/obtenerMensualidadesPendientes') ?>",
                type: "GET",
                data: {
                    id_responsable: id_responsable,
                    id_schoolYear: id_schoolYear
                },
                dataType: "json",
                success: function(response) {
                    let tablaMensualidades = $('#estudiantes-mensualidades-list');
                    tablaMensualidades.empty();

                    if (response.status === 'success') {
                        let estudiantes = response.data;

                        if (estudiantes.length > 0) {
                            $.each(estudiantes, function(index, estudiante) {
                                let mesesPendientes = '';
                                
                                $.each(estudiante.meses_pendientes, function(i, mes) {
                                    mesesPendientes += `
                                        <div class="form-check">
                                            <input class="form-check-input mes-checkbox" type="checkbox"
                                                name="meses[${estudiante.id}][]" 
                                                value="${mes.numero}" 
                                                data-monto="${mes.monto}"
                                                id="mes-${estudiante.id}-${mes.numero}">
                                            <label class="form-check-label" for="mes-${estudiante.id}-${mes.numero}">
                                                ${mes.nombre} - RD$${mes.monto}
                                            </label>
                                        </div>
                                    `;
                                });

                                tablaMensualidades.append(`
                                    <tr>
                                        <td>${estudiante.nombre} ${estudiante.apellido}</td>
                                        <td>${estudiante.curso}</td>
                                        <td>${mesesPendientes}</td>
                                        <td>
                                            <input type="text" class="form-control monto-total-estudiante" 
                                                id="monto-estudiante-${estudiante.id}" 
                                                value="0.00" readonly>
                                        </td>
                                        <td>
                                            <input type="checkbox" class="form-check-input estudiante-checkbox" 
                                                name="estudiantes[]" value="${estudiante.id}">
                                        </td>
                                    </tr>
                                `);
                            });

                            $('.mes-checkbox').on('change', function() {
                                actualizarMontos();
                            });

                            $('.estudiante-checkbox').on('change', function() {
                                let estudianteId = $(this).val();
                                let isChecked = $(this).prop('checked');

                                $(`input[name="meses[${estudianteId}][]"]`).prop('checked', isChecked);
                                actualizarMontos();
                            });
                        } else {
                            tablaMensualidades.append('<tr><td colspan="5">No hay mensualidades pendientes.</td></tr>');
                        }
                    } else {
                        tablaMensualidades.append(`<tr><td colspan="5">${response.message}</td></tr>`);
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    $('#estudiantes-mensualidades-list').html('<tr><td colspan="5">Error al cargar los datos.</td></tr>');
                }
            });
        }

        // 🔥 Función para actualizar montos
        function actualizarMontos() {
            let totalGeneral = 0;

            $('.estudiante-checkbox').each(function() {
                let estudianteId = $(this).val();
                let totalEstudiante = 0;

                $(`input[name="meses[${estudianteId}][]"]:checked`).each(function() {
                    totalEstudiante += parseFloat($(this).data('monto'));
                });

                $(`#monto-estudiante-${estudianteId}`).val(totalEstudiante.toFixed(2));
                totalGeneral += totalEstudiante;
            });

            $('#total_pago').val(totalGeneral.toFixed(2));
        }

    });
</script>
<?= $this->endSection() ?>
