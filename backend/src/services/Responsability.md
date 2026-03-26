
## Lógica de negocio
Archivos tipo: UserService.php, RoutineService.php
Combinan datos
Aplican reglas
Procesan info

## Ejemplo
function getActiveUsers() {
    $users = User::all();
    return array_filter($users, fn($u) => $u['active']);
}
