# Software Design Document (SDD) - Grimorio

## 1. Overview

**Project Name:** Grimorio

**Goal:** App de notas

**Description:**
Grimorio es una aplicación web para la gestión de notas, inspirada en el método Zettelkasten. Permite crear, organizar, enlazar y compartir notas entre usuarios, con control de permisos y búsqueda eficiente.

---

## 2. Architecture

- **Pattern:** MVC (Laravel)
- **Feature-based folders:** Separación de features por carpetas (si Laravel lo permite)
- **API:** API RESTful + Blade minimal (sin lógica de negocio en vistas)
- **SDD:** Uso de IA para automatización y recomendaciones (tests, arquitectura, etc.)

---

## 3. Tech Stack

- **Backend:** Laravel (última versión)
- **Database:** MySQL
- **Frontend:** Blade templates + Vanilla JS (sin SPA)
- **Testing:** Unitarios, integración, e2e
- **Auth:** JWT (stateless) - tokens con expiración corta, sin gestión de revoke (escala pequeña)
- **Deployment:** Local y servicios cloud (Cloudflare)
- **Scale:** < 100 usuarios (no requiere optimizaciones de scale)

---

## 4. Main Features

### MVP (Fase 1)
- Crear notas (titulo, contenido, descripción)
- Ver/Leer notas propias
- Editar notas propias
- Eliminar notas propias
- Búsqueda por texto completo (AND/OR operators)
- Búsqueda por tags
- Sistema de tags: crear, asignar, filtrar

### Fase 2
- Login y registro con JWT
- Autenticación estateless (sin sesiones)

### Fase 3
- Compartir notas individuales con otros usuarios via URL privada
- Permisos: lectura o lectura+edición
- Invitaciones (email o link)

---

## 5. Entities

- **Usuario**: id, email, password_hash, created_at
- **Nota**: id, user_id (propietario), titulo, contenido, descripcion, created_at, updated_at
- **Tag**: id, nombre, user_id (propietario del tag), created_at
- **NoteTag** (pivot): note_id, tag_id (relación M:M)
- **SharedLink**: id, note_id, recipient_user_id, access_level (read/edit), token_unique, created_at (Fase 3)

---

## 6. Permissions (Fase 2+)

- **Propietario de nota:** crear, editar, eliminar, leer (siempre autenticado)
- **Usuario invitado (Fase 3):** leer (si access_level=read) o leer+editar (si access_level=edit)
- **Requisito:** Ambos actores deben estar autenticados con JWT
- **Validación:** Autorizar por user_id en header Authorization

---

## 7. Search (MVP)

- **Búsqueda por texto:** contra titulo + contenido
- **Operadores booleanos:** AND (default), OR
- **Búsqueda por tags:** filtro por uno o varios tags (AND logic)
- **Combinación:** texto AND/OR tags simultaneamente
- **Scope:** Solo notas del usuario propietario (MVP) o con acceso (Fase 3)

---

## 8. Non-Functional Requirements

- **Users:** < 100 usuarios (sin optimizaciones de scale)
- **Response time:** < 500ms búsquedas típicas (MySQL full-text search con índices)
- **Availability:** Local + Cloudflare CDN
- **Database:** MySQL, índices en user_id, tag_id, note_id
- **Storage:** Ilimitado en MVP (no hay límite de notas/usuario)
- **Concurrency:** Baja (< 10 usuarios simultáneos esperados)

---

## 9. External Integrations

- No se requieren integraciones externas

---

## 10. Logging

- Registrar todos los eventos relevantes (creación, edición, login, errores, etc.)

---

## 11. Testing Strategy

- **Unitarios (PHPUnit):** Lógica de negocio (búsqueda, validaciones, permisos)
- **Feature tests (Pest/PHPUnit):** Integración controller + model (CRUD notas, filtros, búsqueda)
- **E2E (Cypress):** Flujos completos user (crear nota → buscar → abrir)
- **Timing:** Tests DESPUÉS de implementar features (TDD light)
- **IA:** Generar templates de tests basados en plan.md

---

## 12. SDD & IA

- Incluir recomendaciones de IA para generación de tests, sugerencias de arquitectura y automatización de tareas repetitivas

---

## 13. Deployment

- Despliegue local y en servicios cloud (Cloudflare)

---

## 14. Decisiones de Diseño

### JWT sin revoke
**Razón:** Escala pequeña (<100 usuarios), tokens con expiración corta (15min) suficiente. Evita tabla de blacklist.

### Búsqueda con AND/OR
**Razón:** Proporciona power-user experience sin complejidad de query builder
**Implementación:** Parser simple de operadores en backend

### Tags propios por usuario
**Razón:** Cada usuario gestiona su propio vocabulario de categorías
**Nota:** Puede evolucionar a tags globales compartidos en futuras fases

### Fase 1: Solo CRUD + búsqueda
**Razón:** MVP mínimo viable para validar modelo Zettelkasten
**Beneficio:** Desacoplamiento de autenticación y compartición

## 15. Open Questions

- ¿Interfaz Blade mínima (formularios simples) o UI más elaborada?
- ¿Soporte para adjuntos en notas (Fase 3+)?
- ¿Historial de versiones de notas (nice-to-have)?

---

> _Este documento es un punto de partida y debe evolucionar junto al proyecto._
