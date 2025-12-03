<!-- -- Products catalog -->

<?php


require_once PATH_CONFIG . '/database.php';

/* require __DIR__ . '/../functions.php'; */

$images_folder = PATH_UPLOADS . '/';
$default_image = PATH_IMG . '/no-image.jpg';

// Bases de datos
$db = conectDB();

// get the records
$query = "SELECT * FROM productos WHERE eliminado = 0";
// Query Db
$result = mysqli_query($db, $query);




?>

<div id="products"></div>
<section id="catalog" class="catalog">
    <h2>Catálogo de productos - Standard</h2>


    <?php while ($item = mysqli_fetch_assoc($result)):  ?>

    <div class="catalog__grid">

        <article class="card card--standard">
            <div class="card__badge">En oferta</div>

            <picture class="card__picture">

                <!-- Imagen del producto si existe -->
                <?php if (!empty($item['imagen']) && file_exists($images_folder . $item['imagen'])): ?>
                <source srcset="<?php echo $images_folder . $item['imagen']; ?>" type="image/webp">
                <source srcset="<?php echo $images_folder . $item['imagen']; ?>" type="image/*">
                <img class="card__img img-table" src="<?php echo $images_folder . $item['imagen']; ?>"
                    alt="imagen del <?php echo $item['nombre_producto']; ?>" loading="lazy">
                <?php else: ?>
                <!-- Fallback por defecto -->
                <img class="card__img img-table" src="<?php echo PATH_IMG . '/no-image.jpg'; ?>"
                    alt="producto sin imagen" loading="lazy">
                <?php endif; ?>

            </picture>


            <h3 class="card__title"><?php echo $item['nombre_producto']; ?></h3>
            <p>Código: <span><?php echo $item['codigo_sku']; ?></span></p>
            <p class="card__description">
                <?php echo $item['descripcion']; ?>
            </p>
            <p class="card__price">Precio: $
                <?php echo $item['precio']; ?>
            </p>
            <!-- Opciones de botones -->
            <!-- <a href="/" class="card__link"> -->
            <!-- <button class="btn-ghost">Ver más</button> -->
            <!-- </a> -->

            <a href="/" class="card__link">
                <button class="btn btn--primary">Ver detalles</button>
            </a>
        </article>



    </div> <!-- catalog__grid -->
    <?php endwhile; ?>
</section>
<!-- catalog -->