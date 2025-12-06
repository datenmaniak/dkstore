<?php

    require_once __DIR__ . '/../../includes/app.php';

    use dkstore\Productos;

    requireRole('admin'); // obliga a ser admin - requiere acceso como admin

    // Bases de datos
    $db = conectDB();
    // Productos::setDB($db); // ← IMPORTANTE: Configura DB en la clase

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

    /* // instanciar producto */
    $producto = new Productos(); // ← SIEMPRE existe

    // Arreglo con mensajes de errores
    $errores = [];

    // leer variables / mantiene, para evitar repetir la entrada de campos
    // en caso de errores
    $codigo_sku      = '';
    $nombre_producto = '';
    $precio          = '';
    $descripcion     = '';
    $existencia      = '';
    $stock_minimo    = '';
    $activo          = '';
    $proveedor_id    = '';
    $categoria_id    = '';

    echo "<h1>🔍 DEBUG COMPLETO</h1>";
    echo "<pre>REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "</pre>";

    // Ejecutar despues que se envia el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Begin Debug
        echo "<h2>📤 POST RECIBIDO:</h2>";
        echo "<pre>";
        var_dump($_POST);
        echo "</pre>";

        echo "<h2>🔧 CREANDO PRODUCTO:</h2>";
        $producto = new Productos($_POST);
        echo "<pre>";
        echo "nombre_producto: '" . $producto->nombre_producto . "'\n";
        echo "proveedor_id: '" . $producto->proveedor_id . "'\n";
        echo "</pre>";
        // END debug

        // Guardar en sesión ANTES de validar
        $_SESSION['form_data'] = $_POST;

        $producto = new Productos($_POST);

        // carga los campos
        // $codigo_sku      = mysqli_real_escape_string($db, $_POST['codigo_sku']);
        // $nombre_producto = mysqli_real_escape_string($db, $_POST['nombre_producto']);
        // $precio          = mysqli_real_escape_string($db, $_POST['precio']);
        // $descripcion     = mysqli_real_escape_string($db, $_POST['descripcion']);
        // $existencia      = mysqli_real_escape_string($db, $_POST['existencia']);
        // $stock_minimo    = mysqli_real_escape_string($db, $_POST['stock_minimo']);
        // $activo          = mysqli_real_escape_string($db, isset($db, $_POST['activo']) ? 1 : 0);
        // // $activo = mysqli_real_escape_string($db, $_POST['activo']);
        // $proveedor_id = mysqli_real_escape_string($db, $_POST['proveedor_id']);
        // $categoria_id = mysqli_real_escape_string($db, $_POST['categoria_id']);

        // 1. Validar imagen ANTES de sanitize()
        // imagen del producto
        $imagen     = $_FILES['imagen'];
        $validacion = validarImagen($_FILES['imagen']);
        if (! $validacion['valida']) {
            $errores[] = "Error: " . $validacion['error'];
        }
        if (! $imagen['name']) {
            $errores[] = 'La imagen del producto es obligatoria!';
        }

        // validacion
        if (! $nombre_producto) {
            $errores[] = 'Es obligatorio incluir un nombre';
        }
        if (! $precio) {
            $errores[] = 'Es necesario establecer un precio';
        }
        // // Validación longitud (ajusta $max según tu esquema MySQL, ej: VARCHAR(1000))
        // $longitud = mb_strlen($descripcion, 'UTF-8');
        // if ($longitud < 24) {
        //     $errores[] = 'La descripción debe tener al menos 24 caracteres.';
        // }
        // Llama validación de la clase
        [$esValido, $resultado] = $producto->validarDescripcion($descripcion);
        if (! $esValido) {
            $errores[] = $resultado;
        } else {
            $descripcion = $resultado; // Usa versión limpia
        }
        // if ($longitud > 1000) { // Reemplaza por longitud real de tu tabla
        //     $errores[] = 'La descripción no puede exceder 1000 caracteres.';
        // }
        if (! $existencia) {
            $errores[] = 'Es necesario incluir un existencia';
        }
        if (! $precio) {
            $errores[] = 'Es necesario incluir un precio';
        }
        if (! $stock_minimo) {
            $errores[] = 'Es necesario incluir un stock minimo del inventario';
        }
        if (! $proveedor_id) {
            $errores[] = 'Es obligatorio incluir el código del proveedor';
        }
        if (! $categoria_id) {
            $errores[] = 'Es necesario incluir el código de categoria';
        }

        // 2. Sanitizar/Validar (incluye longitud descripción)
        if (! $producto->sanitize()) {
            $errores = array_merge($errores, $producto->getErrores());
        } else {
            unset($_SESSION['form_data']); // Limpiar al éxito
        }

        // 3. Si todo OK, procesar imagen y guardar
        if (empty($errores)) {

            if ($validacion['valida']) {
                $imgNewName = generarNombreUnico($_FILES['imagen']['name']);

                move_uploaded_file($imagen['tmp_name'], $images_folder . $imgNewName);

                $producto->imagen = $imgNewName; // Actualiza imagen

            } else {
                echo "Error: " . $validacion['error'];
            }

            //         // insertar en la DB
            //         $query = "INSERT INTO productos (codigo_sku, nombre_producto,
            // precio, imagen, descripcion, existencia, stock_minimo,
            // proveedor_id, categoria_id )
            // VALUES ('$codigo_sku', '$nombre_producto',
            // '$precio', '$imgNewName', '$descripcion', '$existencia',
            //  $stock_minimo,
            // '$proveedor_id', '$categoria_id' )";

            // echo $query;
            // $res = mysqli_query($db, $query);

            // if (! $producto->sanitize()) {
            //     $errores = $producto->getErrores(); // Recoge errores de la clase
            //                                         // $descripcion = $_POST['descripcion'] ?? ''; // Repobla
            // } elseif ($producto->guardarRecord()) {
            //     // Sanitización OK, guarda

            //     debugResult($p);

            //     // header('Location: /admin/productos/');

            //     exit();
            // }

            if ($producto->guardarRecord()) {
                // header('Location: /admin/productos/');
                header('Location: /admin?result=1');
                exit;
            }
        }

        // if ($res) {
        //     // echo "Insertado correcto en la DB";

        //     // redireccionar a otra página para evitar repetidos registro duplicados
        //     // al 'enviar datos'

        //     // Query string
        //     header('Location: /admin?result=1');
        // }

        // Imagen preview para errores
        if (! empty($_FILES['imagen']['name'])) {
            $imagen_mostrar = $images_folder . $_FILES['imagen']['name'];
        }

    }

    includeTemplate('header');

    //                              // 🔥 AQUÍ → JUSTO DESPUÉS de if(empty($errores))
    // $imagen_mostrar = $no_image; // Por defecto

    // /* if (!empty($producto['imagen']) || !empty($imagen)) { */
    // if (! empty($imagen)) {
    //     $imagen_mostrar = $images_folder . $imagen;
    // } elseif (! empty($prod['imagen'])) {
    //     $imagen_mostrar = $images_folder . $prod['imagen'];
    // }

