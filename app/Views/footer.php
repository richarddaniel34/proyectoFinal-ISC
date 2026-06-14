
<!--
<section>
    <div class="footerPage">
        <div class="col-12 footerInfo">
            <small>© 2024 Richard Daniel Moronta. Todos los derechos reservados.</small> <br>
            <small>Proyecto Final de Grado, Ing. en Sistemas de Computacion P3-2024 --UCATECI--</small>
        </div>
    </div>
</section>
-->







<!--====== Scripts (Orden optimizado) -->
<script src="<?php echo base_url(); ?>js/vendor/jquery-3.1.1.min.js"></script> <!-- jQuery debe cargarse primero -->

<!--=========================================== DATATABLES ===========================================-->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>


<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>

<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!--configuracion de datatable personalizada-->
<script src="<?php echo base_url(); ?>js/core/datatables-config.js"></script>
<!--==================================================================================================-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


<script src="<?php echo base_url(); ?>/js/vendor/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 después de jQuery -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!-- Script de Bootstrap-Select -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script src="<?php echo base_url(); ?>js/core/modal.js"></script>
<script src="<?php echo base_url(); ?>js/vendor/material.min.js"></script>
<script src="<?php echo base_url(); ?>js/font_awesome.js"></script>
<script src="<?php echo base_url(); ?>js/datatables-simple-demo.js"></script>
<script src="<?php echo base_url(); ?>js/vendor/simple-datatable.js"></script>
<script src="<?php echo base_url(); ?>js/vendor/ripples.min.js"></script>
<script src="<?php echo base_url(); ?>js/vendor/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="<?php echo base_url(); ?>js/core/main.js"></script>
<script src="<?= base_url('js/core/formularios.js') ?>"></script>
<script src="<?php echo base_url(); ?>js/modules/usuarios.js"></script>

<script>
    $.material.init();
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


<!--SWEET ALERT-->
<script>
    $(document).ready(function() {
        // ✅ Éxito
        <?php if (session()->getFlashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: <?= json_encode(session()->getFlashdata('success')) ?>,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK',
                showCloseButton: true,
                focusConfirm: false,
                backdrop: false
            });
        <?php endif; ?>

        //  Error de lógica/controlador
        <?php if (session()->getFlashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Registro no permitido',
                text: <?= json_encode(session()->getFlashdata('error')) ?>,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido',
                showCloseButton: true,
                focusConfirm: false,
                backdrop: false
            });
        <?php endif; ?>

        // ⚠️ Error en campos del formulario
        <?php if (session()->getFlashdata('errors')): ?>
            Swal.fire({
                icon: 'error',
                title: '¡Error en el formulario!',
                text: 'Por favor, corrige los campos en rojo.',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Entendido',
                showCloseButton: true,
                focusConfirm: false,
                backdrop: false
            });
        <?php endif; ?>
    });
</script>



<?php if (session()->getFlashdata('error_swal')):
    $swal = session()->getFlashdata('error_swal'); ?>
    <script>
        Swal.fire({
            icon: 'warning',
            title: '<?= esc($swal['titulo']) ?>',
            html: `<?= $swal['mensaje'] ?>`,
            confirmButtonText: 'Aceptar'
        });
    </script>
<?php endif; ?>




