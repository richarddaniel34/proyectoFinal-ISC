<script>
    var baseUrl = "<?= base_url(); ?>";
</script>



<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-graduation-cap"></i>
            Configuración / <small><?= esc($titulo) ?></small>
        </h1>
    </div>
</div>
<br>
<!-- Formulario para agregar o editar curso -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-md-10 offset-md-1">

                                <form method="POST" action="<?= base_url('estructura-academica/configurarCursos/guardar') ?>"
                                    class="formulario-personalizado" autocomplete="off">
                                    <?= csrf_field() ?>

                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <span class="nav-link active">DATOS DEL CURSO</span>
                                        </li>
                                    </ul>

                                    <div class="tab-content mt-3">



                                        <p class="lead">Los campos marcados con (<span class="text-danger">*</span>) son obligatorios</p>



                                        <!-- Año escolar -->
                                        <div class="row">
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group">
                                                    <label for="schoolyear">
                                                        Año Escolar: <small class="obligatorio-formulario">*</small>
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        id="schoolyear"
                                                        name="schoolyear"
                                                        value="<?= esc($schoolYear['nombre'] ?? 'No definido') ?>"
                                                        readonly />
                                                    <input type="" hidden
                                                        id="id_schoolyear"
                                                        name="id_schoolyear"
                                                        value="<?= esc($schoolYear['id'] ?? '') ?>" />
                                                </div>
                                            </div>

                                            <!-- Servicio -->
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group">
                                                    <label for="id_servicio">
                                                        Servicio: <small class="obligatorio-formulario">*</small>
                                                    </label>
                                                    <select class="form-control" id="id_servicio" name="id_servicio" required>
                                                        <option value="">--Seleccione el Servicio--</option>

                                                        <?php foreach ($servicios as $servicio): ?>
                                                            <option
                                                                value="<?= esc($servicio['id_servicio']) ?><?= !empty($servicio['id_salida_tecnica']) ? '-' . esc($servicio['id_salida_tecnica']) : '' ?>"
                                                                data-salida="<?= esc($servicio['nombre_salida'] ?? '') ?>">
                                                                <?= esc($servicio['nombre_servicio']) ?>
                                                                <?= !empty($servicio['nombre_salida']) ? ' - ' . esc($servicio['nombre_salida']) : '' ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Curso base -->
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group">
                                                    <label for="id_cursos_base">
                                                        Curso: <small class="obligatorio-formulario">*</small>
                                                    </label>
                                                    <select class="form-control" id="id_cursos_base" name="id_cursos_base" required>
                                                        <option value="">--Seleccione primero un Servicio--</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Capacidad, tipo aula, estado, etc. -->
                                        <div class="row mt-3">
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group">
                                                    <label for="capacidad">Capacidad: <small class="obligatorio-formulario">*</small></label>
                                                    <input type="number" class="form-control" id="capacidad" name="capacidad"
                                                        min="1" max="60" placeholder="Ej: 35" required>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="form-group">
                                                    <label for="tipo_aula">Tipo de Aula: <small class="obligatorio-formulario">*</small></label>
                                                    <select class="form-control" id="tipo_aula" name="tipo_aula" required>
                                                        <option value="">--Seleccione el Tipo de Aula--</option>
                                                        <option value="Inicial">Inicial</option>
                                                        <option value="Normal">Normal</option>
                                                        <option value="Improvisada">Improvisada</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-5">
                                            <button type="submit" class="btn btn-success btn-sistema">
                                                <i class="fa-solid fa-floppy-disk"></i> <b>GUARDAR</b>
                                            </button>
                                            <a
                                                href="#"
                                                onclick="mostrarModalCancelar('<?= base_url('estructura-academica/configurarCursos') ?>')"
                                                class="btn btn-danger text-light btn-sistema">
                                                <i class="fa-solid fa-ban"></i> <b>CANCELAR</b>
                                            </a>
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




