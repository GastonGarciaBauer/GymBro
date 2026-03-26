
## Qué URL hace qué cosa?
Archivos tipo: userRoutes.php, workoutRoutes.php
Detectan la request (GET, POST, etc)
Llaman al controller correcto

## Ejemplo
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getUsers();
}
?>