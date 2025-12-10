<?php
// Cargar bootstrap y constantes
require_once __DIR__ . '/includes/app.php';
/*
require_once PATH_FUNCTIONS;                 // carga funciones */

includeTemplate('header');
/* includeTemplate('about-us'); */
includeTemplate('catalog-std-db');
/* includeTemplate('catalog-featured'); */
includeTemplate('footer');
includeTemplate('scripts');
includeTemplate('end-page');
