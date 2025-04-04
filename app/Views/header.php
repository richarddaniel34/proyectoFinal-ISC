<!DOCTYPE html>
<html lang="es">

<head>
	<title>CENSA-Inicio</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
	
	<!-- Asegurar que los estilos de SweetAlert2 se cargan correctamente -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/datatables.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/font_awesome.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">


	<link rel="shortcut icon" href="<?php echo base_url(); ?>/assets/img/logo/LOGO-CENSA.png" type="image/x-icon">

</head>

<body>
	<!-- SideBar -->
	<section class="full-box cover dashboard-sideBar">
		<div class="full-box dashboard-sideBar-bg btn-menu-dashboard"></div>
		<div class="full-box dashboard-sideBar-ct">
			<!--SideBar Title -->
			<div class="full-box text-uppercase text-center text-titles dashboard-sideBar-title">
				PROYECTO DE GRADO <i class="zmdi zmdi-close btn-menu-dashboard visible-xs"></i>
			</div>
			<!-- SideBar User info -->
			<div class="full-box dashboard-sideBar-UserInfo">
				<figure class="full-box">
					<img src="<?php echo base_url(); ?>/assets/img/avatar.jpg" alt="UserIcon">
					<figcaption class="text-center text-titles">User Name</figcaption>
				</figure>
				<ul class="full-box list-unstyled text-center">
					<li>
						<a href="<?php echo base_url(); ?>usuarios/logout">
							<i class="zmdi zmdi-settings"></i>
						</a>
					</li>
					<li>
						<a href="#!" class="btn-exit-system">
							<i class="zmdi zmdi-power"></i>
						</a>
					</li>
				</ul>
			</div>
			<!-- SideBar Menu -->
			<ul class="list-unstyled full-box dashboard-sideBar-Menu">
				<li>
					<a href="<?php echo base_url(); ?>home">
						<i class="fa-solid fa-gauge"></i> Dashboard
					</a>
				</li>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-address-card "></i> REGISTRO <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo base_url(); ?>personal"><i class=" fa-solid fa-users"></i> Personal</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>responsables"><i class="fa-solid fa-person"></i><i class="fa-solid fa-person-dress"></i> Padres</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>estudiantes"><i class="fa-solid fa-user-graduate"></i> Estudiantes</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>asistencia"><i class="fa-solid fa-user-graduate"></i> Asistencia</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-address-card "></i> Calificaciones <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo base_url(); ?>calificaciones"><i class=" fa-solid fa-users"></i> Calificaciones</a>
						</li>
					</ul>

				</li>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="zmdi zmdi-account-add zmdi-hc-fw"></i> Users <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo base_url(); ?>usuarios/docentes"><i class="zmdi zmdi-account zmdi-hc-fw"></i> docentes</a>
						</li>
						<li>
							<a href="teacher.html"><i class="zmdi zmdi-male-alt zmdi-hc-fw"></i> Teacher</a>
						</li>
						<li>
							<a href="student.html"><i class="zmdi zmdi-face zmdi-hc-fw"></i> Student</a>
						</li>
						<li>
							<a href="representative.html"><i class="zmdi zmdi-male-female zmdi-hc-fw"></i> Representative</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-dollar-sign"></i> Pagos<i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo base_url(); ?>inscripciones"><i class="fa-solid fa-money-bill-1"></i> Inscripciones</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>inscripciones/mensualidades"><i class="fa-solid fa-calendar-check"></i> Mensualidades</a>
						</li>
						<li>
							<a href="payments.html"><i class="zmdi zmdi-money zmdi-hc-fw"></i> </a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-gear"></i> Configuracion <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo base_url(); ?>escuela"><i class="fa-solid fa-school"></i> Informacion de la Escuela</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>schoolyear"><i class="fa-solid fa-calendar-days"></i> Periodo Academico</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>grados"><i class="fa-solid fa-graduation-cap"></i> <i class="fa-solid fa-a"></i><i class="fa-solid fa-b"></i><i class="fa-solid fa-c"></i> Grados</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>asignatura"><i class="fa-solid fa-book"></i> Asignaturas</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>distribucionacademica"><i class="fa-solid fa-book"></i> Distribucion Acedmica</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</section>
	<!-- Content page-->
	<section class="full-box dashboard-contentPage">
		<!-- NavBar -->
		<nav class="full-box dashboard-Navbar">
			<ul class="full-box list-unstyled text-left">
				<li class="pull-left">
					<a href="#!" class="btn-menu-dashboard"><i class="fa-solid fa-bars"></i></a>
				</li>

				<li>
					<a href="#!" class="btn-modal-help">
						<i class="zmdi zmdi-help-outline"></i>
					</a>
				</li>
			</ul>
		</nav>