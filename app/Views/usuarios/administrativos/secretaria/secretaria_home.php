<!-- Content page -->

<div class="container-fluid">
	<div class="page-header">
		<h1 class="text-titles">PROYECTO FINAL DE GRADO <small>HOME</small></h1>
	</div>
</div>


<div class="full-box text-center" style="padding: 30px 10px;">
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Personal
		</div>
		<div class="full-box tile-icon text-center">
			<i class="fa-solid fa-users"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?= esc($total_personal) ?></p>
			<small>Registrados</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Personal Docente
		</div>
		<div class="full-box tile-icon text-center">
			<i class="fa-solid fa-chalkboard-user"></i>
			
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?= esc($total_docentes) ?></p>
			<small>Registrados</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Personal Administrativo
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-male-female"></i>
		</div>
		<div class="full-box tile-number text-titles">
            <p class="full-box"><?= esc($total_administrativos) ?></p>
			<small>Registradosr</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Personal de Apoyo
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-hand"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?= esc($total_apoyo) ?></p>
			<small>Registrados</small>
		</div>
	</article>
	<br>
	<a href="!#">VER MAS>></a>
	<br>
	<br>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Estudiantes
		</div>
		<div class="full-box tile-icon text-center">
			<i class="fa-solid fa-user-graduate"></i>

		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?= esc($total_estudiantes) ?></p>
			<small>Inscritos</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Masculinos
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-male"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?= esc($total_masculinos) ?></p>
			<small>Inscritos</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Femeninos
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-female"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?= esc($total_femeninos) ?></p>
			<small>Inscritos</small>
		</div>
	</article>
	<article class="full-box tile">
		<div class="full-box tile-title text-center text-titles text-uppercase">
			Familias
		</div>
		<div class="full-box tile-icon text-center">
			<i class="zmdi zmdi-male-female"></i>
		</div>
		<div class="full-box tile-number text-titles">
			<p class="full-box"><?= esc($total_familia) ?></p>
			<small>Registradas</small>
		</div>
	</article>
</div>


</section>

<!-- Notifications area -->
<section class="full-box Notifications-area">
	<div class="full-box Notifications-bg btn-Notifications-area"></div>
	<div class="full-box Notifications-body">
		<div class="Notifications-body-title text-titles text-center">
			Notifications <i class="zmdi zmdi-close btn-Notifications-area"></i>
		</div>
		<div class="list-group">
			<div class="list-group-item">
				<div class="row-action-primary">
					<i class="zmdi zmdi-alert-triangle"></i>
				</div>
				<div class="row-content">
					<div class="least-content">17m</div>
					<h4 class="list-group-item-heading">Tile with a label</h4>
					<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus.</p>
				</div>
			</div>
			<div class="list-group-separator"></div>
			<div class="list-group-item">
				<div class="row-action-primary">
					<i class="zmdi zmdi-alert-octagon"></i>
				</div>
				<div class="row-content">
					<div class="least-content">15m</div>
					<h4 class="list-group-item-heading">Tile with a label</h4>
					<p class="list-group-item-text">Donec id elit non mi porta gravida at eget metus.</p>
				</div>
			</div>
			<div class="list-group-separator"></div>
			<div class="list-group-item">
				<div class="row-action-primary">
					<i class="zmdi zmdi-help"></i>
				</div>
				<div class="row-content">
					<div class="least-content">10m</div>
					<h4 class="list-group-item-heading">Tile with a label</h4>
					<p class="list-group-item-text">Maecenas sed diam eget risus varius blandit.</p>
				</div>
			</div>
			<div class="list-group-separator"></div>
			<div class="list-group-item">
				<div class="row-action-primary">
					<i class="zmdi zmdi-info"></i>
				</div>
				<div class="row-content">
					<div class="least-content">8m</div>
					<h4 class="list-group-item-heading">Tile with a label</h4>
					<p class="list-group-item-text">Maecenas sed diam eget risus varius blandit.</p>
				</div>
			</div>
		</div>

	</div>
</section>

<!-- Dialog help -->
<div class="modal fade" tabindex="-1" role="dialog" id="Dialog-Help">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Help</h4>
			</div>
			<div class="modal-body">
				<p>
					Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nesciunt beatae esse velit ipsa sunt incidunt aut voluptas, nihil reiciendis maiores eaque hic vitae saepe voluptatibus. Ratione veritatis a unde autem!
				</p>
			</div>
			<!--
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-primary btn-raised" data-dismiss="modal"><i class="zmdi zmdi-thumb-up"></i> Ok</button>
		      	</div>
-->
		</div>
	</div>
</div>