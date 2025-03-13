<!DOCTYPE html>
<html lang="es">

<head>
    <title>LogIn</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="<?php echo base_url();?>css/main.css">
    
</head>

<body class="cover" style="background-image: url(./assets/img/loginFont.jpg);">
    <form action="<?= base_url('login/auth'); ?>" method="POST" autocomplete="off" class="full-box logInForm">
        <?= csrf_field(); ?>
        <p class="text-center text-muted"><i class="zmdi zmdi-account-circle zmdi-hc-5x"></i></p>
        <p class="text-center text-muted text-uppercase">Inicia sesión con tu cuenta</p>
        <div class="form-group label-floating">
            <label class="control-label" for="usuario">E-mail</label>
            <input class="form-control" id="usuario" type="text">
            <p class="help-block">Escribe tú E-mail</p>
        </div>
        <div class="form-group label-floating">
            <label class="control-label" for="clave">Contraseña</label>
            <input class="form-control" id="clave" type="text">
            <p class="help-block">Escribe tú contraseña</p>
        </div>
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