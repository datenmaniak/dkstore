<!-- formulario: 12.12.25, 18h00 -->

<?php if (! empty($producto->imagen)): ?>
<img src="<?php echo htmlspecialchars($images_folder . $producto->imagen, ENT_QUOTES, 'UTF-8'); ?>" class="img-prod"
    alt="<?php echo htmlspecialchars($producto->nombre ?? 'Imagen del producto', ENT_QUOTES, 'UTF-8'); ?>"
    loading="lazy" decoding="async">
<?php endif; ?>


<?php if (! empty($producto->imagen)): ?>
<img src="<?php echo PATH_UPLOADS . $producto->imagen; ?>" class="img-prod" alt="product image">
<?php else: ?>
<!-- Imagen por defecto -->
<img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="image no available">
<?php endif; ?>

<fieldset>
    <legend>Visible en el catálogo </legend>
    <label for="is_active" class="form-check form-switch">

        <input type="checkbox" id="is_active" name="is_active" class="form-check-input"
            <?php echo (int) $producto->is_active === 1 ? 'checked' : ''; ?>>
        <span id="estado-notificacion" class="form-check-label">
            <?php echo (int) $producto->is_active === 1 ? 'Activo' : 'Inactivo'; ?>
        </span>

    </label>

</fieldset>


<?php $activo = (int) ($producto->is_active ?? 0); ?>
<input type="checkbox" id="is_active" name="is_active" class="form-check-input"
    <?php echo($activo ?? '0') === '1' ? 'checked' : '' ?>>
<span id="estado-notificacion" class="form-check-label">
    <?php echo($activo ?? '0') === '1' ? 'activo' : 'inactivo' ?>
</span>
?>

<td class="text-center active-in-catalog">
    <span class="status-badge
                                <?php echo $item->is_active ? 'active' : 'inactive'; ?>">
        <i class="<?php echo $item->is_active ? 'ri-checkbox-line me-1' : 'ri-close-line me-1'; ?>"></i>
        <?php echo $item->is_active ? 'Activo' : 'Inactivo'; ?>
    </span>
</td>


<input type="checkbox" id="is_active" name="is_active" class="form-check-input"
    <?php echo (int) $producto->is_active === 1 ? 'checked' : ''; ?>>
<span id="estado-notificacion" class="form-check-label">
    <?php echo (int) $producto->is_active === 1 ? 'Activo' : 'Inactivo'; ?>
</span>


<?php

< ! -- <  ? phpforeach($errores as $error) : ?>
<div class="alerta error">
    <?php echo $error; ?>
</div>
<?php endforeach; ?> -->


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



// BEGIN: procesar imagen
// 1. Validar imagen
$imagen = $_FILES['imagen'];
$validacion = validarImagen($_FILES['imagen']);
if (! $validacion['valida']) {
$errores[] = "Error: " . $validacion['error'];
}

// BEGIN procesar imagen
if ($validacion['valida']) {
$imgNewName = generarNombreUnico($_FILES['imagen']['name']);

$imgManager = new ImageManager(Driver::class);
$img = $imgManager->read($_FILES['imagen']['tmp_name'])->cover(800, 600);

// obtener la ruta y guardar en el servidor (antes sin OOP)
/* move_uploaded_file($imagen['tmp_name'], $images_folder . $imgNewName); */
// $producto->imagen = $imgNewName; // Actualiza imagen

// nuevo modo de almacenar en el servidor
$img->save($images_folder . $imgNewName);

// actualiza la referencia de la imagen
$producto->setImage($imgNewName);

} else {
echo "Error: " . $validacion['error'];
}
// END procesar imagen
// END - gestion / validacion imagen

<!--
    <?php if ($errores): ?>
    <div class="alerta error">
        <ul><?php foreach ($errores as $error): ?>
            <li><?php echo htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?> -->

" class="img-prod spinner-loader" alt="">

<img src="<?php echo ! $imagen_mostrar ? $no_image : ''; ?>
             " class="img-prod<?php echo $hay_imagen_nueva ? ' spinner' : ''; ?>" alt="">



// Renderizado imagen + spinner



<!-- Renderizado condicional -->
<?php if ($mostrar_spinner): ?>
<!-- Overlay con spinner tipo barra -->
<div class="overlay-spinner">
    <div class="bar-spinner">
        <span class="spinner-message">Procesando imagen...</span>
        <div class="spinner-bar"></div>
    </div>
</div>
<?php else: ?>
<!-- Imagen por defecto -->
<img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="Imagen por defecto">
<?php endif; ?>


<!-- Notificacion de errores en PHP -->

<?php if ($errores): ?>
<div class="alerta error">
    <ul><?php foreach ($errores as $error): ?>
        <li><?php echo htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>


// END

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

// end

