<?php

// Cargamos el controller que contiene la logica del endpoint.
require_once __DIR__ . '/../controllers/MuscleGroupController.php';

// Funcion enrutadora: recibe metodo y path y dice si pudo manejar la request.
function handle_muscle_group_routes(string $method, string $path): bool
{
    // Si el cliente pide GET /muscle-groups, ejecutamos el controller.
    if ($method === 'GET' && $path === '/muscle-groups') {
        // Ejecuta el caso de uso para listar grupos musculares.
        list_muscle_groups();
        // Indicamos que esta ruta si fue atendida.
        return true;
    }

    // Si no coincide, avisamos que esta request no pertenece a este router.
    return false;
}
