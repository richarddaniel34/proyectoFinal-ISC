<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Factura #<?= $factura['numero_factura'] ?></h5>
            <a href="<?= base_url('inscripciones/imprimirFactura/' . $factura['id']) ?>" class="btn btn-light" target="_blank">
                <i class="fas fa-print"></i> Imprimir Factura
            </a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Datos del Cliente</h6>
                    <p><strong>Responsable:</strong> <?= $factura['nombre_responsable'] ?></p>
                    <p><strong>Fecha de emisión:</strong> <?= date('d/m/Y', strtotime($factura['fecha_emision'])) ?></p>
                </div>
                <div class="col-md-6 text-md-right">
                    <h6 class="text-muted">Estado de Factura</h6>
                    <p><span class="badge bg-success"><?= $factura['estado'] ?></span></p>
                    <p><strong>Total:</strong> $<?= number_format($factura['total'], 2) ?></p>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Estudiante</th>
                            <th class="text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalles as $detalle): ?>
                        <tr>
                            <td><?= $detalle['concepto'] ?></td>
                            <td><?= $detalle['estudiante'] ?></td>
                            <td class="text-right">$<?= number_format($detalle['monto'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-right">Total</th>
                            <th class="text-right">$<?= number_format($factura['total'], 2) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="mt-4">
                <a href="<?= base_url('inscripciones') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a Inscripciones
                </a>
            </div>
        </div>
    </div>
</div>