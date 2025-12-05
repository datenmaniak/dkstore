<?php

// before
// require '../includes/config/database.php';
// require '../includes/functions.php';

require_once __DIR__ . '../../includes/app.php';

includeTemplate('header');
?>

<main class="no-access">
    <section class="no-access-container">
        <h2>Acceso Restringido</h2>
        <p>No tienes permisos para acceder a esta sección.</p>
        <a href="/admin/login.php" class="btn ">Volver al inicio de sesión</a>
    </section>
</main>

<?php includeTemplate('footer'); ?>