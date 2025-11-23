<?php
/* index.php   - Administrador  */

require '../includes/functions.php';
includeTemplate('header');


?>

<!-- <div class="admin-layout"> REVISAR -->


<main class="admin-layout">

    <div class="admin-grid">
        <aside class="sidebar">
            <h3>Menú de Administración</h3>
            <nav>
                <ul>
                    <li>
                        <a href="#">Categorías de Productos</a>
                        <ul class="submenu">
                            <li><a href="/admin/categorias/create.php">Crear</a></li>
                            <li><a href="/admin/categorias/read.php">Listar</a></li>
                            <li><a href="/admin/categorias/update.php">Actualizar</a></li>
                            <li><a href="/admin/categorias/delete.php">Eliminar</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Proveedores</a>
                        <ul class="submenu">
                            <li><a href="/admin/proveedores/create.php">Crear</a></li>
                            <li><a href="/admin/proveedores/read.php">Listar</a></li>
                            <li><a href="/admin/proveedores/update.php">Actualizar</a></li>
                            <li><a href="/admin/proveedores/delete.php">Eliminar</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">Productos</a>
                        <ul class="submenu">
                            <li><a href="/admin/products/create.php">Crear</a></li>
                            <li><a href="/admin/products/read.php">Listar</a></li>
                            <li><a href="/admin/products/update.php">Actualizar</a></li>
                            <li><a href="/admin/products/delete.php">Eliminar</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>
    </div>
    <h2>Procesamiento de datos</h2>
    <div class="">
    </div>data-process

</main>
<div class="return-home mb-10">
    <!-- <a href="/admin/" class="btn btn-secondary">Volver</a> -->

</div>

<!-- </div>  REVISAR -->
<!-- 
<main class="narrow-container mt-10">
    <h2>Administrador de la tienda</h2>

    <div class="return-home mb-10"></div>
    <a href="/admin/products/create.php"
        class="btn btn-secondary">Agregar Producto</a>
</main>
 -->
<script src="/build/js/bundle.js"></script>

<?php
// includeTemplate('footer');

?>