# Grimorio 📖

Webapp de notas inspirada en el método **Zettelkasten**. Rápida, pragmática, flexible.

- Notas atómicas con título, descripción y contenido.
- Tags por usuario, autocomplete y filtro combinado AND/OR sobre el texto.
- Compartición entre usuarios registrados con permisos `read` o `edit`.
- Multiusuario, autenticación por sesión Laravel (cookies + CSRF).

> Stack: PHP 8.1+ · Laravel 10 · MySQL 8 · Blade · Vanilla JS · Vite · PHPUnit · Cypress · Laravel Sail (Docker)

---

## Estado del proyecto

| Fase | Estado |
| ---- | ------ |
| 1. CRUD de notas + tags + búsqueda | ✅ |
| 2. Auth por sesiones (registro, login, logout, rate limit) | ✅ |
| 3. Compartición con niveles `read`/`edit` | ✅ |
| Mejoras (PDF export, links públicos sin auth, versiones, adjuntos) | 🕓 pendiente |

Documento técnico completo en [`.github/spec.md`](.github/spec.md).

---

## Desarrollo con Laravel Sail (recomendado)

Requiere [Docker](https://www.docker.com/get-started/).

```bash
composer install
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
npm install && npm run dev
```

App disponible en `http://localhost`. phpMyAdmin en `http://localhost:8081`.

> Todos los comandos de `php artisan` deben ir precedidos de `./vendor/bin/sail`.

```bash
./vendor/bin/sail ps      # contenedores activos
./vendor/bin/sail down    # apagar
```

## Desarrollo sin Sail

```bash
composer install
cp .env.example .env
php artisan key:generate
# configura DB_* en .env apuntando a tu MySQL local
php artisan migrate
npm install && npm run dev
php artisan serve
```

---

## Tests

### PHPUnit (unit + feature, SQLite en memoria)

```bash
php artisan test
# o
./vendor/bin/sail test
```

Cubren:

- `tests/Unit/SearchServiceTest.php` — parser AND/OR.
- `tests/Unit/NoteTest.php` — fillables del modelo.
- `tests/Feature/AuthTest.php` — registro, login OK/KO, logout.
- `tests/Feature/NoteCrudTest.php` — CRUD propio + aislamiento entre usuarios.

### Cypress (E2E)

```bash
npm install
npx cypress open       # interactivo
npx cypress run        # CLI
```

Happy path en [cypress/e2e/happypath.cy.js](cypress/e2e/happypath.cy.js).

---

## Estructura

```
app/Http/Controllers/   AuthController, NoteController, SharedLinkController
app/Services/           NoteService, SearchService, TagService, SharedLinkService
app/Models/             User, Note, Tag, SharedLink
resources/views/        auth/, notes/, shared/, layouts/, home.blade.php
routes/web.php          Todas las rutas (incluyendo AJAX /api/...)
database/migrations/    users, notes, tags, note_tag, shared_links
```

Arquitectura MVC estándar de Laravel — **no** feature-based folders.

---

## Licencia

Proyecto académico — Trabajo de Fin de Ciclo (DAW).
