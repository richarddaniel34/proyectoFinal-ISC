<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-file-signature"></i>
            <?= esc($titulo_1) ?>/ <small><?= esc($titulo_2) ?></small>
        </h1>
    </div>
</div>

<div class="container-fluid py-3">
    <form action="<?= base_url('pagos/registrar_inscripcion'); ?>" method="POST" class="formulario-personalizado pago-inscripcion-form">


        <input type="hidden" id="id_responsable" name="id_responsable">
        <input type="hidden" id="id_schoolYear" name="id_schoolYear" value="<?= esc($schoolYearActivo['id'] ?? ''); ?>">
        <input type="hidden" id="id_schoolYear_actual" value="<?= esc($schoolYearActivo['id'] ?? ''); ?>">
        <input type="hidden" id="id_schoolYear_espera" value="<?= esc($schoolYearEspera['id'] ?? ''); ?>">

        <div class="pago-filtros-card">
            <div class="row align-items-end">
                <div class="col-md-8 mb-3 mb-md-0">
                    <div class="form-group label-floating">
                        <label for="id_estudiante_busqueda" class="form-label"><b>BUSCAR ESTUDIANTE:</b></label>
                        <select class="form-control select2" id="id_estudiante_busqueda" name="id_estudiante_busqueda" required>
                            <option value="">Seleccione un estudiante</option>
                            <?php foreach ($estudiantesSelect as $estudiante): ?>
                                <option value="<?= esc($estudiante['id']); ?>">
                                    <?= esc($estudiante['nombre']) . ' ' . esc($estudiante['apellido']) . ' - ' . esc($estudiante['matricula']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small id="infoResponsable" class="form-text text-muted"></small>
                    </div>
                </div>

                <div class="pago-completo-box">
                    <input
                        type="checkbox"
                        id="pago_completo"
                        name="pago_completo"
                        value="1">

                    <div class="pago-completo-texto">
                        <label for="pago_completo">
                            Pagar todo el año
                        </label>

                        <small>
                            Incluye inscripción y mensualidades
                        </small>
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
                <span>Seleccionados</span>
                <strong id="contador-seleccionados">0</strong>
            </div>
            <div>
                <span>Total actual</span>
                <strong>RD$ <span id="total-mini">0.00</span></strong>
            </div>
        </div>

        <div class="pago-tabla-card">
            <div class="tabla-header">
                <h5><i class="fas fa-users"></i> Grupo familiar asociado</h5>
                <small>Las filas bloqueadas indican estudiantes que ya tienen inscripción registrada.</small>
            </div>

            <div class="table-responsive">
                <table class="table pago-tabla align-middle text-center">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Matrícula</th>
                            <th>Proceso</th>
                            <th>Servicio</th>
                            <th>Curso</th>
                            <th>Monto</th>
                            <th>Seleccionar</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="estudiantes-list">
                        <tr>
                            <td colspan="8" class="tabla-empty">
                                <i class="fas fa-search"></i>
                                <span>Seleccione un estudiante para cargar el grupo familiar.</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pago-resumen-final">
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

                    <input
                        type="text"
                        id="total_pago"
                        name="total_pago"
                        value="0.00"
                        readonly>
                </div>
            </div>

            <div class="pago-detalle-box" id="resumen-inscripcion">
                <strong>Resumen</strong>
                <ul id="resumen-detalle" class="list-unstyled mb-0">
                    <li>Seleccione estudiantes para ver el total.</li>
                </ul>
            </div>

            <div class="pago-actions">
                <button type="submit" id="btn-registrar-inscripcion" class="btn btn-success btn-lg" disabled>
                    <i class="fas fa-check-circle"></i> Registrar Inscripción
                </button>

                <a href="<?= base_url('pagos'); ?>" class="btn btn-danger btn-lg">
                    <i class="fas fa-times-circle"></i> Cancelar
                </a>
            </div>
        </div>

    </form>
</div>
<?= $this->section('scripts') ?>

<script>
    window.PAGOS_INSCRIPCION = {
        urlGrupoFamiliar: "<?= base_url('pagos/obtenerGrupoFamiliarPorEstudiante') ?>",
        urlCursos: "<?= base_url('estructura-academica/obtenerCursosPorServicioInscripcion') ?>",
        costoInscripcion: <?= esc($concepto_inscripcion['monto']); ?>,
        costoMensualidad: <?= esc($concepto_mensualidad['monto']); ?>,
        cantidadMensualidades: <?= esc($cantidad_mensualidades); ?>
    };
</script>

<script src="<?= base_url('js/pagos/nueva-inscripcion.js') ?>"></script>

<?= $this->endSection() ?>