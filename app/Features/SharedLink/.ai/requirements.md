# Requirements — SharedLink

## Objetivo

Permitir que usuarios compartan notas individuales con otros usuarios autenticados a través de URLs privadas. Implementar control granular de permisos (lectura o lectura+edición) para notas compartidas.

---

## Actores

- **Propietario de nota**: Usuario que posee la nota y decide compartirla.
- **Usuario invitado**: Usuario autenticado que recibe acceso a nota compartida.
- **Sistema**: Valida permisos, genera tokens únicos, registra accesos.

---

## Reglas de negocio

1. **Compartición individual**: Se comparte una nota específica, no carpetas ni todas las notas.
2. **Permisos**:
   - **read**: Usuario invitado solo puede ver la nota (no editar)
   - **edit**: Usuario invitado puede ver y editar
3. **Token único**: Cada compartición genera un token único (URL-safe, 64 caracteres aleatorios).
4. **Revocabilidad**: Propietario puede revocar acceso eliminando el link.
5. **Ambos autenticados**: Propietario y invitado deben estar autenticados (no acceso anónimo).
6. **Traza**: Registrar quién accedió, cuándo, qué permisos tiene.

---

## Entradas (Casos de uso)

### UC1: Crear link compartido
- **Entrada**: note_id, recipient_user_id (o email), access_level (read/edit), propietario_id
- **Validación**: 
  - Nota pertenece a propietario_id
  - recipient_user_id existe y está autenticado
  - access_level en [read, edit]
- **Salida**: SharedLink creado con token único, URL: `/shared/{token}`
- **Evento**: SharedLink.created (owner_id, recipient_id, note_id, access_level)

### UC2: Ver nota compartida (vía token)
- **Entrada**: token (URL), user_id actual
- **Validación**: 
  - Token existe y no ha expirado
  - user_id coincide con recipient_user_id del link
  - Nota aún existe y no ha sido eliminada
- **Salida**: Nota completa {id, title, content, tags, owner: {id, email}}
- **Error**: 404 si token inválido, 403 si user no autorizado

### UC3: Editar nota compartida (si access_level=edit)
- **Entrada**: token, contenido actualizado, user_id
- **Validación**: 
  - Token con access_level=edit
  - user_id es recipient_user_id
  - Nota aún existe
- **Salida**: Nota actualizada
- **Evento**: SharedLink.note_edited (recipient_id, note_id, owner_id)
- **Nota**: Edición visible para ambos usuarios (propietario y invitado)

### UC4: Listar notas compartidas conmigo
- **Entrada**: user_id (recipient), filtros (owner_id, access_level)
- **Validación**: user autenticado
- **Salida**: Array de notas compartidas, con info de propietario
- **Paginación**: 20 por página

### UC5: Revocar acceso a nota
- **Entrada**: shared_link_id, propietario_id
- **Validación**: Link pertenece a propietario_id
- **Salida**: Link eliminado, usuario invitado ya no puede acceder
- **Evento**: SharedLink.revoked (owner_id, recipient_id, note_id)

### UC6: Listar notas que he compartido
- **Entrada**: propietario_id
- **Validación**: user autenticado
- **Salida**: Array de notas compartidas, con info de recipients
- **Información**: Quién, cuándo, permisos

---

## Salidas esperadas

### Estructura - SharedLink
```json
{
  "id": 1,
  "note_id": 5,
  "owner_id": 1,
  "recipient_id": 2,
  "token": "a7f3e8d2c1b4f9e6a3d5c8b1e4f7a2d5c9e1f4a7b0d3e6c9f2a5b8e1d4g7h",
  "access_level": "read",
  "created_at": "2026-04-27T10:30:00Z",
  "expires_at": null
}
```

### Respuesta GET /shared/{token}
```json
{
  "note": {
    "id": 5,
    "title": "Mi nota privada",
    "content": "Contenido...",
    "description": "Resumen",
    "tags": [...],
    "owner": {
      "id": 1,
      "email": "owner@example.com"
    },
    "access_level": "read"
  }
}
```

### Respuesta POST /notes/{id}/share
```json
{
  "shared_link": {
    "id": 1,
    "token": "a7f3e8d2c1b4f9e6a3d5c8b1e4f7a2d5c9e1f4a7b0d3e6c9f2a5b8e1d4g7h",
    "url": "https://grimorio.local/shared/a7f3e8d2c1b4f9e6a3d5c8b1e4f7a2d5c9e1f4a7b0d3e6c9f2a5b8e1d4g7h",
    "access_level": "read",
    "recipient": {
      "id": 2,
      "email": "recipient@example.com"
    }
  }
}
```

### Respuestas HTTP
- **200 OK**: Acceso a nota compartida, listar shares
- **201 Created**: Link compartido creado
- **204 No Content**: Revocación exitosa
- **400 Bad Request**: Datos incompletos, access_level inválido
- **403 Forbidden**: Usuario no autorizado, permisos insuficientes
- **404 Not Found**: Token no existe, nota eliminada
- **409 Conflict**: Ya compartida (si no permitir duplicados)

---

## Casos límite

1. **Token expirado**: ¿Permitir expiración? → MVP: no, future: opcional con TTL.
2. **Eliminar nota compartida**: Propietario elimina → link queda huérfano, acceso 404.
3. **Editar nota compartida como invitado**: Solo si access_level=edit, no puede cambiar título/tags.
4. **Compartir con el propietario**: ¿Permitir? → Error 400 (no tiene sentido).
5. **Compartir misma nota con mismo usuario**: ¿Duplicar o actualizar? → Error 409 o silencioso update.
6. **Invitado intenta revocar link**: Error 403 (solo propietario).
7. **Recipient es inactivo/eliminado**: Link sigue existiendo, acceso sigue válido hasta revoke.
8. **User_id en token vs link**: ¿Validar cada request? → Sí, token JWT puede cambiar (seguridad).
9. **Búsqueda en nota compartida**: ¿Permite búsqueda dentro de nota? → SÍ, mismo contenido que propietario.
10. **Tags en nota compartida**: ¿Muestra tags? → Sí, solo lectura para invitado.

---

## Dependencias

- **Base de datos**: Tabla `shared_links` (id, note_id, owner_id, recipient_id, token, access_level, created_at)
- **Índices**: `shared_links.(note_id, recipient_id)`, `shared_links.token` UNIQUE
- **Feature Auth**: Requiere autenticación JWT
- **Feature Note**: Requiere que nota exista y sea del propietario
- **Token generation**: Usar `random_bytes()` + base64_url_encode
- **Broadcast** (futuro): Si editor simultáneo, notificar cambios en tiempo real

---

## Dudas abiertas (RESUELTAS)

1. ¿Permitir compartición con múltiples usuarios de una sola nota? → **SÍ** (múltiples links por nota).
2. ¿Edición simultánea (conflictos)? → **Último write wins**, no hay conflict resolution.
3. ¿Historial de ediciones compartidas? → **No para MVP**.
4. ¿Notificaciones al invitado? → **No para MVP**, UI muestra notas compartidas.
5. ¿Expiración de link? → **No para MVP**, revoke manual suficiente.
6. ¿Contraseña adicional en link? → **No para MVP** (JWT suficiente).
7. ¿Copiar nota compartida a propias notas? → **No para MVP**.
8. ¿Permisos granulares (ej: comentarios)? → **No**, solo read/edit.
9. ¿Auditoría de quién editó qué en compartida? → **Básica** (registrar edit pero sin diff).
10. ¿Analytics: quién accedió cuándo? → **Nunca se implementará** (logging detallado no requerido).

