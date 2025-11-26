<?php

function conectDB()
{

    $db = mysqli_connect('localhost', 'dk', 'chachita',  'dkstore');

    if (!$db) {
        echo "DB conection error";
        exit;
    }
    return $db;
}
