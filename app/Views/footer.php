<!--
<div class="footerPage">
        <div class="col-12 footerInfo">
            <small>© 2024 Richard Daniel Moronta. Todos los derechos reservados.</small> <br>
            <small>Proyecto Final de Grado, Ing. en Sistemas de Computacion P3-2024 --UCATECI--</small>
        </div>
    </div>
</section>
-->







<!--====== Scripts (Orden optimizado) -->
<script src="<?php echo base_url(); ?>/js/jquery-3.1.1.min.js"></script> <!-- jQuery debe cargarse primero -->
<script src="<?php echo base_url(); ?>/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 después de jQuery -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!-- Script de Bootstrap-Select -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script src="<?php echo base_url(); ?>/js/modal.js"></script>
<script src="<?php echo base_url(); ?>/js/material.min.js"></script>
<script src="<?php echo base_url(); ?>/js/font_awesome.js"></script>
<script src="<?php echo base_url(); ?>/js/datatables-simple-demo.js"></script>
<script src="<?php echo base_url(); ?>/js/simple-datatable.js"></script>
<script src="<?php echo base_url(); ?>/js/ripples.min.js"></script>
<script src="<?php echo base_url(); ?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo base_url(); ?>/js/main.js"></script>
<script src="<?php echo base_url(); ?>/js/codigos.js"></script>
<script src="<?php echo base_url(); ?>/js/usuarios.js"></script>

<script>
    $.material.init();
</script>






<script>
    $(document).ready(function() {
        $('#id_escuela').change(function() {
            var idEscuela = $(this).val();

            if (idEscuela) {
                $.ajax({
                    url: "<?= base_url('distribucionacademica/getCursos') ?>",
                    type: "POST",
                    data: {
                        id_escuela: idEscuela
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#id_curso').empty().append('<option value="">Seleccione un curso</option>');

                        if (response.length > 0) {
                            $.each(response, function(index, curso) {
                                $('#id_curso').append('<option value="' + curso.id + '">' + curso.nombreCurso + '</option>');
                            });
                        } else {
                            $('#id_curso').append('<option value="">No hay cursos disponibles</option>');
                        }
                    },
                    error: function() {
                        alert("Error al obtener los cursos.");
                    }
                });
            } else {
                $('#id_curso').empty().append('<option value="">Seleccione una escuela primero</option>');
            }
        });
    });
</script>




<script>
    function siguiente(tabId) {
        $('.nav-tabs a[href="#' + tabId + '"]').tab('show');
    }

    function toggleTutor() {
        let tutorInfo = document.getElementById('tutor-info');
        tutorInfo.style.display = document.getElementById('necesita_tutor').checked ? 'block' : 'none';
    }
</script>



<script>
    $(document).ready(function() {
        // ✅ Mostrar alerta de éxito
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '<?= session()->getFlashdata('success') ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                showCloseButton: true,
                focusConfirm: false,
                backdrop: false // 🚀 No bloquea el fondo
            });
        <?php endif; ?>

        // ❌ Mostrar alerta de error si hay errores en los campos
        <?php if (session()->getFlashdata('errors')): ?>
            Swal.fire({
                icon: 'error',
                title: '¡Error en el formulario!',
                text: 'Por favor, corrige los campos en rojo.',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido',
                showCloseButton: true,
                focusConfirm: false,
                backdrop: false // 🚀 No bloquea el fondo
            });
        <?php endif; ?>
    });
</script>


<!--
<script>
    window.onload = function() {
        console.log("✅ Script de búsqueda cargado correctamente");

        let inputElement = document.getElementById("responsables");
        let hiddenInput = document.getElementById("responsables_hidden");
        let suggestionsBox = document.getElementById("listaResponsables");

        let isSelecting = false; // 🔥 Bandera para evitar búsquedas innecesarias

        if (!inputElement || !hiddenInput || !suggestionsBox) {
            console.error("❌ Error: Elementos del formulario no encontrados");
            return;
        }

        console.log("✅ Elementos encontrados, agregando eventos...");

        // 🔥 Evento cuando el usuario escribe en el input
        inputElement.addEventListener("input", function(event) {
            let inputValue = event.target.value.trim();
            console.log("✍ Evento input activado con valor:", inputValue);

            if (isSelecting) {
                console.log("⏳ Se está seleccionando una opción, evitando nueva búsqueda...");
                isSelecting = false; // Restablecer la bandera después de la selección
                return;
            }

            if (inputValue.length > 1) {
                console.log("🔍 Buscando en API: " + inputValue);

                fetch("<?= base_url('responsables/buscar'); ?>?search=" + encodeURIComponent(inputValue))
                    .then(response => response.json())
                    .then(data => {
                        console.log("📥 Datos recibidos del servidor:", data);

                        suggestionsBox.innerHTML = ""; // Limpiar opciones previas
                        suggestionsBox.style.display = "none"; // Ocultar si no hay resultados

                        if (Array.isArray(data.results) && data.results.length > 0) {
                            data.results.forEach(item => {
                                let option = document.createElement("div");
                                option.classList.add("suggestion-item"); // 🔥 Clase para estilos
                                option.textContent = item.text; // Mostrar nombre
                                option.dataset.id = item.id; // Guardar ID

                                // 🔥 Evento al hacer clic en una opción
                                option.addEventListener("click", function() {
                                    console.log("🖱️ Click en opción:", item.text);

                                    isSelecting = true; // 🔥 Evitar búsqueda al asignar valor

                                    setTimeout(() => {
                                        inputElement.value = item.text; // 🔥 Establecer el nombre en el input visible
                                        hiddenInput.value = item.id; // 🔥 Guardar el ID en input oculto

                                        console.log("✅ Después de asignar - Input visible:", inputElement.value);
                                        console.log("✅ Después de asignar - Input oculto:", hiddenInput.value);

                                        suggestionsBox.style.display = "none"; // 🔥 Ocultar la lista de sugerencias
                                        inputElement.focus(); // Asegurar que el input mantenga el foco
                                    }, 10); // 🔥 Pequeña pausa para permitir la actualización del DOM
                                });




                                suggestionsBox.appendChild(option);
                            });

                            suggestionsBox.style.display = "block"; // Mostrar la lista si hay resultados
                        }
                    })
                    .catch(error => console.error("❌ Error en la búsqueda:", error));
            } else {
                suggestionsBox.style.display = "none"; // Ocultar si se borra el texto
            }
        });

        // 🔥 Ocultar la lista si el usuario hace clic fuera
        document.addEventListener("click", function(event) {
            if (!suggestionsBox.contains(event.target) && event.target !== inputElement) {
                suggestionsBox.style.display = "none";
            }
        });
    };
</script>
-->





<?= $this->renderSection('scripts') ?>

</body>

</html>