# Requirements — Note

## Objetivo

Permitir a usuarios crear, consultar, editar y eliminar notas personales. Implementar un sistema robusto de búsqueda por texto (AND/OR operators) y categorización mediante tags para facilitar la gestión de notas siguiendo el método Zettelkasten.

---

## Actores

- **Usuario propietario**: Usuario autenticado (Fase 2) que crea y gestiona sus propias notas.
- **Sistema**: Realiza búsquedas, indexación de tags, validaciones de entrada.

---

## Reglas de negocio

1. **Propiedad de notas**: Cada nota tiene un propietario único (user_id). Solo el propietario puede editar/eliminar.
2. **Permisos en notas compartidas** (Fase 3):
   - **read**: Usuario invitado solo VE la nota (no puede editar ni eliminar)
   - **edit**: Usuario invitado puede VER y EDITAR pero NO puede eliminar
   - **sin permisos**: Usuario NO puede ver, editar ni eliminar
3. **Tags**: Los tags pertenecen al usuario (no globales). Un usuario puede crear sus propios tags y asignarlos a sus notas.
4. **Búsqueda privada**: En MVP, cada usuario solo ve sus propias notas.
5. **Integridad de datos**: No se puede crear nota sin título. El contenido puede estar vacío inicialmente.
6. **Unicidad de títulos**: Un usuario NO puede tener dos notas con el mismo título (unique per user).
7. **Eliminación permanente**: Las notas se eliminan de forma permanente (sin papelera/soft-delete).

---

## Entradas (Casos de uso)

### UC1: Crear nota
- **Entrada**: Título (required, string ≤ 255 chars), Contenido (optional, text), Descripción (optional, string ≤ 500)
- **Validación**: Título no vacío, sin caracteres especiales peligrosos
- **Salida**: Nota creada con ID, timestamps (created_at, updated_at), user_id
- **Evento**: Note.created (user_id, note_id)

### UC2: Ver lista de notas propias
- **Entrada**: user_id (autenticado), filtro opcional (tags, búsqueda)
- **Validación**: Usuario autenticado
- **Salida**: Array de notas [id, title, description, created_at, updated_at, tags]
- **Paginación**: 20 notas por página (futuro: configurable)

### UC3: Ver detalle de una nota
- **Entrada**: note_id, user_id
- **Validación**: Nota pertenece a user_id
- **Salida**: {id, title, content, description, created_at, updated_at, tags: [...]}
- **Error**: 404 si no existe o no pertenece al usuario

### UC4: Editar nota
- **Entrada**: note_id, user_id, título/contenido/descripción nuevos
- **Validación**: Nota pertenece a user_id O user_id tiene permisos de `edit` (Fase 3), título no vacío, título no duplicado en notas propias
- **Salida**: Nota actualizada
- **Evento**: Note.edited (user_id, note_id, fields_changed)
- **Restricción**: Si es nota compartida con permisos `read`, retornar 403 Forbidden

### UC5: Eliminar nota
- **Entrada**: note_id, user_id
- **Validación**: Nota pertenece a user_id (solo propietario puede eliminar)
- **Salida**: Nota eliminada permanentemente, tags desvinculados
- **Evento**: Note.deleted (user_id, note_id)
- **Restricción**: Usuario invitado (incluso con `edit` permisos) NO puede eliminar

### UC6: Buscar notas (texto)
- **Entrada**: query (string), user_id, operators (AND/OR)
- **Validación**: query no vacío, user autenticado
- **Salida**: Array de notas que coinciden, con relevancia/puntuación
- **Comportamiento**:
  - **AND** (default): Todas las palabras deben aparecer
  - **OR**: Al menos una palabra debe aparecer
  - **Búsqueda**: En título + contenido
- **Evento**: Search.executed (user_id, query, results_count)

### UC7: Filtrar por tags
- **Entrada**: tag_ids array, user_id
- **Validación**: Tags pertenecen a user_id
- **Salida**: Notas que tienen TODOS los tags (AND logic)
- **Combinable**: Puede combinarse con búsqueda de texto

