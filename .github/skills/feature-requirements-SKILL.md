# 📌 Ubicación del skill

Este skill debe residir en:
`.github/skills/feature-requirements/SKILL.md`

# Feature Requirements Generator Skill

Usa este skill cuando el usuario quiera generar o refinar el archivo
`requirements.md` de una feature concreta.

## Objetivo

Transformar la especificación global del proyecto (`.github/spec.md`) en
una especificación funcional enfocada a una única feature, sin asumir
una estructura física fija del proyecto.

La ubicación del archivo debe resolverse dinámicamente usando: -
`.github/structure.json` - `base_path` - `ai_directory` - `ai_files`

------------------------------------------------------------------------

## Flujo obligatorio

### 1) Leer contexto global

Lee siempre: - `.github/spec.md` - `.github/copilot-instructions.md` -
`.github/structure.json`

Si ya existe el archivo de la feature, léelo también.

Extrae: - objetivos globales del producto - reglas de negocio
relacionadas - lenguaje ubicuo del dominio - restricciones relevantes -
dependencias con otras features

------------------------------------------------------------------------

### 2) Resolver ubicación de la feature

Usa `.github/structure.json` para localizar la feature.

Debes resolver: - carpeta base de features - carpeta `.ai` - archivo
`requirements.md`

No asumas paths hardcodeados.

El skill debe funcionar aunque la base sea, por ejemplo: -
`app/Features` - `src/Domains` - `modules`

------------------------------------------------------------------------

### 3) Aislar la feature

Trabaja solo sobre la feature indicada por el usuario.

Debes identificar: - propósito funcional - actores - entradas -
salidas - validaciones - estados - errores - side effects -
integraciones - dependencias

Ignora contenido global no relacionado.

------------------------------------------------------------------------

### 4) Detectar huecos

Si la spec global no permite deducir requisitos claros: - detente -
formula preguntas concretas - espera respuesta

Haz preguntas orientadas a: - comportamiento esperado - edge cases -
ownership de datos - permisos - lifecycle - restricciones UX/API

------------------------------------------------------------------------

### 5) Generar requirements.md

Crea o actualiza el `requirements.md` de la feature resuelta desde
`structure.json`.

Formato recomendado:

``` md
# Feature: <Feature>

## Objetivo
...

## Actores
...

## Reglas de negocio
...

## Entradas
...

## Salidas
...

## Casos límite
...

## Dependencias
...

## Dudas abiertas
...
```

Debe ser: - incremental - no destructivo - compatible con re-ejecución -
sin borrar contexto existente

------------------------------------------------------------------------

### 6) No avanzar a diseño técnico

No generes todavía: - controllers - actions - DTOs - endpoints -
migrations - tests

Este archivo es exclusivamente funcional.

------------------------------------------------------------------------

### 7) Resultado final

Mensaje final:

> Requirements de `<Feature>` refinados y listos para planificación.
