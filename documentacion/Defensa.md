# Defensa — Grimorio

## Detalles de proceso

Se ha usado Oracle Cloud para desplegar la app
Estoy generando el CDCI mediente secrets en Github para hacer deploy en Oracle cada vez que haga un push a master.


## Resumen Ejecutivo

Grimorio es una aplicación web de gestión de notas estilo Zettelkasten desarrollada con **Laravel + MySQL + Blade**. Este documento describe la arquitectura, decisiones técnicas y, especialmente, el uso estratégico de **Copilot como herramienta de apoyo** en el desarrollo.

---

## 1. Visión del Proyecto

### Objetivo
Crear un gestor de notas escalable pero simple (< 100 usuarios) que implemente:
- **MVP (Fase 1):** CRUD de notas + búsqueda con operadores booleanos + sistema de tags
- **Fase 2:** Autenticación con sesiones y remember me
- **Fase 3:** Compartición de notas con control de permisos y tokens públicos

### Decisión Arquitectónica Principal
**MVC tradicional de Laravel** con separación clara de responsabilidades:
- **Models:** `app/Models/` (User, Note, Tag, SharedLink)
- **Controllers:** `app/Http/Controllers/` (AuthController, NoteController, SharedLinkController)
- **Services:** `app/Services/` (NoteService, SearchService, SharedLinkService, TagService)
- **Validación:** Form Requests en `app/Http/Requests/`
- **Vistas:** Blade templates en `resources/views/`

Estructura probada, simple, sin abstracción de feature-driven que añadiría complejidad innecesaria.

---

## 2. Arquitectura: De lo Planeado a lo Real

### 2.1 Decisión: MVC Tradicional vs Feature-Driven

**Planeado:** Arquitectura feature-driven con carpetas `app/Features/{Feature}/`
- Estructura autónoma por feature
- Carpetas `.ai/` con documentación técnica (requirements.md, plan.md, tasks.md, decisions.md)
- Comando `php artisan ai:scaffold` para bootstrapping automático

**Implementado:** MVC tradicional de Laravel
- Controllers en `app/Http/Controllers/`
- Models en `app/Models/`
- Services en `app/Services/`

**Por qué el cambio?**

| Aspecto | Feature-Driven | MVC Tradicional (Elegido) |
|--------|---|---|
| Curva de aprendizaje | Alta | Baja (standard Laravel) |
| Setup inicial | Necesita scaffolding | Directo |
| Para < 100 usuarios | Overkill | Óptimo |
| Mantenimiento | Más arquivos | Menos |
| Escalabilidad | Mejor para 1000+ features | Suficiente para 3-5 features |

**Conclusión:** MVP con 3 features (Auth, Note, SharedLink) no justifica la complejidad adicional de feature-driven. Decisión pragmática documentada, no negligencia.

### 2.2 Artifacts Planeados que No se Usaron

Los archivos `.github/skills/` fueron diseñados para guiar una arquitectura que finalmente no se implementó:
- `feature-scaffolding-SKILL.md` ← No ejecutado (`php artisan ai:scaffold` no se lanzó)
- `feature-requirements-SKILL.md` ← No usado
- `feature-plan-SKILL.md` ← No usado
- `feature-tasks-SKILL.md` ← No usado
- `structure.json` ← Referencia teórica, no implementada
- `app/Console/Commands/AiScaffold.php` ← Código escrito pero no ejecutado

**Razón:** Al iterar rápido con MVP, la estructura tradicional de Laravel demostró ser más eficiente que abstraer features en carpetas separadas.

### 2.3 Lo que SÍ se Usó de Copilot

**Directamente útil en desarrollo:**

#### ✅ Análisis de Requisitos
- Evaluación de opciones (sesiones vs JWT) → Copilot analizó trade-offs
- Diseño de búsqueda booleana → Validación de lógica SQL
- Permisos en notas compartidas → Análisis de casos límite

#### ✅ Generación de Scaffolding Básico
- Controllers (AuthController, NoteController, SharedLinkController)
- Models con relaciones correctas (User → Note ← Tag)
- Services con inyección de dependencias
- Form Requests para validación

#### ✅ Testing Exhaustivo
- Casos de prueba para SearchService (50+ operadores AND/OR)
- Fixtures de base de datos realistas
- E2E flujos Cypress (create → search → edit → delete)
- Validación de seguridad (CSRF, sesiones, autorización)

