<?php

// Cargamos el modelo que consulta la base de datos.
require_once __DIR__ . '/../models/MuscleGroupModel.php';
// Cargamos el helper de respuestas JSON.
require_once __DIR__ . '/../utils/response.php';

// Controller del endpoint GET /muscle-groups.
function list_muscle_groups(): void
{
    // Usamos try/catch para capturar errores y responder de forma controlada.
    try {
        // Pedimos al modelo la lista completa de grupos musculares.
        $groups = MuscleGroupModel::getAll();
        // Respondemos en formato del contrato: objeto con clave data.
        json_response(['data' => $groups], 200);
    // Capturamos cualquier error o excepcion del flujo.
    } catch (Throwable $e) {
        // Respondemos error generico para no filtrar detalles internos.
        json_response([
            // Codigo de error interno estandar.
            'error' => 'INTERNAL_ERROR',
            // Mensaje amigable para el consumidor de la API.
            'message' => 'Unexpected server error.'
        // Indicamos HTTP 500 (error de servidor).
        ], 500);
    }
}
