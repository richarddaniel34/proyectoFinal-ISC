// ================== calificaciones-tecnicas.js ==================

let tipoUsuario = 0;
let _alumnosCache = null;
let _raConfig = [];

const getVal = (id) => document.getElementById(id)?.value || "";

// ================== HELPERS ==================

function limpiarTablaTecnica() {
  $("#tabla-tecnica tbody").empty();
  $("#inscripciones-container").empty();
}

function limpiarEncabezadoRA() {
  $(".valor-ra").val("");
  $(".minimo-ra").val("");
  $("#total-ra-configurados").val("");
}

function setListaUnicaInscripciones(estudiantes) {
  const $box = $("#inscripciones-container");
  if (!$box.length) return;

  $box.empty();

  (estudiantes || []).forEach((est) => {
    $box.append(
      `<input type="hidden" name="id_inscripcion[]" value="${est.id_inscripcion}">`,
    );
  });
}

function getMinimoRA(numeroRa) {
  const ra = _raConfig.find(
    (item) => Number(item.numero_ra) === Number(numeroRa),
  );
  return ra ? parseFloat(ra.minimo_ra) || 0 : 0;
}

function getValorRA(numeroRa) {
  const ra = _raConfig.find(
    (item) => Number(item.numero_ra) === Number(numeroRa),
  );
  return ra ? parseFloat(ra.valor_ra) || 0 : 0;
}

function getIdRaConfig(numeroRa) {
  const ra = _raConfig.find(
    (item) => Number(item.numero_ra) === Number(numeroRa),
  );
  return ra ? ra.id : "";
}

function recalcularTotalRaConfigurado() {
  let total = 0;

  _raConfig.forEach((ra) => {
    total += parseFloat(ra.valor_ra) || 0;
  });

  $("#total-ra-configurados").val(total);
}

// ================== CARGAR CONFIGURACIÓN RA ==================

function cargarConfiguracionRA(callback = null) {
  const idDistribucion = $("#id_distribucion_asignatura").val();
  const idSchoolYear = $("#schoolYear").val();
  const url = getVal("obtener-ra-config-url");

  _raConfig = [];
  limpiarEncabezadoRA();

  if (!idDistribucion || !idSchoolYear || !url) {
    if (typeof callback === "function") callback();
    return;
  }

  $.getJSON(
    url,
    {
      id_distribucion_asignatura: idDistribucion,
      id_schoolyear: idSchoolYear,
    },
    function (data) {
      _raConfig = Array.isArray(data) ? data : [];

      _raConfig.forEach((ra) => {
        const numero = Number(ra.numero_ra);

        $(`#valor-ra-${numero}`).val(ra.valor_ra);
        $(`#minimo-ra-${numero}`).val(ra.minimo_ra);
      });

      recalcularTotalRaConfigurado();

      if (typeof callback === "function") callback();
    },
  ).fail(function (xhr) {
    console.error(
      "Error cargando configuración RA:",
      xhr.status,
      xhr.responseText,
    );
    if (typeof callback === "function") callback();
  });
}

// ================== VALIDACIÓN VISUAL DE NOTAS ==================

function validarNotaRA($input) {
  const ra = $input.data("ra");
  const valor = parseFloat($input.val());

  const minimo = getMinimoRA(ra);
  const maximo = getValorRA(ra);

  $input.removeClass("nota-baja nota-excede-ra");

  if (isNaN(valor)) return;

  if (maximo > 0 && valor > maximo) {
    $input.addClass("nota-excede-ra");
    return;
  }

  if (minimo > 0 && valor < minimo) {
    $input.addClass("nota-baja");
  }
}

function recalcularTotalEstudiante($tr) {
  let total = 0;

  for (let ra = 1; ra <= 10; ra++) {
    const $cra = $tr.find(`.cra-ra[data-ra="${ra}"]`);
    const $rp1 = $tr.find(`.rp1-ra[data-ra="${ra}"]`);
    const $rp2 = $tr.find(`.rp2-ra[data-ra="${ra}"]`);

    const cra = parseFloat($cra.val());
    const rp1 = parseFloat($rp1.val());
    const rp2 = parseFloat($rp2.val());

    const valores = [cra, rp1, rp2].filter((v) => !isNaN(v));

    if (valores.length > 0) {
      total += Math.max(...valores);
    }
  }

  $tr.find(".total-tecnico").val(total);
}

$(document).on("input", ".nota-ra", function () {
  const $input = $(this);
  const $tr = $input.closest("tr");

  validarNotaRA($input);
  recalcularTotalEstudiante($tr);
});

