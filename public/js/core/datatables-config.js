const idiomaDataTable = {
  search: "Buscar:",
  lengthMenu: "Mostrar _MENU_ registros",
  info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
  infoEmpty: "Mostrando 0 a 0 de 0 registros",
  infoFiltered: "(filtrado de _MAX_ registros totales)",
  zeroRecords: "No se encontraron registros",
  emptyTable: "No hay datos disponibles",
  loadingRecords: "Cargando...",
  processing: "Procesando...",
  paginate: {
    first: "Primero",
    last: "Último",
    next: "Siguiente",
    previous: "Anterior",
  },
};

function iniciarTablaBasica(selector) {
  if (!$(selector).length) return;

  $(selector).DataTable({
    responsive: true,
    autoWidth: false,
    language: idiomaDataTable,
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50, 100],
  });
}

function iniciarTablaExport(selector, tituloArchivo = "Reporte") {
  if (!$(selector).length) return;

  $(selector).DataTable({
    responsive: true,
    autoWidth: false,
    language: idiomaDataTable,
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50, 100],
    dom:
      "<'row mb-3'<'col-md-6'l><'col-md-6 text-md-right'f>>" +
      "<'row mb-3'<'col-md-12'B>>" +
      "<'row'<'col-md-12'tr>>" +
      "<'row mt-3'<'col-md-6'i><'col-md-6'p>>",
    buttons: [
      {
        extend: "excelHtml5",
        text: '<i class="fa-solid fa-file-excel"></i> Excel',
        className: "btn btn-success btn-sm",
        title: tituloArchivo,
      },
      {
        extend: "pdfHtml5",
        text: '<i class="fa-solid fa-file-pdf"></i> PDF',
        className: "btn btn-danger btn-sm",
        title: tituloArchivo,
        orientation: "landscape",
        pageSize: "A4",
      },
      {
        extend: "print",
        text: '<i class="fa-solid fa-print"></i> Imprimir',
        className: "btn btn-secondary btn-sm",
        title: tituloArchivo,
      },
    ],
  });
}

$(document).ready(function () {
  $(".tabla-basica").each(function () {
    iniciarTablaBasica(this);
  });

  $(".tabla-export").each(function () {
    const titulo = $(this).data("titulo") || "Reporte";
    iniciarTablaExport(this, titulo);
  });
});
