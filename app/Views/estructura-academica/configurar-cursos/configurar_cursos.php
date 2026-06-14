<script>
    var baseUrl = "<?= base_url(); ?>";
</script>


<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-a"></i><i class="fa-solid fa-b"></i><i class="fa-solid fa-c"></i>
            <?= esc($titulo_1) ?> / <small><?= esc($titulo_2) ?> / <?= esc($titulo_3) ?> - <?= esc($schoolYearActual) ?></small>
        </h1>
    </div>
</div>

<main>
    <div class="container-fluid px-4">
        <h4 class="mt-4"></h4>
        <div>
            <p>
                <a href="<?php echo base_url(); ?>estructura-academica/configurarCursos/nuevo"
                    class=" btn btn-primary text-light btn-sistema" id="plus-user">
                    <i class="fa-solid fa-file-circle-plus"></i> <b>CONFIGURAR UN NUEVO CURSO</b> 
                </a>
            </p>
        </div>
        <div class="">

            <div class="tab-pane " id="list">
                <div class="table-responsive">
                    <table class="table table-hover table-striped text-center tabla-basica" id="tablaCursos">
                        <thead class="title-table">
                            <tr>
                                <th class="text-center">CURSO</th>
                                <th class="text-center">CAPACIDAD</th>
                                <th class="text-center">TIPO DE AULA</th>
                                <th class="text-center"> ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cursos)): ?>
                                <?php foreach ($cursos as $index => $curso): ?>
                                    <tr data-id="<?= $curso['id'] ?>">
                                        <td><?= esc($curso['nombre_curso']) ?></td>
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
            <div>
                <h3>Leyenda:</h3>
                <p>
                    <i class="fa-solid fa-edit"></i> <strong>Editar</strong> – Modifica los datos del registro |
                    <i class="fa-solid fa-trash"></i> <strong>Inactivar</strong> – Inactiva el registro
                </p>
            </div>
        </div>
</main>



<?= $this->section('scripts') ?>
<script>

    /**
     * botones de la tabla para edicion en linea, 
     */
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

            fetch(`<?= base_url('estructura-academica/configurarCursos/actualizar_curso') ?>/${id}`, {
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