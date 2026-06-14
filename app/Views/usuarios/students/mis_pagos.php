<script>
    var baseUrl = "<?= base_url(); ?>";
</script>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-user-graduate"></i>
            <?= esc($titulo_1) ?> / <small><?= esc($titulo_2) ?></small>
        </h1>
    </div>
</div>

<main>
    <div class="container-fluid px-4">

        <div class="pagos-estudiante-card">

            <div class="tabla-header">
                <h5>
                    <i class="fa-solid fa-receipt"></i>
                    Mi historial de pagos
                </h5>
                <small>
                    Consulta el estado de tus mensualidades registradas durante el año escolar.
                </small>
            </div>

            <div class="pago-estudiante-info">
                <div>
                    <span>Alumno</span>
                    <strong><?= esc($estudiante['nombre'] ?? 'Nombre del estudiante') ?></strong>
                </div>

                <div>
                    <span>Curso</span>
                    <strong><?= esc($estudiante['curso'] ?? 'Curso actual') ?></strong>
                </div>

                <div>
                    <span>Mensualidad</span>
                    <strong>RD$ <?= esc(number_format($monto_mensualidad ?? 0, 2)) ?></strong>
                </div>

                <div>
                    <span>Año escolar</span>
                    <strong><?= esc($schoolYear['codigo'] ?? 'Año escolar') ?></strong>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-bordered text-center tabla-pagos-estudiante">
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($meses as $mes): ?>

                            <?php
                            $nombreMes = $mes['nombre'];
                            $pago = $pagosPorMes[$nombreMes] ?? null;
                            ?>

                            <tr>
                                <td><?= esc($nombreMes) ?></td>

                                <td>
                                    <?php if ($pago): ?>
                                        <span class="badge bg-success">Pagado</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pendiente</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= $pago
                                        ? date('d/m/Y', strtotime($pago['fecha_pago']))
                                        : '---'; ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="pagos-estudiante-footer">
                <div>
                    <strong>Nota:</strong>
                    Esta vista es solo de consulta. Para aclaraciones sobre pagos, comuníquese con la administración del centro.
                </div>

                <a href="<?= base_url('home') ?>" class="btn btn-primary btn-sistema">
                    <i class="fa-solid fa-arrow-left"></i> Volver
                </a>
            </div>

        </div>

    </div>
</main>