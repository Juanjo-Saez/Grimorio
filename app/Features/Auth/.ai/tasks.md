# Tasks — Auth (Fase 2)

## Context

Implementar autenticación stateless con JWT (60 minutos). Usuarios se registran, loguearse reciben token, acceden a recursos con token. Sin refresh token en MVP (reauthentication necesaria). Rate limiting en login (5 intentos/min/IP).

---

## Task List

### 1. Preparar estructura base de Feature

#### 1.1 Crear carpetas feature
- [ ] Crear `app/Features/Auth/` con subdirectorios:
  - Models/
  - Controllers/
  - Services/
  - Requests/
  - Middleware/
  - Helpers/
  - routes.php

#### 1.2 Crear archivo routes
- [ ] `app/Features/Auth/routes.php`:
  ```php
  Route::post('/auth/register', [AuthController::class, 'register']);
  Route::post('/auth/login', [AuthController::class, 'login']);
  Route::middleware('auth:api')->group(function () {
      Route::post('/auth/logout', [AuthController::class, 'logout']);
      Route::post('/auth/refresh', [AuthController::class, 'refresh']);
  });
  ```
- [ ] Registrar en `routes/api.php` o `routes/web.php`

#### 1.3 Crear provider (opcional, pero recomendado)
- [ ] `app/Providers/AuthServiceProvider.php` que registre servicios

---

### 2. Configuración JWT

#### 2.1 Agregar variables .env
- [ ] Actualizar `.env` con:
  ```
  JWT_SECRET=random_secret_key_here
  JWT_EXPIRATION=3600
  JWT_ALGORITHM=HS256
  ```
- [ ] Ejecutar: `php artisan key:generate` (si no existe)

#### 2.2 Crear config/jwt.php (opcional)
- [ ] Centralizar configuración JWT

---

### 3. Persistencia y Models

#### 3.1 Verificar/crear migraciones
- [ ] Revisar si `users` table existe
- [ ] Si falta: Crear migración `create_users_table`:
  ```sql
  id, email (UNIQUE), password_hash, created_at, updated_at
  ```
- [ ] Ejecutar: `php artisan migrate`

#### 3.2 Crear/actualizar Model User
- [ ] `app/Models/User.php` (o `app/Features/Auth/Models/User.php`):
  - Implementar `Authenticatable` (o similar)
  - Propiedades: id, email, password_hash
  - Hidden: password_hash
  - Relaciones: hasMany(Note), hasMany(Tag)

#### 3.3 Crear Factory y Seeder
- [ ] `database/factories/UserFactory.php`
- [ ] `database/seeders/UserSeeder.php`

---

### 4. Servicios (lógica de autenticación)

#### 4.1 Crear JwtService
- [ ] `app/Features/Auth/Services/JwtService.php`:
  - `generateToken($user)` → Crear JWT válido 60 min
  - `validateToken($token)` → Validar firma + expiración
  - `refreshToken($oldToken)` → Generar nuevo token (futuro)
  - `parseToken($token)` → Extraer payload

#### 4.2 Crear AuthService
- [ ] `app/Features/Auth/Services/AuthService.php`:
  - `register($email, $password, $passwordConfirm)` → Crear user
  - `login($email, $password)` → Autenticar + generar token
  - `logout($userId, $ip)` → Log evento
  - `validatePassword($password, $hash)` → Verificar bcrypt
  - `validateEmail($email)` → Formato válido
  - `checkRateLimiting($ip)` → 5 intentos/min

---

### 5. Helpers y Utilidades

#### 5.1 Crear JwtTokenHelper (opcional pero recomendado)
- [ ] `app/Features/Auth/Helpers/JwtTokenHelper.php`:
  - Métodos auxiliares para JWT (encode, decode, validate signature)
  - Usar librería: `firebase/php-jwt`

#### 5.2 Instalar dependencias
- [ ] `composer require firebase/php-jwt`

---

### 6. Validadores (Requests)

#### 6.1 Crear RegisterRequest
- [ ] `app/Features/Auth/Requests/RegisterRequest.php`:
  - email: required, email, unique:users
  - password: required, string, min:8, confirmed
  - Mensaje error personalizado para email duplicado

#### 6.2 Crear LoginRequest
- [ ] `app/Features/Auth/Requests/LoginRequest.php`:
  - email: required, email
  - password: required, string
  - Rate limiting check en authorize()

---

### 7. Middleware

#### 7.1 Crear JwtMiddleware
- [ ] `app/Features/Auth/Middleware/JwtMiddleware.php`:
  - Interceptar Authorization header
  - Validar token con JwtService
  - Inyectar $request->user() (con id, email)
  - Retornar 401 si token inválido/expirado

#### 7.2 Registrar middleware
- [ ] En `app/Http/Kernel.php`:
  - Agregar a `$routeMiddleware['auth:api']`

#### 7.3 Crear middleware de rate limiting (opcional)
- [ ] `app/Features/Auth/Middleware/RateLimitLogin.php`
- [ ] Usar Laravel Cache

---

### 8. Controllers

