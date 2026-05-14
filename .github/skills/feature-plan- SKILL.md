
Este skill debe residir en:
.github/skills/feature-plan/SKILL.md

# Feature Plan Generator Skill
Usa este skill cuando el usuario quiera convertir
los requirements funcionales de una feature
en un plan técnico de implementación.

## Objetivo
Transformar `requirements.md`
en `plan.md`
sin generar todavía código.

La ubicación debe resolverse desde:
- .github/structure.json
- base_path
- ai_directory
- ai_files

---

## Flujo obligatorio

### 1) Leer contexto
Lee siempre:
- .github/spec.md
- .github/copilot-instructions.md
- .github/structure.json
- requirements.md de la feature
- plan.md existente (si existe)

---

### 2) Diseñar arquitectura
Define:
- componentes principales
- responsabilidades
- boundaries
- acciones
- servicios
- eventos
- jobs
- policies
- recursos externos

Mantén consistencia con convenciones globales.

---

### 3) Diseñar persistencia
Solo si aplica, define:
- entidades
- relaciones
- cambios en tablas
- ownership de datos
- índices
- lifecycle

---

### 4) Diseñar testing strategy
Define:
- unit tests
- feature tests
- edge cases
- integration tests

---

### 5) Detectar riesgos
Marca:
- dudas técnicas
- dependencias externas
- trade-offs
- decisiones pendientes

---

### 6) Generar plan.md
Formato:

# Feature: <Feature>

## Arquitectura
...

## Flujo técnico
...

## Persistencia
...

## Testing
...

## Riesgos
...

## Decisiones abiertas
...

Debe ser incremental y no destructivo.

---

### 7) No generar tareas todavía
No crear todavía:
- tasks.md
- código
- migraciones
- tests