<?php

// echo "<pre>";
// var_dump($_GET);
// echo "</pre>";

// get the record and validate the URL
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /admin');
}

// var_dump($id);



require '../../includes/config/database.php';
require  '../../includes/functions.php';

$images_folder = '../../uploads/';
$no_image = '../../assets/img/no-image.jpg';

// Bases de datos
$db = conectDB();
// var_dump($db);

// 1. OBTENER producto de BD
// Search the product by ID and get its data
$query = "SELECT * FROM productos WHERE id = $id ";
$productquery = mysqli_query($db, $query);
$prod = mysqli_fetch_assoc($productquery);

// echo "<pre>";
// var_dump($prod);
// echo "</pre>";


// Proveedores
$sellers = "SELECT * FROM proveedores";
$sellers_list = mysqli_query($db, $sellers);

// Categorias
$categories = "SELECT * FROM categorias";
$categories_list = mysqli_query($db, $categories);

// Arreglo con mensajes de errores
$errores = [];


/* --- Este bloque, así lo explicó/codificó el profesor --- */
// BEGIN 
// leer variables / mantiene, para evitar repetir la entrada de campos
// en caso de errores
// $codigo_sku = $prod['codigo_sku'];
// $nombre_producto = $prod['nombre_producto'];
// $precio = $prod['precio'];
// $descripcion = $prod['descripcion'];
// $existencia = $prod['existencia'];
// $stock_minimo = $prod['stock_minimo'];
// $activo = $prod['activo'];
// $proveedor_id = $prod['proveedor_id'];
// $categoria_id = $prod['categoria_id'];
// $imagen = $prod['imagen'];
// $imagen = $_FILES['imagen'];
// END

/* --- de este modo yo implementé el bloque anterior -- */
// 2. ARRAY ÚNICO con TODOS los campos inicializados
$producto = [
    'codigo_sku' => $prod['codigo_sku'] ?? '',
    'nombre_producto' => $prod['nombre_producto'] ?? '',
    'precio' => $prod['precio'] ?? 0,
    'descripcion' => $prod['descripcion'] ?? '',
    'existencia' => $prod['existencia'] ?? 0,
    'stock_minimo' => $prod['stock_minimo'] ?? 0,
    'activo' => $prod['activo'] ?? 0,
    'proveedor_id' => $prod['proveedor_id'] ?? 0,
    'categoria_id' => $prod['categoria_id'] ?? 0,
    'imagen' => $prod['imagen'] ?? ''
];



