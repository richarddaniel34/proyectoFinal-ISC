<div class="container-fluid">
    <div class="row">
        <!-- Sección de Imagen -->
        <div class="col-md-3 text-center">
            <div class="image-container mb-3">
                <img src="<?= base_url($datos['foto']); ?>" class="img-fluid rounded shadow-sm" alt="Foto del personal" style="max-width: 180px; border: 2px solid #e9ecef;">
            </div>
        </div>

        <!-- Información Personal -->
        <div class="col-md-9">
            <div class="row">
                <div class="col-sm-5 mb-9">
                    <div class="info-item">
                        <strong class="text-primary">Nombre:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['nombre']); ?></p>
                    </div>
                </div>
                <div class="col-sm-5 mb-2">
                    <div class="info-item">
                        <strong class="text-primary">Apellido(s):</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['apellido']); ?></p>
                    </div>
                </div>

                <div class="col-sm-5 mb-2">
                    <div class="info-item">
                        <strong class="text-primary">Cédula:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['cedula']); ?></p>
                    </div>
                </div>
                <div class="col-sm-5 mb-3">
                    <div class="info-item">
                        <strong class="text-primary">Sexo:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['sexo']); ?></p>
                    </div>
                </div>
                <div class="col-sm-5 mb-3">
                    <div class="info-item">
                        <strong class="text-primary">Célular:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['celular']); ?></p>
                    </div>
                </div>
                <div class="col-sm-5 mb-3">
                    <div class="info-item">
                        <strong class="text-primary">Teléfono:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['telefono']); ?></p>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-sm-4 mb-3">
                    <div class="info-item">
                        <strong class="text-primary">Email:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['email']); ?></p>
                    </div>
                </div>
                <div class="col-sm-4 mb-3">
                    <div class="info-item">
                        <strong class="text-primary">Dirección:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['direccion']); ?></p>
                    </div>
                </div>
                <div class="col-sm-4 mb-3">
                    <div class="info-item">
                        <strong class="text-primary">Fecha de Nacimiento:</strong>
                        <p class="mb-1 ml-2"><?= esc($datos['fecha_nac']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Línea separadora -->
    <hr class="my-4">

    <!-- Botón de Acción -->
    <div class="text-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Cerrar
        </button>
    </div>
</div>