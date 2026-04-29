---
name: scaffolding
description: Bootstrap inicial del proyecto Laravel a partir de spec.md, generando structure.json, el comando Artisan ai:scaffold y toda la estructura .ai por feature.
---

# 📌 Ubicación del skill

Este skill debe residir en:

.github/skills/scaffolding/SKILL.md

junto con los recursos asociados al bootstrap del proyecto.

---

# Scaffolding Bootstrap Skill

Usa esta skill cuando el usuario quiera preparar la estructura inicial del proyecto a partir de:
- .github/copilot-instructions.md
- .github/spec.md

El objetivo es dejar el proyecto listo para empezar desarrollo iterativo por features.

---

## Flujo obligatorio

### 1) Leer contexto global
Lee siempre:

- .github/copilot-instructions.md
- .github/spec.md

Extrae:
- lista de features funcionales
- contexto general del proyecto
- convenciones globales que afecten a nombres

No generes todavía carpetas técnicas como Controllers, Models o Requests.

---

### 2) Resolver ambigüedades
Si spec.md no define claramente las features:

- detente y pregunta al usuario
- espera confirmación

Normaliza nombres a PascalCase singular.

Ej:
- auth → Auth
- user profile → UserProfile
- invoices → Invoice

---

### 3) Generar structure.json
Crea o actualiza:

.github/structure.json

Schema:

{
  "base_path": "app/Features",
  "features": [
    "Auth",
    "Projects"
  ],
  "ai_directory": ".ai",
  "ai_files": [
    "requirements.md",
    "plan.md",
    "tasks.md",
    "decisions.md"
  ]
}

⚠️ Solo incluye:
- features
- .ai folder
- markdown files

---

### 4) Crear comando Artisan (OBLIGATORIO)

Ruta:
app/Console/Commands/AiScaffold.php

Comando:
php artisan ai:scaffold

Responsabilidad:
- leer .github/structure.json
- crear app/Features
- crear carpetas de cada feature
- crear .ai
- crear:
  - requirements.md
  - plan.md
  - tasks.md
  - decisions.md

Propiedades:
- idempotente
- no destructivo
- no sobrescribe sin comprobar
- usa filesystem Laravel

---

### 5) Ejecutar bootstrap

Ejecuta:

php artisan ai:scaffold

Solo pide intervención si falla.

---

### 6) Resultado final

Debe quedar:

- features creadas en app/Features
- cada feature con .ai
- 4 archivos markdown por feature

Mensaje final:

Scaffolding completado. Ya puedes trabajar por features usando los archivos .ai.
