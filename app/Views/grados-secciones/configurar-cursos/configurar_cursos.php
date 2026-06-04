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
<!-- Formulario para agregar o editar curso -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div class="tab-pane fade show active">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-md-10 offset-md-1">

                                <p class="lead">Los campos marcados con (<span class="text-danger">*</span>) son obligatorios.</p>

                                <form method="POST" action="<?= base_url('gradossecciones/guardar_configuracion_cursos') ?>"
                                    class="formulario-personalizado" autocomplete="off">
                                    <?= csrf_field() ?>

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
                                                            value="<?= esc($servicio['id_servicio']) ?>"
                                                            data-salida="<?= esc($servicio['nombre_salida'] ?? '') ?>">
                                                            <?= esc($servicio['nombre_servicio']) ?>
                                                            <?= $servicio['nombre_salida'] ? ' - ' . esc($servicio['nombre_salida']) : '' ?>
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
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa-solid fa-floppy-disk"></i> Guardar
                                        </button>
                                        <button type="button" class="btn btn-danger" id="btn-cancelar">
                                            <i class="fa-solid fa-ban"></i> Cancelar
                                        </button>
                                    </div>
                                </form>


                                <hr class="my-5">

                                <!-- Tabla de cursos configurados -->
                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered table-striped text-center align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Curso Base</th>
                                                <th>Año Escolar</th>
                                                <th>Capacidad</th>
                                                <th>Tipo de Aula</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($cursos)): ?>
                                                <?php foreach ($cursos as $index => $curso): ?>
                                                    <tr data-id="<?= $curso['id'] ?>">
                                                        <td><?= $index + 1 ?></td>
                                                        <td><?= esc($curso['nombre_curso']) ?></td>
                                                        <td><?= esc($curso['schoolyear']) ?></td>
                                                        <td class="capacidad-cell">
                                                            <span class="capacidad-texto texto"><?= esc($curso['capacidad']) ?></span>
                                                            <input type="number" class="edit-input d-none" value="<?= esc($curso['capacidad']) ?>" min="1" max="60">
                                                        </td>
                                                        <td class="tipo-aula-cell">
                                                            <span class="tipo-aula-texto texto"><?= esc($curso['tipo_aula']) ?></span>
                                                            <select class="edit-select d-none">
                                                                <option value="Inicial" <?= $curso['tipo_aula'] == 'Inicial' ? 'selected' : '' ?>>Inicial</option>
                                                                <option value="Normal" <?= $curso['tipo_aula'] == 'Normal' ? 'selected' : '' ?>>Normal</option>
                                                                <option value="Improvisada" <?= $curso['tipo_aula'] == 'Improvisada' ? 'selected' : '' ?>>Improvisada</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <span class="badge <?= $curso['activo'] ? 'bg-success' : 'bg-secondary' ?>">
                                                                <?= $curso['activo'] ? 'Activo' : 'Inactivo' ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary btn-editar">
                                                                <i class="fa-solid fa-pen-to-square"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-success btn-guardar d-none">
                                                                <i class="fa-solid fa-floppy-disk"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-secondary btn-cancelar d-none">
                                                                <i class="fa-solid fa-xmark"></i>
                                                            </button>
                                                            <span class="mensaje-guardado text-success d-none">Guardado ✔</span>

                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7">No hay cursos configurados todavía.</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                </div>

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

        fetch(`<?= base_url('grados-y-secciones/obtener_cursos_por_servicio') ?>/${servicioId}`)
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