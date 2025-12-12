<?php
    $mostrar_spinner = false; // o true según tu lógica

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

<!-- // BEGIN -->
<div class="product-image">
    <label>Imagen (100 kb. max):
        <input type="file" id="imagenUpload" name="imagen" accept="image/*">
    </label>

    <!-- Contenedor para la vista previa (siempre presente) -->
    <div id="previewContainer" class="preview">
        <img id="imagenPreview" class="img-prod" alt="Vista previa" style="display:none; ">

        <button type="button" id="previewClear" class="btn-primary  btn-right" style="display:none; ">Quitar
            imagen</button>
    </div>
</div>

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