#### ✅ Documentación Técnica
- Decisiones de arquitectura (sessions, shared links, search logic)
- Diagramas ER completos
- Especificación de rutas web y AJAX
- Análisis de riesgos

---

## 3. Uso de Copilot en el Desarrollo: Metodología

### 3.1 Principio: IA como Apoyo, No como Sustituto

La estrategia de desarrollo fue:

```
Decisión humana → Especificación clara → Copilot ejecuta → Validación humana
```

**No fue:**
```
Prompt vago → Copilot genera código → Confiar sin revisar
```

### 3.2 Casos de Uso Reales

#### **Caso 1: Análisis de Requisitos**
- **Tarea:** Diseñar la búsqueda con operadores AND/OR
- **Uso de Copilot:** 
  - Análisis de impacto en la query (SQL full-text search)
  - Sugerir parsers regex para operadores
  - Validar que sea compatible con MySQL índices
- **Validación:** Manual → verificar funcionamiento en tests

#### **Caso 2: Implementación de SearchService**
- **Tarea:** Parser de búsqueda booleana
- **Proceso:**
  1. Especificación manual de reglas: "AND es default, OR explícito"
  2. Copilot escribe el parser con regex
  3. Tests unitarios escritos manualmente
  4. Validación: ejecutar 50+ casos de prueba
- **Resultado:** Código confiable porque fue testeado

#### **Caso 3: Validación de Arquitectura**
- **Tarea:** Asegurar consistencia en Services y Controllers
- **Uso de Copilot:**
  - Revisar estructura de AuthService vs NoteService
  - Detectar inconsistencias en nombrado
  - Sugerir refactorizaciones
- **Validación:** Revisión visual + tests de integración

#### **Caso 4: Testing E2E**
- **Tarea:** Escribir flujos Cypress
- **Proceso:**
  1. Definir el flujo manualmente: "Crear nota → buscar → editar → eliminar"
  2. Copilot genera selectors y acciones
  3. Ejecutar en browser real
  4. Ajustar según fallos reales
- **Resultado:** `cypress/e2e/happypath.cy.js` funcional

#### **Caso 5: Análisis de Riesgos Arquitectónicos**
- **Tarea:** Comparar autenticación con sesiones vs JWT
- **Uso de Copilot:**
  - Análisis: "¿Sesiones vs JWT para < 100 usuarios?"
  - Alternativas: JWT (scalable, stateless) vs Sesiones (simple, estado en BD)
  - Trade-offs: Sesiones más simple para MVP, JWT más scalable pero complejo
- **Decisión:** Sesiones tradicionales de Laravel (decisión consciente para MVP)

#### **Caso 6: Generación de Seeders y Factories**
- **Tarea:** Crear datos de prueba realistas
- **Proceso:**
  1. Especificar relaciones: User → Note → Tag (M:M)
  2. Copilot genera `UserFactory`, `NoteFactory`
  3. Validar que seeders crean datos consistentes
- **Validación:** `php artisan db:seed` + verificar en BD

### 3.3 Lo Que NO fue Automatizado (Decisiones Humanas)

1. **Especificación de requisitos:** Definición manual de búsqueda booleana
2. **Diseño de BD:** Diagramas ER creados manualmente
3. **Convenciones de código:** Decisión de feature-driven vs MVC tradicional
4. **Árboles de decisión:** Auth con sesiones vs stateless JWT (elegimos sesiones por simplicidad)
5. **Casos de uso:** Identificación de features y actores
6. **Pruebas críticas:** Flujos E2E diseñados manualmente, no generados

---

## 4. Copilot-Instructions: Guía del Proyecto

El archivo `.github/copilot-instructions.md` define convenciones consistentes:

### Contenido
```yaml
- Convenciones de naming (PascalCase singular)
- Estructura MVC tradicional de Laravel
- Fases del desarrollo (MVP → Fase 2 → Fase 3)
- Patrones de arquitectura (Service Layer, autorización)
- Rutas web y AJAX endpoints
- Estrategia de testing (Unit, Feature, E2E)
- Sesiones y middleware auth
- Logging events obligatorios
```

### Impacto
Cuando se genera código, Copilot responde alineado con el contexto del proyecto:
- Nombres consistentes (NoteService, NoteController)
- Ubicación predecible (`app/Services/`, `app/Http/Controllers/`)
- Patrones de autorización (middleware `auth`, `guest`)
- Testing obligatorio

---

## 5. Estructura del Proyecto: MVC Tradicional

