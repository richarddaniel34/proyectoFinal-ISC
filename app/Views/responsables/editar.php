<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
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
                                <form method="POST" action="<?= base_url('responsables/actualizar/' . $responsable['id']); ?>" autocomlete="off" class="formulario-personalizado">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= esc($responsable['id'] ?? ''); ?>">


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
                                                        <label for="nombre" class="control-label">Nombre(s):</label>
                                                        <input type="text" value="<?php echo $responsable['nombre']; ?>" name="nombre" class="form-control" placeholder="">
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="apellido" class="control-label">Apellido(s):</label>
                                                        <input type="text" value="<?php echo $responsable['apellido']; ?>" name="apellido" class="form-control" placeholder="">
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="cedula" class="control-label">Cédula:</label>
                                                        <input type="text" name="cedula" value="<?= esc($responsable['cedula']); ?>" class="form-control" placeholder="">
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="celular" class="control-label">Celular:</label>
                                                        <input class="form-control" type="text" id="celular" value="<?php echo $responsable['celular']; ?>" name="celular" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono" class="control-label">Teléfono:</label>
                                                        <input class="form-control" type="text" id="telefono" value="<?php echo $responsable['telefono']; ?>" name="telefono" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="direccion" class="control-label">Dirección:</label>
                                                        <input class="form-control" type="text" id="direccion" name="direccion" value="<?php echo $responsable['direccion']; ?>" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="trabajo" class="control-label">Lugar de trabajo:</label>
                                                        <input class="form-control" type="text" id="trabajo" name="trabajo" value="<?php echo $responsable['trabajo']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="telefono_trabajo" class="control-label">Telefono trabajo:</label>
                                                        <input class="form-control" type="text" id="telefono_trabajo" name="telefono_trabajo" value="<?php echo $responsable['telefono_trabajo']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="contacto_emergencia" class="control-label">Contacto de emergencia:</label>
                                                        <input class="form-control" type="text" id="contacto_emergencia" name="contacto_emergencia" value="<?php echo $responsable['contacto_emergencia']; ?>" />
                                                        <small class="aviso text-danger">*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <button type="submit" class="btn btn-success">Actualizar</button>
                                                <a href="<?php echo base_url(); ?>responsables/" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-user-plus"></i>Regresar</a>
                                            </div>
                                        </div>

                                    </div>

                            </div>
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