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
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/grados/insertarCurso" autocomlete="off" class="formP">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="secciones" class="control-label">Grados:</label>
                                                <select class="form-control" id="id_grado" name="id_grado" required>
                                                    <option value=" ">--Seleccione la Sección--</option>
                                                    <?php foreach ($grados as $grado): ?>
                                                        <option value="<?= esc($grado['id']); ?>"><?= esc($grado['nombre']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="secciones" class="control-label">Sección:</label>
                                                <select class="form-control <?= session('errors.id_secciones') ? 'is-invalid' : '' ?>" id="id_secciones" name="id_secciones" required>
                                                    <option value=" ">--Seleccione la Sección--</option>
                                                    <?php foreach ($secciones as $seccion): ?>
                                                        <option value="<?= esc($seccion['id']); ?>" <?= old('id_secciones') == $seccion['id'] ? 'selected' : '' ?>><?= esc($seccion['nombre']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php if (session('errors.id_secciones')) : ?>
                                                    <div class="invalid-feedback d-block">
                                                        <?= esc(session('errors.id_secciones')) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="nombreCurso" class="control-label">Nombre curso:</label>
                                                <input class="form-control <?= session('errors.nombreCurso') ? 'is-invalid' : '' ?>" type="text" id="nombreCurso" name="nombreCurso" value="<?= old('nombreCurso', ' ') ?>" readonly required />
                                                <small>*</small>
                                                <?php if (session('errors.nombreCurso')) : ?>
                                                    <div class="invalid-feedback d-block">
                                                        <?= esc(session('errors.nombreCurso')) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="codigoCurso" class="control-label">Codigo curso:</label>
                                                <input class="form-control <?= session('errors.codigoCurso') ? 'is-invalid' : '' ?>" type="text" id="codigoCurso" name="codigoCurso" value="<?= old('codigoCurso', ' ') ?>" readonly required />
                                                <small>*</small>
                                                <?php if (session('errors.codigoCurso')) : ?>
                                                    <div class="invalid-feedback d-block">
                                                        <?= esc(session('errors.codigoCurso')) ?>
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
                                            href="<?php echo base_url(); ?>grados"
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