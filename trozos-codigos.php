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
<!--                                                                      <?php else: ?>
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