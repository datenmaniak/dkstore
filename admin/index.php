<?php
/* index.php   - Administrador  */

// echo "<pre>";
// var_dump($_GET);
// echo "</pre>";


$result = $_GET['result'] ?? false;

require '../includes/functions.php';
includeTemplate('header');


?>

<!-- <div class="admin-layout"> REVISAR -->


<main class="admin-layout">
    <h3>Menú de Administración</h3>
    <?php if ($result): ?>
        <p class="alerta success">Producto registrado correctamente</p>
    <?php endif; ?>

    <div class="admin-container">

        <aside class="sidebar">
            <h3>Dashboard</h3>
            <ul>
                <li><a href="/admin/productos/create.php">Crear</a></li>
                <li><a href="/admin/productos/read.php">Listar</a></li>
                <li><a href="/admin/productos/delete.php">Eliminar</a></li>
            </ul>

        </aside>

        <div class="admin-content">
            <h3>Resultados</h3>
            <!-- // Aqui los resultados -->
        </div>

        <!-- // END: Container -->
    </div>
</main>



<script src="/build/js/bundle.js"></script>

<?php
// includeTemplate('footer');

?>