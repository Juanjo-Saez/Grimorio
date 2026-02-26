# 📘 DWES - Apuntes Completos (Tema 1 + Tema 2)

CFGS DAW - Desarrollo Web en Entorno Servidor\
Curso 2024-2025

Guillermo:

Lo importante en esta asignatura, el flujo. Cosas importantes, los modelos y las relaciones importantisimo, si eso falla, falla todo. Las migraciones y los seeders super importante, pero después el flujo.

Que no funcione el método porque me he equivocado en un if un else no es tan importante si realmente estoy después consumiendo el servicio de manera adecuada, el servicio consume su repositorio, el repositorio es el que usa los modelos, que el flujo lo tengais claro. Quien pide cada cosa, quien la usa.

De cara al examen, en el caso de que sea una ampliación de algo, vais a tener que pasar por el flujo, tened claro cómo funciona cada cosa, como funciona cada cosa y quien pide cada cosa

No me cojais el controlador y me hagais una consulta desde el controlador o un create desde el controlador que no pase por un servicio. Va a ir muy encarado el examen a eso

Convertirlo en una API, consumir APIs, cosas de front a lo mejor pongo un botón que llame al controlador, no voy a ponerme a pediros que haga mil vistas nuevas o 4 componentes. Teneis que saber en qué componente o os lo doy y teneis que añadirlo, saber entrar, añadir un botón, etc. 

En ordinaria será como modelos anteriores donde tengo que daros todo el contexto, ya que no conoceis la app, no vais a ir sobre vuestra app. 

El año pasado daba un diagrama de entidad-realcion y tenias que montar lo que te pedía. Modelos, seeders,  controladores, middlewares, vistas, conectarte a una api externa, un test de prueba... entra prácticamente todo
Son tres horas de no parar de hacer cosas para que te de tiempo a todo lo que pide

------------------------------------------------------------------------

Notas no tramposas:

`composer create-project laravel/laravel proyecto`

tras hacer Api hay que crear la ruta en boostrap/app.php `api: __DIR__.'/../routes/api.php',`

apuntarme todos los ejemplos de CRUD

Para crear una clase static el new
    public function index()
    {
        $pepe = new StarWarsApiService;
        $character = $pepe->getRandomCharacter();

        return response()->json($character, 200);
    }

Para acceder a un valor de un objeto en concreto en PHP
return $response->json()['result']['properties']['name'];

Apuntes Examen escritos a mano:

Cosas Clave

Import siempre

HOJA 1 (DELANTE)
TEMA 4 – Gestión de datos en PHP (sin framework)
🔑 Superglobales (muy preguntado)

$_GET → datos por URL

$_POST → datos de formularios

$_REQUEST → mezcla (NO recomendada)

$_SESSION → datos persistentes del usuario

$_COOKIE → datos en el navegador

$_FILES → subida de archivos

👉 Session

session_start();
$_SESSION['user'] = 'Juan';
session_destroy();

👉 Cookie

setcookie('nombre', 'valor', time()+3600);
🔑 Formularios

method="GET" → visible en URL

method="POST" → no visible

action → script destino

Validar SIEMPRE en servidor:

if(empty($_POST['email'])) { ... }
🔑 Conexión a BD con PDO (MUY típico)
$pdo = new PDO(
  "mysql:host=localhost;dbname=bd",
  "user",
  "pass"
);

✔️ Prepared Statements (evita SQL Injection):

$stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
$stmt->execute([$email]);
🔑 CRUD en PHP

Create → INSERT

Read → SELECT

Update → UPDATE

Delete → DELETE

🧠 HOJA 1 (DETALLE QUE CAE)

❌ Errores típicos:

No usar session_start()

Usar $_REQUEST

No validar datos

No usar prepared statements

🧠 HOJA 2 (DELANTE)
TEMA 5 – Frameworks y Laravel (MVC)
🔑 Patrón MVC (CLAVÍSIMO)

Model → datos + lógica BD

View → HTML / Blade

Controller → lógica + conexión M/V

Usuario → Ruta → Controller → Model → Controller → View
🔑 Estructura Laravel (saber explicar)

routes/web.php → rutas web

routes/api.php → API

app/Models → modelos

app/Http/Controllers

resources/views

database/migrations

🔑 Rutas
Route::get('/', function () {});
Route::post('/login', [AuthController::class, 'login']);

Con nombre:

->name('login');

Variables:

Route::get('/user/{id}', ...);
🔑 Blade
{{ $variable }}   // seguro
@foreach
@if
@extends
@section
@include
🧠 HOJA 2 (DETALLE)

❌ Errores típicos:

Mezclar lógica en la vista

No usar rutas con nombre

No usar Blade

🧠 HOJA 3 (DELANTE)
TEMA 6 – Datos en Laravel (Eloquent)
🔑 Migraciones
php artisan make:migration create_users_table
php artisan migrate
php artisan migrate:rollback

up() → crea/modifica

down() → revierte

🔑 Modelo Eloquent
class User extends Model {
  protected $fillable = ['name','email'];
}

✔️ Asignación masiva necesita $fillable

🔑 Relaciones (MUY preguntadas)

1–1 → hasOne

1–N → hasMany

N–N → belongsToMany

Polimórficas → morphTo

🔑 CRUD con Eloquent
User::all();
User::find(1);
User::create([...]);
$user->update([...]);
$user->delete();
🔑 Seeders & Factories
php artisan make:factory UserFactory
php artisan db:seed
🧠 HOJA 3 (DETALLE)

❌ Errores típicos:

Olvidar $fillable

Confundir relaciones

No ejecutar migrate

🧠 HOJA 4 (DELANTE)
TEMA 7 – Autenticación, Middleware y APIs
🔑 Autenticación Laravel

Basada en sessions

Auth::attempt()

Auth::user()

Auth::logout()

🔑 Guards & Providers

Guard → cómo se autentica

Provider → de dónde salen los datos (BD, Eloquent, OEM)

🔑 Middleware

Tipos:

Global → $middleware

Grupos → $middlewareGroups

Alias → $middlewareAliases

Uso:

Route::middleware('auth')->group(function () {});
🔑 Servicios

Clases reutilizables

app/Services

Inyección por constructor

🔑 APIs REST

JSON

Sin vistas

Rutas en api.php

CRUD API:

Route::apiResource('books', BookApiController::class);
🔑 Testing
php artisan test

TDD:

Test falla (RED)

Código

Test pasa (GREEN)

🧠 HOJA 4 (DETALLE FINAL)

❌ Errores típicos:

Poner auth global

Usar vistas en API

No devolver JSON

Olvidar status HTTP (201, 200, 404)

✅ CONSEJO DE EXAMEN FINAL

Si dudas:

Explica MVC

Dibuja flujo

Escribe nombres reales (auth, middleware, fillable)

Pon ejemplos cortos



Practica de conceptos:

### Service

Clase que encapsula lógica de negocio. No depende de vistas ni rutas, es una lógica reutilizable. Sirve cuando tu controlador empieza a tener demasiada lógica (calculos, procesos largos, integraciones externas, validaciones complejas) lo extraemos a un Service

Creación: Se crea manualmente en app/Services
    Ejemplo:
```php
    namespace App\Services;

    use App\Models\Student;
    use App\Models\Subject;

    class EnrollmentService
    {
        public function enrollStudent($student, $subject)
        {
            $subject->students()->attach($student->dni);
        }
    }
```
    Y tras eso, en el controller ya solo haría falta llamarlo:
```php

    namespace App\Http\Controllers;

    use App\Services\EnrollmentService;
    use App\Models\Student;
    use App\Models\Subject;

    class SubjectController extends Controller
    {
        protected EnrollmentService $service;

        public function __construct(EnrollmentService $service)
        {
            $this->service = $service;
        }

        public function enrollStudent($subjectId, $studentId)
        {
            $student = Student::findOrFail($studentId);
            $subject = Subject::findOrFail($subjectId);

            $this->service->enroll($student, $subject);
        }
    }
```
En resumen: Un Service: Contiene lógica de negocio, Hace el código más limpio, Permite reutilización, Mejora testabilidad

### Service Provider

Clase que registra servicios en el contenedor de Laravel, y se usan para registrar paquetes, configurar bindings, configurar listeners.
Está en app/Providers/AppServiceProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EnrollmentService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EnrollmentService::class);
    }
}
```

### Trait

Mecanismo para reutilizar código en varias clases. Es como una mini clase reutilizable que se puede insertar en otras clases. Sirve cuando varias clases necesitan el mismo comportamiento

Creación: No se crean con artisan, si no a mano en app/Traits

Ejemplos claros son el SoftDeletes o el HasFactory, que son traits muy usados ya implementados por Laravel, pero podemos crear nuestros propios traits.
    Ejemplo:
```php
    namespace App\Traits;

    trait LogsActivity
    {
        public function log($message)
        {
            echo $message;
        }
    }
```
y luego en una clase
```php
    use App\Traits\LogsActivity;

    class Course
    {
        use LogsActivity;
    }
```

### Helper

Es una función global que se puede usar en cualquier parte del proyecto, y hay muchos ejemplos en laravel como route(), view(), auth(), dd(), etc.

Sirve para funciones pequeñas y reutilizables

Ejemplo:
```php
    <?php

    function formatName(string $name): string
    {
        return strtoupper($name);
    }
```
Y en composer.json:
"autoload": {
  "files": [
    "app/helpers.php"
  ]
}
y luego `composer dump-autoload`

IMPORTS MÁS IMPORTANTES PARA MEMORIZAR
Concepto	Import clave
Trait	    use App\Traits\X
Factory	    use Illuminate\Database\Eloquent\Factories\Factory
HasFactory	use Illuminate\Database\Eloquent\Factories\HasFactory
Seeder	    use Illuminate\Database\Seeder
Middleware	use Illuminate\Http\Request
Pivot	    use Illuminate\Database\Eloquent\Relations\BelongsToMany
Service	    use App\Services\X
Provider	use Illuminate\Support\ServiceProvider
Model       use App\Models\Adventure;
       

### Middleware

Es una capa que se ejecuta antes o después de una petición HTTP. Filtra o modifica la petición.

Se suele usar para verificar login, rol, control de acceso, regirstrar logs, etc.

Primero lo creamos con `php artisan make:middleware CheckAuth`

Ejemplo:
```php
    <?php

    namespace App\Http\Middleware;

    use Closure;
    use Illuminate\Http\Request;
    use Symfony\Component\HttpFoundation\Response;

    class CheckAuth
    {
        public function handle(Request $request, Closure $next): Response
        {
            if (!auth()->check()) {
                return redirect('/login');
            }

            return $next($request);
        }
    }
```
y en rutas `Route::get('/inicio', CourseController::class)->middleware('auth');`

No olvidemos en Kernel.php poner el `use App\Http\Middleware\CheckAuth;`

### Factory

Es una clase que genera datos falsos para testing o seeders

Ejemplo:
```php
    <?php

    namespace Database\Factories;

    use Illuminate\Database\Eloquent\Factories\Factory;
    use App\Models\Student;

    class StudentFactory extends Factory
    {
        protected $model = Student::class;

        public function definition(): array
        {
            return [
                'dni' => fake()->numerify('########'),
                'nombre' => fake()->name(),
                'apellidos' => fake()->lastName(),
                'email' => fake()->safeEmail(),
                'telefono' => fake()->numerify('#########'),
            ];
        }
    }
```
y en el modelo Student hay que hacer
`use Illuminate\Database\Eloquent\Factories\HasFactory;`

y luego llamarlo en el seeder con
```php
    Student::factory()->count(10)->create();
```

### Seeder

Es una clase que inserta datos iniciales en la base de datos
`php artisan make:seeder StudentSeeder`

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::factory()->count(20)->create();
    }
}
```

### Pivot Relation

Es una tabla intermedia para relaciones many-to-many. student_subject
Modelo Subject
```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Student;

public function students(): BelongsToMany
{
    return $this->belongsToMany(
        Student::class,
        'student_subject',
        'subject_identificador',
        'student_dni'
    );
}
```
Modelo Student
```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Subject;

public function subjects(): BelongsToMany
{
    return $this->belongsToMany(
        Subject::class,
        'student_subject',
        'student_dni',
        'subject_identificador'
    );
}
```
### Dependency Injection

Cuando laravel crea automáticamente una clase y la inyecta en otra. Laravel la crea solo
`public function __construct(EnrollmentService $service)`
```php
use App\Services\EnrollmentService;

public function __construct(EnrollmentService $service)
{
    $this->service = $service;
}
```
### MVC

Modelo - Vista - Controlador



# =========================

# TEMA 1 - ARQUITECTURA WEB

# =========================

# 1. Arquitectura de una aplicación web

## Introducción

Las páginas web son accesibles a través de Internet mediante el sistema
WWW. Se utilizan protocolos como HTTP, HTTPS, FTP o SSH.

El código HTML estructura el contenido y el servidor genera la respuesta
al cliente.

### Software Cliente

-   Google Chrome
-   Microsoft Edge
-   Mozilla Firefox

### Software Servidor

-   Apache
-   Nginx
-   IIS

------------------------------------------------------------------------

## Estructura de una URL

protocolo://servicio.dominio/ruta/archivo.ext

Ejemplo: https://www.garrido.es/img/foto.jpg

