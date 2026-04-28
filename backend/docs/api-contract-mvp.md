# GymBro - API Contract MVP

## Objetivo
Permitir búsqueda y filtrado de ejercicios en una sola pantalla.

## Base URL
`http://localhost:8080/GymBro/backend/index.php`

---

## 1) Listar grupos musculares

### Endpoint
`GET /muscle-groups`

### Query params
Ninguno.

### Respuesta 200 (OK)
{
  "data": [
    { "id": 1, "name": "Pecho" },
    { "id": 2, "name": "Espalda" },
    { "id": 3, "name": "Piernas" }
  ]
}

## Manejo de Errores

500 Internal Server Error
{
  "error": "INTERNAL_ERROR",
  "message": "Unexpected server error."
}

## 2) Listar ejercicios (con búsqueda y filtro)

### Endpoint
`GET /exercises`

### Query params
search (string): texto a buscar en nombre del ejercicio.
muscle_group_id (number): id del grupo muscular.
limit (number, opcional): máximo resultados (default 50).

Reglas de búsqueda
Si no viene ningún parámetro: devuelve todos (limitados por limit).
Si viene search: filtra por coincidencia parcial en name.
Si viene muscle_group_id: filtra por grupo muscular.
Si vienen ambos: aplica ambos filtros (AND).

### Respuesta 200 (OK)
{
  "data": [
    {
      "id": 10,
      "name": "Press de banca",
      "image_url": "https://.../press-banca.jpg",
      "description": "Ejercicio compuesto para pectoral...",
      "muscle_group": {
        "id": 1,
        "name": "Pecho"
      }
    }
  ],
  "meta": {
    "total": 1
  }
}

Respuesta 200 sin resultados
{
  "data": [],
  "meta": {
    "total": 0
  }
}

## Manejo de Errores
400 Bad Request (por ejemplo muscle_group_id inválido)

{
  "error": "VALIDATION_ERROR",
  "message": "muscle_group_id must be a positive integer."
}

500 Internal Server Error
{
  "error": "INTERNAL_ERROR",
  "message": "Unexpected server error."
}