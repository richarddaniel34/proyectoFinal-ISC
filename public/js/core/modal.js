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

/**
 * /////////////////////////////////////////////////////////////////////////////
 * ================ VENTANAS MODALES DEL MODULO DE ASIGNATURAS ================
 * ////////////////////////////////////////////////////////////////////////////
 */

//========> MODAL DE EDICION DE ASIGNATURA

function editarAsignatura(id) {
  // Eliminar cualquier modal previo para evitar duplicados
  $("#edit-asig-modal").remove();
  // Agregar el modal dinámicamente al body
  $("body").append(`
        <div class="modal fade" id="edit-asig-modal" tabindex="-1" role="dialog" aria-labelledby="edit-asig-modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-asig-modalLabel">Editar Datos de la Asignatura</h5>
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
    url: baseUrl + "asignaturas/editar/" + id,
    type: "GET",
    success: function (response) {
      $("#edit-asig-modal-body").html(response);
      $("#edit-asig-modal").modal("show");
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error al obtener los datos:", textStatus, errorThrown);
      alert("Error al obtener los datos de la asignatura");
    },
  });
}

//========> MODAL DE CONFIRMACION DE INACTIVACION

//=======> RESETEAR CONTRASEÑA

function resetearClaveUsuario(id) {
  $("#reset-clave-modal").remove();

  $("body").append(`
    <div class="modal fade" id="reset-clave-modal" tabindex="-1" role="dialog" aria-labelledby="reset-clave-modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document" style="max-width: 500px">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="reset-clave-modalLabel">Restaurar contraseña</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body" id="reset-clave-modal-body">
            <!-- Aquí se cargará el formulario vía AJAX -->
          </div>
        </div>
      </div>
    </div>
  `);

  $.ajax({
    url: baseUrl + "usuarios/modalResetearClave/" + id,
    type: "GET",
    success: function (response) {
      $("#reset-clave-modal-body").html(response);
      $("#reset-clave-modal").modal("show");
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

//===========================================================================

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

/**
 * ///////////////////////////////////////////////////////////////////////////
 * ///////////////////////////////////////////////////////////////////////////
 *
 * ================== VENTANA MODAL PARA INCATIVAR GRADOS ==================
 */

function confirmarInactivarGrado(id) {
  $("#inactivar-grado-modal").remove();

  $("body").append(`
    <div class="modal fade" id="inactivar-grado-modal" tabindex="-1" role="dialog" aria-labelledby="inactivar-grado-modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="inactivar-grado-modalLabel">CONFIRMAR INACTIVACIÓN</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            ¿Está seguro que desea inactivar este grado?
            Esta accion no afectara a cursos ya registrados
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <b> CANCELAR </b>
            </button>

            <a href="${baseUrl}estructura-academica/grados/inactivarGrado/${id}" class="btn btn-danger">
             <b> SÍ, INACTIVAR </b>
            </a>
          </div>

        </div>
      </div>
    </div>
  `);

  $("#inactivar-grado-modal").modal("show");
}

function confirmarRestaurarGrado(id) {
  $("#restaurar-grado-modal").remove();

  $("body").append(`
    <div class="modal fade" id="restaurar-grado-modal" tabindex="-1" role="dialog" aria-labelledby="restaurar-grado-modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="restaurar-grado-modalLabel">CONFIRMAR RESTAURACIÓN</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            ¿Está seguro que desea restaurar este grado?
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
             <b> CANCELAR </b>
            </button>

            <a href="${baseUrl}estructura-academica/grados/restaurarGrado/${id}" class="btn btn-success">
             <b> SÍ, RESTAURAR </b>
            </a>
          </div>

        </div>
      </div>
    </div>
  `);

  $("#restaurar-grado-modal").modal("show");
}

//===========================================================================

/**
 * MODAL DE CANCELACION DE REGISTRO
 */

function mostrarModalCancelar(urlDestino) {
  $("#modal-cancelar-registro").remove();

  $("body").append(`
    <div class="modal fade" id="modal-cancelar-registro" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

          <div class="modal-header bg-danger text-white justify-content-center position-relative">
            <h5 class="modal-title mb-0">
              <i class="fa-solid fa-triangle-exclamation mr-2"></i>
              CANCELAR REGISTRO
            </h5>

            <button type="button"
                    class="close text-white position-absolute"
                    style="right:15px;"
                    data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>

          <div class="modal-body text-center">
            <i class="fa-solid fa-triangle-exclamation fa-4x text-warning mb-3"></i>

            <p class="mb-1">
              ¿Está seguro que desea cancelar este registro?
            </p>

            <small class="text-muted">
              Los cambios que haya hecho no se guardarán.
            </small>
          </div>

          <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
              <b>CERRAR</b>
            </button>

            <a href="${urlDestino}" class="btn btn-danger">
              <b>SÍ, CANCELAR</b>
            </a>
          </div>

        </div>
      </div>
    </div>
  `);

  $("#modal-cancelar-registro").modal("show");
}

/**
 * MODAL DE INACTIVACION DE REGISTRO
 */

function mostrarModalInactivar({
  titulo = "INACTIVAR REGISTRO",
  mensaje = "¿Está seguro que desea inactivar este registro?",
  url,
  textoBoton = "SÍ, INACTIVAR",
}) {
  $("#modal-inactivar-registro").remove();

  $("body").append(`
        <div class="modal fade" id="modal-inactivar-registro" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-warning text-black justify-content-center position-relative">
                        <h5 class="modal-title mb-0">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                            ${titulo}
                        </h5>

                        <button type="button"
                                class="close text-white position-absolute"
                                style="right:15px;"
                                data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <i class="fa-solid fa-triangle-exclamation fa-4x text-warning mb-3"></i>

                        <p class="mb-1">${mensaje}</p>

                        <small class="text-muted">
                            Esta acción no eliminará el registro, solo lo ocultará de los procesos activos.
                        </small>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                            <b>CERRAR</b>
                        </button>

                        <a href="${url}" class="btn btn-danger">
                            <b>${textoBoton}</b>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    `);

  $("#modal-inactivar-registro").modal("show");
}

function mostrarModalRestaurar({
  titulo = "INACTIVAR REGISTRO",
  mensaje = "¿Está seguro que desea inactivar este registro?",
  url,
  textoBoton = "SÍ, RESTAURAR",
}) {
  $("#modal-inactivar-registro").remove();

  $("body").append(`
        <div class="modal fade" id="modal-inactivar-registro" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-success text-white justify-content-center position-relative">
                        <h5 class="modal-title mb-0">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                            ${titulo}
                        </h5>

                        <button type="button"
                                class="close text-white position-absolute"
                                style="right:15px;"
                                data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <i class="fa-solid fa-triangle-exclamation fa-4x text-warning mb-3"></i>

                        <p class="mb-1">${mensaje}</p>

                        <small class="text-muted">
                            Esta acción no creara un nuevo registro, solo lo mostrara en los procesos activos.
                        </small>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                            <b>CERRAR</b>
                        </button>

                        <a href="${url}" class="btn btn-success">
                            <b>${textoBoton}</b>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    `);

  $("#modal-inactivar-registro").modal("show");
}
