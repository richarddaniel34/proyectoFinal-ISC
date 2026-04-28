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
        <h1 class="text-titles"><i class="fa-solid fa-school"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
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
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/escuela/insertar" autocomlete="off" class="formulario-personalizado">
                                    <?= csrf_field() ?>
                                    <ul class="nav nav-tabs" id="registroTabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="escuela-tab" data-toggle="tab" href="#escuela">Datos del Centro </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="administrativos-tab" data-toggle="tab" href="#administrativos-info">Datos Administrativos</a>
                                        </li>
                                    </ul>

                                    <!-- Contenido de las pestañas -->
                                    <div class="tab-content mt-3">
                                        <!-- INFORMACION DE LA ESCUELA -->
                                        <div class="tab-pane fade show active" id="escuela">
                                            <h3>Información de la Escuela</h3>
                                            <small>Los campor marcados con (*) son Obligatorios</small>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="nombre" class="control-label">Nombre:</label>
                                                        <input class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" type="text" id="nombre" name="nombre"
                                                            value="<?= old('nombre'); ?>" autofocus required />
                                                        <?php if (session('errors.nombre')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.nombre'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="id_nivel" class="control-label">NIVEL:</label>
                                                        <select class="form-control <?= session('errors.id_nivel') ? 'is-invalid' : '' ?>" id="id_nivel" name="id_nivel" required>
                                                            <option value="">--Seleccione un Nivel--</option>
                                                            <?php foreach ($niveles as $nivel): ?>
                                                                <option value="<?= esc($nivel['id']); ?>" <?= old('id_nivel') == $nivel['id'] ? 'selected' : ''; ?>>
                                                                    <?= esc($nivel['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="modalidad" class="control-label">MODALIDAD:</label>
                                                        <select class="form-control <?= session('errors.modalidad') ? 'is-invalid' : '' ?>" id="id_modalidad" name="id_modalidad" required>
                                                            <option value="">--Selecciona una Modalidad--</option>
                                                            <?php foreach ($modalidad as $modalidades): ?>
                                                                <option value="<?= esc($modalidades['id']); ?>" <?= old('modalidad') == $modalidades['id'] ? 'selected' : ''; ?>>
                                                                    <?= esc($modalidades['nombre']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="Email" class="control-label">Email</label>
                                                        <input class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" type="email" id="email" name="email"
                                                            value="<?= old('email'); ?>" />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="telefono" class="control-label">Telefono:</label>
                                                        <input class="form-control <?= session('errors.telefono') ? 'is-invalid' : '' ?>" type="tel" id="telefono" name="telefono"
                                                            value="<?= old('telefono'); ?>" pattern="\d{3}-\d{3}-\d{4}" required />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="redes" class="control-label">Redes:</label>
                                                        <input class="form-control <?= session('errors.redes') ? 'is-invalid' : '' ?>" type="text" id="redes" name="redes"
                                                            value="<?= old('redes'); ?>" required />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="direccion" class="control-label">Direccion:</label>
                                                        <input class="form-control <?= session('errors.direccion') ? 'is-invalid' : '' ?>" type="text" id="direccion" name="direccion"
                                                            value="<?= old('direccion'); ?>" required />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="web" class="control-label">web:</label>
                                                        <input class="form-control <?= session('errors.web') ? 'is-invalid' : '' ?>" type="url" id="web" name="web"
                                                            value="<?= old('web'); ?>" required />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="logo" class="control-label">Logo</label>
                                                        <input type="file" name="logo" id="logo" class="form-control <?= session('errors.logo') ? 'is-invalid' : '' ?>">
                                                        <?php if (session('errors.logo')): ?>
                                                            <div class="invalid-feedback">
                                                                <?= session('errors.logo'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-primary" id="btnSiguiente" onclick="siguiente('administrativos-info')">Siguiente <i class="fa-solid fa-forward"></i></button>
                                                <a href="<?= base_url(); ?>/escuela" class="btn btn-danger btn-raised">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </a>

                                            </div>
                                        </div>

                                        <div class="tab-pane fade" id="administrativos-info">
                                            <h3>Datos Administrativos</h3>
                                            <small>Los campor marcados con (*) son Obligatorios</small>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="codigo_gestion" class="control-label">Codigo SIGERD:</label>
                                                        <input class="form-control <?= session('errors.codigo_gestion') ? 'is-invalid' : '' ?>" type="text" id="codigo_gestion" name="codigo_gestion"
                                                            value="<?= old('codigo_gestion'); ?>" required />
                                                        <?php if (session('errors.codigo_gestion')): ?>
                                                            <div class="invalid-feedback alert alert-danger">
                                                                <?= session('errors.codigo_gestion'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="codigo_plantel" class="control-label">Codigo Plantel:</label>
                                                        <input class="form-control <?= session('errors.codigo_plantel') ? 'is-invalid' : '' ?>" type="text" id="codigo_plantel" name="codigo_plantel"
                                                            value="<?= old('codigo_plantel'); ?>" required />
                                                        <?php if (session('errors.codigo_plantel')): ?>
                                                            <div class="invalid-feedback">
                                                                <?= session('errors.codigo_plantel'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="rnc" class="control-label">RNC:</label>
                                                        <input class="form-control <?= session('errors.rnc') ? 'is-invalid' : '' ?>" type="text" id="rnc" name="rnc"
                                                            value="<?= old('rnc'); ?>" required />
                                                        <?php if (session('errors.rnc')): ?>
                                                            <div class="invalid-feedback">
                                                                <?= session('errors.rnc'); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="distrito_educativo" class="control-label">Distrito Educativo:</label>
                                                        <select class="form-control <?= session('errors.distrito_educativo') ? 'is-invalid' : '' ?>"
                                                            id="distrito_educativo"
                                                            name="distrito_educativo" required>
                                                            <option value="<?= esc($distritoEducativo['id']) ?>" selected>
                                                                <?= esc($distritoEducativo['distrito_educativo']) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="tanda" class="control-label">Tanda:</label>
                                                        <select name="tanda" id="tanda" class="form-control <?= session('errors.tanda') ? 'is-invalid' : '' ?>" required>
                                                            <option value="">Seleccione una tanda</option>
                                                            <?php foreach ($tandas as $key => $valor): ?>
                                                                <option value="<?= esc($key) ?>" <?= old('tanda') == $key ? 'selected' : '' ?>>
                                                                    <?= esc($valor) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="tipo" class="control-label">Tipo:</label>
                                                        <select name="tipo" id="tipo" class="form-control <?= session('errors.tipo') ? 'is-invalid' : '' ?>" required>
                                                            <option value="">Seleccione el tipo</option>
                                                            <?php foreach ($tipos as $key => $valor): ?>
                                                                <option value="<?= esc($key) ?>" <?= old('tipo') == $key ? 'selected' : '' ?>>
                                                                    <?= esc($valor) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>



                                            </div>

                                            <div class="text-center mt-4">
                                                <button type="button" class="btn btn-secondary" onclick="siguiente('escuela')"><i class="fa-solid fa-backward"></i> Atrás</button>

                                                <a
                                                    href="<?php echo base_url(); ?>/escuela"
                                                    class="btn btn-danger btn-raised">
                                                    <i class="fa-solid fa-ban"></i> Cancelar
                                                </a>

                                                <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
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




<!--=========== SCRIPTS PARA EL FUNCIONAMIENTO DE LA SECCION ===========-->

<?= $this->section('scripts') ?>



<script>
    function siguiente(tab) {
        $('#registroTabs a[href="#' + tab + '"]').tab('show');
    }
</script>


<?= $this->endSection() ?>