?>
<pre>
¿$_POST tiene "PRUEBA 123"? →                                 <?php echo $_POST['nombre_producto'] ?? 'NO' ?>
¿$producto tiene datos? →<?php echo $producto->nombre_producto ?>
</pre>



<main class="admin-layout mt-15">
    <h2>Registrar Producto</h2>

    <a href="/admin/index.php" class="btn btn-secondary mt-2">Salir</a>

    <!--
    <?php if ($errores): ?>
    <div class="alerta error">
        <ul><?php foreach ($errores as $error): ?>
            <li><?php echo htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?> -->



    <form method="POST" class="form-productos" enctype="multipart/form-data">
        <!-- action="/admin/productos/create.php"> -->


        <?php if ($errores): ?>
        <div class="alerta error">
            <ul><?php foreach ($errores as $error): ?>
                <li><?php echo htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <label>Código (SKU):
            <input type="text" name="codigo_sku" value="<?php echo $codigo_sku; ?>">
        </label>
        <label>Nombre del producto:
            <input type="text" name="nombre_producto" value="<?php echo $nombre_producto; ?>">
        </label>
        <label>Precio:
            <input type="number" step="0.01" name="precio" value="<?php echo $precio; ?>">
        </label>
        <div class="product-image">

            <label>Imagen (100 kb. max):
                <input type="file" name="imagen" accept="image/*">
            </label>
        </div>
        <img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="">
        <label>Descripción:
            <textarea name="descripcion"><?php echo $descripcion; ?></textarea>
        </label>
        <label>Existencia:
            <input type="number" name="existencia" value="<?php echo $existencia; ?>">
        </label>
        <label>Stock mínimo:
            <input type="number" name="stock_minimo" value="<?php echo $stock_minimo; ?>">
        </label>
        <!-- TODO: aqui estuvo  el campo de activo  -->

        <!-- <label>ID Proveedor: <input type="number" name="proveedor_id"></label> -->
        <fieldset>
            <legend>Proveedor:</legend>
            <select name="proveedor_id" id="">
                <option value="">- Elija el proveedor - </option>
                <?php while ($seller = mysqli_fetch_assoc($sellers_list)): ?>
                <option<?php echo $proveedor_id === $seller['id'] ? 'selected' : ''; ?>
                    value="<?php echo $seller['id']; ?>">
                    <?php echo $seller['empresa'] . " - " . $seller['contact_name']; ?>
                    </option>
                    <?php endwhile; ?>
                    <!-- <option value="1">Global PC</option>
                <option value="2">datenmaniak</option> -->
            </select>
        </fieldset>
        <fieldset>
            <legend>Categoría:</legend>
            <select name="categoria_id" id="">
                <option value="">- Elija categoría -</option>
                <?php while ($category = mysqli_fetch_assoc($categories_list)): ?>
                <option<?php echo $categoria_id === $category['id'] ? 'selected' : ''; ?>
                    value="<?php echo $category['id']; ?>">
                    <?php echo $category['categoria'] . " - " . $category['descripcion']; ?>
                    </option>
                    <?php endwhile; ?>
            </select>
        </fieldset>
        <fieldset>
            <legend>Visible en el catálogo </legend>
            <label for="activo" class="form-check form-switch">
                <input type="checkbox" id="activo" name="activo" class="form-check-input" value="1"
                    <?php echo($activo === 1) ? 'checked' : ''; ?>>
                <span id="estado-texto" class="form-check-label">
                    <?php echo($activo === 1) ? ' activo' : ' inactivo'; ?>
                </span>
            </label>

        </fieldset>



        <div class="submit-block">

            <button type="submit" class="btn-block-size  ">Enviar</button>
        </div>
    </form>
    <div class="warning-bar"></div>


</main>


<?php includeTemplate('footer'); ?>