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

            <form action="<?= base_url('pagos/registrarPagoMensualidad'); ?>"
                method="POST"
                class="formulario-personalizado pago-mensualidad-form">

                <div class="pago-filtros-card">
                    <div class="tabla-header">
                        <h5><i class="fa-solid fa-filter"></i> Datos de consulta</h5>
                        <small>Seleccione a un estudiante para cargar las mensualidades pendientes.</small>
                    </div>
                    <input type="hidden" id="id_responsable" name="id_responsable">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="id_responsable">Seleccione un estudainte</label>
                                <select class="form-control select2" id="id_estudiante_busqueda" name="id_estudiante_busqueda" required>
                                    <option value="">Seleccione un estudiante</option>
                                    <?php foreach ($estudiantesSelect as $estudiante): ?>
                                        <option value="<?= esc($estudiante['id']); ?>">
                                            <?= esc($estudiante['nombre']) . ' ' . esc($estudiante['apellido']) . ' - ' . esc($estudiante['matricula']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
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
                </div>


                <div class="pago-mini-resumen">
                    <div>
                        <span>Estudiantes encontrados</span>
                        <strong id="contador-estudiantes">0</strong>
                    </div>
                    <div>
                        <span>Meses</span>
                        <strong id="contador-meses">0</strong>
                    </div>
                    <div>
                        <span>Total actual</span>
                        <strong>RD$ <span id="total-mini">0.00</span></strong>
                    </div>
                </div>

                <div class="pago-detalle-card">
                    <div class="tabla-header">
                        <h5>
                            <i class="fa-solid fa-money-bill-wave"></i>
                            Detalle de cobros pendientes
                        </h5>
                        <small>
                            Seleccione las mensualidades que serán incluidas en la transacción.
                        </small>
                    </div>

                    <div id="estudiantes-mensualidades-list" class="mensualidades-container">
                        <div class="tabla-empty">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>
                                Seleccione un responsable y un año escolar para cargar los cobros pendientes.
                            </span>
                        </div>
                    </div>
                </div>

                <div class="pago-resumen-final mensualidad-resumen-final">

                    <div class="metodo-pago-card">
                        <i class="fas fa-money-bill-wave"></i>
                        <div>
                            <strong>Efectivo</strong>
                            <small>Único método permitido</small>
                            <input type="hidden" class="form-control" id="metodo_pago" name="metodo_pago" value="Efectivo" readonly>
                        </div>
                    </div>

                    <div class="pago-total-box">
                        <label for="total_pago">Total a pagar</label>

                        <div class="total-pago-visual">
                            <span class="total-moneda">RD$</span>
                            <input type="text" id="total_pago" name="total_pago" value="0.00" readonly>
                        </div>
                    </div>

                    <div class="pago-actions">
                        <button type="submit" class="btn btn-success btn-sistema">
                            <i class="fa-solid fa-check-circle"></i> Registrar Pago
                        </button>

                        <a href="#"
                            onclick="mostrarModalCancelar('<?= base_url('pagos') ?>')"
                            class="btn btn-danger btn-sistema">
                            <i class="fa-solid fa-ban"></i> Cancelar
                        </a>
                    </div>

                </div>

            </form>

        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    window.PAGOS_MENSUALIDAD = {
        urlMensualidades: "<?= base_url('pagos/obtenerMensualidadesPendientes') ?>",
        urlGrupoFamiliar: "<?= base_url('pagos/obtenerGrupoFamiliarPorEstudiante') ?>"
    };
</script>

<script src="<?= base_url('js/pagos/mensualidades.js') ?>"></script>
<?= $this->endSection() ?>