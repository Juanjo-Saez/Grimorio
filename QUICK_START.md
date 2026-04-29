# 🚀 Grimorio - Guía de Inicio Rápido

## ¿Qué está listo?

✅ **Interfaz completa en el navegador** (Blade templates)
✅ **API funcional** (todos los endpoints)
✅ **Base de datos** (migraciones y modelos)
✅ **Autenticación** (registro y login con JWT)

---

## 🎯 Pasos para ver la app en el navegador

### 1. Abre una terminal en `c:\Users\jjsaez\Downloads\PFC\Grimorio`

```bash
cd c:\Users\jjsaez\Downloads\PFC\Grimorio
```

### 2. Instala dependencias PHP

```bash
composer install
```

### 3. Instala JWT (requerido para tokens)

```bash
composer require firebase/php-jwt
```

### 4. Genera APP_KEY

```bash
php artisan key:generate
```

### 5. Crea la base de datos MySQL

Abre MySQL y ejecuta:
```sql
CREATE DATABASE grimorio;
```

O si usas `mysql` directamente:
```bash
mysql -u root -e "CREATE DATABASE grimorio;"
```

### 6. Ejecuta las migraciones

```bash
php artisan migrate --force
```

### 7. Ejecuta los seeders (datos de prueba)

```bash
php artisan db:seed
```

### 8. **¡Inicia el servidor!**

```bash
php artisan serve
```

Deberías ver:
```
INFO  Server running on [http://127.0.0.1:8000]
```

---

## 🌐 Accede en el navegador

**Abre tu navegador y ve a:**
```
http://localhost:8000
```

### Verás:
- 🏠 **Página de inicio** con info sobre Grimorio
- 🔐 Links a **Login** y **Registro**

---

## 📋 Cómo probar la app

### Opción 1: Crear cuenta nueva

1. Click en "Crear Cuenta"
2. Email: `miusuario@example.com`
3. Password: `password123` (y confirmación)
4. Click "Crear Cuenta"
5. ¡Ya puedes acceder! Click en "Ingresar"

### Opción 2: Usar cuentas de prueba

Si ya ejecutaste los seeders, hay 2 usuarios listos:
```
Email: test@example.com
Password: password123

Email: test2@example.com  
Password: password123
```

Simplemente inicia sesión con estos.

---

## ✨ Características disponibles

### Una vez autenticado:

✅ **Listar notas** - Ve todas tus notas
✅ **Crear notas** - Título, contenido, descripción
✅ **Buscar** - Con operadores AND/OR
✅ **Editar notas** - Modifica contenido
✅ **Eliminar notas** - Borra notas
✅ **Tags** - Organiza con etiquetas
✅ **Fecha de creación** - Timestamp automático

---

## 🔍 URL de cada sección

| Página | URL |
|--------|-----|
| Inicio | `http://localhost:8000/` |
| Login | `http://localhost:8000/login` |
| Registro | `http://localhost:8000/register` |
| Mis Notas | `http://localhost:8000/notes` |
| Nueva Nota | `http://localhost:8000/notes/create` |
| Ver Nota | `http://localhost:8000/notes/{id}` |
| Editar Nota | `http://localhost:8000/notes/{id}/edit` |

---

## 🧪 API REST (Postman/cURL)

Si prefieres probar la API directamente:

**Base URL:** `http://localhost:8000/api`

### Registrarse
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login (obtener token)
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

Copiar el `token` del response.

### Crear nota
```bash
curl -X POST http://localhost:8000/api/v1/notes \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token_aqui}" \
  -d '{
    "title": "Mi primera nota",
    "content": "Contenido de la nota",
    "description": "Resumen"
  }'
```

### Listar notas
```bash
curl http://localhost:8000/api/v1/notes
```

### Buscar notas
```bash
curl "http://localhost:8000/api/v1/notes/search?q=mi&op=AND"
```

---

## ⚙️ Configuración avanzada

### Si necesitas cambiar puertos

En `.env`:
```env
APP_URL=http://localhost:8001  # cambiar puerto
```

Luego:
```bash
php artisan serve --port=8001
```

### Si la BD está en otro servidor

En `.env`:
```env
DB_HOST=tu.servidor.com
DB_USERNAME=usuario
DB_PASSWORD=contraseña
```

---

## 🆘 Troubleshooting

### Error: "SQLSTATE[HY000]: General error: 1030"
Asegúrate de que MySQL está corriendo:
```bash
# Windows
net start MySQL80  # o el nombre de tu servicio

# Linux
sudo systemctl start mysql
```

### Error: "Class 'Firebase\JWT\JWT' not found"
Instala la dependencia:
```bash
composer require firebase/php-jwt
```

### Error: "APP_KEY not set"
Ejecuta:
```bash
php artisan key:generate
```

### Error: "PDOException: could not find driver"
Habilita PDO en PHP (asegúrate que php.ini tenga `extension=pdo_mysql`)

---

## 📊 Estructura de la BD

```sql
-- Tablas principales
users          -- usuarios (email, password_hash)
notes          -- notas (title, content, description, user_id)
tags           -- tags (name, user_id)
note_tag       -- relación M:M entre notas y tags
shared_links   -- comparticiones (token, access_level, owner_id, recipient_id)
```

---

## 🎉 ¡Todo listo!

Ya puedes:
1. ✅ Ver la app en el navegador
2. ✅ Crear cuenta y login
3. ✅ Crear/editar/eliminar notas
4. ✅ Buscar notas
5. ✅ Compartir notas

**¿Preguntas?** Revisa los logs en `storage/logs/laravel.log`

