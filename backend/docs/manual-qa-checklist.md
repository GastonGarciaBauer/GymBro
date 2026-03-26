# GymBro MVP - Manual QA Checklist

## Objetivo

Validar que los endpoints del MVP funcionen correctamente antes de integrar el frontend.

## Entorno

- Base URL local: `http://localhost/GymBro/backend/public/api`

- Herramienta de prueba: Postman / Thunder Client / navegador

- Base de datos cargada con datos semilla: Sí / No

---

## Estado inicial

- [ ] Conexión a base de datos funcionando
- [ ] Tabla `muscle_groups` con datos
- [ ] Tabla `exercises` con datos
- [ ] FK `exercises.muscle_group_id -> muscle_groups.id` activa
---

## Casos de prueba - `GET /muscle-groups`

### Caso MG-01: listar grupos musculares
- Request: `GET /muscle-groups`
- Esperado:
  - Status `200`
  - JSON con `data` (array)
  - Cada item tiene `id` (number) y `name` (string)
- Resultado: [ ] OK [ ] FAIL
- Notas:

### Caso MG-02: formato de respuesta consistente
- Request: `GET /muscle-groups`
- Esperado:
  - `Content-Type: application/json`
  - No devuelve HTML/Warnings de PHP
- Resultado: [ ] OK [ ] FAIL
- Notas:
---

## Casos de prueba - `GET /exercises`

### Caso EX-01: listar ejercicios sin filtros
- Request: `GET /exercises`
- Esperado:
  - Status `200`
  - JSON con `data` (array) y `meta.total`
- Resultado: [ ] OK [ ] FAIL
- Notas:

### Caso EX-02: búsqueda por nombre (coincidencia parcial)
- Request: `GET /exercises?search=press`
- Esperado:
  - Status `200`
  - Resultados que contengan “press” en `name` (según regla definida)
- Resultado: [ ] OK [ ] FAIL
- Notas:

### Caso EX-03: filtro por grupo muscular
- Request: `GET /exercises?muscle_group_id=1`
- Esperado:
  - Status `200`
  - Todos los ejercicios devueltos pertenecen al grupo `id=1`
- Resultado: [ ] OK [ ] FAIL
- Notas:

### Caso EX-04: búsqueda + filtro combinados
- Request: `GET /exercises?search=remo&muscle_group_id=2`
- Esperado:
  - Status `200`
  - Se aplica lógica AND (cumple búsqueda y grupo)
- Resultado: [ ] OK [ ] FAIL
- Notas:

### Caso EX-05: sin resultados
- Request: `GET /exercises?search=zzzzzz`
- Esperado:
  - Status `200`
  - `data: []`
  - `meta.total: 0`
- Resultado: [ ] OK [ ] FAIL
- Notas:

### Caso EX-06: parámetro inválido
- Request: `GET /exercises?muscle_group_id=abc`
- Esperado:
  - Status `400`
  - JSON de error: `error` + `message`
- Resultado: [ ] OK [ ] FAIL
- Notas:
---

## Casos de error técnico

### Caso ER-01: error de base de datos
- Acción: simular credenciales incorrectas o DB caída
- Esperado:
  - Status `500`
  - JSON de error controlado (sin stack trace)
- Resultado: [ ] OK [ ] FAIL
- Notas:

---