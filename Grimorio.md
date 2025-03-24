# Grimorio Web
 
### Vision 

Con el constante avance de la tecnología, sobretodo en el campo del desarrollo, el acceso a información específica y útil es clave. Si bien nuevas tecnologías como las IA's nos han facilitado labores (sintaxis ejem ejem), todos tenemos un deber como profesionales de ser capaces de encontrar el conocimiento que buscamos de una manera eficiente. 

Todos nuestros aprendizajes pueden reencontrarse en la web, pero la idea de personalizarlos para facilitar nuestro trabajo de recuerdo es clave. Ahora mismo, una gran parte de nosotros tendrá directorios organizados de múltiples maneras donde se encuentran pedazos de ese aprendizaje, pero ante cualquier problema, solemos recurrir a una rápida búsqueda en la documentación oficial de la tecnología que nos atañe o preguntar a una IA. Todo esto nos aporta una respuesta, pero no una con la que estamos familiarizados.

Por el contrario, nuestras propias anotaciones y aprendizajes son mucho mejores en estas situaciones, pero tienden a ser más dificiles de localizar.

Grimorio busca ofrecer una solución a esa necesidad que siempre vamos a tener, acceder a nuestro propio conocimiento de una manera rápida y eficaz. Todos tus apuntes, tus anotaciones en archivos markdown bien divididos y agrupados. Con la opción de añadirles múltiples tags para conseguir mayor agrupación que, de otra manera, no podrían permitir ser encontrados en múltiples búsquedas distintas. Si tenemos un archivo en una carpeta (Entorno Servidor) no podemos tenerlo en otras carpetas, mientras que con los tags podemos taggearlo en 

Es como la wikipedia pero personal y privada pero con opciones de compartir con otros y colaborar.

Nace de una necesidad personal, quiero tener todos mis conocimientos en el mismo sitio y fáciles de leer

La mayoría de webs para tomar notas son rígidas (notion y obsidian), se busca un sistema mucho más flexible que simule el pensamiento.

Zetelkasten (método de tomar notas para gente caótica) que es una buena contraposición ante las notas muy categorizadas, da más facilidad a poder hacer notas rápidas sin pensar en cómo guardarlas. Zettlekasten nos aporta el hecho de enlazar archivos, ayudandonos a poder poner tags a nuestras notas y de esta manera que aparezcan en distintas agrupaciones. En una jerarquía de carpetas normativa, no podríamos tener un archivo en varias carpetas a menos que los dupliquemos. De esta manera, podremos tener un Grimorio en el que agruparemos todos los archivos sin necesidad de bajar a distintos niveles/categorizarlos en carpetas. 

Búsqueda de texto rápida en todo, tanto el título, contenido, directorios

Tags (Poder seleccionar una nota y que te vengan todas las enlazadas a ella ya cargadas)

Gráficos


programas: FZF (búsqueda)