Puertos habituales: - HTTP → 80 - HTTPS → 443 - Alternativos → 8080

------------------------------------------------------------------------

# Arquitectura Cliente/Servidor

El cliente realiza peticiones y el servidor responde con códigos HTTP: -
200 OK - 404 Not Found

## Middleware

Software intermedio que permite comunicación.

Tipos: - General (TCP/IP) - Servicios (ODBC, CORBA, Sockets)

------------------------------------------------------------------------

## TCP/IP

TCP → Control de transmisión\
IP → Dirección numérica

IPv4 ejemplo: 192.168.0.1

IPv6 ejemplo: 2001:0000:3238:DFE1:63:0000:0000:FEFB

DNS traduce dominio a IP.

------------------------------------------------------------------------

# Servidores y páginas web

## Métodos HTTP

-   GET
-   POST
-   PUT
-   DELETE

## Tipos de servidores

-   Compartido
-   Dedicado
-   VPS

Infraestructura como Servicio (IaaS): - AWS - Azure - Google Cloud

------------------------------------------------------------------------

# Tipos de páginas web

## Estáticas

Contenido fijo HTML/CSS.

## Dinámicas

Generadas con base de datos y backend.

Ejemplos: - Tiendas online - CMS - Aplicaciones web

------------------------------------------------------------------------

# Lenguajes

## Cliente

-   JavaScript
-   TypeScript
-   WebAssembly
-   Svelte
-   Solid.js
-   Stencil
-   Lit

## Servidor

-   PHP
-   Python
-   Java
-   Rust
-   Go
-   Deno
-   Elixir
-   Kotlin
-   Swift

------------------------------------------------------------------------

# =========================

# TEMA 2 - INTRODUCCIÓN PHP

# =========================

# PHP

Lenguaje de scripting ejecutado en servidor. Se puede embeber en HTML.

## Bloques PHP

```{=html}
<?php
// código
?>
```
## Comentarios

// línea \# línea /* varias líneas */

## Mostrar contenido

```{=html}
<?php
echo "Hola mundo";
?>
```

------------------------------------------------------------------------

# Variables

Empiezan por \$

\$nombre = "Juan"; \$edad = 25;

Distingue mayúsculas/minúsculas.

Convenciones: - Variables → camelCase - Clases → StudlyCaps - Constantes
→ MAYUSCULAS_CON_GUIONES

------------------------------------------------------------------------

# Constantes

define("MAYOR_EDAD", 18);

------------------------------------------------------------------------

# Ámbito

global \$variable; \$GLOBALS\['variable'\];

------------------------------------------------------------------------

# Operadores

-   Aritméticos
-   Asignación
-   Comparación
-   Lógicos

Operador coalescente:

\$nombre = \$nombre ?? "Guillermo";

------------------------------------------------------------------------

# Tipos de datos

## Primitivos

-   int
-   float
-   string
-   bool

## Especiales

-   null
-   resource

------------------------------------------------------------------------

# Conversión

Casting: \$edad = (int) "37";

Funciones: - intval() - floatval() - strval() - boolval()

Formateo: echo number_format(1234.5678, 2, ',', '.');

------------------------------------------------------------------------

# Tipos compuestos

## Arrays

\$semana = \["Lunes","Martes"\];

## Objetos

\$libro = new Libro();

## Enum (PHP 8.1+)

enum Rol { case profesor; case estudiante; }

------------------------------------------------------------------------

# FIN DOCUMENTO


# 📘 DWES – Tema 3: POO en PHP, Formularios, Seguridad y Errores

## Programación Orientada a Objetos en PHP

### Clases
Una clase es una plantilla que define atributos y métodos.

```php
class Persona {
}
```

### Atributos
Propiedades del objeto:

```php
class Persona {
  private string $nombre;
}
```

### Métodos
Funciones dentro de la clase:

```php
public function getNombre() {
  return $this->nombre;
}

public function setNombre($nombre) {
  $this->nombre = $nombre;
}
```

### Objetos

```php
$persona = new Persona();
$persona->setNombre("Guillermo");
```

---

## Métodos mágicos

- __get()
- __set()
- __isset()
- __unset()
- __toString()
- __serialize()
- __unserialize()
- __destruct()

---

## Constructor y destructor

```php
public function __construct(string $nombre) {
   $this->nombre = $nombre;
}
```

---

## Herencia

```php
class Cliente extends Persona {
}
```

---

## Visibilidad

- private
- protected
- public

---

## Constantes de clase

```php
const NOMBRE = "Guillermo";
```

Acceso:

```php
self::NOMBRE;
```

---

## Clases abstractas

```php
abstract class Usuario {
   abstract public function login();
}
```

---

## Interfaces

```php
interface Administrador {
   public function borrar();
}
```

---

# FIN TEMA 3

# Tema 4 – Gestión de datos con PHP

## 1. Operaciones con el sistema de ficheros

### Enviar ficheros al servidor
Uso de formularios con enctype multipart/form-data y variable $_FILES.

Se procesan:
- Ruta destino
- Comprobación de extensión
- Movimiento con move_uploaded_file()

Soporta múltiples ficheros con arrays.

Control de tamaño usando MAX_FILE_SIZE.

### Manejo en servidor
- Renombrar (hash, md5, sha1)
- Eliminar con unlink()

---

## 2. Ficheros de texto

### Abrir ficheros
fopen con modos:
- r lectura
- w escritura
- a añadir

### Leer contenido
- fgets()
- feof()
- file_get_contents()

### Escribir
- fwrite()

### Comprobaciones
- file_exists()
- is_readable()
- is_writable()

### Descargar archivos
Uso de headers:
- Content-Type
- Content-Disposition
- readfile()

---

## 3. JSON

### Leer JSON
file_get_contents + json_decode()

Acceso como array u objeto.

### Validación
json_last_error()

### Crear JSON
json_encode() con flags:
- JSON_PRETTY_PRINT
- JSON_NUMERIC_CHECK

Guardar con file_put_contents()

---

## 4. Bases de datos relacionales

### MySQL con PHP
Uso de:
- MySQLi
- PDO

### Conexión MySQLi
mysqli_connect(host,user,pass,db,port)

### Consultas
- query()
- manejo de errores
- cierre con mysqli_close()

### Creación de tablas y BD
Sentencias CREATE DATABASE, CREATE TABLE

# Fin Tema 4 Teoría

<?php
/*
Crear un fichero de texto llamado precios.txt que contiene 10 precios (números reales). Estos aparecerán en el fichero de manera que cada línea del fichero contiene un único precio. 

A partir de este, se desea crear un fichero llamado descuentos.txt que contendrá los precios de menos de 50 euros del fichero precios.txt rebajados un 5% y los precios de 50 euros o más del fichero precios.txt rebajados un 10%. 

Escribir el programa en PHP que partiendo del fichero precios.txt genere el fichero descuentos.txt. Considerar el caso en el que el fichero de entrada está vacío
*/
```php
$fichero = fopen("precios.txt","r");
$ficheroDescuento = fopen("descuentos.txt","w");

if(!filesize("./precios.txt")){
    echo "El archivo está vacío";
}else{


    while(($buffer = fgets($fichero)) !== false){
        $precio = (float) trim($buffer);
        if ($precio === 0 && trim($buffer) === '') {
            continue;
        }

        if($precio<=50){
            $precio *= 0.95;
        }else{
            $precio *= 0.90;
        }
        fwrite($ficheroDescuento, $precio . "\n");
    }
}
fclose($fichero);
fclose($ficheroDescuento);
?>
```

<?php

Crear un script PHP que genere un fichero numeros.txt que contenga 100 números,
aleatorios del 1 al 120, situados un número en cada fila.

A partir del fichero obtener:
• El porcentaje de números pares
• La suma de números impares
• El número de veces que aparece cada número


// 1️⃣ CREAR Y ESCRIBIR EL FICHERO
```php
$fichero = fopen("numeros.txt", "w");

for ($i = 0; $i < 100; $i++) {
    $numero = rand(1, 120);
    fwrite($fichero, $numero . PHP_EOL);
}

fclose($fichero);

// 2️⃣ ABRIR EL FICHERO PARA LECTURA
$fichero = fopen("numeros.txt", "r");

$conteoPares = 0;
$sumaImpares = 0;
$totalNumeros = 0;
$contador = [];

while (($buffer = fgets($fichero)) !== false) {
    $numero = (int) trim($buffer);

    // Contador total
    $totalNumeros++;

    // Pares / impares
    if ($numero % 2 === 0) {
        $conteoPares++;
    } else {
        $sumaImpares += $numero;
    }

    // Contar apariciones
    if (isset($contador[$numero])) {
        $contador[$numero]++;
    } else {
        $contador[$numero] = 1;
    }
}

fclose($fichero);

// 3️⃣ CÁLCULOS FINALES
$porcentajePares = ($conteoPares / $totalNumeros) * 100;

// 4️⃣ MOSTRAR RESULTADOS
echo "Porcentaje de números pares: " . number_format($porcentajePares, 2) . "%<br>";
echo "Suma de números impares: $sumaImpares<br><br>";

echo "Número de veces que aparece cada número:<br>";
foreach ($contador as $numero => $veces) {
    echo "Número $numero → $veces veces<br>";
}
?>
```


Crear el siguiente formulario dinámico de un perfil de aficiones. 

El atributo action del form enviará los datos a otro archivo cuyo script PHP debe comprobar si existe el directorio “ficheros”, de no ser así lo creará, 
y debe generar en dicho directorio un fichero JSON por cada usuario registrado cuyo nombre se compondrá del nombre y el apellido introducido en el formulario. 

Dentro del fichero se almacenará toda la información proveniente del formulario. 

Por último, se redirigirá nuevamente al formulario principal donde se mostrará un mensaje de si ha sido posible o no crear el archivo. 
(En la página 4 del documento se incluye una imagen del diseño del formulario: 
    "Formulario de Aficiones" con campos para nombre, apellidos, aficiones, inversión económica, horas dedicadas y opciones de compartir aficiones)

```php
if(!is_dir("./ficheros")){
    mkdir("./ficheros");
}

if (isset($_POST['Enviar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $nombreLimpio = str_replace(" ", "_", strtolower($nombre.$apellidos));

    $fichero = fopen("./ficheros/$nombreLimpio.json","w");
    $datos = [
        "nombre" => $nombre,
        "apellidos" => $apellidos,
        "aficion" => $_POST['aficion'] ?? '',
        "dinero" => $_POST['dinero'] ?? '',
        "horas" => $_POST['horas'] ?? '',
        "tiempo" => $_POST['tiempo'] ?? '',
        "compartir" => $_POST['compartir'] ?? ''
    ];
    $json = json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if(!$fichero){
        die("No se pudo abrir el archivo para escritura");
    }

    fwrite($fichero, $json);
    fclose($fichero);

    if(file_exists("./ficheros/$nombreLimpio.json")){
        header("Location: ejercicio3.html?ok=1");
        exit;
    }else {
        echo "No se ha podido crear el archivo";
    }

}
```

Partiendo del ejercicio 3 generar un script PHP que muestre los porcentajes según las respuestas del formulario de los diferentes usuarios. Para ello:
• Si no existe, se recorrerán los diferentes archivos del directorio donde se almacenen las respuestas de los usuarios almacenando sus valores.
• Se generará una función que realice los cálculos.
• Se generará una función que muestre los resultados.
• IMPORTANTE: El método scandir() devuelve un array con todos los ficheros del directorio que se le indique como parámetro. 
También incluye en el array “.” y “..” por lo que no son ficheros y pueden causar error

```php
$directorio = "../Ejercicio3/ficheros";

// 1️⃣ Comprobar si existe el directorio
if (!is_dir($directorio)) {
    echo "No hay datos todavía";
    exit;
}

// 2️⃣ Obtener solo los archivos JSON
$archivos = array_diff(scandir($directorio), ['.', '..']);

// 3️⃣ Función que realiza los cálculos
function calculos(array $archivos, string $directorio): array {

    $contadores = [
        "aficion" => [],
        "dinero" => [],
        "horas" => [],
        "tiempo" => [],
        "compartir" => []
    ];

    $totalUsuarios = 0;

    foreach ($archivos as $archivo) {

        $contenido = file_get_contents("$directorio/$archivo");
        $datos = json_decode($contenido, true);

        if (!$datos) {
            continue;
        }

        foreach ($contadores as $campo => &$contador) {
            $valor = $datos[$campo];

            if (isset($contador[$valor])) {
                $contador[$valor]++;
            } else {
                $contador[$valor] = 1;
            }
        }

        $totalUsuarios++;
    }

    // Calcular porcentajes
    $porcentajes = [];

    foreach ($contadores as $campo => $contador) {
        foreach ($contador as $opcion => $cantidad) {
            $porcentajes[$campo][$opcion] = ($cantidad / $totalUsuarios) * 100;
        }
    }

    return $porcentajes;
}

// 4️⃣ Función que muestra los resultados
function resultados(array $porcentajes): void {

    echo "<h1>Resultados de la encuesta</h1>";

    foreach ($porcentajes as $categoria => $valores) {

        echo "<h2>" . ucfirst($categoria) . "</h2>";

        foreach ($valores as $opcion => $porcentaje) {
            echo htmlspecialchars($opcion) . ": " . round($porcentaje, 2) . "%<br>";
        }
    }
}

// 5️⃣ Ejecutar todo
$porcentajes = calculos($archivos, $directorio);
resultados($porcentajes);
```

