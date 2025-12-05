<!-- index.php -->
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

?>

<!-- // include  'includes/templates/header.php';
// include  'includes/templates/hero.php';
// include  'includes/templates/about-us.php';
// include  'includes/templates/catalog-std.php';
// include  'includes/templates/catalog-minimal.php';
// include  'includes/templates/footer.php'; -->