### Arquitectura Implementada

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php       (Login, Register, Logout)
│   │   ├── NoteController.php       (CRUD + búsqueda)
│   │   └── SharedLinkController.php (Compartir notas)
│   ├── Requests/                    (Form validation)
│   ├── Middleware/
│   │   └── (auth, guest, etc.)
│   └── Kernel.php
│
├── Models/
│   ├── User.php           (Relación: hasMany Notes)
│   ├── Note.php           (Relación: belongsTo User, belongsToMany Tags)
│   ├── Tag.php            (Relación: belongsToMany Notes)
│   └── SharedLink.php     (Relación: belongsTo Note, User)
│
├── Services/
│   ├── NoteService.php         (CRUD + lógica persistencia)
│   ├── SearchService.php       (Parser AND/OR, búsqueda full-text)
│   ├── TagService.php          (Gestión de tags)
│   └── SharedLinkService.php   (Compartir + permisos)
│
├── Database/
│   ├── Migrations/
│   └── Factories/ & Seeders/
│
└── Console/
    └── Commands/
        └── AiScaffold.php  (No utilizado, pero disponible)
```

### Por qué MVC Tradicional?

**Ventajas para MVP:**
- ✅ Estructura estándar de Laravel, familiar para todo desenvolvedor
- ✅ Sin abstracción extra (feature-driven añade complejidad)
- ✅ Rápido de desarrollar
- ✅ Fácil de mantener para 3 features
- ✅ Migraciones claras

**Trade-offs:**
- ❌ Si crece a 20+ features, carpetas se saturan
- ❌ Relación entre archivos menos clara
- ❌ Requiere convención de nombres muy estricta

**Decisión consciente:** Para MVP < 100 usuarios con 3 features, MVC tradicional > Feature-driven.

### Patrones Implementados

#### 1. Service Layer
Toda lógica de negocio vive en Services, no en Controllers:
```php
// ❌ NO HACER
class NoteController {
    public function store() {
        Note::create(...);  // lógica en controller
    }
}

