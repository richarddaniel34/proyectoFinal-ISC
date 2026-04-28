// ================== calificaciones.js (FINAL consolidado) ==================

// ✅ Variables globales
let funcion = "";
let tipoUsuario = 0;
let _alumnosCache = null; // cache del último listado de alumnos

// Helpers
const getVal = (id) => document.getElementById(id)?.value || "";

// ========= BUILDERS (ENCABEZADOS) =========

// Académico: THEAD de 2 filas por periodo: cada competencia con P# y RP#
function buildTheadAcademico(periodo, competencias, ordinal) {
  const $table = $(`#tabla-${periodo}`);
  const $thead = $table.find("thead");
  $thead.empty();

  // Fila 1
  let row1 = '<tr><th rowspan="2">No.</th><th rowspan="2">Alumno</th>';
  (competencias || []).forEach((code) => {
    row1 += `<th colspan="2">${String(code).toUpperCase()}</th>`;
  });
  row1 += "</tr>";

  // Fila 2
  let row2 = "<tr>";
  (competencias || []).forEach(() => {
    row2 += `<th>${periodo}</th><th>RP${periodo.replace("P", "")}</th>`;
  });
  row2 += "</tr>";

  $thead.html(row1 + row2);
  $table.css({ tableLayout: "fixed", width: "100%" });
}

// Técnica: THEAD minimal (No., Alumno, CAL, AC, %)
function buildTheadTecnico(periodo /*ordinal no usado*/) {
  const $thead = $(`#tabla-${periodo} thead`);
  $thead.html(`
    <tr>
      <th>No.</th>
      <th>Alumno</th>
      <th>CAL</th>
      <th>AC</th>
      <th>%</th>
    </tr>
  `);
  $(`#tabla-${periodo}`).css({ tableLayout: "fixed", width: "100%" });
}

// Resumen PC (solo académico)
function buildTheadResumen(competencias, mostrarFinalAnual = true) {
  const $thead = $("#tabla-RES thead");
  $thead.empty();

  let html = "<tr><th>No.</th><th>Alumno</th>";
  (competencias || []).forEach((code) => {
    html += `<th>PC ${String(code).toUpperCase()}</th>`;
  });
  if (mostrarFinalAnual) html += "<th>Final anual</th>";
  html += "</tr>";

  $thead.html(html);
}

// ========= UTILIDADES DE UI =========

function aplicarBloqueoFila($row, bloqueado) {
  $row.find('input[type="number"]').prop("disabled", !!bloqueado);
}

function limpiarTodasLasTablas() {
  ["P1", "P2", "P3", "P4", "RES"].forEach((p) => {
    $(`#tabla-${p} thead`).empty();
    $(`#tabla-${p} tbody`).empty();
  });
}

function tabsMostrarResumenPC(mostrar) {
  if (mostrar) {
    $("#tab-RES").closest("li").show();
  } else {
    $("#tab-RES").closest("li").hide();
    $("#content-RES").removeClass("show active");
    $("#tab-P1").tab("show");
  }
}

// Setea una sola lista de inscripciones (evita duplicados)
function setListaUnicaInscripciones(estudiantes) {
  const $box = $("#inscripciones-container");
  if (!$box.length) return;
  $box.empty();
  (estudiantes || []).forEach((est) => {
    $box.append(
      `<input type="hidden" name="id_inscripcion[]" value="${est.id_inscripcion}">`
    );
  });
}

// ========= CÁLCULOS (Resumen PC “entre 4”) =========

