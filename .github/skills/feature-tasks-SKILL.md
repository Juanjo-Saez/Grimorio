# Task Generator Skill

Usa este skill cuando exista un `plan.md` validado
y el siguiente paso sea convertirlo en una lista
ordenada de tareas de implementación.

## Ubicación del skill
Este archivo debe guardarse en:

.github/skills/task-generator/SKILL.md

Esta ubicación deja claro que el skill pertenece
a la fase posterior al diseño técnico
y previa a la generación de código.

---

## Objetivo
Transformar un archivo `plan.md`
en un archivo `tasks.md`
con tareas atómicas, ordenadas y ejecutables.

El resultado debe servir para que otro agente
(o un desarrollador)
pueda implementar la feature paso a paso
sin necesidad de reinterpretar el plan.

---

## Entradas esperadas
- `plan.md`
- `.github/structure.json`
- contexto del repositorio
- stack detectado del proyecto
- convenciones existentes

---

## Salida esperada
Crear:

tasks.md

La ruta final debe resolverse usando:

- `base_path`
- `ai_directory`
- estructura definida en `.github/structure.json`

---

## Reglas de generación
Cada tarea debe:

1. Ser pequeña y ejecutable
2. Tener un único objetivo técnico
3. Poder validarse individualmente
4. Seguir dependencias lógicas
5. Estar ordenada por implementación real

---

## Formato obligatorio
El archivo `tasks.md` debe seguir este formato:

# Tasks — <feature_name>

## Context
Resumen breve del objetivo técnico.

## Task List

### 1. Preparar estructura base
- Crear archivos necesarios
- Registrar rutas o módulos
- Crear command/artifact inicial

### 2. Implementar lógica principal
- Servicios
- Casos de uso
- Integración con APIs
- Gestión de errores

### 3. UI / Integración frontend
- Componentes
- Estados
- Hooks
- Conexión con backend

### 4. Persistencia / datos
- Migraciones
- Modelos
- Repositorios
- Seeds si aplica

### 5. Testing
- Unit tests
- Integration tests
- Edge cases

### 6. Documentación
- README técnico
- ejemplos de uso
- decisiones relevantes

---

## Reglas de calidad
Las tareas NO deben describir código línea a línea.

Las tareas deben representar:
- bloques funcionales
- hitos técnicos
- validaciones
- dependencias

Cada tarea debe ser suficientemente pequeña
como para completarse en una sesión de trabajo.

---

## Dependencias
Si el `plan.md` define fases,
las tareas deben respetarlas.

Si detectas blockers,
crear una tarea previa explícita.

---

## Restricciones
No generar código.

No modificar archivos productivos.

Solo producir `tasks.md`.