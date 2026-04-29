# Requirements — Auth

## Objetivo

Implementar autenticación stateless basada en JWT para permitir que usuarios se registren, inicien sesión y accedan a recursos protegidos. Los tokens son de corta duración (60 minutos) sin mecanismo de revoke centralizado.

---

## Actores

- **Usuario no autenticado**: Puede registrarse o iniciar sesión.
- **Usuario autenticado**: Tiene token JWT válido en header `Authorization: Bearer {token}`.
- **Sistema**: Valida tokens, expira sesiones, genera nuevos tokens.

---

## Reglas de negocio

1. **Registro**: Email único, contraseña hasheada (bcrypt), confirmación de email (futuro, MVP sin confirmación).
2. **Login**: Email + contraseña válida → genera JWT.
3. **Token**:
   - Duración: 60 minutos (2700 segundos)
   - Contenido: user_id, email, iat, exp, iss
   - Firma: HS256 (secret key en .env)
4. **Logout**: Cliente elimina token de localStorage (no hay invalidación servidor).
5. **Sin revoke**: Token sigue siendo válido hasta expiración (60 min), incluso después de logout solicitado.
6. **Refresh**: Endpoint `/auth/refresh` con token válido → nuevo token (no implementar en MVP, futuro).

---

## Entradas (Casos de uso)

### UC1: Registrarse
- **Entrada**: email (string, email válido, ≤ 255), password (string, ≥ 8 chars, confirmación)
- **Validación**: 
  - Email válido y no duplicado
  - Contraseña ≥ 8 caracteres
  - Contraseñas coinciden
- **Salida**: Usuario creado, no retorna token (solo confirmación)
- **Evento**: Auth.registered (user_id, email, ip)

### UC2: Iniciar sesión
- **Entrada**: email, password
- **Validación**: Email existe, contraseña coincide
- **Salida**: {token: "eyJ...", expires_in: 900, user: {id, email}}
- **Evento**: Auth.login (user_id, ip)

### UC3: Acceder recurso protegido
- **Entrada**: Header `Authorization: Bearer {token}`
- **Validación**: Token válido, no expirado, firma correcta
- **Salida**: Request continúa con `$request->user()` inyectado (user_id, email)
- **Error**: 401 Unauthorized si token inválido/expirado

### UC4: Refrescar token (Futuro)
- **Entrada**: Token válido (aunque cerca expiración)
- **Validación**: Token no expirado
- **Salida**: Nuevo token {token: "...", expires_in: 900}
- **Nota**: MVP sin implementar, preparar arquitectura

### UC5: Logout
- **Entrada**: Token (para confirmar user)
- **Validación**: Token válido
- **Salida**: Confirmación, client elimina localStorage
- **Nota**: No invalida token servidor (expirará en 60 min)
- **Evento**: Auth.logout (user_id, ip)

---

## Salidas esperadas

### Estructura - Usuario
```json
{
  "id": 1,
  "email": "user@example.com",
  "password_hash": "$2y$10$...",
  "created_at": "2026-04-27T10:30:00Z",
  "updated_at": "2026-04-27T10:30:00Z"
}
```

### Token JWT
```json
{
  "sub": 1,
  "email": "user@example.com",
  "iat": 1714222200,
  "exp": 1714223100,
  "iss": "grimorio"
}
```

### Respuesta POST /auth/register
```json
{
  "message": "Usuario registrado exitosamente",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "created_at": "2026-04-27T10:30:00Z"
  }
}
```

### Respuesta POST /auth/login
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expires_in": 900,
  "user": {
    "id": 1,
    "email": "user@example.com"
  }
}
```

### Respuestas HTTP
- **200 OK**: Login exitoso, token retornado
- **201 Created**: Usuario registrado
- **204 No Content**: Logout exitoso
- **400 Bad Request**: Email no válido, contraseña débil, datos incompletos
- **401 Unauthorized**: Credenciales inválidas, token expirado/inválido
- **409 Conflict**: Email ya registrado
- **422 Unprocessable Entity**: Validación fallida (contraseñas no coinciden, etc.)

---

## Casos límite

1. **Login con email no registrado**: Retornar error genérico (no revelar si existe o no).
2. **Contraseña incorrecta**: Error 401 genérico (sin indicar que email existe).
3. **Token expirado a mitad de request**: Retornar 401, forzar re-login.
4. **Múltiples logins**: Usuario puede tener varios tokens válidos simultáneamente (no hay sesión única).
5. **Logout sin token**: Ignorar silenciosamente o retornar 200 (UX: no error).
6. **Refresh de token expirado**: Error 401 (debe re-loguear).
7. **Header Authorization malformado**: Error 400 (ej: "Bearer" sin token).
8. **Secret key comprometida**: Todos los tokens quedan inseguros (rotación manual necesaria).

---

## Dependencias

- **Base de datos**: Tabla `users` (id, email, password_hash, created_at, updated_at)
- **Índice**: `users.email` UNIQUE
- **Librería JWT**: `firebase/php-jwt` o equivalente
- **Hashing**: `password_hash()` con bcrypt (default Laravel)
- **Middleware**: `Auth:api` (o personalizado JwtMiddleware)
- **Config**: `.env` con `JWT_SECRET`, `JWT_EXPIRATION` (900 segundos)
- **Feature Note**: Requerirá autenticación para acceder a propias notas

---

## Dudas abiertas (RESUELTAS)

1. ¿Validación de email real (verificación)? → **No para MVP** (futuro: Fase 2b).
2. ¿Recuperación de contraseña? → **No para MVP**.
3. ¿2FA (two-factor auth)? → **No para MVP**.
4. ¿Rate limiting en login? → **Sí** (5 intentos/minuto/IP).
5. ¿Guardar IP de login en auditoría? → **Sí**, en eventos pero no persistir en BD.
6. ¿Refresh token separado (con mayor duración)? → **No para MVP**, explorar en Fase 2b.
7. ¿Almacenar tokens revocados (blacklist)? → **No**, expiración corta suficiente.
8. ¿CORS para API desde frontend? → **Sí**, definir orígenes permitidos en config.

