<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Registro no permitido',
            text: <?= json_encode(session()->getFlashdata('error')) ?>,
            confirmButtonColor: '#d33'
        });
    </script>
<?php endif; ?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-school"></i> Configuración /
            <small><?= esc($titulo) ?></small>
        </h1>
    </div>
</div>
<br>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div class="tab-pane fade show active">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-md-10 offset-md-1">
                                <p class="lead">Los campos marcados con (<span class="text-danger">*</span>) son obligatorios</p>
                                <form method="POST" action="<?= base_url('gradossecciones/guardar_cursos') ?>" class="formulario-personalizado" autocomplete="off">
                                    <?= csrf_field() ?>
                                    <div class="row">
                                        <!-- Grado -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <label for="id_grado">
                                                    Grado: <small class="obligatorio-formulario">*</small>
                                                </label>
                                                <select class="form-control" id="id_grado" name="id_grado" required>
                                                    <option value="">--Seleccione el Grado--</option>
                                                    <?php if (!empty($grados)): ?>
                                                        <?php foreach ($grados as $grado): ?>
                                                            <option value="<?= esc($grado['id']) ?>"><?= esc($grado['nombre_grado']) ?></option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option value="">No hay grados disponibles</option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Sección -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <label for="id_seccion">Sección: <small class="obligatorio-formulario">*</small></label>
                                                <select class="form-control" id="id_seccion" name="id_seccion" required>
                                                    <option value="">--Seleccione la Sección--</option>
                                                    <?php foreach ($secciones as $seccion): ?>
                                                        <option value="<?= $seccion['id'] ?>"><?= esc($seccion['letra']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Servicio -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <label for="id_servicio">Servicio: <small class="obligatorio-formulario">*</small></label>
                                                <select class="form-control" id="id_servicio" name="id_servicio" required>
                                                    <option value="">--Seleccione el Servicio--</option>
                                                    <?php foreach ($servicios as $servicio): ?>
                                                        <option value="<?= $servicio['id'] ?>">
                                                            <?= esc($servicio['servicio_nombre']) ?>
                                                            <?= !empty($servicio['salida_tecnica']) ? ' - ' . esc($servicio['salida_tecnica']) : '' ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Nivel educativo (solo lectura) -->
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group">
                                                <label for="nivel">Nivel educativo:</label>
                                                <input class="form-control" type="text" id="nivel" value="<?= esc($nivel) ?>" readonly />
                                            </div>
                                        </div>

                                        <!-- Escuela (oculto) -->
                                        <input type="hidden" name="id_escuela" value="<?= session('id_escuela') ?>">
                                    </div>

                                    <div class="row mt-3">
                                        <!-- Nombre del Curso -->
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="nombre_curso">Nombre del Curso: </label>
                                                <input class="form-control" type="text" id="nombre_curso" name="nombre_curso" readonly required>
                                                <small class="indicacion-formulario">Se genera automáticamente: Grado + Sección + Servicio</small>
                                            </div>
                                        </div>

                                        <!-- Código del Curso -->
                                        <div class="col-12 col-sm-6">
                                            <div class="form-group">
                                                <label for="codigo_curso">Código del Curso:</label>
                                                <input class="form-control" type="text" id="codigo_curso" name="codigo_curso" readonly required>
                                                <small class="indicacion-formulario">Se genera automáticamente: GRADO + SECCIÓN + SERVICIO</small>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->section('scripts') ?>

    <script>
        const nivelNombre = "<?= esc($nivel) ?>"; // Ej: "Secundaria"
    </script>

    <script>
        document.getElementById('btn-cancelar').addEventListener('click', function() {
            Swal.fire({
                title: '¿Está seguro que desea cancelar?',
                text: "Los cambios que haya hecho no se guardarán.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, cancelar',
                cancelButtonText: 'No, continuar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirige a la página de cursos base
                    window.location.href = "<?= base_url('grados-y-secciones/cursos') ?>";
                }
            });
        });




        function actualizarNombreYCodigo() {
            const grado = document.getElementById('id_grado');
            const seccion = document.getElementById('id_seccion');
            const servicio = document.getElementById('id_servicio');
            const nombreCurso = document.getElementById('nombre_curso');
            const codigoCurso = document.getElementById('codigo_curso');

            const gradoTexto = grado.options[grado.selectedIndex]?.text?.trim();
            const seccionTexto = seccion.options[seccion.selectedIndex]?.text?.trim();
            const servicioTexto = servicio?.options[servicio.selectedIndex]?.text?.trim();

            // Si falta algo esencial, limpiar
            if (!gradoTexto || !seccionTexto || gradoTexto === "--Seleccione el Grado--" || seccionTexto === "--Seleccione la Sección--") {
                nombreCurso.value = "";
                codigoCurso.value = "";
                return;
            }

            //  Nivel seguro (usa variable global o un valor por defecto)
            const nivelNombreLocal = typeof nivelNombre !== "undefined" ? nivelNombre : "Secundaria";

            // Abreviaturas de grados
            const nombres = {
                "Pre-Primario": "PP",
                "Kinder": "K",
                "Maternal": "MAT",
                "Primero": "1ro",
                "Segundo": "2do",
                "Tercero": "3ro",
                "Cuarto": "4to",
                "Quinto": "5to",
                "Sexto": "6to"
            };

            // Abreviaturas de salidas técnicas
            const abreviaturasSalidas = {
                "Desarrollo y Administración de Aplicaciones Informáticas": "INF",
                "Comercio y Mercadeo": "COM",
                "Electromecánica": "ELE",
                "Servicios de Enfermería": "ENF",
                "Contabilidad": "CON",
                "Hotelería y Turismo": "HOT",
                "Diseño Gráfico": "DG",
                "Electricidad": "ELC"
            };

            const gradosSinNivel = ["Pre-Primario", "Kinder", "Maternal"];
            const abreviado = nombres[gradoTexto] ?? gradoTexto;
            const nivelAbrev = nivelNombreLocal.split(' ')[0].slice(0, 3); // Ejemplo: Secundaria → Sec

            let nombreBase = "";
            let codigoBase = "";

            // Nombre base según nivel
            if (gradosSinNivel.includes(gradoTexto)) {
                nombreBase = `${gradoTexto} ${seccionTexto}`;
                codigoBase = `${abreviado}-${seccionTexto}`;
            } else {
                nombreBase = `${abreviado} ${nivelNombreLocal} ${seccionTexto}`;
                codigoBase = `${abreviado}${nivelAbrev}${seccionTexto}`;
            }

            // Si el servicio es técnico profesional, agregar salida
            if (servicioTexto && servicioTexto.startsWith("Técnico Profesional")) {
                const salida = servicioTexto.split("-")[1]?.trim();
                if (salida) {
                    nombreBase += ` - ${salida}`;
                    const abrevSalida =
                        abreviaturasSalidas[salida] ??
                        salida.replace(/\s+/g, '').slice(0, 3).toUpperCase();
                    codigoBase += `-${abrevSalida}`;
                }
            }

            // Quitar espacios redundantes y aplicar formato limpio
            nombreCurso.value = nombreBase.trim();
            codigoCurso.value = codigoBase.replace(/\s+/g, '').toUpperCase();
        }

        // Escuchar cambios en los selects
        document.getElementById('id_grado').addEventListener('change', actualizarNombreYCodigo);
        document.getElementById('id_seccion').addEventListener('change', actualizarNombreYCodigo);
        document.getElementById('id_servicio').addEventListener('change', actualizarNombreYCodigo);
    </script>

    <?= $this->endSection() ?>
</div>