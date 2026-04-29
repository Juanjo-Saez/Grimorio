# Plan — Note (MVP)

## Arquitectura

### Componentes principales

**Models:**
- `Note` → Entidad de nota (title, content, description, user_id)
- `Tag` → Entidad de etiqueta (name, user_id)
- Relación M:M → `note_tag` (pivot)

**Services:**
- `NoteService` → Lógica de CRUD, validaciones, permisos
- `SearchService` → Parser de operadores AND/OR, búsqueda full-text
- `TagService` → Gestión de tags, creación/asignación

**Controllers:**
- `NoteController` → HTTP routes (create, store, show, edit, update, destroy, index)
- `SearchController` → Búsqueda de notas

**Requests (Validadores):**
- `CreateNoteRequest` → Validación al crear (título required, unique por user)
- `UpdateNoteRequest` → Validación al editar
- `SearchNoteRequest` → Validación de query y operadores

**Middleware:**
- `AuthUser` → Inyecta usuario autenticado (mock en MVP, JWT en Fase 2)

---

## Flujo técnico

### UC1: Crear nota
```
POST /notes
  ├─ Validar (CreateNoteRequest)
  │   └─ Título: required, ≤255, unique per user
  ├─ NoteService::create($request->user(), $data)
  │   ├─ Crear modelo Note
  │   ├─ Asignar user_id = $user->id
  │   ├─ Fire evento: Note.created
  │   └─ Retornar nota
  └─ Response: 201 Created + nota JSON
```

### UC2: Listar notas propias
```
GET /notes?page=1&per_page=20
  ├─ Obtener user autenticado
  ├─ NoteService::getMyNotes($user, $page)
  │   ├─ Query: notes.where('user_id', $user->id)
  │   ├─ Lazy Load: 20 por página (scroll load más)
  │   ├─ Eager load: tags (relación M:M)
  │   └─ Retornar collection con paginación
  └─ Response: 200 OK + array de notas
```

### UC3: Ver detalle de nota
```
GET /notes/{id}
  ├─ Obtener user autenticado
  ├─ NoteService::show($user, $noteId)
  │   ├─ Buscar nota
  │   ├─ Verificar: nota->user_id == $user->id (propietario)
  │   ├─ Eager load: tags
  │   └─ Retornar nota completa
  └─ Response: 200 OK o 404 Not Found
```

### UC4: Editar nota
```
PUT /notes/{id}
  ├─ Validar (UpdateNoteRequest)
  │   ├─ Título: required, ≤255, unique per user EXCEPTO la nota actual
  │   └─ Content/description: optional
  ├─ NoteService::update($user, $noteId, $data)
  │   ├─ Verificar ownership (user_id == $user->id)
  │   ├─ Actualizar campos
  │   ├─ Fire evento: Note.edited
  │   └─ Retornar nota actualizada
  └─ Response: 200 OK o 403 Forbidden
```

### UC5: Eliminar nota
```
DELETE /notes/{id}
  ├─ Obtener user autenticado
  ├─ NoteService::delete($user, $noteId)
  │   ├─ Verificar ownership
  │   ├─ Cascade delete: note_tag (pivot)
  │   ├─ Eliminar nota (permanent)
  │   ├─ Fire evento: Note.deleted
  │   └─ Retornar confirmación
  └─ Response: 204 No Content o 403 Forbidden
```

### UC6: Buscar notas
```
GET /notes/search?q=query&op=AND&tags=tag1,tag2
  ├─ Validar (SearchNoteRequest)
  ├─ SearchService::search($user, $query, $operator, $tagIds)
  │   ├─ Parse operadores: "word1 AND word2" → ['word1', 'word2']
  │   ├─ Query builder:
  │   │   ├─ WHERE user_id = $user->id
  │   │   ├─ WHERE (operator AND/OR)
  │   │   ├─ MATCH title/content/description AGAINST query
  │   │   └─ WHERE tags IN (...)
  │   ├─ Lazy load resultados
  │   └─ Fire evento: Search.executed
  └─ Response: 200 OK + array de notas
```

### UC7: Asignar tags a nota
```
POST /notes/{id}/tags
  ├─ Obtener/crear tag
  ├─ TagService::attachTag($note, $tagName)
  │   ├─ Buscar o crear tag (unique per user)
  │   ├─ Attach a nota (no duplicados)
  │   └─ Retornar nota con tags
  └─ Response: 200 OK
```

---

## Persistencia

### Tablas requeridas

**notes**
```sql
id (PK)
user_id (FK users)
title (string, ≤255, UNIQUE per user)
content (longtext, nullable)
description (string, ≤500, nullable)
created_at (timestamp)
updated_at (timestamp)

Índices:
- (user_id, title) UNIQUE
- FULLTEXT (title, content, description)
```

**tags**
```sql
id (PK)
user_id (FK users)
name (string, ≤100, UNIQUE per user)
created_at (timestamp)

Índices:
- (user_id, name) UNIQUE
```

**note_tag** (pivot)
```sql
note_id (FK notes, cascade delete)
tag_id (FK tags)
created_at (timestamp)

Índices:
- PRIMARY (note_id, tag_id) UNIQUE
```

**users** (ya existe)
```sql
id (PK)
email (string, UNIQUE)
password_hash (string)
created_at (timestamp)
updated_at (timestamp)
```

### Migraciones pendientes
- Verificar columna `user_id` en `notes`
- Verificar índice FULLTEXT en `notes`
- Verificar restricción UNIQUE en `(user_id, title)`

---

## Testing

### Unit Tests
- `SearchService::parseQuery()` → AND/OR operators parsing
- `NoteService::validateTitle()` → uniqueness per user
- `TagService::attachTag()` → duplicate prevention

### Feature Tests
- Crear nota con título duplicado → 409 Conflict
- Editar nota con título duplicado → 409 Conflict
- Ver nota de otro usuario → 404 Not Found
- Buscar con AND operator → solo notas con AMBAS palabras
- Buscar con OR operator → notas con CUALQUIER palabra
- Lazy load → cargar más en scroll (20 + 20 + ...)

### Edge Cases
- Nota con contenido vacío pero título → debe permitir
- Tags duplicados en misma nota → ignorar silenciosamente
- Query búsqueda vacía → retornar 400 Bad Request
- Actualizar nota mientras se edita → último write wins

---

## Riesgos

### Técnicos
1. **SQL Injection en búsqueda**: Mitigar con prepared statements (Laravel automático)
2. **Performance de full-text**: Índices requeridos en BD, sin éstos búsqueda lenta
3. **Lazy load vs paginación**: Lazy load sin offset + cursor (futuro: puede ser ineficiente en BD)
4. **Soft-delete en futuro**: Si se añade después, breaking change en lógica

### Arquitectónicos
1. **User mock en MVP**: ¿Cómo simular usuarios sin Auth? → Asumir user_id = 1 en middleware mock
2. **Permisos en SharedLink**: Fase 3 requerirá refactoring de NoteService para soportar invitados

### Dependencias
1. **Librería full-text search**: MySQL FULLTEXT suficiente para MVP, futuro: Elasticsearch si crece

---

## Decisiones abiertas

1. ¿Usar Repository pattern o Eloquent directo en Service? → Eloquent directo (MVP simple)
2. ¿Eventos síncronos o queue? → Síncronos (MVP sin colas de trabajo)
3. ¿Caché en búsquedas repetidas? → No para MVP
4. ¿Soft-delete de notas? → No, eliminación permanente confirmada
5. ¿Versioning de notas? → No para MVP