<?= $this->section('scripts') ?>
<script>
    document.getElementById('id_servicio').addEventListener('change', function() {
        const select = this;
        const servicioId = select.value; // integer
        const nombreSalida = select.options[select.selectedIndex].dataset.salida || '';
        const selectCursos = document.getElementById('id_cursos_base');

        selectCursos.innerHTML = '<option value="">Cargando cursos...</option>';

        if (!servicioId) {
            selectCursos.innerHTML = '<option value="">--Seleccione primero un Servicio--</option>';
            return;
        }

        fetch(`<?= base_url('estructura-academica/obtener_cursos_por_servicio') ?>/${servicioId}`)
            .then(res => res.json())
            .then(cursos => {
                selectCursos.innerHTML = '<option value="">--Seleccione el Curso--</option>';
                cursos.forEach(curso => {
                    const option = document.createElement('option');
                    option.value = curso.id;
                    option.textContent = curso.nombre_curso;
                    selectCursos.appendChild(option);
                });
            })
            .catch(() => {
                selectCursos.innerHTML = '<option value="">Error al cargar cursos</option>';
            });
    });


    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', function() {
            const fila = this.closest('tr');

            // Mostrar inputs y select
            fila.querySelector('.edit-input').classList.remove('d-none');
            fila.querySelector('.edit-select').classList.remove('d-none');

            // Ocultar texto normal
            fila.querySelectorAll('.texto').forEach(el => el.classList.add('d-none'));

            // Botones
            this.classList.add('d-none'); // ocultar editar
            fila.querySelector('.btn-guardar').classList.remove('d-none');
            fila.querySelector('.btn-cancelar').classList.remove('d-none');
        });
    });

    document.querySelectorAll('.btn-cancelar').forEach(btn => {
        btn.addEventListener('click', function() {
            const fila = this.closest('tr');

            // Ocultar inputs y select
            fila.querySelector('.edit-input').classList.add('d-none');
            fila.querySelector('.edit-select').classList.add('d-none');

            // Mostrar texto normal
            fila.querySelectorAll('.texto').forEach(el => el.classList.remove('d-none'));

            // Botones
            fila.querySelector('.btn-editar').classList.remove('d-none');
            fila.querySelector('.btn-guardar').classList.add('d-none');
            this.classList.add('d-none'); // ocultar cancelar
        });
    });

    document.querySelectorAll('.btn-guardar').forEach(btn => {
        btn.addEventListener('click', function() {
            const fila = this.closest('tr');
            const id = fila.dataset.id;

            const data = {
                capacidad: fila.querySelector('.edit-input').value,
                tipo_aula: fila.querySelector('.edit-select').value
            };

            fetch(`<?= base_url('gradossecciones/actualizar_curso') ?>/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        // Actualizar contenido de la fila
                        fila.querySelector('.capacidad-texto').textContent = data.capacidad;
                        fila.querySelector('.tipo-aula-texto').textContent = data.tipo_aula;

                        // Ocultar inputs y select
                        fila.querySelector('.edit-input').classList.add('d-none');
                        fila.querySelector('.edit-select').classList.add('d-none');

                        // Mostrar texto normal
                        fila.querySelectorAll('.texto').forEach(el => el.classList.remove('d-none'));

                        // Botones
                        fila.querySelector('.btn-editar').classList.remove('d-none');
                        fila.querySelector('.btn-guardar').classList.add('d-none');
                        fila.querySelector('.btn-cancelar').classList.add('d-none');

                        // Mostrar mensaje temporal
                        const msg = fila.querySelector('.mensaje-guardado');
                        if (msg) {
                            msg.classList.remove('d-none');
                            setTimeout(() => msg.classList.add('d-none'), 2000);
                        }
                    } else {
                        alert('Error al guardar cambios');
                    }
                })
                .catch(() => alert('Error de conexión'));
        });
    });


    document.getElementById('btn-cancelar').addEventListener('click', function() {
        Swal.fire({
            title: '¿Está seguro?',
            text: "Los cambios no se guardarán.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No, continuar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir si confirma
                window.location.href = "<?= base_url('grados-y-secciones') ?>";
            }
        });
    });
</script>

<?= $this->endSection() ?>