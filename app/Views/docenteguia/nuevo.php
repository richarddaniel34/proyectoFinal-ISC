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
        <h1 class="text-titles"><i class="fa-solid fa-book"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
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

                                <!-- Formulario principal -->
                                <form method="POST" action="<?= base_url('docenteguia/insertar'); ?>" class="formulario-personalizado">

                                    <div class="row">
                                        <!-- Curso -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="id_curso">Curso:</label>
                                                <select class="form-control" id="id_curso" name="id_curso">
                                                    <option value="">-- Seleccione un curso --</option>
                                                    <?php if (!empty($cursos)): ?>
                                                        <?php foreach ($cursos as $curso): ?>
                                                            <option value="<?= $curso['id'] ?>">
                                                                <?= $curso['nombre_curso'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No hay cursos disponibles</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Docente -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="id_docente">Docente:</label>
                                                <select class="form-control" id="id_docente" name="id_docente">
                                                    <option value="">-- Seleccione un docente --</option>
                                                    <?php foreach ($docentes as $docente): ?>
                                                        <option value="<?= $docente['id'] ?>">
                                                            <?= $docente['nombre_completo'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Año Escolar -->
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="schoolyear">Año Escolar:</label>
                                                <input type="text" class="form-control" id="schoolyear" name="schoolyear"
                                                    value="<?= isset($anioEscolar['nombre']) ? $anioEscolar['nombre'] : '' ?>" readonly>
                                                <input type="hidden" id="id_schoolyear"
                                                    value="<?= isset($anioEscolar['id']) ? $anioEscolar['id'] : '' ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botón para agregar a la tabla -->
                                    <div class="text-center mb-3">
                                        <button type="button" class="btn btn-success" id="btnAgregar">
                                            <i class="fas fa-plus"></i> Agregar a la tabla
                                        </button>
                                    </div>

                                    <!-- Tabla de registros a guardar -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tablaDocentes">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Curso</th>
                                                    <th>Docente</th>
                                                    <th>Año Escolar</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>

                                    <!-- Botones finales -->
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Guardar todo
                                        </button>
                                        <a href="<?= base_url('docenteguia'); ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Volver
                                        </a>
                                    </div>
                                    <input type="hidden" name="asignaciones" id="asignaciones-json">


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
    document.addEventListener('DOMContentLoaded', function() {
        console.log("✅ DOM completamente cargado");

        const btnAgregar = document.getElementById('btnAgregar');
        const tablaBody = document.querySelector('#tablaDocentes tbody');
        const inputAsignaciones = document.getElementById('asignaciones-json');
        const inputSchoolyear = document.getElementById('schoolyear');
        const idSchoolyear = document.getElementById('id_schoolyear').value;

        if (!btnAgregar || !tablaBody || !inputAsignaciones || !inputSchoolyear || !idSchoolyear) {
            console.error("❌ Elementos necesarios no encontrados");
            return;
        }

        let asignaciones = [];

        btnAgregar.addEventListener('click', function() {
            console.log("🟢 Botón 'Agregar a la tabla' clickeado");

            const selectCurso = document.getElementById('id_curso');
            const selectDocente = document.getElementById('id_docente');

            const idCurso = selectCurso.value;
            const nombreCurso = selectCurso.options[selectCurso.selectedIndex]?.text || '';
            const idDocente = selectDocente.value;
            const nombreDocente = selectDocente.options[selectDocente.selectedIndex]?.text || '';
            const schoolyear = idSchoolyear;

            console.log("📌 Valores seleccionados:", {
                idCurso,
                nombreCurso,
                idDocente,
                nombreDocente,
                schoolyear
            });

            if (!idCurso || !idDocente || !schoolyear) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Debe seleccionar curso, docente y año escolar antes de agregar.',
                    confirmButtonColor: '#3085d6'
                });
                return;
            }

            // Evitar duplicados
            if (asignaciones.some(a => a.id_curso === idCurso || a.id_personal === idDocente)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Duplicado detectado',
                    text: 'Ese curso o docente ya fue agregado.',
                    confirmButtonColor: '#d33'
                });
                console.warn("⚠️ Duplicado detectado en array local", {
                    idCurso,
                    idDocente
                });
                return;
            }

            // Crear objeto de asignación
            const nuevaAsignacion = {
                id_curso: idCurso,
                id_personal: idDocente,
                nombre_curso: nombreCurso,
                nombre_personal: nombreDocente,
                schoolyear: schoolyear
            };

            asignaciones.push(nuevaAsignacion);
            console.log("✅ Asignaciones actuales:", asignaciones);

            // Actualizar input oculto
            inputAsignaciones.value = JSON.stringify(asignaciones);
            console.log("🔹 Input oculto actualizado:", inputAsignaciones.value);

            // Agregar fila a la tabla
            const nuevaFila = document.createElement('tr');
            nuevaFila.innerHTML = `
            <td>${nombreCurso}</td>
            <td>${nombreDocente}</td>
            <td>${schoolyear}</td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btnEliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
            tablaBody.appendChild(nuevaFila);

            // Limpiar selects
            selectCurso.value = "";
            selectDocente.value = "";
        });

        // Confirmación de eliminación
        tablaBody.addEventListener('click', function(e) {
            if (e.target.closest('.btnEliminar')) {
                const fila = e.target.closest('tr');
                Swal.fire({
                    title: '¿Eliminar registro?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const index = Array.from(tablaBody.children).indexOf(fila);
                        if (index > -1) {
                            asignaciones.splice(index, 1);
                            inputAsignaciones.value = JSON.stringify(asignaciones);
                            console.log("🗑️ Asignación eliminada, array actualizado:", asignaciones);
                            console.log("🔹 Input oculto actualizado:", inputAsignaciones.value);
                        }
                        fila.remove();
                    }
                });
            }
        });
    });



    /* SELECT 2 */
    $(document).ready(function() {
        const $select = $('#id_docente');

        $select.select2({
            placeholder: "-- Seleccione un docente --",
            allowClear: true,
            width: '100%'
        });

        const $container = $select.next('.select2-container');

        // Aplica estilos al contenedor principal
        $container.addClass('form-control');
        $container.css({
            'background-color': 'white',
            'border': 'none',
            'border-radius': '0',
            'box-shadow': 'none',
            'height': '36px'
        });

        // Aplica estilos a la selección (el "input" que ves)
        $container.find('.select2-selection').css({
            'background-color': 'white',
            'border': 'none',
            'border-bottom': '1px solid #d2d2d2',
            'border-radius': '0',
            'box-shadow': 'none',
            'height': '36px',
            'line-height': '36px',
            'padding': '0 10px'
        });

        // Opcional: efecto al focus
        $container.find('.select2-selection').on('focus', function() {
            $(this).css('border-bottom', '2px solid #0b1065');
        });
    });
</script>





<?= $this->endSection() ?>