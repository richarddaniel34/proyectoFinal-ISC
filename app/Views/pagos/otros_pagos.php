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
                                <form action="<?= base_url('pagos/registrarPagoMensualidad'); ?>" method="POST">

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

        // 🔹 Select2
        $('#id_responsable').select2({
            theme: "bootstrap-4",
            placeholder: "Buscar responsable...",
            allowClear: true,
            width: '100%'
        }).next('.select2-container').addClass('form-control');

        // 🔹 Detectar cambios
        $('#id_responsable, #id_schoolYear').on('change', function() {
            let id_responsable = $('#id_responsable').val();
            let id_schoolYear = $('#id_schoolYear').val();

            if (id_responsable && id_schoolYear) {
                cargarMensualidadesPendientes(id_responsable, id_schoolYear);
            }
        });

        //  Cargar mensualidades
        function cargarMensualidadesPendientes(id_responsable, id_schoolYear) {

            $.ajax({
                url: "<?= base_url('pagos/obtenerMensualidadesPendientes') ?>",
                type: "GET",
                data: {
                    id_responsable,
                    id_schoolYear
                },
                dataType: "json",

                success: function(response) {

                    let tabla = $('#estudiantes-mensualidades-list');
                    tabla.empty();

                    if (response.status === 'success' && response.data.length > 0) {

                        response.data.forEach(est => {

                            let mesesHTML = '';

                            const pagados = (est.meses_pagados || []).map(m => m.toLowerCase());

                            est.meses_pendientes.forEach(mes => {

                                let nombreMes = mes.nombre.toLowerCase();
                                let isPagado = pagados.includes(nombreMes);

                                mesesHTML += `
                                <div class="form-check">
                                    <input class="form-check-input mes-checkbox"
                                        type="checkbox"
                                        name="meses[${est.id}][]"
                                        value="${mes.nombre}"
                                        data-monto="${mes.monto}"
                                        data-estudiante="${est.id}"
                                        ${isPagado ? 'checked disabled' : ''}>

                                    <label class="form-check-label ${isPagado ? 'text-success fw-bold' : ''}">
                                        ${mes.nombre} - RD$${mes.monto}
                                        ${isPagado ? '✔ Pagado' : ''}
                                    </label>
                                </div>
                            `;
                            });

                            tabla.append(`
                            <tr>
                                <td>${est.nombre} ${est.apellido}</td>
                                <td>${est.curso}</td>
                                <td>${mesesHTML}</td>
                                <td>
                                    <input type="text" class="form-control"
                                        id="total-${est.id}" value="0.00" readonly>
                                </td>
                                <td>
                                    <input type="checkbox"
                                        class="estudiante-checkbox"
                                        value="${est.id}">
                                </td>
                            </tr>
                        `);
                        });

                    } else {
                        tabla.append(`<tr><td colspan="5">No hay mensualidades pendientes.</td></tr>`);
                    }
                }
            });
        }

        //  Evento: seleccionar mes
        $(document).on('change', '.mes-checkbox', function() {

            let estudianteId = $(this).data('estudiante');

            let algunoMarcado = $(`.mes-checkbox[data-estudiante="${estudianteId}"]:checked`).length > 0;

            $(`.estudiante-checkbox[value="${estudianteId}"]`)
                .prop('checked', algunoMarcado);

            actualizarMontos();
        });

        //  Evento: seleccionar estudiante
        $(document).on('change', '.estudiante-checkbox', function() {

            let estudianteId = $(this).val();
            let checked = $(this).prop('checked');

            $(`.mes-checkbox[data-estudiante="${estudianteId}"]`)
                .not(':disabled') //  CLAVE: no tocar meses pagados
                .prop('checked', checked);

            actualizarMontos();
        });

        //  Calcular totales (VERSIÓN CORREGIDA)
        function actualizarMontos() {

            let totalGeneral = 0;

            let estudiantes = new Set();

            $('.mes-checkbox').each(function() {
                estudiantes.add($(this).data('estudiante'));
            });

            estudiantes.forEach(estudianteId => {

                let totalEst = 0;

                $(`.mes-checkbox[data-estudiante="${estudianteId}"]:checked`).each(function() {

                    if (!$(this).is(':disabled')) { //  CLAVE FINAL

                        totalEst += parseFloat($(this).data('monto')) || 0;
                    }
                });

                $(`#total-${estudianteId}`).val(totalEst.toFixed(2));

                totalGeneral += totalEst;
            });

            $('#total_pago').val(totalGeneral.toFixed(2));
        }

        //  Validación antes de enviar
        $('form').on('submit', function(e) {

            let valido = false;

            $('.estudiante-checkbox:checked').each(function() {
                let id = $(this).val();

                if ($(`.mes-checkbox[data-estudiante="${id}"]:checked:not(:disabled)`).length > 0) {
                    valido = true;
                }
            });

            if (!valido) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Debe seleccionar al menos un mes para un estudiante.'
                });
            }
        });

    });
</script>
<?= $this->endSection() ?>