### UC8: Crear/Asignar tags
- **Entrada**: tag_name (string ≤ 100), nota_id, user_id
- **Validación**: Tag_name único por usuario (no duplicar dentro del mismo usuario)
- **Salida**: Tag creado (o existente) y vinculado a nota
- **Nota**: Un tag puede usarse en múltiples notas

---

## Salidas esperadas

### Estructura de datos - Nota
```json
{
  "id": 1,
  "user_id": 1,
  "title": "Mi primera nota",
  "content": "Contenido largo...",
  "description": "Resumen corto",
  "created_at": "2026-04-27T10:30:00Z",
  "updated_at": "2026-04-27T12:15:00Z",
  "tags": [
    {"id": 1, "name": "zettelkasten"},
    {"id": 2, "name": "learning"}
  ]
}
```

### Estructura de datos - Tag
```json
{
  "id": 1,
  "user_id": 1,
  "name": "zettelkasten",
  "created_at": "2026-04-27T10:30:00Z"
}
```

### Respuestas HTTP
- **200 OK**: CRUD exitoso
- **201 Created**: Nota creada
- **204 No Content**: Eliminación exitosa
- **400 Bad Request**: Validación falla (título vacío, campos inválidos)
- **404 Not Found**: Nota o tag no existe
- **422 Unprocessable Entity**: Datos incompletos

---

## Casos límite

1. **Nota vacía**: ¿Permitir crear nota sin contenido pero con título? → SÍ, solo título es requerido.
2. **Tags duplicados**: ¿Qué pasa si asigno el mismo tag dos veces a una nota? → Ignorar duplicados (relación unique note_id + tag_id).
3. **Búsqueda vacía**: ¿Qué retorna si query=""? → Error 400 (query requerido).
4. **Caracteres especiales**: ¿Qué ocurre con SQL injection en título? → Usar prepared statements (Laravel escapa automático).
5. **Búsqueda con demasiados operadores**: ¿Límite de complejidad? → Inicialmente no, futuro: limitar a N operadores.
6. **Actualizar nota eliminada**: Si se elimina durante edición → Retornar 404.
7. **Tags sin asignar**: ¿Mantener tags que no tienen notas? → Sí (limpieza futura).
8. **Límite de notas**: ¿Hay límite? → No, MVP sin límite (<100 usuarios no justifica restricción).
9. **Edición simultánea**: Si dos usuarios editan mismo nota (compartida), último write wins (sin conflict resolution).
10. **Titulo duplicado**: Crear nota con título que ya existe en notas propias → Error 409 Conflict.

---

## Dependencias

- **Base de datos**: Tabla `notes`, `tags`, `note_tag` (pivot)
- **Índices**: `notes.(user_id, title)` UNIQUE, `tags.user_id`, `note_tag.(note_id, tag_id)`
- **Búsqueda full-text**: Índices FULLTEXT en MySQL para `notes.title`, `notes.content`, `notes.description`
- **Fase 2 (Auth)**: En MVP, asumir usuario único o contexto autenticado mock
- **Fase 3 (SharedLink)**: Requerirá validar permisos en acceso a notas
- **Logging**: Sistema de eventos ya existente

---

## Dudas abiertas (RESUELTAS)

1. ¿Paginación o lazy-load? → **Lazy Load** (cargar más notas al scroll).
2. ¿Permitir búsqueda de texto en la descripción también? → **SÍ** (incluir en búsqueda full-text).
3. ¿Los tags deben tener color o icono para UI? → **Solo nombres** (MVP sin estilos).
4. ¿Expiración de notas (borrado automático)? → **Nunca**, las notas se borran solo manualmente.
5. ¿Auditoría de quién editó qué? → **No por ahora**, logging básico sin historial de versiones.
6. ¿Soft delete de notas (papelera)? → **No**, eliminación permanente e inmediata.

