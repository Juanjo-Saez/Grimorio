# 📌 Ubicación del skill

Este skill debe residir en:

`.github/skills/dev-journal/SKILL.md`

------------------------------------------------------------------------

# Development Journal Skill

Usa este skill de forma transversal cada vez que una generación de
código introduzca un cambio funcional, estructural o arquitectónico que
se considere válido dentro del estado actual del proyecto.

Su objetivo no es bloquear el flujo principal de desarrollo, sino
construir una **memoria cronológica global del proyecto** que
posteriormente sirva como base para redactar documentación técnica,
memorias de desarrollo, ADRs o incluso una tesis técnica del proceso
seguido.

------------------------------------------------------------------------

## 🎯 Objetivo

Mantener actualizado el archivo global:

`.github/journal.md`

con una narración **cronológica, descriptiva y corregible** de la
evolución del proyecto.

Debe comportarse como **un alumno que toma apuntes detallados mientras
desarrolla una tesis**:

-   qué se ha implementado
-   por qué se ha hecho así
-   qué problema resolvía
-   qué alternativas se descartaron
-   qué impacto tiene en el resto del sistema
-   qué refactors sustituyen decisiones previas

La redacción no necesita ser perfecta, pero sí **rica en contexto y útil
para reescritura posterior**.

------------------------------------------------------------------------

## 📚 Principios de escritura

Cada entrada debe escribirse con un tono explicativo y natural.

Evitar formato telegrama como:

-   "se crea controller"
-   "se mueve lógica"
-   "fix bug"

En su lugar, usar una narrativa breve pero completa:

> Se ha extraído la lógica de autenticación a un servicio dedicado para
> reducir la responsabilidad del controlador y facilitar futuras
> reutilizaciones desde endpoints API. Esta decisión también prepara el
> terreno para incorporar proveedores externos de autenticación sin
> modificar la capa HTTP.

Cada nota debe permitir que, semanas después, se entienda:

-   el contexto del cambio
-   la intención técnica
-   la motivación
-   las consecuencias

------------------------------------------------------------------------

## 🔄 Cuándo se ejecuta

Se lanza en paralelo siempre que:

-   se genere código nuevo
-   se modifique código existente
-   se cierre una iteración válida
-   se acepte un refactor
-   se revierta una decisión anterior
-   se cambie una convención técnica relevante

No forma parte del pipeline lineal:

`requirements → plan → tasks → código`

sino que acompaña al desarrollo como un sistema de journaling
transversal.

------------------------------------------------------------------------

## 🧠 Flujo obligatorio

### 1) Leer contexto global

Lee siempre:

-   `.github/spec.md`
-   `.github/copilot-instructions.md`
-   `.github/structure.json`
-   `.github/journal.md` existente (si existe)
-   `tasks.md` de la feature activa (si existe)

------------------------------------------------------------------------

### 2) Detectar el cambio real

Identifica:

-   feature afectada
-   tarea o subtarea relacionada
-   archivos modificados
-   intención funcional
-   motivo del cambio
-   impacto arquitectónico

No registrar pruebas fallidas ni intentos descartados que no hayan
formado parte del resultado aceptado.

------------------------------------------------------------------------

### 3) Narrar el cambio con contexto

Explica con algo de literatura técnica:

-   qué se hizo
-   por qué se decidió
-   qué problema previo resolvió
-   qué se espera mejorar con ello
-   cómo afecta a futuras iteraciones

Pensar siempre en utilidad posterior para memoria, documentación o
tesis.

------------------------------------------------------------------------

### 4) Gestionar correcciones y marcha atrás

Si una decisión previa queda obsoleta:

-   no eliminar la entrada anterior
-   añadir una nueva entrada que la corrija
-   explicar por qué se sustituye
-   indicar claramente el refactor o rollback

Esto mantiene una historia evolutiva real del proyecto.

------------------------------------------------------------------------

### 5) Actualizar `.github/journal.md`

Añadir una nueva entrada cronológica con este formato:

``` md
## 2026-04-12 18:30 — auth login

Durante esta iteración se ha reorganizado el flujo de autenticación
para separar la validación de entrada de la lógica de negocio.
Inicialmente la comprobación de credenciales residía en el controlador,
pero se ha considerado más mantenible moverla a un servicio dedicado.

El motivo principal de este cambio ha sido preparar la feature para
futuras extensiones, como autenticación por OAuth y login desde API.
Además, esta separación facilita el testing unitario y reduce el
acoplamiento con la capa HTTP.

Este cambio sustituye parcialmente una aproximación anterior basada
en middleware personalizado, descartada por añadir complejidad sin
beneficio claro en esta fase del proyecto.
```

------------------------------------------------------------------------

## ✍️ Reglas de calidad

Cada entrada debe permitir responder:

-   ¿qué se cambió?
-   ¿por qué?
-   ¿qué decisión reemplaza?
-   ¿qué mejora aporta?
-   ¿qué implicaciones tiene a futuro?

Si alguna de estas preguntas no queda respondida, la nota está
incompleta.

------------------------------------------------------------------------

## 🚫 Restricciones

No generar todavía:

-   documentación final
-   ADR formales
-   changelog release
-   README funcional
-   memoria final redactada

Este skill solo prepara **materia prima rica en contexto**.
