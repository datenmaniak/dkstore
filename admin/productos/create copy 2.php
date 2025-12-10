<?php

    require_once __DIR__ . '/../../includes/app.php';

    use dkstore\Productos;
    use Intervention\Image\Drivers\Gd\Driver;
    use Intervention\Image\ImageManager;

    requireRole('admin'); // obliga a ser admin - requiere acceso como admin

    // Bases de datos
    $db = conectDB();
    Productos::setDB($db); // ← IMPORTANTE: Configura DB en la clase

    // Proveedores
    $sellers      = "SELECT * FROM proveedores";
    $sellers_list = mysqli_query($db, $sellers);

    // Categorias
    $categories      = "SELECT * FROM categorias";
    $categories_list = mysqli_query($db, $categories);

    // ubicacion de la imagenes
    $images_folder  = '../../uploads/';
    $no_image       = '../../assets/img/no-image.jpg';
    $imagen_mostrar = $no_image; // Por defecto
    $uploaded_image = '../../assets/img/arrow_12959560.png';

    /* // instanciar producto */
    $producto = new Productos(); // ← SIEMPRE existe

    // Arreglo con mensajes de errores
    $errores = Productos::getErrores();

    // echo "<h1>🔍 DEBUG COMPLETO</h1>";
    // echo "<pre>REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "</pre>";

    // Ejecutar despues que se envia el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Guardar en sesión ANTES de validar
        $_SESSION['form_data'] = $_POST;

        $producto = new Productos($_POST);

        // BEGIN:  procesar imagen
        // 1. Validar imagen
        // $validacion = validarImagen($_FILES['imagen']);
        // if (! $validacion['valida']) {
        //     $errores[] = "Error: " . $validacion['error'];
        // }

        // BEGIN procesar imagen
        // if ($validacion['valida']) {
        if ($_FILES['imagen']['tmp_name']) {

            $imgNewName = generarNombreUnico($_FILES['imagen']['name']);
            $imgManager = new ImageManager(Driver::class);
            $img        = $imgManager->read($_FILES['imagen']['tmp_name'])->cover(800, 600);

                                              // actualiza la referencia de la imagen
            $producto->setImage($imgNewName); // if (! $_FILES['imagen']) {
                                              //     $imagen_mostrar = $_FILES['tmp_name'];
                                              //     debugResult($imagen_mostrar, false);
                                              // }
            $imagen_mostrar = $imgNewName;

        }

        // obtener la ruta y guardar en el servidor (antes sin OOP)
        /* move_uploaded_file($imagen['tmp_name'], $images_folder . $imgNewName); */
        // $producto->imagen = $imgNewName; // Actualiza imagen

        // } else {
        //     echo "Error: " . $validacion['error'];
        // }
        // END procesar imagen
        // END - gestion / validacion imagen

        // 2. Validar campos
        $errores = $producto->validateEntry();

        // 3. Sanitizar/Validar
        if (! $producto->sanitize()) {
            $errores = array_merge($errores, $producto->getErrores());
        } else {
            unset($_SESSION['form_data']); // Limpiar al éxito
        }

        // 4. Si todo OK, procesar data
        if (empty($errores)) {

            // nuevo modo de almacenar en el servidor
            $img->save($images_folder . $imgNewName);

            if ($producto->guardarRecord()) {
                // header('Location: /admin/productos/');
                header('Location: /admin?result=1');
                exit;
            }
        }

        // Imagen preview para errores
        if (! empty($_FILES['imagen']['name'])) {
            $imagen_mostrar = $images_folder . $_FILES['imagen']['name'];
        }

    }

    includeTemplate('header');

    $hay_imagen_nueva = isset($_FILES['imagen']) &&
    $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
    ! empty($_FILES['imagen']['name']);

    if ($hay_imagen_nueva) {
        // 🎯 ESCENARIO 3: Usuario cargó imagen → Spinner "procesando"
        $imagen_mostrar  = $uploaded_image;
        $mostrar_spinner = true;
    } else {
        // 🎯 ESCENARIO 1 + 2: GET inicial O POST sin imagen → Por defecto
        $imagen_mostrar  = $no_image;
        $mostrar_spinner = false;
    }

