<section id=" about-us" class="about-us">
    <div class="about-us__container">
        <div class="about-us__img">
            <picture class="card__picture">
                <!-- WebP único -->
                <source srcset="./build/img/2965738.webp" type="image/webp">

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
            <!-- <img src="img/about-us/helena-lopes-2MBtXGq4Pfs-unsplash.jpg" alt="" /> -->
        </div>
        <div class="about-us__description">
            <h2>Quiénes somos</h2>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing eli quis modi aut
                delectus fuga saepe sed. harum dolorum nihil, labore ut facilis
                aperiam pariatur ipsum.
                harum dolorum nihil, labore ut facilis
                aperiam pariatur ipsum. fuga saepe sed
            </p>
        </div>
    </div>
</section>