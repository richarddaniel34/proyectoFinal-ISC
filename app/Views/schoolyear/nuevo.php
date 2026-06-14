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
                                <form id="formPeriodo" method="POST" action="<?= base_url(); ?>schoolyear/insertar" autocomplete="off" class="formulario-personalizado">
                                    <div class="row">
                                        <!-- NOMBRE -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="nombre" class="control-label">Nombre:</label>
                                                <input class="form-control" type="text" id="nombre" name="nombre" autofocus
                                                    oninput="generarCodigoPeriodo(); this.value = this.value.toUpperCase();" required />
                                                <small>*</small>
                                            </div>
                                        </div>

                                        <!-- FECHA INICIO -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="fecha_inicio" class="control-label">Fecha de Inicio:</label>
                                                <input class="form-control" type="date" id="fecha_inicio" name="fecha_inicio" required />
                                            </div>
                                        </div>

                                        <!-- FECHA TERMINO -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="fecha_termino" class="control-label">Fecha de Término:</label>
                                                <input class="form-control" type="date" id="fecha_termino" name="fecha_termino" />
                                            </div>
                                        </div>

                                        <!-- CÓDIGO -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="codigo" class="control-label">Código:</label>
                                                <input class="form-control" type="text" id="codigo" name="codigo" readonly required />
                                                <small>*</small>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-center mt-3">
                                        <button type="submit" class="btn btn-success text-light">
                                            <i class="fa-solid fa-floppy-disk"></i> <b>GUARDAR</b>
                                        </button>

                                        <a href="<?= base_url(); ?>schoolyear" class="btn btn-danger text-light">
                                            <i class="fa-solid fa-ban"></i> <b>CANCELAR</b>
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
    function generarCodigoPeriodo() {
        let nombre = document.getElementById("nombre").value;
        let codigoInput = document.getElementById("codigo");

        // Expresión regular para encontrar los años en el formato "2024-2025"
        let match = nombre.match(/\d{4}-\d{4}/);

        if (match) {
            let years = match[0]; // Captura los años
            let codigo = `ESC-JAR_${years}`;
            codigoInput.value = codigo;
        } else {
            codigoInput.value = ""; // Si no encuentra un año válido, deja el campo vacío
        }
    }

    // Validación JS al enviar
    document.getElementById("formPeriodo").addEventListener("submit", function(e) {
        const nombre = document.getElementById("nombre").value.trim();
        const fechaInicio = document.getElementById("fecha_inicio").value.trim();
        const codigo = document.getElementById("codigo").value.trim();

        if (nombre === "" || fechaInicio === "" || codigo === "") {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Campos requeridos incompletos',
                text: 'Por favor, completa todos los campos obligatorios marcados con *',
                confirmButtonColor: '#d33'
            });
        }
    });
</script>
<?= $this->endSection() ?>