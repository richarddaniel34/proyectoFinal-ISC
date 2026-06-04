let tipoUsuario = 0;

const getVal = (id) => document.getElementById(id)?.value || "";

$(document).ready(function () {
  const baseBuscarCursos = getVal("buscar-cursos-url");
  const baseBuscarAsignaturas = getVal("buscar-asignaturas-url");
  const baseBuscarDocentes = getVal("buscar-docentes-url");
  const estudiantesCompletivoURL = getVal("estudiantes-completivo-url");

  tipoUsuario = parseInt(getVal("tipo_usuario") || 0);

  $("#curso, #asignatura").select2({
    theme: "bootstrap4",
    allowClear: true,
    width: "100%",
  });

  if ($("#docente").is("select")) {
    $("#docente").select2({
      theme: "bootstrap4",
      placeholder: "--- Seleccione un docente ---",
      allowClear: true,
      width: "100%",
      ajax: {
        url: baseBuscarDocentes,
        dataType: "json",
        delay: 250,
        data: (params) => ({ search: params.term || "" }),
        processResults: (data) => ({ results: data }),
        cache: true,
      },
    });

    $("#docente").on("change", function () {
      cargarCursos($(this).val());
    });
  }

  const docenteIdInicial = $("#docente").val();

  if (docenteIdInicial) {
    cargarCursos(docenteIdInicial);
  }

  $("#curso").on("change", function () {
    const docenteId = $("#docente").val();
    const cursoId = $(this).val();

    $("#asignatura")
      .empty()
      .append('<option value="">Seleccione una asignatura</option>');
    $("#id_distribucion_asignatura").val("");
    limpiarTablaCompletivo();

    if (docenteId && cursoId) {
      cargarAsignaturas(docenteId, cursoId);
    }
  });

  $("#asignatura").on("change", function () {
    const idDistribucion =
      $(this).find("option:selected").attr("data-id_distribucion_asignatura") ||
      "";

    $("#id_distribucion_asignatura").val(idDistribucion);

    if (idDistribucion) {
      cargarEstudiantesCompletivo(idDistribucion)
    } else {
      limpiarTablaCompletivo();
    }
  });

  function cargarCursos(docenteId) {
    $("#curso").empty().append('<option value="">Seleccione un curso</option>');
    $("#asignatura")
      .empty()
      .append('<option value="">Seleccione una asignatura</option>');
    $("#id_distribucion_asignatura").val("");
    limpiarTablaCompletivo();

    if (!docenteId) return;

    $.getJSON(`${baseBuscarCursos}${docenteId}`, function (data) {
      (data || []).forEach((c) => {
        $("#curso").append(new Option(c.text, c.id));
      });

      $("#curso").trigger("change.select2");
    });
  }

  function cargarAsignaturas(docenteId, cursoId) {
    $.getJSON(
      `${baseBuscarAsignaturas}${docenteId}/${cursoId}`,
      function (data) {
        $("#asignatura")
          .empty()
          .append('<option value="">Seleccione una asignatura</option>');

        (data || []).forEach((a) => {
          const opt = $(`
          <option 
            value="${a.id}" 
            data-es_tecnica="${Number(a.es_tecnica) === 1 ? 1 : 0}"
            data-id_distribucion_asignatura="${a.id_distribucion_asignatura || ""}">
            ${a.text}
          </option>
        `);

          $("#asignatura").append(opt);
        });

        $("#asignatura").trigger("change.select2");
      },
    );
  }

  function cargarEstudiantesCompletivo(idDistribucion) {
    const $thead = $("#tabla-completivo thead");
    const $tbody = $("#tabla-completivo tbody");

    $thead.html(`
  <tr>
    <th class="col-no">No.</th>
    <th class="col-alumno">Alumno</th>
    <th class="col-nota">C.F.</th>
    <th class="col-nota">50% C.F.</th>
    <th class="col-nota">C.E.C.</th>
    <th class="col-nota">50% C.E.C.</th>
    <th class="col-nota">50% C.C.F.</th>
  </tr>
`);

    $tbody.html(`
      <tr>
        <td colspan="7" class="text-center">Cargando estudiantes...</td>
      </tr>
    `);

    $.getJSON(
      estudiantesCompletivoURL,
      {
        id_distribucion_asignatura: idDistribucion,
      },
      function (data) {
        $tbody.empty();

        if (!Array.isArray(data) || data.length === 0) {
          $tbody.html(`
          <tr>
            <td colspan="7" class="text-center">No hay estudiantes en completivos.</td>
          </tr>
        `);
          return;
        }

        data.forEach((est, i) => {
          const cf = parseFloat(est.calificacion_final || 0);
          const cf50 = cf * 0.5;

          const cecGuardada =
            est.calif_e_completiva !== null &&
            est.calif_e_completiva !== undefined &&
            est.calif_e_completiva !== ""
              ? parseFloat(est.calif_e_completiva)
              : null;

          const cec50 = cecGuardada !== null ? cecGuardada * 0.5 : 0;

          const ccfGuardada =
            est.calif_completiva !== null &&
            est.calif_completiva !== undefined &&
            est.calif_completiva !== ""
              ? parseFloat(est.calif_completiva)
              : null;

          const ccf = ccfGuardada !== null ? ccfGuardada : cf50 + cec50;

          $tbody.append(`
    <tr>
      <td class="col-no">${i + 1}</td>

      <td class="col-alumno">
        ${est.apellido}, ${est.nombre}
      </td>

      <td class="col-nota">
        ${cf.toFixed(2)}
      </td>

      <td class="col-nota">
        ${cf50.toFixed(2)}
      </td>

      <td class="col-nota">
        <input type="number"
          name="calif_e_completiva[${est.id_inscripcion}]"
          value="${cecGuardada !== null ? cecGuardada.toFixed(2) : ""}"
          class="form-control input-nota-calif nota-completiva"
          min="0"
          max="100"
          step="0.01"
          data-cf="${cf}">
      </td>

      <td class="col-nota cec-50">
        0.00
      </td>

      <td class="col-nota ccf-final">
        ${cf50.toFixed(2)}
      </td>
    </tr>
  `);
          recalcularFilaCompletivo(
            $tbody.find(
              `input[name="calif_e_completiva[${est.id_inscripcion}]"]`,
            ),
          );
        });
      },
    );
  }

  function recalcularFilaCompletivo($input) {
    const cec = parseFloat($input.val()) || 0;
    const cf = parseFloat($input.data("cf")) || 0;

    const cf50 = cf * 0.5;
    const cec50 = cec * 0.5;
    const ccf = cf50 + cec50;

    const $tr = $input.closest("tr");

    $tr.find(".cec-50").text(cec50.toFixed(2));
    $tr.find(".ccf-final").text(ccf.toFixed(2));
  }

  $(document).on("input", ".nota-completiva", function () {
    recalcularFilaCompletivo($(this));
  });

  function limpiarTablaCompletivo() {
    $("#tabla-completivo thead").html(`
      <tr>
       <th class="col-no">No.</th>
    <th class="col-alumno">Alumno</th>
    <th class="col-nota">C.F.</th>
    <th class="col-nota">50% C.F.</th>
    <th class="col-nota">C.E.C.</th>
    <th class="col-nota">50% C.E.C.</th>
    <th class="col-nota">50% C.C.F.</th>
      </tr>
    `);

    $("#tabla-completivo tbody").html(`
      <tr>
        <td colspan="7" class="text-center">Seleccione docente, curso y asignatura.</td>
      </tr>
    `);
  }

  limpiarTablaCompletivo();
});
