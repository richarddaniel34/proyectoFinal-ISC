/**
 * //////////////////////////////////////////
 * ============= MENSUALIDADES =============
 * /////////////////////////////////////////
 */

$(document).ready(function () {
  const config = window.PAGOS_MENSUALIDAD || {};

  function actualizarMiniResumen() {
    const totalEstudiantes = $(".mensualidad-card").length;
    const mesesSeleccionados = $(".mes-checkbox:checked:not(:disabled)").length;
    const totalActual = $("#total_pago").val() || "0.00";

    $("#contador-estudiantes").text(totalEstudiantes);
    $("#contador-meses").text(mesesSeleccionados);
    $("#total-mini").text(totalActual);
  }

  if (typeof iniciarSelect2 === "function") {
    iniciarSelect2("#id_estudiante_busqueda", "Buscar estudiante");
  } else {
    $("#id_estudiante_busqueda").select2({
      placeholder: "Buscar estudiante",
      allowClear: true,
      width: "100%",
    });
  }

  $("#id_estudiante_busqueda, #id_schoolYear").on("change", function () {
    let id_estudiante = $("#id_estudiante_busqueda").val();
    let id_schoolYear = $("#id_schoolYear").val();

    limpiarMensualidades();

    if (id_estudiante && id_schoolYear) {
      obtenerResponsableYCargarMensualidades(id_estudiante, id_schoolYear);
    }
  });

  function limpiarMensualidades() {
    $("#id_responsable").val("");
    $("#estudiantes-mensualidades-list").empty();
    $("#total_pago").val("0.00");
    actualizarMiniResumen();
  }

  function obtenerResponsableYCargarMensualidades(
    id_estudiante,
    id_schoolYear,
  ) {
    $.ajax({
      url: config.urlGrupoFamiliar,
      type: "GET",
      data: { id_estudiante },
      dataType: "json",
      success: function (response) {
        if (response.status !== "success" || !response.id_responsable) {
          $("#estudiantes-mensualidades-list").html(
            `<div class="alert alert-warning">No se encontró responsable vinculado.</div>`,
          );
          return;
        }

        $("#id_responsable").val(response.id_responsable);
        cargarMensualidadesPendientes(response.id_responsable, id_schoolYear);
      },
      error: function (xhr) {
        console.error(xhr.responseText);
        Swal.fire({
          icon: "error",
          title: "Error",
          text: "No se pudo cargar el grupo familiar.",
        });
      },
    });
  }

  function cargarMensualidadesPendientes(id_responsable, id_schoolYear) {
    $.ajax({
      url: config.urlMensualidades,
      type: "GET",
      data: {
        id_responsable,
        id_schoolYear,
      },
      dataType: "json",
      success: function (response) {
        let tabla = $("#estudiantes-mensualidades-list");
        tabla.empty();

        if (response.status === "success" && response.data.length > 0) {
          response.data.forEach((est) => {
            let mesesHTML = "";

            const pagados = (est.meses_pagados || []).map((m) =>
              m.toLowerCase(),
            );

            est.meses_pendientes.forEach((mes) => {
              let nombreMes = mes.nombre.toLowerCase();
              let isPagado = pagados.includes(nombreMes);

              mesesHTML += `
<label class="mes-item ${isPagado ? "mes-pagado" : ""}">
    <input class="mes-checkbox"
        type="checkbox"
        name="meses[${est.id}][]"
        value="${mes.nombre}"
        data-monto="${mes.monto}"
        data-estudiante="${est.id}"
        ${isPagado ? "checked disabled" : ""}>

    <span>${mes.nombre}</span>
    <strong>RD$${mes.monto}</strong>
    ${isPagado ? "<em>Pagado</em>" : ""}
</label>`;
            });

            tabla.append(`
<div class="mensualidad-card">
    <div class="mensualidad-header">
        <div>
            <h6>${est.nombre} ${est.apellido}</h6>
            <span class="mensualidad-curso">${est.curso}</span>
        </div>

        <label class="seleccionar-estudiante">
            <input type="checkbox"
                class="estudiante-checkbox"
                value="${est.id}">
            Seleccionar todo
        </label>
    </div>

    <div class="meses-grid">
        ${mesesHTML}
    </div>

    <div class="total-estudiante">
        <span>Total por estudiante</span>

        <div class="total-estudiante-valor">
            RD$
            <input type="text"
                id="total-${est.id}"
                value="0.00"
                readonly>
        </div>
    </div>
</div>`);
          });
        } else {
          tabla.append(
            `<div class="alert alert-info">No hay mensualidades pendientes.</div>`,
          );
        }

        actualizarMiniResumen();
      },
    });
  }

  $(document).on("change", ".mes-checkbox", function () {
    let estudianteId = $(this).data("estudiante");

    let algunoMarcado =
      $(`.mes-checkbox[data-estudiante="${estudianteId}"]:checked`).length > 0;

    $(`.estudiante-checkbox[value="${estudianteId}"]`).prop(
      "checked",
      algunoMarcado,
    );

    actualizarMontos();
  });

  $(document).on("change", ".estudiante-checkbox", function () {
    let estudianteId = $(this).val();
    let checked = $(this).prop("checked");

    $(`.mes-checkbox[data-estudiante="${estudianteId}"]`)
      .not(":disabled")
      .prop("checked", checked);

    actualizarMontos();
  });

  function actualizarMontos() {
    let totalGeneral = 0;
    let estudiantes = new Set();

    $(".mes-checkbox").each(function () {
      estudiantes.add($(this).data("estudiante"));
    });

    estudiantes.forEach((estudianteId) => {
      let totalEst = 0;

      $(`.mes-checkbox[data-estudiante="${estudianteId}"]:checked`).each(
        function () {
          if (!$(this).is(":disabled")) {
            totalEst += parseFloat($(this).data("monto")) || 0;
          }
        },
      );

      $(`#total-${estudianteId}`).val(totalEst.toFixed(2));
      totalGeneral += totalEst;
    });

    $("#total_pago").val(totalGeneral.toFixed(2));
    actualizarMiniResumen();
  }

  $("form").on("submit", function (e) {
    let valido = false;

    $(".estudiante-checkbox:checked").each(function () {
      let id = $(this).val();

      if (
        $(`.mes-checkbox[data-estudiante="${id}"]:checked:not(:disabled)`)
          .length > 0
      ) {
        valido = true;
      }
    });

    if (!valido) {
      e.preventDefault();
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Debe seleccionar al menos un mes para un estudiante.",
      });
    }
  });
});
