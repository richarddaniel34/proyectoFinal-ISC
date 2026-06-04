/**
 * Gestiona las validaciones, formateo e inicialización
 * de los formularios del sistema.
 *
 * Incluye:
 * - Validaciones de campos
 * - Formateo automático de datos
 * - Manejo de errores visuales
 * - Configuración de eventos y listeners
 * - Control de navegación entre pestañas
 *
 * Compatible con formularios de:
 * - Estudiantes
 * - Responsables
 * - Personal
 * - Otros módulos futuros
 *
 * @returns {boolean} Retorna true si todos los campos son válidos, de lo contrario false.
 */

// =============================== UTILIDADES ===============================
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

function limpiarErroresTab(tab) {
  if (!tab) return;

  const campos = tab.querySelectorAll("input, select, textarea");

  campos.forEach((campo) => limpiarError(campo));
}

function enfocarPrimerError(tab) {
  if (!tab) return;

  const primerError = tab.querySelector(".is-invalid");

  if (primerError) {
    primerError.focus();
  }
}

function normalizarCamposTexto(ids) {
  ids.forEach((id) => {
    const campo = obtenerCampo(id);

    if (campo) {
      campo.value = limpiarTexto(campo.value || "");
    }
  });
}

function obtenerCampo(id) {
  return document.getElementById(id);
}

function limpiarTexto(valor) {
  return valor.trim().replace(/\s+/g, " ");
}
// ==========================================================================

// =============================== FORMATEADORES ===============================

// FORMATO DE CEDULAS Y NUMERO UNICO DE IDENTIDADA (NUI)
function formatearCedula(valor) {
  if (valor.length <= 3) return valor;
  if (valor.length <= 10) return valor.replace(/(\d{3})(\d+)/, "$1-$2");
  return valor.replace(/(\d{3})(\d{7})(\d{1})/, "$1-$2-$3");
}

function formatearTelefono(valor) {
  valor = valor.replace(/\D/g, "").slice(0, 10);

  if (valor.length <= 3) {
    return valor;
  }

  if (valor.length <= 6) {
    return valor.replace(/(\d{3})(\d+)/, "$1-$2");
  }

  return valor.replace(/(\d{3})(\d{3})(\d+)/, "$1-$2-$3");
}
// =============================================================================

// =============================== CONFIGURADORES ===============================
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

function configurarCampoSoloNumeros(idCampo, mensaje) {
  const campo = obtenerCampo(idCampo);

  if (!campo) return;

  campo.addEventListener("input", function () {
    const valorOriginal = this.value;

    const valorLimpio = valorOriginal.replace(/\D/g, "");

    if (valorOriginal !== valorLimpio) {
      this.value = valorLimpio;

      mostrarError(this, mensaje);
    } else {
      limpiarError(this);
    }
  });
}

function configurarCamposTelefono(campos) {
  campos.forEach(({ id, nombre }) => {
    const campo = obtenerCampo(id);

    if (!campo) return;

    campo.addEventListener("input", function () {
      const valorNumerico = this.value.replace(/\D/g, "");

      this.value = formatearTelefono(valorNumerico);

      if (valorNumerico.length > 0 && valorNumerico.length < 10) {
        mostrarError(this, `El campo ${nombre} debe tener 10 dígitos.`);
      } else {
        limpiarError(this);
      }
    });
  });
}

function configurarDocumentoIdentidad(campos) {
  campos.forEach(({ documento, nacionalidad }) => {
    const campoDocumento = obtenerCampo(documento);

    const campoNacionalidad = obtenerCampo(nacionalidad);

    if (!campoDocumento || !campoNacionalidad) return;

    campoDocumento.addEventListener("input", function () {
      if (campoNacionalidad.value === "1") {
        let valor = this.value.replace(/\D/g, "").slice(0, 11);

        this.value = formatearCedula(valor);

        if (valor.length > 0 && valor.length < 11) {
          mostrarError(this, "La cédula debe tener 11 dígitos.");
        } else {
          limpiarError(this);
        }
      } else {
        limpiarError(this);
      }
    });

    campoNacionalidad.addEventListener("change", function () {
      campoDocumento.value = "";

      limpiarError(campoDocumento);
    });
  });
}
// ==============================================================================

