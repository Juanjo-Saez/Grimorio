# Copilot Instructions — Grimorio

## Contexto General

**Proyecto:** Grimorio - App de notas estilo Zettelkasten
**Stack:** Laravel + MySQL + Blade + Sessions
**Scale:** < 100 usuarios
**Arquitectura:** Estructura tradicional MVC de Laravel

---

## Convenciones de Naming

### PascalCase Singular
- Features → `Auth`, `Note`, `SharedLink`
- Models → `User`, `Note`, `Tag`, `SharedLink`
- Controllers → `NoteController`, `AuthController`
- Services → `NoteService`, `SearchService`
- Requests → `CreateNoteRequest`, `LoginRequest`

### Blade Templates
- Ubicación: `resources/views/{feature}/`
- Patrón: `{action}.blade.php` (create.blade.php, edit.blade.php)

### Tests
- Unit: `tests/Unit/`
- Feature: `tests/Feature/`
- E2E: `cypress/e2e/`

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
- Login/Registro con sesiones
- Remember me token
- Autorización con middleware auth

### Fase 3
**Features:** SharedLink
- Compartir notas individuales
- Permisos: read/edit
- Invitaciones vía URL

---

## Principios de Arquitectura

### 1. Service Layer
- Lógica de negocio en Services (no en Controllers)
- Controllers: request → service → response
- Ejemplo: `NoteService::search($query, $filters)`
- Ubicación: `app/Services/`

### 2. Models
- Entidades de BD en `app/Models/`
- Relaciones claras: User → Note → Tag
- Ejemplo: `app/Models/Note.php`

### 3. Controllers
- Ubicación: `app/Http/Controllers/`
- Un controller por feature (NoteController, AuthController, SharedLinkController)
- Delegación a Services para lógica compleja

### 4. Tests AFTER Code
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

### Auth (Fase 2) — Web Forms
- `GET /login` → formulario login
- `POST /login` → procesar login con sesión
- `GET /register` → formulario registro
- `POST /register` → crear usuario y sesión
- `POST /logout` → destruir sesión

### SharedLinks (Fase 3) — Web + Tokens públicos
- `POST /notes/{id}/share` → crear link compartido con token único
- `GET /shared/{token}` → acceder nota compartida (sin autenticación)
- `PUT /shared/{token}` → editar nota compartida (si access_level=edit)
- `GET /shared` → listar notas compartidas conmigo

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

## Sessions (Fase 2+)

### Laravel Session
- Driver: Database o File (configurable)
- CSRF protection automática en Blade `@csrf`
- Remember me: `remember_token` en tabla users (30 días default)

### Middleware
- `middleware('auth')` → verifica sesión activa
- `middleware('guest')` → solo usuarios sin autenticación
- `middleware('auth.shared')` → valida token público para SharedLinks

### Seguridad
- Sesiones con HttpOnly cookie (no accesible desde JS)
- HTTPS obligatorio en producción
- CSRF token en todos los formularios POST/PUT/DELETE

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

