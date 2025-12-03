<?php
/* index.php   - Administrador  */

// echo "<pre>";
// var_dump($_GET);
// echo "</pre>";

// Importar la conexion a la DB


require '../includes/config/database.php';
require '../includes/functions.php';
includeTemplate('header');

$images_folder =  '../uploads/';

// Bases de datos
$db = conectDB();

// Write the query
$query = "SELECT * FROM productos WHERE eliminado = 0";

// Query Db
$sqlquery = mysqli_query($db, $query);

// Notificaciones
// $result = $_GET['result'] ?? null;
// Capturamos el parámetro "result" del query string
$result = isset($_GET['result']) ? (int)$_GET['result'] : 0;

// Procesa la eliminación del registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

    if ($id) {
        // 1. Obtener la imagen asociada al producto
        $stmt = $db->prepare("SELECT imagen FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($imagen);
        $stmt->fetch();
        $stmt->close();

        // 2. Si quieres eliminar físicamente la imagen del servidor
        if ($imagen) {
            $rutaImagen =  $images_folder . $imagen;
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen); // elimina el archivo
            } else {
                echo "No se encontró la imagen en: $rutaImagen";
            }
        }



        // 3. Marcar el producto como eliminado (soft delete) - Consulta preparada
        $stmt = $db->prepare("UPDATE productos SET eliminado = 1 WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Redirigir con mensaje de éxito
            header('Location: /admin?result=3');
            exit();
        } else {
            echo '<p class="alerta error">Error al eliminar el producto</p>';
        }
    } else {
        echo '<p class="alerta error">ID inválido</p>';
    }
}




// gestion/presentacion de la imagen
$images_folder = '../../uploads/';
$no_image = '../../assets/img/no-image.jpg';
$imagen_mostrar = $no_image; // Por defecto


?>



<main class="admin-layout">
    <h3>Panel de Administración</h3>
    <div class="panel-options">
        <a href="/" class="btn btn-secondary">Volver</a>
        <a href="/admin/productos/create.php" class="btn btn-primary">Agregar</a>
    </div>

    <?php
    if ($result !== 0) {

        switch ($result) {
            case 1:
                echo '<p class="alerta success">Producto registrado correctamente</p>';
                break;
            case 2:
                echo '<p class="alerta success">Producto actualizado correctamente</p>';
                break;
            case 3:
                echo '<p class="alerta success">Producto eliminado</p>';
                break;
            default:
                echo '<p class="alerta error">Desconocida</p>';
                break;
        }
    }
    ?>




    <div class="admin-container">
        <!-- 
        <aside class="sidebar">
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
                        <th>Catálogo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <!-- Mostrar resultados -->
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($sqlquery)): ?>
                        <tr>
                            <td>
                                <?php
                                if (!empty($item['imagen'])) {
                                    $imagen_mostrar = $images_folder . $item['imagen'];
                                } else {
                                    $imagen_mostrar = $no_image;
                                }

                                ?>

                                <img src="<?php echo $imagen_mostrar; ?> " alt="img product" class="img-table">

                            </td>
                            <td> <?php echo $item['id']; ?> </td>
                            <td> <?php echo $item['codigo_sku']; ?> </td>
                            <td> <?php echo $item['nombre_producto']; ?> </td>
                            <td> <?php echo $item['descripcion']; ?> </td>
                            <td>$<?php echo $item['precio']; ?> </td>
                            <td> <?php echo $item['existencia']; ?> </td>

                            <!-- // TODO: Work here -->
                            <td class="text-center">
                                <span class="status-badge <?php echo $item['activo'] ? 'active' : 'inactive'; ?>">
                                    <i
                                        class="<?php echo $item['activo'] ? 'ri-checkbox-line me-1' : 'ri-close-line me-1'; ?>"></i>
                                    <?php echo $item['activo'] ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>


                            <td class="actions-p">
                                <a href="/admin/productos/update.php?id=<?php echo $item['id']; ?> " class="btn-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25" height="25"
                                        fill="currentColor">
                                        <path
                                            d="M15.7279 9.57627L14.3137 8.16206L5 17.4758V18.89H6.41421L15.7279 9.57627ZM17.1421 8.16206L18.5563 6.74785L17.1421 5.33363L15.7279 6.74785L17.1421 8.16206ZM7.24264 20.89H3V16.6473L16.435 3.21231C16.8256 2.82179 17.4587 2.82179 17.8492 3.21231L20.6777 6.04074C21.0682 6.43126 21.0682 7.06443 20.6777 7.45495L7.24264 20.89Z">
                                        </path>
                                    </svg>


                                </a>
                            </td>
                            <td class="actions-p ">
                                <form method="POST" action="">
                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25" height="25"
                                            fill="currentColor">
                                            <path
                                                d="M7 4V2H17V4H22V6H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V6H2V4H7ZM6 6V20H18V6H6ZM9 9H11V17H9V9ZM13 9H15V17H13V9Z">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </td>


                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- // END: Container -->
    </div>
</main>



<!-- <script src="/build/js/bundle.js"></script> -->

<?php
// includeTemplate('footer');
includeTemplate('end-page');

// cerrar la conexion a la DB
mysqli_close($db);

?>