// =============================== VALIDADORES GENERALES ===============================

function validarDocumentoIdentidad(
  idCampoDocumento,
  idCampoNacionalidad,
  nombreCampo = "Documento de identidad",
) {
  const campo = obtenerCampo(idCampoDocumento);
  const nacionalidad = obtenerCampo(idCampoNacionalidad);

  if (!campo || !nacionalidad) return true;

  let valor = campo.value.trim();
  const esDominicano = nacionalidad.value === "1";

  // DOMINICANO = CÉDULA
  if (esDominicano) {
    valor = valor.replace(/\D/g, "");
    campo.value = formatearCedula(valor);

    if (valor === "") {
      limpiarError(campo);
      return true;
    }

    if (!/^\d{11}$/.test(valor)) {
      mostrarError(campo, `${nombreCampo} debe tener 11 dígitos.`);
      return false;
    }

    limpiarError(campo);
    return true;
  }

  // EXTRANJERO = FORMATO LIBRE
  if (valor === "") {
    limpiarError(campo);
    return true;
  }

  const regex = /^[A-Za-z0-9\-]{5,20}$/;

  if (!regex.test(valor)) {
    mostrarError(campo, `${nombreCampo} tiene un formato inválido.`);
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
    return false;
  }

  limpiarError(campo);
  return true;
}

function validarRequerido(idCampo, nombreCampo) {
  const campo = obtenerCampo(idCampo);
  if (!campo) return true;

  const valor = limpiarTexto(campo.value || "");

  if (valor === "") {
    mostrarError(campo, `El campo ${nombreCampo} es obligatorio.`);
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
    return false;
  }

  limpiarError(campo);
  campo.value = valor;
  return true;
}

