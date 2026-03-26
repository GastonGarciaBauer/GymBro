
## Herramientas reutilizables
Archivos tipo: helpers.php, response.php
Formatear respuestas
Validaciones simples
Funciones auxiliares

## Ejemplo
function jsonResponse($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
}