function recomputeResumenPC(compCodes, pesosPC = null) {
  const periodos = ["P1", "P2", "P3", "P4"];
  const DIVISOR_PERIODOS = 4; // SIEMPRE ENTRE 4

  $("#tabla-RES tbody tr").each(function () {
    const $trRes = $(this);
    const idInsc = $trRes.data("insc");

    let sumaFinal = 0,
      cuentaFinal = 0;

    compCodes.forEach((cod) => {
      let sumEff = 0;

      periodos.forEach((p) => {
        const $rowP = $(`#tabla-${p} tbody tr[data-insc="${idInsc}"]`);
        if ($rowP.length) {
          const $nota = $rowP.find(
            `input.nota-comp[data-comp="${cod}"][data-periodo="${p}"]`
          );
          const $rp = $rowP.find(
            `input.rp-comp[data-comp="${cod}"][data-periodo="${p}"]`
          );
          const v = parseFloat($nota.val());
          const rv = parseFloat($rp.val());
          const eff = Math.max(isNaN(v) ? 0 : v, isNaN(rv) ? 0 : rv); // faltante = 0
          sumEff += eff;
        }
      });

      // PC fijo sobre 4 periodos
      const pc = sumEff / DIVISOR_PERIODOS;

      const $pcInput = $trRes.find(`td.pc-cell[data-comp="${cod}"] .pc-input`);

      // Mostrar vacío si literalmente no hay ningún dato en los 4 P para esa comp
      const hayAlguno = periodos.some((p) => {
        const $rowP = $(`#tabla-${p} tbody tr[data-insc="${idInsc}"]`);
        if (!$rowP.length) return false;
        const v = parseFloat(
          $rowP
            .find(`input.nota-comp[data-comp="${cod}"][data-periodo="${p}"]`)
            .val()
        );
        const rv = parseFloat(
          $rowP
            .find(`input.rp-comp[data-comp="${cod}"][data-periodo="${p}"]`)
            .val()
        );
        return !isNaN(v) || !isNaN(rv);
      });

      if (!hayAlguno) {
        $pcInput.val("").css("background-color", "");
      } else {
        $pcInput
          .val(pc.toFixed(2))
          .css("background-color", pc < 70 ? "#f8d7da" : "#d4edda");
      }

      // Final anual: promedio simple de PCs o ponderado
      if (hayAlguno) {
        if (pesosPC && pesosPC[cod] != null)
          sumaFinal += pc * parseFloat(pesosPC[cod]);
        else {
          sumaFinal += pc;
          cuentaFinal++;
        }
      }
    });

    const $final = $trRes.find(".final-anual-input");
    const anual = pesosPC
      ? sumaFinal
      : cuentaFinal
      ? sumaFinal / cuentaFinal
      : NaN;
    if (isNaN(anual)) {
      $final.val("").css("background-color", "");
    } else {
      $final
        .val(anual.toFixed(2))
        .css("background-color", anual < 70 ? "#f8d7da" : "#d4edda");
    }
  });
}

// ========= CARGA DE FILAS =========

// Académico: por periodo, pares NOTA/RP por competencia (names claveados por id_inscripcion)
function cargarAlumnosAcademico(periodo, compCodes) {
  return new Promise((resolve, reject) => {
    const cursoId = $("#curso").val();
    if (!cursoId) return resolve();
    let estado = {};
    try {
      estado = JSON.parse($("#estadoPeriodosJson").val() || "{}");
    } catch (e) {}
    const bloqueado = !!estado?.[periodo]?.bloqueado;
    const $tbody = $(`#tabla-${periodo} tbody`).empty();

    $.getJSON(
      `/censa-prueba/public/calificaciones/estudiantes-por-curso/${cursoId}`,
      function (estudiantes) {
        _alumnosCache = Array.isArray(estudiantes) ? estudiantes : [];
        $(document).trigger("alumnos:cargados");

        if (!_alumnosCache.length) {
          $tbody.append(
            `<tr><td colspan="${
              compCodes.length * 2 + 2
            }" class="text-center">No hay estudiantes</td></tr>`
          );
          return resolve();
        }

        setListaUnicaInscripciones(_alumnosCache);

        _alumnosCache.forEach((est, i) => {
          let html = `<tr data-insc="${est.id_inscripcion}"><td>${
            i + 1
          }</td><td>${est.apellido}, ${est.nombre}</td>`;
          compCodes.forEach((cod) => {
            html += `<td><input type="number" name="${cod}_${periodo}[${
              est.id_inscripcion
            }]" class="form-control input-nota-calif nota-comp" data-comp="${cod}" data-periodo="${periodo}" step="0.01" min="0" max="100" ${
              bloqueado ? "disabled" : ""
            }></td>`;
            html += `<td><input type="number" name="rp_${cod}_${periodo}[${
              est.id_inscripcion
            }]" class="form-control input-nota-calif rp-comp" data-comp="${cod}" data-periodo="${periodo}" step="0.01" min="0" max="100" ${
              bloqueado ? "disabled" : ""
            }></td>`;
          });
          html += `</tr>`;
          $tbody.append(html);
        });

        // Traer notas guardadas
        $.getJSON(
          `/censa-prueba/public/calificaciones/obtenerNotas`,
          {
            curso: cursoId,
            asignatura: $("#asignatura").val(),
            schoolYear: $("#schoolYear").val(), // <== agregado
          },
          function (notas) {
            if (Array.isArray(notas)) {
              notas.forEach((n) => {
                const $tr = $(
                  `#tabla-${periodo} tbody tr[data-insc="${n.id_inscripcion}"]`
                );
                if ($tr.length) {
                  compCodes.forEach((cod) => {
                    if (n[`${cod}_nota`] != null)
                      $tr
                        .find(`input.nota-comp[data-comp="${cod}"]`)
                        .val(n[`${cod}_nota`]);
                    if (n[`${cod}_rp`] != null)
                      $tr
                        .find(`input.rp-comp[data-comp="${cod}"]`)
                        .val(n[`${cod}_rp`]);
                  });
                }
              });
            }
            recomputeResumenPC(compCodes);
            resolve();
          }
        );
      }
    ).fail(() => resolve());
  });
}

