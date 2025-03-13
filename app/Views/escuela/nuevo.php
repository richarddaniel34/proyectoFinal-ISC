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
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/escuela/insertar" autocomlete="off" class="formP">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
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
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="id_nivel" class="control-label"></label>
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
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="modalidad" class="control-label"></label>
                                                <select class="form-control <?= session('errors.modalidad') ? 'is-invalid' : '' ?>" id="modalidad" name="modalidad" required>
                                                    <option value="">--Selecciona una Modalidad--</option>
                                                    <?php foreach ($modalidad as $modalidades): ?>
                                                        <option value="<?= esc($modalidades['id']); ?>" <?= old('modalidad') == $modalidades['id'] ? 'selected' : ''; ?>>
                                                            <?= esc($modalidades['nombre']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
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
                                        
                                    </div>

                                    <div class="row">
                                    <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
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
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
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
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="regional_educacion" class="control-label">Regional:</label>
                                                <input class="form-control <?= session('errors.regional_educacion') ? 'is-invalid' : '' ?>" type="text" id="regional_educacion" name="regional_educacion"
                                                    value="<?= old('regional_educacion'); ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="distrito_educativo" class="control-label">Distrito:</label>
                                                <input class="form-control <?= session('errors.distrito_educativo') ? 'is-invalid' : '' ?>" type="text" id="distrito_educativo" name="distrito_educativo"
                                                    value="<?= old('distrito_educativo'); ?>" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="Email" class="control-label">Email</label>
                                                <input class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" type="email" id="email" name="email"
                                                    value="<?= old('email'); ?>" />
                                                <small>*</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="telefono" class="control-label">Telefono:</label>
                                                <input class="form-control <?= session('errors.telefono') ? 'is-invalid' : '' ?>" type="tel" id="telefono" name="telefono"
                                                    value="<?= old('telefono'); ?>" pattern="\d{3}-\d{3}-\d{4}" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="redes" class="control-label">Redes:</label>
                                                <input class="form-control <?= session('errors.redes') ? 'is-invalid' : '' ?>" type="text" id="redes" name="redes"
                                                    value="<?= old('redes'); ?>" required />
                                                <small>*</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="direccion" class="control-label">Direccion:</label>
                                                <input class="form-control <?= session('errors.direccion') ? 'is-invalid' : '' ?>" type="text" id="direccion" name="direccion"
                                                    value="<?= old('direccion'); ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="web" class="control-label">web:</label>
                                                <input class="form-control <?= session('errors.web') ? 'is-invalid' : '' ?>" type="url" id="web" name="web"
                                                    value="<?= old('web'); ?>" required />
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="logo" class="control-label"></label>
                                                <input type="text" readonly="" class="form-control" placeholder="Logo..." />
                                                <input type="file" name="logo" id="logo" class="form-control <?= session('errors.logo') ? 'is-invalid' : '' ?>">
                                                <?php if (session('errors.logo')): ?>
                                                    <div class="invalid-feedback">
                                                        <?= session('errors.logo'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-center">

                                        <button type="submit" class="btn btn-info btn-raised btn-sm">
                                            <i class="fa-solid fa-floppy-disk"></i> Guardar
                                        </button>

                                        <a
                                            href="<?php echo base_url(); ?>/escuela"
                                            class="btn btn-danger btn-raised btn-sm">
                                            <i class="fa-solid fa-ban"></i> Cancelar
                                        </a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>