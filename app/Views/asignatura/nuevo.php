

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
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/asignatura/insertar" autocomlete="off" class="formP">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="nombre" class="control-label">Nombre asignatura:</label>
                                                <input class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" type="text" id="nombre" name="nombre" value="<?= old('nombre', '') ?>" autofocus oninput="generarCodigo()" required />
                                                <small>*</small>
                                                <?php if (session('errors.nombre')) : ?>
                                                    <div class="invalid-feedback d-block">
                                                        <?= esc(session('errors.nombre')) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="codigo-asignatura" class="control-label">Codigo Asignatura:</label>
                                                <input class="form-control <?= session('errors.codigo_asignatura') ? 'is-invalid' : '' ?>" type="text" id="codigo_asignatura" name="codigo_asignatura" value="<?= old('codigo_asignatura', ' ') ?>" required/>
                                                <?php if (session('errors.codigo_asignatura')) : ?>
                                                    <div class="invalid-feedback d-block">
                                                        <?= esc(session('errors.codigo_asignatura')) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="tipo_asignatura" class="control-label">tipo_asignatura:</label>
                                                <select class="form-control <?= session('errors.tipo_asignatura') ? 'is-invalid' : '' ?>" id="tipo_asignatura" name="tipo_asignatura" required>
                                                    <option value="">Selecciona tipo de asignatura</option>    
                                                    <?php foreach ($tipo_asignaturas as $tipo_asignatura): ?>
                                                    <option value="<?= esc($tipo_asignatura['id']); ?>" <?= old('tipo_asignatura') == $tipo_asignatura['id'] ? 'selected' : '' ?>><?= esc($tipo_asignatura['nombre']); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <?php if (session('errors.tipo_asignatura')) : ?>
                                                    <div class="invalid-feedback d-block">
                                                        <?= esc(session('errors.tipo_asignatura')) ?>
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
                                        href="<?php echo base_url(); ?>/asignatura"
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

 



