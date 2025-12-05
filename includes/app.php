<!-- app.php -->

<?php
// app.php dentro de /includesx
/* 
define('PATH_TEMPLATES', __DIR__ . '/templates');
define('PATH_FUNCTIONS', __DIR__ . '/functions.php');
define('BASE_PATH', __DIR__); */


define('BASE_PATH', dirname(__DIR__)); // ahora apunta a /dk-store
define('PATH_INCLUDES', BASE_PATH . '/includes');
define('PATH_TEMPLATES', PATH_INCLUDES . '/templates');
define('PATH_CONFIG', PATH_INCLUDES . '/config');
define('PATH_FUNCTIONS', PATH_INCLUDES . '/functions.php');
define('PATH_UPLOADS', BASE_PATH . '/uploads');
define('PATH_ASSETS', BASE_PATH . '/assets');
define('PATH_IMG', PATH_ASSETS . '/img');


// Cargar funciones comunes
require_once PATH_FUNCTIONS;

// Cargar conexión a la base de datos
require_once PATH_CONFIG . '/database.php';
