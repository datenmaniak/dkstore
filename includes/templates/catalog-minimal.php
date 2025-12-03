<div id="products"></div>
<section id="catalog" class="catalog">
    <h2>Catálogo de productos - Minimal </h2>

    <div class="catalog__grid">


        <article class="card card--minimal">
            <!-- Badge en la esquina superior derecha -->
            <div class="card__badge">minimal</div>
            <picture class="card__picture">
                <!-- WebP único -->
                <source srcset="./build/img/akhil-yerabati-Q2uV5TkjNz8-unsplash.webp" type="image/webp">

                <!-- JPG con tres tallas -->
                <source srcset="
      ./build/img/akhil-yerabati-Q2uV5TkjNz8-unsplash-sm.jpg 600w,
      ./build/img/akhil-yerabati-Q2uV5TkjNz8-unsplash-md.jpg 1024w,
      ./build/img/akhil-yerabati-Q2uV5TkjNz8-unsplash-lg.jpg 1600w" sizes="(max-width: 600px) 100vw,
           (max-width: 1024px) 50vw,
           33vw" type="image/jpeg">

                <!-- Fallback por defecto -->
                <img class="card__img" src="./build/img/akhil-yerabati-Q2uV5TkjNz8-unsplash-lg.jpg"
                    alt="Producto destacado" loading="lazy">
            </picture>

            <h3 class="card__title">Producto minimalista</h3>
            <p class="card__description">Vivamus a hendrerit justo. Maecenas mattis varius consequat. Pellentesque
                tempor
                leo nec accumsan dapibus. </p>
            <a href="#" class="card__link">
                <button class="btn btn--primary">Ver más detalles</button>
            </a>
        </article>

    </div> <!-- catalog__grid -->
</section> <!-- catalog -->