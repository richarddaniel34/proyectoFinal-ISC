/*
COdigo para cargar los modales y alertas
*/

// modal.js

function editarEscuela(id) {
    // Eliminar cualquier modal previo para evitar duplicados
    $('#editarModal').remove();
    
    // Agregar el modal dinámicamente al body
    $('body').append(`
        <div class="modal fade" id="editarModal" tabindex="-1" role="dialog" aria-labelledby="editarModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarModalLabel">Editar Datos de la Escuela</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">
                        <!-- Aquí se cargará el formulario de edición vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

    // Llamada AJAX para cargar el contenido del modal
    $.ajax({
        url: baseUrl + '/escuela/editar/' + id,
        type: "GET",
        success: function(response) {
            $('#modal-body').html(response);
            $('#editarModal').modal('show');

            // 🔥 Reabrir el modal si hay errores almacenados en la sesión
            if ($('#modal-body').find('#modal_error_flag').length > 0) {
                $('#editarModal').modal('show');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener los datos:", textStatus, errorThrown);
            alert("Error al obtener los datos de la escuela");
        }
    });
}

$(document).ready(function() {
    // 🔥 Verificar si hay errores en la sesión y mantener el modal abierto
    let errorFlag = $('#error_modal_flag').val(); // Captura el ID de la escuela con error

    if (errorFlag) {
        console.log("Reabriendo modal con ID: " + errorFlag);
        editarEscuela(errorFlag); // Llamar a la función que abre el modal
    }
});





function visualizarEscuela(id) {
    // Eliminar cualquier modal previo para evitar duplicados
    $('#visualizarModal').remove();
    // Agregar el modal dinámicamente al body
    $('body').append(`
        <div class="modal fade" id="visualizarModal" tabindex="-1" role="dialog" aria-labelledby="visualizarModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visualizarModalLabel"> Datos de la Escuela</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="visualizar-modal-body">
                        <!-- Aquí se cargará el formulario de edición vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

    // Llamada Ajax para cargar el contenido del modal
    $.ajax({
        url: baseUrl + '/escuela/visualizar/' + id,
        type: "GET",
        success: function(response) {
            $('#visualizar-modal-body').html(response);
            $('#visualizarModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener los datos:", textStatus, errorThrown);
            alert("Error al obtener los datos de la escuela");
        }
    });
}



function editarAsignatura(id) {
    // Eliminar cualquier modal previo para evitar duplicados
    $('#edit-asig-modal').remove();
    // Agregar el modal dinámicamente al body
    $('body').append(`
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
        url: baseUrl + '/asignatura/editar/' + id,
        type: "GET",
        success: function(response) {
            $('#edit-asig-modal-body').html(response);
            $('#edit-asig-modal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener los datos:", textStatus, errorThrown);
            alert("Error al obtener los datos de la escuela");
        }
    });
}


function visualizarPersonal(id) {
    // Eliminar cualquier modal previo para evitar duplicados
    $('#visualizarModal').remove();
    // Agregar el modal dinámicamente al body
    $('body').append(`
        <div class="modal fade" id="visualizarModal" tabindex="-1" role="dialog" aria-labelledby="visualizarModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visualizarModalLabel"> Datos de la Escuela</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="visualizar-modal-body">
                        <!-- Aquí se cargará el formulario de edición vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

    // Llamada Ajax para cargar el contenido del modal
    $.ajax({
        url: baseUrl + '/personal/visualizar/' + id,
        type: "GET",
        success: function(response) {
            $('#visualizar-modal-body').html(response);
            $('#visualizarModal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener los datos:", textStatus, errorThrown);
            alert("Error al obtener los datos de la escuela");
        }
    });
}


function editarPersonal(id) {
    // Eliminar cualquier modal previo para evitar duplicados
    $('#edit-personal-modal').remove();
    // Agregar el modal dinámicamente al body
    $('body').append(`
        <div class="modal fade" id="edit-personal-modal" tabindex="-1" role="dialog" aria-labelledby="edit-personal-modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edit-personal-modallLabel">Editar Datos de la Escuela</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="edit-personal-modal-body">
                        <!-- Aquí se cargará el formulario de edición vía AJAX -->
                    </div>
                </div>
            </div>
        </div>
    `);

    // Llamada Ajax para cargar el contenido del modal
    $.ajax({
        url: baseUrl + '/personal/editar/' + id,
        type: "GET",
        success: function(response) {
            $('#edit-personal-modal-body').html(response);
            $('#edit-personal-modal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener los datos:", textStatus, errorThrown);
            alert("Error al obtener los datos del personal");
        }
    });
}



function editarYear(id) {
    // Eliminar cualquier modal previo para evitar duplicados
    $('#edit-year-modal').remove();
    // Agregar el modal dinámicamente al body
    $('body').append(`
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
        url: baseUrl + '/schoolyear/editar/' + id,
        type: "GET",
        success: function(response) {
            $('#edit-year-modal-body').html(response);
            $('#edit-year-modal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener los datos:", textStatus, errorThrown);
            alert("Error al obtener los datos");
        }
    });
}