<?php
/*
Crear un archivo "conexion.php", que contendrá las constantes “HOST_DB”, “USER_DB”, “PASS_DB” y que se utilizan para realizar la conexión a la base de datos mysql que también
se realizará en este archivo almacenándose en la variable $conexion. 
 
Crear un archivo llamado “inicializar.php” que creará la base de datos ejercicios_ud4 y contendrá las 
tablas usuarios (nombre_usuario, email, contrasenya) y direcciones (id_direccion, calle, número, cp, ciudad, provincia, pais e id_usuario),. Además, la tabla usuarios se 
inicializará con 4 usuarios que tendrán una dirección cada uno. 

Crear un archivo index.php donde se utilizarán los archivos anteriores y se realizará una consulta “select” 
para obtener y mostrar los datos “nombre” y “email” de la tabla usuarios de la BD en una tabla HTML
*/
Conexion
```php
define ("HOST_DB", "localhost");
define("USER_DB", "root");
define ("PASS_DB", "");

$conexion = new mysqli(HOST_DB, USER_DB, PASS_DB);

if ($conexion->connect_errno){
    echo "Conexión fallida: " . $conexion->connect_error . "<br>";
    exit();
}
```

Index
```php
require_once "conexion.php";
$conexion->select_db("ejercicios_ud4");

$sql = "SELECT nombre_usuario, email FROM usuarios";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Usuarios</title>
</head>
<body>

<h1>Usuarios registrados</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Nombre</th>
        <th>Email</th>
    </tr>

    <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
            <td><?php echo $fila['nombre_usuario']; ?></td>
            <td><?php echo $fila['email']; ?></td>
        </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
```

Inicializar
```php
require_once "conexion.php";

// Crear BD
$conexion->query("CREATE DATABASE IF NOT EXISTS ejercicios_ud4");
$conexion->select_db("ejercicios_ud4");

// Tabla usuarios
$conexion->query("
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50),
    email VARCHAR(100),
    contrasenya VARCHAR(255)
)
");

// Tabla direcciones
$conexion->query("
CREATE TABLE IF NOT EXISTS direcciones (
    id_direccion INT AUTO_INCREMENT PRIMARY KEY,
    calle VARCHAR(100),
    numero VARCHAR(10),
    cp VARCHAR(10),
    ciudad VARCHAR(50),
    provincia VARCHAR(50),
    pais VARCHAR(50),
    id_usuario INT,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario)
)
");

// Usuarios
$pass1 = password_hash("1234", PASSWORD_DEFAULT);
$pass2 = password_hash("abcd", PASSWORD_DEFAULT);
$pass3 = password_hash("qwerty", PASSWORD_DEFAULT);
$pass4 = password_hash("pass", PASSWORD_DEFAULT);

$conexion->query("
INSERT INTO usuarios (nombre_usuario, email, contrasenya) VALUES
('ana', 'ana@email.com', '$pass1'),
('juan', 'juan@email.com', '$pass2'),
('laura', 'laura@email.com', '$pass3'),
('pedro', 'pedro@email.com', '$pass4')
");

// Direcciones
$conexion->query("
INSERT INTO direcciones (calle, numero, cp, ciudad, provincia, pais, id_usuario) VALUES
('Calle A', '1', '46001', 'Valencia', 'Valencia', 'España', 1),
('Calle B', '2', '46002', 'Valencia', 'Valencia', 'España', 2),
('Calle C', '3', '46003', 'Madrid', 'Madrid', 'España', 3),
('Calle D', '4', '28001', 'Sevilla', 'Sevilla', 'España', 4)
");

echo "Base de datos inicializada correctamente";

```

Ejercicio 6 

Dos botones o enlaces, de perfil y borrar usuario:

Desde index.php mandamos el id_usuario
<td>
    <a href="perfil.php?id=<?= $fila['id_usuario'] ?>">Ver perfil</a> |
    <a href="eliminar.php?id=<?= $fila['id_usuario'] ?>" 
       onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
       Eliminar
    </a>
</td>
y en perfil.php
<?php
require "conexion.php";

$id = $_GET['id'];

$usuario = $conexion->query("SELECT * FROM usuarios WHERE id_usuario=$id")->fetch_assoc();

$direcciones = $conexion->query("SELECT * FROM direcciones WHERE id_usuario=$id");
?>

<h2><?= $usuario['nombre_usuario'] ?> (<?= $usuario['email'] ?>)</h2>

<table border="1">
<tr>
    <th>Calle</th><th>Número</th><th>CP</th><th>Ciudad</th><th>Provincia</th><th>País</th>
</tr>

<?php while($d = $direcciones->fetch_assoc()): ?>
<tr>
<td><?= $d['calle'] ?></td>
<td><?= $d['numero'] ?></td>
<td><?= $d['cp'] ?></td>
<td><?= $d['ciudad'] ?></td>
<td><?= $d['provincia'] ?></td>
<td><?= $d['pais'] ?></td>
</tr>
<?php endwhile; ?>
</table>

<a href="index.php">Volver</a>

y en eliminar.php

<?php
require "conexion.php";

$id = $_GET['id'];

$conexion->query("DELETE FROM direcciones WHERE id_usuario=$id");
$conexion->query("DELETE FROM usuarios WHERE id_usuario=$id");

header("Location: index.php");


Ejercicio 7 - Editar direcciones

En perfil.php añadimos botón

<td>
<a href="editar.php?id=<?= $d['id_direccion'] ?>">Editar</a>
</td>

y en editar.php

<?php
require "conexion.php";

$id = $_GET['id'];

$dir = $conexion->query("SELECT * FROM direcciones WHERE id_direccion=$id")->fetch_assoc();
?>

<form action="procesar_editar.php" method="post">
<input type="hidden" name="id" value="<?= $id ?>">

Calle: <input type="text" name="calle" value="<?= $dir['calle'] ?>"><br>
Número: <input type="text" name="numero" value="<?= $dir['numero'] ?>"><br>
CP: <input type="text" name="cp" value="<?= $dir['cp'] ?>"><br>
Ciudad: <input type="text" name="ciudad" value="<?= $dir['ciudad'] ?>"><br>
Provincia: <input type="text" name="provincia" value="<?= $dir['provincia'] ?>"><br>
País: <input type="text" name="pais" value="<?= $dir['pais'] ?>"><br>

<input type="submit" value="Guardar">
</form>

y procesar_editar.php

<?php
require "conexion.php";

$id = $_POST['id'];

$calle = $_POST['calle'];
$numero = $_POST['numero'];
$cp = $_POST['cp'];
$ciudad = $_POST['ciudad'];
$provincia = $_POST['provincia'];
$pais = $_POST['pais'];

