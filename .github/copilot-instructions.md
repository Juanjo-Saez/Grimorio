# Copilot Instructions — Grimorio

## Contexto General

**Proyecto:** Grimorio - App de notas estilo Zettelkasten
**Stack:** Laravel + MySQL + Blade + JWT
**Scale:** < 100 usuarios
**Arquitectura:** Feature-based folders en `app/Features`

---

## Convenciones de Naming

### PascalCase Singular
- Features → `Auth`, `Note`, `SharedLink`
- Models → `User`, `Note`, `Tag`, `SharedLink`
- Controllers → `NoteController`, `AuthController`
- Services → `NoteService`, `SearchService`
- Requests → `CreateNoteRequest`, `LoginRequest`

### Blade Templates
- Ubicación: `resources/views/features/{feature}/`
- Patrón: `{action}.blade.php` (create.blade.php, edit.blade.php)

### Tests
- Unit: `tests/Unit/{feature}/`
- Feature: `tests/Feature/{feature}/`
- E2E: `cypress/e2e/{feature}.cy.js`

---

## Fases de Desarrollo

### MVP (Fase 1)
**Features:** Note (CRUD + búsqueda)
- Crear notas con título y contenido
- Ver/Editar/Eliminar propias
- Búsqueda con operadores AND/OR
- Sistema de tags
- **Sin autenticación** (assume usuario único local)

### Fase 2
**Features:** Auth
- Login/Registro con JWT
- Tokens con expiración 15min
- Autorización en endpoints

### Fase 3
**Features:** SharedLink
- Compartir notas individuales
- Permisos: read/edit
- Invitaciones vía URL

---

## Principios de Arquitectura

### 1. Feature-Driven
Cada feature es autónoma en `app/Features/{Feature}/`:
```
app/Features/Note/
  ├── Models/Note.php
  ├── Controllers/NoteController.php
  ├── Services/NoteService.php
  ├── Requests/CreateNoteRequest.php
  └── routes.php
```

### 2. Service Layer
- Lógica de negocio en Services (no en Controllers)
- Controllers: request → service → response
- Ejemplo: `NoteService::search($query, $filters)`

### 3. Tests AFTER Code
- Implementar feature → Tests
- Validar funcionamiento → Documentar

### 4. Persistencia Clara
- Modelos → Entidades de BD
- Factories → Seeders
- Migraciones nombradas por feature

---

## Rutas API (Futuro)

Prefix: `/api/v1/` (preparado para Fase 2+)

### Notes
- `GET /notes` → listar propias
- `POST /notes` → crear
- `GET /notes/{id}` → ver
- `PUT /notes/{id}` → editar
- `DELETE /notes/{id}` → eliminar
- `GET /notes/search?q=query&tags=tag1,tag2` → búsqueda

### Auth (Fase 2)
- `POST /auth/register`
- `POST /auth/login`
- `POST /auth/refresh` (refresh token)
- `POST /auth/logout`

### SharedLinks (Fase 3)
- `POST /notes/{id}/share` → crear link
- `GET /notes/shared/{token}` → acceder nota compartida

---

## Búsqueda (MVP)

### Operadores
- **AND**: `"user experience" AND "design"` (ambos términos)
- **OR**: `"ruby" OR "python"` (cualquier término)
- **Tags**: `#productivity #learning` (filtro explícito)

### Implementación
- Parser regex en `SearchService`
- Query builder condicional en `NoteRepository`
- Índices full-text en `nota.titulo`, `nota.contenido`

---

## JWT (Fase 2+)

### Token Structure
```json
{
  "sub": 1,
  "email": "user@example.com",
  "iat": 1234567890,
  "exp": 1234568790,
  "iss": "grimorio"
}
```

### Middleware
- `JwtMiddleware` → verifica token en header Authorization
- `AuthenticatedUser` → inyecta user en request

### Sin revoke
- Tokens de 15min
- Browser borra localStorage en logout
- Futuro: tabla `revoked_tokens` si es necesario

---

## Testing Strategy

### Unit Tests
- `SearchService::parse()` → operadores AND/OR
- `NoteService::validatePermission()` → permisos
- Validadores de input

### Feature Tests
- Flujo CRUD notas
- Búsqueda con filtros
- Listado paginado

### E2E (Cypress)
- Crear nota → buscar → editar → eliminar
- Búsqueda con operadores
- Filtrado por tags

---

## Logging

Registrar eventos:
- `Note.created` (user_id, note_id)
- `Note.edited` (user_id, note_id, campos)
- `Note.deleted` (user_id, note_id)
- `Search.executed` (query, results_count)
- `Auth.login` (user_id, ip)
- `Auth.logout` (user_id)

Location: `storage/logs/grimorio-{date}.log`

---

## Decisiones Pendientes

- [ ] ¿Blade mínima (forms) o UI elaborada?
- [ ] ¿Paginación en listados?
- [ ] ¿Adjuntos en notas (Fase 3+)?
- [ ] ¿Historial de versiones?

---

## Recursos

- **spec.md** → Requisitos funcionales globales
- **structure.json** → Estructura de features
- **Skills** → feature-plan, feature-requirements, feature-tasks, feature-scaffolding
- **Command** → `php artisan ai:scaffold` (genera .ai por feature)

