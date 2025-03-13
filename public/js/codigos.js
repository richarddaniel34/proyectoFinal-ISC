function generarCodigo() {
    const nombre = document.getElementById('nombre').value.trim();

    if (nombre === '') {
        document.getElementById('codigo_asignatura').value = '';
        return;
    }

    // Palabras irrelevantes que se deben ignorar
    const palabrasIrrelevantes = ['y', 'de', 'del', 'la', 'el', 'los', 'las', 'en'];

    // Separar palabras, ignorar espacios adicionales y convertir a mayúsculas
    const palabras = nombre.split(/\s+/).filter(p => !palabrasIrrelevantes.includes(p.toLowerCase())).map(p => p.toUpperCase());

    // Generar el código tomando las primeras tres letras de cada palabra relevante
    let codigo = palabras.map((palabra, index) => {
        return palabra.substring(0, 3); // Máximo 3 letras por palabra
    }).join('-'); // Separador "-"

    // Limitar la longitud máxima del código (opcional)
    const maxLongitud = 15; // Ajusta este valor según tus necesidades
    if (codigo.length > maxLongitud) {
        codigo = codigo.substring(0, maxLongitud);
    }

    // Asignar el código generado al campo correspondiente
    document.getElementById('codigo_asignatura').value = codigo;
}


// Generar el Nombre Corto del Curso
function generarNombreCorto(nombreGrado) {
    const palabras = nombreGrado.split(/\s+/); // Dividir palabras
    let nombreCorto = '';

    // Grados específicos para Pre-Primario y Kinder
    if (nombreGrado.toLowerCase() === 'pre-primario') {
        nombreCorto = 'PP';
    } else if (nombreGrado.toLowerCase() === 'kinder') {
        nombreCorto = 'K';
    } else {
        // Convertir la primera palabra (grado)
        if (palabras[0].toLowerCase() === 'primero') nombreCorto = '1ro';
        else if (palabras[0].toLowerCase() === 'segundo') nombreCorto = '2do';
        else if (palabras[0].toLowerCase() === 'tercero') nombreCorto = '3ro';
        else if (palabras[0].toLowerCase() === 'cuarto') nombreCorto = '4to';
        else if (palabras[0].toLowerCase() === 'quinto') nombreCorto = '5to';
        else if (palabras[0].toLowerCase() === 'sexto') nombreCorto = '6to';

        // Agregar el nivel educativo sin abreviar
        if (palabras[2] && palabras[2].toLowerCase() === 'primaria') {
            nombreCorto += ' de Primaria';
        } else if (palabras[2] && palabras[2].toLowerCase() === 'secundaria') {
            nombreCorto += ' de Secundaria';
        }
    }

    return nombreCorto;
}

// Generar el Código del Curso
function generarCodigoCurso(nombreCorto, seccion) {
    if (nombreCorto === 'PP' || nombreCorto === 'K') {
        return `${nombreCorto}-${seccion.toUpperCase()}`;
    }

    const partes = nombreCorto.split(' ');
    const grado = partes[0].toUpperCase();
    const nivel = partes[2]?.slice(0, 3).toUpperCase() || ''; // Usar "PRI" o "SEC"

    return `${grado}-${nivel}-${seccion.toUpperCase()}`;
}

// Actualizar Nombre Curso y Código Curso
function actualizarNombreYCodigo() {
    const gradoSelect = document.getElementById('id_grado');
    const seccionSelect = document.getElementById('id_secciones');
    const nombreCursoInput = document.getElementById('nombreCurso');
    const codigoCursoInput = document.getElementById('codigoCurso');

    const gradoNombre = gradoSelect.options[gradoSelect.selectedIndex].text.trim(); // Obtener texto del grado
    const seccionNombre = seccionSelect.options[seccionSelect.selectedIndex].text.trim(); // Obtener texto de la sección

    // Verificar que no se seleccione el valor predeterminado
    if (
        gradoNombre === '--Seleccione la Sección--' || 
        seccionNombre === '--Seleccione la Sección--' || 
        gradoNombre === '' || 
        seccionNombre === ''
    ) {
        nombreCursoInput.value = '';
        codigoCursoInput.value = '';
        return;
    }

    const nombreCorto = generarNombreCorto(gradoNombre);
    nombreCursoInput.value = `${nombreCorto} ${seccionNombre}`;
    codigoCursoInput.value = generarCodigoCurso(nombreCorto, seccionNombre);
}

// Agregar eventos a los select
document.getElementById('id_grado').addEventListener('change', actualizarNombreYCodigo);
document.getElementById('id_secciones').addEventListener('change', actualizarNombreYCodigo);


    function generarCodigoPeriodo() {
        let nombre = document.getElementById("nombre").value;
        let codigoInput = document.getElementById("codigo");

        // Expresión regular para encontrar los años en el formato "2024-2025"
        let match = nombre.match(/\d{4}-\d{4}/);

        if (match) {
            let years = match[0]; // Captura los años
            let codigo = `ESC-JAR_${years}`;
            codigoInput.value = codigo;
        } else {
            codigoInput.value = ""; // Si no encuentra un año válido, deja el campo vacío
        }
    }


