<?php

// function conectDB(): mysqli
// {
//
//     $db = new mysqli('localhost', 'dk', 'chachita', 'dkstore');
//
//     if (! $db) {
//         echo "DB conection error";
//         exit;
//     }
//     return $db;
// }

// <?php
function conectDB() {
    $host = 'db-dev';  // ← Nombre contenedor
    $db = 'dkstore_db';
    $user = 'dkstore_user';
    $pass = 'dkstore123';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
