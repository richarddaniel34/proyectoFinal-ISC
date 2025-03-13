<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles"><i class="fa-solid fa-school"></i>Configuración/ <small><?php print_r($titulo) ?></small></h1>
    </div>
</div>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="new">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xs-12 col-md-10 offset-md-1">
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>schoolyear/insertar" autocomlete="off" class="formP">
                                    <div class="row">
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="nombre" class="control-label">Nombre:</label>
                                                <input class="form-control" type="text" id="nombre" name="nombre" autofocus oninput="generarCodigoPeriodo()" required />
                                                <small>*</small>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="fecha_inicio" class="control-label">fecha de Inicio:</label>
                                                <input class="form-control" type="date" id="fecha_inicio" value=" " name="fecha_inicio"  required/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="fecha_termino" class="control-label">fecha de Termino:</label>
                                                <input class="form-control" type="date" id="fecha_termino" value=" " name="fecha_termino"/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3">
                                            <div class="form-group label-floating">
                                                <label for="codigo" class="control-label">Codigo:</label>
                                                <input class="form-control" type="text" id="codigo" name="codigo" readonly required />
                                                <small>*</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-center">

                                    <button type="submit" class="btn btn-info btn-raised btn-sm">
                                        <i class="fa-solid fa-floppy-disk"></i> Guardar
                                    </button>

                                    <a
                                        href="<?php echo base_url(); ?>/asignatura"
                                        class="btn btn-danger btn-raised btn-sm">
                                        <i class="fa-solid fa-ban"></i> Cancelar
                                                    </a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 



