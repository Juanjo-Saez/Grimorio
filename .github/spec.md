# Software Design Document (SDD) — Grimorio

> Documento de diseño que refleja el **estado real** del proyecto. Se actualiza según evoluciona el código.

## 1. Overview

**Project Name:** Grimorio

**Goal:** Webapp de notas tipo Zettelkasten — rápida, pragmática y flexible.

**Description:**
Grimorio centraliza la toma y búsqueda de notas inspirándose en el método Zettelkasten: notas individuales y atómicas, organizadas mediante tags por usuario, con búsqueda combinada (texto + tags) y compartición controlada entre usuarios. La aplicación es multiusuario, con autenticación basada en sesiones, y prioriza simplicidad sobre features pesadas (no SPA, no editor enriquecido en MVP).

**Branch activa:** `proyectosinJWT` (la autenticación JWT inicialmente prevista se sustituyó por sesiones nativas de Laravel).

---

## 2. Architecture

- **Pattern:** MVC clásico de Laravel (estructura estándar, **no** feature-based folders).
- **Capas:**
  - `app/Http/Controllers/` — orquestación HTTP y validación de input.
  - `app/Services/` — lógica de negocio (`NoteService`, `SearchService`, `TagService`, `SharedLinkService`).
  - `app/Models/` — entidades Eloquent (`User`, `Note`, `Tag`, `SharedLink`).
  - `app/Http/Middleware/` — `Authenticate`, `RedirectIfAuthenticated`, `RateLimitLogin`, `VerifyCsrfToken`, etc.
  - `resources/views/` — Blade (`home`, `auth/`, `notes/`, `shared/`, `layouts/`).
- **Servidor + vistas:** Renderizado server-side con Blade. Sin SPA. JavaScript vanilla puntual (autocomplete de tags, notas relacionadas al elegir tag).
- **Endpoints AJAX:** Definidos en `routes/web.php` (no `api.php`) para reutilizar la sesión y CSRF de Laravel.

---

## 3. Tech Stack

- **Lenguaje:** PHP 8.1+
- **Framework:** Laravel 10.10
- **Base de datos:** MySQL 8 (vía Docker / Laravel Sail)
- **Frontend:** Blade + CSS propio + JavaScript vanilla (sin framework JS).
- **Build assets:** Vite
- **Auth:** **Sesiones nativas de Laravel** (cookies + CSRF). Login/registro con `Auth::attempt` y `Auth::login`. Hash bcrypt sobre `password`.
- **Entorno de desarrollo:** Laravel Sail (Docker) — runtime PHP 8.4, MySQL 8, phpMyAdmin en puerto 8081.
- **Testing:** PHPUnit 10 (unit + feature) y Cypress (E2E).
- **Escala:** < 100 usuarios, baja concurrencia.

---

## 4. Main Features

### Fase 1 — Notas (✅ Implementada)
- CRUD completo de notas propias (`title`, `description`, `content`).
- Título único por usuario (validado en controlador).
- Sistema de tags por usuario (auto-creación al teclear, autocomplete).
- Búsqueda combinable: texto libre con operador `AND`/`OR` + filtro por uno o varios tags (lógica AND entre tags).
- Listado paginado (10 por página) ordenado por fecha de creación descendente.
- Al crear nota: si se selecciona un tag se muestran las notas previas con ese tag (vía AJAX).

### Fase 2 — Autenticación (✅ Implementada, sin JWT)
- Registro con email + contraseña (mín. 8 caracteres, confirmación).
- Login con sesión persistente opcional (`remember`).
- Logout con invalidación de sesión y regeneración de token CSRF.
- Rate limiting en login: `throttle:5,1` (5 intentos por minuto por IP).
- Middleware `auth` protege todas las rutas privadas; `guest` redirige a usuarios ya logueados.
- **Decisión clave:** se descartó JWT en favor de sesiones por simplicidad operativa, aprovechamiento de protección CSRF nativa y ausencia de cliente SPA o móvil.

