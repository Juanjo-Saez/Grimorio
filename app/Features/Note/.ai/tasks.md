# Tasks — Note (MVP)

## Context

Implementar feature completa de CRUD de notas con búsqueda por texto (AND/OR) y sistema de tags. MVP sin autenticación (asumir user_id = 1 en mock). Cada tarea debe completarse y testearse antes de pasar a la siguiente.

---

## Task List

### 1. Preparar estructura base de Feature

#### 1.1 Crear carpetas feature
- [ ] Crear `app/Features/Note/` con subdirectorios:
  - Models/
  - Controllers/
  - Services/
  - Requests/
  - routes.php

#### 1.2 Registrar Feature en app
- [ ] Agregar provider `NoteServiceProvider` en `app/Providers/` que:
  - Auto-discover routes
  - Registrar bindings de servicios
  - Boot listeners de eventos

#### 1.3 Crear archivo routes
- [ ] `app/Features/Note/routes.php` con todas las rutas:
  ```php
  Route::get('/notes', [NoteController::class, 'index']);
  Route::post('/notes', [NoteController::class, 'store']);
  Route::get('/notes/{id}', [NoteController::class, 'show']);
  Route::put('/notes/{id}', [NoteController::class, 'update']);
  Route::delete('/notes/{id}', [NoteController::class, 'destroy']);
  Route::get('/notes/search', [NoteController::class, 'search']);
  ```
- [ ] Registrar en `routes/web.php` o `routes/api.php`

---

### 2. Persistencia y modelos

#### 2.1 Verificar/crear migraciones
- [ ] Revisar migraciones existentes en `database/migrations/`
- [ ] Si falta: Crear migración `create_notes_table` con:
  - id, user_id, title (UNIQUE per user), content, description, timestamps
  - Índice FULLTEXT (title, content, description)
- [ ] Crear migración `create_tags_table` con:
  - id, user_id, name (UNIQUE per user), created_at
- [ ] Crear migración `create_note_tag_table` (pivot) con:
  - note_id, tag_id, created_at
- [ ] Ejecutar: `php artisan migrate`

#### 2.2 Crear Models
- [ ] Crear `app/Features/Note/Models/Note.php`:
  - Relación: belongsTo(User)
  - Relación: belongsToMany(Tag) mediante note_tag
  - Casting de campos (created_at, updated_at)
  - Hidden: password fields (si aplica)

- [ ] Crear `app/Features/Note/Models/Tag.php`:
  - Relación: belongsTo(User)
  - Relación: belongsToMany(Note) mediante note_tag

- [ ] Crear/actualizar `app/Models/User.php`:
  - Relación: hasMany(Note)
  - Relación: hasMany(Tag)

#### 2.3 Crear Factories y Seeders
- [ ] `database/factories/NoteFactory.php`:
  - Generar notas de test con títulos únicos per user
- [ ] `database/factories/TagFactory.php`
- [ ] `database/seeders/NoteSeeder.php` y `TagSeeder.php`

---

### 3. Servicios (lógica de negocio)

#### 3.1 Crear NoteService
- [ ] `app/Features/Note/Services/NoteService.php`:
  - `create($user, $data)` → crear nota validada
  - `update($user, $noteId, $data)` → actualizar con validación ownership
  - `delete($user, $noteId)` → eliminar (cascade tags)
  - `getMyNotes($user, $page=1)` → lazy load de notas propias
  - `show($user, $noteId)` → obtener nota con validación
  - Validaciones de unicidad de título, seguridad

#### 3.2 Crear SearchService
- [ ] `app/Features/Note/Services/SearchService.php`:
  - `parseQuery($query)` → parsear operadores AND/OR
  - `search($user, $query, $operator, $tagIds)` → búsqueda full-text
  - Manejar edge cases: query vacía, caracteres especiales
  - Implementar AND y OR logic

#### 3.3 Crear TagService
- [ ] `app/Features/Note/Services/TagService.php`:
  - `getOrCreate($user, $tagName)` → buscar o crear tag
  - `attachToNote($note, $tag)` → asignar tag a nota (sin duplicados)
  - Validar que tag pertenece al mismo usuario

#### 3.4 Crear Repository (Eloquent queries)
- [ ] `app/Features/Note/Repositories/NoteRepository.php`:
  - `searchByText($userId, $terms, $operator)` → query FULLTEXT
  - `filterByTags($userId, $tagIds)` → query con tags
  - Métodos de query reutilizables

---

### 4. Validadores (Requests)

#### 4.1 Crear CreateNoteRequest
- [ ] `app/Features/Note/Requests/CreateNoteRequest.php`:
  - title: required, string, ≤255, unique per user
  - content: nullable, string
  - description: nullable, string, ≤500
  - Mensaje error personalizado para título duplicado

#### 4.2 Crear UpdateNoteRequest
- [ ] `app/Features/Note/Requests/UpdateNoteRequest.php`:
  - title: required, string, ≤255, unique per user EXCEPTO nota actual
  - content, description: opcional
  - Usar `unique` con `ignore($noteId)`

#### 4.3 Crear SearchNoteRequest
- [ ] `app/Features/Note/Requests/SearchNoteRequest.php`:
  - q (query): required, string, ≥3 chars
  - op (operator): in:AND,OR
  - tags: nullable, comma-separated tag IDs

---

### 5. Controllers y rutas

#### 5.1 Crear NoteController
- [ ] `app/Features/Note/Controllers/NoteController.php`:
  - `index($request)` → GET /notes (lazy load)
  - `store(CreateNoteRequest $request)` → POST /notes
  - `show($id)` → GET /notes/{id}
  - `update(UpdateNoteRequest $request, $id)` → PUT /notes/{id}
  - `destroy($id)` → DELETE /notes/{id}
  - `search(SearchNoteRequest $request)` → GET /notes/search

