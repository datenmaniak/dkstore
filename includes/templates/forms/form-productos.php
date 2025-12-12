<?php
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

?>



<label>Código (SKU):
    <input type="text" name="codigo_sku" value="<?php echo sanitizeHTML($producto->codigo_sku ?? '') ?>">
</label>
<label>Nombre del producto:
    <input type="text" name="nombre_producto" value="<?php echo $producto->nombre_producto ?? '' ?>">
</label>
<label>Precio:
    <input type="number" step="0.01" name="precio" value="<?php echo sanitizeHTML($producto->precio ?? 0); ?>">
</label>

<!-- // BEGIN imagen del producto -->
<div class="product-image">
    <label>Imagen (100 kb. max):
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
    <?php endif; ?>
    <?php debugResult($producto->imagen, false); ?>

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

    <!-- Contenedor para la vista previa (siempre presente) -->
    <div id="previewContainer" class="preview">
        <img id="imagenPreview" class="img-prod" alt="Vista previa" style="display:none; ">

        <button type="button" id="previewClear" class="btn-primary  btn-right" style="display:none; ">Quitar
            imagen</button>
    </div>
    <!--                                                                                                                                                                                                                                                                                                                                                                                                                                                             <?php debugResult($producto->imagen, false); ?> -->

</div>

<!-- // END imagen del producto -->

<label>Descripción:
    <textarea name="descripcion" maxlength="255"><?php echo sanitizeHTML($producto->descripcion ?? '') ?></textarea>
    <small id="description_input_counter">0/255</small>
</label>
<label>Existencia:
    <input type="number" name="existencia" value="<?php echo sanitizeHTML($producto->existencia ?? 0); ?>">
</label>
<label>Stock mínimo:
    <input type="number" name="stock_minimo" value="<?php echo sanitizeHTML($producto->stock_minimo ?? 0); ?>">
</label>

<fieldset>
    <legend>Visible en el catálogo </legend>
    <label for="is_active" class="form-check form-switch">


        <?php $activo = (int) ($producto->is_active ?? 0); ?>
        <input type="checkbox" id="is_active" name="is_active" class="form-check-input"
            <?php echo($activo ?? '0') === '1' ? 'checked' : '' ?>>
        <span id="estado-notificacion" class="form-check-label">
            <?php echo($activo ?? '0') === '1' ? 'activo' : 'inactivo' ?>
        </span>

    </label>

</fieldset>