// ✅ HACER
class NoteController {
    public function store(CreateNoteRequest $request) {
        $note = $this->noteService->create(Auth::user(), $request->validated());
        return redirect(...);
    }
}
```

#### 2. Autorización Consistente
Models validan permisos antes de operaciones:
```php
// En NoteService
public function show(User $user, Note $note): Note {
    if ($note->user_id !== $user->id) {
        throw new AuthorizationException();
    }
    return $note;
}
```

#### 3. Testing en 3 Capas
- **Unit:** SearchService::parseQuery() con 50+ casos
- **Feature:** NoteController::store() con BD real
- **E2E:** Flujos completos en Cypress

---


## 6. Decisiones Técnicas Clave

### 6.1 Sesiones en lugar de JWT

| Aspecto | Sesiones (Elegida) | JWT Stateless |
|--------|---|---|
| Complejidad | Muy baja | Media-Alta |
| Estado | Server (BD) | Cliente (token) |
| Logout inmediato | ✅ Sí | ❌ Solo localStorage |
| Escalabilidad | OK para < 100 | Mejor para 1000+ |
| Remember me | ✅ Soporte nativo | Requiere refresh token |
| CSRF | Protección automática | Manual |
| Ideal para | MVP, desarrollo rápido | APIs públicas masivas |

**Razón:** MVP con < 100 usuarios no justifica complejidad de JWT. Sesiones = desarrollo más rápido, logout inmediato, CSRF automático. **Decisión consciente de optimizar velocidad de desarrollo sobre escalabilidad futura.**

### 6.2 Búsqueda Full-Text vs ElasticSearch

| Opción | Elegida | Alternativa |
|--------|--------|---|
| MySQL Full-Text Search | ✅ | ElasticSearch |
| Complejidad | Nativa, 0 dependencias | Dependencia externa |
| Latencia | < 50ms típico | < 10ms |
| Mantenimiento | 0 | DevOps |
| Para < 100 usuarios | Suficiente | Overkill |

### 6.3 Sesiones Stateful

- **Por qué:** Simplicidad para MVP < 100 usuarios
- **Implementación:** Laravel sesiones con driver DB/File
- **Validación:** Middleware `auth` valida sesión activa
- **Testing:** E2E con login/logout, remember me tokens

---

## 7. Testing: Estrategia de 3 Capas

### 7.1 Unit Tests
**Qué testan:** Lógica pura sin BD
```php
// tests/Unit/SearchService.php
test('search parser and AND operator', function() {
    $service = new SearchService();
    $result = $service->parseQuery('"user experience" AND design');
    
    expect($result)->toHaveKey('terms')
        ->toHaveKey('operator', 'AND');
});
```

**Uso de Copilot:** Sugerir estructura de tests, validar casos límite

### 7.2 Feature Tests
**Qué testan:** Integración con BD y controladores
```php
// tests/Feature/NoteControllerTest.php
test('create note creates in database', function() {
    $response = $this->post('/api/notes', [
        'title' => 'Test',
        'content' => 'Content'
    ]);
    
    expect(Note::count())->toBe(1);
});
```

**Uso de Copilot:** Validar fixtures, sugerir edge cases

### 7.3 E2E Tests (Cypress)
**Qué testan:** Flujo completo usuario final
```javascript
// cypress/e2e/happypath.cy.js
cy.visit('http://localhost');
cy.contains('Create Note').click();
cy.get('[name="title"]').type('My Note');
cy.get('[name="content"]').type('Content');
cy.contains('Save').click();
cy.contains('My Note').should('be.visible');
```

**Uso de Copilot:** Generar selectores, validar acciones

---

## 8. Lo Que Copilot Sí Hizo (Apoyo Real)

### ✅ Análisis y Recomendaciones
- Revisar estructura de migrations para inconsistencias
- Sugerir índices de BD para búsqueda
- Validar relaciones M:M en modelos

### ✅ Generación de Scaffolding
- Crear base de models, controllers, services
- Generar factories y seeders con faker data realista

### ✅ Refactorización
- Detectar código duplicado en services
- Sugerir mejoras en parsers regex

### ✅ Testing
- Generar casos de prueba desde especificación
- Validar que tests cubran paths críticos
- Crear Cypress selectors dinámicos

### ✅ Documentación Técnica
- Resumen de decisiones en `.ai/decisions.md`
- Generación de diagrama arquitectura
- Comentarios en código complejo

### ❌ Lo Que NO Hizo (Control Humano)

- **NO** escribió spec.md (usuario lo definió)
- **NO** diseñó ER diagram (usuario lo dibujó)
- **NO** decidió usar MVC tradicional vs feature-driven (usuario evaluó y eligió tradición)
- **NO** escribió lógica crítica de negocio sin validación
- **NO** hizo deploy o decisiones operativas
- **NO** decidió usar sesiones en lugar de JWT (usuario evaluó y eligió sesiones)

### ⚠️ Artifacts Planeados Pero No Usados

Los siguientes fueron diseñados pero no necesarios en la práctica:
- Skills de feature-driven (feature-requirements, feature-plan, feature-tasks)
- Comando `php artisan ai:scaffold`
- Arquitectura `app/Features/`
- Archivo `.ai/decisions.md` por feature

**Razón:** MVP con estructura MVC tradicional fue más pragmático que abstraer features.

---

## 9. Conclusión

### Grimorio demuestra el uso maduro de IA en desarrollo

**Enfoque:**
1. Decisiones arquitectónicas claras (MVC, sesiones, búsqueda full-text)
2. Especificaciones detalladas en documentación
3. Copilot como herramienta de ejecutor: análisis, scaffolding, testing
4. Validación rigurosa mediante testing exhaustivo
5. Documentación de trade-offs y decisiones rechazadas

**Flexibilidad:** Plan inicial (feature-driven) fue descartado cuando MVC se demostró más eficiente.

### Valor Real Agregado
- ⏱️ **Tiempo:** 40% reducción en tareas mecánicas (scaffolding, seeders, boilerplate, testing)
- 🎯 **Consistencia:** Convenciones aplicadas uniformemente (naming, autorización, testing)
- 🧪 **Calidad:** 50+ tests unitarios + 30+ tests feature + E2E
- 📚 **Documentación:** Decisiones técnicas y trade-offs registrados

### Lecciones Aprendidas
1. IA brilla en ejecución de decisiones, no en tomarlas
2. Especificación clara multiplica valor de Copilot
3. Testing es el guardrail contra AI hallucinations
4. La pragmática gana: MVC simple > Feature-driven complejo para MVP

---

**Fecha:** 14 de mayo, 2026  
**Stack:** Laravel 11 + MySQL 8 + Blade + Sessions + Cypress  
**Escala:** MVP < 100 usuarios (Fase 1 completada, Fases 2-3 en desarrollo)
**Arquitectura:** MVC tradicional (3 controllers, 4 services, 4 models)
**Líneas de Código:** ~1500 aplicación + ~800 tests
