<h1>HOLA MUNDO</h1>
<h2>Este es un usuario administrador</h2>

<!-- Mostrar el tipo de usuario -->
<?php if (isset($usuario['tipo_usuario'])): ?>
    <p>Hola usuario: <strong><?= htmlspecialchars($usuario['tipo_usuario']); ?></strong></p>
<?php else: ?>
    <p>No se pudo obtener el tipo de usuario.</p>
<?php endif; ?>
