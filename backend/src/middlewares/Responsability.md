
## Se ejecutan antes de llegar al controller
Archivos tipo: authMiddleware.php, corsMiddleware.php
Validar token/login
Permisos
Logs
CORS

## Ejemplo
function checkAuth() {
    if (!isset($_SESSION['user'])) {
    http_response_code(401);
exit;}
}