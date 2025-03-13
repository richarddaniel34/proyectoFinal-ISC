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

    function generarUsuario() {
        let nombre = nombreInput.value.trim();
        let apellido = apellidoInput.value.trim();

        console.log("📥 Nombre capturado:", nombre);
        console.log("📥 Apellido capturado:", apellido);

        if (nombre === "" || apellido === "") {
            console.warn("⚠️ Advertencia: Nombre o apellido vacío, no se generará usuario.");
            return;
        }

        // 🔥 Separar nombres y apellidos correctamente
        let nombreParts = nombre.split(" ").filter(n => n.trim() !== "");
        let apellidoParts = apellido.split(" ").filter(a => a.trim() !== "");

        console.log("🔍 Partes del Nombre:", nombreParts);
        console.log("🔍 Partes del Apellido:", apellidoParts);

        let usuarioBase = "";

        if (nombreParts.length >= 2) {
            usuarioBase = nombreParts[0].charAt(0).toLowerCase() + nombreParts[1].charAt(0).toLowerCase();
        } else {
            usuarioBase = nombreParts[0].substring(0, 2).toLowerCase();
        }

        let primerApellido = apellidoParts.length > 0 ? apellidoParts[0].toLowerCase() : "";
        let segundoApellidoLetra = apellidoParts.length > 1 ? apellidoParts[1].charAt(0).toLowerCase() : "";

        usuarioBase += primerApellido + segundoApellidoLetra;

        console.log("📤 Usuario base generado ANTES de AJAX:", usuarioBase);

        usuarioInput.value = usuarioBase;
        claveInput.value = usuarioBase; // También asignar como clave

        let formData = new FormData();
        formData.append("nombre", nombre);
        formData.append("apellido", apellido);

        fetch("http://localhost/edsn/public/usuarios/validarUsuario", {
            method: "POST",
            body: formData,
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.usuario) {
                console.log("✅ Usuario final validado DESPUÉS de AJAX:", data.usuario);
                usuarioInput.value = data.usuario; 
                claveInput.value = data.usuario; // También asignar la misma clave
                confirmar_clave.value = data.usuario;
            } else {
                console.error("❌ Error en la respuesta de la API:", data);
            }
        })
        .catch((error) => console.error("❌ Error en la solicitud:", error));
    }

    nombreInput.addEventListener("input", generarUsuario);
    apellidoInput.addEventListener("input", generarUsuario);
});
