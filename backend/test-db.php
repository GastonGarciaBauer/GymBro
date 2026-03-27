<?php

// Indicamos que esta respuesta sera JSON en UTF-8.
header('Content-Type: application/json; charset=utf-8');

// Helper local para responder JSON y terminar ejecucion.
function respond_json(array $payload, int $statusCode = 200): void
{
    // Asignamos el codigo HTTP de la respuesta.
    http_response_code($statusCode);
    // Convertimos el payload PHP a JSON de forma robusta.
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    // Cortamos la ejecucion para evitar salida extra.
    exit;
}

// Iniciamos bloque protegido para capturar excepciones.
try {
    // Cargamos la conexion desde el archivo de configuracion.
    $conn = require __DIR__ . '/src/config/db_connection.php';

    // Verificamos que el require devolvio un objeto mysqli valido.
    if (!($conn instanceof mysqli)) {
        // Si no es valido, devolvemos error controlado.
        respond_json([
            // Bandera booleana de resultado.
            'ok' => false,
            // Codigo de error interno.
            'error' => 'INVALID_CONNECTION_OBJECT',
            // Mensaje explicando el problema.
            'message' => 'db_connection.php no devolvio una conexion mysqli valida.'
        // Estado 500 porque es fallo del servidor/config.
        ], 500);
    }

    // Ejecutamos una query minima para comprobar que MySQL responde.
    $result = $conn->query('SELECT 1 AS ok');

    // Si la query falla, devolvemos error controlado.
    if ($result === false) {
        // Enviamos el error SQL al cliente (solo para pruebas locales).
        respond_json([
            // Bandera booleana de resultado.
            'ok' => false,
            // Codigo de error interno.
            'error' => 'QUERY_FAILED',
            // Mensaje tecnico devuelto por mysqli.
            'message' => $conn->error
        // Estado 500 por fallo del servidor/DB.
        ], 500);
    }

    // Si todo salio bien, devolvemos confirmacion de conexion exitosa.
    respond_json([
        // Bandera de exito.
        'ok' => true,
        // Mensaje legible para validar rapidamente en navegador/Postman.
        'message' => 'Conexion a DB exitosa.',
        // Info del host de conexion para diagnostico.
        'db' => $conn->host_info
    // Estado por defecto 200 OK.
    ]);
// Capturamos cualquier excepcion inesperada.
} catch (Throwable $e) {
    // Respondemos error controlado.
    respond_json([
        // Bandera booleana de resultado.
        'ok' => false,
        // Codigo interno de error de conexion.
        'error' => 'DB_CONNECTION_ERROR',
        // Mensaje tecnico de la excepcion capturada.
        'message' => $e->getMessage()
    // Estado 500 de error de servidor.
    ], 500);
}