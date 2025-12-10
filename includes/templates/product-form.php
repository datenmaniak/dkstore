  <label>Código (SKU):
      <input type="text" name="codigo_sku" value="<?php echo htmlspecialchars($producto->codigo_sku) ?>">
  </label>
  <label>Nombre del producto:
      <input type="text" name="nombre_producto" value="<?php echo htmlspecialchars($producto->nombre_producto) ?>">
  </label>
  <label>Precio:
      <input type="number" step="0.01" name="precio" value="<?php echo $producto->precio; ?>">
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

      <!-- Renderizado condicional existente -->
      <?php if ($mostrar_spinner): ?>
      <div class="bar-spinner" id="barSpinner">
          <span class="spinner-message">Imagen no procesada. Reintente de nuevo...</span>
          <div class="spinner-bar"></div>
      </div>
      <?php endif; ?>
  </div>

  <label>Descripción:
      <textarea name="descripcion" maxlength="255">
                <?php echo htmlspecialchars($producto->descripcion) ?></textarea>
      <small id="description_input_counter">0/255</small>
  </label>
  <label>Existencia:
      <input type="number" name="existencia" value="<?php echo $producto->existencia; ?>">
  </label>
  <label>Stock mínimo:
      <input type="number" name="stock_minimo" value="<?php echo $producto->stock_minimo; ?>">
  </label>
  <!-- TODO: aqui estuvo  el campo de activo  -->

  <!-- <label>ID Proveedor: <input type="number" name="proveedor_id"></label> -->
  <fieldset>
      <legend>Proveedor:</legend>
      <select name="proveedor_id" id="">
          <option value="">- Elija el proveedor - </option>
          <?php while ($seller = mysqli_fetch_assoc($sellers_list)): ?>
          <option value="<?php echo $seller['id'] ?>"
              <?php echo $producto->proveedor_id == $seller['id'] ? 'selected' : '' ?>>
              <?php echo htmlspecialchars($seller['empresa']) ?>
          </option>
          <?php endwhile; ?>
      </select>
  </fieldset>
  <fieldset>
      <legend>Categoría:</legend>
      <select name="categoria_id" id="">
          <option value="">- Elija categoría -</option>
          <?php while ($category = mysqli_fetch_assoc($categories_list)): ?>
          <option value="<?php echo $category['id'] ?>"
              <?php echo $producto->categoria_id == $category['id'] ? 'selected' : '' ?>>
              <?php echo htmlspecialchars($category['categoria'] . " - " . $category['descripcion']) ?>
          </option>
          <?php endwhile; ?>
      </select>
  </fieldset>
  <fieldset>
      <legend>Visible en el catálogo </legend>
      <label for="is_active" class="form-check form-switch">
          <input type="checkbox" id="is_active" name="is_active" class="form-check-input"
              <?php echo($producto->is_active == 1) ? 'checked' : '' ?>>

          <span id="estado-notificacion" class="form-check-label">
              <?php echo($producto->is_active == 1) ? ' activo' : ' inactivo'; ?>
          </span>
      </label>

  </fieldset>