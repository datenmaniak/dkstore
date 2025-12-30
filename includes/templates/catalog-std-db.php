<!-- -- Products catalog -->

<?php

// before
// require_once PATH_CONFIG . '/database.php';
/* require __DIR__ . '/../functions.php'; */

// Se simplifica
require_once __DIR__ . '/../../includes/app.php';

$images_folder     = PATH_UPLOADS . '/';
$default_image     = PATH_IMG . '/no-image.jpg';
$images_folder_fs  = PATH_UPLOADS . '/'; // filesystem
$images_folder_url = '/uploads/';        // URL pública
$default_image_url = '/assets/img/no-image.png';

// Bases de datos
$db = conectDB();

// get the records.
$query = "SELECT * FROM products WHERE is_deleted = 0 AND status = 'active' ORDER BY updated_at DESC;";
// Excluye los que tienen un soft-deleted y aquellos que ha sido marcado
// para mostrarse en el catálogo.

// Query Db
$result = mysqli_query($db, $query);

?>

<!-- <div id="products"></div> -->
<section id="catalog" class="catalog">
    <h2>Catálogo de productos - Standard</h2>

    <div class="catalog__grid">

        <?php while ($item = mysqli_fetch_assoc($result)):

            if (! empty($item['product_image']) && file_exists($images_folder_fs . $item['product_image'])) {
                $image_url = $images_folder_url . $item['product_image'];
            } else {
                $image_url = $default_image_url;
            }
        ?>
            <!-- Estrellas en la esquina superior izquierda -->
            <!-- <div class="card__stars">★★★★★</div> -->

            <article class="card card--standard">
                <div class="card__badge">En oferta</div>

                <picture class="card__picture">
                    <source srcset="<?php echo $image_url; ?>" type="image/webp">
                    <source srcset="<?php echo $image_url; ?>" type="image/*">
                    <img class="card__img" src="<?php echo $image_url; ?>" alt="imagen del producto" loading="lazy">
                </picture>

                <h3 class="card__title"><?php echo htmlspecialchars($item['product_name']); ?></h3>
                <p>Código: <code><?php echo htmlspecialchars($item['sku']); ?></code></p>

                <p class="card__description"><?php echo htmlspecialchars($item['description']); ?></p>
                <p class="card__price">
                    <span>US$</span>
                    <span class="price-value">
                        <?php echo number_format($item['price'], 2, ',', '.'); ?>
                    </span>
                </p>
                <!-- Opciones de botones -->
                <!-- <a href="/" class="card__link"> -->
                <!-- <button class="btn-ghost">Ver más</button> -->
                <!-- </a> -->

                <a href="/" class="card__link">
                    <button class="btn-secondary btn-block-50">Ver detalles</button>
                </a>

            </article>

        <?php endwhile; ?>
    </div> <!-- catalog__grid -->
</section>
<!-- catalog -->


<?php mysqli_close($db); ?>