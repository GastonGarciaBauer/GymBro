<?php

// Importamos la configuracion de conexion a base de datos.
require_once __DIR__ . '/../config/db_connection.php';

// Modelo encargado de las consultas de grupos musculares.
class MuscleGroupModel
{
    // Metodo estatico para obtener todos los grupos como arreglo.
    public static function getAll(): array
    {
        // Abrimos una conexion a la base de datos.
        $conn = get_db_connection();
        // Definimos la consulta SQL ordenada alfabeticamente por nombre.
        $sql = "SELECT id, name FROM muscle_groups ORDER BY name ASC";
        // Ejecutamos la consulta.
        $result = $conn->query($sql);

        // Si la consulta fallo, lanzamos excepcion para que el controller la maneje.
        if ($result === false) {
            throw new RuntimeException('Failed to query muscle groups.');
        }

        // Inicializamos el arreglo que vamos a devolver.
        $rows = [];
        // Recorremos cada fila del resultado como arreglo asociativo.
        while ($row = $result->fetch_assoc()) {
            // Mapeamos cada registro al formato esperado por la API.
            $rows[] = [
                // Convertimos id a entero para tipado consistente.
                'id' => (int) $row['id'],
                // Copiamos el nombre del grupo muscular.
                'name' => $row['name']
            ];
        }

        // Liberamos memoria del resultset.
        $result->free();
        // Cerramos la conexion para no dejar recursos abiertos.
        $conn->close();

        // Retornamos todos los grupos listos para el controller.
        return $rows;
    }
}
