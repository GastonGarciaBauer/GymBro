<?php

require_once __DIR__ . '/../models/ExerciseModel.php';
require_once __DIR__ . '/../utils/response.php';

/**
 * Controller del endpoint GET /exercises.
 * Los filtros llegan como query params en la URL (?search=...&muscle_group_id=...&limit=...).
 * PHP los expone automaticamente en el array superglobal $_GET.
 */
function list_exercises(): void
{
    // Capturamos cualquier fallo inesperado y respondemos 500 generico (sin filtrar detalles).
    try {
        // --- Parametro opcional: search (texto libre en el nombre del ejercicio) ---

        // Valor por defecto: no filtrar por nombre.
        $search = null;
        // isset: solo intentamos leer si el cliente mando la clave en la query string.
        if (isset($_GET['search'])) {
            // Cast a string y trim: quitamos espacios al inicio y al final.
            $trimmed = trim((string) $_GET['search']);
            // Si despues de limpiar queda algo, ese es el filtro; si no, queda null (sin filtro).
            if ($trimmed !== '') {
                $search = $trimmed;
            }
        }

        // --- Parametro opcional: muscle_group_id (entero positivo) ---

        // null = el cliente no pidio filtrar por grupo.
        $muscleGroupId = null;
        // array_key_exists distingue "no enviado" de "enviado vacio" (util para validar).
        if (array_key_exists('muscle_group_id', $_GET)) {
            // Valor crudo tal cual llega por URL (siempre string en $_GET).
            $rawGroup = $_GET['muscle_group_id'];
            // Regla MVP: debe ser entero positivo; usamos ctype_digit (solo digitos 0-9, sin signo).
            if ($rawGroup === '' || !ctype_digit((string) $rawGroup) || (int) $rawGroup < 1) {
                // Respuesta 400 segun contrato: parametro de filtro invalido.
                json_response([
                    // Codigo de error acordado para validacion.
                    'error' => 'VALIDATION_ERROR',
                    // Mensaje claro para quien integra el front.
                    'message' => 'muscle_group_id must be a positive integer.',
                ], 400);
                return;
            }
            // Convertimos a int para el modelo y para el SQL tipado.
            $muscleGroupId = (int) $rawGroup;
        }

        // --- Parametro opcional: limit (cantidad maxima de filas, default 50, max 100) ---

        // Valor por defecto alineado con api-contract-mvp.
        $limit = 50;
        // Si el cliente envio limit, validamos formato y rango.
        if (isset($_GET['limit'])) {
            // Valor crudo de la query string.
            $rawLimit = $_GET['limit'];
            // Misma idea: entero positivo solo con digitos.
            if ($rawLimit === '' || !ctype_digit((string) $rawLimit) || (int) $rawLimit < 1) {
                json_response([
                    // Misma familia de error que muscle_group_id.
                    'error' => 'VALIDATION_ERROR',
                    // Explica que limit debe ser un entero > 0.
                    'message' => 'limit must be a positive integer.',
                ], 400);
                return;
            }
            // Convertimos y acotamos para no traer millones de filas por accidente.
            $limit = (int) $rawLimit;
        }

        // Llamada al modelo: aplica filtros en SQL (AND si vienen ambos).
        $exercises = ExerciseModel::findWithFilters($search, $muscleGroupId, $limit);

        // Respuesta exitosa con forma del contrato: data + meta.total.
        json_response([
            // Lista de ejercicios (puede ser [] si no hubo coincidencias).
            'data' => $exercises,
            // meta.total en MVP: cantidad de elementos devueltos en esta respuesta (respeta limit).
            'meta' => [
                // count sobre el arreglo ya armado (sin segunda query a la DB).
                'total' => count($exercises),
            ],
        ], 200);
    } catch (Throwable $e) {
        
        json_response([
            // Codigo interno para errores de servidor.
            'error' => 'INTERNAL_ERROR',
            'message' => 'Unexpected server error.',
        ], 500);
    }
}
