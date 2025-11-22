<?php


include  'includes/templates/color-palette.php' ?>;

<div id="products"></div>
<section id="catalog" class="catalog">
    <h2>Catálogo de productos</h2>

    <div class="catalog__grid">

        <article class="card card--standard">
            <!-- <div class="card__badge">En oferta</div> -->

            <picture class="card__picture">
                <!-- WebP único -->
                <source srcset="./build/img/i-m-zion-7MvFxGjWWVs-unsplash.webp" type="image/webp">

                <!-- JPG con tres tallas -->
                <source srcset="
      ./build/img/i-m-zion-7MvFxGjWWVs-unsplash-sm.jpg 600w,
      ./build/img/i-m-zion-7MvFxGjWWVs-unsplash-md.jpg 1024w,
      ./build/img/i-m-zion-7MvFxGjWWVs-unsplash-lg.jpg 1600w" sizes="(max-width: 600px) 100vw,
           (max-width: 1024px) 50vw,
           33vw" type="image/jpeg">

                <!-- Fallback por defecto -->
                <img class="card__img" src="./build/img/i-m-zion-7MvFxGjWWVs-unsplash-lg.jpg" alt="Producto destacado"
                    loading="lazy">
            </picture>


            <h3 class="card__title">Producto estándar</h3>
            <p class="card__description">Praesent vulputate auctor tellus vel gravida. Nam ac commodo nisl, at laoreet odio.
            </p>
            <a href="/" class="card__link">
                <button class="btn-ghost">Ver detalles</button>
            </a>
        </article>


        <article class="card card--standard">
            <div class="card__badge">Standard</div>

            <picture class="card__picture">
                <!-- WebP único -->
                <source srcset="./build/img/fili-santillan-OWfts4TGOlo-unsplash.webp" type="image/webp">

                <!-- JPG con tres tallas -->
                <source srcset="
      ./build/img/fili-santillan-OWfts4TGOlo-unsplash-sm.jpg 600w,
      ./build/img/fili-santillan-OWfts4TGOlo-unsplash-md.jpg 1024w,
      ./build/img/fili-santillan-OWfts4TGOlo-unsplash-lg.jpg 1600w" sizes="(max-width: 600px) 100vw,
           (max-width: 1024px) 50vw,
           33vw" type="image/jpeg">

                <!-- Fallback por defecto -->
                <img class="card__img" src="./build/img/fili-santillan-OWfts4TGOlo-unsplash-lg.jpg" alt="Producto destacado"
                    loading="lazy">
            </picture>


            <h3 class="card__title">Producto estándar</h3>
            <p class="card__description">Praesent vulputate auctor tellus vel gravida. Nam ac commodo nisl, at laoreet odio.
            </p>
            <a href="/" class="card__link">
                <button class="btn btn--primary">Ver detalles</button>
            </a>
        </article>

        <article class="card card--standard">
            <div class="card__badge">Standard</div>

            <picture class="card__picture">
                <!-- WebP único -->
                <source srcset="./build/img/alienware-Hpaq-kBcYHk-unsplash.webp" type="image/webp">

                <!-- JPG con tres tallas -->
                <source srcset="
      ./build/img/alienware-Hpaq-kBcYHk-unsplash-sm.jpg 600w,
      ./build/img/alienware-Hpaq-kBcYHk-unsplash-md.jpg 1024w,
      ./build/img/alienware-Hpaq-kBcYHk-unsplash-lg.jpg 1600w" sizes="(max-width: 600px) 100vw,
           (max-width: 1024px) 50vw,
           33vw" type="image/jpeg">

                <!-- Fallback por defecto -->
                <img class="card__img" src="./build/img/alienware-Hpaq-kBcYHk-unsplash-lg.jpg" alt="Producto destacado"
                    loading="lazy">
            </picture>


            <h3 class="card__title">Producto estándar</h3>
            <p class="card__description">Praesent vulputate auctor tellus vel gravida. Nam ac commodo nisl, at laoreet odio.
            </p>
            <a href="/" class="card__link">
                <button class="btn btn--primary">Ver detalles</button>
            </a>
        </article>
        <!-- akhil-yerabati-Q2uV5TkjNz8-unsplash -->
    </div> <!-- catalog__grid -->
</section> <!-- catalog -->