### Fase 3 — Compartición (✅ Implementada)
- Compartir nota con otro usuario registrado vía email del destinatario.
- Niveles de acceso: `read` (solo lectura) o `edit` (lectura + edición de `content` y `description`; el título solo lo cambia el dueño).
- Token único de 64 caracteres (`bin2hex(random_bytes(32))`) usado como URL `/shared/{token}`.
- El dueño puede revocar la compartición en cualquier momento.
- El destinatario ve sus notas compartidas en `/shared`.
- En el listado principal, el usuario puede activar el filtro `shared` para incluir notas compartidas con él.
- Una nota no puede compartirse dos veces con el mismo destinatario (constraint única `note_id + recipient_id`).
- **No** existe (todavía) compartición vía link público sin autenticación.

### Mejoras futuras (pendientes)
- 📥 Descarga de nota individual en PDF.
- 🌐 Links públicos de solo lectura sin requerir cuenta destinataria.
- 🔗 Enlaces cruzados entre notas (estilo wiki).
- 📝 Historial de versiones.
- 📎 Adjuntos en notas.

---

## 5. Entities (schema real según migraciones)

### `users`
| Campo | Tipo | Notas |
| ----- | ---- | ----- |
| id | bigint PK | |
| email | string unique | |
| password | string | hash bcrypt |
| remember_token | string nullable | |
| created_at, updated_at | timestamps | |

### `notes`
| Campo | Tipo | Notas |
| ----- | ---- | ----- |
| id | bigint PK | |
| user_id | FK users | `cascadeOnDelete` |
| title | string(255) | unique por `user_id` |
| content | longText nullable | |
| description | string(500) nullable | |
| created_at, updated_at | timestamps | índice en `created_at` |

### `tags`
| Campo | Tipo | Notas |
| ----- | ---- | ----- |
| id | bigint PK | |
| user_id | FK users | `cascadeOnDelete` |
| name | string(100) | unique por `user_id` |
| created_at, updated_at | timestamps | |

### `note_tag` (pivot)
- `note_id`, `tag_id` (PK compuesta), ambos con `cascadeOnDelete`.

### `shared_links`
| Campo | Tipo | Notas |
| ----- | ---- | ----- |
| id | bigint PK | |
| note_id | FK notes | `cascadeOnDelete` |
| owner_id | FK users | `cascadeOnDelete` |
| recipient_id | FK users | `cascadeOnDelete` |
| token | string(64) unique | |
| access_level | enum('read','edit') | default `'read'` |
| created_at | timestamp | sin `updated_at` |

Restricciones: `unique(note_id, recipient_id)`; índices en `owner_id` y `recipient_id`.

---

## 6. Permissions

- **Propietario:** Acceso total (CRUD + compartir/revocar). Determinado por `note.user_id == auth()->id()`.
- **Destinatario `read`:** Puede ver la nota en `/shared/{token}` y en el listado con el filtro `shared`. No puede editar.
- **Destinatario `edit`:** Lo anterior + puede modificar `content` y `description` (no `title` ni los tags).
- **Usuarios no autenticados:** Solo pueden ver `/`, `/login`, `/register`. Cualquier otra ruta privada redirige a login.
- **Validación:**
  - Pertenencia comprobada en `NoteService::ensureOwner()`.
  - Acceso a recurso compartido validado en `SharedLinkService::validateAccess()` cruzando `token` y `recipient_id`.
  - CSRF en todos los formularios (`VerifyCsrfToken` middleware del grupo `web`).

---

## 7. Search

- **Implementación:** `SearchService::search()` construye un `Eloquent\Builder` con:
  - Scope: notas propias (`user_id == auth user`); opcionalmente OR con notas presentes en `shared_links` donde `recipient_id == auth user`.
  - Texto: parser `parseQuery()` que separa términos por espacio o por operadores `AND`/`OR` explícitos, y aplica `LIKE %término%` sobre `title`, `content` y `description`.
  - Operador (`AND` por defecto): combina los términos vía `where` anidado o `orWhere` anidado según corresponda.
  - Tags: cada tag seleccionado añade un `whereHas('tags')` (lógica AND entre tags).
  - Resultados paginados (10/página) ordenados por `created_at` desc, conservando query string.
- **Limitación:** No usa MySQL FULLTEXT — `LIKE` es suficiente para la escala objetivo. Migrar a FULLTEXT es trivial si crece la base de notas.

---

## 8. Non-Functional Requirements

