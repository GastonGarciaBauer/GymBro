<?php

// CORS: el frontend en desarrollo (p. ej. Vite :5173) llama al API en otro origen (:8080).
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Cargamos archivos de rutas y helpers.
require_once __DIR__ . '/src/routes/MuscleGroupRoutes.php';
require_once __DIR__ . '/src/routes/ExerciseRoutes.php';
require_once __DIR__ . '/src/utils/response.php';

// Leemos el metodo HTTP de la request
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Preflight CORS sin body JSON
if ($method === 'OPTIONS') {
    http_response_code(204);
    exit;
}
// Leemos la URI completa solicitada por el cliente.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
// Nos quedamos solo con la parte del path, sin query params.
$path = parse_url($requestUri, PHP_URL_PATH) ?? '/';

// Permitimos que funcione tanto con /index.php/... como sin /index.php.
$path = preg_replace('#^/GymBro/backend/index\.php#', '', $path);
// Quitamos el prefijo base del proyecto para quedarnos con la ruta logica.
$path = preg_replace('#^/GymBro/backend#', '', $path);

// Si la ruta queda vacia o invalida, la normalizamos a "/".
if ($path === '' || $path === false) {
    $path = '/';
}

// Intentamos resolver la request contra las rutas de muscle-groups.
if (handle_muscle_group_routes($method, $path)) {
    exit;
}

// Intentamos resolver la request contra las rutas de exercises.
if (handle_exercise_routes($method, $path)) {
    exit;
}

// Si ninguna ruta coincide, devolvemos error 404 en JSON.
json_response([
    // Codigo interno de error para ruta inexistente.
    'error' => 'NOT_FOUND',
    // Mensaje legible para quien consume la API.
    'message' => 'Route not found.'
// Indicamos estado HTTP 404 (no encontrado).
], 404);
