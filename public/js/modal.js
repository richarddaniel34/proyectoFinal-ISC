/*
COdigo para cargar los modales y alertas
*/

// modal.js

$(document).ready(function () {
  //  Verificar si hay errores en la sesión y mantener el modal abierto
  let errorFlag = $("#error_modal_flag").val(); // Captura el ID de la escuela con error

  if (errorFlag) {
    console.log("Reabriendo modal con ID: " + errorFlag);
    editarEscuela(errorFlag); // Llamar a la función que abre el modal
  }
});

function editarAsignatura(id) {
  // Eliminar cualquier modal previo para evitar duplicados
  $("#edit-asig-modal").remove();
  // Agregar el modal dinámicamente al body
  $("body").append(`
        <div class="modal fade" id="edit-asig-modal" tabindex="-1" role="dialog" aria-labelledby="edit-asig-modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-asig-modalLabel">Editar Datos de la Escuela</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="edit-asig-modal-body">
                        <!-- Aquí se cargará el formulario de edición vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

  // Llamada Ajax para cargar el contenido del modal
  $.ajax({
    url: baseUrl + "/asignatura/editar/" + id,
    type: "GET",
    success: function (response) {
      $("#edit-asig-modal-body").html(response);
      $("#edit-asig-modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al obtener los datos:", textStatus, errorThrown);
      alert("Error al obtener los datos de la escuela");
    },
  });
}

function editarYear(id) {
  // Eliminar cualquier modal previo para evitar duplicados
  $("#edit-year-modal").remove();
  // Agregar el modal dinámicamente al body
  $("body").append(`
        <div class="modal fade" id="edit-year-modal" tabindex="-1" role="dialog" aria-labelledby="edit-year-modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-year-modallLabel">Editar Datos de la Escuela</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="edit-year-modal-body">
                        <!-- Aquí se cargará el formulario de edición vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

  // Llamada Ajax para cargar el contenido del modal
  $.ajax({
    url: baseUrl + "/schoolyear/editar/" + id,
    type: "GET",
    success: function (response) {
      $("#edit-year-modal-body").html(response);
      $("#edit-year-modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al obtener los datos:", textStatus, errorThrown);
      alert("Error al obtener los datos");
    },
  });
}

function editarDocenteGuia(id) {
  // Eliminar modal previo
  $("#edit-docente-modal").remove();

  // Crear modal dinámico
  $("body").append(`
        <div class="modal fade" id="edit-docente-modal" tabindex="-1" role="dialog" aria-labelledby="edit-docente-modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-docente-modalLabel">Editar Asignación de Docente Guía</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="edit-docente-modal-body">
                        <!-- Aquí se cargará el formulario vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

  // AJAX para cargar el formulario
  $.ajax({
    url: baseUrl + "/docenteguia/editar/" + id,
    type: "GET",
    success: function (response) {
      $("#edit-docente-modal-body").html(response);

      // Inicializar select2 en el select recién cargado con dropdownParent
      const $select = $("#edit_docente");
      $select.select2({
        placeholder: "-- Seleccione un docente --",
        allowClear: true,
        width: "100%",
        dropdownParent: $("#edit-docente-modal"), // <- importante para que no quede detrás
      });

      const $container = $select.next(".select2-container");

      // Estilos del contenedor principal
      $container.addClass("form-control");
      $container.css({
        "background-color": "white",
        border: "none",
        "border-radius": "0",
        "box-shadow": "none",
        height: "36px",
      });

      // Estilos de la selección
      $container.find(".select2-selection").css({
        "background-color": "white",
        border: "none",
        "border-bottom": "1px solid #d2d2d2",
        "border-radius": "0",
        "box-shadow": "none",
        height: "36px",
        "line-height": "36px",
        padding: "0 10px",
      });

      // Efecto focus opcional
      $container.find(".select2-selection").on("focus", function () {
        $(this).css("border-bottom", "2px solid #0b1065");
      });

      // Mostrar el modal finalmente
      $("#edit-docente-modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al obtener los datos:", textStatus, errorThrown);
      alert("Error al obtener los datos");
    },
  });
}
