<?php
// proveedores.php - bloque CRUD de proveedores

// Aquí iría tu conexión a la BD
// $db = conectarDB();
// $query = "SELECT id, empresa, nombre, apellido FROM proveedores LIMIT 10";
// $result = mysqli_query($db, $query);
?>

<h2>Gestión de Proveedores</h2>

<div class="crud-actions">
    <button class="add">Agregar</button>
    <button class="update">Actualizar</button>
    <button class="delete">Eliminar</button>
</div>

<div class="crud-list">
    <h3>Listado breve</h3>
    <ul>
        <?php // ejemplo estático, luego lo reemplazas con datos reales 
        ?>
        <li>Proveedor 1 - Empresa X</li>
        <li>Proveedor 2 - Empresa Y</li>
        <li>Proveedor 3 - Empresa Z</li>
    </ul>
</div>