// Técnica: CAL/AC/% por periodo (sin RP ni Final; names ya claveados)
function cargarAlumnosTecnico(periodo) {
  const cursoId = $("#curso").val();
  if (!cursoId) return;

  let estado = {};
  try {
    estado = JSON.parse($("#estadoPeriodosJson").val() || "{}");
  } catch (e) {}
  const bloqueado = !!estado?.[periodo]?.bloqueado;

  const $tbody = $(`#tabla-${periodo} tbody`).empty();

  $.getJSON(
    `/censa-prueba/public/calificaciones/estudiantes-por-curso/${cursoId}`,
    function (estudiantes) {
      _alumnosCache = Array.isArray(estudiantes) ? estudiantes : [];
      $(document).trigger("alumnos:cargados");

      if (!_alumnosCache.length) {
        $tbody.append(
          '<tr><td colspan="5" class="text-center">No hay estudiantes</td></tr>'
        );
        return;
      }

      // lista única de inscripciones
      setListaUnicaInscripciones(_alumnosCache);

      _alumnosCache.forEach((est, i) => {
        $tbody.append(`
        <tr data-insc="${est.id_inscripcion}">
          <td>${i + 1}</td>
          <td>${est.apellido}, ${est.nombre}</td>
          <td><input type="number" name="cal[${periodo}][${
          est.id_inscripcion
        }]" class="form-control t-cal" step="0.01" min="0" ${
          bloqueado ? "disabled" : ""
        }></td>
          <td><input type="number" name="ac[${periodo}][${
          est.id_inscripcion
        }]"  class="form-control t-ac"  step="0.01" min="0" ${
          bloqueado ? "disabled" : ""
        }></td>
          <td><input type="number" name="porc[${periodo}][${
          est.id_inscripcion
        }]" class="form-control t-porc" step="0.01" readonly></td>
        </tr>
      `);
      });

      // % = (CAL*100)/AC
      $(`#tabla-${periodo}`)
        .off("input.tech")
        .on("input.tech", ".t-cal,.t-ac", function () {
          const $tr = $(this).closest("tr");
          const cal = parseFloat($tr.find(".t-cal").val()) || 0;
          const ac = parseFloat($tr.find(".t-ac").val()) || 0;
          const porc = ac > 0 ? (cal * 100) / ac : 0;
          $tr.find(".t-porc").val(porc.toFixed(2));
        });
    }
  );
}

// ========= RESUMEN PC (cuerpo) =========
function buildBodyResumen(compCodes) {
  const $tbody = $("#tabla-RES tbody").empty();

  const ests = Array.isArray(_alumnosCache) ? _alumnosCache : [];
  if (!ests.length) {
    $tbody.append(
      `<tr><td colspan="${
        compCodes.length + 3
      }" class="text-center">No hay estudiantes</td></tr>`
    );
    return;
  }

  ests.forEach((est, i) => {
    let html = `<tr data-insc="${est.id_inscripcion}">
      <td>${i + 1}</td>
      <td>${est.apellido}, ${est.nombre}</td>`;
    compCodes.forEach((cod) => {
      html += `<td class="pc-cell" data-comp="${cod}">
        <input type="number" class="form-control pc-input" step="0.01" readonly>
      </td>`;
    });
    html += `<td><input type="number" class="form-control final-anual-input" step="0.01" readonly></td></tr>`;
    $tbody.append(html);
  });
}