function validarSoloNumeros(idCampo, nombreCampo) {
  const campo = obtenerCampo(idCampo);

  if (!campo) return true;

  const valor = limpiarTexto(campo.value || "");

  if (valor === "") return true;

  if (!/^\d+$/.test(valor)) {
    mostrarError(campo, `El campo ${nombreCampo} solo debe contener números.`);

    return false;
  }

  limpiarError(campo);

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
// =====================================================================================

// =============================== VALIDADORES DE ESTUDIANTES ===============================

function validarPestanaDatosBasicos() {
  const tabDatosBasicos = document.getElementById("datos-basicos");

  let formularioValido = true;

  limpiarErroresTab(tabDatosBasicos);

  normalizarCamposTexto([
    "nombre",
    "apellido",
    "lugar_nacimiento",
    "provincia",
    "direccion",
  ]);

  if (!validarSelect("id_grado", "Grado al que Ingresa")) {
    formularioValido = false;
  }

  if (!validarRequerido("nombre", "Nombre(s)")) {
    formularioValido = false;
  }

  if (!validarRequerido("apellido", "Apellido(s)")) {
    formularioValido = false;
  }

  if (!validarSelect("sexo", "Sexo")) {
    formularioValido = false;
  }

  if (!validarRequerido("fecha_nac", "Fecha de Nacimiento")) {
    formularioValido = false;
  }

  if (!validarRequerido("lugar_nacimiento", "Lugar de Nacimiento")) {
    formularioValido = false;
  }

  if (!validarRequerido("provincia", "Provincia")) {
    formularioValido = false;
  }

  if (!validarSelect("id_nacionalidad", "Nacionalidad")) {
    formularioValido = false;
  }

  if (!validarRequerido("direccion", "Dirección")) {
    formularioValido = false;
  }

  if (!validarSoloLetras("nombre", "Nombre(s)")) {
    formularioValido = false;
  }

  if (!validarSoloLetras("apellido", "Apellido(s)")) {
    formularioValido = false;
  }

  if (!validarSoloLetras("lugar_nacimiento", "Lugar de Nacimiento")) {
    formularioValido = false;
  }

  if (!validarSoloLetras("provincia", "Provincia")) {
    formularioValido = false;
  }

  if (!validarSoloNumeros("sigerd_id", "ID SIGERD")) {
    formularioValido = false;
  }

  if (!validarFechaNacimiento("fecha_nac")) {
    formularioValido = false;
  }

  if (!formularioValido) {
    enfocarPrimerError(tabDatosBasicos);

    return false;
  }

  return true;
}

function validarFormularioCompleto() {
  const basicosValidos = validarPestanaDatosBasicos();
  const responsablesValidos = validarPestanaResponsables();
  const saludValida = validarPestanaSalud();

  if (!basicosValidos) {
    siguiente("datos-basicos");
    return false;
  }

  if (!responsablesValidos) {
    siguiente("responsable");
    return false;
  }
  /*
  if (!saludValida) {
    siguiente("salud");
    return false;
  }*/

  return true;
}

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

  /*
  function limpiarTexto(valor) {
    return valor.trim().replace(/\s+/g, " ");
  }*/

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

// ==========================================================================================

// =============================== VALIDADORES DE RESPONSABLES ===============================
function validarRegistroPadres() {
  let formularioValido = true;

  if (
    !validarDocumentoIdentidad(
      "cedula_padre",
      "id_nacionalidad_padre",
      "Documento del padre",
    )
  ) {
    formularioValido = false;
  }

  if (
    !validarDocumentoIdentidad(
      "cedula_madre",
      "id_nacionalidad_madre",
      "Documento de la madre",
    )
  ) {
    formularioValido = false;
  }

  if (
    !validarDocumentoIdentidad(
      "cedula_tutor",
      "id_nacionalidad_tutor",
      "Documento del tutor",
    )
  ) {
    formularioValido = false;
  }

  return formularioValido;
}
// ===========================================================================================

// =============================== VALIDADORES DE PERSONAL ===============================
// ===========================================================================================

// =============================== INICIALIZACIÓN ===============================
document.addEventListener("DOMContentLoaded", function () {
  const tabResponsables = document.getElementById("responsables-tab");
  configurarCampoSoloNumeros("sigerd_id", "El ID SIGERD solo admite números.");

  configurarCamposSoloTexto([
    { id: "nombre", nombre: "Nombre(s)" },
    { id: "apellido", nombre: "Apellido(s)" },
    { id: "lugar_nacimiento", nombre: "Lugar de Nacimiento" },
    { id: "provincia", nombre: "Provincia" },

    //Padre
    { id: "nombre_padre", nombre: "nombre del padre" },
    { id: "apellido_padre", nombre: "apellido del padre" },

    //Madre
    { id: "nombre_madre", nombre: "nombre del madre" },
    { id: "apellido_madre", nombre: "apellido del madre" },

    //Tutor
    { id: "nombre_tutor", nombre: "nombre del tutor" },
    { id: "apellido_tutor", nombre: "apellido del tutor" },
  ]);

  configurarDocumentoIdentidad([
    {
      documento: "numero_identidad",
      nacionalidad: "id_nacionalidad",
    },

    {
      documento: "cedula_padre",
      nacionalidad: "id_nacionalidad_padre",
    },

    {
      documento: "cedula_madre",
      nacionalidad: "id_nacionalidad_madre",
    },

    {
      documento: "cedula_tutor",
      nacionalidad: "id_nacionalidad_tutor",
    },
  ]);

  $("#responsables-tab").on("show.bs.tab", function (e) {
    if (!validarPestanaDatosBasicos()) {
      e.preventDefault();

      return false;
    }
  });
  $("#salud-tab").on("show.bs.tab", function (e) {
    if (!validarPestanaDatosBasicos()) {
      e.preventDefault();

      return false;
    }

    if (!validarPestanaResponsables()) {
      e.preventDefault();

      return false;
    }
  });
});

// ==============================================================================
