<!DOCTYPE html>
<html lang="es">

<head>
	<title>CENSA-Inicio</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">



	<!-- Asegurar que los estilos de SweetAlert2 se cargan correctamente -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	<link rel="stylesheet" href="<?php echo base_url(); ?>css/font_awesome.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/main.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/pagos.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/estudiantes.css">
	<!--<link rel="stylesheet" href="css/bootstrap-material-design.min">-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">

	<!-- DataTables Bootstrap 4 CSS -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">

	<link rel="stylesheet" href="<?= base_url('css/form.css') ?>?v=3">
	<link rel="stylesheet" href="<?= base_url('css/calificaciones.css') ?>?v=3">
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/logo/censa-favicon.ico" type="image/x-icon">

	<!-- Estilo personalizado para el dropdown de escuelas -->
	<style>
		.dropdown-escuela {
			position: relative !important;
		}

		.dropdown-escuela .dropdown-menu {
			position: fixed !important;
			min-width: 400px !important;
			width: 400px !important;
			background-color: white !important;
			border: 3px solid red !important;
			/* Para debugging */
			border-radius: .25rem !important;
			box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5) !important;
			display: none !important;
			z-index: 999999 !important;
			max-height: 400px !important;
			overflow-y: auto !important;
		}

		/* Mostrar dropdown cuando tiene la clase 'show' */
		.dropdown-escuela .dropdown-menu.show {
			display: block !important;
		}

		.dropdown-escuela .dropdown-item {
			padding: 10px 16px !important;
			transition: all 0.2s ease !important;
			border-bottom: 1px solid #f8f9fa !important;
			cursor: pointer !important;
			display: block !important;
			color: #333 !important;
			text-decoration: none !important;
		}

		.dropdown-escuela .dropdown-item:last-child {
			border-bottom: none !important;
		}

		.dropdown-escuela .dropdown-item:hover {
			background-color: #f8f9fa !important;
			color: #333 !important;
			transform: translateX(5px) !important;
		}

		.dropdown-escuela .dropdown-item i {
			color: #6c757d !important;
			width: 16px !important;
		}

		.dropdown-escuela .dropdown-item:hover i {
			color: #333 !important;
		}

		#btnCargarEscuelas {
			border-radius: 20px !important;
			font-size: 12px !important;
			padding: 6px 12px !important;
		}



		/*CHIPS */

		.chips-container {
			display: flex;
			flex-wrap: wrap;
			gap: 5px;
		}

		.chip {
			display: inline-block;
			padding: 5px 10px;
			background-color: #eee;
			border-radius: 20px;
			cursor: pointer;
			user-select: none;
		}

		.chip.selected {
			background-color: #FF5722;
			color: #fff;
		}

		.asignacion-item {
			margin-top: 5px;
			padding: 5px 10px;
			background-color: #f5f5f5;
			border-radius: 5px;
		}

		.asignacion-item .delete-btn {
			cursor: pointer;
			color: red;
			margin-left: 10px;
		}



		/*CALIFICACIONES 

	   body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #f4a261;
        }
        .asignatura {
            background-color: #a8d5ba;
            font-weight: bold;
        }
        .comp-group {
            background-color: #fcd5b5;
            font-size: 11px;
        }
    */
	</style>
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
					<img src="<?= session('foto') ?>" alt="Foto de perfil">
					<!--<img src="<?php //echo base_url(); 
									?>/assets/img/avatar.jpg" alt="UserIcon">-->
					<figcaption class="text-center text-titles"> <b>USUARIO: </b><?= session('usuario') ?></figcaption>
				</figure>
				<ul class="full-box list-unstyled text-center">
					<li>
						<a href="#!">
							<i class="zmdi zmdi-settings"></i>
						</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>logout" class="btn-exit-system">
							<i class="zmdi zmdi-power"></i>
						</a>
					</li>
				</ul>
			</div>
			<!-- SideBar Menu -->
			<?php
			$usuario = session()->get('usuario_data');

			$tipo = trim($usuario['tipo_usuario'] ?? '');
			$funcion = trim($usuario['personal_funcion'] ?? '');

			$puedeRegistro = (
				in_array($tipo, ['Administrador', 'S-ADMIN']) ||
				(
					$tipo === 'Administrativo' &&
					in_array($funcion, ['Digitador/a', 'Secretaria/o'])
				)
			);
			$puedePago = (
				in_array($tipo, ['Administrador', 'S-ADMIN']) ||
				(
					$tipo === 'Administrativo' &&
					in_array($funcion, ['Digitador/a', 'Secretaria/o'])
				)
			);
			$puedeConfiguracion = (
				in_array($tipo, ['Administrador', 'S-ADMIN'])
			);
			?>

			<ul class="list-unstyled full-box dashboard-sideBar-Menu">
				<li>
					<a href="<?php echo base_url(); ?>home">
						<i class="fa-solid fa-gauge"></i> <b>DASHBOARD</b>
					</a>
				</li>
				<?php if ($puedeRegistro): ?>
					<li>
						<a href="#!" class="btn-sideBar-SubMenu">
							<i class="fa-solid fa-address-card "></i> <b>REGISTRO</b>
							<i class="zmdi zmdi-caret-down pull-right"></i>
						</a>
						<ul class="list-unstyled full-box">
							<li>
								<a href="<?php echo base_url(); ?>personal"><i class=" fa-solid fa-users"></i> Personal</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>responsables"><i class="fa-solid fa-person"></i> Padres</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>estudiantes"><i class="fa-solid fa-user-graduate"></i> Estudiantes</a>
							</li>

						</ul>
					</li>
				<?php endif; ?>

				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-graduation-cap"></i> <b> GESTIÓN ACADEMICA </b>
						<i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo base_url(); ?>inscripciones/relacion"><i class="fa-solid fa-user-graduate"></i> Relación Estudiantes</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>asistencia"><i class="fa-solid fa-calendar-check"></i> Asistencia</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>docenteguia"><i class="fa-solid fa-chalkboard-user"></i> Docente Guia</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>distribucion-academica"><i class="fa-solid fa-diagram-project"></i> Distribución Academica</a>
						</li>
						<li>
							<a href="<?php echo base_url(); ?>calificaciones"><i class="fa-solid fa-clipboard-check"></i> Calificaciones</a>
						</li>
					</ul>
				</li>



				<li>
					<a href="<?php echo base_url(); ?>reportes" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-address-card "></i> <b>REPORTES</b> <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
				</li>

				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-user"></i> <b>USUARIOS</b> <i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?php echo base_url(); ?>usuarios/listarUsuarios"><i class="zmdi zmdi-account zmdi-hc-fw"></i> Personal</a>
						</li>
					</ul>
				</li>

				<li>
					<a href="#!" class="btn-sideBar-SubMenu">
						<i class="fa-solid fa-dollar-sign"></i> <b>PAGOS</b><i class="zmdi zmdi-caret-down pull-right"></i>
					</a>
					<ul class="list-unstyled full-box">
						<li>
							<a href="<?= base_url('student/mis_pagos') ?>"><i class="fa-solid fa-receipt"></i> Mis Pagos</a>
						</li>
						<?php if ($puedePago): ?>
							<li>
								<a href="<?php echo base_url(); ?>pagos"><i class="fa-solid fa-money-bill-1"></i> Gestión de Pagos</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>inscripciones/mensualidades"><i class="fa-solid fa-calendar-check"></i> Mensualidades</a>
							</li>
							<li>
								<a href="payments.html"><i class="zmdi zmdi-money zmdi-hc-fw"></i> </a>
							</li>
						<?php endif; ?>
					</ul>
				</li>

				<?php if ($puedeConfiguracion): ?>

					<li>
						<a href="#!" class="btn-sideBar-SubMenu">
							<i class="fa-solid fa-gear"></i> <b>CONFIGURACIÓN</b><i class="zmdi zmdi-caret-down pull-right"></i>
						</a>
						<ul class="list-unstyled full-box">
							<li>
								<a href="<?php echo base_url(); ?>escuela"><i class="fa-solid fa-school"></i> Informacion de la Escuela</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>schoolyear"><i class="fa-solid fa-calendar-days"></i> Periodo Academico</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>estructura-academica"><i class="fas fa-sitemap"></i> Estructura Academica</a>
							</li>
							<li>
								<a href="<?php echo base_url(); ?>asignaturas"><i class="fa-solid fa-book"></i> Asignaturas</a>
							</li>

						</ul>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</section>


	<!-- Content page-->
	<section class="full-box dashboard-contentPage">
		<!-- NavBar -->
		<nav class="full-box dashboard-Navbar d-flex justify-content-between align-items-center px-3">
			<ul class="list-unstyled d-flex mb-0">
				<li class="me-3">
					<a href="#!" class="btn-menu-dashboard text-white"><i class="fa-solid fa-bars"></i></a>
				</li>
				<li>
					<a href="#!" class="btn-modal-help text-white">
						<i class="zmdi zmdi-help-outline"></i>
					</a>
				</li>

			</ul>



			<!-- Aquí va el bloque de info a la derecha -->
			<div class="d-flex align-items-center bg-success text-white px-3 py-2 ml-auto rounded">
				<?php if (session('tipo_usuario') == '5'): ?>
					<!-- Selector de escuela para administradores -->
					<div class="dropdown dropdown-escuela">
						<button class="btn btn-success dropdown-toggle p-0" type="button" id="dropdownEscuela" aria-haspopup="true" aria-expanded="false">
							<?php if (session('codigo_gestion') && session('nombre_escuela')): ?>
								<?= session('codigo_gestion') ?> - <?= session('nombre_escuela') ?>
							<?php else: ?>
								Seleccionar Escuela
							<?php endif; ?>
							- Administrador
						</button>
						<div class="dropdown-menu" aria-labelledby="dropdownEscuela">
							<!-- Aquí se cargarán las escuelas dinámicamente mediante AJAX -->
							<div class="dropdown-item text-center">
								<button class="btn btn-sm btn-info" id="btnCargarEscuelas">Cargar Escuelas</button>
							</div>
							<div id="listaEscuelas"></div>
						</div>
					</div>
				<?php else: ?>
					<span class="text-truncate" style="max-width: 600px;">
						<?php if (session('codigo_gestion') && session('nombre_escuela')): ?>
							<?= session('codigo_gestion') ?> - <?= session('nombre_escuela') ?>
							- <?= ucfirst(session('tipo_usuario_nombre')) ?>
							<?php if (session('funcion')): ?>
								(<?= ucfirst(session('funcion')) ?>)
							<?php endif; ?>
						<?php else: ?>
							Datos no disponibles
						<?php endif; ?>
					</span>
				<?php endif; ?>
			</div>
		</nav>