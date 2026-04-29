# Tasks — SharedLink (Fase 3)

## Context

Implementar compartición de notas individuales con permisos granulares (read/edit). Usuarios autenticados generan tokens únicos para invitar otros usuarios. Propietario controla acceso. Último write wins en edición simultánea.

---

## Task List

### 1. Preparar estructura base de Feature

#### 1.1 Crear carpetas feature
- [ ] Crear `app/Features/SharedLink/` con subdirectorios:
  - Models/
  - Controllers/
  - Services/
  - Requests/
  - Helpers/
  - routes.php

#### 1.2 Crear archivo routes
- [ ] `app/Features/SharedLink/routes.php`:
  ```php
  Route::middleware('auth:api')->group(function () {
      Route::post('/notes/{id}/share', [SharedLinkController::class, 'store']);
      Route::get('/notes/{id}/shared', [SharedLinkController::class, 'listShared']);
      Route::delete('/shared/{id}', [SharedLinkController::class, 'revoke']);
      Route::get('/shared', [SharedLinkController::class, 'listReceivedShares']);
  });
  Route::get('/shared/{token}', [SharedNoteController::class, 'show']);
  Route::put('/shared/{token}', [SharedNoteController::class, 'update']);
  ```
- [ ] Registrar en `routes/api.php`

---

### 2. Persistencia y Migraciones

#### 2.1 Crear migración shared_links
- [ ] `database/migrations/{timestamp}_create_shared_links_table.php`:
  ```sql
  id, note_id (FK), owner_id (FK), recipient_id (FK),
  token (UNIQUE), access_level (read/edit), created_at
  ```
- [ ] Crear índices: token UNIQUE, (note_id, recipient_id)
- [ ] Ejecutar: `php artisan migrate`

#### 2.2 Crear Model SharedLink
- [ ] `app/Features/SharedLink/Models/SharedLink.php`:
  - Relación: belongsTo(Note)
  - Relación: belongsTo(User, 'owner_id')
  - Relación: belongsTo(User, 'recipient_id')
  - Casts: access_level (enum o string)

#### 2.3 Actualizar Model Note
- [ ] Agregar relación: `hasMany(SharedLink)`

#### 2.4 Crear Factory y Seeder
- [ ] `database/factories/SharedLinkFactory.php`
- [ ] `database/seeders/SharedLinkSeeder.php`

---

### 3. Helpers y Utilidades

#### 3.1 Crear TokenGenerator
- [ ] `app/Features/SharedLink/Helpers/TokenGenerator.php`:
  - `generate()` → random_bytes(32) + base64_url_encode
  - Retorna string 64 chars, URL-safe, criptográficamente aleatorio

---

### 4. Servicios (lógica de compartición)

#### 4.1 Crear SharedLinkService
- [ ] `app/Features/SharedLink/Services/SharedLinkService.php`:
  - `createLink($owner, $note, $recipient, $accessLevel)` → crear share
  - `revokeLink($owner, $sharedLinkId)` → revocar acceso
  - `getSharedByMe($owner, $noteId)` → listar mis shares
  - `getSharedWithMe($recipient, $filters)` → listar shares recibidos
  - `validateToken($token)` → verificar token existe y nota válida
  - Validaciones: ownership, permisos, user existence

#### 4.2 Crear SharedLinkAccessService
- [ ] `app/Features/SharedLink/Services/SharedLinkAccessService.php`:
  - `canView($sharedLink, $user)` → user == recipient
  - `canEdit($sharedLink, $user)` → user == recipient AND access_level == 'edit'
  - `canRevoke($sharedLink, $user)` → user == owner
  - Métodos de validación granular

---

### 5. Validadores (Requests)

#### 5.1 Crear CreateSharedLinkRequest
- [ ] `app/Features/SharedLink/Requests/CreateSharedLinkRequest.php`:
  - note_id: required, exists:notes
  - recipient_id: required, exists:users, not_same_as_owner
  - access_level: required, in:read,edit
  - Verificar nota pertenece a owner (custom rule)

#### 5.2 Crear EditSharedNoteRequest
- [ ] `app/Features/SharedLink/Requests/EditSharedNoteRequest.php`:
  - content: nullable, string
  - description: nullable, string
  - Bloquear: title (custom rule: no permitir)

---

### 6. Controllers

#### 6.1 Crear SharedLinkController
- [ ] `app/Features/SharedLink/Controllers/SharedLinkController.php`:
  - `store(CreateSharedLinkRequest $request)` → POST /notes/{id}/share
  - `listShared($noteId)` → GET /notes/{id}/shared
  - `listReceivedShares()` → GET /shared
  - `revoke($id)` → DELETE /shared/{id}

#### 6.2 Crear SharedNoteController
- [ ] `app/Features/SharedLink/Controllers/SharedNoteController.php`:
  - `show($token)` → GET /shared/{token}
  - `update($token, EditSharedNoteRequest $request)` → PUT /shared/{token}
  - Manejo de acceso por token (sin autenticación JWT en show)

#### 6.3 Implementar acciones
- [ ] Cada acción valida acceso
- [ ] Retorna JSON con status correcto
- [ ] Maneja excepciones: 403 Forbidden, 404 Not Found

