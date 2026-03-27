<?php

// Configuramos mysqli para que lance excepciones en errores.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Funcion que crea y retorna una conexion mysqli.
function get_db_connection(): mysqli
{

    $username = "root";
    $password = "";
    $database = "gymbro";
    $host = "localhost";
    $port = 3307;

    // Creamos la conexion con los parametros definidos arriba.
    $conn = new mysqli($host, $username, $password, $database, $port);
    // Fijamos charset UTF-8 completo para evitar problemas de acentos/emojis.
    $conn->set_charset('utf8mb4');

    // Retornamos la conexion abierta y lista para usar.
    return $conn;
}

// Este archivo devuelve directamente una conexion al ser requerido.
return get_db_connection();