$conexion->query("
UPDATE direcciones SET
calle='$calle',
numero='$numero',
cp='$cp',
ciudad='$ciudad',
provincia='$provincia',
pais='$pais'
WHERE id_direccion=$id
");

header("Location: index.php");


Ejercicio 8 - MongoDB en PHP

mongo.php

<?php
require 'vendor/autoload.php';

$cliente = new MongoDB\Client("mongodb://localhost:27017");

$db = $cliente->ejercicio_mongo_ud4;
$coleccion = $db->usuarios;

/* Insertar usuarios */

$coleccion->insertOne([
    "nombre" => "Ana",
    "email" => "ana@email.com",
    "password" => md5("1234")
]);

$coleccion->insertOne([
    "nombre" => "Juan",
    "email" => "juan@email.com",
    "password" => md5("abcd"),
    "nacimiento" => 1998
]);

/* Mostrar todos */

$usuarios = $coleccion->find();

echo "<table border='1'>";
echo "<tr><th>Nombre</th><th>Email</th></tr>";

foreach ($usuarios as $u) {
    echo "<tr>";
    echo "<td>".$u['nombre']."</td>";
    echo "<td>".$u['email']."</td>";
    echo "</tr>";
}

echo "</table>";

/* Buscar por email */

$buscado = $coleccion->findOne(["email"=>"ana@email.com"]);

echo "<p>Usuario encontrado: ".$buscado['nombre']."</p>";


EJERCICIO 9 – Adaptar todo a MongoDB (idea clave)

Aquí ya no hay SQL. Usuarios + direcciones en un solo documento:

$coleccion->insertOne([
   "nombre"=>"Pedro",
   "email"=>"pedro@mail.com",
   "direcciones"=>[
        [
          "calle"=>"Mayor",
          "numero"=>3,
          "ciudad"=>"Madrid"
        ]
   ]
]);

Actualizar dirección:

$coleccion->updateOne(
 ["email"=>"pedro@mail.com"],
 ['$set'=>["direcciones.0.calle"=>"Nueva calle"]]
);

# Fin Tema 4 Ejercicios

# 📘 Tema 5 – Introducción a los Frameworks en PHP (Laravel)

## 1. Patrón MVC

MVC significa Modelo–Vista–Controlador.

### Modelo
Gestiona los datos y la lógica de negocio. Suele interactuar con la base de datos mediante ORM.

### Vista
Interfaz que ve el usuario. Muestra los datos y recibe acciones.

### Controlador
Intermediario entre vista y modelo. Procesa peticiones y prepara datos.

### Ventajas
- Separación de responsabilidades
- Código más limpio
- Escalabilidad
- Facilita testing

### Desventajas
- Curva de aprendizaje
- Más archivos

---

## 2. Frameworks

Un framework es un conjunto de herramientas que aceleran el desarrollo.

Ejemplos:
- Laravel
- Symfony
- CakePHP
- Zend

### Laravel

Framework PHP moderno:
- Backend potente
- Patrón MVC
- ORM Eloquent
- Composer
- Artisan

---

## Instalación

```bash
composer create-project laravel/laravel proyecto
```

---

## Estructura de proyecto

Carpetas principales:

- app/
- routes/
- resources/
- database/
- public/
- storage/
- config/

Archivo .env configura base de datos.

---

## Ejecutar servidor

```bash
php artisan serve
```

Acceso: http://127.0.0.1:8000

---

## 3. Rutas

Definidas en:

- web.php
- api.php

Ejemplo:

```php
Route::get('/', function() {
    return view('welcome');
});
```

### Rutas con nombre

```php
Route::get('/about', 'AboutController')->name('about');
```

### Rutas con variables

```php
Route::get('/user/{id}', function($id){});
```

### Validaciones

```php
where('id','[0-9]+');
```

---

## Paso de datos a vistas

```php
return view('welcome', compact('usuario'));
```

---

## 4. Vistas y Blade

Blade es el motor de plantillas de Laravel.

```php
{{ $variable }}
```

Evita inyección de código.

---

### Control de flujo

```php
@if()
@foreach()
@endif
```

---

## Reutilización

Includes:

```php
@include('partials.nav')
```

Layouts:

```php
@extends('layouts.base')
@section('content')
```

---

## Componentes

Uso de slots:

```php
<x-layout>
   <x-slot name="pie">Texto</x-slot>
</x-layout>
```

---

## 5. Controladores

Creación:

```bash
php artisan make:controller UserController
```

### Controlador único

Método __invoke()

### Controlador resource

Incluye:
- index
- create
- store
- show
- edit
- update
- destroy

```bash
php artisan make:controller UserController --resource
```

---

# FIN TEMA 5 Teoría
Ejercicio1
Crea un nuevo proyecto de Laravel con el nombre "enterprise_web_XXX" (reemplaza las XXX con el nombre del alumno).
• Define una ruta que muestre un mensaje de bienvenida (como ¡Bienvenido a la página principal!) 
en la página principal utilizando el controlador adecuado y la vista correspondiente.
• Crea una segunda ruta que presente un listado de productos con una tabla.
• Crea una tercera ruta que presente información sobre un producto ficticio utilizando un controlador y una vista separada.
• Asegúrate de que todas las rutas funcionen correctamente en un navegador.


Creamos el proyecto con `composer create-project laravel/laravel enterprise_web_juanjo`

Cambiamos en la view welcome todo por un ! en el que haya un h1 con el mensaje que queremos

Para la segunda ruta hacemos cd a la carpeta del proyecto y luego php artisan make:view productos para crear la view necesaria.
Tras esto, en routes/web.php hacemos una ruta simple nueva que lleve a /productos

Para la tercera ruta, utilizamos un controlador, así que lo creamos con solo invoke:
php artisan make:controller ProductController -i
Creo también a view item 
php artisan make:view item  
Y dentro del controller pongo en el invoke la llamada a la view item
Y dentro de rutas pongo la llamada al controlador.

Ejercicio2
Modifica el proyecto anterior añadiendo las nuevas funcionalidades.
A. Crear una vista principal:
• Crea una vista Blade llamada welcome.blade.php que servirá como la vista principal de la página de inicio.
• Diseña la estructura HTML básica de la página, que puede incluir la estructura del encabezado, navegación y pie de página.
• En la vista principal, utiliza la directiva Blade @include para incluir las vistas parciales que crearás a continuación.
B. Crear vistas parciales:
• Divide la página de inicio en varias secciones, como encabezado con un navegador (con los enlaces home y contacts), sección de productos destacados y sección de testimonios.
• Crea archivos Blade separados para cada sección (por ejemplo, header.blade.php, products.blade.php y testimonials.blade.php).
• Diseña y personaliza cada vista parcial de acuerdo con su contenido específico.
C. Incluir vistas parciales:
• En la vista principal (welcome.blade.php), utiliza la directiva @include para incluir las vistas parciales que has creado en las secciones correspondientes.
• Asegúrate de que las vistas parciales se muestren correctamente dentro de la vista principal.


Vale, pasamos directamente a Blade. el welcome.blade.php ya estaba ya que es el que se crea de base. Se crea la carpet partials y las 3 views pedidas. Se pone la estructura distribuida en esas views parciales. Se añade en el welcome.blade.php los @include de cada vista (@include('partials.header'))

Ejercicio3 

Modifica el proyecto anterior añadiendo las nuevas funcionalidades.
• Definir la ruta de producto: Crea una ruta que muestre información detallada sobre un producto específico. Por ejemplo, product/{id} podría ser una ruta que reciba un identificador de producto.

En routes/web.php lo definimos

```php
use App\Http\Controllers\ProductController;

Route::get('/productos', [ProductController::class, 'index']);
Route::get('/productos/{id}', [ProductController::class, 'detalle']);
```
/productos → muestra listado

/productos/{id} → recibe un identificador dinámico

{id} se pasa automáticamente al método detalle($id)


Antes hay que crear el controller: `php artisan make:controller ProductController`

y dentro del controller definir un array privado:

```php
private $productos = [
    [
        'id' => 1,
        'nombre' => 'Ratón inalámbrico',
        'descripcion' => 'Ratón ergonómico con batería recargable',
        'precio' => 12.99,
        'imagen' => 'https://via.placeholder.com/300',
        'info' => 'Conexión Bluetooth'
    ],
    ...
];
```
Esto simula una base de datos

```php
public function index()
{
    return view('productos', [
        'productos' => $this->productos
    ]);
}
```
El método index que envia los productos a la vista productos.blade.php

(Dentro de productos.blade.php se imprimen así:

    @foreach($productos as $producto)
        <h2>{{ $producto['nombre'] }}</h2>
        <a href="/productos/{{ $producto['id'] }}">
            Ver producto
        </a>
    @endforeach
)

```php
public function detalle($id)
{
    $producto = collect($this->productos)
                ->firstWhere('id', $id);

    return view('product', compact('producto'));
}
```
El método detalle que hace lo siguiente:

collect() convierte el array en colección

firstWhere('id', $id) busca el producto cuyo id coincide

Se envía a la vista product.blade.php

• Crear vista para producto: Crea una vista Blade llamada product.blade.php.
como siempre
• Mostrar detalles del producto: Utiliza Blade para mostrar los detalles del producto como:

    • Nombre del Producto: Incluye el nombre del producto de manera destacada en la vista. Esto podría ser un encabezado o título principal que muestre claramente qué producto se está viendo.

<h1>{{ $producto['nombre'] }}</h1>

    • Descripción del Producto: Proporciona una descripción detallada del producto que explique sus características, ventajas y cualquier información relevante para los compradores. Utiliza párrafos y formatos de texto para que sea fácil de leer.

<p>{{ $producto['descripcion'] }}</p>

    • Precio: Muestra el precio del producto de manera visible, preferiblemente junto a una etiqueta que indique claramente que se trata del precio. Puedes usar estilos para hacer que el precio destaque.

<h2>{{ $producto['precio'] }} €</h2>

    • Imágenes del Producto: Si es posible, muestra imágenes del producto para que los usuarios puedan verlo visualmente. Puedes incluir una galería de imágenes o una imagen principal del producto.

<img src="{{ $producto['imagen'] }}" width="250">

    • Botón de Compra o Acción: Agrega un botón o enlace que permita a los usuarios tomar una acción, como "Comprar" o "Agregar al carrito". Este botón debe ser fácil de encontrar y utilizar.

<button>Comprar</button>
o
<a href="#" class="btn">Comprar</a>

    • Información Adicional: Si el producto tiene características especiales, especificaciones técnicas o cualquier otro detalle importante, inclúyelo de manera organizada en la vista.

<p>{{ $producto['info'] }}</p>

    En total:
    <div>
        <h1>{{ $producto['nombre'] }}</h1>

        <img src="{{ $producto['imagen'] }}" width="250">

        <p>{{ $producto['descripcion'] }}</p>

        <h2>{{ $producto['precio'] }} €</h2>

        <p>{{ $producto['info'] }}</p>

        <button>Comprar</button>

        <br><br>
        <a href="/productos">Volver</a>
    </div>

• Enlace de ruta con vista: Enlaza la ruta del producto con la vista creada y asegúrate de que se muestre correctamente. Para ello, los productos mostrados en products.blade.php pueden tener un enlace con la ruta que va a la página de product.blade.php.

Route::get('/productos/{id}', [ProductController::class, 'detalle']);

Click en enlace
        ↓
/productos/2
        ↓
Route::get('/productos/{id}')
        ↓
detalle($id)
        ↓
return view('product')
        ↓
product.blade.php

OPCIONAL: Que cada producto carge distinta información.
Se podrían añadir diferentes productos en un array de objetos con la información del producto. Posteriormente cargar el producto en la vista según el id que se pase en la ruta.

private $productos = [
    [
        'id' => 1,
        'nombre' => 'Ratón inalámbrico',
        'descripcion' => 'Ratón ergonómico con batería recargable',
        'precio' => 12.99,
        'imagen' => 'https://via.placeholder.com/300',
        'info' => 'Conexión Bluetooth'
    ],
    [
        'id' => 2,
        'nombre' => 'Teclado mecánico',
        'descripcion' => 'Teclado con luces RGB',
        'precio' => 59.99,
        'imagen' => 'https://via.placeholder.com/300',
        'info' => 'Switches rojos'
    ],
    ...
];

y se carga con

private $productos = [
    [
        'id' => 1,
        'nombre' => 'Ratón inalámbrico',
        'descripcion' => 'Ratón ergonómico con batería recargable',
        'precio' => 12.99,
        'imagen' => 'https://via.placeholder.com/300',
        'info' => 'Conexión Bluetooth'
    ],
    [
        'id' => 2,
        'nombre' => 'Teclado mecánico',
        'descripcion' => 'Teclado con luces RGB',
        'precio' => 59.99,
        'imagen' => 'https://via.placeholder.com/300',
        'info' => 'Switches rojos'
    ],
    ...
];

Ejercicio4
Modifica el proyecto anterior añadiendo las nuevas funcionalidades.

• Utilizar directivas de control de Blade para comentarios dinámicos:

*Aquí ya lo tenemos hecho, ya que en los ejercicios anteriores todo lo hemos hecho con un @foreach estilo Blade. Del mismo modo, en el Home usamos los @include. Podriamos meter un par de @sections si fuese necesario o utilizar los @if en otros puntos.*

• Crea una vista Blade separada llamada product-testimonials.blade.php en la carpeta resources/views para mostrar los testimonios de usuarios.

*Vale, empezamos por hacer el típico `php artisan make:view product-testimonials`.*

• En product-testimonials.blade.php, utiliza directivas Blade como @foreach para iterar a través de un conjunto de testimonios. pueden ser generados de manera aleatoria a partir de un array de testimonios simulados y mostrar dos testimonios en cada visita.

*Vamos a meter los datos con directivas Blade. En otros ejercicios, teníamos un array de productos dentro de nuestro ProductController. Quizá podemos hacer lo mismo para testimonios. Estos testimonios los vamos a generar en un array en el controlador (ProductController) y en el partial de product-testimonials que acabamos de crear, iterarlos con un @foreach y mostrarlos en product.blade.php con un    `@include('partials.product-testimonials')`. Para que les llegue correctament todo tenemos que asegurarnos de que en el controller cogemos la información del array creado ahí mismo, pero al ser private, tenemos que pasarselo a la view en la función que llamaremos en la ruta*:
```php
    public function detalle($id)
    {
        $producto = collect($this->productos)
                    ->firstWhere('id', $id);

        $testimonials = collect($this->allTestimonials)
                    ->shuffle()
                    ->take(2);


        return view('product', compact('producto','testimonials'));
    }
```
*Es importante ver que lo que hemos hecho es un shuffle y un take 2 porque quieren que mostremos dos testimonios aleatorios en cada visita.*

• En la vista principal del producto (product.blade.php), utiliza la directiva Blade @component para incluir la vista de testimonios que creaste anteriormente (product-testimonials.blade.php).

Cambiamos el `@include('partials.product-testimonials')`
Por     
```php
@component('partials.product-testimonials', ['testimonials' => $testimonials])
@endcomponent
```
*Hace lo mismo practicamente, pero include es más bien un copiar-pegar de HTML Blade, mientras que component es una pieza con datos propios, es una mini vista con variables propias vamos, como un componente de frontend.*

• Donde $testimonials será la variable que contendrá los testimonios dinámicos.

*Eso ya lo hicimos*

• Asegúrate de que los testimonios se muestren de manera coherente y formen parte de la página de producto avanzada.

*Hecho*

• Agregar estilos CSS personalizados para mejorar la presentación de la página. Esto incluye estilos para los testimonios, y cualquier otro elemento que desees destacar.

*Pasando*

Ejercicio5 

Modifica el proyecto anterior añadiendo las nuevas funcionalidades.
• Define una nueva ruta que muestre una página de contacto e incluirla en la cabecera (header.blade.php).
```php
Route::get('/contacto', function () {
    return view('contact');
});
```
*Y lo metemos en el partial de header para que enlace con un <a>*

• Crea una vista Blade llamada contact.blade.php.
`php artisan make:view contact`

• Diseña un formulario de contacto simple con campos como nombre, correo electrónico y mensaje.

*Diseñado*

• Utiliza Blade para crear el formulario y mostrar mensajes de validación si es necesario.

*Hacemos el array de campos y luego con un foreach lo recorremos. Miramos errores con el @error:*

```php
@php
$campos = [
    ['name' => 'nombre', 'type' => 'text', 'label' => 'Nombre'],
    ['name' => 'email', 'type' => 'email', 'label' => 'Correo electrónico'],
];
@endphp


<form method="POST" action="/contact">
    @csrf

    @foreach($campos as $campo)
        <div>
            <label>{{ $campo['label'] }}</label>
            <input 
                type="{{ $campo['type'] }}" 
                name="{{ $campo['name'] }}" 
                value="{{ old($campo['name']) }}"
            >

            @error($campo['name'])
                <p style="color:red">{{ $message }}</p>
            @enderror
        </div>
    @endforeach

    <div>
        <label>Mensaje</label>
        <textarea name="mensaje">{{ old('mensaje') }}</textarea>

        @error('mensaje')
            <p style="color:red">{{ $message }}</p>
        @enderror
    </div>

    <button>Enviar</button>
</form>
```

• El formulario ha de ser con elementos dinámicos (usa arrays para almacenar los campos y generalo con directivas Blade).

*Lo hecho en el punto anterior*

# FIN TEMA 5 Ejercicios

# 📘 Tema 6 – Gestión de datos con Frameworks en PHP (Laravel)

## 1. Bases de datos desde Laravel

Laravel permite trabajar con múltiples bases de datos usando Query Builder y ORM Eloquent.

Configuración principal en:
config/database.php

Variables en .env:
DB_CONNECTION
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD

---

## Conexiones adicionales (MongoDB)

Instalación:

composer require jenssegers/mongodb

Configuración en database.php dentro de connections.

---

## 2. Migraciones

Permiten crear y modificar tablas.

Crear:

php artisan make:migration create_customers_table

Ejecutar:

php artisan migrate

Rollback:

php artisan migrate:rollback

Refresh:

php artisan migrate:refresh

Estructura típica:

Schema::create('customers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});

---

## 3. ORM Eloquent

Cada tabla se asocia a un modelo.

Crear modelo:

php artisan make:model Customer

Eloquent usa pluralización automática.

---

## Relaciones

### Uno a uno

hasOne() / belongsTo()

### Uno a muchos

hasMany()

### Muchos a muchos

belongsToMany() usando tabla pivote

### Polimórficas

morphOne(), morphMany(), morphTo()

---

## 4. Consultas

Query Builder:

DB::table('customers')->get();

Colecciones Eloquent permiten:

first()
where()
pluck()
orderBy()
count()
sum()

---

## 5. Crear registros

$customer = new Customer();
$customer->name = "Juan";
$customer->save();

O:

Customer::create($request->all());

---

## Asignación masiva

En modelo:

protected $fillable = ['name','email'];

---

## 6. Formularios

Validación:

$request->validate([
 'name'=>'required',
 'email'=>'email'
]);

Mostrar errores con $errors

---

## 7. Seeders

Crear:

php artisan make:seeder CustomerSeeder

Ejecutar:

php artisan db:seed

Sirven para poblar la base de datos.

---

# FIN TEMA 6 Teoría


Crear un proyecto de Laravel llamado college_XXX (las XXX hacen referencia al nombre del alumno) e instala Bootstrap 5. 
Este se debe configurar con una base de datos MySQL y tendrá una base de datos llamada college.

*Creamos el proyecto con `composer create-project laravel/laravel college_Juanjo`*
*Si hacemos npm install bootstrap no se exactamente cómo eso luego existe, se que aparece en resources js app o cdss app pero no se cómo insertarlo. Podemos copiar en el CDN lo que sale en la docu oficial y ya.*
*Para la base de datos activamos en XAMPP tanto apache como mysql y le damos a admin para ir a localhost/phpMyAdmin y ahí crear la BBDD College. Tras esto tenemos que ir a .env y cambiar los datos de conexión a los siguientes:*
DB_CONNECTION=MySQL
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=college
DB_USERNAME=root
DB_PASSWORD= 

Realiza una migración (migration) para mostrar los cursos(courses) que se imparten en el instituto. 
Este contendrá un identificador que serán 3 letras, un nombre, número de horas, un campo verdadero o falso para indicar si hay que realizar prácticas de empresa además añade los timestamps.

*Lo primero que hacemos es crear la migración con `php artisan make:migration create_courses_table`*
*Dentro de esta tenemos que añadir lo que nos han pedido de la siguiente manera:*
```php
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->char('identificador', 3)->primary();
            $table->string('nombre');
            $table->integer('numero_horas');
            $table->boolean('practicas_empresa');
            $table->timestamps();
        });
    }
```
Realiza un semillero (seed) para llenar los cursos que pertenecen a la FP de Informática (del grado básico, medio y superior).

*Primero lo creamos con `php artisan make:seeder CoursesTableSeeder`*
*Añadimos después en dicho seeder lo necesario:*
*La importación del modelo y los datos en la función*
```php
use App\Models\Course;

    public function run()    {
        $curso = new Course();
        $curso->identificador = "DAW";
        $curso->nombre = "Desarrollo de Aplicaciones Web";
        $curso->numero_horas = 2000;
        $curso->practicas_empresa = true;
        $curso->save();

        $curso = new Course();
        $curso->identificador = "DAM";
        $curso->nombre = "Desarrollo de Aplicaciones Multiplataforma";
        $curso->numero_horas = 2000;
        $curso->practicas_empresa = true;
        $curso->save();

        $curso = new Course();
        $curso->identificador = "ASIR";
        $curso->nombre = "Administración de Sistemas Informáticos en Red";
        $curso->numero_horas = 2000;
        $curso->practicas_empresa = true;
        $curso->save();
    }
```

Realiza un modelo para los cursos que sea rellenable de forma masiva.

*Primero lo creamos con `php artisan make:model Course`*
*Luego le añadimos todos los detalles que nos piden*
```php
class Course extends Model
{
    protected $primaryKey = 'identificador';
    protected $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'identificador',
        'nombre',
        'numero_horas',
        'practicas_empresa'
    ];
}
```
*Detalles a mencionar, tenemos que aclarar que identificador, nuestro "DAW" es la primary key. También tenemos que indicar que no es incremental (Que suele pasar con id's numéricos) y que es de tipo string, además de incluirlo en los fillable.*

Realiza una página Blade inicio.blade.php que muestre una cabecera como un carrusel y justo abajo se muestre un menú con enlaces a los cursos que se imparten.

*Perfecto, se crea con el habitual `php artisan make:view inicio`*
*Tras esto, dentro de esa view ponemos las opciones de Bootstrap 5 en el CDN (en el header el link al css y al final del body antes de cerrarlo el JS*
*Tras esto ponemos ya el código con los recursos sacados de la doc oficial*
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- CABECERA: CAROUSEL -->
    <div id="carouselCursos" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/1200x400" class="d-block w-100" alt="FP Informática">
                <div class="carousel-caption d-none d-md-block">
                    <h5>FP Informática</h5>
                    <p>Formación profesional de calidad</p>
                </div>
            </div>

            <div class="carousel-item">
                <img src="https://via.placeholder.com/1200x400" class="d-block w-100" alt="Grado Medio">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Grado Medio</h5>
                    <p>Sistemas y redes</p>
                </div>
            </div>

            <div class="carousel-item">
                <img src="https://via.placeholder.com/1200x400" class="d-block w-100" alt="Grado Superior">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Grado Superior</h5>
                    <p>Desarrollo de aplicaciones</p>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#carouselCursos" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselCursos" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- MENÚ DE CURSOS -->
    <div class="container mt-4">
        <h2 class="mb-3">Cursos que se imparten</h2>

        <div class="list-group">
            @foreach ($courses as $course)
                <a href="#" class="list-group-item list-group-item-action">
                    {{ $course->nombre }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


Realiza un controlador CourseController de una única función que obtenga los cursos de la base de datos y los envíe a la página inicio.blade.php.

*php artisan make:controller CourseController -i porque nos piden de una única función, así la hacemos con invoke*
*En dicho controller tenemos que llamar al modelo y obtener los datos de la BBDD con Eloquent metiendolos en la variables courses y luego pasandosela a la vista con compact*
<?php
```php
namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __invoke(Request $request)
    {
        $courses = Course::all();

        return view('inicio', compact('courses'));
    }
}
```



Crea la ruta necesaria o utiliza la principal para llamar al método anterior y cargar la vista.

*
<?php
```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/inicio', CourseController::class);
```
*


El siguiente paso es hacerlo funcionar. Ya tenemos el proyecto creado, la base de datos vacía creada (college). Nos falta crear las tablas, meter los datos y verlos en la web. Primero configuramos en .env bien, que ya hicimos. MySQL tiene que estar arrancado. 

Ejecutamos las migraciones: `php artisan migrate`

Registramos el seeder en database/seeders/DatabaseSeeder.php con `$this->call(CourseTableSeeder::class);`

Ahora ejecutamos los seeders `php artisan db:seed`. Aunque como lo hemos preparado todo, lo mejor sería hacerlo a la vez el migrate y el seed con `php artisan migrate --seed`

y lo probamos en el navegador `php artisan serve`

Ejercicio2

Modifica el proyecto anterior añadiendo las nuevas funcionalidades.

Realiza una migración (migration) para mostrar las asignaturas (subjects) que se imparten en cada curso. Este contendrá un identificador
que serán 4 letras, un nombre, número de horas, un campo nivel que puede ser nulo para indicar en que año se imparte además añade los 
timestamps.

*Empezamos con un `php artisan make:migration create_subjects_table`*
*Dentro queremos identificador de 4 letras, nombre, numero de horas, y nivel que puede ser null para indicar en qué año se imparte, ademas de timestamps*
```php
        Schema::create('courses', function (Blueprint $table) {
            $table->char('identificador', 3)->primary();
            $table->string('nombre');
            $table->integer('numero_horas');
            $table->boolean('practicas_empresa');
            $table->timestamps();
        });
```

Crea una migración (migration) para editar la tabla asignaturas añadiendo un campo que sea clave ajena del campo identificador de curso

*Empezamos con un `php artisan make:migration update`*
*Y dentro de esta lo ponemos así*
*Tenemos que indicar que el identificador no es numerico y luego que es foreign de course. Los foreing siempre se indican en la relación en el muchos*
<?php
```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subjects', function (Blueprint $table) {

            $table->char('course_identificador', 3);

            $table->foreign('course_identificador')
                  ->references('identificador')
                  ->on('courses')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['course_identificador']);
            $table->dropColumn('course_identificador');
        });
    }
};
```

Realiza un semillero (seed) para llenar las asignaturas que pertenecen a cada curso de la FP de Informática (del grado básico, medio y superior).

*Empezamos con un `php artisan make:seeder SubjectsTableSeeder`*
*Para utilizarlo correctamente lo mejor será crear también el modelo Subject añadiendole el 'course_identificador'*

<?php
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $primaryKey = 'identificador';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'identificador',
        'course_identificador',
        'nombre',
        'numero_horas',
        'nivel'
    ];
}
```
y en el SubjectsTableSeeder ponerlo también todo:

<?php
```php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DAW
        Subject::create([
            'identificador' => 'DWES',
            'nombre' => 'Desarrollo Web Entorno Servidor',
            'numero_horas' => 300,
            'nivel' => 2,
            'course_identificador' => 'DAW'
        ]);

        Subject::create([
            'identificador' => 'DWEC',
            'nombre' => 'Desarrollo Web Entorno Cliente',
            'numero_horas' => 250,
            'nivel' => 2,
            'course_identificador' => 'DAW'
        ]);

        // DAM
        Subject::create([
            'identificador' => 'PMDM',
            'nombre' => 'Programación Multimedia y Dispositivos Móviles',
            'numero_horas' => 200,
            'nivel' => 2,
            'course_identificador' => 'DAM'
        ]);

        Subject::create([
            'identificador' => 'ADAT',
            'nombre' => 'Acceso a Datos',
            'numero_horas' => 180,
            'nivel' => 2,
            'course_identificador' => 'DAM'
        ]);

        // ASI
        Subject::create([
            'identificador' => 'SINF',
            'nombre' => 'Sistemas Informáticos',
            'numero_horas' => 200,
            'nivel' => 1,
            'course_identificador' => 'ASI'
        ]);

        Subject::create([
            'identificador' => 'REDI',
            'nombre' => 'Redes e Infraestructuras',
            'numero_horas' => 220,
            'nivel' => 1,
            'course_identificador' => 'ASI'
        ]);
    }

}
```


Realiza un modelo para las asignaturas que use el SoftDelete.

*Ya habiamos creado el modelo, pero aseguremonos del SoftDelete. Tenemos que usar $table->softDeletes(); en la migración y en el modelo use SoftDeletes; dentro de la clase y llamar con el use con use Illuminate\Database\Eloquent\SoftDeletes;*


Crea una relación entre los modelos en la que un curso puede tener muchas asignaturas, pero una asignatura solo pertenece a un curso.

*Vale, tanto en el modelo de cursos como en el modelo de subject debemos ponerlo, en cada uno a su manera "hasMany" y "belongsTo"*

```php
    public function courseSubjects(){
        return $this->hasMany(Subject::class, 'course_identificador', 'identificador');
    }
```
Hay que tener en cuenta también que al no ser un id normal, tenemos que aclarar el identificador


Modifica el controlador CourseController para que su función obtenga los cursos y las asignaturas de la base de datos por medio de su relación y los envíe a la página inicio.blade.php.

*La clave aquí es pasarle el with*
$courses = Course::with('subjects')->get();


Modifica la página Blade inicio.blade.php que muestre las asignaturas como un submenú al situarse encima de un curso.

*Tenemos que poner también en la view el foreach para imprimirlos*
```php
@foreach($courses as $course)
    {{ $course->nombre }}

    @foreach($course->subjects as $subject)
        {{ $subject->nombre }}
    @endforeach
@endforeach
```

Ahora para probarlo, como hemos cambiado cosas hacemos un:

`php artisan migrate:fresh --seed`

Borrando tablas, recreando migraciones y metiendo cursos+asignaturas.

Ejercicio3 

Modifica el proyecto anterior añadiendo las nuevas funcionalidades.

Realiza una migración (migration) para mostrar los alumnos (students) que en cada curso. 
Este contendrá un identificador que será su DNI, un nombre, apellidos, email, teléfono y añade los timestamps.

Partimos de lo de siempre `php artisan make:migration create_students_table`
```php
        Schema::create('students', function (Blueprint $table) {
            $table->string('dni', 8)->primary();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('email');
            $table->string('telefono', 9); 
            $table->timestamps();
            $table->softDeletes();
        });
```
Realiza una factoría (Factory) para llenar 20 alumnos automáticamente.

*Antes tenemos que hacer el modelo del Student `php artisan make:model Student`

<?php
```php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'dni';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'dni',
        'nombre',
        'apellidos',
        'email',
        'telefono'
    ];
}
```
*Para crear una factory hacemos `php artisan make:factory StudentFactory --model=Student`*

<?php
```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni' => fake()->unique()->numerify('########'),
            'nombre' => fake()->name(),
            'apellidos' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'telefono' => fake()->numerify('#########'),
        ];
    }
}
```

Realiza un modelo para las los alumnos que use el SoftDelete.

*Hecho y apuntado antes*

Crea una relación entre los modelos alumno y asignatura en la que un alumno puede tener muchas asignaturas, 
y una asignatura puede cursarse por muchos alumnos. Crea todo lo que sea necesario para que funcione la relación.

*Aquí tenemos mucho que hacer. Identificamos claramente que es una relación Many to Many, por lo que en esas relaciones SIEMPRE se necesita una tabla pivote. La creamos mediante migration y en ella ponemos los foreign key de ambas, al igual que nos aseguramos que sea unique o se podrían repetir infinitamente la misma relación 'Juanjo_Daw*
```php
        Schema::create('student_subject', function (Blueprint $table) {
            $table->char('subject_identificador', 3);

            $table->foreign('subject_identificador')
                  ->references('identificador')
                  ->on('subjects')
                  ->onDelete('cascade');
            
            $table->char('student_dni', 8);

            $table->foreign('student_dni')
                  ->references('dni')
                  ->on('students')
                  ->onDelete('cascade');

            $table->unique(['student_dni', 'subject_identificador']);

            $table->timestamps();
        });
