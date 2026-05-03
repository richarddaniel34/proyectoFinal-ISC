<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>



<!DOCTYPE html>
<html lang="es">

<head>
    <title>CENSA-LogIn</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-material-design.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">

    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/logo/censa-favicon.ico" type="image/x-icon">


</head>

<body class="cover" style="background-image: url(./assets/img/loginFont2.jpg);">
    <form action="<?= base_url('login/auth'); ?>" method="POST" autocomplete="off" class="full-box logInForm">
        <?= csrf_field(); ?>

        <!-- Ícono superior -->
        <p class="text-center text-muted">
            <i class="zmdi zmdi-account-circle zmdi-hc-5x"></i>
        </p>

        <!-- Título -->
        <p class="text-center text-uppercase titulo-login">Inicia sesión con tu cuenta</p>

        <!-- Campo usuario -->
        <div class="form-group label-floating">
            <label class="control-label" for="usuario">Usuario</label>
            <input class="form-control" id="usuario" name="usuario" type="text" required>
            <p class="help-block">Escribe tu E-mail</p>
        </div>

        <!-- Campo contraseña -->
        <div class="form-group label-floating">
            <label class="control-label" for="clave">Contraseña</label>
            <input class="form-control" id="clave" name="clave" type="password" required>
            <p class="help-block">Escribe tu contraseña</p>
        </div>

        <!-- Botón -->
        <div class="form-group text-center">
            <input type="submit" value="Iniciar sesión" class="btn btn-raised btn-danger">
        </div>
    </form>
    <!--====== Scripts -->
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