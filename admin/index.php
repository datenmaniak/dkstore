<?php
    /* index.php   - Administrador  */

    // Se simplifica
    require_once __DIR__ . '../../includes/app.php';

    use dkstore\Productos;

    // Valida la sesión
    // session_start();

    /* requireLogin(); // obliga a estar logueado */
    requireRole('admin'); // obliga a ser admin

    includeTemplate('header');

    $images_folder = '../uploads/';

    // Método para obtener todos los productos
    $productos = Productos::getAll();
    /*
    // REMOVE
    // Bases de datos
    $db = conectDB();

    // Write the query
    $query = "SELECT * FROM productos WHERE eliminado = 0";

    // Query Db
    $sqlquery = mysqli_query($db, $query);
    //  REMOVE
 */
    // Notificaciones
    $result = isset($_GET['result']) ? (int) $_GET['result'] : 0;

    // Procesa la eliminación del registro
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        // procesar la eliminacion de registro
        if ($id) {
            // 1. Obtener la imagen asociada al producto
            //     /* color de fondo sólido */
            $stmt = $db->prepare("SELECT imagen FROM productos WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($imagen);
            $stmt->fetch();
            $stmt->close();

            // 2. Si quieres eliminar físicamente la imagen del servidor
            if ($imagen) {
                $rutaImagen = $images_folder . $imagen;
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
                echo '<p  class="notification-bar error medium center">Error durante la eliminación</p>';

            }
        } else {
            echo '<p class="alerta error">ID inválido</p>';
        }
    }

    // gestion/presentacion de la imagen
    $no_image       = '../../assets/img/no-image.jpg';
    $imagen_mostrar = $no_image; // Por defecto

?>



<main class="admin-layout ">
    <h3>Panel de Administración - Productos</h3>
    <a href="/admin/productos/create.php" class="btn-primary btn-block-10 btn-right">Agregar</a>
    <div class="panel-options ">
        <!-- <a href="/admin/logout.php" class="btn-secondary">Cerrar sesión</a> -->
    </div>
    <!-- <h3>Productos</h3> -->

    <?php
        if ($result !== 0) {

            switch ($result) {
                case 1:
                    echo '<p  class="notification-bar success medium center hide">Producto registrado correctamente</p>';
                    break;
                case 2:
                    echo '<p  class="notification-bar success medium center hide">Producto actualizado correctamente</p>';
                    break;
                case 3:
                    echo '<p  class="notification-bar warning medium center hide">Producto eliminado correctamente</p>';
                    break;
                case 9:
                    echo '<p  class="notification-bar warning medium center hide">Error: Notifique al administrador de sistemas</p>';
                    break;
                default:
                    echo '<p  class="notification-bar warning medium center hide">Acción desconocida o no registrada</p>';

                    break;
            }
        }
    ?>

    <div class="admin-container ">
        <!--
            <aside class="sidebar">
                <h3>Dashboard</h3>
                <ul>
                    <li><a href="/admin/productos/create.php">Crear</a></li>
                    <li><a href="/admin/productos/read.php">Listar</a></li>
                    <li><a href="/admin/productos/delete.php">Eliminar</a></li>
                </ul>

            </aside> -->

        <div class="admin-content mb-10 catalog-scroll">

            <!-- Cabecera fija como bloque independiente -->
            <div class="table-header">
                <div>Imagen</div>
                <div>ID</div>
                <div>SKU</div>
                <div>Nombre</div>
                <div>Descripción</div>
                <div>Precio</div>
                <div>Cant.</div>
                <div>Stock</div>
                <div>Catálogo</div>
                <div>Acciones</div>
            </div>
            <!-- // Aqui los resultados -->
            <!-- <h3>Productos</h3> -->
            <div class="table-body">

                <table class="products-table">
                    <thead class="sr-only">
                        <tr>
                            <th>Imagen</th>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Cant</th>
                            <th>Catálogo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <!-- Mostrar resultados -->
                    <tbody>
                        <?php foreach ($productos as $item): ?>
                        <tr>
                            <td>
                                <?php
                                    if (! empty($item->imagen)) {
                                        $imagen_mostrar = $images_folder . $item->imagen;
                                    } else {
                                        $imagen_mostrar = $no_image;
                                    }

                                ?>

                                <img src="<?php echo $imagen_mostrar; ?> " alt="img product" class="img-table">

                            </td>
                            <td><?php echo $item->id; ?> </td>
                            <td><?php echo $item->codigo_sku; ?> </td>
                            <td><?php echo $item->nombre_producto; ?> </td>
                            <td><?php echo $item->descripcion; ?> </td>
                            <td>$<?php echo $item->precio; ?> </td>
                            <td><?php echo $item->stock_minimo; ?> </td>
                            <td><?php echo $item->existencia; ?> </td>

                            <!-- // TODO: Work here -->
                            <td class="text-center active-in-catalog">
                                <span class="status-badge
                                <?php echo $item->is_active ? 'active' : 'inactive'; ?>">
                                    <i
                                        class="<?php echo $item->is_active ? 'ri-checkbox-line me-1' : 'ri-close-line me-1'; ?>"></i>
                                    <?php echo $item->is_active ? 'Activo' : 'Inactivo'; ?>
                                </span>
                            </td>


                            <td class="actions-p">
                                <a href="/admin/productos/update.php?id=<?php echo $item->id; ?> "
                                    class="btn-icon edit-icon">
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
                                    <input type="hidden" name="id" value="<?php echo $item->id; ?>">
                                    <button type="submit" class="btn-icon delete-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="25"
                                            height="25" fill="currentColor">
                                            <path
                                                d="M7 4V2H17V4H22V6H20V21C20 21.5523 19.5523 22 19 22H5C4.44772 22 4 21.5523 4 21V6H2V4H7ZM6 6V20H18V6H6ZM9 9H11V17H9V9ZM13 9H15V17H13V9Z">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            </td>


                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- // END: Container -->
    </div>
</main>




<?php

    $templates_to_load = ['footer', 'scripts', 'end-page'];

    foreach ($templates_to_load as $tpl) {
        if (! includeTemplate($tpl)) {
            showNotification("Plantilla no existe o no autorizada: " . htmlspecialchars($tpl));
        }
    }

?>