- **Usuarios:** < 100, < 10 concurrentes.
- **Tiempo de respuesta:** < 500 ms en listados/búsquedas típicas con dataset de prueba.
- **Almacenamiento:** Sin límite por usuario en MVP.
- **Disponibilidad:** Despliegue local con Docker Compose. Posible despliegue cloud futuro.
- **Seguridad:**
  - CSRF en formularios.
  - Rate limit en login (`5 req/min`).
  - Hash bcrypt de contraseñas.
  - Validación server-side de todos los inputs.
  - Cookies de sesión `HttpOnly` y `Secure` (cuando se sirve por HTTPS).
- **Índices BD:** `user_id` y `created_at` en `notes`; uniques en `(user_id, title)`, `(user_id, name)`, `(note_id, recipient_id)`; índices en `owner_id` y `recipient_id` de `shared_links`.

---

## 9. External Integrations

- **Ninguna actualmente.** No se envían emails, no se usan APIs externas. La compartición requiere que el destinatario ya esté registrado.
- **Posible futuro:** envío de invitaciones por email (Mailtrap/SMTP), exportación a PDF (DomPDF / Browsershot).

---

## 10. Logging

- Logs estándar Laravel en `storage/logs/laravel.log` (canal `single` por defecto).
- Eventos sugeridos a registrar (pendiente de implementar de forma sistemática):
  - `Note.created` / `Note.updated` / `Note.deleted` (user_id, note_id).
  - `SharedLink.created` / `SharedLink.revoked` (owner_id, recipient_id, note_id, access_level).
  - `Auth.login` / `Auth.login_failed` / `Auth.logout` (email, IP).
  - `Search.executed` (user_id, query, n_resultados).
- Errores y excepciones se gestionan vía `app/Exceptions/Handler.php` (default Laravel).

---

## 11. Testing Strategy

### Estado actual
- **Cypress (E2E):** `cypress/e2e/happypath.cy.js` cubre login → crear → editar → eliminar nota → logout (happy path completo).
- **Unit (PHPUnit):**
  - `tests/Unit/SearchServiceTest.php` — tests de `parseQuery()` (AND, OR, sin operador, vacío, espacios múltiples).
  - `tests/Unit/NoteTest.php` — fillables del modelo (assigna y comprueba campos).
  - Tests obsoletos `ExampleTest.php` eliminados.
- **Feature (PHPUnit):**
  - `tests/Feature/AuthTest.php` — registro, login OK, login KO, logout.
  - `tests/Feature/NoteCrudTest.php` — guest redirige, listar/crear/editar/borrar nota propia, título único por usuario, otro usuario no ve la nota.

### Recomendado / pendiente
- Feature tests de:
  - `NoteController` (CRUD + validación de título único + filtros).
  - `AuthController` (login, registro, logout, rate limit).
  - `SharedLinkController` (crear/revocar/validar acceso, niveles `read`/`edit`).
- Unit tests adicionales para `SharedLinkService::validateAccess` y reglas de propiedad.
- E2E adicionales: flujo de compartición y flujo de búsqueda con operadores y tags.

---

## 12. SDD & IA

- Uso de Copilot/Claude para:
  - Generación inicial de scaffolding (modelos, migraciones, controladores).
  - Refactor de servicios y revisión de seguridad.
  - Generación de tests a partir del código existente.
- Documentación viva: `.github/copilot-instructions.md` describe convenciones del proyecto para asistentes IA.

---

## 13. Deployment

- **Local:** `./vendor/bin/sail up -d` levanta `laravel.test` (Apache + PHP 8.4), MySQL 8 y phpMyAdmin (`localhost:8081`).
- **Setup inicial:** `sail artisan migrate` + `npm run dev` para Vite.
- **Cloud (futuro):** Despliegue previsto en VPS o servicio gestionado tipo Forge/Render. Cloudflare como CDN/proxy opcional.
- **CI:** No configurado actualmente. GitHub Actions pendiente.

---

## 14. Design Decisions

### ✅ Sesiones nativas en lugar de JWT
**Razón:** Sin SPA ni cliente móvil que justifique tokens stateless; las sesiones de Laravel ofrecen CSRF gratis, gestión madura de cookies y `remember me`. Se evita complejidad de refresh tokens, expiración corta y blacklist.
**Estado:** dependencia `tymon/jwt-auth`, `firebase/php-jwt`, `JwtMiddleware` y alias `auth.jwt` eliminados de la rama `proyectosinJWT`.

