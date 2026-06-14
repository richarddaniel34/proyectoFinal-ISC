function iniciarSelect2(selector, placeholder = "-- Seleccione --") {
  if (!$(selector).length) return;

  $(selector).select2({
    placeholder: placeholder,
    allowClear: true,
    width: "100%",
  });
}

function iniciarSelect2Ajax(selector, url, placeholder = "-- Seleccione --") {
  if (!$(selector).length) return;

  $(selector).select2({
    placeholder: placeholder,
    allowClear: true,
    width: "100%",
    minimumInputLength: 0,
    ajax: {
      url: url,
      dataType: "json",
      delay: 250,
      data: function (params) {
        return {
          q: params.term || "",
          page: params.page || 1,
        };
      },
      processResults: function (data) {
        return {
          results: (data.items || []).map(function (item) {
            item.text = item.text || item.nombre || item.nombre_completo;
            return item;
          }),
          pagination: {
            more: data.more || false,
          },
        };
      },
      cache: true,
    },
  });
}
