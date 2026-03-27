<?php

// Helper reutilizable para responder JSON con status HTTP.
function json_response(array $payload, int $statusCode = 200): void
{
    // Establecemos el codigo de estado HTTP (200, 400, 500, etc.).
    http_response_code($statusCode);
    // Indicamos que la respuesta es JSON en UTF-8.
    header('Content-Type: application/json; charset=utf-8');
    // Convertimos el array PHP a JSON tolerando problemas de UTF-8.
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    // Finalizamos el script para evitar salida adicional no deseada.
    exit;
}
