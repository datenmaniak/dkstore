<!-- -- Products catalog -->

<?php


require_once PATH_CONFIG . '/database.php';

/* require __DIR__ . '/../functions.php'; */

$images_folder = PATH_UPLOADS . '/';
$default_image = PATH_IMG . '/no-image.jpg';
$images_folder_fs = PATH_UPLOADS . '/';       // filesystem
$images_folder_url = '/uploads/';             // URL pública
$default_image_url = '/assets/img/no-image.jpg';


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


    <?php while ($item = mysqli_fetch_assoc($result)):

        if (!empty($item['imagen']) && file_exists($images_folder_fs . $item['imagen'])) {
            $image_url = $images_folder_url . $item['imagen'];
        } else {
            $image_url = $default_image_url;
        }


    ?>

        <div class="catalog__grid">

            <article class="card card--standard">
                <div class="card__badge">En oferta</div>

                <picture class="card__picture">

                    <!-- Imagen del producto si existe -->
                    <?php if (!empty($item['imagen']) && file_exists($images_folder_fs . $item['imagen'])): ?>

                        $image_url = $images_folder_url . $item['imagen'];

                        <source srcset="<?php echo $image_url; ?>" type="image/webp">
                        <source srcset="<?php echo $image_url; ?>" type="image/*">
                        <img class="card__img img-table" src="<?php echo $images_url; ?>" alt="imagen del producto"
                            loading="lazy">
                    <?php else: ?>
                        <!-- Fallback por defecto -->

                        $image_url = '/assets/img/no-image.jpg';

                        <img class="card__img img-table" src="<?php echo $image_url; ?>" alt="producto sin imagen"
                            loading="lazy">
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