<!-- // BEGIN imagen del producto 12.12.25 11:06 -->
<div class="product-image">
    <label>Imagen (100 kb. max):
        <input type="file" id="imagenUpload" name="imagen" accept="image/*">
    </label>
    <!-- Renderizado condicional -->
    <?php if ($mostrar_spinner): ?>
    <!-- Overlay con spinner tipo barra -->
    <div class="overlay-spinner">
        <div class="bar-spinner">
            <span class="spinner-message">Procesando imagen...</span>
            <div class="spinner-bar"></div>
        </div>
    </div>
    <?php else: ?>
    <!-- Imagen por defecto -->
    <img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="Imagen por defecto">
    <?php endif; ?>


    <!-- Contenedor para la vista previa (siempre presente) -->
    <div id="previewContainer" class="preview">
        <img id="imagenPreview" class="img-prod" alt="Vista previa" style="display:none; ">

        <button type="button" id="previewClear" class="btn-primary  btn-right" style="display:none; ">Quitar
            imagen</button>
    </div>
    <?php debugResult($mostrar_spinner, false); ?>

</div>
<!-- // END imagen del producto -->


<div class="product-image">
    <label>Imagen (100 kb. max):
        <!-- <input type="file" name="imagen" accept="image/*"> -->
        <input type="file" id="imagenUpload" name="imagen" accept="image/*">
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

// end
<!--                                                                                                                                              <?php else: ?>
            <img src="<?php echo $imagen_mostrar; ?>" class="img-prod" alt="Imagen por defecto"> -->


style="display:none; max-width:300px;"

style="display:none;"


// Valida include template

<?php
if (includeTemplate($template_path)): ?>
<!-- Template cargado OK
        <button type="submit" class="btn-primary btn-block-30 btn-right">Enviar</button>
        <?php else: ?>
        <!-- Template falló → Notification + Salir -->
<div class="notification-bar error medium center">
    <span class="icon">⚠️</span>
    <div class="message"><strong>Formulario no disponible</strong></div>
    <a href="/" class="btn-secondary btn-block-20">Salir</a>
</div>
<?php endif; ?>


<?php
if (includeTemplate('product-form')): ?>
<!-- Template cargado OK
        <button type="submit" class="btn-primary btn-block-30 btn-right">Enviar</button>
        <?php else: ?>
        <!-- Template falló → Notification + Salir -->
<div class="notification-bar error medium center">
    <span class="icon">⚠️</span>
    <div class="message"><strong>Formulario no disponible</strong></div>
    <a href="/" class="btn-secondary btn-block-20">Salir</a>
</div>
<?php endif; ?>



<?php includeTemplate('footer');
    includeTemplate('scripts');
includeTemplate('end-page'); ?>


<?php
    // Inicializaciones seguras para evitar errores de variables indefinidas
    $mostrar_spinner = $mostrar_spinner ?? false;
    $producto        = $producto ?? new \dkstore\Productos();
    $sellers_list    = $sellers_list ?? [];
    $categories_list = $categories_list ?? [];
?>

//BEGIN - carga el item en el formulario
<?php while ($seller = mysqli_fetch_assoc($sellers_list)): ?>
<option value="<?php echo $seller['id'] ?>"<?php echo $producto->proveedor_id == $seller['id'] ? 'selected' : '' ?>>
    <?php echo htmlspecialchars($seller['empresa']) ?>
</option>
<?php endwhile; ?>

<?php while ($category = mysqli_fetch_assoc($categories_list)): ?>
<option value="<?php echo $category['id'] ?>"
    <?php echo $producto->categoria_id == $category['id'] ? 'selected' : '' ?>>
    <?php echo htmlspecialchars($category['categoria'] . " - " . $category['descripcion']) ?>
</option>
<?php endwhile; ?>


// BEGIN Codigo del formulario de update.php
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
        <option<?php echo $producto['proveedor_id'] === $seller['id'] ? 'selected' : ''; ?>
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
        <option<?php echo $producto['categoria_id'] === $category['id'] ? 'selected' : ''; ?>
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
            <?php echo($producto['activo'] == 1) ? 'checked' : ''; ?>>
        <span id="estado-texto" class="form-check-label">
            <?php echo($producto['activo'] == 1) ? ' activo' : ' inactivo'; ?>
        </span>
    </label>

</fieldset>

// END

//begin : VIEJO METODO PARA VALIDAR FOTO EN update.php_check_syntax
/* // TODO: aqui procesa la imagen del producto */
// Detectar si realmente se subió una imagen nueva
$hay_nueva_imagen = ! empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK;

$imgNewName = '';
if ($hay_nueva_imagen) {
$validacion = validarImagen($_FILES['imagen']);

if (! $validacion['valida']) {
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
// unlink($ruta_imagen_anterior);
// }

if (
! empty($imagen_anterior) &&
$imagen_anterior !== $imgNewName && file_exists($ruta_imagen_anterior)
) {
unlink($ruta_imagen_anterior);
}

// Aquí actualizarías SQL con: imagen = '$imgNewName'
$update_imagen = true;
$producto['imagen'] = $imgNewName; // aqui se guarda para SQL

// Ahora recalculamos la ruta para mostrar en el formulario
$imagen_mostrar = $images_folder . $producto['imagen'];
} else {
$errores[] = 'Error al mover la imagen al servidor';
}
}
}

//END


// viene de: create.php , Errores
<?php if ($errores): ?>
<div class="notification-bar error medium center ">
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

