<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DK Electronic Store</title>
    <link rel="stylesheet" href="/build/css/main.css" />
</head>

<body>
    <!-- BEGIN -->
    <header class="header" data-header>
        <div class="header__container">
            <div class="logo__container">
                <a href="/" class="branding" aria-label="Inicio">
                    <!-- src="assets/logo.svg" -->
                    <img src="/build/img/9391712.webp" alt="Logo " />
                    <!-- <img src="./assets/logos/logo-globo.png" alt="Logo " /> -->
                    <span class="site-name">DK Electronic</span>
                </a>
            </div>
            <nav>
                <div class="top-bar">
                    <!-- <p class="propaganda"> Oferta Black Friday toda la semana! </p> -->
                </div>
                <ul class="nav__options">
                    <div class="nav__branding">
                        <img src="/build/img/9391712.webp" alt="Logo Violet Pulse" />
                        <!-- <img src="./assets/logos/logo-globo.png" alt="Logo Violet Pulse" width="50" height="50" /> -->
                        <span class="site-name">DK Electronic</span>
                    </div>
                    <li><a href="/">Inicio </a></li>
                    <li><a href="/admin/admin.php">Admin</a></li>
                    <li><a href="/productos.php">Productos</a></li>
                    <li><a href="">Blog</a></li>
                    <li><a href="">Contacto</a></li>
                    <!-- Último elemento: acceso a cuenta -->
                    <li class="nav__login"><a href="/login.php" class="">Mi cuenta</a>
                        <!-- <a href="login.html" class="btn-ghost">Mi cuenta</a> -->
                    </li>
                    <!-- <li class="dark-mode-button"></li> -->
                    <li class="dark-mode-button">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="..." />
                        </svg>
                    </li>

                    <!-- <img class="dark-mode-button" src="./assets/dark-mode/sunny-outline.svg" alt="dark-mode"> -->

                </ul>
            </nav>
            <!--  mobile option -->
            <div class="menuToggle" aria-label="Abrir menú" aria-expanded="false"></div>

            <!-- Botón para abrir/cerrar el menú -->
            <div class="overlay"></div>
        </div>
    </header>