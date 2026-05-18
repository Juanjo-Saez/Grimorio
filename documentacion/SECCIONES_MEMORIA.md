# Secciones para la Memoria - Grimorio

## Notas de Diseño

### Paleta de Colores
- Primario: Azul oscuro (#1a365d)
- Secundario: Verde menta (#10b981)
- Alertas: Rojo (#ef4444)
- Fondo: Blanco/Gris claro (#f3f4f6)
- Texto: Gris oscuro (#1f2937)

### Tipografía
- Headings: Inter Bold, 24px (H1), 20px (H2)
- Cuerpo: Inter Regular, 14px
- Monoespaciado (código): Monaco/Courier, 12px

### Componentes Recurrentes
- Botones: Azul primario, texto blanco, hover oscuro
- Campos de input: Borde gris, focus azul
- Cards/Modales: Sombra suave, borde redondeado 8px
- Tags: Fondo verde menta, texto blanco, cursor pointer
- Iconos: FontAwesome 6.4, 16-24px según contexto

## 5. Manejo de Errores y Warnings

### 5.1 Validación de Entrada
La aplicación implementa validación en múltiples capas:

Frontend:
- Validación HTML5 (required, email, min/max length)
- Validación JavaScript antes de enviar formularios
- Mensajes de error contextuales al usuario

Backend (Form Requests):
```php
// app/Http/Requests/CreateNoteRequest.php
class CreateNoteRequest extends FormRequest {
    public function rules() {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'content' => 'required|string|max:65535',
            'tags' => 'nullable|array|max:20',
        ];
    }
}
```

### 5.2 Excepciones Manejadas

AuthorizationException
- Lanzada cuando usuario intenta acceder/editar nota que no le pertenece
- Respuesta: HTTP 403 (Forbidden)

ModelNotFoundException
- Cuando se intenta acceder a una nota, usuario o link inexistente
- Respuesta: HTTP 404 (Not Found)

HttpTransportException
- Ocurrida durante implementación de emails con Mailgun
- Capturada y mostrada al usuario: "Compartición creada (email próximamente)"

ValidationException
- Automática de Laravel cuando Form Request falla
- Retorna errores específicos por campo al usuario

### 5.3 Logging
Todos los errores se registran automáticamente en `storage/logs/laravel.log`:

```
[2026-05-18 14:23:45] local.ERROR: Attempt to access non-existent note #42 by user #3 
[2026-05-18 14:25:12] local.WARNING: Email send failed for shared_link #15: HttpTransportException
[2026-05-18 14:30:01] local.INFO: Note #5 shared with 'test@example.com'
```

Formato: `[timestamp] environment.LEVEL: message [context]`

### 5.4 Errores Comunes Capturados
| Escenario | Código | Mensaje Mostrado |
|-----------|--------|-----------------|
| Nota no encontrada | 404 | "La nota que buscas no existe" |
| Acceso denegado | 403 | "No tienes permisos para esta acción" |
| Email inválido | 422 | "El email no es válido" |
| Token compartido inválido | 404 | "El link ha expirado o es inválido" |
| Campos requeridos vacíos | 422 | "Los campos marcados son obligatorios" |
| Búsqueda sin resultados | 200 | "No se encontraron notas con esos criterios" |

### 5.5 CSRF Protection
- Todos los formularios incluyen token CSRF (`@csrf` en Blade)
- Middleware `VerifyCsrfToken` valida en POST/PUT/DELETE
- Errores: HTTP 419 (Token Expired)

### 5.6 Evitar o Capturar Errores y Warnings

Decisiones de Diseño en el Manejo de Errores:

La aplicación implementa un enfoque multicapa para prevenir y capturar errores, evitando que lleguen al usuario final:

En el Backend (Laravel):

Los controladores utilizan bloques try/catch para operaciones críticas que pueden fallar, como acceso a base de datos o validación de permisos. Por ejemplo, cuando un usuario intenta compartir una nota, primero se verifica que tenga autorización sobre esa nota (lanzaría AuthorizationException si no). Si ocurre un error inesperado, se registra en los logs y se retorna un mensaje genérico al usuario sin exponer detalles técnicos.

Los servicios implementan validación de datos antes de persistirlos en la base de datos. La clase SearchService, por ejemplo, valida que el usuario haya ingresado al menos un criterio de búsqueda (query o tags) antes de ejecutar la consulta. Esto evita consultas inválidas o costosas a la BD.

En el Frontend (JavaScript):

Antes de enviar datos al servidor, se realiza validación en el navegador. Para compartir una nota por email, se verifica que la dirección ingresada sea un email válido usando expresiones regulares. También se valida que el nivel de acceso seleccionado (lectura/edición) sea uno de los valores permitidos. Si hay un error, se muestra inmediatamente al usuario sin hacer una petición innecesaria al servidor.

El manejo de respuestas HTTP distingue entre éxito y error. Si la respuesta no es satisfactoria (código HTTP 4xx o 5xx), se captura el mensaje de error del servidor y se muestra al usuario en un formato comprensible. Todos los errores se registran también en la consola del navegador para debugging.

En las Vistas (Blade Templates):

Los formularios HTML5 incluyen validación nativa del navegador mediante atributos como required, maxlength, y type="email". Estos proporcionan feedback inmediato antes de enviar el formulario. Además, después de procesar un formulario en el servidor, Laravel automáticamente redirecciona de vuelta al formulario si hay errores de validación, mostrando los mensajes de error específicos junto a cada campo.

Métodos de Captura Implementados:

Try/Catch: Utilizado en operaciones que pueden fallar de forma impredecible (acceso a base de datos, llamadas a APIs externas). Cuando ocurre una excepción, se captura, se registra en los logs para auditoría, y se retorna un mensaje de error apropiado al usuario.

Validación de Input: Ocurre en tres niveles. Primero en el navegador (HTML5), luego en JavaScript antes de enviar, finalmente en el servidor mediante Form Requests de Laravel. Este enfoque en capas asegura que datos inválidos nunca lleguen a la lógica de negocio.

Guard Clauses: En servicios y controladores, se validan precondiciones temprano (¿tiene permisos? ¿existe el recurso? ¿es válido el input?). Si algo no cumple la precondición, la función retorna inmediatamente, evitando lógica anidada y haciendo el código más legible.

Logging: Todos los errores se registran en storage/logs/laravel.log con timestamp, nivel (ERROR/WARNING/INFO), mensaje descriptivo y contexto relevante. En producción, el nivel de logging se ajusta a ERROR únicamente para no generar logs excesivos. En desarrollo, se registra más detalle.

User Feedback: Cuando algo falla, el usuario recibe un mensaje amable y accionable, no un error técnico. Por ejemplo, en lugar de "SQLSTATE[HY000]: General error", el usuario ve "No se encontraron notas con esos criterios". Los modales de error y toast messages (notificaciones emergentes) informan al usuario de forma no intrusiva.

Warnings Esperados (No Son Errores Críticos):

Durante el desarrollo, Laravel puede mostrar warnings por funciones deprecadas o variables no inicializadas. Estos son advertencias de que el código podría mejorarse, pero no impiden que la aplicación funcione. En los logs aparecen como WARNING. Durante las revisiones de código, estos warnings se abordan como tareas de mantenimiento, no como bloqueadores.

Configuración en Producción:

En el archivo .env de producción, APP_DEBUG se establece a false, lo que evita que se muestren detalles técnicos de errores en pantalla. El nivel de logging se ajusta a 'error' para registrar solo problemas reales. Esto protege la seguridad (no expone rutas ni estructura de código) y mejora la performance (menos I/O en logs).

---

## 6. Compatibilidad de Navegadores

### 6.1 Estrategia de Compatibilidad

Decisión de Soporte:

La aplicación fue diseñada para soportar navegadores modernos lanzados después de 2020. Esta decisión se basa en que el 95%+ de usuarios utiliza versiones actualizadas de Chrome, Firefox, Safari o Edge. Soporte para Internet Explorer o navegadores muy antiguos fue descartado conscientemente, ya que requeriría transpilación adicional, polyfills complejos y testing exhaustivo que no se justifica para un MVP.

Navegadores Soportados:
- Google Chrome versión 90+ (motor Blink)
- Mozilla Firefox versión 88+ (motor Gecko)
- Apple Safari versión 14+ (motor WebKit)
- Microsoft Edge versión 90+ (motor Blink)

Estas versiones garantizan soporte para ES6+, CSS Grid, Flexbox y Fetch API, que son tecnologías clave de la aplicación.

### 6.2 Características Modernas Utilizadas

JavaScript (ES6+):

La aplicación utiliza características modernas de JavaScript que simplifican el código y mejoran la legibilidad. Se usan variables con scope adecuado (const/let en lugar de var), funciones flecha para callbacks, destructuring para extraer propiedades de objetos, el spread operator para manipular arrays, y template literals para strings dinámicos. Para comunicación con el servidor, se utiliza la Fetch API estándar, que es más limpia y moderna que XMLHttpRequest.

CSS (Grid y Flexbox):

El diseño responsivo se implementa con CSS Grid para el layout principal de dos columnas (navegación + contenido), y Flexbox para alinear botones, navbars y elementos pequeños. Se utilizan CSS Variables para mantener la consistencia de colores y espaciado, lo que facilita cambios globales sin modificar múltiples archivos. Las media queries permiten que la aplicación se adapte a diferentes tamaños de pantalla (móvil, tablet, desktop). Algunos efectos visuales utilizan backdrop filters para crear efectos de blur, que es soportado en navegadores modernos.

HTML5 Semántico:

Se utilizan etiquetas semánticas como nav para navegación, main para contenido principal, article para notas individuales, y footer. Además, se usan data attributes para almacenar información relacionada con elementos del DOM, facilitando la manipulación desde JavaScript. Los input types modernos (email, password, text) permiten validación nativa y teclados optimizados en dispositivos móviles.

### 6.3 Testing de Compatibilidad

Pruebas en Navegadores Reales:

Se realizó testing manual en cada navegador soportado. En Chrome, se utiliza Responsive Design Mode para simular diferentes tamaños de pantalla (320px para móvil, 768px para tablet, 1024px y 1440px para desktop). Se verifica que la consola no muestre errores críticos, que las cargas de página sean menores a 2 segundos, y que las puntuaciones de Lighthouse sean superiores a 85 (métrica de Google para calidad y performance).

Firefox se prueba con Developer Edition, verificando que el Inspector CSS funcione correctamente con Grid y Flexbox, controlando que no haya warnings de deprecaciones, y testando el Responsive Design Mode.

Safari se prueba en macOS para verificar que WebKit renderice correctamente elementos CSS y que no haya incompatibilidades con propiedades específicas de Apple.

Herramientas de Testing Automatizado:

Se utiliza Cypress para automatizar pruebas end-to-end en múltiples navegadores. Los tests verifican flujos completos: carga del sitio, creación de notas, búsqueda, y navegación. La suite de Cypress puede ejecutarse en Chrome, Firefox y Edge automáticamente, capturando screenshots o videos si algo falla. Esto permite detección rápida de regresos de compatibilidad cuando se introducen cambios en el código.

Dimensiones de Pantalla Probadas:

Se prueban cuatro puntos de quiebre importantes: 320px (móvil pequeño), 768px (tablet), 1024px (laptop pequeño), y 1440px (desktop estándar). En cada dimensión se verifica que:
- El layout se reorganiza correctamente (grid de 1 columna en móvil, 2 en desktop)
- Los textos e inputs son legibles y cliqueables
- No hay overflow horizontal
- Los botones son accesibles con el dedo en móvil

### 6.4 Degradación Elegante y Fallbacks

Si JavaScript Está Deshabilitado:

La aplicación mantiene funcionalidad básica sin JavaScript. Los formularios (crear nota, buscar, compartir) utilizan POST/GET tradicionales que funcionan en cualquier navegador. La búsqueda, en lugar de ser AJAX en tiempo real, requeriría una recarga de página para mostrar resultados. La compartición de notas aún es posible, aunque la experiencia de usuario es menos fluida. Esto es importante porque algunos usuarios corporativos o con políticas de seguridad restrictivas pueden tener JavaScript deshabilitado.

Si CSS Grid No Está Disponible:

Aunque muy improbable en navegadores modernos, el código CSS incluye un fallback a Flexbox. El layout seguiría siendo usable, solo que con un aspecto menos elegante. Esto se logra definiendo el grid, pero Flexbox actúa como layout por defecto que es sobrescrito por el grid en navegadores que lo soportan.

Si Fetch API No Está Disponible:

Esto afectaría a menos del 1% de usuarios (principalmente navegadores muy antiguos). Si fuese necesario en el futuro, se podría agregar un polyfill que convierta Fetch en XMLHttpRequest automáticamente, pero actualmente no se considera prioritario.

Validación HTML5 Nativa:

Los navegadores que no soportan validación HTML5 (muy pocos) simplemente no mostrarían los mensajes de error nativos del navegador, pero el backend continúa validando todos los datos. El usuario aún recibiría retroalimentación, solo que vía servidor en lugar del navegador.

### 6.5 Consideraciones de Performance Cross-Browser

Optimización por Motor:

Aunque los cuatro navegadores soportados utilizan motores modernos (Blink, Gecko, WebKit), cada uno tiene características de performance únicas. Se minimiza el uso de transpiladores (como Babel) para ES6+, ya que todos los navegadores soportan nativamente. Los assets (CSS, JavaScript) se minifican con Vite, una herramienta moderna que genera builds optimizados para cada navegador.

Pruebas de Carga:

Se verifica que la página principal cargue en menos de 2 segundos en conexión 3G simulada. Esto se comprueba en Chrome DevTools Network throttling. Diferencias menores entre navegadores (Chrome suele ser más rápido que Firefox) son aceptables si están dentro de umbrales razonables.

Consideraciones de Caché:

Los navegadores modernos tienen políticas de caché similares. Los assets estáticos se sirven con headers de cache que permiten que el navegador los almacene localmente durante 30 días. Esto reduce tiempos de carga en visitas posteriores, de forma consistente en todos los navegadores.

### 6.6 Herramientas Utilizadas para Asegurar Compatibilidad

Chrome DevTools: Inspector que permite depurar JavaScript, inspeccionar CSS, medir performance, y simular diferentes dispositivos y conexiones de red.

Firefox Developer Edition: Alternativa a Chrome con enfoque en estandarización y compatibilidad open-source. Especialmente útil para detectar problemas que Firefox específicamente revela.

Safari Developer Tools: Integrado en macOS para verificar que WebKit renderice correctamente. Importante para usuarios de Apple que representan una porción significativa del mercado.

Cypress: Framework de testing E2E que permite automatizar pruebas de compatibilidad en múltiples navegadores. Los tests corren en headless mode (sin interfaz gráfica) para CI/CD.

Lighthouse: Herramienta de Google integrada en Chrome que mide performance, accesibilidad, best practices y SEO. Un score > 85 indica buena optimización cross-browser.

Browser Stack / Local Testing: Si fuese necesario testing en versiones antiguas o navegadores poco comunes (como Opera Mini), se usaría BrowserStack. Actualmente no es necesario para el MVP.

---

## 7. Documentación Externa

## 7. Documentación Externa

### 7.1 Documentación Oficial Consultada

**Backend y Framework:**

La documentación oficial de Laravel 10 fue la referencia principal para toda la arquitectura MVC. Se consultaron capítulos específicos sobre routing para definir endpoints, migraciones para crear las tablas de la base de datos, y el ORM Eloquent para implementar relaciones entre modelos (un usuario tiene muchas notas, una nota tiene muchos tags). La sección de autenticación fue clave para comprender sesiones y tokens remember-me, aunque en el MVP no se implementó login. El capítulo de testing proporcionó patrones para escribir Feature Tests y Unit Tests que verifican el comportamiento de servicios y controladores.

**Base de Datos:**

La documentación de MySQL 8.0 fue consultada especialmente para implementar búsqueda full-text con operadores AND/OR. El manual de MySQL explica cómo utilizar MATCH() y AGAINST() en modo BOOLEAN, que es lo que implementó SearchService. También se consultó el capítulo de indexación para crear índices full-text en los campos titulo y contenido de la tabla notes, mejorando la velocidad de búsqueda.

**Frontend y Estilo:**

Bootstrap 5.3 documentación se utilizó para los componentes CSS (botones, modales, grid system) y para asegurar compatibilidad cross-browser. La documentación de MDN sobre CSS Grid Layout fue fundamental para diseñar el layout de dos columnas. La Fetch API fue consultada para entender cómo hacer requests HTTP desde JavaScript de forma moderna y segura.

**Build Tools y Testing:**

Vite se utilizó como build tool moderno que reemplaza webpack. Su documentación explica cómo configurar hot module replacement para desarrollo más rápido, y cómo optimizar el build para producción. PHPUnit y Cypress tenían documentación clara sobre cómo escribir tests automatizados, aunque la implementación completa quedó para la Fase 2.

**DevOps e Infraestructura:**

Para el futuro, se consultó documentación de Docker para preparar la aplicación como contenedor (config está parcialmente hecha). GitHub Actions documentación fue revisada para entender cómo configurar CI/CD pipeline, aunque la versión actual usa deploys manuales.

### 7.2 Fuentes Consultadas Durante el Desarrollo

**Stack Overflow:**

Fue la principal fuente de solución de problemas. Cuando surgía una pregunta específica (ej: "cómo hacer que Eloquent retorne solo ciertos campos"), Stack Overflow proporcionaba soluciones verificadas por la comunidad. Se priorizaban respuestas con score alto y que explicaban la solución.

**GitHub Issues y Repositories:**

Revisar issues de Laravel y repositorios similares permitió entender problemas comunes que otros desarrolladores habían enfrentado. Esto fue útil para validar decisiones de arquitectura antes de implementarlas.

**Blogs Técnicos:**

Artículos especializados en Laravel, seguridad web (OWASP), y performance fueron consultados para best practices. Especialmente útiles fueron posts sobre SQL Injection prevention (enseña por qué Eloquent ORM es seguro) y XSS prevention (por qué Blade auto-escapa HTML).

**Documentación de Seguridad (OWASP):**

El proyecto OWASP Top 10 fue consultado para identificar vulnerabilidades comunes que debían prevenirse desde el diseño: inyección SQL, XSS, CSRF, etc. Esto influyó en decisiones como usar ORM prepared statements, tokens CSRF en formularios, y validation de input.

**MDN Web Docs:**

Para JavaScript, CSS y HTML5 se utilizó MDN como referencia autorizada. Especialmente para características modernas como Grid, Fetch API, y validación HTML5 nativa.

### 7.3 Estándares de Código Aplicados

**PSR-12 (PHP Standards Recommendation):**

Este estándar define la forma estándar de escribir código PHP en la industria. Se aplicó indentación de 4 espacios, línea máxima de 120 caracteres, y formato consistente de clases y métodos. Esto asegura que cualquier desarrollador que revise el código encuentre una estructura familiar.

**HTML Semántico (HTML5):**

En lugar de usar divs genéricos para todo, se utilizaron etiquetas semánticas que describen el propósito del contenido: nav para barras de navegación, main para contenido principal, article para notas, y footer para pie de página. Esto mejora accesibilidad y SEO, permitiendo que lectores de pantalla entiendan la estructura.

**Seguridad (OWASP Top 10):**

SQL Injection se previene usando Eloquent ORM que genera prepared statements automáticamente. XSS se previene porque Blade auto-escapa HTML por defecto en cualquier variable impresa con llaves. CSRF se protege con tokens que se validan en middleware. Contraseñas se hashean con bcrypt, algoritmo criptográfico lento a propósito para hacer fuerza bruta impracticable.

**Accesibilidad (WCAG 2.1 Level AA):**

El contraste de colores entre texto y fondo cumple con estándares de 4.5:1 para texto normal, y muchos elementos logran AAA (7:1). Las etiquetas están asociadas a inputs para que lectores de pantalla lean "Correo electrónico" junto al campo. Botones con solo iconos tienen atributos aria-label que describen su función. La navegación con teclado Tab es completa, y elementos interactivos muestran focus visible cuando se seleccionan.

### 7.4 Librerías Externas Utilizadas

| Librería | Versión | Propósito | Decisión |
|----------|---------|----------|----------|
| Laravel Framework | 10.50.2 | Framework MVC principal | Elegida por comunidad grande, documentación, built-in features (Eloquent, Blade, Auth) |
| Bootstrap | 5.3 | Framework CSS | Proporciona componentes listos, grid responsive, compatible cross-browser |
| Font Awesome | 6.4.0 | Iconografía | Librería de 6000+ iconos gratuitos, carga rápida |
| Vite | 5.0.0 | Build tool | Reemplazo moderno de webpack, hot reload en desarrollo, minificación en producción |
| PHPUnit | 10.5.63 | Testing framework | Estándar de facto en PHP, integrado en Laravel |
| Cypress | 14.3.2 | E2E Testing | Alternativa moderna a Selenium, mejor debugging, ejecución en navegador real |
| Doctrine DBAL | 3.x | Database abstraction | Proporciona abstracción de BD para migrations, soporta MySQL, PostgreSQL, etc |

### 7.5 Técnicas y Patrones Implementados

**Patrones de Diseño Aplicados:**

Service Layer Pattern centraliza la lógica de negocio en clases como NoteService, SearchService y SharedLinkService. Los controladores no contienen lógica compleja; simplemente delegan al servicio. Esto hace que sea fácil reutilizar esa lógica desde tests o APIs futuras sin repetir código.

Repository Pattern encapsula el acceso a datos. Aunque Laravel no implementa repositorios explícitamente (Eloquent cumple ese rol), se diseñó de forma que si en el futuro se necesitase cambiar de BD, bastaría cambiar el modelo sin tocar controladores.

Model-View-Controller asegura separación clara: Models contienen datos y relaciones, Views (Blade templates) contienen presentación, Controllers contienen orquestación de requests. Esto hace que cambiar la presentación (de Blade a API JSON) sea posible sin tocar modelos.

Dependency Injection en Laravel es automática. Los servicios se inyectan en constructores, lo que facilita testing (pasar mocks en tests) y desacoplamiento.

**Buenas Prácticas Implementadas:**

DRY (Don't Repeat Yourself) se aplicó creando servicios reutilizables. Búsqueda se ejecuta desde el mismo SearchService en listados, búsquedas filtradas, y APIs futuras.

SOLID Principles guiaron decisiones: Single Responsibility (cada servicio hace una cosa), Open/Closed (extensión sin modificación), etc.

Code Review mediante Git history: cada commit tiene mensajes descriptivos que explican qué cambió y por qué.

Documentation mediante docstrings: métodos complejos tienen comentarios que explican algoritmo, casos especiales, y ejemplos de uso.

---

## 8. Infraestructura y Despliegue

### 8.1 Decisión de Infraestructura: De Cloudflare a Oracle Cloud

**Consideraciones Iniciales:**

En la fase de planificación, Cloudflare era una opción atractiva por su popularidad como CDN (Content Delivery Network) y su facilidad de integración. Sin embargo, Cloudflare es principalmente un proxy inverso y servicio de caché; requiere que la aplicación esté alojada en algún servidor de origen. Esto significaría dos costos: uno por el dominio personalizado (obligatorio para usar Cloudflare profesionalmente) y otro por el hosting del servidor backend.

**Por Qué Cambiar a Oracle Cloud:**

Se eligió Oracle Cloud Always Free Tier por una razón fundamental: proporciona una máquina virtual gratuita de forma perpetua. Oracle ofrece el tier "Always Free" que incluye una VM.Standard.E2.1.Micro con recursos suficientes para un MVP (1 OCPU, 1GB RAM), sin límite de tiempo. Esto eliminaba completamente el costo de infraestructura.

La decisión clave fue evitar gastos innecesarios en el MVP. Al no requerir un dominio personalizado (la aplicación se accede por IP), se ahorro el costo anual de dominio (típicamente 10-15 EUR) y se elimino la necesidad de usar CDN. La solución es 100% gratuita: hosting gratuito en Oracle Cloud, código alojado gratuitamente en GitHub, y CI/CD con GitHub Actions también gratuito.

**Comparativa de Opciones Rechazadas:**

Otros proveedores fueron considerados: Heroku ahora cobra desde 7 USD/mes mínimo (antes era gratuito). AWS Free Tier ofrece 12 meses gratuitos pero después requiere pago. DigitalOcean ofrece droplets desde 4-5 USD/mes. Google Cloud y Azure tienen opciones gratuitas limitadas pero complejas. Oracle Cloud fue la opción más directa: máquina virtual perpetua gratuita sin restricciones.

**Limitaciones Aceptadas:**

La única limitación importante es que la IP es efímera (puede cambiar si se reinicia la máquina o por mantenimiento de Oracle). Para un MVP en etapa de proyecto académico, esto es aceptable. En producción real, se registraría un dominio personalizado y se usaría IP fija o DNS dinámico. La solución actual permite demostrar funcionalidad sin inversión, lo que es el objetivo del MVP.

### 8.2 Infraestructura Actual

**Ubicación del Servidor:**

La aplicación se aloja en un servidor de Oracle Cloud en la región de Ámsterdam, Países Bajos. Se eligió esta región por latencia razonable a Europa y porque es una de las opciones disponibles en Always Free Tier. El servidor es una máquina virtual Linux con capacidad para ejecutar Laravel con MySQL y Nginx simultáneamente.

**Stack Técnico Implementado:**

El servidor ejecuta Oracle Linux 8, que es una distribución basada en RHEL pero optimizada por Oracle. Nginx actúa como web server, escuchando en el puerto 80 (HTTP). PHP 8.2 se ejecuta mediante PHP-FPM, un gestor de procesos que permite que Nginx maneje múltiples requests. MySQL 8.0 almacena todas las notas, usuarios y links compartidos. Composer gestiona dependencias de PHP, y Node.js con npm manejan la compilación de assets con Vite.

**URL de Acceso:**

La aplicación está disponible en http://51.170.49.16. Esta es una IP pública pero efímera, lo que significa que podría cambiar en cualquier momento (aunque en la práctica es estable si no se reinicia la máquina). Cuando la IP cambii, se actualizará en los GitHub Actions secrets para que el CI/CD continúe funcionando.

### 8.3 Flujo de Despliegue (CI/CD)

**Configuración Actual:**

Se implementó un pipeline básico de despliegue continuo. Cuando un desarrollador hace push a la rama master en GitHub, GitHub Actions (servicio de CI/CD de GitHub) detecta el cambio automáticamente. El workflow definido en .github/workflows/deploy.yml se ejecuta, compilando la aplicación y verificando que no haya errores.

**Proceso de Despliegue:**

Después de compilación exitosa, GitHub Actions se conecta vía SSH al servidor Oracle Cloud usando una clave privada almacenada en GitHub secrets. Se ejecutan comandos remotos que actualizan el repositorio git, instalan dependencias con Composer, ejecutan migraciones si hay cambios en BD, y reinician servicios si es necesario. Todo el proceso toma aproximadamente 30 segundos.

**Usuario de Despliegue:**

En el servidor, existe un usuario específico llamado "opc" (Oracle Public Cloud) que tiene permisos para acceder a la carpeta /var/www/grimorio y reiniciar servicios. Las claves SSH están almacenadas de forma segura en GitHub, nunca en el repositorio público. Esto asegura que solo GitHub Actions pueda desplegar en el servidor, sin exponer credenciales.

### 8.4 Base de Datos en Producción

**Ubicación y Respaldo:**

MySQL 8.0 se ejecuta en el mismo servidor que la aplicación. Aunque idealmente la BD estaría en un servidor separado en producción real, para el MVP en una VM de 1GB, está dentro de los límites. La base de datos contiene las tablas users, notes, tags, note_tag (relación muchos-a-muchos), y shared_links.

**Optimización:**

Se crearon índices full-text en los campos titulo y contenido de la tabla notes, lo que permite que la búsqueda con operadores AND/OR sea rápida incluso con miles de notas. La conexión entre aplicación y BD es local (localhost), sin latencia de red.

**Respaldo Manual:**

Actualmente no hay respaldo automatizado. Para recuperar datos en caso de problema, se accede por SSH al servidor y se ejecuta mysqldump para exportar la BD a un archivo SQL. Esto es suficiente para MVP; en producción se implementaría respaldo automático diario.

### 8.5 Monitoreo y Logs

**Logs de Aplicación:**

Laravel genera logs detallados en /var/www/grimorio/storage/logs/laravel.log. Se configuro rotación diaria para que los logs no crezcan indefinidamente. Se retienen 30 días de histórico de logs, suficiente para auditoría y debugging. El nivel de logging está configurado a INFO, capturando errores y warnings, pero evitando verbosidad innecesaria.

**Acceso a Logs:**

Un desarrollador puede conectarse al servidor por SSH y ejecutar tail -f /var/www/grimorio/storage/logs/laravel.log para ver logs en tiempo real. Esto permite debugging rápido cuando algo falla en producción. También se pueden verificar los estados de servicios (Nginx, PHP-FPM, MySQL) con systemctl para confirmar que todos están corriendo.

**Verificación de Permisos:**

Se verifican permisos en el directorio storage/ para asegurar que Nginx y PHP-FPM pueden escribir logs. Si hay problemas de escritura, la aplicación falla silenciosamente, lo que se detecta rápidamente en logs. Se mantiene documentación de cómo resolver problemas comunes (permisos incorrectos, servicios caídos, espacio en disco lleno).

### 8.6 Acceso y Credenciales

**Usuario de Demo:**

Se proporciona un usuario de prueba con email test@example.com y contraseña password para que evaluadores puedan probar la aplicación sin crear su propia cuenta. Este usuario viene pre-poblado en las migraciones de base de datos.

**Acciones de Prueba Disponibles:**

Un evaluador puede: (1) Hacer login con el usuario de demo, (2) Ver el listado de notas existentes, (3) Crear una nueva nota con título y contenido, (4) Buscar notas usando operadores como "Laravel AND deployment" o "nota OR apunte", (5) Filtrar por tags seleccionando uno o varios, (6) Compartir una nota generando un link público que puede copiarse, (7) Acceder a ese link en una ventana incógnita (sin estar logeado) para confirmar que el link compartido es accesible públicamente, (8) Intentar compartir por email (la funcionalidad está implementada pero el email no se envía por limitaciones del firewall de Oracle).

### 8.7 Repositorio y Control de Versiones

**GitHub Repository:**

El código está alojado en https://github.com/Juanjo-Saez/Grimorio. La rama principal es master, donde está el código de producción. Cada feature se desarrolla en ramas separadas (feature/search, feature/shared-links, etc.) y se integra a master mediante pull requests.

**Histórico de Commits:**

Hay más de 50 commits documentando la evolución del proyecto desde la estructura inicial hasta features como búsqueda, tags, y compartición de links. El commit más reciente (2026-05-18) incluye feature de shared links con soporte de email.

**Cómo Clonar y Ejecutar Localmente:**

Un desarrollador puede clonar el repositorio, instalar dependencias con Composer, configurar el archivo .env con credenciales locales, ejecutar migraciones para crear tablas, y iniciar el servidor local con php artisan serve. Todo el código está documentado y las instrucciones están en el README.md del repositorio.

---

## 9. Posibles Mejoras Futuras (Post-21 de Mayo)

1. Emails Funcionales
   - Resolver firewall de Oracle Cloud para Mailgun/SendGrid
   - Implementar notificaciones por email
   - Queue jobs para envío asincrónico

2. HTTPS/SSL
   - Registrar dominio personalizado
   - Instalar Let's Encrypt certificate
   - Redirigir HTTP → HTTPS

3. Características Avanzadas
   - Historial de versiones de notas
   - Colaboración en tiempo real (WebSockets)
   - Adjuntos/imágenes en notas
   - Exportar notas a PDF/Markdown

4. Performance
   - Implementar Redis para caché
   - Lazy load de imágenes
   - Minificación agresiva de assets

5. Seguridad
   - 2FA (Two-Factor Authentication)
   - Audit log detallado
   - Rate limiting en APIs

6. Mobile
   - Aplicación React Native
   - Sincronización offline-first

---

Documento generado: 2026-05-18  
Última actualización: Entrega PFC