```
Luego en cada modelo tendremos que poner su propia relación, teniendo en cuenta que siempre es primero la tabla pivote, luego la foreig key del modelo actual y luego la foreing key del modelo relacionado:

Subject:
```php
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'student_subject',
            'subject_identificador',
            'student_dni'
            );
    }
```

Student:
```php
        public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(
            Subject::class, 
            'student_subject',
            'student_dni',
            'subject_identificador' 
            );
    }
```

Realiza un controlador SubjectController que sea un controlador de recursos. Y crea las rutas para este si es necesario.

*Al ser ya uno de recursos (es decir, CRUD y demás) lo creamos indicandolo `php artisan make:controller SubjectController --resource`*
*La ruta necesaria sería en web poner Route::resource('subjects', SubjectController::class); e importarlo con use App\Http\Controllers\SubjectController;*

Implementa la función show() de SubjectController para que obtenga la asignatura con el identificador entrante y utilizar la relación 
para recuperar de la base de datos los alumnos que estén matriculados en dicha asignatura y los envíe a la página show.blade.php dentro
del directorio views/subjects.

*Vale, dentro del SubjectController solo nos interesa la funcion show, por lo que la completamos*
```php
    public function show(Subject $subject)
    {
        $students = $subject->students;

        return view('subjects.show', compact('subject', 'students'));
    }
