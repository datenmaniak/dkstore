<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
// ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DK Electronic Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
        integrity="sha512-XcIsjKMcuVe0Ucj/xgIXQnytNwBttJbNjltBV18IOnru2lDPe9KRRyvCXw6Y5H415vbBLRm8+q6fmLUU7DfO6Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/build/css/main.css" />
</head>

<body>
    <!-- BEGIN -->
    <header class="header" data-header>
        <div class="header__container">

            <div class="logo__container">
                <!-- <p class="mobileMenu"
                    aria-label="Abrir menú"
                    aria-expanded="false">
                </p> -->
                <!-- src="assets/logo.svg" -->
                <img src="/build/img/9391712.webp" alt="Logo " />
                <!-- <img src="./assets/logos/logo-globo.png" alt="Logo " /> -->
                <a href="/" class="branding" aria-label="Inicio">
                    <p>
                        <span class="site-name">DK Electronic</span>

                    </p>
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
                    <li><a href="/admin/index.php">Admin</a></li>
                    <li><a href="/productos.php">Productos</a></li>
                    <li><a href="">Blog</a></li>
                    <li><a href="">Contacto</a></li>


                    <!-- Último elemento: acceso a cuenta -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- User has logged -->
                        <li class="nav__login">
                            <a href="/admin/logout.php">Cerrar Sessión</a>
                        </li>
                    <?php else: ?>

                        <li class="nav__login">
                            <a href="/admin/login.php">Mi cuenta</a>
                        </li>
                    <?php endif; ?>
                    <li class="dark-mode-button">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="..." />
                        </svg>
                    </li>
                    <li class="mobileMenu"></li>
                </ul>
                <!--  mobile option -->
                <div class="menuToggle" aria-label="Abrir menú" role="menuToggle" aria-expanded="false">
                </div>
            </nav>

            <!-- Botón para abrir/cerrar el menú -->
            <div class="overlay"></div>
        </div>
    </header>