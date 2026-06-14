let tipoUsuario = 0;

const getVal = (id) => document.getElementById(id)?.value || "";

$(document).ready(function () {
  const baseBuscarCursos = getVal("buscar-cursos-url");
  const baseBuscarAsignaturas = getVal("buscar-asignaturas-url");
  const baseBuscarDocentes = getVal("buscar-docentes-url");
  const estudiantesEspecialURL = getVal("estudiantes-especial-url");

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
    limpiarTablaEspecial();

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
      cargarEstudiantesEspecial(idDistribucion);
    } else {
      limpiarTablaEspecial();
    }
  });

  function cargarCursos(docenteId) {
    $("#curso").empty().append('<option value="">Seleccione un curso</option>');
    $("#asignatura")
      .empty()
      .append('<option value="">Seleccione una asignatura</option>');

    $("#id_distribucion_asignatura").val("");
    limpiarTablaEspecial();

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

  function cargarEstudiantesEspecial(idDistribucion) {
    const $thead = $("#tabla-especial thead");
    const $tbody = $("#tabla-especial tbody");

    $thead.html(`
      <tr>
        <th class="col-no">No.</th>
        <th class="col-alumno">Alumno</th>
        <th class="col-nota">C.F.</th>
        <th class="col-faltante">Puntos faltantes</th>
        <th class="col-nota">C.E.</th>
        <th class="col-nota">C.ESP.F.</th>
      </tr>
    `);

    $tbody.html(`
      <tr>
        <td colspan="6" class="text-center">Cargando estudiantes...</td>
      </tr>
    `);

    $.getJSON(
      estudiantesEspecialURL,
      {
        id_distribucion_asignatura: idDistribucion,
      },
      function (data) {
        $tbody.empty();

        if (!Array.isArray(data) || data.length === 0) {
          $tbody.html(`
            <tr>
              <td colspan="6" class="text-center">
                No hay estudiantes disponibles para evaluación especial.
              </td>
            </tr>
          `);
          return;
        }

        data.forEach((est, i) => {
          const cf = parseFloat(est.calificacion_final || 0);
          const faltante = Math.max(0, 100 - cf);

          const ceGuardada =
            est.calif_e_especial !== null &&
            est.calif_e_especial !== undefined &&
            est.calif_e_especial !== ""
              ? parseFloat(est.calif_e_especial)
              : null;

          const especialFinalGuardada =
            est.calif_especial !== null &&
            est.calif_especial !== undefined &&
            est.calif_especial !== ""
              ? parseFloat(est.calif_especial)
              : null;

          const especialFinal =
            especialFinalGuardada !== null
              ? especialFinalGuardada
              : cf + (ceGuardada || 0);

          $tbody.append(`
            <tr>
              <td class="col-no">${i + 1}</td>

              <td class="col-alumno">
                ${est.apellido}, ${est.nombre}
              </td>

              <td class="col-nota">
                ${cf.toFixed(2)}
              </td>

             <td class="col-faltante">
  ${faltante.toFixed(2)}
</td>

              <td class="col-nota">
                <input type="number"
                  name="calif_e_especial[${est.id_inscripcion}]"
                  value="${ceGuardada !== null ? ceGuardada.toFixed(2) : ""}"
                  class="form-control input-nota-calif nota-especial"
                  min="0"
                  max="${faltante}"
                  step="0.01"
                  data-cf="${cf}"
                  data-faltante="${faltante}">
              </td>

              <td class="col-nota especial-final">
                ${especialFinal.toFixed(2)}
              </td>
            </tr>
          `);

          recalcularFilaEspecial(
            $tbody.find(
              `input[name="calif_e_especial[${est.id_inscripcion}]"]`,
            ),
          );
        });
      },
    );
  }

  function recalcularFilaEspecial($input) {
    const cf = parseFloat($input.data("cf")) || 0;
    const faltante = parseFloat($input.data("faltante")) || 0;
    let ce = parseFloat($input.val()) || 0;

    if (ce > faltante) {
      ce = faltante;
      $input.val(faltante.toFixed(2));
    }

    if (ce < 0) {
      ce = 0;
      $input.val("0.00");
    }

    const finalEspecial = cf + ce;
    const $tr = $input.closest("tr");

    $tr.find(".especial-final").text(finalEspecial.toFixed(2));
  }

  $(document).on("input", ".nota-especial", function () {
    recalcularFilaEspecial($(this));
  });

  function limpiarTablaEspecial() {
    $("#tabla-especial thead").html(`
      <tr>
        <th class="col-no">No.</th>
        <th class="col-alumno">Alumno</th>
        <th class="col-nota">C.F.</th>
        <th class="col-faltante">Puntos faltantes</th>
        <th class="col-nota">C.E.</th>
        <th class="col-nota">C.ESP.F.</th>
      </tr>
    `);

    $("#tabla-especial tbody").html(`
      <tr>
        <td colspan="6" class="text-center">
          Seleccione docente, curso y asignatura.
        </td>
      </tr>
    `);
  }

  limpiarTablaEspecial();
});