async function cargarPeriodosAcademicos(compCodes) {
  const periodos = ["P1", "P2", "P3", "P4"];
  for (let p of periodos) {
    buildTheadAcademico(p, compCodes);
    await cargarAlumnosAcademico(p, compCodes);
  }
  // Luego de cargar todos los periodos
  buildTheadResumen(compCodes, true);
  buildBodyResumen(compCodes);
  recomputeResumenPC(compCodes);
}

// ========= ORQUESTACIÓN: SOLO CARGAR SI HAY 3 SELECTS =========

async function refrescarSiListo() {
  const docenteId = $("#docente").val();
  const cursoId = $("#curso").val();
  const $optAsig = $("#asignatura").find("option:selected");
  const asignaturaId = $optAsig.val();

  // ✅ coerción a número al LEER y asegura hidden de distribución
  const esTecnica = Number($optAsig.data("es_tecnica")) || 0;
  const idDistrib = $optAsig.data("id_distribucion_asignatura") || "";
  $("#id_distribucion_asignatura").val(idDistrib);

  const compCodes = (window.competencias || []).map((c) =>
    (c.codigo_competencia || c).toString().toLowerCase()
  );

  if (!docenteId || !cursoId || !asignaturaId) {
    limpiarTodasLasTablas();
    return;
  }

  // Trae estado de bloqueos para este docente-curso-asignatura
  let estadoPeriodos = {};
  try {
    const url = $("#estado-periodo-url").val();
    const est = await $.getJSON(url, {
      docente: docenteId,
      curso: cursoId,
      asignatura: asignaturaId,
    });
    estadoPeriodos = est || {};
    $("#estadoPeriodosJson").val(JSON.stringify(estadoPeriodos));
    ["P1", "P2", "P3", "P4"].forEach((p) => {
      const bloq = !!estadoPeriodos[p]?.bloqueado;
      $(`#bloq${p}`).prop("checked", bloq);
    });
  } catch (e) {
    /* ignora */
  }

  if (esTecnica === 1) {
    tabsMostrarResumenPC(false);
    ["P1", "P2", "P3", "P4"].forEach((p) => {
      buildTheadTecnico(p);
      cargarAlumnosTecnico(p);
    });
    // limpia resumen por si quedó algo
    $("#tabla-RES thead, #tabla-RES tbody").empty();
  } else {
    tabsMostrarResumenPC(true);
    // ✅ reemplazamos forEach por función en cadena
    await cargarPeriodosAcademicos(compCodes);

    // cuando algún periodo termine de cargar alumnos, pinto RES desde cache
    $(document)
      .off("alumnos:cargados.resumen")
      .on("alumnos:cargados.resumen", function () {
        buildBodyResumen(compCodes);
        recomputeResumenPC(compCodes);
      });

    // si ya había cache (recarga parcial)
    if (Array.isArray(_alumnosCache) && _alumnosCache.length) {
      buildBodyResumen(compCodes);
      recomputeResumenPC(compCodes);
    }
  }
}

