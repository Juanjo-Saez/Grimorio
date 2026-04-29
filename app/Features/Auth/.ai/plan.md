# Plan — Auth (Fase 2)

## Arquitectura

### Componentes principales

**Models:**
- `User` → Entidad de usuario (email, password_hash)
- Relación: hasMany(Note), hasMany(Tag), hasMany(SharedLink)

**Services:**
- `AuthService` → Lógica de autenticación (registro, login, logout)
- `JwtService` → Generación y validación de tokens JWT

**Controllers:**
- `AuthController` → HTTP routes (register, login, logout, refresh)

**Requests (Validadores):**
- `RegisterRequest` → Validación de registro (email, password)
- `LoginRequest` → Validación de login
- `RefreshTokenRequest` → Validación de refresh (futuro)

**Middleware:**
- `JwtMiddleware` → Validar token en Authorization header
- `EnsureUserIsAuthenticated` → Proteger rutas

**Helpers:**
- `JwtTokenHelper` → Generar/parsear/validar tokens

---

## Flujo técnico

### UC1: Registrarse
```
POST /auth/register
  ├─ Validar (RegisterRequest)
  │   ├─ Email: válido, no duplicado
  │   ├─ Contraseña: ≥8 chars
  │   └─ Confirmación contraseña: coincide
  ├─ AuthService::register($email, $password)
  │   ├─ Hash contraseña: password_hash($pwd, PASSWORD_BCRYPT)
  │   ├─ Crear User en BD
  │   ├─ Fire evento: Auth.registered
  │   └─ Retornar usuario (sin token)
  └─ Response: 201 Created + {user: {id, email}}
```

### UC2: Iniciar sesión
```
POST /auth/login
  ├─ Validar (LoginRequest)
  │   ├─ Email: required, válido
  │   └─ Contraseña: required
  ├─ AuthService::login($email, $password)
  │   ├─ Buscar User por email
  │   ├─ Validar contraseña: password_verify($pwd, $user->password_hash)
  │   ├─ Si inválido → Rate limiting check (5 intentos/min/IP)
  │   ├─ JwtService::generateToken($user)
  │   │   ├─ Payload: {sub: $user->id, email: $user->email, iat, exp}
  │   │   ├─ Firma: HS256 con JWT_SECRET de .env
  │   │   ├─ Expiración: iat + 60 minutos
  │   │   └─ Retornar token
  │   ├─ Fire evento: Auth.login (user_id, ip)
  │   └─ Retornar {token, expires_in: 3600, user: {id, email}}
  └─ Response: 200 OK o 401 Unauthorized
```

### UC3: Acceder recurso protegido
```
GET /notes (cualquier endpoint protegido)
  ├─ JwtMiddleware intercepta request
  │   ├─ Extraer header: Authorization: Bearer {token}
  │   ├─ JwtService::validateToken($token)
  │   │   ├─ Verificar firma HS256
  │   │   ├─ Verificar expiración: $token->exp > time()
  │   │   ├─ Extraer payload: {sub, email, iat, exp}
  │   │   └─ Retornar payload o throw exception
  │   ├─ Inyectar $request->user() con datos decodificados
  │   └─ Pasar control a route handler
  ├─ Route handler usa $request->user()->id
  └─ Response: 200 OK o 401 Unauthorized
```

### UC4: Refrescar token (Futuro, arquitectura preparada)
```
POST /auth/refresh
  ├─ JwtMiddleware valida token existente
  ├─ JwtService::refreshToken($oldToken)
  │   ├─ Validar que NO está expirado (o cerca)
  │   ├─ Generar nuevo token con mismo sub
  │   └─ Retornar {token, expires_in}
  └─ Response: 200 OK
```

### UC5: Logout
```
POST /auth/logout
  ├─ JwtMiddleware valida token
  ├─ AuthService::logout($userId, $ip)
  │   ├─ Fire evento: Auth.logout (user_id, ip)
  │   └─ Retornar confirmación
  ├─ Cliente elimina token de localStorage
  └─ Response: 204 No Content
```

---

## Persistencia

### Tabla requerida

**users** (actualizar si existe)
```sql
id (PK)
email (string, UNIQUE, ≤255)
password_hash (string, bcrypt)
created_at (timestamp)
updated_at (timestamp)

Índices:
- email UNIQUE
```

### Rate Limiting (en memoria o cache)
- Usar Laravel Cache: `cache()->increment("login_attempts_{$ip}", 1, 60)` (1 min TTL)
- Si intentos ≥ 5 → 429 Too Many Requests

---

## Testing

### Unit Tests
- `JwtService::generateToken()` → payload correcto + firma válida
- `JwtService::validateToken()` → token válido pasa, expirado falla
- `AuthService::validatePassword()` → bcrypt verification
- `JwtTokenHelper::parseToken()` → extrae payload correctamente

### Feature Tests
- Registrarse con email válido → 201 Created + user
- Registrarse con email duplicado → 409 Conflict
- Registrarse con contraseña débil → 422 Unprocessable
- Login con credenciales válidas → 200 OK + token
- Login con credenciales inválidas → 401 Unauthorized
- Login rate limited → 429 Too Many Requests
- Acceder recurso sin token → 401 Unauthorized
- Acceder recurso con token válido → 200 OK + acceso
- Acceder recurso con token expirado → 401 Unauthorized
- Logout → 204 No Content

---

## Riesgos

### Seguridad
1. **Secret key comprometida**: Todos los tokens quedan inválidos (rotación manual necesaria)
2. **Rate limiting insuficiente**: Ataques de fuerza bruta (mitigación: 5 intentos/min)
3. **CORS mal configurado**: Otros dominios acceden a API (mitigar: whitelist orígenes)
4. **Token en localStorage**: XSS puede robarlo (futuro: considerar HttpOnly cookies)
5. **Headers HTTP inyectados**: Validar Authorization header format

### Arquitectónicos
1. **Sin refresh token**: Usuario debe re-loguear cada 60 min (aceptable para MVP)
2. **Sin revoke centralizado**: Token sigue válido hasta expiración (aceptable <100 usuarios)
3. **Sin 2FA**: Acceso solo con email+password (futuro: Fase 2b)

### Dependencias
1. **Librería JWT**: `firebase/php-jwt` (o similar compatibles)
2. **Bcrypt de PHP**: Built-in, no requiere librerías externas

---

## Decisiones abiertas

1. ¿Usar Session + JWT hybrid? → No, puro JWT (stateless)
2. ¿Almacenar tokens en Redis para revoke? → No para MVP
3. ¿Email confirmation requerido? → No para MVP
4. ¿Social login? → No para MVP
5. ¿Usar Passport de Laravel? → No, JWT manual (control total)