// ================== CARGAR ESTUDIANTES ==================

function cargarAlumnosTecnicosRA() {
  const cursoId = $("#curso").val();
  const urlBase = getVal("estudiantes-curso-url");
  const $tbody = $("#tabla-tecnica tbody");

  if (!cursoId) {
    limpiarTablaTecnica();
    return;
  }

  $tbody.empty();

  const idSchoolYear = getVal("schoolYear");
  console.log("Cargando estudiantes técnicos RA1:", {
    url: `${urlBase}${cursoId}`,
    cursoId: cursoId,
    idSchoolYear: idSchoolYear,
  });

  $.getJSON(
    `${urlBase}${cursoId}`,
    { id_schoolyear: idSchoolYear },
    function (estudiantes) {
      console.log("Respuesta estudiantes técnicos RA2:", estudiantes);

      _alumnosCache = Array.isArray(estudiantes)
        ? estudiantes
        : (estudiantes.estudiantes ?? []);

      if (!_alumnosCache.length) {
        $tbody.append(`
        <tr>
          <td colspan="33" class="text-center">No hay estudiantes</td>
        </tr>
      `);
        return;
      }

      setListaUnicaInscripciones(_alumnosCache);

      _alumnosCache.forEach((est, i) => {
        let columnasRA = "";

        for (let ra = 1; ra <= 10; ra++) {
          const idRaConfig = getIdRaConfig(ra);

          columnasRA += `
          <td>
            <input type="hidden"
                   name="ra_notas[${est.id_inscripcion}][${ra}][id_ra_configuracion]"
                   value="${idRaConfig}">

            <input type="number"
                   name="ra_notas[${est.id_inscripcion}][${ra}][cra]"
                   class="form-control form-control-sm nota-ra cra-ra"
                   data-ra="${ra}"
                   data-insc="${est.id_inscripcion}"
                   min="0"
                   step="1">
          </td>

          <td>
            <input type="number"
                   name="ra_notas[${est.id_inscripcion}][${ra}][rp1]"
                   class="form-control form-control-sm nota-ra rp1-ra"
                   data-ra="${ra}"
                   data-insc="${est.id_inscripcion}"
                   min="0"
                   step="1">
          </td>

          <td>
            <input type="number"
                   name="ra_notas[${est.id_inscripcion}][${ra}][rp2]"
                   class="form-control form-control-sm nota-ra rp2-ra"
                   data-ra="${ra}"
                   data-insc="${est.id_inscripcion}"
                   min="0"
                   step="1">
          </td>
        `;
        }

        $tbody.append(`
        <tr data-insc="${est.id_inscripcion}">
          <td>${i + 1}</td>
          <td>${est.apellido}, ${est.nombre}</td>
          ${columnasRA}
          <td>
            <input type="number"
                   name="total[${est.id_inscripcion}]"
                   class="form-control form-control-sm total-tecnico"
                   readonly>
          </td>
        </tr>
      `);
      });
    },
  ).fail(function (xhr) {
    console.error("Error estudiantes:", xhr.status, xhr.responseText);
  });
}

function cargarNotasTecnicas() {
  const url = getVal("obtener-notas-tecnicas-url");

  const idDistribucion = $("#id_distribucion_asignatura").val();

  const idSchoolYear = $("#schoolYear").val();

  const idPeriodo = $("#periodo").val();

  if (!idDistribucion || !idSchoolYear || !idPeriodo) {
    return;
  }

  $.getJSON(
    url,
    {
      id_distribucion_asignatura: idDistribucion,
      id_schoolyear: idSchoolYear,
      id_periodo: idPeriodo,
    },
    function (notas) {
      console.log("Notas recuperadas:", notas);

      notas.forEach((nota) => {
        const insc = nota.id_inscripcion;

        const raConfig = _raConfig.find(
          (r) => Number(r.id) === Number(nota.id_ra_configuracion),
        );

        if (!raConfig) return;

        const numeroRa = raConfig.numero_ra;

        const prefijo = `ra_notas[${insc}][${numeroRa}]`;

        $(`input[name="${prefijo}[cra]"]`).val(nota.cra);

        $(`input[name="${prefijo}[rp1]"]`).val(nota.rp1);

        $(`input[name="${prefijo}[rp2]"]`).val(nota.rp2);
      });

      $(".nota-ra").trigger("input");
    },
  );
}

// ================== INIT ==================

