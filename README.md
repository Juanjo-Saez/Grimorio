# Grimorio 📖

Grimorio es una webapp para centralizar la toma y busqueda de notas. Grimorio es:

- Rapido
- Pragmatico
- Flexible

Esta basado en el metodo [Zettlekasten](https://es.wikipedia.org/wiki/Zettelkasten), pero es flexible en el uso de otras metodologias.

Las notas se enlazan entre si mediante *links* y se agrupan por *etiquetas*.


## Desarrollo

Instalar dependencias

`composer install`


#### Sail

Requiere instalar y ejecutar [Docker](https://www.docker.com/get-started/).

`./vendor/bin/sail up -d` Levanta el servidor y la base de datos.

`./vendor/bin/sail php artisan migrate:fresh --seed` Ejecuta de cero todas las migraciones y los seeders de la base de datos.

Servidor disponible en [https://localhost:80].

`./vendor/bin/sail ps` Comprueba los contenedores levantados.

`./vendor/bin/sail down` Apaga el servidor y la BBDD.

**Todos los comandos de php artisan deben lanzar con `./vendor/bin/sail` delante.**


#### Testing con Cypress

`npm i` Instala la suite de cypress.

`npx cypress open` Ejecuta los tests interactivos.

`npx cypress run` Ejecuta los tests desde el terminal.
