<!-- app.php -->

<?php

    // Activar autoload de Composer
    require_once __DIR__ . '/../vendor/autoload.php';

    define('BASE_PATH', dirname(__DIR__)); // ahora apunta a /dk-store
    define('PATH_INCLUDES', BASE_PATH . '/includes/');
    define('PATH_TEMPLATES', PATH_INCLUDES . '/templates/');
    define('PATH_CONFIG', PATH_INCLUDES . '/config');
    define('PATH_FUNCTIONS', PATH_INCLUDES . '/functions.php');
    define('PATH_UPLOADS', BASE_PATH . '/uploads/');
    define('PATH_ASSETS', BASE_PATH . '/assets');
    define('PATH_IMG', PATH_ASSETS . '/img');
    define('PATH_AUTOLOAD', BASE_PATH . '/vendor');

    // Cargar funciones comunes
    require PATH_FUNCTIONS;

    // Cargar conexión a la base de datos
    require_once PATH_CONFIG . '/database.php';

    // Conexión a la DB
    $db = conectDB();

    use dkstore\Productos;

Productos::setDB($db);