$(document).ready(function () {
  const baseBuscarCursos = getVal("buscar-cursos-url");
  const baseBuscarAsignaturas = getVal("buscar-asignaturas-url");
  const baseBuscarDocentes = getVal("buscar-docentes-url");

  tipoUsuario = parseInt(getVal("tipo_usuario") || 0);

  $("#curso").select2({
    theme: "bootstrap4",
    placeholder: "Seleccione un curso",
    allowClear: true,
    width: "100%",
  });

  $("#asignatura").select2({
    theme: "bootstrap4",
    placeholder: "Seleccione un módulo",
    allowClear: true,
    width: "100%",
  });

  if ($("#periodo").length) {
    $("#periodo").select2({
      theme: "bootstrap4",
      placeholder: "Seleccione un período",
      allowClear: true,
      width: "100%",
    });
  }

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
        data: (params) => ({
          search: params.term || "",
        }),
        processResults: (data) => ({
          results: data,
        }),
        cache: true,
      },
    });
  }

  const docenteIdInicial = $("#docente").val();

  if (docenteIdInicial) {
    $.getJSON(`${baseBuscarCursos}${docenteIdInicial}`, function (data) {
      $("#curso").empty();
      $("#curso").append('<option value="">Seleccione un curso</option>');

      (data || []).forEach((c) => {
        $("#curso").append(new Option(c.text, c.id));
      });
    });
  }

  $("#docente")
    .off("change.tecnica")
    .on("change.tecnica", function () {
      const docenteId = $(this).val();

      $("#curso").empty().trigger("change");
      $("#asignatura").empty().trigger("change");

      _alumnosCache = null;
      _raConfig = [];
      limpiarTablaTecnica();
      limpiarEncabezadoRA();

      if (!docenteId) return;

      $.getJSON(`${baseBuscarCursos}${docenteId}`, function (data) {
        $("#curso").append('<option value="">Seleccione un curso</option>');

        (data || []).forEach((c) => {
          $("#curso").append(new Option(c.text, c.id));
        });
      });
    });

  $("#curso")
    .off("change.tecnica")
    .on("change.tecnica", function () {
      const cursoId = $(this).val();
      const docenteId = $("#docente").val();

      $("#asignatura").empty().trigger("change");

      _alumnosCache = null;
      _raConfig = [];
      limpiarTablaTecnica();
      limpiarEncabezadoRA();

      if (!cursoId || !docenteId) return;

      $.getJSON(
        `${baseBuscarAsignaturas}${docenteId}/${cursoId}`,
        function (data) {
          $("#asignatura").append(
            '<option value="">Seleccione una asignatura</option>',
          );

          (data || []).forEach((a) => {
            const opt = new Option(a.text, a.id);

            const esTec = Number(a.es_tecnica) === 1 ? 1 : 0;
            $(opt).attr("data-es_tecnica", esTec);

            if (a.id_distribucion_asignatura) {
              $(opt).attr(
                "data-id_distribucion_asignatura",
                a.id_distribucion_asignatura,
              );
            }

            $("#asignatura").append(opt);
          });

          $("#asignatura").trigger("change.select2");
        },
      ).fail(function (xhr) {
        console.error(
          "Error cargando asignaturas:",
          xhr.status,
          xhr.responseText,
        );
      });
    });

  $("#asignatura, #periodo")
    .off("change.tecnica")
    .on("change.tecnica", function () {
      const idDistribucion =
        $("#asignatura option:selected").attr(
          "data-id_distribucion_asignatura",
        ) || "";

      $("#id_distribucion_asignatura").val(idDistribucion);

      if ($("#asignatura").val() && idDistribucion && $("#periodo").val()) {
        cargarConfiguracionRA(function () {
          cargarAlumnosTecnicosRA();

          setTimeout(function () {
            cargarNotasTecnicas();
          }, 300);
        });
      } else {
        limpiarTablaTecnica();
      }
    });

  $(".tabla-calificaciones-form")
    .off("submit.tecnica")
    .on("submit.tecnica", function (e) {
      e.preventDefault();

      if (!$("#id_distribucion_asignatura").val()) {
        Swal.fire({
          icon: "warning",
          title: "Seleccione un módulo técnico",
          text: "Debe seleccionar docente, curso y módulo antes de guardar.",
        });
        return false;
      }

      if ($("#periodo").length && !$("#periodo").val()) {
        Swal.fire({
          icon: "warning",
          title: "Seleccione un período",
          text: "Debe seleccionar el período en que se publican los RA.",
        });
        return false;
      }

      Swal.fire({
        title: "¿Guardar calificaciones técnicas?",
        text: "Se registrarán las calificaciones por Resultado de Aprendizaje.",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
});