#### 8.1 Crear AuthController
- [ ] `app/Features/Auth/Controllers/AuthController.php`:
  - `register(RegisterRequest $request)` → POST /auth/register
  - `login(LoginRequest $request)` → POST /auth/login
  - `logout()` → POST /auth/logout
  - `refresh()` → POST /auth/refresh (futuro)

#### 8.2 Implementar acciones
- [ ] Cada acción llama al service correspondiente
- [ ] Manejar excepciones: validation, rate limit, not found
- [ ] Retornar JSON con status codes correctos

---

### 9. Testing - Unitarios

#### 9.1 Unit test: JwtService
- [ ] `tests/Unit/Auth/JwtServiceTest.php`:
  - test_generate_token_valid_payload()
  - test_generate_token_has_correct_expiration()
  - test_validate_token_valid() → retorna true
  - test_validate_token_expired() → retorna false
  - test_validate_token_invalid_signature() → retorna false
  - test_parse_token_extracts_payload()

#### 9.2 Unit test: AuthService
- [ ] `tests/Unit/Auth/AuthServiceTest.php`:
  - test_register_creates_user()
  - test_register_hashes_password()
  - test_register_duplicate_email_throws()
  - test_login_valid_credentials() → retorna token
  - test_login_invalid_password() → retorna error
  - test_validate_password_correct()
  - test_validate_password_incorrect()

#### 9.3 Unit test: Rate Limiting
- [ ] `tests/Unit/Auth/RateLimitTest.php`:
  - test_first_5_attempts_allowed()
  - test_6th_attempt_blocked()
  - test_limit_resets_after_minute()

---

### 10. Testing - Feature/Integration

#### 10.1 Feature test: Registro
- [ ] `tests/Feature/Auth/RegisterTest.php`:
  - test_register_with_valid_data() → 201 Created
  - test_register_with_duplicate_email() → 409 Conflict
  - test_register_password_weak() → 422 Unprocessable
  - test_register_password_mismatch() → 422
  - test_register_email_invalid() → 422

#### 10.2 Feature test: Login
- [ ] `tests/Feature/Auth/LoginTest.php`:
  - test_login_valid_credentials() → 200 OK + token
  - test_login_invalid_email() → 401 Unauthorized
  - test_login_invalid_password() → 401 Unauthorized
  - test_login_rate_limited() → 429 Too Many Requests
  - test_login_returns_token_with_expiration()

#### 10.3 Feature test: Token validation
- [ ] `tests/Feature/Auth/TokenTest.php`:
  - test_access_protected_route_with_valid_token() → 200 OK
  - test_access_protected_route_without_token() → 401
  - test_access_protected_route_with_expired_token() → 401
  - test_access_protected_route_with_invalid_token() → 401

#### 10.4 Feature test: Logout
- [ ] `tests/Feature/Auth/LogoutTest.php`:
  - test_logout_returns_204() → 204 No Content
  - test_logout_fires_event()

---

### 11. Integración con Feature Note

#### 11.1 Actualizar NoteController
- [ ] Cambiar middleware mock por `auth:api`
- [ ] Usar `$request->user()` real (con JWT)
- [ ] Verificar que user_id viene del JWT

#### 11.2 Tests de integración
- [ ] Crear nota siendo autenticado con JWT
- [ ] Listar notas de user autenticado
- [ ] Rechazar acceso sin token

---

### 12. Configuración CORS

#### 12.1 Configurar CORS en Laravel
- [ ] Editar `config/cors.php`:
  - `allowed_origins` → ['localhost:3000', 'grimorio.local']
  - `allowed_methods` → ['GET', 'POST', 'PUT', 'DELETE']
  - `allowed_headers` → ['Content-Type', 'Authorization']
- [ ] Middleware ya aplicado (default en Laravel)

---

### 13. Documentación y logging

#### 13.1 Logging de eventos
- [ ] Listeners para:
  - `Auth.registered` → log user_id, email
  - `Auth.login` → log user_id, ip
  - `Auth.logout` → log user_id, ip
  - Rate limit exceeded → log ip, email

#### 13.2 Crear README técnico
- [ ] `app/Features/Auth/README.md`:
  - Descripción de autenticación JWT
  - Ejemplos de requests (registro, login, acceso recurso)
  - Configuración requerida (.env)
  - Decisiones técnicas (sin refresh token en MVP, etc.)

---

## Dependencias entre tareas

```
2. Config JWT → 3. BD/Models → 4. Servicios → 
5. Helpers (firebase/php-jwt) → 6. Validadores → 
7. Middleware → 8. Controllers → 9. Tests unitarios → 
10. Tests feature → 11. Integración Note → 12. CORS → 13. Docs
```

---

## Criterios de aceptación por tarea

- [ ] JWT generado con payload correcto y firma HS256
- [ ] Token expira exactamente en 60 minutos
- [ ] Login rate limitado: 5 intentos/min/IP
- [ ] Password hasheado con bcrypt, nunca en plaintext
- [ ] Acceso a recursos protegidos requiere token válido
- [ ] Token expirado rechazado con 401
- [ ] Registro valida email único y contraseña fuerte
- [ ] Tests ≥ 85% cobertura
- [ ] CORS configurado para frontend
- [ ] Eventos de seguridad (login, logout) se registran

