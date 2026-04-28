/**
 * Valida el formato y la integridad de los campos del formulario
 * de registro de estudiantes antes de su envío.
 *
 * @returns {boolean} Retorna true si todos los campos son válidos, de lo contrario false.
 */

function limpiarTexto(valor) {
  return valor.trim().replace(/\s+/g, " ");
}

function obtenerCampo(id) {
  return document.getElementById(id);
}

function obtenerContenedorError(campo) {
  const formGroup = campo.closest(".form-group");
  return formGroup ? formGroup.querySelector(".error-message") : null;
}

function mostrarError(campo, mensaje) {
  campo.classList.add("is-invalid");

  const errorDiv = obtenerContenedorError(campo);
  if (errorDiv) {
    errorDiv.textContent = mensaje;
  }
}

function limpiarError(campo) {
  campo.classList.remove("is-invalid");

  const errorDiv = obtenerContenedorError(campo);
  if (errorDiv) {
    errorDiv.textContent = "";
  }
}

function formatearCedula(valor) {
  if (valor.length <= 3) return valor;
  if (valor.length <= 10) return valor.replace(/(\d{3})(\d+)/, "$1-$2");
  return valor.replace(/(\d{3})(\d{7})(\d{1})/, "$1-$2-$3");
}

function configurarCamposSoloTexto(campos) {
  campos.forEach(({ id, nombre }) => {
    const campo = document.getElementById(id);
    if (!campo) return;

    campo.addEventListener("input", function () {
      const valorOriginal = this.value;
      const valorLimpio = valorOriginal.replace(
        /[^A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]/g,
        "",
      );

      if (valorOriginal !== valorLimpio) {
        this.value = valorLimpio;
        mostrarError(this, `El campo ${nombre} solo admite letras.`);
      } else {
        if (this.value.trim() !== "") {
          limpiarError(this);
        }
      }
    });
  });
}

