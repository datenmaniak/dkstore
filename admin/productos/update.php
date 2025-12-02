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
require '../../includes/functions.php';
includeTemplate('header');


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
$sellers = 'SELECT * FROM proveedores';
$sellers_list = mysqli_query($db, $sellers);

// Categorias
$categories = 'SELECT * FROM categorias';
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

// var_dump($producto['imagen']);
// echo "<br>";
// var_dump($prod['imagen']);

// exit();

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
    $producto = [
        'codigo_sku' => trim($_POST['codigo_sku'] ?? '') ?: $producto['codigo_sku'],
        'nombre_producto' => htmlspecialchars(trim($_POST['nombre_producto'] ?? '')),
        'precio' => (float) ($_POST['precio'] ?? 0),
        'descripcion' => htmlspecialchars(trim($_POST['descripcion'] ?? '')),
        'existencia' => (int) ($_POST['existencia'] ?? 0),
        'stock_minimo' => (int) ($_POST['stock_minimo'] ?? 0),
        'activo' => (int) ($_POST['activo'] ?? 0),
        'proveedor_id' => (int) ($_POST['proveedor_id'] ?? 0),
        'categoria_id' => (int) ($_POST['categoria_id'] ?? 0)

    ];

    // 2. Validaciones de negocio
    if (strlen($producto['codigo_sku']) > 32) {
        $errores[] = 'SKU muy largo';
    }
    if (strlen($producto['nombre_producto']) < 3) {
        $errores[] = 'Es necesario asignar un nombre al menos de 16 caracteres';
    }
    if ($producto['precio'] < 0) {
        $errores[] = 'Precio inválido';
    }
    if (strlen($producto['descripcion']) < 32) {
        $errores[] = 'La descripción debe contener al menos 32 caracteres';
    }
    if ($producto['existencia'] < 1) {
        $errores[] = 'Se recomienda asignar al menos 1 para la cantidad de existencia';
    }
    if ($producto['stock_minimo'] < 1) {
        $errores[] = 'Se recomienda asignar al menos 1 para el stock mínimo';
    }

    if (!$producto['proveedor_id']) {
        $errores[] = 'Es obligatorio incluir el código del proveedor';
    }
    if (!$producto['categoria_id']) {
        $errores[] = 'Es obligatorio incluir el código de la categoría';
    }

    // TODO: Evaluar porque se repite y si es necesario este bloque
    // Determinar qué imagen mostrar (siempre al final)
    // $imagen_mostrar = '';
    // if (isset($imgNewName)) {
    //     $imagen_mostrar = $images_folder . $imgNewName;
    // } elseif (!empty($prod['imagen'])) {
    //     $imagen_mostrar = $images_folder . $prod['imagen'];
    // } else {
    //     $imagen_mostrar = $no_image;
    // }

    // var_dump(' hay nueva imagen: ', $hay_nueva_imagen);
    // echo "<br>";
    // var_dump(' validacion: ', $validacion);
    // echo "<br>";
    // var_dump(' imgNewName: ', $imgNewName);
    // echo "<br>";
    // var_dump(' isset(imgNewName) ', isset($imgNewName));
    // echo "<br>";
    // var_dump('$imagen_mostrar ', $imagen_mostrar);
    // echo "<br>";
    // var_dump('Imagen anterior: ', $ruta_imagen_anterior);
    // echo "<br>";

    // var_dump('$_FILES:', $_FILES['imagen']);
    // var_dump('$imgNewName:', $imgNewName);
    // var_dump('$producto[imagen]:', $producto['imagen']);
    // var_dump('$query:', $query);  // Ver SQL generado

    // exit();

    /* // TODO: aqui procesa la imagen del producto */
    // Detectar si realmente se subió una imagen nueva
    $hay_nueva_imagen = !empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK;

    $imgNewName = '';
    if ($hay_nueva_imagen) {
        $validacion = validarImagen($_FILES['imagen']);

        if (!$validacion['valida']) {
            $errores[] = 'Error: ' . $validacion['error'];
        } else {
            // Solo aquí procesamos la imagen NUEVA y eliminamos la anterior
            $imgNewName = generarNombreUnico($_FILES['imagen']['name']);

            // Guardar imagen anterior
            $imagen_anterior = $prod['imagen'];

            // Ruta para eliminar imagen anterior SOLO si existe
            $ruta_imagen_anterior = $images_folder . $imagen_anterior;


            // mover archivo temporal a directorio de 'uploads'
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $images_folder . $imgNewName)) {


                // if (!empty($producto['imagen']) && file_exists($ruta_imagen_anterior)) {
                //     unlink($ruta_imagen_anterior);
                // }

                if (
                    !empty($imagen_anterior) &&
                    $imagen_anterior !== $imgNewName && file_exists($ruta_imagen_anterior)
                ) {
                    unlink($ruta_imagen_anterior);
                }

                // Aquí actualizarías SQL con: imagen = '$imgNewName'
                $update_imagen = true;
                $producto['imagen'] = $imgNewName;  // aqui se guarda para SQL

                // Ahora recalculamos la ruta para mostrar en el formulario
                $imagen_mostrar = $images_folder . $producto['imagen'];
            } else {
                $errores[] = 'Error al mover la imagen al servidor';
            }
        }
    }



    if (empty($errores)) {


        $set_parts = [];
        foreach ($producto as $campo => $valor) {
            $set_parts[] = "$campo = '" . mysqli_real_escape_string($db, $valor) . "'";
        }

        $query = 'UPDATE productos SET ' . implode(', ', $set_parts) . ' WHERE id = ' . (int) $id;

        $res = mysqli_query($db, $query);

        // var_dump('  $hay_nueva_imagen): ', $hay_nueva_imagen);
        // echo "<br>";
        // var_dump(' $validacion ', $validacion);
        // echo "<br>";
        // var_dump(' $imgNewName ', $imgNewName);
        // echo "<br>";
        // var_dump(' isset(imgNewName) ', isset($imgNewName));
        // echo "<br>";
        // var_dump('$imagen_mostrar ', $imagen_mostrar);
        // echo "<br>";
        // var_dump(' $ruta_imagen_anterior ', $ruta_imagen_anterior);
        // echo "<br>";

        // var_dump('$_FILES: ', $_FILES['imagen']);
        // echo "<br>";
        // var_dump('$imgNewName: ', $imgNewName);
        // echo "<br>";
        // var_dump('$producto[imagen]:', $producto['imagen']);
        // echo "<br>";
        // var_dump('$query:', $query);  // Ver SQL generado
        // echo "<br>";
        // var_dump('$images_folder ', $images_folder);
        // echo "<br>";
        // var_dump('$no_image', $no_image);
        // echo "<br>";
        // var_dump('$imagen_mostrar ', $imagen_mostrar);



        if ($res) {
            // echo "Insertado correcto en la DB";

            // redireccionar a otra página para evitar repetidos registro duplicados
            // al 'enviar datos'

            // Query string
            header('Location: /admin?result=2');
            exit();
        }
    }
}