```

Realiza la página Blade show.blade.php de subject que muestre un listado con los detalles de la asignatura y una tabla con todos los
alumnos registrados en ella.

*Creamos la view `php artisan make:view show` y en ella ponemos lo necesario*

<h2>{{ $subject->nombre }}</h2>

<p><strong>Identificador:</strong> {{ $subject->identificador }}</p>
<p><strong>Horas:</strong> {{ $subject->numero_horas }}</p>
<p><strong>Nivel:</strong> {{ $subject->nivel }}</p>

<h3>Alumnos matriculados</h3>

@if($students->isEmpty())
    <p>No hay alumnos matriculados en esta asignatura.</p>
@else
    <ul>
        @foreach($students as $student)
            <li>
                {{ $student->nombre }} {{ $student->apellidos }} — {{ $student->email }}
            </li>
        @endforeach
    </ul>
@endif



Modifica la página Blade inicio.blade.php que al pulsar en una asignatura del submenú, llame a la ruta que carga el método show() de SubjectController.

*    <!-- MENÚ DE CURSOS -->
    <div class="container mt-4">
        <h2 class="mb-3">Cursos que se imparten</h2>

        <div class="list-group">
            @foreach($courses as $course)
                <h5 class="mt-3">{{ $course->nombre }}</h5>

                @foreach($course->courseSubjects as $subject)
                    <a 
                        href="{{ route('subjects.show', $subject->identificador) }}" 
                        class="list-group-item list-group-item-action"
                    >
                        {{ $subject->nombre }}
                    </a>
                @endforeach

            @endforeach

        </div>

    </div>*


Vale, vamos a probarlo con php artisan migrate:fresh y luego php artisan db:seed

Nos hemos dado cuenta de que no habíamos llamado al factory de students en seeders, hemos tenido que crear un seeder nuevo y poner que llamamos a factory, al igual que indicar en el modelo de student el hasFactory. También hemos tenido que hacer en dicho seeder un enlace automático con atach() a asignaturas.



Ejercicio1P2 TODOList

Crear un proyecto de Laravel llamado relaciones_laravel_XXX (las XXX hacen referencia al nombre del alumno). 
Este se debe configurar con una base de datos MYSQL que generará una base de datos llamada relaciones_eloquent.

*Empezamos creando la BBDD como ya sabemos, `composer create-project laravel/laravel relaciones_laravel_juanjo`*

*Ahora cambiamos en .env para que se configure con base de datos mysql y conectando a esa BBDD. Recordemos que para conectar a MySQL hay que ponerlo en minúsculas, que ya cometí ese error antes*

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=relaciones_eloquent
DB_USERNAME=root
DB_PASSWORD=

Realiza las migraciones y modelos (añadiendo las relaciones necesarias) para implementar el siguiente ejemplo:

![alt text](image.png)

ACLARACIONES:
- No es necesario crear relaciones tienen uno (HOT) y tiene muchos (HMT)
- Los atributos de cada migración serán id, nombre y los timestamps.
- Si las tablas y atributos se ponen en inglés, aprovechando la generación automática de Laravel, hay que ir con cuidado con la tabla “migrations”, ya que este es el mismo nombre que la tabla que Laravel genera automáticamente. Por este motivo la tabla que se cree se llamará “schemes”.
- Los colores son una ayuda para identificar el tipo de relación.

Ejercicio1P2Ampliacion TODOList

Ejercicio2P2

Crear un proyecto de Laravel llamado formula_one_XXX (las XXX hacen referencia al nombre del alumno). Este se debe configurar con una base de datos MYSQL que generará una base de datos llamada formula_one.

*Empezamos creando la BBDD como ya sabemos, `composer create-project laravel/laravel formula_one_juanjo` y cambiamos el .env*

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=formula_one
DB_USERNAME=root
DB_PASSWORD=

Realiza las migraciones, modelos (añadiendo las relaciones necesarias), los semilleros (al menos 3 de ellos) y factorías para implementar el siguiente ejemplo:

![alt text](image.png)

*Analizamos todo lo indicado en el diagrama*

Country–GrandPrix: 

Country hasMany Circuits
Circuit belongsTo Country
Circuit hasMany GrandPrix
GrandPrix belongsTo Circuit

Circuit–GrandPrix: En Circuit has many GrandPrix (hasMany(GrandPrix::class) 

En GrandPrix belongs to Circuit (belongsTo(Circuit::class) 

Team–Car: En Team has many Cars (hasMany(Car::class) 

En Car belongs to Team (belongsTo(Team::class) 

Tire–Car: 
En Tire has Many Cars (hasMany(Car::class) 
En Car belongsTo Tire (belongsTo(Tire::class) 

Car–Engine: Many to Many, osea, 
relación pivote car_engine con car_id y engine_id 
En ambos modelos belongsToMany() 

Car–GrandPrix: Otra Many to Many, osea que relación pivote car_grand_prix con car_id, grand_prix_id pero también car_num, race_date, laps. En los modelos se usa belongsToMany() withPivot() 

Driver–GrandPrix: Otra Many to Many, 
tabla pivote driver_grand_prix que tenga 
driver_id 
grand_prix_id 
position 
race_type 
best_training_time 
y en ambos modelos belongsToMany()withPivot() 

La tabla Cars tendrá columna team_id (foreignId) tire_id (foreignId)

*Vale, empezamos por modelos y migraciones: Tenemos que añadir*


ACLARACIONES:
- El diagrama anterior referencia a los modelos no a las migraciones. Cuidado con los nombres.
- Es necesario crear relaciones tienen uno (HOT) o tiene muchos (HMT) desde País-GranPremio y desde Neumaticos-Monoplaza
- Las tablas y atributos deben ponerse en inglés, para aprovechar la generación automática de Laravel.
- Los semilleros pueden proporcionar datos obtenidos en la web Oficial de la Formula 1.
- Los modelos sin atributos tendrán como mínimo, id, nombre y los timestamps.
- Las relaciones con columnas adicionales en las tablas pivote obtendrán dichas columnas.

EjercicioTesting

El objetivo de este ejercicio es consolidar los conocimientos adquiridos en el desarrollo de un proyecto en Laravel, enfocándose en la creación de pruebas para garantizar la calidad y el correcto funcionamiento de las diferentes funcionalidades implementadas.

Modifica el proyecto Proyecto College_XXX añadiendo las nuevas pruebas.

*En cada test usaremos la misma estructura base, los crearemos con 
php artisan make:test CourseTest --unit 
(el --unit solo para los unitarios, claro)

(Para acelerar, podemos hacer todos los test ya:
php artisan make:test CourseTest --unit
php artisan make:test CourseControllerTest
php artisan make:test DatabaseTest
php artisan make:test RouteTest
php artisan make:test ViewTest)

y luego pondremos lo siguiente en cada uno de ellos 

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Course;

y dentro de la clase

use RefreshDatabase;*

**Pruebas Unitarias**:

    Crear pruebas unitarias para los modelos Course, Subject, y Student.

    Verificar que los modelos permitan la asignación masiva de datos de manera segura.

    Asegurarse de que la eliminación de registros se realice correctamente, incluyendo el uso de SoftDelete cuando sea aplicable.

*Para course ponemos lo siguiente, primero comprobamos el fillable*
public function test_course_allows_mass_assignment()
{
    $course = Course::create([
        'identificador' => 'DAM',
        'nombre' => 'Desarrollo de Aplicaciones',
        'numero_horas' => 2000,
        'practicas_empresa' => true,
    ]);

    $this->assertDatabaseHas('courses', [
        'identificador' => 'DAM',
    ]);
}

*y luego el SoftDelete (o Delete en su defecto si no usa Soft, pero tendríamos que cmabiarlo)*
    public function test_course_can_be_deleted()
    {
        $course = Course::factory()->create();

        $course->delete();

        $this->assertSoftDeleted('courses', [
            'identificador' => $course->identificador
        ]);


    }


**Pruebas de Funcionalidad para Controladores**:

    Implementar pruebas para el controlador CourseController.

    public function test_inicio_view_receives_courses()
    {
        Course::factory()->count(3)->create();

        $response = $this->get('/inicio');

        $response->assertStatus(200);
        $response->assertViewHas('courses');
    }

    Verificar que la función obtenga correctamente los cursos de la base de datos y los envíe a la vista inicio.blade.php.

    Asegurarse de que la relación entre cursos y asignaturas se maneje adecuadamente.

**Pruebas de Base de Datos**:

    Realizar pruebas de base de datos para las migraciones de Course, Subject, y Student.

    public function test_subject_belongs_to_course()
    {
        $course = Course::factory()->create();
        $subject = Subject::factory()->create([
            'course_identificador' => $course->identificador
        ]);

        $this->assertEquals($course->identificador, $subject->course->identificador);
    }

    Verificar que los datos se almacenen correctamente en la base de datos.

    Asegurarse de que las relaciones entre tablas funcionen según lo esperado.
    public function test_students_can_be_attached_to_subjects()
    {
        $subject = Subject::factory()->create();
        $students = Student::factory()->count(3)->create();

        $subject->students()->attach(
            $students->pluck('dni')->toArray()
        );

        $this->assertCount(3, $subject->students);
    }
    Asegurarse de que la factoría (Factory) esté creando correctamente los alumnos automáticamente.

    public function test_students_factory_creates_students()
    {
        Student::factory()->count(5)->create();

        $this->assertDatabaseCount('students', 5);
    }

**Pruebas de Rutas**:

    Implementar pruebas para las rutas del proyecto.

    public function test_inicio_route_exists()
    {
        Course::factory()->count(2)->create();

        $response = $this->get('/inicio');

        $response->assertStatus(200);
    }

    Verificar que las rutas estén configuradas correctamente y redirijan según lo esperado.

    public function test_invalid_route_returns_404()
    {
        $response = $this->get('/no-existe');

        $response->assertStatus(404);
    }

**Pruebas de Vistas**:

    Crear pruebas para las vistas inicio.blade.php y show.blade.php.

    public function test_inicio_view_displays_courses_and_subjects()
    {
        $course = Course::factory()->create(['nombre' => 'DAM']);
        $subject = Subject::factory()->create([
            'course_identificador' => $course->identificador,
            'nombre' => 'Programacion'
        ]);

        $response = $this->get('/inicio');

        $response->assertSee('DAM');
        $response->assertSee('Programacion');
    }

    Verificar que las vistas muestren la información correctamente.

    public function test_show_view_displays_subject_and_students()
    {
        $subject = Subject::factory()->create(['nombre' => 'Bases de Datos']);
        $students = Student::factory()->count(2)->create();

        $subject->students()->attach(
            $students->pluck('dni')->toArray()
        );

        $response = $this->get('/subjects/'.$subject->identificador);

        $response->assertSee('Bases de Datos');

        foreach ($students as $student) {
            $response->assertSee($student->nombre);
        }
    }

    Asegurarse de que al interactuar con la vista se realicen las acciones esperadas.

    $response->assertSee(
        route('subjects.show', $subject->identificador)
    );


# FIN TEMA 6 Ejercicios

# 📘 Tema 7 -- Autenticación, Middleware y Servicios Web en Laravel

## 1. Autenticación y autorización

Laravel incluye herramientas para: - Registro de usuarios - Login /
logout - Recuperación de contraseñas - Gestión de sesiones

### Cookies y sesiones

Tipos:

#### Autenticación basada en sesiones

El servidor guarda la sesión y el navegador mantiene una cookie con el
ID.

#### Autenticación basada en tokens

Se usa un token único para cada petición (APIs).

Laravel soporta librerías como: - Passport (OAuth2) - Sanctum -
Socialite (Google, Facebook, Twitter) - JWT - Spatie roles/permisos

### Módulos de autenticación

Basados en:

-   Guards → cómo se autentica el usuario
-   Providers → de dónde salen los datos del usuario

Configurados en:

config/auth.php

------------------------------------------------------------------------

## Configuración básica en Laravel

1.  Crear modelo User con migración
2.  Editar migración (name, email, password, remember_token)
3.  Configurar .env con base de datos
4.  Ejecutar:

php artisan migrate

------------------------------------------------------------------------

## Registro y Login

### Rutas

GET / → login\
GET /register → registro\
POST /login\
POST /register

### Controlador AuthController

Incluye métodos: - register() - login() - logout()

Validaciones con Request y Validator.

Auth::attempt() para login.\
Auth::logout() para cerrar sesión.

------------------------------------------------------------------------

## Formularios Blade

### Registro

Campos: - name - email - password - password_confirmation

### Login

Campos: - email - password

Incluye @csrf y mensajes de error.

------------------------------------------------------------------------

# 2. Middleware

Los middleware se ejecutan entre la petición HTTP y el controlador.

Sirven para: - Autenticación - Filtros - Seguridad - Control de acceso

### Crear middleware

php artisan make:middleware NombreMiddleware

Se crean en:

app/Http/Middleware

### Registro

En:

app/Http/Kernel.php

Tipos:

-   \$middleware → global
-   \$middlewareGroups → grupos
-   \$middlewareAliases → alias

------------------------------------------------------------------------

## Uso en rutas

Individual:

Route::get('/profile')-\>middleware('auth');

Grupo:

Route::middleware('auth')-\>group(function(){ Route::get('/dashboard',
...); });

------------------------------------------------------------------------

# 3. Servicios en Laravel

Clases que encapsulan lógica específica:

Ejemplos: - Envío de emails - Pagos - Archivos - APIs externas

Ubicación típica:

app/Services

------------------------------------------------------------------------

## Service Providers

Registran servicios en el contenedor.

Se crean en:

app/Providers

Método register(): registra servicios\
Método boot(): configuración

Uso común:

\$this-\>app-\>bind()\
\$this-\>app-\>singleton()

------------------------------------------------------------------------

## Inyección de dependencias

Laravel inyecta servicios automáticamente en constructores.

Ejemplo:

public function \_\_construct(FileService \$fileService)

------------------------------------------------------------------------

# 4. Helpers y Librerías

Helpers útiles:

-   str_random()
-   route()
-   view()
-   redirect()
-   asset()
-   dd()

### Redirect helpers

redirect()-\>route()\
redirect()-\>back()\
with(), withErrors()

Mensajes flash: - success - error - warning - info

------------------------------------------------------------------------

# Librerías

Se instalan con Composer:

composer require nombre

Fuentes: - Packagist - GitHub - Laravel packages

------------------------------------------------------------------------

# 5. Servicios Web y APIs

### Qué es una API

Interfaz para comunicar sistemas vía HTTP.

Usa JSON.

Métodos: - GET - POST - PUT/PATCH - DELETE

------------------------------------------------------------------------

## API REST en Laravel

Rutas en:

routes/api.php

Uso de apiResource()

Controladores API devuelven JSON.

------------------------------------------------------------------------

## Pruebas

PHPUnit para tests automáticos.\
Postman para pruebas manuales.

------------------------------------------------------------------------

## Autenticación en APIs

JWT\
Sanctum\
Tokens

------------------------------------------------------------------------

## Documentación

Postman permite generar documentación pública.

------------------------------------------------------------------------

# FIN TEMA 7 Teoría

Ejercicio1

1. Laboratorio de registro y autenticación con cookies

Este laboratorio consiste en configurar el registro de usuarios y establecer un sistema de autenticación basado en cookies dentro de una aplicación Laravel.




Pasos a seguir:

1.Preparación del entorno: Asegúrate de tener un entorno de desarrollo Laravel configurado y funcionando correctamente con acceso a la base de datos.

*Vamos a empezar creando el proyecto: `composer create-project laravel/laravel lab_juanjo`*
*cambiamos el .env con los datos de nuestra BBDD*
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laboratory
DB_USERNAME=root
DB_PASSWORD=

*Y activamos Apache y MySQL además de que creamos la BBDD "laboratory" en phpMyAdmin.*


2.Configuración de la tabla la base de datos:

a.Cambia las migraciones de usuarios donde para que se elimine el atributo name del usuario y se añadan los atributos first_name y last_name.

*No hace falta que creemos la migración porque al crear el proyecto ya se crea dicha migración*
*Ahora la cambiamos para incluir esos atributos*
*Quizá lo correcto para seguir la indicación sería que hubiese una migración inicial y lo que hagamos sea crear una migración nueva con un update_users_table, en la que dropeariamos name y añadiriamos first_name y las_name. Vamos a hacerlo así por practicar*
`php artisan make:migration update_users_table`
*y dentro añadimos las columnas que queremos y dropeamos la que existe*
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('first_name');
            $table->string('last_name');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
            $table->string('name');
        });
    }
};
```

