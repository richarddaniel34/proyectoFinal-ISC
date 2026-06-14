$(document).ready(function () {
  const getVal = (id) => document.getElementById(id)?.value || "";

  const tipoUsuario = parseInt(getVal("tipo_usuario") || 0);
  const esDocente = tipoUsuario === 3;

  const baseBuscarCursos = getVal("buscar-cursos-url");
  const baseBuscarAsignaturas = getVal("buscar-asignaturas-url");
  const baseBuscarDocentes = getVal("buscar-docentes-url");
  const baseEstudiantesCurso = getVal("estudiantes-curso-url");
  const idSchoolYear = getVal("schoolYear");

  const $docente = $("#docente");
  const $curso = $("#curso");
  const $asignatura = $("#asignatura");
  const $tbody = $("#tbody-asistencia");

  function mensajeTabla(texto, clase = "text-muted") {
    $tbody.html(`
            <tr>
                <td colspan="5" class="text-center ${clase}">
                    ${texto}
                </td>
            </tr>
        `);
  }

  function limpiarCursos() {
    $curso
      .html('<option value="">Seleccione un curso</option>')
      .trigger("change.select2");
  }

  function limpiarAsignaturas() {
    $asignatura
      .html('<option value="">Seleccione una asignatura</option>')
      .trigger("change.select2");
  }

  function cargarDocentes() {
    if (esDocente) {
      cargarCursos();
      return;
    }

    $.ajax({
      url: baseBuscarDocentes,
      type: "GET",
      dataType: "json",
      success: function (response) {
        console.log("DOCENTE:", response);
        let opciones = '<option value="">Seleccione un docente</option>';

        response.forEach((docente) => {
          opciones += `
                        <option value="${docente.id}">
                            ${docente.text}
                        </option>
                    `;
        });

        $docente.html(opciones).trigger("change.select2");
      },
    });
  }

  function cargarCursos() {
    const idDocente = $docente.val();

    limpiarCursos();
    limpiarAsignaturas();
    mensajeTabla(
      "Seleccione docente, curso y asignatura para cargar los estudiantes.",
    );

    if (!idDocente) return;

    $.ajax({
      url: baseBuscarCursos + idDocente,
      type: "GET",
      dataType: "json",
      success: function (response) {
        console.log("CURSOS:", response);
        let opciones = '<option value="">Seleccione un curso</option>';

        response.forEach((curso) => {
          opciones += `
                        <option value="${curso.id}">
                           ${curso.text}
                        </option>
                    `;
        });

        $curso.html(opciones).trigger("change.select2");
      },
    });
  }

  function cargarAsignaturas() {
    const idDocente = $docente.val();
    const idCurso = $curso.val();

    limpiarAsignaturas();
    mensajeTabla(
      "Seleccione docente, curso y asignatura para cargar los estudiantes.",
    );

    if (!idDocente || !idCurso) return;

    $.ajax({
        
      url: baseBuscarAsignaturas + idDocente + "/" + idCurso,
      type: "GET",
      dataType: "json",
      success: function (response) {
        console.log("ASIGNATURAS:", response);
        let opciones = '<option value="">Seleccione una asignatura</option>';

        response.forEach((asignatura) => {
          opciones += `
                        <option 
                            value="${asignatura.id_asignatura ?? asignatura.id}"
                            data-distribucion="${asignatura.id_distribucion_asignatura ?? ""}">
                            ${asignatura.text}
                        </option>
                    `;
        });

        $asignatura.html(opciones).trigger("change.select2");
      },
    });
  }

  function cargarEstudiantes() {
    const idCurso = $curso.val();
    const idAsignatura = $asignatura.val();
    const idDistribucion =
      $asignatura.find(":selected").data("distribucion") || "";

    $("#id_distribucion_asignatura").val(idDistribucion);

    if (!idCurso || !idAsignatura) {
      mensajeTabla(
        "Seleccione docente, curso y asignatura para cargar los estudiantes.",
      );
      return;
    }

    $.ajax({
      url: baseEstudiantesCurso + idCurso,
      type: "GET",
      dataType: "json",
      data: {
        id_schoolyear: idSchoolYear,
      },
      beforeSend: function () {
        mensajeTabla("Cargando estudiantes...");
      },
      success: function (response) {
        const estudiantes = response.estudiantes ?? response;

        if (!estudiantes || estudiantes.length === 0) {
          mensajeTabla("No hay estudiantes registrados en este curso.");
          return;
        }

        let filas = "";

        estudiantes.forEach((estudiante, index) => {
          const idInscripcion = estudiante.id_inscripcion ?? estudiante.id;
          const nombre =
            estudiante.nombre_completo ??
            `${estudiante.nombre ?? ""} ${estudiante.apellido ?? ""}`;

          filas += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>
                                ${nombre}
                                <input type="hidden" name="asistencia[${idInscripcion}][id_inscripcion]" value="${idInscripcion}">
                            </td>

                            <td class="text-center">
                                <input type="radio"
                                       name="asistencia[${idInscripcion}][estado]"
                                       value="Presente"
                                       checked>
                            </td>

                            <td class="text-center">
                                <input type="radio"
                                       name="asistencia[${idInscripcion}][estado]"
                                       value="Ausente">
                            </td>

                            <td class="text-center">
                                <input type="radio"
                                       name="asistencia[${idInscripcion}][estado]"
                                       value="Excusa">
                            </td>
                        </tr>
                    `;
        });

        $tbody.html(filas);
      },
      error: function () {
        mensajeTabla("Error al cargar los estudiantes.", "text-danger");
      },
    });
  }

  $docente.on("change", cargarCursos);
  $curso.on("change", cargarAsignaturas);
  $asignatura.on("change", cargarEstudiantes);

  cargarDocentes();
});