#### 5.2 Implementar acciones del controller
- [ ] Cada acción llama al service correspondiente
- [ ] Manejar excepciones: validation, not found, forbidden
- [ ] Retornar JSON estructurado con status codes correctos

#### 5.3 Gestión de errores
- [ ] Crear exception handler personalizado o usar Laravel default
- [ ] Asegurar respuestas 400, 403, 404, 409 correctas

---

### 6. Autenticación mock (MVP)

#### 6.1 Crear middleware AuthUser
- [ ] `app/Features/Note/Middleware/AuthUser.php` (o en app/Http/Middleware):
  - Asumir usuario autenticado con ID = 1
  - Inyectar `$request->user()` con User mock
  - Futuro (Fase 2): Reemplazar con JWT middleware

#### 6.2 Aplicar middleware
- [ ] En routes.php, agrupar todas las rutas con middleware AuthUser

---

### 7. Testing - Unitarios

#### 7.1 Unit test: SearchService
- [ ] `tests/Unit/Note/SearchServiceTest.php`:
  - test_parse_and_operators() → "word1 AND word2"
  - test_parse_or_operators() → "word1 OR word2"
  - test_parse_mixed_operators() → "word1 AND word2 OR word3"
  - test_empty_query_returns_error()
  - test_search_respects_user_isolation()

#### 7.2 Unit test: NoteService
- [ ] `tests/Unit/Note/NoteServiceTest.php`:
  - test_create_note_with_unique_title()
  - test_create_duplicate_title_throws()
  - test_update_with_duplicate_title_throws()
  - test_delete_cascades_tags()
  - test_user_cannot_access_others_note()

#### 7.3 Unit test: TagService
- [ ] `tests/Unit/Note/TagServiceTest.php`:
  - test_create_tag_unique_per_user()
  - test_attach_tag_prevents_duplicates()
  - test_get_or_create_existing_tag()

---

### 8. Testing - Feature/Integration

#### 8.1 Feature test: CRUD notas
- [ ] `tests/Feature/Note/NoteTest.php`:
  - test_create_note() → POST /notes → 201 Created
  - test_list_my_notes() → GET /notes → 200 OK + array
  - test_show_note() → GET /notes/{id} → 200 OK + nota completa
  - test_update_note() → PUT /notes/{id} → 200 OK
  - test_delete_note() → DELETE /notes/{id} → 204 No Content

#### 8.2 Feature test: Búsqueda
- [ ] `tests/Feature/Note/SearchTest.php`:
  - test_search_and_operator() → ambas palabras presentes
  - test_search_or_operator() → cualquier palabra presente
  - test_search_filter_by_tags() → solo notas con tags
  - test_search_combined() → búsqueda + tags simultáneamente
  - test_search_empty_query() → 400 Bad Request

#### 8.3 Feature test: Permisos/Edge cases
- [ ] `tests/Feature/Note/PermissionTest.php`:
  - test_cannot_view_others_note() → 404
  - test_cannot_update_others_note() → 403
  - test_cannot_delete_others_note() → 403
  - test_cannot_create_duplicate_title() → 409 Conflict
  - test_can_edit_title_to_same_value() → permitir
  - test_empty_content_permitted() → nota sin contenido OK

---

### 9. Frontend (Blade templates)

#### 9.1 Crear templates
- [ ] `resources/views/features/note/index.blade.php`:
  - Listar notas con lazy load
  - Input de búsqueda (AND/OR)
  - Filtro por tags
  - Link a crear/editar nota

- [ ] `resources/views/features/note/create.blade.php`:
  - Form: título, contenido, descripción, tags
  - Validación frontend (opcional, backend lo hace)
  - POST a /notes

- [ ] `resources/views/features/note/edit.blade.php`:
  - Form prellenado con nota existente
  - PUT a /notes/{id}

- [ ] `resources/views/features/note/show.blade.php`:
  - Vista completa de nota
  - Mostrar tags
  - Botones editar/eliminar (si es propietario)

#### 9.2 Implementar lazy load
- [ ] JavaScript en `resources/js/`:
  - Event listener en scroll
  - Fetch /notes?page=N
  - Append nuevas notas al DOM

---

### 10. Documentación y logging

#### 10.1 Logging de eventos
- [ ] Implementar listeners para:
  - `Note.created` → log con user_id, note_id
  - `Note.edited` → log con user_id, note_id, campos
  - `Note.deleted` → log con user_id, note_id
  - `Search.executed` → log con user_id, query, results_count

#### 10.2 Crear README técnico
- [ ] `app/Features/Note/README.md`:
  - Descripción de la feature
  - Arquitectura (models, services, controllers)
  - Ejemplos de uso (API requests)
  - Decisiones técnicas tomadas

---

## Dependencias entre tareas

```
1. Estructura → 2. Persistencia → 3. Servicios → 
4. Validadores → 5. Controllers → 6. Middleware → 
7. Tests unitarios → 8. Tests feature → 9. UI → 10. Docs
```

**Nota:** 7-8 pueden ejecutarse en paralelo con otras si los tests usan mocks.

---

## Criterios de aceptación por tarea

- [ ] Cada migración ejecutada sin errores
- [ ] Cada service testeado unitariamente
- [ ] Cada endpoint responde con status code correcto
- [ ] Toda validación rechaza datos inválidos
- [ ] Búsqueda AND/OR funciona correctamente
- [ ] Permisos user_id validados en cada operación
- [ ] Tests ≥ 80% cobertura
- [ ] UI renderiza sin errores de Blade
- [ ] Eventos se loguean correctamente

