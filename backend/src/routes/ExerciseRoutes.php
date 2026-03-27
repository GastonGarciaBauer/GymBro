<?php

// Incluye list_exercises() y toda la cadena controller -> model.
require_once __DIR__ . '/../controllers/ExerciseController.php';

/**
 * Enrutador del recurso exercises.
 * Importante: los query params (?search=...) NO forman parte de $path.
 * $path sigue siendo "/exercises"; $_GET se rellena solo al parsear la URL completa.
 *
 * @param string $method Metodo HTTP (GET, POST, ...).
 * @param string $path Ruta logica ya normalizada por index.php (ej. "/exercises").
 * @return bool true si esta request correspondia a exercises y ya se manejo (o se respondio).
 */
function handle_exercise_routes(string $method, string $path): bool
{
    // Solo atendemos listado con GET en la ruta exacta /exercises.
    if ($method === 'GET' && $path === '/exercises') {
        // list_exercises lee filtros desde $_GET y termina con json_response (exit).
        list_exercises();
        // Si algun dia quitas el exit del helper, el dispatcher sabe que ya hubo match.
        return true;
    }

    // Esta request no es para este router; index.php puede seguir probando otras rutas.
    return false;
}