### ✅ Búsqueda con LIKE en vez de FULLTEXT
**Razón:** Escala objetivo (<100 usuarios, decenas/centenas de notas por usuario) hace que `LIKE` sea suficiente y portable. Permite buscar también en `description` sin reconfigurar índices.
**Coste:** Escaneos lineales si la base crece mucho — migración a `FULLTEXT` documentada como evolución futura.

### ✅ Tags por usuario
**Razón:** Cada usuario tiene su vocabulario; evita colisiones semánticas y simplifica permisos.
**Trade-off:** No hay tags compartidos globales (pendiente de evaluar si la compartición de notas hace falta unificar tags).

### ✅ Arquitectura estándar Laravel (no feature-based)
**Razón:** Tamaño del proyecto no justifica el coste cognitivo de carpetas por feature. Mantener la estructura idiomática facilita la lectura por evaluadores externos y futuros mantenedores.

### ✅ AJAX sobre rutas `web` (no API stateless)
**Razón:** Reutiliza sesión y CSRF; evita duplicar autenticación. Las rutas `/api/user/tags` y `/api/notes/by-tag/{tag}` viven en `routes/web.php`.

### ✅ Token único de 64 chars para sharing
**Razón:** Imposible de adivinar; permite URLs estables aunque hoy se valida también el `recipient_id`. Deja la puerta abierta a futura compartición pública sin auth.

---

## 15. Open Questions

- [ ] **Cobertura de tests:** ¿meta de cobertura mínima antes de la entrega? Hoy: 4 archivos de test (2 unit + 2 feature) + Cypress happy path.
- [ ] **PDF export:** ¿qué librería (DomPDF vs Browsershot) y cuándo se prioriza?
- [ ] **Links públicos sin auth:** confirmar si entran en el alcance del TFG o quedan como mejora.
- [ ] **Paginación configurable:** hoy fija a 10; ¿exponer como preferencia de usuario?
- [ ] **Historial de versiones:** valor para Zettelkasten vs coste de almacenamiento.
- [ ] **Búsqueda fuzzy / sin acentos:** el `LIKE` actual es case-insensitive en MySQL pero no insensible a acentos.
- [ ] **i18n:** ¿UI multi-idioma o solo español?

---

## 16. Rutas (referencia rápida — `routes/web.php`)

| Método | Ruta | Nombre | Middleware | Descripción |
| ------ | ---- | ------ | ---------- | ----------- |
| GET    | `/` | `home` | — | Landing |
| GET    | `/login` | `login` | `guest` | Form login |
| POST   | `/login` | — | `guest`, `throttle:5,1` | Submit login |
| GET    | `/register` | `register` | `guest` | Form registro |
| POST   | `/register` | — | `guest` | Submit registro |
| POST   | `/logout` | `logout` | `auth` | Cerrar sesión |
| GET    | `/notes` | `notes.index` | `auth` | Listado + búsqueda |
| GET    | `/notes/create` | `notes.create` | `auth` | Form crear |
| POST   | `/notes` | `notes.store` | `auth` | Crear nota |
| GET    | `/notes/{note}` | `notes.show` | `auth` | Ver nota |
| GET    | `/notes/{note}/edit` | `notes.edit` | `auth` | Form editar |
| PUT    | `/notes/{note}` | `notes.update` | `auth` | Actualizar |
| DELETE | `/notes/{note}` | `notes.destroy` | `auth` | Eliminar |
| GET    | `/api/user/tags` | `api.user.tags` | `auth` | AJAX tags del usuario |
| GET    | `/api/notes/by-tag/{tag}` | `api.notes.byTag` | `auth` | AJAX notas con un tag |
| POST   | `/notes/{note}/share` | `shared.store` | `auth` | Compartir nota |
| DELETE | `/shared/{sharedLink}` | `shared.destroy` | `auth` | Revocar compartición |
| GET    | `/shared` | `shared.index` | `auth` | Notas compartidas conmigo |
| GET    | `/shared/{token}` | `shared.show` | `auth` | Ver nota compartida |
| PUT    | `/shared/{token}` | `shared.update` | `auth` | Editar (si `access_level=edit`) |

---

> _Documento vivo. Última actualización refleja la rama `proyectosinJWT` con autenticación por sesiones._

