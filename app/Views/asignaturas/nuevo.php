<script>
    var baseUrl = "<?= base_url(); ?>";
</script>


<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-book"></i><?php print_r($titulo_1) ?>/ <small><?php print_r($titulo) ?></small></h1>
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
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/asignaturas/insertar" autocomlete="off" class="formulario-personalizado">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <span class="nav-link active">Datos de Asignatura</span>
                                        </li>
                                    </ul>

                                    <div class="tab-content mt-3">

                                        <div class="tab-pane fade show active" id="datos-basicos">

                                            <p class="lead">Los campos marcados con (<span class="text-danger">*</span>) son obligatorios</p>


                                            <div class="row">
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="nombre" class="control-label">Nombre asignatura: <small class="obligatorio-formulario">*</small></label>
                                                        <input class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" type="text" id="nombre" name="nombre" value="<?= old('nombre', '') ?>" autofocus oninput="generarCodigo()" required />
                                                        <?php if (session('errors.nombre')) : ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= esc(session('errors.nombre')) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="codigo-asignatura" class="control-label">Codigo Asignatura: <small class="obligatorio-formulario">*</small> </label>
                                                        <input class="form-control <?= session('errors.codigo_asignatura') ? 'is-invalid' : '' ?>" type="text" id="codigo_asignatura" name="codigo_asignatura" value="<?= old('codigo_asignatura', ' ') ?>" required />
                                                        <small class="indicacion-formulario">
                                                            El sistema genera un código de referencia que puede ser editado según sus necesidades.
                                                        </small>
                                                        <?php if (session('errors.codigo_asignatura')) : ?>
                                                            <div class="invalid-feedback d-block">
                                                                <?= esc(session('errors.codigo_asignatura')) ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-4">
                                                    <div class="form-group label-floating">
                                                        <label for="tipo_asignatura" class="control-label">tipo asignatura: <small class="obligatorio-formulario">*</small></label>
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
                                        </div>
                                    </div>
                                    <br>

                                    <p class="text-center">

                                        <button type="submit" class=" btn btn-success text-light btn-sistema">
                                            <i class="fa-solid fa-floppy-disk"></i> <b>GUARDAR</b>
                                        </button>

                                        <a href="#"
                                            onclick="mostrarModalCancelar('<?= base_url('asignaturas') ?>')"
                                            class="btn btn-danger text-light btn-sistema">
                                            <i class="fa-solid fa-trash"></i> <b>CANCELAR</b>
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

<?= $this->section('scripts') ?>

<script>
   


    function generarCodigo() {
        const nombre = document.getElementById('nombre').value.trim();

        if (!nombre) {
            document.getElementById('codigo_asignatura').value = '';
            return;
        }

        const palabrasIrrelevantes = [
            'y', 'de', 'del', 'la', 'el', 'los', 'las', 'en'
        ];

        const palabras = nombre
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .split(/\s+/)
            .filter(p => !palabrasIrrelevantes.includes(p.toLowerCase()));

        let codigo = '';

        if (palabras.length === 1) {
            codigo = palabras[0].substring(0, 4);
        } else {
            codigo = palabras
                .map(p => p.substring(0, 3))
                .join('-');
        }

        document.getElementById('codigo_asignatura').value =
            codigo.toUpperCase();
    }
</script>

<?= $this->endSection() ?>