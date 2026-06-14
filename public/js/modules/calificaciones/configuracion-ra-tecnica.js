// ================== configuracion-ra-tecnica.js ==================

let tipoUsuario = 0;

const getVal = (id) => document.getElementById(id)?.value || "";

// ================== HELPERS ==================

function limpiarConfiguracionRA() {
  $("#id_distribucion_asignatura").val("");

  $(".valor-ra").val("");
  $(".minimo-ra").val("");

  $("#tabla-config-ra tbody tr").each(function () {
    $(this)
      .find(".badge")
      .removeClass("badge-success badge-warning")
      .addClass("badge-secondary")
      .text("Pendiente");
  });
}

function calcularMinimoRA($input) {
  const ra = $input.data("ra");
  const valor = parseFloat($input.val()) || 0;
  const minimo = Math.ceil(valor * 0.7);

  $("#minimo-ra-" + ra).val(valor > 0 ? minimo : "");
}

// ================== CÁLCULO 70% ==================

$(document).on("input", ".valor-ra", function () {
  calcularMinimoRA($(this));
  recalcularTotalRA();

  const valor = parseFloat($(this).val()) || 0;
  const $fila = $(this).closest("tr");
  const $badge = $fila.find(".badge");

  if (valor > 0) {
    $badge
      .removeClass("badge-secondary badge-warning")
      .addClass("badge-success")
      .text("Configurado");
  } else {
    $badge
      .removeClass("badge-success badge-warning")
      .addClass("badge-secondary")
      .text("Pendiente");
  }
});

// ================== CARGAR RA CONFIGURADOS ==================

function cargarConfiguracionRA() {
  const idDistribucion = $("#id_distribucion_asignatura").val();
  const idSchoolYear = $("#schoolYear").val();
  const url = getVal("obtener-ra-config-url");

  if (!idDistribucion || !idSchoolYear) {
    limpiarConfiguracionRA();
    return;
  }

  $(".valor-ra").val("");
  $(".minimo-ra").val("");

  $.getJSON(
    url,
    {
      id_distribucion_asignatura: idDistribucion,
      id_schoolyear: idSchoolYear,
    },
    function (data) {
      if (!Array.isArray(data) || data.length === 0) {
        return;
      }

      data.forEach((ra) => {
        const numero = ra.numero_ra;
        const valor = ra.valor_ra;
        const minimo = ra.minimo_ra;

        const $valor = $(`.valor-ra[data-ra="${numero}"]`);
        const $minimo = $(`#minimo-ra-${numero}`);

        $valor.val(valor);
        $minimo.val(minimo);

        const $fila = $valor.closest("tr");

        $fila
          .find(".badge")
          .removeClass("badge-secondary badge-warning")
          .addClass("badge-success")
          .text("Configurado");
      });
      recalcularTotalRA();
    },
  ).fail(function (xhr) {
    console.error(
      "Error cargando configuración RA:",
      xhr.status,
      xhr.responseText,
    );
  });
}

function recalcularTotalRA() {
  let total = 0;

  $(".valor-ra").each(function () {
    total += parseFloat($(this).val()) || 0;
  });

  $("#total-ra").text(total);

  const $alert = $("#alert-total-ra");

  $alert.removeClass("alert-success alert-warning alert-danger");

  if (total === 100) {
    $alert.addClass("alert-success");
  } else if (total < 100) {
    $alert.addClass("alert-warning");
  } else {
    $alert.addClass("alert-danger");
  }
}

// ================== INIT ==================

$(document).ready(function () {
  const baseBuscarCursos = getVal("buscar-cursos-url");
  const baseBuscarAsignaturas = getVal("buscar-asignaturas-url");
  const baseBuscarDocentes = getVal("buscar-docentes-url");

  tipoUsuario = parseInt(getVal("tipo_usuario") || 0);

  // ================== SELECT2 ==================

  $("#curso").select2({
    theme: "bootstrap4",
    placeholder: "Seleccione un curso",
    allowClear: true,
    width: "100%",
  });

  $("#asignatura").select2({
    theme: "bootstrap4",
    placeholder: "Seleccione un módulo técnico",
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

  // ================== PRECARGAR CURSOS SI ES DOCENTE ==================

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

  // ================== CAMBIO DE DOCENTE ==================

  $("#docente")
    .off("change.configRa")
    .on("change.configRa", function () {
      const docenteId = $(this).val();

      $("#curso").empty().trigger("change");
      $("#asignatura").empty().trigger("change");

      limpiarConfiguracionRA();

      if (!docenteId) return;

      $.getJSON(`${baseBuscarCursos}${docenteId}`, function (data) {
        $("#curso").append('<option value="">Seleccione un curso</option>');

        (data || []).forEach((c) => {
          $("#curso").append(new Option(c.text, c.id));
        });
      });
    });

  // ================== CAMBIO DE CURSO ==================

  $("#curso")
    .off("change.configRa")
    .on("change.configRa", function () {
      const cursoId = $(this).val();
      const docenteId = $("#docente").val();

      $("#asignatura").empty().trigger("change");
      limpiarConfiguracionRA();

      if (!cursoId || !docenteId) return;

      $.getJSON(
        `${baseBuscarAsignaturas}${docenteId}/${cursoId}`,
        function (data) {
          $("#asignatura").append(
            '<option value="">Seleccione un módulo técnico</option>',
          );

          (data || []).forEach((a) => {
            const esTec = Number(a.es_tecnica) === 1 ? 1 : 0;

            // Solo módulos/asignaturas técnicas
            if (esTec !== 1) return;

            const opt = new Option(a.text, a.id);

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
          "Error cargando módulos técnicos:",
          xhr.status,
          xhr.responseText,
        );
      });
    });

  // ================== CAMBIO DE MÓDULO ==================

  $("#asignatura")
    .off("change.configRa")
    .on("change.configRa", function () {
      const idDistribucion =
        $("#asignatura option:selected").attr(
          "data-id_distribucion_asignatura",
        ) || "";

      $("#id_distribucion_asignatura").val(idDistribucion);

      if ($(this).val() && idDistribucion) {
        cargarConfiguracionRA();
      } else {
        limpiarConfiguracionRA();
      }
    });

  // ================== CONFIRMACIÓN GUARDAR ==================

  $(".tabla-calificaciones-form")
    .off("submit.configRa")
    .on("submit.configRa", function (e) {
      e.preventDefault();

      if (!$("#id_distribucion_asignatura").val()) {
        Swal.fire({
          icon: "warning",
          title: "Seleccione un módulo técnico",
          text: "Debe seleccionar docente, curso y módulo técnico antes de guardar.",
        });
        return false;
      }

      const valores = $(".valor-ra")
        .map(function () {
          return parseFloat($(this).val()) || 0;
        })
        .get();

      const total = parseFloat($("#total-ra").text()) || 0;

      if (total <= 0) {
        Swal.fire({
          icon: "warning",
          title: "Sin configuración",
          text: "Debe asignar valor al menos a un Resultado de Aprendizaje.",
        });
        return false;
      }

      if (total !== 100) {
        Swal.fire({
          icon: "warning",
          title: "Configuración incompleta",
          text: "La suma de los RA debe ser exactamente 100.",
        });

        return false;
      }

      Swal.fire({
        title: "¿Guardar configuración de RA?",
        text: `La suma actual de los RA configurados es ${total}.`,
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
