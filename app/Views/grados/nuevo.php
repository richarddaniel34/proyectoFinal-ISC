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
                                <form method="POST" enctype="multipart/form-data" action="<?php echo base_url(); ?>/grados/insertar" autocomlete="off" class="formP">
                                    <div class="row">
                                        <div class="col-12 col-12">
                                            <div class="form-group label-floating">
                                                <label for="nombre" class="control-label">Nombre Grado:</label>
                                                <input class="form-control" type="text" id="nombre" name="nombre" autofocus required />
                                                <small>*</small>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-center">

                                        <button type="submit" class="btn btn-info btn-raised btn-sm">
                                            <i class="fa-solid fa-floppy-disk"></i> Guardar
                                        </button>

                                        <a
                                            href="<?php echo base_url(); ?>grados"
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


    <div class="container-fluid">
        <div class="page-header">

        </div>
    </div>

    <div class="tab-pane " id="list">
        <div class="table-responsive">
            <table class="table table-hover table-striped text-center" id="datatablesSimple">
                <thead class="title-table">
                    <tr>
                        <th class="text-center">Grado</th>
                        <th class="text-center">Seccion</th>
                        <th class="text-center">Codigo</th>
                        <th class="text-center"></th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>

        </div>