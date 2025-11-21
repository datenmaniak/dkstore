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
                <img class="card__img" src="./build/img/akhil-yerabati-Q2uV5TkjNz8-unsplash-lg.jpg" alt="Producto destacado"
                    loading="lazy">
            </picture>

            <h3 class="card__title">Producto minimalista</h3>
            <p class="card__description">Vivamus a hendrerit justo. Maecenas mattis varius consequat. Pellentesque tempor
                leo nec accumsan dapibus. </p>
            <a href="#" class="card__link">
                <button class="btn btn--primary">Ver más detalles</button>
            </a>
        </article>


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