// 🔥 AQUÍ → JUSTO DESPUÉS de if(empty($errores))
$imagen_mostrar = $no_image;  // Por defecto
if (!empty($producto['imagen'])) {
    $imagen_mostrar = $images_folder . $producto['imagen'];
} elseif (!empty($prod['imagen'])) {
    $imagen_mostrar = $images_folder . $prod['imagen'];
} elseif (!$imgNewName !== NULL) {
    $imagen_mostrar = $imgNewName;
}
// if (!$_FILES['imagen']) {
//     $imagen_mostrar = $no_image;
// }

// var_dump('  $hay_nueva_imagen): ', $hay_nueva_imagen);
// echo "<br>";
// var_dump(' $validacion ', $validacion);
// echo "<br>";
// var_dump(' $imgNewName ', $imgNewName);
// echo "<br>";
// var_dump(' isset(imgNewName) ', isset($imgNewName));
// echo "<br>";
// var_dump('$imagen_mostrar ', $imagen_mostrar);
// echo "<br>";
// var_dump(' $ruta_imagen_anterior ', $ruta_imagen_anterior);
// echo "<br>";

// var_dump('$_FILES: ', $_FILES['imagen']);
// echo "<br>";
// var_dump('$imgNewName: ', $imgNewName);
// echo "<br>";
// var_dump('$producto[imagen]:', $producto['imagen']);
// echo "<br>";
// var_dump('$query:', $query);  // Ver SQL generado
// echo "<br>";
// var_dump('$images_folder ', $images_folder);
// echo "<br>";
// var_dump('$no_image', $no_image);
// echo "<br>";
// var_dump('$imagen_mostrar ', $imagen_mostrar);
// exit();

// END - Form processing
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

    <form method="POST" class="form-productos radius-t radius-b" enctype="multipart/form-data">
        <label>Código (SKU):
            <input type="text" name="codigo_sku" value="<?php echo $producto['codigo_sku']; ?>">
        </label>
        <label>Nombre del producto:
            <input type="text" name="nombre_producto" value="<?php echo $producto['nombre_producto']; ?>">
        </label>
        <label>Precio:
            <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>">
        </label>
        <label>Imagen (100 kb. max):
            <input type="file" name="imagen" accept="image/*">
        </label>

        <!-- imagen -->
        <img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="">


        <label>Descripción:
            <textarea name="descripcion"><?php echo $producto['descripcion']; ?></textarea>
        </label>
        <label>Existencia:
            <input type="number" name="existencia" value="<?php echo $producto['existencia']; ?>">
        </label>
        <label>Stock mínimo:
            <input type="number" name="stock_minimo" value="<?php echo $producto['stock_minimo']; ?>">
        </label>
        <fieldset>
            <legend>Proveedor:</legend>
            <select name="proveedor_id" id="">
                <option value="">- Elija el proveedor - </option>
                <?php while ($seller = mysqli_fetch_assoc($sellers_list)): ?>
                <option <?php echo $producto['proveedor_id'] === $seller['id'] ? 'selected' : ''; ?>
                    value="<?php echo $seller['id']; ?>">
                    <?php echo $seller['empresa'] . ' - ' . $seller['contact_name']; ?>
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
                <option <?php echo $producto['categoria_id'] === $category['id'] ? 'selected' : ''; ?>
                    value="<?php echo $category['id']; ?>">
                    <?php echo $category['categoria'] . ' - ' . $category['descripcion']; ?>
                </option>
                <?php endwhile; ?>
            </select>
        </fieldset>
        <fieldset>
            <legend>Visible en el catálogo </legend>
            <label for="activo" class="form-check form-switch">
                <input type="checkbox" id="activo" name="activo" class="form-check-input" value="1"
                    <?php echo ($producto['activo'] == 1) ? 'checked' : ''; ?>>
                <span id="estado-texto" class="form-check-label">
                    <?php echo ($producto['activo'] == 1) ? ' activo' : ' inactivo'; ?>
                </span>
            </label>

        </fieldset>

        <div class="submit-block">

            <button type="submit" class="btn-block-size  ">Enviar los cambios</button>
        </div>
    </form>
    <div class="warning-bar"></div>



</main>

<script src="/build/js/bundle.js"></script>

<?php
// includeTemplate('footer');

?>