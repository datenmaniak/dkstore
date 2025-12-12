<?php

    require_once dirname(__DIR__, 2) . '/includes/app.php';

    use dkstore\Productos;
    use Intervention\Image\Drivers\Gd\Driver;
    use Intervention\Image\ImageManager;

    requireRole('admin'); // obliga a ser admin

    // template to use here
    $form_template = 'product-form';

    // get the record and validate the URL
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (! $id) {
        header('Location: /admin');
    }

    includeTemplate('header');

    $images_folder = '../../uploads/';
    $no_image      = '../../assets/img/no-image.jpg';

    // Productos::setDB($db); // ← IMPORTANTE: Configura DB en la clase

    // Search the product by ID and get its data
    $producto = Productos::getRecordById($id);

    // Proveedores
    $sellers      = 'SELECT * FROM proveedores';
    $sellers_list = mysqli_query($db, $sellers);

    // Categorias
    $categories      = 'SELECT * FROM categorias';
    $categories_list = mysqli_query($db, $categories);

                   // Arreglo con mensajes de errores
    $errores = []; // antes Productos::getErrores();

    // Ejecutar despues que se envia el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Guardar en sesión ANTES de validar
        $_SESSION['form_data'] = $_POST;

        // asigna los atributos
        $args               = [];
        $args['codigo_sku'] = $_POST['codigo'] ?? '';

        $producto->syncData($args);

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

        // 2. Validar campos
        // $errores = $producto->validateEntry();

        // if (! $producto->sanitize()) {
        //     // $errores = array_merge($errores, $producto->getErrores());
        //     $errores = array_merge($errores, $producto::getErrores());
        // } else {
        //     unset($_SESSION['form_data']); // Limpiar al éxito
        // }

        //     if (empty($errores)) {

        //         $set_parts = [];
        //         foreach ($producto as $campo => $valor) {
        //             $set_parts[] = "$campo = '" . mysqli_real_escape_string($db, $valor) . "'";
        //         }

        //         $query = 'UPDATE productos SET ' . implode(', ', $set_parts) . ' WHERE id = ' . (int) $id;

        //         $res = mysqli_query($db, $query);

        //         if ($res) {
        //             // echo "Insertado correcto en la DB";

        //             // redireccionar a otra página para evitar repetidos registro duplicados
        //             // al 'enviar datos'

        //             // Query string
        //             header('Location: /admin?result=2');
        //             exit();
        //         }
        //     }
        // }

        // 🔥 AQUÍ → JUSTO DESPUÉS de if(empty($errores))
        $hay_imagen_nueva = isset($_FILES['imagen']) &&
        $_FILES['imagen']['error'] === UPLOAD_ERR_OK &&
        ! empty($_FILES['imagen']['name']);

        if ($hay_imagen_nueva) {
            // 🎯 ESCENARIO 3: Usuario cargó imagen → Spinner "procesando"
            $imagen_mostrar  = $imgNewName;
            $mostrar_spinner = true;
        } else {
            // 🎯 ESCENARIO 1 + 2: GET inicial O POST sin imagen → Por defecto
            $imagen_mostrar  = $no_image;
            $mostrar_spinner = false;
        }
    }
?>


<main class="admin-layout  mt-15">
    <!-- <main class="add-products-container mt-15 basic-container"> -->
    <h2>Actualizar Producto</h2>

    <a href="/admin/index.php" class="btn btn-secondary btn-block-10 btn-left">Salir</a>


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


        <?php

            if (! includeTemplate($form_template)) {
                showNotification("Plantilla no existe o no autorizada: " . htmlspecialchars($form_template), true);
            }
        ?>

        <button type="submit" class="btn-primary btn-right ">Enviar los cambios</button>
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