# Plan — SharedLink (Fase 3)

## Arquitectura

### Componentes principales

**Models:**
- `SharedLink` → Token + relación entre nota, propietario e invitado
- Relación: belongsTo(User) [owner]
- Relación: belongsTo(User) [recipient]
- Relación: belongsTo(Note)

**Services:**
- `SharedLinkService` → Lógica de compartición (crear, revocar, listar)
- `SharedLinkAccessService` → Validación de permisos en nota compartida

**Controllers:**
- `SharedLinkController` → HTTP routes (create share, list shared)
- `SharedNoteController` → Ver/editar nota compartida vía token

**Requests (Validadores):**
- `CreateSharedLinkRequest` → Validación de compartición
- `EditSharedNoteRequest` → Validación de edición en nota compartida

**Helpers:**
- `TokenGenerator` → Generar tokens únicos aleatorios (64 chars)

---

## Flujo técnico

### UC1: Crear link compartido
```
POST /notes/{id}/share
  ├─ Obtener user autenticado
  ├─ Validar (CreateSharedLinkRequest)
  │   ├─ note_id: existe, pertenece a user
  │   ├─ recipient_id: existe, es otro usuario
  │   ├─ access_level: in:[read, edit]
  ├─ SharedLinkService::createLink($owner, $note, $recipient, $accessLevel)
  │   ├─ Generar token: random_bytes(32) → base64_url_encode
  │   ├─ Crear SharedLink en BD
  │   ├─ Fire evento: SharedLink.created
  │   └─ Retornar {token, url: "/shared/{token}"}
  └─ Response: 201 Created
```

### UC2: Ver nota compartida (vía token)
```
GET /shared/{token}
  ├─ SharedLinkService::validateToken($token)
  │   ├─ Buscar SharedLink por token
  │   ├─ Verificar no expirado (no aplica MVP, futuro)
  │   ├─ Verificar nota aún existe (no eliminada)
  │   └─ Retornar SharedLink + nota
  ├─ SharedNoteController::show($token)
  │   ├─ Obtener user actual (debe estar autenticado)
  │   ├─ Verificar user == recipient_id
  │   ├─ Eager load nota + tags + propietario
  │   └─ Retornar nota + access_level
  └─ Response: 200 OK o 404 Not Found
```

### UC3: Editar nota compartida (si access_level=edit)
```
PUT /shared/{token}
  ├─ Validar (EditSharedNoteRequest)
  │   ├─ content/description: optional
  │   └─ NO permitir cambiar title (solo propietario)
  ├─ SharedLinkAccessService::canEdit($sharedLink)
  │   ├─ Verificar access_level == 'edit'
  │   ├─ Verificar user == recipient_id
  │   └─ Retornar true/false
  ├─ Si permitido:
  │   ├─ Actualizar nota (content/description)
  │   ├─ Fire evento: SharedLink.note_edited
  │   └─ Retornar nota actualizada
  └─ Response: 200 OK o 403 Forbidden
```

### UC4: Listar notas compartidas conmigo
```
GET /shared?owner_id=1&access_level=read
  ├─ Obtener user autenticado (recipient)
  ├─ SharedLinkService::getSharedWithMe($user, $filters)
  │   ├─ Query: shared_links.recipient_id = $user->id
  │   ├─ Filtrar por owner_id (opcional)
  │   ├─ Filtrar por access_level (opcional)
  │   ├─ Eager load: nota, propietario, tags
  │   └─ Lazy load: 20 por página
  └─ Response: 200 OK + array de notas compartidas
```

### UC5: Revocar acceso a nota
```
DELETE /shared/{id}
  ├─ Obtener user autenticado (debe ser owner)
  ├─ SharedLinkService::revoke($user, $sharedLinkId)
  │   ├─ Verificar ownership (owner_id == user->id)
  │   ├─ Eliminar SharedLink
  │   ├─ Fire evento: SharedLink.revoked
  │   └─ Retornar confirmación
  └─ Response: 204 No Content o 403 Forbidden
```

### UC6: Listar notas que he compartido
```
GET /notes/{id}/shared
  ├─ Obtener user autenticado (owner)
  ├─ SharedLinkService::getSharedByMe($user, $noteId)
  │   ├─ Verificar nota pertenece a user
  │   ├─ Query: shared_links.owner_id = user->id AND note_id = $noteId
  │   ├─ Eager load: recipient, access_level
  │   └─ Retornar array de recipients
  └─ Response: 200 OK o 404 Not Found
```

---

## Persistencia

### Tabla requerida

**shared_links**
```sql
id (PK)
note_id (FK notes, cascade delete)
owner_id (FK users)
recipient_id (FK users)
token (string, UNIQUE, 64 chars)
access_level (enum: 'read', 'edit')
created_at (timestamp)

Índices:
- token UNIQUE
- (note_id, recipient_id) para evitar duplicados
- (owner_id, created_at) para listar shares del owner
- (recipient_id) para listar shares recibidas
```

### Relaciones
- Note.shared_links → hasMany(SharedLink)
- User (owner).shared_links_owned → hasMany(SharedLink, 'owner_id')
- User (recipient).shared_links_received → hasMany(SharedLink, 'recipient_id')

---

## Testing

### Unit Tests
- `TokenGenerator::generate()` → token 64 chars, URL-safe, único
- `SharedLinkAccessService::canView()` → user == recipient
- `SharedLinkAccessService::canEdit()` → access_level == 'edit'
- `SharedLinkService::validateToken()` → token existe y nota válida

### Feature Tests
- Crear share: nota existe, owner autenticado → 201 Created + token
- Ver nota compartida con read → 200 OK, ver contenido
- Editar nota compartida con read → 403 Forbidden
- Editar nota compartida con edit → 200 OK, actualizada
- Editar título en compartida → 403 Forbidden (solo propietario)
- Revocar acceso: solo owner → 204 No Content
- Recipient intenta revocar → 403 Forbidden
- Ver nota compartida eliminada → 404 Not Found
- Listar mis shares (owner) → 200 OK + array
- Listar shares conmigo (recipient) → 200 OK + array
- Filtrar shares por access_level → solo coincidentes
- Último write wins en edición simultánea → valor final correcto

---

## Riesgos

### Seguridad
1. **Token predecible**: Usar `random_bytes()` + base64_url_encode (suficiente)
2. **Token reutilizable**: Sin expiración (riesgo bajo en MVP, futuro: TTL)
3. **Access level bypass**: Validar en cada request (no confiar en frontend)
4. **Propietario elimina nota**: SharedLink queda huérfano (cascade delete maneja)
5. **Recipient eliminado**: Link sigue existiendo (aceptable, acceso bloqueado por user_id)

### Arquitectónicos
1. **Edición simultánea**: Último write wins (sin locking, conflict resolution futuro)
2. **Relaciones: User.shared_links compleja**: Usar scopes para claridad
3. **Cascada eliminar nota**: ¿Notificar recipient? → No para MVP

### Dependencias
1. **Feature Auth**: Requiere JWT para autenticación
2. **Feature Note**: Modelos y lógica de notas
3. **Broadcast (futuro)**: Si se añade edición en tiempo real

---

## Decisiones abiertas

1. ¿Permitir revoke masivo (todas las shares de una nota)? → No para MVP
2. ¿Notificación cuando recipient accede? → No, logging básico
3. ¿Contraseña adicional en link? → No, JWT suficiente
4. ¿Analytics de acceso? → No, nunca se implementará
5. ¿Expiración automática de link? → No para MVP

