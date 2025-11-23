<!-- functions.php -->

<?php

require __DIR__ . '/app.php';

function includeTemplate(string $page)
{
    // templates whitelist permit
    $allowed = [
        'header',
        'footer',
        'about-us',
        'ads',
        'catalog-featured',
        'catalog-minimal',
        'catalog-std',
        'catalog-split',
        'color-palette',
        'hero',
        'home'
    ];

    if (in_array($page, $allowed)) {
        require PATH_TEMPLATES . "/$page.php";
    } else {
        echo "$page: Plantilla no permitida.";
    }
}
