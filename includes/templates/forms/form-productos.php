<?php

//   form-productos.php

$_SESSION['imgNewName'] = $imgNewName;
// $is_update_context  = true;

// REMOVE, si funciona el bloque abajo
// // 🔥 AQUÍ → JUSTO DESPUÉS de if(empty($errores))
// $hay_imagen_nueva = isset($_FILES['product_image']) &&
//     $_FILES['product_image']['error'] === UPLOAD_ERR_OK &&
//     ! empty($_FILES['product_image']['name']);

// if ($hay_imagen_nueva) {
//     // 🎯 ESCENARIO 3: Usuario cargó imagen → Spinner "procesando"
//     $imagen_mostrar  = $imgNewName;
//     $mostrar_spinner = true;
// } else {
//     // 🎯 ESCENARIO 1 + 2: GET inicial O POST sin imagen → Por defecto
//     $imagen_mostrar  = $no_image;
//     $mostrar_spinner = false;
// }

// TODO,  Evaluar nuevo bloque
$hay_imagen_nueva = isset($_FILES['product_image']) &&
    $_FILES['product_image']['error'] === UPLOAD_ERR_OK &&
    !empty($_FILES['product_image']['name']);

if ($hay_imagen_nueva) {
    // Usuario subió una nueva imagen
    $imagen_mostrar  = $imgNewName;
    $mostrar_spinner = true;
    $producto->product_image = $imgNewName; // asignar a la clase
} else {
    // if ($is_update_context && !empty($producto->product_image)) {
    if (!empty($producto->product_image)) {
        // Estamos en actualización y ya existe una imagen previa
        $imagen_mostrar  = $producto->product_image;
        $mostrar_spinner = false;
    } else {
        // Caso inicial sin imagen
        $imagen_mostrar  = $no_image;
        $mostrar_spinner = false;
        // $this->errorsValidation[] = 'La imagen es obligatoria';
    }
}

// if ($is_update_context) {
//     // Mostrar imagen previa si existe
//     $imagen_mostrar = $producto->product_image ?? $no_image;
// } else {
//     // En alta, la imagen es obligatoria
//     $imagen_mostrar = $no_image;
// }



?>


<label>Código (SKU):
    <input type="text" name="sku" value="<?php echo sanitizeHTML($producto->sku ?? '') ?>">
</label>
<label>Nombre del producto:
    <input type="text" name="product_name" value="<?php echo $producto->product_name ?? '' ?>">
</label>
<label>Precio:
    <input type="number" step="0.01" name="price" value="<?php echo sanitizeHTML($producto->price ?? 0); ?>">
</label>

<!-- // BEGIN imagen del producto -->
<div class="product-image">
    <label>Imagen (100 kb. max):
        <input type="file" id="imagenUpload" name="product_image" accept="image/*">
    </label>

    <!-- Renderizado condicional -->
    <?php if ($mostrar_spinner): ?>
        <!-- Spinner activo sin imagen -->
        <!-- Contenedor del spinner tipo barra -->
        <div class=" bar-spinner">
            <span class="spinner-message">Imagen cargada. Esperando para procesar...</span>
            <div class="spinner-bar"></div>
        </div>
    <?php endif; ?>
    <!-- Contenedor para la vista previa (siempre presente) -->
    <div id="previewContainer" class="preview">
        <?php if (!empty($imagen_mostrar)): ?>
            <img id="imagenPreview"
                class="img-prod"
                alt="Vista previa"
                src="/uploads/<?php echo htmlspecialchars($imagen_mostrar); ?>"
                style="display:block;">
        <?php else: ?>
            <img id="imagenPreview" class="img-prod" alt="Vista previa" style="display:none;">
            <button type="button" id="previewClear" class="btn-primary btn-right" style="display:none;">Quitar imagen</button>
        <?php endif; ?>
    </div>

</div>
<!-- // END imagen del producto -->

<label>Descripción:
    <textarea name="description" maxlength="255"><?php echo sanitizeHTML($producto->description ?? '') ?></textarea>
    <small id="description_input_counter">0/255</small>
</label>
<label>Existencia:
    <input type="number" name="stock_quantity" value="<?php echo sanitizeHTML($producto->stock_quantity ?? 0); ?>">
</label>
<label>Stock mínimo:
    <input type="number" name="low_stock_threshold" value="<?php echo sanitizeHTML($producto->low_stock_threshold ?? 0); ?>">
</label>

<fieldset>
    <legend>Visible en el catálogo </legend>
    <label for="is_active" class="form-check form-switch">
        <input type="checkbox" id="is_active" name="status" value="<?php echo (int) $producto->status; ?>"
            class="form-check-input" <?php echo $producto->status === 'active' ? 'checked' : ''; ?>>
        <span id="estado-notificacion" class="form-check-label">
            <?php echo  $producto->status === 'active' ? '  Activo' : '  Inactivo'; ?>
        </span>

    </label>
</fieldset>