document.addEventListener("DOMContentLoaded", function () {
  console.log("✅ Script de usuario cargado correctamente");

  let nombreInput = document.getElementById("nombre");
  let apellidoInput = document.getElementById("apellido");
  let usuarioInput = document.getElementById("usuario");
  let claveInput = document.getElementById("clave");
  let confirmar_clave = document.getElementById("confirmar_clave");

  if (!nombreInput || !apellidoInput || !usuarioInput || !claveInput) {
    console.error("❌ Error: No se encontraron los elementos necesarios.");
    return;
  }

  function limpiarTextoUsuario(texto) {
    return texto
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .replace(/ñ/g, "n")
      .replace(/Ñ/g, "N")
      .replace(/[^a-zA-Z0-9\s]/g, "")
      .trim();
  }

  function generarUsuario() {
    let nombre = limpiarTextoUsuario(nombreInput.value.trim());
    let apellido = limpiarTextoUsuario(apellidoInput.value.trim());

    console.log("📥 Nombre capturado:", nombre);
    console.log("📥 Apellido capturado:", apellido);

    if (nombre === "" || apellido === "") {
      console.warn(
        "⚠️ Advertencia: Nombre o apellido vacío, no se generará usuario.",
      );
      return;
    }

    let nombreParts = nombre.split(" ").filter((n) => n.trim() !== "");
    let apellidoParts = apellido.split(" ").filter((a) => a.trim() !== "");

    let usuarioBase = "";

    if (nombreParts.length >= 2) {
      usuarioBase =
        nombreParts[0].charAt(0).toLowerCase() +
        nombreParts[1].charAt(0).toLowerCase();
    } else {
      usuarioBase = nombreParts[0].substring(0, 2).toLowerCase();
    }

    let primerApellido =
      apellidoParts.length > 0 ? apellidoParts[0].toLowerCase() : "";
    let segundoApellidoLetra =
      apellidoParts.length > 1 ? apellidoParts[1].charAt(0).toLowerCase() : "";

    usuarioBase += primerApellido + segundoApellidoLetra;

    console.log("📤 Usuario base generado ANTES de AJAX:", usuarioBase);

    usuarioInput.value = usuarioBase;
    claveInput.value = usuarioBase;

    let formData = new FormData();
    formData.append("nombre", nombre);
    formData.append("apellido", apellido);

    fetch(baseUrl + "usuarios/validarUsuario", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.usuario) {
          let usuarioLimpio = limpiarTextoUsuario(data.usuario).toLowerCase();

          console.log(
            "✅ Usuario final validado DESPUÉS de AJAX:",
            usuarioLimpio,
          );

          usuarioInput.value = usuarioLimpio;
          claveInput.value = usuarioLimpio;
          confirmar_clave.value = usuarioLimpio;
        } else {
          console.error("❌ Error en la respuesta de la API:", data);
        }
      })
      .catch((error) => console.error("❌ Error en la solicitud:", error));
  }

  nombreInput.addEventListener("input", generarUsuario);
  apellidoInput.addEventListener("input", generarUsuario);
});
