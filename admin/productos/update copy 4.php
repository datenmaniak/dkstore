<?php

require_once dirname(__DIR__, 2) . '/includes/app.php';

use dkstore\Productos;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

requireRole('admin'); // obliga a ser admin

// template to use here
$form_template = 'form-productos';

// control del formulario
$is_update_context = true;

// get the record and validate the URL
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (! $id) {
    header('Location: /admin/index.php');
}

includeTemplate('header');

// $images_folder   = '../../uploads/';
$images_folder   = PATH_UPLOADS;
$no_image        = '../../assets/img/no-image.jpg';
$imagen_mostrar  = $no_image; // Por defecto
$mostrar_spinner = false;
$imgNewName      = $no_image;
$hay_nueva_imagen = '';


// Productos::setDB($db); // ← IMPORTANTE: Configura DB en la clase

// Search the product by ID and get its data
$producto = Productos::getRecordById($id);

// Proveedores
$sellers      = 'SELECT * FROM suppliers';
$sellers_list = mysqli_query($db, $sellers);

// Categorias
$categories      = 'SELECT * FROM categories';
$categories_list = mysqli_query($db, $categories);

/* // Arreglo con mensajes de errores */
$errores = []; // antes Productos::getErrores();

// MAIN Ejecutar despues que se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Guardar en sesión ANTES de validar
    $_SESSION['form_data'] = $_POST;

    // Data binding: asignar valores del formulario al objeto
    // Binding directo desde el formulario
    $producto->dataBinding($_POST);

    // STEP 2. Validar entradas (errores que el usuario debe corregir)
    $erroresValid = $producto->validateEntry();

    error_log("=== DESPUÉS validateEntry + sanitize ===");
    error_log(print_r($producto, true));

    // STEP 3. Sanitizar entradas (limpieza y detección de intentos sospechosos)
    $sanOk = $producto->sanitize();

    // error_log("DESPUÉS SANITIZE: " . $producto->product_name);
    // error_log(print_r($producto, true));

    // STEP 4 - Errores de sanitización
    $erroresSan = $producto->getErrores('sanitize');

    // $erroresValid = $producto->validarDescripcion();

    // STEP 5. Consolidar errores de validación + sanitización
    $errores = array_merge($erroresValid, $erroresSan);


    // STEP 6. Si todo OK, procesar data
    if (empty($errores) && $sanOk) {


        // Detectar si realmente se subió una imagen nueva
        $hay_nueva_imagen = isset($_FILES['product_image']) &&
            $_FILES['product_image']['error'] === UPLOAD_ERR_OK &&
            !empty($_FILES['product_image']['name']);


        if ($hay_nueva_imagen) {

            // Guardar imagen anterior
            $imagen_anterior = $producto->product_image;

            // Ruta para eliminar imagen anterior SOLO si existe
            $ruta_imagen_anterior = $images_folder . $imagen_anterior;

            $imgNewName = generarNombreUnico($_FILES['product_image']['name']);
            $imgManager = new ImageManager(Driver::class);
            $img        = $imgManager->read($_FILES['product_image']['tmp_name'])->cover(800, 600);


            // debugResult($ruta_imagen_anterior, false);
            // debugResult($imgNewName, false);
            // debugResult(PATH_UPLOADS, false);

            /*  actualiza la referencia de la imagen */
            $producto->setImage($imgNewName, $ruta_imagen_anterior);

            // Actualiza / almacena la referencia de la imagen en el servidor
            $img->save(PATH_UPLOADS . $imgNewName);
        }

        // Step 8. Intentar guardar en BD
        if ($producto->actualizarRecord()) {
            // header('Location: /admin?result=2');
            header('Location: /admin/index.php?result=2');
            exit();
        } else {
            // Acumular errores de sistema si falla el guardado
            $erroresSys = $producto->getErrores('system');
            $errores    = array_merge($errores, $erroresSys);
        }
    }

    // Imagen preview para errores
    if (! empty($_FILES['product_image']['name'])) {
        $imagen_mostrar = $images_folder . $_FILES['product_image']['name'];
    }

    $imagen_mostrar = $hay_nueva_imagen ? $imgNewName : $producto->product_image;
    $mostrar_spinner = $hay_nueva_imagen;
}
// // 🔥 AQUÍ → JUSTO DESPUÉS de if(empty($errores))
// $hay_imagen_nueva = isset($_FILES['imagen']) &&
// $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
// ! empty($_FILES['imagen']['name']);

// if ($hay_imagen_nueva) {
//     // 🎯 ESCENARIO 3: Usuario cargó imagen → Spinner "procesando"
//     $imagen_mostrar  = $imgNewName;
//     $mostrar_spinner = true;
// } else {
//     // 🎯 ESCENARIO 1 + 2: GET inicial O POST sin imagen → Por defecto
//     $imagen_mostrar  = $no_image;
//     $mostrar_spinner = false;
// }
?>


<main class="admin-layout  mt-15">
    <!-- <main class="add-products-container mt-15 basic-container"> -->
    <h2>Actualizar Producto</h2>

    <!-- <a href="/admin/index.php" class="btn btn-secondary btn-block-10 btn-left">Salir</a> -->

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


    <!-- action="/admin/productos/update.php"> -->



    <form method="POST" class="form-productos " enctype="multipart/form-data">
        <!-- update.php  -->

        <?php
        // Incluir formulario con variables necesarias
        $is_form_ok = includeForm($form_template, [
            'producto'        => $producto,
            'errores'         => $errores,
            'categories_list' => $categories_list,
            'sellers_list'    => $sellers_list,
            'images_folder'   => $images_folder,
            'imagen_mostrar'  => $imagen_mostrar,
            'mostrar_spinner' => $mostrar_spinner,
            'imgNewName'      => $imgNewName,
            'no_image'        => $no_image,
            'hay_nueva_imagen' => $hay_nueva_imagen,
            'is_update_context' => $is_update_context
        ]);

        ?>
        <?php if ($is_form_ok): ?>
            <button type="submit" class="btn-success btn-block-40 btn-left">Aceptar cambios</button>
            <a href="/admin/index.php" class="btn-warning btn-block-30 btn-right">Cancelar</a>

        <?php else: ?>
            <?php showNotification("Form not found: " . htmlspecialchars($form_template), false); ?>
        <?php endif; ?>
    </form>




</main>

<?php

$templates_to_load = ['footer', 'scripts', 'end-page'];

foreach ($templates_to_load as $tpl) {
    if (! includeTemplate($tpl)) {
        showNotification("Plantilla no existe o no autorizada: " . htmlspecialchars($tpl), false);
    }
}

?>