<?php
/*
  create.php
  -- agregar productos
   */
// require_once __DIR__ . '/../../includes/app.php';
require_once dirname(__DIR__, 2) . '/includes/app.php';

use dkstore\Productos;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

requireRole('admin'); // obliga a ser admin - requiere acceso como admin

// // includeTemplate('header');

if (! includeTemplate('header')) {
    showNotification("Plantilla no existe o no autorizada: " . sanitizeHTML('header'), true);
}

/* // Bases de datos */
/* // $db = conectDB(); */
Productos::setDB($db); // ← IMPORTANTE: Configura DB en la clase

// Listas: Proveedores y Categorias de Productos
$sellers_list    = mysqli_query($db, "SELECT * FROM suppliers") ?: die(mysqli_error($db));
$categories_list = mysqli_query($db, "SELECT * FROM categories") ?: die(mysqli_error($db));

// template to use here
$form_template = 'product-form';

// control del formulario
$is_update_context = false;

// ubicacion de la imagenes
$images_folder   = '../../uploads/';
$no_image        = '../../assets/img/no-image.png';
$imagen_mostrar  = $no_image; // Por defecto
$imgNewName      = $no_image;
$mostrar_spinner = false;
$hay_nueva_imagen = '';

/* // instanciar producto */
$producto = new Productos();

//TODO:  declarar por defecto, hasta que sean creadas las Clases para estos.
$producto->supplier_id = 1;
$producto->category_id = 1;

// // Arreglo con mensajes de errores
// $errores = Productos::getErrores();
$errores = [];

// MAIN - Ejecutar despues que se envia el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // MAIN 1 - Guardar en sesión ANTES de validar
    $_SESSION['form_data'] = $_POST;

    // MAIN 2 - Instanciar el objeto con datos del formulario
    $producto = new Productos($_POST);

    // STEP  1. procesa la imagen del producto
    $hay_nueva_imagen = ! empty($_FILES['product_image']['tmp_name'])
        && $_FILES['product_image']['error'] === UPLOAD_ERR_OK;

    if ($hay_nueva_imagen) {

        // Guardar imagen anterior
        $imagen_anterior = $producto->product_image;

        // Ruta para eliminar imagen anterior SOLO si existe
        $ruta_imagen_anterior = $images_folder . $imagen_anterior;

        $imgNewName = generarNombreUnico($_FILES['product_image']['name']);
        $imgManager = new ImageManager(Driver::class);
        $img        = $imgManager->read($_FILES['product_image']['tmp_name'])->cover(800, 600);

        error_log("-- product_image --\n");

        // debugResult($producto, false);

        /*  actualiza la referencia de la imagen */
        $producto->setImage($imgNewName, $ruta_imagen_anterior);
    }

    // step 2. Validar entradas (errores que el usuario debe corregir)
    $erroresValid = $producto->validateEntry();

    error_log("=== DESPUÉS validateEntry + sanitize ===");
    error_log(print_r($producto, true));

    // step 3. Sanitizar entradas (limpieza y detección de intentos sospechosos)
    $sanOk = $producto->sanitize();

    error_log("DESPUÉS SANITIZE: " . $producto->product_name);
    error_log(print_r($producto, true));

    // step 4. Errores de sanitización
    $erroresSan = $producto->getErrores('sanitize');

    // $erroresValid = $producto->validarDescripcion();

    // step 5. Consolidar errores de validación + sanitización
    $errores = array_merge($erroresValid, $erroresSan);

    // step 6. Si todo OK, procesar data
    if (empty($errores) && $sanOk) {


        // STEP 7. Actualiza / almacena la imagen en el servidor
        $img->save(PATH_UPLOADS . $imgNewName);

        // STEP 8. Intentar guardar en BD
        if ($producto->guardarRecord()) {
            header('Location: /admin/index.php?result=1');
            exit;
        } else {
            // Acumular errores de sistema si falla el guardado
            $erroresSys = $producto->getErrores('system');
            $errores    = array_merge($errores, $erroresSys);
        }
    }

    // Imagen preview para errores
    if (! empty($_FILES['product_image']['name'])) {
        $imagen_mostrar = $images_folder . $_FILES['imagen']['name'];
    }
}

// $hay_imagen_nueva = isset($_FILES['imagen']) &&
// $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
// ! empty($_FILES['imagen']['name']);
// $mostrar_spinner = true;

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



<main class="admin-layout">
    <h2>Registrar Producto</h2>

    <a href="/admin/index.php" class="btn-secondary btn-block-10 btn-left">Salir</a>

    <?php if (! empty($errores)): ?>
        <div class="notification-bar error medium center">
            <span class="icon">⚠️</span>
            <div class="message">
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo sanitizeHTML($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <span class="close-btn">
                <img src="/assets/icons/close.svg" width="40px" height="40px" alt="">
            </span>
        </div>
    <?php endif; ?>


    <form method="POST" class="form-productos" enctype="multipart/form-data">

        <?php

        // Incluir formulario con variables necesarias
        $is_form_ok = includeForm('form-productos', [
            'producto'        => $producto,
            'errores'         => $errores,
            'categories_list' => $categories_list,
            'sellers_list'    => $sellers_list,
            'images_folder'   => $images_folder,
            'imagen_mostrar'  => $imagen_mostrar,
            'mostrar_spinner' => $mostrar_spinner,
            'imgNewName'      => $imgNewName,
            'no_image'        => $no_image,
            'hay_nueva_image' => $hay_nueva_imagen,
            'is_update_context' => $is_update_context
        ]);

        ?>
        <?php if ($is_form_ok): ?>
            <button type="submit" class="btn-success btn-block-30 btn-left">Aceptar</button>
            <a href="/admin/index.php" class="btn-warning btn-block-30 btn-right">Cancelar</a>
        <?php else: ?>
            <?php showNotification("Form not found: " . htmlspecialchars($tpl), false); ?>
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