<pre><?php print_r(session()->getFlashdata('errors')); ?></pre>
<?php $datos = $datos ?? []; ?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-school"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new">
                    <div class="container-fluid">
                        <div class="row">
                            
                            <div class="col-xs-12 col-md-10 offset-md-1">
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>escuela/actualizar" autocomlete="off" class="formulario-personalizado">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?php echo $datos['id']; ?>" />
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
                                                        <input class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="nombre"
                                                            name="nombre"
                                                            value="<?= old('nombre', $datos['nombre'] ?? '') ?>"
                                                            autofocus required />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="id_nivel" class="control-label">NIVEL:</label>
                                                        <select class="form-control <?= session('errors.id_nivel') ? 'is-invalid' : '' ?>" id="id_nivel" name="id_nivel" required>
                                                            <option value="">--Seleccione un Nivel--</option>
                                                            <?php foreach ($niveles as $nivel): ?>
                                                                <option value="<?= esc($nivel['id']) ?>"
                                                                    <?= old('id_nivel', $datos['id_nivel'] ?? '') == $nivel['id'] ? 'selected' : '' ?>>
                                                                    <?= esc($nivel['nombre']) ?>
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
                                                            <?php foreach ($modalidad as $modal): ?>
                                                                <option value="<?= esc($modal['id']) ?>"
                                                                    <?= old('modalidad', $datos['id_modalidad'] ?? '') == $modal['id'] ? 'selected' : '' ?>>
                                                                    <?= esc($modal['nombre']) ?>
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
                                                        <input class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
                                                            type="email"
                                                            id="email"
                                                            name="email"
                                                            value="<?= old('email', $datos['email'] ?? '') ?>" />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="telefono" class="control-label">Telefono:</label>
                                                        <input class="form-control <?= session('errors.telefono') ? 'is-invalid' : '' ?>"
                                                            type="tel"
                                                            id="telefono"
                                                            name="telefono"
                                                            value="<?= old('telefono', $datos['telefono'] ?? '') ?>"
                                                            pattern="\d{3}-\d{3}-\d{4}" required />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="redes" class="control-label">Redes:</label>
                                                        <input class="form-control <?= session('errors.redes') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="redes"
                                                            name="redes"
                                                            value="<?= old('redes', $datos['redes'] ?? '') ?>" required />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="direccion" class="control-label">Direccion:</label>
                                                        <input class="form-control <?= session('errors.direccion') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="direccion"
                                                            name="direccion"
                                                            value="<?= old('direccion', $datos['direccion'] ?? '') ?>" required />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="web" class="control-label">web:</label>
                                                        <input class="form-control <?= session('errors.web') ? 'is-invalid' : '' ?>"
                                                            type="url"
                                                            id="web"
                                                            name="web"
                                                            value="<?= old('web', $datos['web'] ?? '') ?>" required />
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
                                                        <input class="form-control <?= session('errors.codigo_gestion') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="codigo_gestion"
                                                            name="codigo_gestion"
                                                            value="<?= old('codigo_gestion', $datos['codigo_gestion'] ?? '') ?>" required />
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group ">
                                                        <label for="codigo_plantel" class="control-label">Codigo Plantel:</label>
                                                        <input class="form-control <?= session('errors.codigo_plantel') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="codigo_plantel"
                                                            name="codigo_plantel"
                                                            value="<?= old('codigo_plantel', $datos['codigo_plantel'] ?? '') ?>" required />
                                                        <small>*</small>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group">
                                                        <label for="rnc" class="control-label">RNC:</label>
                                                        <input class="form-control <?= session('errors.rnc') ? 'is-invalid' : '' ?>"
                                                            type="text"
                                                            id="rnc"
                                                            name="rnc"
                                                            value="<?= old('rnc', $datos['rnc'] ?? '') ?>" required />
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
                                                            <option value="<?= esc($distritoEducativo['id']) ?>"
                                                                <?= old('distrito_educativo', $datos['distrito_educativo'] ?? '') == $distritoEducativo['id'] ? 'selected' : '' ?>>
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
                                                                <option value="<?= esc($key) ?>" <?= old('tanda', $datos['tanda'] ?? '') == $key ? 'selected' : '' ?>>
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
                                                                <option value="<?= esc($key) ?>" <?= old('tipo', $datos['tipo'] ?? '') == $key ? 'selected' : '' ?>>
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

                                                <button class="btn btn-primary">
                                                    <i class="fa-solid fa-sync-alt"></i> Actualizar
                                                </button>
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


<?php if (session()->getFlashdata('errors')): ?>
    <!-- Flag oculto para que `modal.js` detecte si hay errores -->
    <div id="modal_error_flag" style="display: none;"></div>

    <script>
        $(document).ready(function() {
            Swal.fire({
                icon: 'error',
                title: '¡Errores en el formulario!',
                html: `<ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>`,
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#d33'
            });
        });
    </script>
<?php endif; ?>