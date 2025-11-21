<div id="products"></div>
<section id="catalog" class="catalog">
    <h2>Catálogo de productos</h2>

    <div class="catalog__grid">


        <!-- 14.11.25 -->
        <article class="card card--split">
            <div class="card__badge">Split</div>

            <picture class="card__picture">
                <!-- WebP único -->
                <source srcset="./build/img/helena-lopes-2MBtXGq4Pfs-unsplash.webp" type="image/webp">

                <!-- JPG con tres tallas -->
                <source srcset="
        ./build/img/helena-lopes-2MBtXGq4Pfs-unsplash-sm.jpg 600w,
        ./build/img/helena-lopes-2MBtXGq4Pfs-unsplash-md.jpg 1024w,
        ./build/img/helena-lopes-2MBtXGq4Pfs-unsplash-lg.jpg 1600w" sizes="(max-width: 600px) 100vw,
               (max-width: 1024px) 50vw,
              33vw" type="image/jpeg">

                <!-- Fallback absoluto -->
                <img class="card__img" src="./build/img/helena-lopes-2MBtXGq4Pfs-unsplash-lg.jpg" alt="Producto split"
                    loading="lazy">
            </picture>

            <div class="card__body">
                <h3 class="card__title">Producto split</h3>
                <p class="card__description">La imagen ocupa el 40% de la altura. Praesent vulputate auctor tellus
                    vel. Praesent vulputate auctor
                    gravida.</p>
                <a href="#" class="card__link">
                    <button class="btn btn--primary">Explore el producto</button>
                </a>
            </div>
        </article>

    </div> <!-- catalog__grid -->
</section> <!-- catalog -->