<div id="products"></div>
<section id="catalog" class="catalog">
    <h2>Catálogo de productos</h2>

    <div class="catalog__grid">


        <article class="card card--featured">
            <!-- Estrellas en la esquina superior izquierda -->
            <div class="card__stars">★★★★★</div>

            <!-- Badge en la esquina superior derecha -->
            <div class="card__badge">Premium</div>

            <picture class="card__picture">
                <!-- WebP único -->
                <source srcset="./build/img/daniel-korpai-oOlPR-Fwd7A-unsplash.webp" type="image/webp">

                <!-- JPG con tres tallas -->
                <source srcset="
      ./build/img/daniel-korpai-oOlPR-Fwd7A-unsplash-sm.jpg 600w,
      ./build/img/daniel-korpai-oOlPR-Fwd7A-unsplash-md.jpg 1024w,
      ./build/img/idaniel-korpai-oOlPR-Fwd7A-unsplash-lg.jpg 1600w" sizes="(max-width: 600px) 100vw,
           (max-width: 1024px) 50vw,
           33vw" type="image/jpeg">

                <!-- Fallback por defecto -->
                <img class="card__img" src="./build/img/daniel-korpai-oOlPR-Fwd7A-unsplash-lg.jpg" alt="Producto destacado"
                    loading="lazy">
            </picture>

            <h3 class="card__title">Producto destacado</h3>
            <p class="card__description">Vivamus a hendrerit justo. Maecenas mattis varius consequat. Pellentesque tempor
                leo nec accumsan dapibus. </p>
            <a href="#" class="card__link">
                <button class="btn btn--primary">Ver más detalles</button>
            </a>
            <!-- daniel-korpai-oOlPR-Fwd7A-unsplash -->
        </article>

    </div> <!-- catalog__grid -->
</section> <!-- catalog -->