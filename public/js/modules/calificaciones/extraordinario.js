let tipoUsuario = 0;

const getVal = (id) => document.getElementById(id)?.value || "";

$(document).ready(function () {
  const baseBuscarCursos = getVal("buscar-cursos-url");
  const baseBuscarAsignaturas = getVal("buscar-asignaturas-url");
  const baseBuscarDocentes = getVal("buscar-docentes-url");
  const estudiantesReprobadosURL = getVal("estudiantes-extraordinario-url");

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
    limpiarTablaExtraordinario();

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
      cargarEstudiantesExtraordinario(idDistribucion);
    } else {
      limpiarTablaExtraordinario();
    }
  });

  function cargarCursos(docenteId) {
    $("#curso").empty().append('<option value="">Seleccione un curso</option>');
    $("#asignatura")
      .empty()
      .append('<option value="">Seleccione una asignatura</option>');
    $("#id_distribucion_asignatura").val("");
    limpiarTablaExtraordinario();

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

  function cargarEstudiantesExtraordinario(idDistribucion) {
    const $thead = $("#tabla-extraordinario thead");
    const $tbody = $("#tabla-extraordinario tbody");

    $thead.html(`
  <tr>
  <th class="col-no">No.</th>
  <th class="col-alumno">Alumno</th>
  <th class="col-nota">C.F.</th>
  <th class="col-nota">30% C.F.</th>
  <th class="col-nota">C.E. EX</th>
  <th class="col-nota">70% C.E. EX</th>
  <th class="col-nota">C.EX.F.</th>
</tr>
`);

    $tbody.html(`
      <tr>
        <td colspan="7" class="text-center">Cargando estudiantes...</td>
      </tr>
    `);

    $.getJSON(
      estudiantesReprobadosURL,
      {
        id_distribucion_asignatura: idDistribucion,
      },
      function (data) {
        $tbody.empty();

        if (!Array.isArray(data) || data.length === 0) {
          $tbody.html(`
          <tr>
            <td colspan="4" class="text-center">No hay estudiantes con calificación final menor de 70.</td>
          </tr>
        `);
          return;
        }

        data.forEach((est, i) => {
          const cf = parseFloat(est.calificacion_final || 0);
          const cf30 = cf * 0.3;

          const ceexGuardada =
            est.calif_e_extraordinaria !== null &&
            est.calif_e_extraordinaria !== undefined &&
            est.calif_e_extraordinaria !== ""
              ? parseFloat(est.calif_e_extraordinaria)
              : null;

          const ceex70 = ceexGuardada !== null ? ceexGuardada * 0.7 : 0;

          const cexfGuardada =
            est.calif_extraordinaria !== null &&
            est.calif_extraordinaria !== undefined &&
            est.calif_extraordinaria !== ""
              ? parseFloat(est.calif_extraordinaria)
              : null;

          const cexf = cexfGuardada !== null ? cexfGuardada : cf30 + ceex70;

          $tbody.append(`
    <tr>
      <td class="col-no">${i + 1}</td>

      <td class="col-alumno">${est.apellido}, ${est.nombre}</td>

      <td class="col-nota">${cf.toFixed(2)}</td>

      <td class="col-nota">${cf30.toFixed(2)}</td>

      <td class="col-nota">
        <input type="number"
          name="calif_e_extraordinaria[${est.id_inscripcion}]"
          value="${ceexGuardada !== null ? ceexGuardada.toFixed(2) : ""}"
          class="form-control input-nota-calif nota-extraordinaria"
          min="0"
          max="100"
          step="0.01"
          data-cf="${cf}">
      </td>

      <td class="col-nota ceex-70">${ceex70.toFixed(2)}</td>

      <td class="col-nota cexf-final">${cexf.toFixed(2)}</td>
    </tr>
  `);

          recalcularFilaExtraordinario(
            $tbody.find(
              `input[name="calif_e_extraordinaria[${est.id_inscripcion}]"]`,
            ),
          );
        });
      },
    );
  }

  function recalcularFilaExtraordinario($input) {
  const ceex = parseFloat($input.val()) || 0;
  const cf = parseFloat($input.data("cf")) || 0;

  const cf30 = cf * 0.3;
  const ceex70 = ceex * 0.7;
  const cexf = cf30 + ceex70;

  const $tr = $input.closest("tr");

  $tr.find(".ceex-70").text(ceex70.toFixed(2));
  $tr.find(".cexf-final").text(cexf.toFixed(2));
}

  $(document).on("input", ".nota-extraordinaria", function () {
    recalcularFilaExtraordinario($(this));
  });

  function limpiarTablaExtraordinario() {
    $("#tabla-extraordinario thead").html(`
      <tr>
       <th class="col-no">No.</th>
  <th class="col-alumno">Alumno</th>
  <th class="col-nota">C.F.</th>
  <th class="col-nota">30% C.F.</th>
  <th class="col-nota">C.E. EX</th>
  <th class="col-nota">70% C.E. EX</th>
  <th class="col-nota">C.EX.F.</th>
      </tr>
    `);

    $("#tabla-extraordinario tbody").html(`
      <tr>
        <td colspan="7" class="text-center">Seleccione docente, curso y asignatura.</td>
      </tr>
    `);
  }

  limpiarTablaExtraordinario();
});


/**
 * Agregar color de fondo a calificaciones < 70
 * Agregar validaciones basicas (estrictas pero flexibles)
 */
