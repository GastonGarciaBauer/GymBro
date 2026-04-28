<?php

require_once __DIR__ . '/../controllers/ExerciseController.php';


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
