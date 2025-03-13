<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class=" fa-solid fa-users"></i> Configuración/ <small><?php print_r($titulo) ?></small></h1>
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
                                <form method="POST" action="<?= base_url('responsables/actualizar/' . $responsable['id']); ?>" autocomlete="off">
                                    <?= csrf_field() ?>
                                    <input type="text" name="id" value="<?= esc($responsable['id'] ?? ''); ?>">

                                    <!-- Pestañas -->
                                    <ul class="nav nav-tabs" id="registroTabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="padre-tab" data-toggle="tab" href="#padre">Padre</a>
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
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="nombre_padre" class="control-label">Nombre(s):</label>
                                                        <input type="text" name="nombre_padre" class="form-control" 
                                                        value="<?= esc($responsable['nombre_padre'] ?? ''); ?>"/>

                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido_padre" class="control-label">Apellido(s):</label>
                                                        <input class="form-control" type="text" id="apellido_padre" name="apellido_padre"
                                                        value="<?= esc($responsable['apellido_padre'] ?? ''); ?>"  />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="cedula_padre" class="control-label">Cédula:</label>
                                                        <input class="form-control" type="text" id="cedula_padre" name="cedula_padre"
                                                        value="<?= esc($responsable['cedula_padre'] ?? ''); ?>"  />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_padre" class="control-label">Teléfono:</label>
                                                        <input class="form-control" type="text" id="telefono_padre" name="telefono_padre"
                                                        value="<?= esc($responsable['telefono_padre'] ?? ''); ?>"  />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion_padre" class="control-label">Dirección:</label>
                                                        <input class="form-control" type="text" id="direccion_padre" name="direccion_padre" 
                                                        value="<?= esc($responsable['direccion_padre'] ?? ''); ?>"/>
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="trabajo_padre" class="control-label">Lugar de trabajo:</label>
                                                        <input class="form-control" type="text" id="trabajo_padre" name="trabajo_padre" 
                                                        value="<?= esc($responsable['trabajo_padre'] ?? ''); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo_padre" class="control-label">Telefono trabajo:</label>
                                                        <input class="form-control" type="text" id="telefono_trabajo_padre" name="telefono_trabajo_padre" 
                                                        value="<?= esc($responsable['telefono_trabajo_padre'] ?? ''); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="siguiente('madre')">Siguiente</button>
                                        </div>

                                        <!-- Madre -->
                                        <div class="tab-pane fade" id="madre">
                                            <h3>Datos de la Madre</h3>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="nombre_madre" class="control-label">Nombre(s):</label>
                                                        <input class="form-control" type="text" id="nombre_madre" name="nombre_madre" 
                                                        value="<?= esc($responsable['nombre_madre'] ?? ''); ?>" />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido_madre" class="control-label">Apellido(s):</label>
                                                        <input class="form-control" type="text" id="apellido_madre" name="apellido_madre" 
                                                        value="<?= esc($responsable['apellido_madre'] ?? ''); ?>" /> 
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="cedula_madre" class="control-label">Cédula:</label>
                                                        <input class="form-control" type="text" id="cedula_madre" name="cedula_madre" 
                                                        value="<?= esc($responsable['cedula_madre'] ?? ''); ?>" /> 
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_madre" class="control-label">Teléfono:</label>
                                                        <input class="form-control" type="text" id="telefono_madre" name="telefono_madre"
                                                        value="<?= esc($responsable['telefono_madre'] ?? ''); ?>" />  
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion_madre" class="control-label">Dirección:</label>
                                                        <input class="form-control" type="text" id="direccion_madre" name="direccion_madre"
                                                        value="<?= esc($responsable['direccion_madre'] ?? ''); ?>" /> 
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="trabajo_madre" class="control-label">Lugar de trabajo:</label>
                                                        <input class="form-control" type="text" id="trabajo_madre" name="trabajo_madre" 
                                                        value="<?= esc($responsable['trabajo_madre'] ?? ''); ?>" /> 
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo_madre" class="control-label">Telefono trabajo:</label>
                                                        <input class="form-control" type="text" id="telefono_trabajo_madre" name="telefono_trabajo_madre"
                                                        value="<?= esc($responsable['telefono_trabajo_madre'] ?? ''); ?>" /> 
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
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="nombre_tutor" class="control-label">Nombre(s):</label>
                                                        <input class="form-control" type="text" id="nombre_tutor" name="nombre_tutor" 
                                                        value="<?= esc($responsable['nombre_tutor'] ?? ''); ?>" />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido_tutor" class="control-label">Apellido(s):</label>
                                                        <input class="form-control" type="text" id="apellido_tutor" name="apellido_tutor" 
                                                        value="<?= esc($responsable['apellido_tutor'] ?? ''); ?>" /> 
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="cedula_tutor" class="control-label">Cédula:</label>
                                                        <input class="form-control" type="text" id="cedula_tutor" name="cedula_tutor"
                                                        value="<?= esc($responsable['cedula_tutor'] ?? ''); ?>" /> 
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_tutor" class="control-label">Teléfono:</label>
                                                        <input class="form-control" type="text" id="telefono_tutor" name="telefono_tutor" 
                                                        value="<?= esc($responsable['telefono_tutor'] ?? ''); ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion_tutor" class="control-label">Dirección:</label>
                                                        <input class="form-control" type="text" id="direccion_tutor" name="direccion_tutor" 
                                                        value="<?= esc($responsable['direccion_tutor'] ?? ''); ?>" />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="trabajo_tutor" class="control-label">Telefono trabajo:</label>
                                                        <input class="form-control" type="text" id="trabajo_tutor" name="trabajo_tutor" 
                                                        value="<?= esc($responsable['trabajo_tutor'] ?? ''); ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-3">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo_tutor" class="control-label">Telefono trabajo:</label>
                                                        <input class="form-control" type="text" id="telefono_trabajo_tutor" name="telefono_trabajo_tutor" 
                                                        value="<?= esc($responsable['telefono_trabajo_tutor'] ?? ''); ?>" />
                                                    </div>
                                                </div>
                                            </div>

                                            </div>
                                            <button type="button" class="btn btn-secondary" onclick="siguiente('madre')">Atrás</button>
                                            <button type="submit" class="btn btn-success">Actualizar</button>
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