<?php

require_once __DIR__ . '/../models/ExerciseModel.php';
require_once __DIR__ . '/../utils/response.php';

function list_exercises(): void
{
    try {
        // --- Parametro opcional: search (texto libre en el nombre del ejercicio) ---

        $search = null;
        if (isset($_GET['search'])) {
            $rawSearch = $_GET['search'];
            if (!is_string($rawSearch)) {
                json_response([
                    'error' => 'VALIDATION_ERROR',
                    'message' => 'search must be a single value.',
                ], 400);
                return;
            }
            $trimmed = trim($rawSearch);
            if ($trimmed !== '') {
                $search = $trimmed;
            }
        }

        // --- Parametro opcional: muscle_group_id (entero positivo) ---

        $muscleGroupId = null;
        if (isset($_GET['muscle_group_id'])) {
            // En PHP un mismo nombre puede repetirse en la query y llegar como array; el MVP solo acepta string.
            $rawGroup = $_GET['muscle_group_id'];
            if (!is_string($rawGroup)) {
                json_response([
                    'error' => 'VALIDATION_ERROR',
                    'message' => 'muscle_group_id must be a single value.',
                ], 400);
                return;
            }
            // Regla MVP: debe ser entero positivo; ctype_digit (solo digitos 0-9, sin signo).
            if ($rawGroup === '' || !ctype_digit($rawGroup) || (int) $rawGroup < 1) {
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
            $rawLimit = $_GET['limit'];
            if (!is_string($rawLimit)) {
                json_response([
                    'error' => 'VALIDATION_ERROR',
                    'message' => 'limit must be a single value.',
                ], 400);
                return;
            }
            if ($rawLimit === '' || !ctype_digit($rawLimit) || (int) $rawLimit < 1) {
                json_response([
                    // Misma familia de error que muscle_group_id.
                    'error' => 'VALIDATION_ERROR',
                    // Explica que limit debe ser un entero > 0.
                    'message' => 'limit must be a positive integer.',
                ], 400);
                return;
            }
            // Convertimos y acotamos (contrato MVP: max 100).
            $limit = min(100, max(1, (int) $rawLimit));
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
        // En produccion no exponemos $e->getMessage() al cliente.
        json_response([
            'error' => 'INTERNAL_ERROR',
            'message' => 'Unexpected server error.',
        ], 500);
    }
}