*Comprobamos todo con un `php artisan migrate:fresh`*
*Deberiamos recordar cambiar el Model User para que aparezca en fillable el first_name y last_name*


b.Cambia el factory o el seeder para que cargue 10 usuarios siguiendo este esquema.

*Lo cambiamos el factory*:

```php
    public function definition(): array
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
```

También tenemos que ir al DatabaseSeeder.php y poner lo que está comentado quitando el otro:

`User::factory(10)->create();`

c.Ejecútala para preparar la tabla de usuarios en la base de datos.

*Y lo ejecutamos todo, esto ya lo habíamos hecho para comprobar buen funcionamiento, así que lo hacemos con fresh y con el --seed para ejecutar el factory*
`php artisan migrate:fresh --seed`



3.Asegúrate de tener el modelo User que se crea al realizar la instalación de Laravel y añade los nuevos atributos.

```php
*Tambien lo hicimos antes para no liarla*
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];
```


4.Crea un controlador llamado AuthController que tenga los métodos login(), logout() y register() e importa el modelo User.

*Lo creamos como siempre `php artisan make:controller AuthController --model=User`*
*y le añadimos los 3 métodos que tenemos en los apuntes, adaptandolos para que sean como lo que queremos (con first_name y last_name)*
```php
    public function register(Request $request){
        // Lógica de validación de datos
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Crear el usuario
        $user = \App\Models\User::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Redirigir a la página de inicio
        return redirect('/')->withSuccess('¡Te has registrado correctamente!');
    }
    
    public function login(Request $request){
        // Lógica de validación de datos
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'Ingresa una dirección de correo electrónico válida.',
            'password.required' => 'El campo de contraseña es obligatorio.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        // Intentar iniciar sesión
        if (Auth::attempt($credentials)) {
            return redirect('/');
        }

        // Si falla, redirigir con mensaje de error
        return redirect()->back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('home');
    }
```


5.Crea las rutas de tipo POST para poder enviar datos a estos métodos del controlador. Además, asegúrate que la ruta de tipo GET raíz(/) te carga la vista “login” y que la ruta de tipo GET “register” te carga la vista de registro.

*Las creamos aunque realmente aun no tenemos las vistas. Recordemos, los gets para coger la vista, los post para enviar la info de los formularios*

```php
Route::get('/', function () {
    return view('login');
})->name('home');

Route::get('/register', function () {
    return view('register');
});

Route::post('/login', [AuthController::class,'login'])->name('login');
Route::post('/register', [AuthController::class,'register'])->name('register');
```


6.Crea un formulario de registro.blade.php que tenga al menos los campos, nombre, apellidos, email, contraseña y confirmar contraseña.

```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h2>Registro de usuario</h2>

<form action="{{ route('register') }}" method="POST">
    @csrf

    <div>
        <label>Nombre</label>
        <input type="text" name="first_name" value="{{ old('first_name') }}">
        @error('first_name')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>Apellidos</label>
        <input type="text" name="last_name" value="{{ old('last_name') }}">
        @error('last_name')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}">
        @error('email')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>Contraseña</label>
        <input type="password" name="password">
        @error('password')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>Confirmar contraseña</label>
        <input type="password" name="password_confirmation">
    </div>

    <button type="submit">Registrarse</button>

</form>

<a href="{{ route('home') }}">Ir a login</a>

</body>
</html>

```


7.Crea un formulario de login.blade.php que tenga los campos, email y contraseña.
```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>

<h2>Iniciar sesión</h2>

<form action="{{ route('login') }}" method="POST">
    @csrf

    <div>
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}">
        @error('email')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label>Contraseña</label>
        <input type="password" name="password">
        @error('password')
            <div style="color:red">{{ $message }}</div>
        @enderror
    </div>

    @if ($errors->has('email'))
        <div style="color:red">{{ $errors->first('email') }}</div>
    @endif

    <button type="submit">Entrar</button>

</form>

<a href="/register">Crear cuenta</a>

</body>
</html>

```


8.Implementa la funcionalidad de registro de usuarios en tu aplicación que al terminar redirija al Login.




9.Implementa la funcionalidad de login donde al terminar guarde el nombre y apellido del usuario en una cookie y redirija al welcome.blade.php.

10.Modifica la vista welcome.blade.php para que muestre una bienvenida y en caso de estar logueado el nombre del usuario.


Ejercicio 2

2. Proyecto CRUD personalizado

En esta actividad deberéis pensar e implementar una app en Laravel que cuente con un controlador de tipo resources para la gestión del contenido de la app.

Pasos a seguir:

1.Deberéis pensar un tema para vuestra aplicación, puede ser cualquier cosa que se pueda administrar con un controlador de métodos CRUD.

Vale, pues vamos a hacer un gestor de libros. Primero creamos el proyecto `composer create-project laravel/laravel libros_juanjo`


2.Partiendo de la actividad anterior hay que modificar la aplicación para implementar la migración, modelo/s y vistas, así como un factory o seeder que os ayude a cargar la base de datos.

Luego dentro de eso tenemos que crear el modelo + migracion + controlador de Book
`php artisan make:model Book -mcr`




3.Implementa el controlador CRUD para la gestión de la app con todas las funcionalidades de los métodos.


4.Al realizar el login debe ir al Index de vuestro controlador CRUD y desde este se debe poder navegar por todas las vistas en menos de 3 clics.


5.Añade el middleware de autenticación para poder acceder al contenido de la app (todo menos register y login) solo si estas logueado.

Ejercicio3 TODOList Middleware Personalizado

Ejercicio4 

4. Laboratorio de servicios

Esta actividad consiste en desarrollar en el proyecto de la actividad anterior, dos servicios, uno para el envío de correos electrónicos y otro para la gestión de archivos.

Pasos a seguir:

1.Busca información sobre las clases Mail y Storage de Laravel.

Sirven para mandar correos y para guardar archivos

2.Crea un servicio EmailService que contenga un método sendEmail() para enviar correos electrónicos utilizando la clase Mail de Laravel.

Primero debemos crear la carpeta app/Services/
Creamos el archivo EmailService.php
y lo poblamos así:
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendEmail($to, $subject, $message)
    {
        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to)
                 ->subject($subject);
        });
    }
}

```

Además también creamos FileService.php y lo poblamos así:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService
{
    public function save($file, $path = 'uploads')
    {
        return $file->store($path);
    }

    public function get($path)
    {
        return Storage::get($path);
    }

    public function delete($path)
    {
        return Storage::delete($path);
    }
}
```

Para darles uso tenemos que crear el Service Provider:
`php artisan make:provider AppServiceProviderCustom`
y poblarlo:
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EmailService;
use App\Services\FileService;

class AppServiceProviderCustom extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EmailService::class, function () {
            return new EmailService();
        });

        $this->app->singleton(FileService::class, function () {
            return new FileService();
        });
    }

    public function boot(): void
    {
        //
    }
}
```

No olvidemos también registrar el provider custom que hemos hecho en config/app.php buscando en la parte de providers y añadiendolo.

3.Crea un servicio FileService que contenga métodos para guardar, recuperar y eliminar archivos del storage utilizando la clase Storage de Laravel.


4.Registra estos servicios EmailService y FileService en el contenedor de servicios de Laravel utilizando un Service Provider.


5.Inyecta estos servicios en el controlador CRUD y en AuthController y pruébalos. Por ejemplo, utiliza EmailService para enviar un correo de bienvenida cuando se registra un nuevo usuario. Utiliza el servicio FileService para guardar un archivo desde create.

Ejercicio5

5. Laboratorio de uso de helpers de Laravel
Esta actividad consiste en, partiendo del proyecto de la actividad anterior, utilizar algunos de los helpers que proporciona Laravel por defecto.

Pasos a seguir:

1.Utiliza el helper Str_random para generar una cadena aleatoria de 20 caracteres para utilizarla como remember_token del modelo Usuario.

Primero lo importamos `use Illuminate\Support\Str;`
Luego lo usamos:
```php
User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => bcrypt($request->password),
    'remember_token' => Str::random(20),
]);
```

2.Utiliza el helper dd: imprime una variable y detiene la ejecución del script.

Por ejemplo, en el BookController podríamos hacer:
```php
    public function show(Book $book) {
        dd($book);
        return view('books.show', compact('book'));
    }
```
Lo hemos usado antes cuando nos fallaba el Mailing para comprobar que entrase bien a la función, poniendo un dd('He entrado').


3.Utiliza el helper Redirect para redirigir a una ruta específica después de una acción de los métodos store, update, delete donde en cada uno de ellos se envíe como success si todo va bien, error si hay algún problema y warning si existe un problema de validación.

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title'=>'required',
        'author'=>'required',
        'year'=>'required|integer',
        'description'=>'nullable'
    ]);

    try {
        Book::create($validated);

        return redirect()
            ->route('books.index')
            ->with('success', 'Libro creado correctamente');

    } catch (\Exception $e) {

        return redirect()
            ->route('books.index')
            ->with('error', 'Error al crear el libro');
    }
}
```
```php
Y similar en el resto:

    public function destroy(Book $book) {
        return redirect()->route('books.index');

        try {
            $book->delete();

            return redirect()
                ->route('books.index')
                ->with('success', 'Libro eliminado');

        } catch (\Exception $e) {

            return redirect()
                ->route('books.index')
                ->with('error', 'Error al eliminar');
        }
    }
```