// TODO: agregar a formulario cuando este concluido el modulo create.php
<!-- // REMOVE SUSPENDIDO TEMPORALMENTE-->
<!-- <fieldset>
    <legend>Proveedor:</legend>
    <select name="proveedor_id" id="">
        <option value="">- Elija el proveedor - </option>
        // BEGIN
        <?php if ($sellers_list && $sellers_list instanceof mysqli_result): ?>
            <?php while ($seller = mysqli_fetch_assoc($sellers_list)): ?>
                <option value="<?php echo $seller['id'] ?>"
                <?php echo($producto->proveedor_id ?? '') == $seller['id'] ? 'selected' : '' ?>>
                <?php echo sanitizeHTML($seller['empresa']) ?>
            </option>
            <?php endwhile; ?>
            <?php endif; ?>

            //END
        </select>
    </fieldset> -->
<!-- // REMOVE SUSPENDIDO TEMPORALMENTE-->
<!-- <fieldset>
    <legend>Categoría:</legend>
    <select name="categoria_id" id="">
        <option value="">- Elija categoría -</option>
        // begin
        <?php if ($categories_list && $categories_list instanceof mysqli_result): ?>
        <?php while ($category = mysqli_fetch_assoc($categories_list)): ?>
        <option value="<?php echo $category['id'] ?>"
            <?php echo($producto->categoria_id ?? '') == $category['id'] ? 'selected' : '' ?>>
            <?php echo sanitizeHTML($category['categoria'] . " - " . $category['descripcion']) ?>
        </option>
        <?php endwhile; ?>
        <?php endif; ?>

        // end
    </select>
</fieldset> -->
//END

<input type="checkbox" id="is_active" name="is_active" class="form-check-input"
    <?php echo($producto->is_active == 1) ? 'checked' : '' ?>>

<span id="estado-notificacion" class="form-check-label">
    <?php echo($producto->is_active == 1) ? ' activo' : ' inactivo'; ?>
</span>


//TODO formulario actual create.php
<form method="POST" class="form-productos" enctype="multipart/form-data">

    <?php

        if (! includeTemplate($form_template)) {
            showNotification("Plantilla no existe o no autorizada: " . htmlspecialchars($form_template));
        }
    ?>
    <div class="send-form">
        <button type="submit" class="btn-primary btn-block-20 btn-left">Enviar</button>
    </div>

</form>



<form method="POST" class="form-productos" enctype="multipart/form-data" style="border: 5px solid green;">

    <!-- DEBUG: Confirma datos PHP -->
    <div style="background:yellow;padding:10px;">
        DEBUG: nombre_producto = "<?php echo htmlspecialchars($producto->nombre_producto) ?>"
    </div>

    <?php includeTemplate('product-form'); ?>

    <div class="send-form">
        <button type="submit" class="btn-primary btn-block-20 btn-left">ENVIAR SIN JS</button>
    </div>
</form>

// create.php

<!-- Renderizado condicional existente -->
<?php if ($mostrar_spinner): ?>
<div class="bar-spinner" id="barSpinner">
    <span class="spinner-message">Imagen no procesada. Reintente de nuevo...</span>
    <div class="spinner-bar"></div>
</div>
<?php endif; ?>

// Validar y sanitizar → la clase maneja errores internamente
// $producto->sanitize();

// // Solo consultas los errores centralizados
// $errores = Productos::getErrores();
//todo:
// if (! $producto->sanitize()) {
// // $errores = array_merge($errores, $producto->getErrores());
// $errores = array_merge($errores, $producto::getErrores());
// } else {
// unset($_SESSION['form_data']); // Limpiar al éxito
// }
// TODO:

// 4. Si todo OK, procesar data
// if (empty($errores)) {

// // nuevo modo de almacenar en el servidor
// $img->save(PATH_UPLOADS . $imgNewName);
// // $img->save($images_folder . $imgNewName);

// if ($producto->guardarRecord()) {
// // header('Location: /admin/productos/');
// header('Location: /admin?result=1');
// exit;
// }
// }



<form method="POST" class="form-productos" enctype="multipart/form-data">

    <?php
        // ✅ SEGURO + RÁPIDO - Solo 3 variables
        $GLOBALS['producto']        = $producto;
        $GLOBALS['sellers_list']    = $sellers_list;
        $GLOBALS['categories_list'] = $categories_list;

        includeTemplate('product-form');
        // if (! includeTemplate($form_template)) {
        //     showNotification("Plantilla no existe o no autorizada: " . htmlspecialchars($form_template));
        // }
    ?>

    <div class="send-form">
        <button type="submit" class="btn-primary btn-block-20 btn-left">Enviar</button>
    </div>

</form>

<?php
    // ✅ SEGURO + RÁPIDO - Solo 3 variables
    $GLOBALS['producto']        = $producto;
    $GLOBALS['sellers_list']    = $sellers_list;
    $GLOBALS['categories_list'] = $categories_list;

    includeTemplate('product-form');
    // if (! includeTemplate($form_template)) {
    //     showNotification("Plantilla no existe o no autorizada: " . htmlspecialchars($form_template));
// }
?>