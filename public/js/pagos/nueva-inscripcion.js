$(document).ready(function () {
  const config = window.PAGOS_INSCRIPCION || {};

  const idSchoolYearActual =
    $("#id_schoolYear_actual").val() || $("#id_schoolYear").val();
  const idSchoolYearEspera = $("#id_schoolYear_espera").val() || "";
  const totalInput = document.getElementById("total_pago");
  const pagarTodoCheckbox = document.getElementById("pago_completo");

  const costoInscripcion = Number(config.costoInscripcion || 0);
  const costoMensualidad = Number(config.costoMensualidad || 0);
  const cantidadMensualidades = Number(config.cantidadMensualidades || 0);
  const costoAnualMensualidades = costoMensualidad * cantidadMensualidades;

  if (typeof iniciarSelect2 === "function") {
    iniciarSelect2("#id_estudiante_busqueda", "Buscar estudiante");
  } else {
    $("#id_estudiante_busqueda").select2({
      placeholder: "Buscar estudiante",
      allowClear: true,
      width: "100%",
    });
  }

  function validarSeleccion() {
    let total = $(".check-inscribir:checked").length;
    $("#btn-registrar-inscripcion").prop("disabled", total === 0);
    actualizarMiniResumen();
  }

  function actualizarTotal() {
    let total = 0;

    $(".check-inscribir:checked").each(function () {
      total += pagarTodoCheckbox.checked
        ? costoInscripcion + costoAnualMensualidades
        : costoInscripcion;
    });

    totalInput.value = total.toFixed(2);
    actualizarMiniResumen();
  }

  function formatoMonto(valor) {
    return Number(valor || 0).toFixed(2);
  }

  function actualizarMiniResumen() {
    const totalFilas = $("#estudiantes-list tr").not(
      ":has(td[colspan])",
    ).length;

    const totalSeleccionados = $(".check-inscribir:checked").length;

    $("#contador-estudiantes").text(totalFilas);
    $("#contador-seleccionados").text(totalSeleccionados);
    $("#total-mini").text(totalInput.value || "0.00");
  }

  function pintarMensajeTabla(mensaje) {
    $("#estudiantes-list").html(`
    <tr>
      <td colspan="8" class="tabla-empty">
        <i class="fas fa-search"></i>
        <span>${mensaje}</span>
      </td>
    </tr>
  `);
  }

  function pintarFilaEstudiante(est) {
    let estado =
      est.estado_inscripcion || (est.inscrito ? "Inscrito" : "No inscrito");

    let proceso =
      est.tipo_proceso || (est.inscrito ? "Bloqueado" : "Nuevo ingreso");

    let puede = est.puede_inscribirse;
    if (puede === undefined) {
      puede = !est.inscrito;
    }

    let idSchoolYearDestino =
      est.tipo_proceso === "Reinscripción" && idSchoolYearEspera
        ? idSchoolYearEspera
        : idSchoolYearActual;

    let claseEstado = puede ? "badge bg-success" : "badge bg-danger";

    let opciones = `<option value="">Seleccione</option>`;

    if (Array.isArray(est.servicios)) {
      est.servicios.forEach((serv) => {
        const value =
          serv.es_tecnico == 1 && serv.salida_id
            ? `${serv.id_servicio}|${serv.salida_id}`
            : serv.id_servicio;

        opciones += `<option value="${value}">${serv.nombre}</option>`;
      });
    }

    return `
<tr class="${!puede ? "fila-bloqueada" : ""}">
  <td>${est.nombre} ${est.apellido}</td>
  <td>${est.matricula}</td>

  <td>
    <span class="badge bg-info">${proceso}</span>
  </td>

  <td>
    <select class="form-control servicio-select"
      data-id="${est.id}"
      name="estudiantes[${est.id}][id_servicio]"
      ${!puede ? "disabled" : ""}>
      ${opciones}
    </select>

    <input type="hidden" name="estudiantes[${est.id}][id_grado]" value="${est.id_grado_destino || est.id_grado_nivel}">
    <input type="hidden" name="estudiantes[${est.id}][id_seccion_actual]" value="${est.id_seccion_actual || ""}">
    <input type="hidden" name="estudiantes[${est.id}][id_schoolYear_destino]" value="${idSchoolYearDestino}">
    <input type="hidden" id="curso-${est.id}" name="estudiantes[${est.id}][id_curso]">
    <input type="hidden" name="estudiantes[${est.id}][id_escuela]" value="${est.id_escuela_destino || est.id_escuela}">
  </td>

  <td>
    <input type="text" class="form-control text-center"
      id="curso-visual-${est.id}" readonly>
  </td>

  <td>
    <input type="text" class="form-control text-end"
      value="${formatoMonto(costoInscripcion)}" readonly>
  </td>

  <td>
    <input type="checkbox"
      class="check-inscribir"
      data-id="${est.id}"
      name="estudiantes[${est.id}][inscribir]"
      value="1"
      ${!puede ? "disabled" : ""}>
  </td>

  <td>
    <span class="${claseEstado}">${estado}</span>
  </td>
</tr>`;
  }

  $(document).on("change", ".check-inscribir", function () {
    validarSeleccion();
    actualizarTotal();
  });

  $("#pago_completo").on("change", actualizarTotal);

  $("#id_estudiante_busqueda").on("change", function () {
    let id_estudiante = $(this).val();
    let tabla = $("#estudiantes-list");

    tabla.empty();
    $("#id_responsable").val("");
    $("#btn-registrar-inscripcion").prop("disabled", true);
    totalInput.value = "0.00";

    if (!id_estudiante) {
      pintarMensajeTabla(
        "Seleccione un estudiante para cargar el grupo familiar.",
      );
      actualizarMiniResumen();
      return;
    }

    $.ajax({
      url: config.urlGrupoFamiliar,
      type: "GET",
      data: { id_estudiante },
      dataType: "json",
      success: function (response) {
        if (
          response.status !== "success" ||
          !Array.isArray(response.data) ||
          response.data.length === 0
        ) {
          tabla.append(
            '<tr><td colspan="8">No hay estudiantes vinculados</td></tr>',
          );
          return;
        }

        $("#id_responsable").val(response.id_responsable);

        response.data.forEach((est) => {
          console.log("ESTUDIANTE:", est.nombre);
          console.log("grado actual expediente:", est.id_grado_nivel);
          console.log("grado destino:", est.id_grado_destino);
          console.log("tipo proceso:", est.tipo_proceso);
          tabla.append(pintarFilaEstudiante(est));
        });
      },
      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Error cargando estudiantes vinculados");
      },
    });
  });

  $(document).on("change", ".servicio-select", function () {
    let estudianteId = $(this).data("id");
    let fila = $(this).closest("tr");

    let servicio = $(this).val();
    let idGrado = fila
      .find(`input[name="estudiantes[${estudianteId}][id_grado]"]`)
      .val();
    let idEscuela = fila
      .find(`input[name="estudiantes[${estudianteId}][id_escuela]"]`)
      .val();
    let idSeccionActual = fila
      .find(`input[name="estudiantes[${estudianteId}][id_seccion_actual]"]`)
      .val();
    let idSchoolYear = fila
      .find(`input[name="estudiantes[${estudianteId}][id_schoolYear_destino]"]`)
      .val();

    $(`#curso-${estudianteId}`).val("");
    $(`#curso-visual-${estudianteId}`).val("");

    if (!servicio) return;

    $.ajax({
      url: config.urlCursos,
      type: "GET",
      data: {
        servicio_compuesto: servicio,
        id_grado: idGrado,
        id_escuela: idEscuela,
        id_schoolyear: idSchoolYear,
        id_seccion_actual: idSeccionActual,
      },
      dataType: "json",
      success: function (response) {
        if (
          response.status !== "success" ||
          !Array.isArray(response.data) ||
          response.data.length === 0
        ) {
          alert("No hay cursos disponibles");
          return;
        }

        response.data.forEach((c) => {
          c.disponibles = Number(c.capacidad) - Number(c.ocupados);
        });

        let mejor = response.data[0];
        if (!mejor) {
          alert("No hay cupo disponible");
          return;
        }

        let nombre = mejor.nombre_curso || "Curso";

        $(`#curso-${estudianteId}`).val(mejor.id);
        $(`#curso-visual-${estudianteId}`).val(
          `${nombre} (${mejor.ocupados}/${mejor.capacidad})`,
        );
      },
      error: function (xhr) {
        console.error(xhr.responseText);
        alert("Error al obtener cursos");
      },
    });
  });
});