E importante, si queremos que lo imprima también hay que cambiar la view en Index:

@if(session('success'))
<p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
<p style="color:red">{{ session('error') }}</p>
@endif

@if($errors->any())
<p style="color:orange">Error de validación</p>
@endif


4.Utiliza el helper Date para formatear una fecha del campo email_verified_at para un usuario.

Primero añadimos en el register() dentro del AuthController el dato:
```php
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'remember_token' => Str::random(20),
            'email_verified_at' => Carbon::now(),
        ]);
```
Como siempre, no olvidemos importarlo dentro del mismo AuthController `use Carbon\Carbon;`

y luego para comprobarlo que sale bien, creamos una vista profile y ponemos lo siguiente:
```php
<h2>Perfil</h2>

<p>Email: {{ auth()->user()->email }}</p>

<p>Verificado el: 
{{ auth()->user()->email_verified_at->format('d/m/Y H:i') }}
</p>

```

Ejercicio6
6. Laboratorio de uso de librerías en Laravel
Este laboratorio consiste en, partiendo del proyecto de la actividad anterior, instalar y utilizar una librería externa en un proyecto Laravel.

Pasos a seguir:

1.Investiga librerías PHP populares (no vistas en clase) y elige una para integrar a tu proyecto Laravel.

Librería elegida: Intervention Image

2.Utilizando Composer instala la librería en tu proyecto.

composer require intervention/image

Luego tenemos que añadir la migración para poder poner imagenes en book
`php artisan make:migration add_image_to_books_table`

Dentro de esa migración
```php
Schema::table('books', function (Blueprint $table) {
    $table->string('image')->nullable();
});
```
hacemos php artisan migrate

y añadimos en el formulario de create la opción del input de type file:
```php
<form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data">
@csrf

<input name="title">
<input name="author">
<input name="year">
<textarea name="description"></textarea>

<input type="file" name="image">

<button>Guardar</button>
</form>
```

Hasta ahora eso lo que nos hace es que tengamos un campo en el formulario, pero el BookController tiene que saber lo que estamos haciendo, así que habrá que importarlo y añadirlo:

use Intervention\Image\Facades\Image;

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'title'=>'required',
        'author'=>'required',
        'year'=>'required|integer',
        'description'=>'nullable',
        'image'=>'nullable|image'
    ]);

    if($request->hasFile('image')){
        $img = Image::make($request->file('image'))
                    ->resize(300, 400);

        $filename = time().'.jpg';
        $img->save(public_path('images/'.$filename));

        $validated['image'] = $filename;
    }

    Book::create($validated);

    return redirect()->route('books.index')
        ->with('success','Libro creado con imagen');
}

```

y claro está, mostrarlo en el index o no nos sirve de nada guardarlo.

@if($book->image)
<img src="/images/{{ $book->image }}" width="80">
@endif

También recordemos añadir el fillable de image al modelo

3.Siguiendo la documentación de la librería, realiza la configuración necesaria como publicar assets, agregar providers, aliases, etc.

Lo explicado anteriormente

4.En tu controlador utiliza la librería para implementar una funcionalidad.

Lo explicado anteriormente

Ejercicio7

7. Taller de creación de APIs
Este taller consiste en, partiendo del proyecto de la actividad anterior, crear un controlador de tipo API y verificarlo.

Pasos a seguir:

1.Basándoos en la temática que elegisteis durante la actividad del middleware personalizado, comenzaréis la creación de una API en Laravel.

2.Deberéis crear un controlador de tipo API.

php artisan make:controller Api/BookApiController --api

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Listado de libros'
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'message' => 'Crear libro'
        ]);
    }

    public function show(string $id)
    {
        return response()->json([
            'message' => 'Mostrar libro '.$id
        ]);
    }

    public function update(Request $request, string $id)
    {
        return response()->json([
            'message' => 'Actualizar libro '.$id
        ]);
    }

    public function destroy(string $id)
    {
        return response()->json([
            'message' => 'Eliminar libro '.$id
        ]);
    }
}


3.En esta fase, sólo se creará el controlador, implementar los métodos CRUD con un mensaje básico JSON que indique a que método estamos llamando.

hecho

4.Os tocará configurar las rutas de vuestra API para que correspondan correctamente a los métodos en vuestro controlador API.

use App\Http\Controllers\Api\BookApiController;

Route::apiResource('books', BookApiController::class);

5.Por último, deberéis comprobar que las rutas devuelven el JSON esperado, para ello deberéis usar Postman.

hecho

Ejercicio8

8. Laboratorio TDD de métodos CRUD
En este laboratorio adoptaremos la metodología de programación TDD (Test-Driven Development). Esto significa que antes de implementar los métodos en vuestro código, deberéis escribir pruebas unitarias que describan el comportamiento esperado de estos métodos. Sólo después de que las pruebas estén escritas, pasaréis a implementar la funcionalidad necesaria para hacer que esas pruebas sean exitosas. Esta metodología nos ayuda a garantizar que las funciones se estén construyendo de acuerdo con las expectativas especificadas, y a prevenir la introducción de errores.
Pasos a seguir:


1.Para cada uno de los métodos CRUD que necesitáis implementar en vuestro controlador de API, deberéis comenzar escribiendo pruebas unitarias que describan lo que se espera que estos métodos devuelvan.

Vale, empecemos una a una, primero creamos el test `php artisan make:test BookApiTest`

Y pasamos a escribir cada una de las pruebas

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_books()
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200);
    }

    public function test_can_create_book()
    {
        $response = $this->postJson('/api/books', [
            'title' => 'Libro test',
            'author' => 'Autor test',
            'year' => 2024
        ]);

        $response->assertStatus(201);
    }

    public function test_can_show_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson('/api/books/'.$book->id);

        $response->assertStatus(200);
    }

    public function test_can_update_book()
    {
        $book = Book::factory()->create();

        $response = $this->putJson('/api/books/'.$book->id, [
            'title' => 'Nuevo título',
            'author' => 'Nuevo autor',
            'year' => 2023
        ]);

        $response->assertStatus(200);
    }

    public function test_can_delete_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson('/api/books/'.$book->id);

        $response->assertStatus(200);
    }
}

```

2.Una vez que tengáis escritas las pruebas, ejecutadlas utilizando PHPUnit para verificar que, como se espera, fallan (puesto que aún no habéis implementado la funcionalidad).

Efectivamente, al pasarlas con `php artisan test` han fallado.
3.A continuación, es vuestra tarea implementar la funcionalidad necesaria en vuestros métodos CRUD para que las pruebas pasen.

Ahora cambiamos lo que había que solo mostraba mensaje, por funcionalidad real:

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;

class BookApiController extends Controller
{
    public function index()
    {
        return response()->json(Book::all(), 200);
    }

    public function store(Request $request)
    {
        $book = Book::create($request->validate([
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|integer',
            'description' => 'nullable'
        ]));

        return response()->json($book, 201);
    }

    public function show(Book $book)
    {
        return response()->json($book, 200);
    }

    public function update(Request $request, Book $book)
    {
        $book->update($request->validate([
            'title' => 'required',
            'author' => 'required',
            'year' => 'required|integer',
            'description' => 'nullable'
        ]));

        return response()->json($book, 200);
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}

```
4.Una vez que hayáis implementado la funcionalidad, volved a ejecutar vuestras pruebas para comprobar que ahora pasan.

pasamos de nuevo los test y todos correctos

5.Este ciclo de "red, green, refactor" (fallar, pasar, refactorizar) se deberá repetir hasta que todos los métodos CRUD estén implementados y todas las pruebas pasen.


6.Recordad que el objetivo de este ejercicio es familiarizaros con la metodología TDD y su ciclo de trabajo.

Ejercicio9 

9. Práctica de autenticación en la API con JWT
En esta práctica tendréis la oportunidad de implementar la autenticación JWT en vuestra API Laravel existente.

Además, deberéis modificar las pruebas unitarias para que utilicen el token de JWT y realizar las llamadas en Postman utilizando este token.

Pasos a seguir:

1.Vuestro siguiente paso será investigar e implementar el paquete JWT-Auth de Laravel para permitir la autenticación JWT.

Vale, para implementarlo tenemos que poner el require:
`composer require tymon/jwt-auth`

como no se ha creado el config/jwt.php tenemos que publicarlo
`php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"`

ahora generamos una llave secreta en .env con:
`php artisan jwt:secret`
(JWT_SECRET=YqZi4Yvy92jmid0rhAt2PRxh3UXKgWzzIbDJrwa1pNU8awK0tAnGB6NmlXGVDnI5)

y pasamos a configurar el guard. en config/auth.php tenemos que añadir la parte api y jwt en guards:

```php
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ]
    ],
```

Ahora creamos el AuthApiController `php artisan make:controller Api/AuthApiController` y lo poblamos 

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }
}
```


2.Después, tendréis que configurar la autenticación JWT en vuestra API. Esto implicará configurar el middleware de autenticación para usar JWT y modificar la lógica de inicio de sesión para devolver un token JWT.

y también añadimos las cosas a la routes/api:
```php
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BookApiController;

Route::post('login', [AuthApiController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::apiResource('books', BookApiController::class);
});
```

3.Comprobad que vuestra implementación funciona correctamente realizando varias solicitudes a la API utilizando un cliente HTTP, y verificad que la autenticación JWT está operativa.

Lo probamos con Postman con un POST a http://127.0.0.1:8000/api/login añadiendo al body JSON:
{
  "email": "usuario@ejemplo.com",
  "password": "password"
}


Ejercicio10 TODOList

10. Documentación de la API.
Esta actividad consiste en documentar la API que habéis realizado en los ejercicios anteriores. Utilizaremos lo que habéis aprendido de los videos y de la documentación de Postman para llevar a cabo esta tarea.
Además, deberéis publicar la documentación con Postman y generar un enlace público que debéis compartir en el foro.
Pasos a seguir:
1.
Antes de la clase, debéis repasar y comprender la teoría de la documentación de la API y familiarizaros con la herramienta Postman.
2.
En clase tendréis que utilizar Postman para documentar cada una de las rutas de la API de vuestro proyecto, asegurándoos de incluir detalles sobre los parámetros, los cuerpos de las solicitudes y las respuestas esperadas.
3.
Aseguraos de incluir ejemplos de solicitudes y respuestas si es posible.
4.
Al terminar de documentar vuestra API, utilizad la función de publicación de Postman para generar un enlace público a vuestra documentación.
5.
Finalmente, compartid este enlace en el foro de clase y realizad 2 comentarios sobre el trabajo de los demás, para mejorar sus trabajos.

Ejercicio11 

11. Integración de APIs externas.

En esta tarea, deberéis seleccionar un servicio web de vuestro interés, investigar en profundidad su documentación, y posteriormente integrarlo dentro de vuestro propio proyecto.

Finalmente, debéis garantizar su correcto funcionamiento mostrando los resultados en vuestra API.
La elección del servicio web deberá ser justificada y su integración en el proyecto claramente explicada o documentada.


Pasos a seguir:
1.Seleccionad un servicio web de la lista proporcionada o bien uno que sea de vuestro interés.

Vamos a coger la PokeAPI porque es la que se usó en el examen del año pasado y no supe hacerlo.
(https://pokeapi.co/)
Codigo:
https://pokeapi.co/api/v2/pokemon/pikachu


2.Estudiad la documentación del servicio web elegido hasta que comprendáis a fondo su funcionamiento.

Hecho! Si hiciesemos un `GET /api/pokemon/pikachu` nos devolvería un JSON con al info del Pokemon


3.Integrad el servicio web seleccionado en vuestro propio proyecto.

Como Laravel ya trae cliente HTTP no hay que instalar nada, así que vamos directos:
Creamos el controlador para API Externa `php artisan make:controller Api/PokemonApiController`

y lo poblamos

```php 
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PokemonApiController extends Controller
{
    public function show($name)
    {
        $response = Http::get("https://pokeapi.co/api/v2/pokemon/$name");

        if ($response->failed()) {
            return response()->json([
                'error' => 'Pokémon no encontrado'
            ], 404);
        }

        return response()->json($response->json(), 200);
    }
}
```


4.Cread las rutas necesarias para consumirlo.

En routes/api.php tenemos que poner el controller y la ruta

```php
use App\Http\Controllers\Api\PokemonApiController;

Route::get('pokemon/{name}', [PokemonApiController::class, 'show']);
```


5.Verificad que el servicio web funcione correctamente en vuestro proyecto.

Ahora lo probamos con Postman o navegador `GET http://127.0.0.1:8000/api/pokemon/pikachu`
y efectivamente, en el navegador me aparece una lista enorme de información del pokemon. Ahora faltaría darle formato e introducirlo en una view de una manera adecuada, pero la API externa ya está funcionando en nuestra app.

6.Si se encuentra algún error, buscáis soluciones y realizad las correcciones necesarias.


# FIN TEMA 7 Ejercicios