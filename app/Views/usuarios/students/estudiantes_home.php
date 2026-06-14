
<div class="container-fluid">
    <div class="page-header">
        <h1 class="text-titles">
            <i class="fa-solid fa-user-graduate"></i>
            ESTUDIANTE / <small>HOME</small>
        </h1>
    </div>
</div>


<div class="container-fluid dashboard-estudiante">

    

    <div class="dashboard-hero">
        <div>
            <h2>Bienvenido, <?= session('usuario') ?>!</h2>
            <p>Este es tu espacio de consulta como estudiante.</p>
        </div>

        <div class="perfil-mini">
            <img src="<?= session('foto') ?>" alt="Foto de perfil">
        </div>
    </div>

    <div class="dashboard-grid">

        <div class="dashboard-card info-card">
            <h4><i class="fa-solid fa-id-card"></i> Mi información</h4>
            <p><strong>Matrícula:</strong> <?= session('usuario') ?></p>
            <p><strong>Escuela:</strong> <?= session('nombre_escuela') ?></p>
            <p><strong>Tipo de usuario:</strong> Estudiante</p>
        </div>

        <a href="#" class="dashboard-card action-card">
            <i class="fa-solid fa-book-open"></i>
            <h4>HISTORICO DE MATRICULA</h4>
            <p>Consulta tus cursos o secciones asignadas.</p>
        </a>

        <a href="<?= base_url('student/mis_pagos') ?>" class="dashboard-card action-card">
            <i class="fa-solid fa-receipt"></i>
            <h4>Mis pagos</h4>
            <p>Revisa tus pagos registrados en el sistema.</p>
        </a>

        <a href="#" class="dashboard-card action-card">
            <i class="fa-solid fa-user-gear"></i>
            <h4>Mi perfil</h4>
            <p>Consulta tu información personal registrada.</p>
        </a>

    </div>
</div>