


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>css/main.css">
</head>

<body>
    <h1>Cambiar Contraseña</h1>

    <?php if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    } ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>
<form method="POST" action="<?= site_url('usuarios/actualizarClave'); ?>" class="full-box logInForm">
    
    <!-- Usuario (solo lectura) -->
    <div class="form-group">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" value="<?= session('usuario'); ?>" readonly class="form-control">
    </div>

    <!-- Nuevo Password -->
    <div class="form-group">
        <label for="nuevo_password">Nueva Contraseña:</label>
        <input type="password" id="nuevo_password" name="nuevo_password" required class="form-control">
    </div>

    <!-- Confirmar Password -->
    <div class="form-group">
        <label for="confirmar_password">Confirmar Contraseña:</label>
        <input type="password" id="confirmar_password" name="confirmar_password" required class="form-control">
    </div>

    <!-- Botón -->
    <button type="submit" class="btn btn-primary btn-block mt-3">Actualizar Contraseña</button>
</form>



    <script src="<?php echo base_url(); ?>/js/jquery-3.1.1.min.js"></script> <!-- jQuery debe cargarse primero -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 después de jQuery -->
    <script src="<?php echo base_url(); ?>/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url(); ?>/js/material.min.js"></script>
    <script src="<?php echo base_url(); ?>/js/ripples.min.js"></script>
    <script src="<?php echo base_url(); ?>/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script>
        $.material.init();
    </script>

</body>



</html>