<?php

function conectDB() {
    $host = 'ws.homelab';  // ← Nombre contenedor
    $db = 'dkstore';
    $user = 'dk';
    $pass = 'chachita';

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
    }
    $conn->set_charset("utf8mb4");
    return $conn;
}
