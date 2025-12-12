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
    $sellers_list    = mysqli_query($db, "SELECT * FROM proveedores") ?: die(mysqli_error($db));
    $categories_list = mysqli_query($db, "SELECT * FROM categorias") ?: die(mysqli_error($db));

    // template to use here
    $form_template = 'product-form';

    // ubicacion de la imagenes
    $images_folder  = '../../uploads/';
    $no_image       = '../../assets/img/no-image.png';
    $imagen_mostrar = $no_image; // Por defecto

    /* // instanciar producto */
    $producto = new Productos();

    $producto->proveedor_id = 1;
    $producto->categoria_id = 1;

    // // Arreglo con mensajes de errores
    // $errores = Productos::getErrores();
    $errores = [];

    // Ejecutar despues que se envia el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Guardar en sesión ANTES de validar
        $_SESSION['form_data'] = $_POST;

        // 1. Instanciar el objeto con datos del formulario
        $producto = new Productos($_POST);

        // BEGIN procesar imagen
        // if ($validacion['valida']) {
        if ($_FILES['imagen']['tmp_name']) {

            $imgNewName = generarNombreUnico($_FILES['imagen']['name']);
            $imgManager = new ImageManager(Driver::class);
            $img        = $imgManager->read($_FILES['imagen']['tmp_name'])->cover(800, 600);

                                              // actualiza la referencia de la imagen
            $producto->setImage($imgNewName); // if (! $_FILES['imagen']) {
                                              // $imagen_mostrar = $_FILES['tmp_name'];
                                              // debugResult($imagen_mostrar, false);
                                              // }
            $imagen_mostrar = $imgNewName;
        }

        // error_log("DESPUÉS CONSTRUCTOR: " . $producto->nombre_producto);
        // error_log(print_r($producto, true));

        // 2. Validar entradas (errores que el usuario debe corregir)
        $erroresValid = $producto->validateEntry();

        error_log("=== DESPUÉS validateEntry + sanitize ===");
        error_log(print_r($producto, true));

        // 3. Sanitizar entradas (limpieza y detección de intentos sospechosos)
        $sanOk = $producto->sanitize();

        error_log("DESPUÉS SANITIZE: " . $producto->nombre_producto);
        error_log(print_r($producto, true));

        $erroresSan = $producto->getErrores('sanitize');

        // $erroresValid = $producto->validarDescripcion();

        // 4. Consolidar errores de validación + sanitización
        $errores = array_merge($erroresValid, $erroresSan);

        // 5. Si todo OK, procesar data
        if (empty($errores) && $sanOk) {

            // Guardar imagen en servidor
            $img->save(PATH_UPLOADS . $imgNewName);

            // Intentar guardar en BD
            if ($producto->guardarRecord()) {
                header('Location: /admin?result=1');
                exit;
            } else {
                // Acumular errores de sistema si falla el guardado
                $erroresSys = $producto->getErrores('system');
                $errores    = array_merge($errores, $erroresSys);
            }
        }

        // Imagen preview para errores
        if (! empty($_FILES['imagen']['name'])) {
            $imagen_mostrar = $images_folder . $_FILES['imagen']['name'];
        }
    }

    $hay_imagen_nueva = isset($_FILES['imagen']) &&
    $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
    ! empty($_FILES['imagen']['name']);
    $mostrar_spinner = true;

    if ($hay_imagen_nueva) {
        // 🎯 ESCENARIO 3: Usuario cargó imagen → Spinner "procesando"
        $imagen_mostrar  = $imgNewName;
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
                'imagen_mostrar'  => $imagen_mostrar,
                'mostrar_spinner' => $mostrar_spinner,
            ]);

        ?>
        <?php if ($is_form_ok): ?>
        <button type="submit" class="btn-primary btn-block-20 btn-left">Enviar</button>
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