---

### 7. Integraciones con Features existentes

#### 7.1 Actualizar NoteController
- [ ] Cuando nota se actualiza vía link compartido:
  - Validar que user tiene acceso (via SharedLink)
  - Usar NoteService pero con validación de SharedLink
  - Fire evento: SharedLink.note_edited

#### 7.2 Cascade delete cuando nota se elimina
- [ ] Model Note: `onDelete('cascade')` en migración
- [ ] O: Observer que elimina SharedLinks cuando nota se borra

#### 7.3 Proteger rutas de Note
- [ ] Verificar que NoteController rechaza no-owners
- [ ] SharedNoteController permite invitados si tienen acceso

---

### 8. Testing - Unitarios

#### 8.1 Unit test: TokenGenerator
- [ ] `tests/Unit/SharedLink/TokenGeneratorTest.php`:
  - test_generate_returns_64_chars()
  - test_generate_url_safe()
  - test_generate_unique_on_multiple_calls()
  - test_generate_cryptographically_random()

#### 8.2 Unit test: SharedLinkAccessService
- [ ] `tests/Unit/SharedLink/AccessServiceTest.php`:
  - test_can_view_only_recipient()
  - test_can_edit_only_if_access_level_edit()
  - test_can_revoke_only_owner()
  - test_owner_cannot_view_as_recipient()

#### 8.3 Unit test: SharedLinkService
- [ ] `tests/Unit/SharedLink/SharedLinkServiceTest.php`:
  - test_create_link_generates_unique_token()
  - test_create_link_stores_correct_access_level()
  - test_create_link_validates_ownership()
  - test_revoke_link_only_by_owner()

---

### 9. Testing - Feature/Integration

#### 9.1 Feature test: Crear share
- [ ] `tests/Feature/SharedLink/CreateShareTest.php`:
  - test_create_share_returns_token() → 201 Created
  - test_create_share_invalid_recipient() → 422
  - test_create_share_not_owner() → 403 Forbidden
  - test_create_share_stores_access_level()

#### 9.2 Feature test: Ver nota compartida
- [ ] `tests/Feature/SharedLink/ViewSharedNoteTest.php`:
  - test_view_shared_note_with_valid_token() → 200 OK
  - test_view_shared_note_invalid_token() → 404
  - test_view_shared_note_recipient_only() → 403 si user != recipient
  - test_view_shared_note_deleted() → 404

#### 9.3 Feature test: Editar nota compartida
- [ ] `tests/Feature/SharedLink/EditSharedNoteTest.php`:
  - test_edit_shared_note_with_edit_permission() → 200 OK
  - test_edit_shared_note_with_read_permission() → 403 Forbidden
  - test_edit_shared_note_cannot_change_title() → 403 o ignored
  - test_edit_shared_note_updates_content()
  - test_last_write_wins_simultaneous_edit()

#### 9.4 Feature test: Revocar share
- [ ] `tests/Feature/SharedLink/RevokeShareTest.php`:
  - test_revoke_share_by_owner() → 204 No Content
  - test_revoke_share_not_owner() → 403 Forbidden
  - test_access_denied_after_revoke() → 404 o 403

#### 9.5 Feature test: Listar shares
- [ ] `tests/Feature/SharedLink/ListSharesTest.php`:
  - test_list_shared_with_me() → 200 OK + array
  - test_list_shared_by_me() → 200 OK + array
  - test_list_filtered_by_access_level()

---

### 10. Eventos y Logging

#### 10.1 Crear listeners
- [ ] Listeners para eventos:
  - `SharedLink.created` → log owner_id, recipient_id, note_id
  - `SharedLink.note_edited` → log recipient_id, note_id
  - `SharedLink.revoked` → log owner_id, recipient_id, note_id

#### 10.2 Implementar logging
- [ ] Storage/logs/shared-link-{date}.log

---

### 11. Documentación

#### 11.1 Crear README técnico
- [ ] `app/Features/SharedLink/README.md`:
  - Descripción de compartición
  - Ejemplos de requests (crear share, ver, editar)
  - Flujo de tokens
  - Decisiones técnicas (último write wins, sin revoke remoto)

---

## Dependencias entre tareas

```
1. Estructura → 2. BD/Migraciones → 3. Helpers (TokenGenerator) →
4. Servicios → 5. Validadores → 6. Controllers → 
7. Integraciones Note → 8. Tests unitarios → 9. Tests feature → 
10. Logging → 11. Documentación
```

---

## Criterios de aceptación por tarea

- [ ] Token generado: 64 chars, URL-safe, criptográficamente aleatorio
- [ ] Compartición solo entre usuarios autenticados
- [ ] Access level (read/edit) validado en cada operación
- [ ] Propietario puede revocar, recipient no
- [ ] Edición en nota compartida respeta permisos
- [ ] Título no puede editarse desde link compartido
- [ ] Último write wins en edición simultánea
- [ ] Cascade delete cuando nota se elimina
- [ ] Tests ≥ 80% cobertura
- [ ] Eventos de compartición se registran
- [ ] API responde con status codes correctos (201, 200, 204, 403, 404)

