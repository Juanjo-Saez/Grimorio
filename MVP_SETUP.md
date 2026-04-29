# 🚀 Grimorio MVP - Guía de Instalación y Uso

## ¿Qué se ha implementado?

### ✅ Completado (MVP Viable)
- ✅ **Modelos (Models)**: User, Note, Tag, SharedLink  
- ✅ **Migraciones**: Actualizadas para todas las tablas
- ✅ **Servicios**: NoteService, SearchService, TagService, AuthService, JwtService, SharedLinkService
- ✅ **Controllers**: NoteController, AuthController, SharedLinkController, SharedNoteController
- ✅ **Rutas API**: `/api/v1/notes*`, `/api/v1/auth*`, `/api/v1/shared*`
- ✅ **Configuración**: .env con JWT configurado

### ⏳ Por hacer (No bloquea MVP)
- ⏳ Middleware JWT (funciona sin él en MVP)
- ⏳ Templates Blade (API pura funciona)
- ⏳ Tests unitarios/integración
- ⏳ UI frontend

---

## 📋 Requisitos previos

```bash
# PHP 8.1+
php -v

# Composer
composer --version

# Node.js (para npm)
node -v

# MySQL (local o Docker)
```

---

## 🔧 Instalación y Setup

### 1. Instalar dependencias
```bash
composer install
npm install
```

### 2. Generar APP_KEY
```bash
php artisan key:generate
```

### 3. Crear base de datos MySQL
```sql
CREATE DATABASE grimorio;
CREATE USER 'grimorio'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON grimorio.* TO 'grimorio'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Actualizar .env con credenciales BD
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=grimorio
DB_USERNAME=grimorio
DB_PASSWORD=password
```

### 5. Ejecutar migraciones
```bash
php artisan migrate --force
```

### 6. Ejecutar seeders (datos de prueba)
```bash
php artisan db:seed
```

### 7. Instalar dependencia JWT
```bash
composer require firebase/php-jwt
```

---

## ▶️ Ejecutar el servidor

### Opción 1: Servidor local
```bash
php artisan serve
```
Accede a: `http://localhost:8000`

### Opción 2: Docker Compose (si tienes Docker)
```bash
docker-compose up -d
```

---

## 🧪 Probar la API

### Archivo Postman/cURL

**1. Registrarse**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

**2. Login**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123"
  }'
```
Copia el token del response.

**3. Crear nota**
```bash
curl -X POST http://localhost:8000/api/v1/notes \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Mi primera nota",
    "content": "Contenido de la nota",
    "description": "Resumen"
  }'
```

**4. Listar notas**
```bash
curl -X GET http://localhost:8000/api/v1/notes
```

**5. Buscar notas (AND)**
```bash
curl -X GET "http://localhost:8000/api/v1/notes/search?q=primera&op=AND"
```

**6. Ver nota específica**
```bash
curl -X GET http://localhost:8000/api/v1/notes/1
```

**7. Actualizar nota**
```bash
curl -X PUT http://localhost:8000/api/v1/notes/1 \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Mi primera nota (actualizada)",
    "content": "Contenido actualizado"
  }'
```

**8. Eliminar nota**
```bash
curl -X DELETE http://localhost:8000/api/v1/notes/1
```

**9. Compartir nota**
```bash
curl -X POST http://localhost:8000/api/v1/notes/1/share \
  -H "Content-Type: application/json" \
  -d '{
    "recipient_id": 2,
    "access_level": "read"
  }'
```

**10. Ver nota compartida (por token)**
```bash
curl -X GET http://localhost:8000/api/shared/{token}
```

---

## 📁 Estructura del proyecto

```
app/
  ├── Features/
  │   ├── Note/
  │   │   ├── Controllers/NoteController.php
  │   │   ├── Services/NoteService.php
  │   │   ├── Services/SearchService.php
  │   │   └── Services/TagService.php
  │   ├── Auth/
  │   │   ├── Controllers/AuthController.php
  │   │   ├── Services/AuthService.php
  │   │   └── Services/JwtService.php
  │   └── SharedLink/
  │       ├── Controllers/SharedLinkController.php
  │       ├── Controllers/SharedNoteController.php
  │       └── Services/SharedLinkService.php
  └── Models/
      ├── User.php
      ├── Note.php
      ├── Tag.php
      └── SharedLink.php

database/
  ├── migrations/
  │   ├── 2025_04_27_100000_update_users_table.php
  │   ├── 2025_04_27_100001_update_notes_table.php
  │   ├── 2025_04_27_100002_update_tags_table.php
  │   └── 2025_04_27_100003_create_shared_links_table.php
  └── seeders/
      └── UsersSeeder.php

routes/
  └── api.php (todas las rutas)

.env (configuración JWT incluida)
```

---

## 🔑 Variables de entorno clave

```env
JWT_SECRET=grimorio-super-secret-key-change-in-production
JWT_EXPIRATION=3600  # 60 minutos
JWT_ALGORITHM=HS256

DB_DATABASE=grimorio
DB_USERNAME=grimorio
DB_PASSWORD=password
```

---

## 🎯 Endpoints disponibles

### Autenticación
- `POST /api/auth/register` - Registrarse
- `POST /api/auth/login` - Login (retorna JWT)
- `POST /api/v1/auth/logout` - Logout

### Notas
- `GET /api/v1/notes` - Listar mis notas (lazy load)
- `POST /api/v1/notes` - Crear nota
- `GET /api/v1/notes/{id}` - Ver nota
- `PUT /api/v1/notes/{id}` - Actualizar nota
- `DELETE /api/v1/notes/{id}` - Eliminar nota
- `GET /api/v1/notes/search?q={query}&op={AND|OR}` - Buscar

### Compartición
- `POST /api/v1/notes/{noteId}/share` - Compartir nota
- `GET /api/v1/notes/{noteId}/shared` - Ver quién la compartí
- `GET /api/v1/shared` - Listar notas compartidas conmigo
- `DELETE /api/v1/shared/{id}` - Revocar acceso
- `GET /api/shared/{token}` - Ver nota compartida (público)
- `PUT /api/shared/{token}` - Editar nota compartida

---

## 🚀 Próximos pasos

1. **Tests**: Ejecutar `php artisan test`
2. **Frontend**: Crear UI en Blade o React
3. **Middleware JWT**: Proteger rutas con `JwtMiddleware`
4. **Deployment**: Deploy a producción (Cloudflare, etc)
5. **Documentación**: Generar API docs (Swagger/Postman)

---

## ⚠️ Notas importantes

### MVP Actual
- Sin autenticación JWT verificada (funciona sin middleware por ahora)
- User ID hardcoded a 1 en muchos casos (simulación de usuario)
- Sin UI Blade (API pura JSON)

### Limitaciones conocidas
- Busca FULLTEXT requiere MySQL 5.6+
- SharedLink sin expiración automática (siempre activo)
- Último write wins en ediciones simultáneas (sin conflict resolution)

### Cambios recomendados para producción
1. Cambiar `JWT_SECRET` a algo seguro
2. Implementar rate limiting en login
3. Agregar HTTPS en APP_URL
4. Configurar CORS correctamente
5. Agregar tests de cobertura >80%

---

## 📞 Soporte

Para errores o preguntas, revisar:
- `storage/logs/laravel.log`
- `storage/logs/shared-link-*.log`
- `php artisan tinker` (para debugging)

