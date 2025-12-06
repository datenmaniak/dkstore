<?php

function conectDB(): mysqli
{

    $db = new mysqli('localhost', 'dk', 'chachita', 'dkstore');

    if (! $db) {
        echo "DB conection error";
        exit;
    }
    return $db;
}