Referencias de negocio: 
- [Roam](https://roamresearch.com/) (idea factory)
- [Wikipedia](https://en.wikipedia.org/wiki/Zettelkasten)
- [MyMind](https://mymind.com/) (idea storage)
- [Notion](https://www.notion.so) (idea storage)

#### Tecnologias
 
- Lenguaje [PHP](https://www.php.net/manual/es/index.php)
- Framework [Laravel](https://laravel.com/)
    - Vistas con [Blade](https://laravel.com/docs/11.x/blade)
- Base de datos [SQLite](https://www.sqlite.org/)
 
 
#### Decisiones tecnicas
 
- Gestion de usuarios por BBDD.
- Gestion de notas mediante archivos MD.
 
Cada usuario tiene una carpeta en el servidor. Dentro, se agrupan todas las notas en formato markdown:

``` 
server
|-oxell
|   L repaso-examen-es.md
|   L tipos-de-datos-php.md
|   L estructura-de-un-proyecto.md
|
|-jelohe
|  L trabajar-con-archivos-en-php.md
|  L metodo-zettlekasten.md
|  L proyecto-grimorio.md
``` 
- Control de acceso
    - Las notas son privadas por defecto.
    - Compartir notas mediante un link de solo lectura.
    - Compartir notas mediante un link con acceso de lectura y escritura.
 
 
#### Rubricas
 
- Diagrama entidad relacion.
- Crear seeders y factories.
- CSRF tokens para auth.
- Manejo de session y cookies.
- Testing.
- Traits y clases anonimas.
- Control de errores.
- Uso de middlewares.
- Logs en Laravel.
- API + Postman.
- API externa [(url shortener)](https://publicapi.dev/free-url-shortener-api)
    - Guzzle
- 🍒 del pastel (enlazar imagenes automaticamente)



### Potenciales

Método zettlekasten nos aporta el hecho de enlazar archivos, ayudandonos a poder poner tags a nuestras notas y de esta manera que aparezcan en distintas agrupaciones. En una jerarquía de carpetas normativa, no podríamos tener un archivo en varias carpetas a menos que los dupliquemos. De esta manera, podremos tener un Grimorio en el que agruparemos todos los archivos sin necesidad de bajar a distintos niveles/categorizarlos en carpetas. 



### Tareas
librerias, laravel y frontend middleware y api publica investigar

Cambiar a Laravel 10, que estamos en 12. 

php artisan migrate para enlazar la base de datos. En la práctica 1 o 2 del Tema 1 se hace, para comprobarlo.

Semana que viene trataremos migraciones, seeders etc.

Laravel tiene su propio sistema, el php artisan. XAMPP sirve para el Mysql para el cliente, si tienes uno puede usarlo, de primero o algo. El XAMPP te hace ya el paquete de php mysql y demás. Apache no se va a usar más que para el php myadmin. 
En versiones nuevas de Laravel en lugar de mysql es sqlite. En este caso, dice Guillermo que usaremos mysql.

Puede pasar que en alguna migración o seeder pete y empieza un bucle a crear cosas y se cuelga la base de datos.

Tabla update para probar como funciona para migraciones (si añadimos un campo nuevo, que podriamos cargarnos la base de datos y crear nueva, pero imaginemos que estamos en producción y eso no podemos, así hacemos migración)

Si nos da muchos errores la migración, borrad el contenido de la base de datos y volved a ejecutar la migración, que a veces se queda un error y algunas tablas existen y otras no. Vaciad la base de datos, ejecutais el migrate y se vuelve a subir la base de datos. Con el Refresh se carga el drop y se vuelve a actualizar. Si añades un nuevo campo, no ahces el migrate y haces el refresh, busca una tabla que no existe y se cuelga. Es un error recurrente que suele pasar a menudo, por eso lo mejor es borrar las tablas a mano y volver a ejecutar el migrate, como consejo rápido, para no liarse.

Notas pueden pertenecer a user o team para crear polimorfismos. Team de frontend, backends. Pueden ser imagenes o comentarios, todo es practicar 

Lectura ficheros, clases anonimas, traits, 

Examen: Constultas directas a BBDD no salen.

#### Diagrama ER

Respecto al diagrama entidad relación, no he puesto ningún rombo de relación (Estilo "Has") y los ejemplos muestran algunos de estos HAS que tienen atributos (un diagrama de web de compras que tiene un carrito y demás que junta productos con users, y la cantidad de cada producto y demás atributos van en el HAS no en la tabla)

Buscar como hacer una tabla polimorfica con mis datos.

Los enumerados solo si tienes claro que los campos no van a crecer en un futuro

Roles de usuario por la libería, por lo que el Diagrama ER tendrá que ampliarse depediendo de lo que la libería nos cree.

### Pasos Realizados

A continuación, tras haber realizado el diagrama Entidad Relación de nuestro proyecto y documentar una buena descripción para nuestro README, pasamos a preparar el entorno.

Primero vamos a instalar Laravel. Abrimos PowerShell en windows como administrador y ejecutamos el siguiente comando

De esta manera instalamos PHP, Composer y Laravel.

Se crea el proyecto en Laravel:

`composer clear-cache`
`composer create-project laravel/laravel Grimorio --prefer-dist`

Añadimos Laravel Dusk para test:

`composer require laravel/dusk --dev`

Respecto a dependencias, por ahora no necesitamos ninguna. Se irán añadiendo según aparezcan.

Si quisieramos añadir una librería, Carbon es una utilidad para poder gestionar fechas límite que usaremos en nuestros enlaces, es decir, que expiren pasado cierto tiempo. Además, es útil porque viene incluida en laravel por defecto. Esto se suele llamar Cron Job. Task scheduling es algo más actual que puede sustituir as Cron Jobs

De la misma manera, para gestionar el login, Laravel te da una funcionalidad por defecto

Opciones de liberías externas:

- Carbon. fechas límite que usaremos en nuestros enlaces. Si vas a trabjar con ellas para asignar cosas y tal mejor, porque te quita bastante trabajo, pero si solo es para dos tonterias, mejor no complicarse.

- Spatie Laravel Permissions. Nos serviría para configurar permisos y roles para las notas (EJ: Owner, Viewer(solo lectura) y Collaborator(lectura y escritura)) **Aprobada**

- Laravel Notifications. Para mandar notificaciones por sms. 
  Cuidado con meterse en tema de jobs, y eso. Nos complicará la vida bastante, llamadas tipo broadcast y ese tipo, si no es muy complicado bien, pero mejor pasar porque queremos algo simple para practicar.

- Laravel Dusk. Para End-to-end tests. Te abre el navegador y lo comprueba con clicks, la mayoría de otros test llaman a funcionesy  comprueban, esto lanza la web entera.

- Autenthication: Laravel Jetstream. Sanctum

- Manejo de imagenes: -Spatie medialibrary, -Intervention Image

- Notificaciones: Spatie Laravel webhook

- Multi-Factor es una librería externa que nos puede servir para agregar Two Factor Authentication. Se instalaría:
`composer require paragonie/multi-factor`
- Laravel Fortify y Topo. Para two factor authentication si lo queremos interno de Laravel.
 
- Laravel Debugbar o Laravel Telescope. Por si queremos ayudarnos a debugear de manera más fácil, telescope aporta hacerlo a tiempo real