?>



<main class="admin-layout">
    <h2>Registrar Producto</h2>

    <a href="/admin/index.php" class="btn-secondary btn-block-10 btn-left">Salir</a>

    <?php if ($errores): ?>
    <div class="notification-bar error medium center">
        <span class="icon">⚠️</span>
        <div class="message">
            <ul>
                <?php foreach ($errores as $error): ?>
                <li><?php echo htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <span class="close-btn">
            <img src="/assets/icons/close.svg" width="40px" height="40px" alt="">
            <!-- <i class="ri-close-large-line"></i></span> -->
    </div>
    <?php endif; ?>


    <form method="POST" class="form-productos" enctype="multipart/form-data">
        <!-- action="/admin/productos/create.php"> -->

        <label>Código (SKU):
            <input type="text" name="codigo_sku" value="<?php echo htmlspecialchars($producto->codigo_sku) ?>">
        </label>
        <label>Nombre del producto:
            <input type="text" name="nombre_producto"
                value="<?php echo htmlspecialchars($producto->nombre_producto) ?>">
        </label>
        <label>Precio:
            <input type="number" step="0.01" name="precio" value="<?php echo $producto->precio; ?>">
        </label>
        <div class="product-image">
            <label>Imagen (100 kb. max):
                <input type="file" name="imagen" accept="image/*">
            </label>
            <!-- Renderizado condicional -->
            <?php if ($mostrar_spinner): ?>
            <!-- Spinner activo sin imagen -->
            <!-- Contenedor del spinner tipo barra -->
            <div class="bar-spinner">
                <span class="spinner-message">Imagen cargada. Esperando para procesar...</span>
                <div class="spinner-bar"></div>
            </div>
            <?php else: ?>
            <!-- Imagen por defecto -->
            <img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="Imagen por defecto">
            <?php endif; ?>

        </div>
        <label>Descripción:
            <textarea name="descripcion" maxlength="255">
                <?php echo htmlspecialchars($producto->descripcion) ?></textarea>
            <small id="description_input_counter">0/255</small>
        </label>
        <label>Existencia:
            <input type="number" name="existencia" value="<?php echo $producto->existencia; ?>">
        </label>
        <label>Stock mínimo:
            <input type="number" name="stock_minimo" value="<?php echo $producto->stock_minimo; ?>">
        </label>
        <!-- TODO: aqui estuvo  el campo de activo  -->

        <!-- <label>ID Proveedor: <input type="number" name="proveedor_id"></label> -->
        <fieldset>
            <legend>Proveedor:</legend>
            <select name="proveedor_id" id="">
                <option value="">- Elija el proveedor - </option>
                <?php while ($seller = mysqli_fetch_assoc($sellers_list)): ?>
                <option value="<?php echo $seller['id'] ?>"
                    <?php echo $producto->proveedor_id == $seller['id'] ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($seller['empresa']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </fieldset>
        <fieldset>
            <legend>Categoría:</legend>
            <select name="categoria_id" id="">
                <option value="">- Elija categoría -</option>
                <?php while ($category = mysqli_fetch_assoc($categories_list)): ?>
                <option value="<?php echo $category['id'] ?>"
                    <?php echo $producto->categoria_id == $category['id'] ? 'selected' : '' ?>>
                    <?php echo htmlspecialchars($category['categoria'] . " - " . $category['descripcion']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </fieldset>
        <fieldset>
            <legend>Visible en el catálogo </legend>
            <label for="is_active" class="form-check form-switch">
                <input type="checkbox" id="is_active" name="is_active" class="form-check-input"
                    <?php echo($producto->is_active == 1) ? 'checked' : '' ?>>

                <span id="estado-notificacion" class="form-check-label">
                    <?php echo($producto->is_active == 1) ? ' activo' : ' inactivo'; ?>
                </span>
            </label>

        </fieldset>
        <div class="submit-block">
            <button type="submit" class="btn-primary">Enviar</button>
        </div>
    </form>
</main>

<?php includeTemplate('footer'); ?>