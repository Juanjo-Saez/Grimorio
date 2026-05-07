# Copilot Instructions — Grimorio

## Contexto General

**Proyecto:** Grimorio — App web de notas estilo Zettelkasten
**Stack:** Laravel 10 + MySQL + Blade + sesiones nativas (auth web)
**Escala:** < 100 usuarios
**Arquitectura:** MVC estándar de Laravel (carpetas por capa, **no** por feature)
**Rama activa:** `proyectosinJWT` (la autenticación JWT inicial fue sustituida por sesiones)

---

## Convenciones de Naming

### PHP — PascalCase singular
- Models: `User`, `Note`, `Tag`, `SharedLink`
- Controllers: `NoteController`, `AuthController`, `SharedLinkController`
- Services: `NoteService`, `SearchService`, `TagService`, `SharedLinkService`

### Blade
- Ubicación: `resources/views/{area}/`
- Áreas: `auth/`, `notes/`, `shared/`, `layouts/`, raíz para `home.blade.php`
- Patrón: `{action}.blade.php` (`create`, `edit`, `index`, `show`)

### Tests
- Unit: `tests/Unit/{NombreServicioOClase}Test.php`
- Feature: `tests/Feature/{Flujo}Test.php` (p. ej. `NoteCrudTest`, `AuthTest`)
- E2E: `cypress/e2e/{flujo}.cy.js`

---

## Estructura de carpetas

```
app/
  Http/
    Controllers/        # AuthController, NoteController, SharedLinkController
    Middleware/         # Authenticate, RedirectIfAuthenticated, RateLimitLogin, ...
    Kernel.php          # Aliases: 'auth', 'guest' (=RedirectIfAuthenticated), 'rate.login', ...
  Models/               # User, Note, Tag, SharedLink
  Services/             # NoteService, SearchService, TagService, SharedLinkService
database/
  migrations/           # users, notes, tags, note_tag, shared_links
  factories/            # UserFactory, NoteFactory, LinkFactory
resources/
  views/                # auth/, notes/, shared/, layouts/, home.blade.php
routes/
  web.php               # TODAS las rutas (incluidos endpoints AJAX /api/...)
  api.php               # vacío — no se usa
tests/
  Unit/                 # NoteTest, SearchServiceTest
  Feature/              # AuthTest, NoteCrudTest
cypress/e2e/            # happypath.cy.js
```

---

## Fases de Desarrollo (estado real)

### ✅ Fase 1 — Notas (MVP)
- CRUD de notas propias (`title`, `description`, `content`).
- Sistema de tags por usuario, autocomplete, notas relacionadas al elegir tag.
- Búsqueda combinable: texto + operador `AND`/`OR` + filtro por tags + filtro `shared`.
- Listado paginado (10/página) ordenado por `created_at desc`.

### ✅ Fase 2 — Autenticación (sin JWT)
- Sesiones nativas Laravel (cookies + CSRF).
- Login (`Auth::attempt`), registro (`Auth::login`), logout (invalidación + regeneración CSRF).
- Rate limit en login: `throttle:5,1`.
- Middlewares: `auth` para zona privada, `guest` para `/login` y `/register`.

### ✅ Fase 3 — Compartición
- Compartir nota con usuario registrado por email.
- Niveles: `read` o `edit` (`edit` solo modifica `content` y `description`).
- Token único de 64 hex (`bin2hex(random_bytes(32))`).
- Filtro `shared` en el listado para incluir notas compartidas.

### Pendientes
- 📥 Export PDF de notas
- 🌐 Links públicos sin auth
- 🔗 Enlaces cruzados estilo wiki
- 📝 Historial de versiones
- 📎 Adjuntos

---

## Principios de Arquitectura

### 1. MVC clásico (no feature-folders)
- Controllers orquestan: `request → service → response/view`.
- Services concentran toda la lógica de negocio.
- Models son Eloquent puros (relaciones + casts + `$fillable`).

### 2. Service Layer obligatorio
- Nada de queries Eloquent ni lógica de negocio en controllers.
- Cada controller recibe sus services por inyección en el constructor.
- Ejemplos: `NoteService::create($user, $data)`, `SearchService::search($user, $q, $op, $tagIds, $shared)`.

