<?php

// Configuración de conexión 


$host = "localhost";
$dbname = "dkstore";
$user = "dk";
$pass = "chachita";


try {
    //  Conexión con PDO

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexión establecida correctamente.\n";

    // Arreglo de usuarios con datos esenciales

    $usuarios = [
        [
            "username" => "usuario1",
            "password" => "claveUsuario1"
        ],
        [
            "username" => "admin1",
            "password" => "claveAdmin1"
        ],
        [
            "username" => "usuario",
            "password" => "12qwaszx"
        ]
    ];


    // Procesar cada usuario
    foreach ($usuarios as $u) {
        $hash = password_hash($u["password"], PASSWORD_BCRYPT);

        $update = $pdo->prepare("UPDATE users SET password_hash = :hash WHERE username = :username");


        $update->execute([
            ":hash" => $hash,
            ":username" => $u["username"]
        ]);

        echo "Usuario {$u['username']} actualizado con hash.\n";
    }

    echo "Proceso completado.\n";
} catch (PDOException $e) {
    echo "Error en la conexión o consulta: " . $e->getMessage();
}
