<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class=" fa-solid fa-users"></i> Registro/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>
<br>

<!-- Código de depuración temporal -->


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-xs-12 col-md-10 offset-md-1">
                                <form method="POST" action="<?php echo base_url(); ?>responsables/insertar" autocomlete="off">
                                    <?= csrf_field() ?>

                                    <!-- Pestañas -->
                                    <ul class="nav nav-tabs" id="registroTabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="padre-tab" data-toggle="tab" href="#padre">Padre </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="madre-tab" data-toggle="tab" href="#madre">Madre</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tutor-tab" data-toggle="tab" href="#tutor">Tutor</a>
                                        </li>
                                    </ul>

                                    <!-- Contenido de las pestañas -->
                                    <div class="tab-content mt-3">
                                        <!-- Padre -->
                                        <div class="tab-pane fade show active" id="padre">
                                            <h3>Datos del Padre</h3>
                                            <small>Los campor marcados con (*) son Obligatorios</small>
                                            <div class="row">
                                                <input type="hidden" name="responsables[0][tipo_responsable]" value="padre">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="nombre_padre" class="control-label">Nombre(s):</label>
                                                        <input class="form-control" type="text" id="nombre_padre" name="responsables[0][nombre]" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido_padre" class="control-label">Apellido(s):</label>
                                                        <input class="form-control" type="text" id="apellido_padre" name="responsables[0][apellido]" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="cedula_padre" class="control-label">Cédula:</label>
                                                        <?php
                                                        $hasError = isset(session('errors')['responsables.0.cedula']);
                                                        $errorClass = $hasError ? 'is-invalid' : '';
                                                        ?>
                                                        <input class="form-control <?= $errorClass ?>"
                                                            type="text"
                                                            id="cedula_padre"
                                                            name="responsables[0][cedula]"
                                                            value="<?= old('responsables.0.cedula') ?>" />
                                                        <?php if ($hasError): ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= session('errors')['responsables.0.cedula'] ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <small class="aviso text-danger">*</small>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_padre" class="control-label">Celular:</label>
                                                        <input class="form-control" type="text" id="celular_padre" name="responsables[0][celular]" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_padre" class="control-label">Teléfono:</label>
                                                        <input class="form-control" type="text" id="telefono_padre" name="responsables[0][telefono]" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion_padre" class="control-label">Dirección:</label>
                                                        <input class="form-control" type="text" id="direccion_padre" name="responsables[0][direccion]" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="trabajo_padre" class="control-label">Lugar de trabajo:</label>
                                                        <input class="form-control" type="text" id="trabajo_padre" name="responsables[0][trabajo]" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo_padre" class="control-label">Telefono trabajo:</label>
                                                        <input class="form-control" type="text" id="telefono_trabajo_padre" name="responsables[0][telefono_trabajo]" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo_padre" class="control-label">Contacto de emergencia:</label>
                                                        <input class="form-control" type="text" id="contacto_emergencia_padre" name="responsables[0][contacto_emergencia]" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="siguiente('madre')">Siguiente</button>
                                        </div>

                                        <!-- Madre -->
                                        <div class="tab-pane fade" id="madre">
                                            <h3>Datos de la Madre</h3>
                                            <small>Los campor marcados con (*) son Obligatorios</small>
                                            <div class="row">
                                                <input type="hidden" name="responsables[1][tipo_responsable]" value="madre">
                                                <!-- Ejemplo para un campo de la madre -->
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="nombre_madre" class="control-label">Nombre(s):</label>
                                                        <input class="form-control <?= session('errors.responsables.1.nombre') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="nombre_madre"
                                                            name="responsables[1][nombre]"
                                                            value="<?= old('responsables.1.nombre') ?>" />
                                                        <small class="aviso text-danger">*</small>
                                                        <?php if (session('errors.responsables.1.nombre')): ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= session('errors.responsables.1.nombre') ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Ejemplo para otro campo de la madre -->
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido_madre" class="control-label">Apellido(s):</label>
                                                        <input class="form-control <?= session('errors.responsables.1.apellido') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="apellido_madre"
                                                            name="responsables[1][apellido]"
                                                            value="<?= old('responsables.1.apellido') ?>" />
                                                        <small class="aviso text-danger">*</small>
                                                        <?php if (session('errors.responsables.1.apellido')): ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= session('errors.responsables.1.apellido') ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <!-- Para el campo de cédula de la madre -->
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="cedula_madre" class="control-label">Cédula:</label>
                                                        <?php
                                                        $hasError = isset(session('errors')['responsables.1.cedula']);
                                                        $errorClass = $hasError ? 'is-invalid' : '';
                                                        ?>
                                                        <input class="form-control <?= $errorClass ?>"
                                                            type="text"
                                                            id="cedula_madre"
                                                            name="responsables[1][cedula]"
                                                            value="<?= old('responsables.1.cedula') ?>" />
                                                        <?php if ($hasError): ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= session('errors')['responsables.1.cedula'] ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <small class="aviso text-danger">*</small>

                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_madre" class="control-label">Celular:</label>
                                                        <input class="form-control <?= session('errors.responsables.1.celular') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="celular_madre"
                                                            name="responsables[1][celular]"
                                                            value="<?= old('responsables.1.celular') ?>" />
                                                        <small class="aviso text-danger">*</small>
                                                        <?php if (session('errors.responsables.1.celular')): ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= session('errors.responsables.1.celular') ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_madre" class="control-label">Teléfono:</label>
                                                        <input class="form-control <?= session('errors.responsables.1.telefono') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="telefono_madre"
                                                            name="responsables[1][telefono]"
                                                            value="<?= old('responsables.1.telefono') ?>" />
                                                        <?php if (session('errors.responsables.1.telefono')): ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= session('errors.responsables.1.telefono') ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion_madre" class="control-label">Dirección:</label>
                                                        <input class="form-control <?= session('errors.responsables.1.direccion') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="direccion_madre"
                                                            name="responsables[1][direccion]"
                                                            value="<?= old('responsables.1.direccion') ?>" />
                                                        <small class="aviso text-danger">*</small>
                                                        <?php if (session('errors.responsables.1.direccion')): ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= session('errors.responsables.1.direccion') ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="trabajo_madre" class="control-label">Lugar de trabajo:</label>
                                                        <input class="form-control" type="text" id="trabajo_madre" name="responsables[1][trabajo]" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo_madre" class="control-label">Telefono trabajo:</label>
                                                        <input class="form-control" type="text" id="telefono_trabajo_madre" name="responsables[1][telefono_trabajo]" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo_madre" class="control-label">Contacto de emergencia:</label>
                                                        <input class="form-control" type="text" id="contacto_emergencia_madre" name="responsables[1][contacto_emergencia]" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-secondary" onclick="siguiente('padre')">Atrás</button>
                                            <button type="button" class="btn btn-primary" onclick="siguiente('tutor')">Siguiente</button>
                                        </div>

                                        <!-- Tutor -->
                                        <div class="tab-pane fade" id="tutor">
                                            <h3>Datos del Tutor (Opcional)</h3>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="necesita_tutor" onclick="toggleTutor()">
                                                <label class="form-check-label">Agregar tutor</label>
                                            </div>
                                            <div id="tutor-info" style="display: none;">
                                                <div class="row">
                                                    <input type="hidden" name="responsables[2][tipo_responsable]" value="tutor">
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="nombre_tutor" class="control-label">Nombre(s):</label>
                                                            <input class="form-control" type="text" id="nombre_tutor" name="responsables[2][nombre]" />
                                                            <small class="aviso text-danger">*</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="apellido_tutor" class="control-label">Apellido(s):</label>
                                                            <input class="form-control" type="text" id="apellido_tutor" name="responsables[2][apellido]" />
                                                            <small class="aviso text-danger">*</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="cedula_tutor" class="control-label">Cédula:</label>
                                                            <input class="form-control" type="text" id="cedula_tutor" name="responsables[2][cedula]" />
                                                            <small class="aviso text-danger">*</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="telefono_tutor" class="control-label">Celular:</label>
                                                            <input class="form-control" type="text" id="celular_tutor" name="responsables[2][celular]" />
                                                            <small class="aviso text-danger">*</small>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="telefono_tutor" class="control-label">Teléfono:</label>
                                                            <input class="form-control" type="text" id="telefono_tutor" name="responsables[2][telefono]" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="direccion_tutor" class="control-label">Dirección:</label>
                                                            <input class="form-control" type="text" id="direccion_tutor" name="responsables[2][direccion]" />
                                                            <small class="aviso text-danger">*</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="trabajo_tutor" class="control-label">Lugar de trabajo:</label>
                                                            <input class="form-control" type="text" id="trabajo_tutor" name="responsables[2][trabajo]" />
                                                        </div>
                                                    </div>
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="telefono_trabajo_tutor" class="control-label">Telefono trabajo:</label>
                                                            <input class="form-control" type="text" id="telefono_trabajo_tutor" name="responsables[2][telefono_trabajo]" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-3">
                                                        <div class="form-group label-floating">
                                                            <label for="telefono_trabajo_tutor" class="control-label">Contacto de emergencia:</label>
                                                            <input class="form-control" type="text" id="contacto_emergencia_tutor" name="responsables[2][contacto_emergencia]" />
                                                            <small class="aviso text-danger">*</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <button type="button" class="btn btn-secondary" onclick="siguiente('madre')">Atrás</button>
                                            <button type="submit" class="btn btn-success">Guardar</button>
                                        </div>
                                    </div>
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
    function siguiente(tab) {
        $('#registroTabs a[href="#' + tab + '"]').tab('show');
    }

    function toggleTutor() {
        if (document.getElementById('necesita_tutor').checked) {
            document.getElementById('tutor-info').style.display = 'block';
        } else {
            document.getElementById('tutor-info').style.display = 'none';
        }
    }
</script>



<?= $this->endSection() ?>