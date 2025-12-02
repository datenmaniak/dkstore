<?php

require '../../includes/config/database.php';
require  '../../includes/functions.php';



// Bases de datos
$db = conectDB();
// var_dump($db);

// Proveedores
$sellers = "SELECT * FROM proveedores";
$sellers_list = mysqli_query($db, $sellers);

// Categorias
$categories = "SELECT * FROM categorias";
$categories_list = mysqli_query($db, $categories);

// ubicacion de la imagenes
$images_folder = '../../uploads/';
$no_image = '../../assets/img/no-image.jpg';
$imagen_mostrar = $no_image; // Por defecto

// Arreglo con mensajes de errores
$errores = [];

// leer variables / mantiene, para evitar repetir la entrada de campos
// en caso de errores
$codigo_sku = '';
$nombre_producto = '';
$precio = '';
$descripcion = '';
$existencia = '';
$stock_minimo = '';
$activo = 'true';
$proveedor_id = '';
$categoria_id = '';

// sanitizar / saneamiento de los campos


// Ejecutar despues que se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";


    // echo "<pre>";
    // var_dump($_FILES);
    // echo "</pre>";



    // carga los campos
    $codigo_sku = mysqli_real_escape_string($db,  $_POST['codigo_sku']);
    $nombre_producto = mysqli_real_escape_string($db,  $_POST['nombre_producto']);
    $precio = mysqli_real_escape_string($db,  $_POST['precio']);
    $descripcion = mysqli_real_escape_string($db,  $_POST['descripcion']);
    $existencia = mysqli_real_escape_string($db,  $_POST['existencia']);
    $stock_minimo = mysqli_real_escape_string($db,  $_POST['stock_minimo']);
    $activo       = mysqli_real_escape_string($db, isset($db, $_POST['activo']) ? 1 : 0);
    // $activo = mysqli_real_escape_string($db, $_POST['activo']);
    $proveedor_id = mysqli_real_escape_string($db,  $_POST['proveedor_id']);
    $categoria_id = mysqli_real_escape_string($db,  $_POST['categoria_id']);

    // imagen del producto | asignar file a una variable
    $imagen = $_FILES['imagen'];
    // var_dump($imagen['name']);
    // var_dump($imagen);

    // exit;


    // validacion
    if (!$nombre_producto) {
        $errores[] = 'Es obligatorio incluir un nombre';
    }
    if (!$precio) {
        $errores[] = 'Es necesario establecer un precio';
    }
    if (strlen($descripcion) < 24) {
        $errores[] = 'La descripción debe contener al menos 24 caracteres';
    }
    if (!$existencia) {
        $errores[] = 'Es necesario incluir un existencia';
    }
    if (!$precio) {
        $errores[] = 'Es necesario incluir un precio';
    }
    if (!$stock_minimo) {
        $errores[] = 'Es necesario incluir un stock minimo del inventario';
    }
    if (!$proveedor_id) {
        $errores[] = 'Es obligatorio incluir el código del proveedor';
    }
    if (!$categoria_id) {
        $errores[] = 'Es necesario incluir el código de categoria';
    }
    if (!$imagen['name']) {
        $errores[] = 'La imagen del producto es obligatoria!';
    }

    $validacion = validarImagen($_FILES['imagen']);
    if (!$validacion['valida']) {
        $errores[] = "Error: " . $validacion['error'];
    }

    // validar tamano de la imagen
    // $maxsize = 1024 * 100;
    // if ($imagen['size'] > 100000) {
    //     $errores[] = 'El archivo es demasiado grande. El tamaño máximo permitido es  100 kb';
    // }

    // TODO:  evaluar uso de funcion externa

    // if (
    //     isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
    //     !empty($_FILES['imagen']['tmp_name']) && is_uploaded_file($_FILES['imagen']['tmp_name'])
    // ) {

    //     $maxSizeServer = ini_get('upload_max_filesize'); // Ej: "2M"
    //     $maxSizeApp = 100 * 1024; // 100 KB en bytes

    //     if ($_FILES['imagen']['size'] > $maxSizeApp) {
    //         $errores[] = "Error: La imagen excede los 100 KB permitidos.";
    //     } elseif ($_FILES['imagen']['size'] === 0) {
    //         $errores[] = "Error: La imagen es demasiado grande para el servidor (supera $maxSizeServer).";
    //     }
    //     // else {
    //     //     // Mover y procesar: move_uploaded_file($_FILES['imagen']['tmp_name'], $destino);
    //     //     $errores[] = "Imagen válida y procesada.";
    //     // }
    // }
    // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";


    // var_dump($imagen);

    // exit;

    if (empty($errores)) {


        if ($validacion['valida']) {
            $imgNewName = generarNombreUnico($_FILES['imagen']['name']);

            move_uploaded_file($imagen['tmp_name'], $images_folder . $imgNewName);

            // move_uploaded_file(...)
        } else {
            echo "Error: " . $validacion['error'];
        }


        // insertar en la DB
        $query = "INSERT INTO productos (codigo_sku, nombre_producto, 
    precio, imagen, descripcion, existencia, stock_minimo, 
    proveedor_id, categoria_id ) 
    VALUES ('$codigo_sku', '$nombre_producto', 
    '$precio', '$imgNewName', '$descripcion', '$existencia',
     $stock_minimo,  
    '$proveedor_id', '$categoria_id' )";

        // echo $query;
        $res = mysqli_query($db, $query);

        if ($res) {
            // echo "Insertado correcto en la DB";

            // redireccionar a otra página para evitar repetidos registro duplicados 
            // al 'enviar datos'

            // Query string
            header('Location: /admin?result=1');
        }
    }

    // END - Form processing
}
includeTemplate('header');



// 🔥 AQUÍ → JUSTO DESPUÉS de if(empty($errores))
$imagen_mostrar = $no_image; // Por defecto

/* if (!empty($producto['imagen']) || !empty($imagen)) { */
if (!empty($imagen)) {
    $imagen_mostrar = $images_folder . $imagen;
} elseif (!empty($prod['imagen'])) {
    $imagen_mostrar = $images_folder . $prod['imagen'];
}


?>

<!-- <main class="add-products-container mt-15 basic-container"> -->
<main class="admin-layout mt-15">
    <h2>Registrar Producto</h2>

    <a href="/admin/" class="btn btn-secondary mt-2">Volver</a>


    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>



    <form method="POST" class="form-productos" enctype="multipart/form-data" action="/admin/productos/create.php">
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
                    <option <?php echo $proveedor_id === $seller['id'] ? 'selected' : ''; ?>
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
                    <option <?php echo $categoria_id === $category['id'] ? 'selected' : ''; ?>
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
                    <?php echo ($activo === 1) ? 'checked' : ''; ?>>
                <span id="estado-texto" class="form-check-label">
                    <?php echo ($activo === 1) ? ' activo' : ' inactivo'; ?>
                </span>
            </label>

        </fieldset>



        <div class="submit-block">

            <button type="submit" class="btn-block-size  ">Enviar</button>
        </div>
    </form>
    <div class="warning-bar"></div>


</main>
<script src="/build/js/bundle.js"></script>


<?php
/* includeTemplate('footer'); */

?>