// ================== INIT ==================
$(document).ready(function () {
  // === Lectura de inputs ocultos / URLs ===
  const baseBuscarCursos = getVal("buscar-cursos-url");
  const baseBuscarAsignaturas = getVal("buscar-asignaturas-url");
  const baseBuscarDocentes = getVal("buscar-docentes-url");
  const obtenerEstadoURL = getVal("estado-periodo-url");
  const guardarConfigURL = getVal("guardar-config-url");
  const csrfToken = getVal("csrf-token");
  const csrfHash = getVal("csrf-hash");

  // Estado periodos y competencias
  let estadoPeriodos = {};
  try {
    estadoPeriodos = JSON.parse(getVal("estadoPeriodosJson") || "{}");
  } catch (e) {
    estadoPeriodos = {};
  }

  let competencias = [];
  try {
    competencias = JSON.parse(getVal("competenciasJson") || "[]");
  } catch (e) {
    competencias = [];
  }

  tipoUsuario = parseInt(getVal("tipo_usuario") || 0);
  funcion = (getVal("funcion_usuario") || "").trim().toLowerCase();

  // === SELECT2 ===
  $("#curso, #asignatura").select2({
    theme: "bootstrap4",
    allowClear: true,
    width: "100%",
  });
  $("#curso")
    .data("select2")
    .$container.find("input")
    .attr("placeholder", "Seleccione un curso");
  $("#asignatura")
    .data("select2")
    .$container.find("input")
    .attr("placeholder", "Seleccione una asignatura");

  const docenteIdInicial = $("#docente").val();

  // Precargar cursos si hay docente preseleccionado
  if (docenteIdInicial) {
    $.getJSON(`${baseBuscarCursos}${docenteIdInicial}`, function (data) {
      $("#curso").append('<option value="">Seleccione un curso</option>');
      (data || []).forEach((c) => $("#curso").append(new Option(c.text, c.id)));
    });
  }

  // Select de docentes (si aplica)
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

    // Al cambiar docente → recarga cursos y limpia
    $("#docente").on("change", function () {
      const docenteId = $(this).val();
      $("#curso").empty().trigger("change");
      $("#asignatura").empty().trigger("change");
      _alumnosCache = null;
      limpiarTodasLasTablas();

      if (docenteId) {
        $.getJSON(`${baseBuscarCursos}${docenteId}`, function (data) {
          $("#curso").append('<option value="">Seleccione un curso</option>');
          (data || []).forEach((c) =>
            $("#curso").append(new Option(c.text, c.id))
          );
        });
      }
    });
  }

  // Al cambiar curso → carga asignaturas (NO cargar tablas aún)
  $("#curso").on("change", function () {
    const cursoId = $(this).val();
    const docenteId = $("#docente").val();

    $("#asignatura").empty().trigger("change");
    _alumnosCache = null;
    limpiarTodasLasTablas();

    if (cursoId && docenteId) {
      $.getJSON(
        `${baseBuscarAsignaturas}${docenteId}/${cursoId}`,
        function (data) {
          $("#asignatura").append(
            '<option value="">Seleccione una asignatura</option>'
          );
          (data || []).forEach((a) => {
            const opt = new Option(a.text, a.id);
            // ✅ coerción a número y data-id_distrib
            const esTec = Number(a.es_tecnica) === 1 ? 1 : 0;
            $(opt).attr("data-es_tecnica", esTec);
            if (a.id_distribucion_asignatura) {
              $(opt).attr(
                "data-id_distribucion_asignatura",
                a.id_distribucion_asignatura
              );
            }
            $("#asignatura").append(opt);
          });
        }
      );
    }
  });

  // Cuando hay docente/curso/asignatura → setea distribución y pinta tablas
  $("#asignatura").on("change", function () {
    const idDistrib =
      $(this).find("option:selected").data("id_distribucion_asignatura") || "";
    $("#id_distribucion_asignatura").val(idDistrib); // ✅ ahora sí se envía
    _alumnosCache = null;
    refrescarSiListo(); // solo pinta si los 3 selects están completos
  });

  // Guardar configuración de bloqueos (admin/gestión)
  $("#guardar-configuracion").on("click", function () {
    const docenteId = $("#docente").val();
    const cursoId = $("#curso").val();
    const asignaturaId = $("#asignatura").val();
    const esGlobal = !docenteId && !cursoId && !asignaturaId;
    const esEspecifico = docenteId && cursoId && asignaturaId;

    if (!esGlobal && !esEspecifico) {
      alert(
        "Debe seleccionar docente, curso y asignatura, o dejar los tres vacíos para aplicar configuración global."
      );
      return;
    }

    const datos = {
      docente: docenteId,
      curso: cursoId,
      asignatura: asignaturaId,
    };
    datos[csrfToken] = csrfHash;

    ["P1", "P2", "P3", "P4"].forEach((p) => {
      datos[`configuracion[${p}][]`] = $("#bloq" + p).is(":checked") ? 1 : 0;
    });

    $.post(guardarConfigURL, datos, function (res) {
      alert(
        res?.success
          ? "Configuración guardada correctamente."
          : res?.message || "Ocurrió un error al guardar."
      );
    });
  });

  // Al cargar la página, oculta RES hasta saber si es académica
  tabsMostrarResumenPC(false);
});
