<?php

// Traemos la funcion que abre conexion mysqli segun tu configuracion.
require_once __DIR__ . '/../config/db_connection.php';

// Modelo: solo acceso a datos de la tabla exercises (y join con muscle_groups).
class ExerciseModel
{
    /**
     * Lista ejercicios aplicando filtros opcionales desde la query string.
     *
     * @param string|null $search Texto para buscar en e.name (LIKE parcial), o null si no filtra.
     * @param int|null $muscleGroupId ID de grupo muscular, o null si no filtra.
     * @param int $limit Cantidad maxima de filas (ya acotada en el controller).
     * @return array Lista de ejercicios en el formato de la API (incluye muscle_group anidado).
     */
    public static function findWithFilters(?string $search, ?int $muscleGroupId, int $limit): array
    {
        // Abrimos conexion a MySQL para esta consulta.
        $conn = get_db_connection();

        // Partes del WHERE que iremos armando solo si el filtro viene informado.
        $whereParts = [];
        // Cadena de tipos para bind_param: 's' = string, 'i' = entero (orden igual que $bindValues).
        $types = '';
        // Valores que se enlazan de forma segura a los placeholders ? del SQL.
        $bindValues = [];

        // Si hay texto de busqueda, agregamos condicion por nombre (coincidencia parcial).
        if ($search !== null) {
            // LIKE con % permite encontrar "Press" dentro de "Press de banca".
            $whereParts[] = 'e.name LIKE ?';
            // Marcamos el siguiente placeholder como string.
            $types .= 's';
            // Valor que reemplaza al ? (los % van en el string enlazado).
            $bindValues[] = '%' . $search . '%';
        }

        // Si viene grupo muscular, filtramos por clave foranea.
        if ($muscleGroupId !== null) {
            // Comparacion exacta por ID (entero).
            $whereParts[] = 'e.muscle_group_id = ?';
            // Placeholder entero.
            $types .= 'i';
            // ID numerico enlazado (evita inyeccion SQL).
            $bindValues[] = $muscleGroupId;
        }

        // Si no hubo filtros, WHERE verdadero para traer todas las filas (hasta LIMIT).
        $whereSql = count($whereParts) > 0 ? implode(' AND ', $whereParts) : '1=1';

        // Consulta con alias e/mg para leer ejercicio y nombre del grupo en un solo viaje.
        // INNER JOIN: solo ejercicios con grupo existente (coherente con FK en DB).
        $sql = '
            SELECT e.id, e.name, e.image_url, e.description, mg.id AS mg_id, mg.name AS mg_name
            FROM exercises e
            INNER JOIN muscle_groups mg ON mg.id = e.muscle_group_id
            WHERE ' . $whereSql . '
            ORDER BY e.name ASC
            LIMIT ' . $limit . '
        ';

        // Compila el SQL en el servidor MySQL (plan + placeholders).
        $stmt = $conn->prepare($sql);
        // Si prepare falla, algo esta mal en el SQL o en la conexion.
        if ($stmt === false) {
            // Cerramos conexion antes de salir con error logico.
            $conn->close();
            // El controller puede convertir esto en 500 JSON controlado.
            throw new RuntimeException('Failed to prepare exercise query.');
        }

        // Si hay placeholders, enlazamos valores (orden de tipos = orden de valores).
        if ($types !== '') {
            // ...$bindValues expande el array como argumentos de bind_param (PHP 8+).
            $stmt->bind_param($types, ...$bindValues);
        }

        // Ejecuta la consulta con los valores enlazados.
        if (!$stmt->execute()) {
            // Limpieza de recursos.
            $stmt->close();
            $conn->close();
            // Fallo en ejecucion (timeout, error de servidor, etc.).
            throw new RuntimeException('Failed to execute exercise query.');
        }

        // Obtenemos mysqli_result para iterar filas como arrays asociativos.
        $result = $stmt->get_result();
        // Arreglo PHP que devolveremos en forma de lista de ejercicios.
        $rows = [];

        // Una fila por ejercicio encontrado.
        while ($row = $result->fetch_assoc()) {
            // Cada elemento cumple el contrato MVP: datos del ejercicio + grupo anidado.
            $rows[] = [
                // Identificador numerico estable del ejercicio.
                'id' => (int) $row['id'],
                // Nombre visible para el usuario.
                'name' => $row['name'],
                // URL o ruta de la imagen (la guardaste asi en la base).
                'image_url' => $row['image_url'],
                // Texto largo de descripcion.
                'description' => $row['description'],
                // Objeto anidado: grupo muscular segun contrato api-contract-mvp.
                'muscle_group' => [
                    // ID del grupo (desde el JOIN, alias mg_id).
                    'id' => (int) $row['mg_id'],
                    // Nombre del grupo (alias mg_name).
                    'name' => $row['mg_name'],
                ],
            ];
        }

        // Liberamos el statement (memoria en el driver).
        $stmt->close();
        // Cerramos conexion porque este metodo abrio una conexion nueva.
        $conn->close();

        // Lista final (puede ser vacia si no hubo coincidencias).
        return $rows;
    }
}