<?php if (session('tipo_usuario') == '5'): ?>
    <script>
        $(document).ready(function() {
            console.log('Script cargado correctamente');

            // Verificar que el botón existe
            console.log('Botón encontrado:', $('#dropdownEscuela').length);
            console.log('Dropdown menu encontrado:', $('.dropdown-menu').length);

            // Manejar el dropdown con debugging completo
            $("#dropdownEscuela").click(function(e) {
                console.log('Click detectado en dropdownEscuela');
                e.preventDefault();
                e.stopPropagation();

                // Probar múltiples formas de encontrar el dropdown
                const dropdownMenu1 = $(this).siblings('.dropdown-menu');
                const dropdownMenu2 = $(this).parent().find('.dropdown-menu');
                const dropdownMenu3 = $('.dropdown-escuela .dropdown-menu');

                console.log('siblings:', dropdownMenu1.length);
                console.log('parent.find:', dropdownMenu2.length);
                console.log('class selector:', dropdownMenu3.length);

                // Usar el que funcione
                let dropdownMenu = dropdownMenu1.length ? dropdownMenu1 :
                    dropdownMenu2.length ? dropdownMenu2 : dropdownMenu3;

                console.log('Dropdown seleccionado:', dropdownMenu.length);

                // Cerrar otros dropdowns
                $('.dropdown-menu').removeClass('show').hide();

                // Toggle del dropdown actual
                if (dropdownMenu.hasClass('show')) {
                    console.log('Cerrando dropdown');
                    dropdownMenu.removeClass('show').hide();
                } else {
                    console.log('Abriendo dropdown');

                    // Calcular posición inteligente
                    const buttonRect = this.getBoundingClientRect();
                    const windowWidth = window.innerWidth;
                    const windowHeight = window.innerHeight;
                    const dropdownWidth = 400; // Ancho que definimos en CSS
                    const dropdownHeight = 300; // Altura estimada

                    let leftPos = buttonRect.right - dropdownWidth; // Alinear a la derecha del botón
                    let topPos = buttonRect.bottom + 5;

                    // Verificar si se sale por la izquierda
                    if (leftPos < 10) {
                        leftPos = 10; // Margen mínimo desde la izquierda
                    }

                    // Verificar si se sale por la derecha
                    if (leftPos + dropdownWidth > windowWidth - 10) {
                        leftPos = windowWidth - dropdownWidth - 10; // Margen mínimo desde la derecha
                    }

                    // Verificar si se sale por abajo
                    if (topPos + dropdownHeight > windowHeight - 10) {
                        topPos = buttonRect.top - dropdownHeight - 5; // Mostrar arriba del botón
                    }

                    // Aplicar posición calculada
                    dropdownMenu.css({
                        'top': topPos + 'px',
                        'left': leftPos + 'px'
                    });

                    console.log('Posición calculada - Top:', topPos, 'Left:', leftPos);

                    dropdownMenu.addClass('show').show();
                }

                console.log('Estado final - tiene clase show:', dropdownMenu.hasClass('show'));
                console.log('Estado final - visible:', dropdownMenu.is(':visible'));
            });

            // Resto del código igual...
            $(document).click(function(e) {
                if (!$(e.target).closest('.dropdown-escuela').length) {
                    $('.dropdown-menu').removeClass('show').hide();
                }
            });

            // Cerrar dropdown al hacer scroll o resize
            $(window).scroll(function() {
                $('.dropdown-menu').removeClass('show').hide();
            });

            $(window).resize(function() {
                $('.dropdown-menu').removeClass('show').hide();
            });

            $("#btnCargarEscuelas").click(function(e) {
                e.stopPropagation();
                const $this = $(this);
                const originalText = $this.text();

                $this.html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
                $this.prop('disabled', true);

                $.ajax({
                    url: '<?php echo base_url(); ?>usuarios/getEscuelas',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        let html = '';
                        if (response.length > 0) {
                            response.forEach(function(escuela) {
                                html += `<a class="dropdown-item cambiar-escuela" href="#" 
                            data-id="${escuela.id}" 
                            data-codigo="${escuela.codigo_gestion}" 
                            data-nombre="${escuela.nombre}"
                            title="${escuela.codigo_gestion} - ${escuela.nombre}">
                            <i class="fas fa-school me-2"></i>
                            ${escuela.codigo_gestion} - ${escuela.nombre}
                        </a>`;
                            });
                            html += '<div class="dropdown-divider"></div>';
                            html += '<div class="dropdown-item text-muted text-center small">Total: ' + response.length + ' escuelas</div>';
                        } else {
                            html = '<div class="dropdown-item text-muted">No hay escuelas disponibles</div>';
                        }
                        $("#listaEscuelas").html(html);
                        $this.parent().hide();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudieron cargar las escuelas',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    },
                    complete: function() {
                        $this.html(originalText);
                        $this.prop('disabled', false);
                    }
                });
            });

            $(document).on('click', '.cambiar-escuela', function(e) {
                e.preventDefault();
                const escuelaId = $(this).data('id');
                const escuelaCodigo = $(this).data('codigo');
                const escuelaNombre = $(this).data('nombre');

                $('.dropdown-menu').removeClass('show').hide();

                const $botonPrincipal = $('#dropdownEscuela');
                const textoOriginal = $botonPrincipal.html();
                $botonPrincipal.html('<i class="fas fa-spinner fa-spin"></i> Cambiando...');
                $botonPrincipal.prop('disabled', true);

                $.ajax({
                    url: '<?= base_url('usuarios/cambiarEscuela') ?>',
                    type: 'POST',
                    data: {
                        id_escuela: escuelaId,
                        codigo_gestion: escuelaCodigo,
                        nombre_escuela: escuelaNombre,
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Éxito',
                                text: 'Escuela cambiada correctamente',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'No se pudo cambiar la escuela'
                            });
                            $botonPrincipal.html(textoOriginal);
                            $botonPrincipal.prop('disabled', false);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al procesar la solicitud'
                        });
                        $botonPrincipal.html(textoOriginal);
                        $botonPrincipal.prop('disabled', false);
                    }
                });
            });
        });
    </script>
<?php endif; ?>




<?= $this->renderSection('scripts') ?>
<?= $this->include('layouts/alerts'); ?>
</body>

</html>