function validarRegistroEstudiantes() {
  const tabBasicos = document.getElementById("basicos");
  let formularioValido = true;

  function limpiarErroresTab() {
    const campos = tabBasicos.querySelectorAll("input, select, textarea");
    campos.forEach((campo) => limpiarError(campo));
  }

  function validarRequerido(idCampo, nombreCampo) {
    const campo = obtenerCampo(idCampo);
    if (!campo) return true;

    const valor = limpiarTexto(campo.value || "");

    if (valor === "") {
      mostrarError(campo, `El campo ${nombreCampo} es obligatorio.`);
      formularioValido = false;
      return false;
    }

    limpiarError(campo);
    return true;
  }

  function validarSelect(idCampo, nombreCampo) {
    const campo = obtenerCampo(idCampo);
    if (!campo) return true;

    const valor = campo.value;

    if (!valor || valor === "") {
      mostrarError(campo, `Debe seleccionar una opción en ${nombreCampo}.`);
      formularioValido = false;
      return false;
    }

    limpiarError(campo);
    return true;
  }

  function validarSoloLetras(idCampo, nombreCampo) {
    const campo = obtenerCampo(idCampo);
    if (!campo) return true;

    const valor = limpiarTexto(campo.value || "");
    if (valor === "") return true;

    const regex = /^[A-Za-zÁÉÍÓÚáéíóúÜüÑñ\s]+$/;

    if (!regex.test(valor)) {
      mostrarError(campo, `El campo ${nombreCampo} solo debe contener letras.`);
      formularioValido = false;
      return false;
    }

    limpiarError(campo);
    campo.value = valor;
    return true;
  }

  function validarFechaNacimiento(idCampo) {
    const campo = obtenerCampo(idCampo);
    if (!campo) return true;

    const valor = campo.value;
    if (!valor) return true;

    const fechaIngresada = new Date(valor + "T00:00:00");
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    if (fechaIngresada > hoy) {
      mostrarError(campo, "La fecha de nacimiento no puede ser futura.");
      formularioValido = false;
      return false;
    }

    limpiarError(campo);
    return true;
  }

  function validarNUI(idCampo, idNacionalidad) {
    const campo = document.getElementById(idCampo);
    const nacionalidad = document.getElementById(idNacionalidad);

    if (!campo || !nacionalidad) return true;

    let valor = campo.value.trim();
    const esDominicano = nacionalidad.value === "1";

    if (esDominicano) {
      valor = valor.replace(/\D/g, "");
      campo.value = formatearCedula(valor);

      if (valor === "") {
        mostrarError(
          campo,
          "La cédula es obligatoria para nacionalidad dominicana.",
        );
        formularioValido = false;
        return false;
      }

      if (!/^\d{11}$/.test(valor)) {
        mostrarError(campo, "La cédula debe tener 11 dígitos.");
        formularioValido = false;
        return false;
      }

      limpiarError(campo);
      return true;
    } else {
      if (valor === "") {
        limpiarError(campo);
        return true;
      }

      const regex = /^[A-Za-z0-9\-]{5,20}$/;

      if (!regex.test(valor)) {
        mostrarError(campo, "Formato de pasaporte inválido.");
        formularioValido = false;
        return false;
      }

      limpiarError(campo);
      return true;
    }
  }

  function normalizarCamposTexto() {
    const ids = [
      "nombre",
      "apellido",
      "lugar_nacimiento",
      "provincia",
      "direccion",
    ];

    ids.forEach((id) => {
      const campo = obtenerCampo(id);
      if (campo) {
        campo.value = limpiarTexto(campo.value || "");
      }
    });
  }

  limpiarErroresTab();
  normalizarCamposTexto();

  validarSelect("id_grado", "Grado al que Ingresa");
  validarRequerido("nombre", "Nombre(s)");
  validarRequerido("apellido", "Apellido(s)");
  validarSelect("sexo", "Sexo");
  validarRequerido("fecha_nac", "Fecha de Nacimiento");
  validarRequerido("lugar_nacimiento", "Lugar de Nacimiento");
  validarRequerido("provincia", "Provincia");
  validarSelect("id_nacionalidad", "Nacionalidad");
  validarRequerido("direccion", "Dirección");

  // NUI obligatorio solo si es dominicano
  validarNUI("numero_identidad", "id_nacionalidad");

  validarSoloLetras("nombre", "Nombre(s)");
  validarSoloLetras("apellido", "Apellido(s)");
  validarSoloLetras("lugar_nacimiento", "Lugar de Nacimiento");
  validarSoloLetras("provincia", "Provincia");
  validarFechaNacimiento("fecha_nac");

  if (!formularioValido) {
    const primerError = tabBasicos.querySelector(".is-invalid");
    if (primerError) {
      primerError.focus();
    }
    return false;
  }

  return true;
}

