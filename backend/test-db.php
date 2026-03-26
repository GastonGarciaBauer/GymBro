<?php

header('Content-Type: application/json; charset=utf-8');

function respond_json(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

try {
    $conn = require __DIR__ . '/src/config/db_connection.php';

    if (!($conn instanceof mysqli)) {
        respond_json([
            'ok' => false,
            'error' => 'INVALID_CONNECTION_OBJECT',
            'message' => 'db_connection.php no devolvio una conexion mysqli valida.'
        ], 500);
    }

    $result = $conn->query('SELECT 1 AS ok');

    if ($result === false) {
        respond_json([
            'ok' => false,
            'error' => 'QUERY_FAILED',
            'message' => $conn->error
        ], 500);
    }

    respond_json([
        'ok' => true,
        'message' => 'Conexion a DB exitosa.',
        'db' => $conn->host_info
    ]);
} catch (Throwable $e) {
    respond_json([
        'ok' => false,
        'error' => 'DB_CONNECTION_ERROR',
        'message' => $e->getMessage()
    ], 500);
}