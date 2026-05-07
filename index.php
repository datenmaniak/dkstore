<?php

    /* declare(strict_types=1); */

    //  before
    // require_once __DIR__ . '/includes/functions.php';

    require_once __DIR__ . '/includes/app.php';

    includeTemplate('header');
    includeTemplate('hero');
    includeTemplate('about-us');
    includeTemplate('catalog-std');
    includeTemplate('catalog-minimal');
    includeTemplate('footer');
    includeTemplate('scripts');
    includeTemplate('end-page');

?>