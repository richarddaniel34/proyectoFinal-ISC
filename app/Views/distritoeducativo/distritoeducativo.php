<?php if (session()->get('mensaje')): ?>
    <div class="alert alert-success">
        <?= session()->get('mensaje'); ?>
    </div>
<?php endif; ?>

<?php $distrito = $distrito ?? []; ?>

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
                            <div class="col-xs-12 col-md-10 offset-md-2">
                                <form method="POST" action="<?= base_url(); ?>/distritoeducativo/guardar" class="formulario-personalizado">
                                    <?= csrf_field() ?>
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="regional" class="control-label">Regional:</label>
                                                <input type="text" class="form-control" name="regional" value="<?= $distrito['regional'] ?? ''; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="distrito" class="control-label">Distrito:</label>
                                                <input type="text" class="form-control" name="distrito" value="<?= $distrito['distrito'] ?? ''; ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="director" class="form-label">Director Distrital</label>
                                                <input type="text" class="form-control" name="director_distrito" id="director_distrito" value="<?= $distrito['director_distrito'] ?? ''; ?>"require>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="tecnico" class="form-label">Técnico de Acreditación y Titulación</label>
                                                <input type="text" class="form-control" name="tecnico_acreditacion" id="tecnico_acreditacion" value="<?= $distrito['tecnico_acreditacion'] ?? ''; ?>"require>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-sm-4">
                                            <div class="form-group">
                                                <label for="telefono" class="form-label">Telefono:</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $distrito['telefono'] ?? ''; ?>">
                                                <div class="invalid-feedback">Formato: 000-000-0000</div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-center mt-5">
                                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>

                                        <a href="<?php echo base_url(); ?>escuela" class=" btn btn-primary text-light " id="plus-user"><i class="fa-solid fa-rotate-left"></i>Volver</a>
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