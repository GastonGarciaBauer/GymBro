<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function get_db_connection(): mysqli
{
    $username = "root";
    $password = "";
    $database = "gymbro";
    $host = "localhost";
    $port = 3307;

    $conn = new mysqli($host, $username, $password, $database, $port);
    $conn->set_charset('utf8mb4');

    return $conn;
}

return get_db_connection();
