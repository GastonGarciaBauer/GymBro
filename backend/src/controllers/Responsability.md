
## Reciben la request y devuelven la respuesta
Archivos tipo: UserController.php, WorkoutController.php
Llaman a services o models

## Ejemplo
function getUsers() {
    $users = User::all();
    echo json_encode($users);}