### 3. AJAX en `routes/web.php`
- Los endpoints `/api/user/tags` y `/api/notes/by-tag/{tag}` viven en `web.php` para reutilizar la sesión y CSRF.
- `routes/api.php` está vacío deliberadamente.

### 4. Pertenencia y permisos
- Cada operación que toca una nota verifica `note.user_id == auth()->id()` (`NoteService::ensureOwner`).
- Acceso a recurso compartido validado con `SharedLinkService::validateAccess($token, $user)` cruzando `token` y `recipient_id`.

### 5. Tests AFTER code
- Implementar feature → tests (unit + feature).
- Cypress cubre el happy path E2E.

---

## Auth — sesiones (NO JWT)

- Guard por defecto: `web` con driver `session`.
- Hash de password: bcrypt (cast `'password' => 'hashed'` en `User`).
- Login/registro regeneran sesión: `$request->session()->regenerate()`.
- Logout: `Auth::logout()` + `invalidate()` + `regenerateToken()`.
- Rate limit en `/login`: `throttle:5,1`.
- Protección CSRF en TODOS los formularios web (`@csrf`).
- **No existe** middleware ni dependencia JWT. Si ves cualquier mención (`tymon/jwt-auth`, `firebase/php-jwt`, `JwtMiddleware`, alias `auth.jwt`) es residuo a eliminar.

---

## Búsqueda

### Operadores
- `AND` (default): todos los términos deben aparecer en `title` / `content` / `description`.
- `OR`: al menos uno.
- Filtro de tags: AND entre tags (cada tag selecciona suma un `whereHas`).
- Flag `shared`: incluye notas presentes en `shared_links` con `recipient_id == auth user`.

### Implementación
- `SearchService::parseQuery()` separa por espacios o por operador explícito (`AND`/`OR`).
- `SearchService::search()` construye un `Builder` Eloquent con `LIKE %term%` (no FULLTEXT — suficiente para la escala).
- Resultados paginados con `withQueryString()` para conservar filtros.

---

## Schema (resumen migraciones reales)

| Tabla | Campos clave | Constraints |
| ----- | ------------ | ----------- |
| `users` | id, email, password, remember_token, timestamps | unique(email) |
| `notes` | id, user_id, title, content (longText nullable), description(500 nullable), timestamps | unique(user_id, title); idx user_id, created_at |
| `tags` | id, user_id, name(100), timestamps | unique(user_id, name) |
| `note_tag` | note_id, tag_id | PK compuesta, cascade |
| `shared_links` | id, note_id, owner_id, recipient_id, token(64) unique, access_level enum('read','edit'), created_at | unique(note_id, recipient_id); idx owner_id, recipient_id; **sin** updated_at |

---

## Logging

Eventos a registrar (canal default `single` en `storage/logs/laravel.log`):
- `Note.created`, `Note.updated`, `Note.deleted` (user_id, note_id)
- `SharedLink.created`, `SharedLink.revoked` (owner_id, recipient_id, note_id, access_level)
- `Auth.login`, `Auth.login_failed`, `Auth.logout` (email, IP)
- `Search.executed` (user_id, query, n_resultados)

---

## Tests

### Unit (`tests/Unit/`)
- `SearchServiceTest` — `parseQuery()` con AND/OR, vacío, espacios múltiples.
- `NoteTest` — fillables del modelo.

### Feature (`tests/Feature/`)
- `NoteCrudTest` — guest redirige, listar/crear/editar/borrar nota propia, título único, otro usuario no ve la nota.
- `AuthTest` — registro, login OK, login KO, logout.

### E2E (`cypress/e2e/`)
- `happypath.cy.js` — login → crear nota → editar → eliminar → logout.

### Configuración
- PHPUnit usa SQLite en memoria (`DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`).
- Feature tests usan `RefreshDatabase`.

---

## Recursos del repo

- `.github/spec.md` — fuente de verdad funcional/técnica
- `.github/structure.json` — descriptor de arquitectura
- `.github/skills/` — skills de scaffolding (legacy, alineadas con la fase JWT)
- `docs/memoria-grimorio.md` — memoria del PFC
