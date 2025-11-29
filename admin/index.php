<?php
/* index.php   - Administrador  */

// echo "<pre>";
// var_dump($_GET);
// echo "</pre>";

// Importar la conexion a la DB

use Pdo\Mysql;

require '../includes/config/database.php';
// require  '../includes/functions.php';

// Bases de datos
$db = conectDB();

// Write the query
$query = "SELECT * FROM productos";

// Query Db
$sqlquery = mysqli_query($db, $query);


$result = $_GET['result'] ?? false;

require '../includes/functions.php';
includeTemplate('header');


?>



<main class="admin-layout">
    <h3>Panel de Administración</h3>
    <div class="panel-options">
        <a href="/" class="btn btn-secondary">Volver</a>
        <a href="/admin/productos/create.php" class="btn btn-primary">Agregar</a>
    </div>
    <?php if ($result): ?>
        <p class="alerta success">Producto registrado correctamente</p>
    <?php endif; ?>


    <div class="admin-container">

        <!-- <aside class="sidebar">
            <h3>Dashboard</h3>
            <ul>
                <li><a href="/admin/productos/create.php">Crear</a></li>
                <li><a href="/admin/productos/read.php">Listar</a></li>
                <li><a href="/admin/productos/delete.php">Eliminar</a></li>
            </ul>

        </aside> -->

        <div class="admin-content mb-10">
            <h3>Productos</h3>
            <!-- // Aqui los resultados -->

            <table class="products-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Existencia</th>
                        <th>Activo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Mostrar resultados -->
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($sqlquery)): ?>
                        <tr>
                            <td><img
                                    src="/uploads/<?php echo $item['imagen']; ?> "
                                    alt="img product"
                                    class="img-table">

                            </td>
                            <td> <?php echo $item['id']; ?> </td>
                            <td> <?php echo $item['codigo_sku']; ?> </td>
                            <td> <?php echo $item['nombre_producto']; ?> </td>
                            <td> <?php echo $item['descripcion']; ?> </td>
                            <td>$ <?php echo $item['precio']; ?> </td>
                            <td> <?php echo $item['existencia']; ?> </td>
                            <td><?php if ($item['activo'] == 1): ?>
                                    <i class="ri-checkbox-line" style="color: green;"></i>
                                <?php else: ?>
                                    <i class="ri-close-circle-line" style="color: red;"></i>
                                <?php endif; ?>
                            </td>
                            <td class="actions-p">
                                <a href="/admin/productos/update.php?id=<?php echo $item['id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                                        <path d="M15.7279 9.57627L14.3137 8.16206L5 17.4758V18.89H6.41421L15.7279 9.57627ZM17.1421 8.16206L18.5563 6.74785L17.1421 5.33363L15.7279 6.74785L17.1421 8.16206ZM7.24264 20.89H3V16.6473L16.435 3.21231C16.8256 2.82179 17.4587 2.82179 17.8492 3.21231L20.6777 6.04074C21.0682 6.43126 21.0682 7.06443 20.6777 7.45495L7.24264 20.89Z"></path>
                                    </svg>


                                </a>
                                <a href="/admin/productos/remove.php?id=<?php echo $item['id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="28" height="28" fill="currentColor">
                                        <path d="M7 4V2H17V4H22V6H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V6H2V4H7ZM6 6V20H18V6H6ZM9 9H11V17H9V9ZM13 9H15V17H13V9Z"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- // END: Container -->
    </div>
</main>



<script src="/build/js/bundle.js"></script>

<?php
// includeTemplate('footer');

// cerrar la conexion a la DB
mysqli_close($db);

?>