// Ejecutar despues que se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    /* --- Este bloque corresponde a la clase del profesor --- */
    // BEGIN
    /* // --- sanitizar / saneamiento de los campos antes de procesarlos --- */
    // $codigo_sku = mysqli_real_escape_string($db,  $_POST['codigo_sku']);
    // $nombre_producto = mysqli_real_escape_string($db,  $_POST['nombre_producto']);
    // $precio = mysqli_real_escape_string($db,  $_POST['precio']);
    // $descripcion = mysqli_real_escape_string($db,  $_POST['descripcion']);
    // $existencia = mysqli_real_escape_string($db,  $_POST['existencia']);
    // $stock_minimo = mysqli_real_escape_string($db,  $_POST['stock_minimo']);
    // $activo       = mysqli_real_escape_string($db, isset($db, $_POST['activo']) ? 1 : 0);
    // // $activo = mysqli_real_escape_string($db, $_POST['activo']);
    // $proveedor_id = mysqli_real_escape_string($db,  $_POST['proveedor_id']);
    // $categoria_id = mysqli_real_escape_string($db,  $_POST['categoria_id']);

    // imagen del producto | asignar file a una variable
    // $imagen = $_FILES['imagen'];
    // END

    // 1. FUNCIONES de sanitización por tipo
    $producto_limpio = [
        'codigo_sku' => trim($_POST['codigo_sku'] ?? '') ?: $producto['codigo_sku'],
        'nombre_producto' => htmlspecialchars(trim($_POST['nombre_producto'] ?? '')),
        'precio' => (float)($_POST['precio'] ?? 0),
        'descripcion' => htmlspecialchars(trim($_POST['descripcion'] ?? '')),
        'existencia' => (int)($_POST['existencia'] ?? 0),
        'stock_minimo' => (int)($_POST['stock_minimo'] ?? 0),
        'activo' => (int)($_POST['activo'] ?? 0),
        'proveedor_id' => (int)($_POST['proveedor_id'] ?? 0),
        'categoria_id' => (int)($_POST['categoria_id'] ?? 0)
    ];

    // 2. Validaciones de negocio
    if (strlen($producto_limpio['codigo_sku']) > 32) {
        $errores[] = "SKU muy largo";
    }
    if (strlen($producto_limpio['nombre_producto']) < 3) {
        $errores[] = 'Es necesario asignar un nombre al menos de 16 caracteres';
    }
    if ($producto_limpio['precio'] < 0) {
        $errores[] = "Precio inválido";
    }
    if (strlen($producto_limpio['descripcion']) < 32) {
        $errores[] = 'La descripción debe contener al menos 32 caracteres';
    }
    if ($producto_limpio['existencia'] < 1) {
        $errores[] = "Se recomienda asignar al menos 1 para la cantidad de existencia";
    }
    if ($producto_limpio['stock_minimo'] < 1) {
        $errores[] = "Se recomienda asignar al menos 1 para el stock mínimo";
    }

    if (!$producto_limpio['proveedor_id']) {
        $errores[] = 'Es obligatorio incluir el código del proveedor';
    }
    if (!$producto_limpio['categoria_id']) {
        $errores[] = 'Es obligatorio incluir el código de la categoría';
    }

    // if (!$imagen['name']) {
    //     $errores[] = 'La imagen del producto es obligatoria!';
    // }

    // Si hubo cambio de imagen
    // if ($imagen['name']) {
    //     $validacion = validarImagen($_FILES['imagen']);
    //     if (!$validacion['valida']) {
    //         $errores[] = "Error: " . $validacion['error'];
    //     } else {
    //         // verifica si existe la imagen anterior antes de eliminarla
    //         $ruta_imagen_anterior = $images_folder . $prod['imagen'];
    //         if (!empty($prod['imagen']) && file_exists($ruta_imagen_anterior)) {
    //             if (!unlink($ruta_imagen_anterior)) {
    //                 $errores[] = "Error al eliminar la imagen anterior.";
    //             }
    //         }
    //     }
    // }



    // Detectar si realmente se subió una imagen nueva
    $hay_nueva_imagen = !empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK;

    $imgNewName = '';
    if ($hay_nueva_imagen) {
        $validacion = validarImagen($_FILES['imagen']);
        if (!$validacion['valida']) {
            $errores[] = "Error: " . $validacion['error'];
        } else {
            // Solo aquí procesamos la imagen NUEVA y eliminamos la anterior
            $imgNewName = generarNombreUnico($_FILES['imagen']['name']);
            move_uploaded_file($imagen['tmp_name'], $images_folder . $imgNewName);

            // $imgNewName = $validacion['nombre_archivo']; // Asumiendo que tu función lo genera


            // Eliminar imagen anterior SOLO si existe
            $ruta_imagen_anterior = $images_folder . $prod['imagen'];
            if (!empty($prod['imagen']) && file_exists($ruta_imagen_anterior)) {
                unlink($ruta_imagen_anterior);
            }

            // Aquí actualizarías SQL con: imagen = '$imgNewName'
            $update_imagen = true;
        }
    } else {
        // NO hay nueva imagen: mantener la imagen actual en SQL
        $update_imagen = false;
    }

    // Determinar qué imagen mostrar (siempre al final)
    $imagen_mostrar = '';
    if (isset($imgNewName)) {
        $imagen_mostrar = $images_folder . $imgNewName;
    } elseif (!empty($prod['imagen'])) {
        $imagen_mostrar = $images_folder . $prod['imagen'];
    } else {
        $imagen_mostrar = $no_image;
        // $imagen_mostrar = $images_folder . 'default.jpg';
    }



    // echo "<pre>";
    // var_dump($errores);
    // echo "</pre>";


    // var_dump($imagen);

    // exit;

    if (empty($errores)) {



        // if (isset($update_imagen) && $update_imagen) {

        //     if ($validacion['valida']) {
        //         $imgNewName = generarNombreUnico($_FILES['imagen']['name']);

        //         move_uploaded_file($imagen['tmp_name'], $images_folder . $imgNewName);

        //         // move_uploaded_file(...)
        //     } else {
        //         echo "Error: " . $validacion['error'];
        //     }
        // }


        // Agregar imagen SOLO si hay nueva imagen válida
        if ($hay_nueva_imagen && $validacion['valida']) {
            $imgNewName = $validacion['nombre_archivo'];
            $campos['imagen'] = $imgNewName;
        }


        // update record 
        $query = "UPDATE productos SET codigo_sku = '$codigo_sku', imagen = '$imgNewName'  ,nombre_producto = '$nombre_producto', precio = $precio , descripcion =  '$descripcion', existencia = $existencia , stock_minimo = $stock_minimo, proveedor_id = $proveedor_id, categoria_id = $categoria_id WHERE id = $id ";


        $res = mysqli_query($db, $query);

        if ($res) {
            // echo "Insertado correcto en la DB";

            // redireccionar a otra página para evitar repetidos registro duplicados 
            // al 'enviar datos'

            // Query string
            header('Location: /admin?result=2');
            exit();
        }
    }

    // END - Form processing
}
includeTemplate('header');

?>

<main class="admin-layout  mt-15">
    <!-- <main class="add-products-container mt-15 basic-container"> -->
    <h2>Actualizar Producto</h2>

    <a href="/admin/" class="btn btn-secondary mt-2">Volver</a>


    <?php foreach ($errores as $error): ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>


    <!-- action="/admin/productos/update.php"> -->

    <form method="POST" class="form-productos" enctype="multipart/form-data">
        <label>Código (SKU):
            <input
                type="text"
                name="codigo_sku"
                value="<?php echo $codigo_sku; ?>">
        </label>
        <label>Nombre del producto:
            <input type="text"
                name="nombre_producto"
                value="<?php echo $nombre_producto; ?>">
        </label>
        <label>Precio:
            <input type="number"
                step="0.01"
                name="precio"
                value="<?php echo $precio; ?>">
        </label>
        <label>Imagen (100 kb. max):
            <input type="file"
                name="imagen"
                accept="image/*">
        </label>

        <!-- imagen -->
        <!-- <p><?php echo $images_folder . $imagen; ?></p> -->
        <!-- <img src="<?php echo $images_folder . $imgNewName; ?>" class="img-prod" alt=""> -->
        <img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="">


        <label>Descripción:
            <textarea
                name="descripcion"><?php echo $descripcion; ?></textarea>
        </label>
        <label>Existencia:
            <input
                type="number"
                name="existencia"
                value="<?php echo $existencia; ?>">
        </label>
        <label>Stock mínimo:
            <input type="number"
                name="stock_minimo"
                value="<?php echo $stock_minimo; ?>">
        </label>
        <label>Activo:
            <input
                type="checkbox"
                name="activo" checked
                value="<?php echo $activo; ?>">
        </label>
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


        <button type="submit" class="btn btn-block-50 ">Enviar los cambios</button>
    </form>
    <div class="warning-bar"></div>



</main>

<script src="/build/js/bundle.js"></script>

<?php
// includeTemplate('footer');

?>