document.addEventListener("DOMContentLoaded", function () {
  const tabResponsables = document.getElementById("responsables-tab");
  const tabSalud = document.getElementById("datos-medicos-tab");

  const nui = document.getElementById("numero_identidad");
  const nacionalidad = document.getElementById("id_nacionalidad");

  if (tabResponsables) {
    tabResponsables.addEventListener("click", function (e) {
      if (!validarRegistroEstudiantes()) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  }

  if (tabSalud) {
    tabSalud.addEventListener("click", function (e) {
      if (!validarRegistroEstudiantes()) {
        e.preventDefault();
        e.stopPropagation();
        return;
      }

      if (!validarPestanaResponsables()) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  }

  configurarCamposSoloTexto([
    { id: "nombre", nombre: "Nombre(s)" },
    { id: "apellido", nombre: "Apellido(s)" },
    { id: "lugar_nacimiento", nombre: "Lugar de Nacimiento" },
    { id: "provincia", nombre: "Provincia" },
  ]);

  if (nui) {
    nui.addEventListener("input", function () {
      if (nacionalidad && nacionalidad.value === "1") {
        let valor = this.value.replace(/\D/g, "").slice(0, 11);
        this.value = formatearCedula(valor);
      }
    });
  }

  if (nacionalidad && nui) {
    nacionalidad.addEventListener("change", function () {
      nui.value = "";
      limpiarError(nui);
    });
  }
});

function validarPestanaResponsables() {
  const tabResponsable = document.getElementById("responsable");
  let esValida = true;

  const padre = document.getElementById("padre");
  const madre = document.getElementById("madre");
  const tutor = document.getElementById("tutor");

  const parentescoPadre = document.querySelector('[name="parentesco_padre"]');
  const parentescoMadre = document.querySelector('[name="parentesco_madre"]');
  const parentescoTutor = document.querySelector('[name="parentesco_tutor"]');

  function limpiarErroresTab() {
    const campos = tabResponsable.querySelectorAll("input, select, textarea");
    campos.forEach((campo) => limpiarError(campo));
  }

  function validarSelectRelacionado(
    selectPersona,
    selectParentesco,
    nombrePersona,
  ) {
    const tienePersona = selectPersona && selectPersona.value.trim() !== "";
    const tieneParentesco =
      selectParentesco && selectParentesco.value.trim() !== "";

    if (tienePersona && !tieneParentesco) {
      mostrarError(
        selectParentesco,
        `Debe seleccionar el parentesco de ${nombrePersona}.`,
      );
      esValida = false;
      return false;
    }

    if (!tienePersona && tieneParentesco) {
      mostrarError(
        selectPersona,
        `Debe seleccionar un ${nombrePersona} si indicó parentesco.`,
      );
      esValida = false;
      return false;
    }

    if (tienePersona) limpiarError(selectPersona);
    if (tieneParentesco) limpiarError(selectParentesco);

    return true;
  }

  limpiarErroresTab();

  const hayPadre = padre && padre.value.trim() !== "";
  const hayMadre = madre && madre.value.trim() !== "";
  const hayTutor = tutor && tutor.value.trim() !== "";

  // Regla principal: al menos un responsable registrado
  if (!hayPadre && !hayMadre && !hayTutor) {
    mostrarError(padre, "Debe seleccionar al menos un responsable.");
    mostrarError(madre, "Debe seleccionar al menos un responsable.");
    mostrarError(tutor, "Debe seleccionar al menos un responsable.");
    esValida = false;
  }

  // Por el momento, NO exigir parentesco para padre y madre
  // validarSelectRelacionado(padre, parentescoPadre, "padre");
  // validarSelectRelacionado(madre, parentescoMadre, "madre");

  // Tutor sí requiere parentesco, si fue seleccionado
  validarSelectRelacionado(tutor, parentescoTutor, "tutor");

  if (!esValida) {
    const primerError = tabResponsable.querySelector(".is-invalid");
    if (primerError) {
      primerError.focus();
    }
    return false;
  }

  return true;
}

function validarPestanaSalud() {
  const tabSalud = document.getElementById("datos-medicos-tab");
  let esValida = true;

  const tipoSangre = document.getElementById("tipo_sangre");
  const alergias = document.getElementById("alergias");
  const condicionMedica = document.getElementById("condicion_medica");
  const medicamentos = document.getElementById("medicamentos");

  function limpiarTexto(valor) {
    return valor.trim().replace(/\s+/g, " ");
  }

  function limpiarErroresTab() {
    const campos = tabSalud.querySelectorAll("input, select, textarea");
    campos.forEach((campo) => limpiarError(campo));
  }

  limpiarErroresTab();

  // Normalizar textos
  if (alergias) alergias.value = limpiarTexto(alergias.value || "");
  if (condicionMedica)
    condicionMedica.value = limpiarTexto(condicionMedica.value || "");
  if (medicamentos) medicamentos.value = limpiarTexto(medicamentos.value || "");

  // Si luego quieres hacerlo obligatorio, aquí iría:
  // if (tipoSangre && tipoSangre.value.trim() === "") {
  //     mostrarError(tipoSangre, "Debe seleccionar el tipo de sangre.");
  //     esValida = false;
  // }

  if (!esValida) {
    const primerError = tabSalud.querySelector(".is-invalid");
    if (primerError) {
      primerError.focus();
    }
    return false;
  }

  return true;
}

function validarFormularioCompleto() {
  const basicosValidos = validarRegistroEstudiantes();
  const responsablesValidos = validarPestanaResponsables();
  const saludValida = validarPestanaSalud();

  if (!basicosValidos) {
    siguiente("basicos");
    return false;
  }

  if (!responsablesValidos) {
    siguiente("responsable");
    return false;
  }

  if (!saludValida) {
    siguiente("salud");
    return false;
  }

  return true;
}

/**
 * Valida el formato y la integridad de los campos del formulario
 * de edicion de estudiantes antes de su envio
 */
function validarEdicionEstudiantes() {}

function validarRegistroPadres() {}
function validarRegistroPersonal() {}
