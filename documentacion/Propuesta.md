Buenas! La app tiene que tener por obligación todo lo prometido en la propuesta, que es esta:

📖Grimorio 📖

Grimorio es una webapp para centralizar la toma y busqueda de notas. Grimorio es:

    Rapido
    Pragmatico
    Flexible

Este proyecto de web nace de una necesidad propia que puede convertirse en una herramienta muy útil para cualquier usuario. A la hora de buscar aplicaciones para la toma de notas había opciones como Notion, Obsidian, Roomresearch o Logseq, pero nada tan sencillo y directo como lo que buscaba, por lo que decidí crear mi propia web.

Para explicar más en profundidad la idea, es útil mencionar el método Zettlekasten (caja de notas) que consiste en tener muchas notas individuales con ideas breves, para facilitar anotar cualquier tipo de información aunque esté desestructurada, y asociarlas a un tag o un número jerárquico para asociarlas así a otras notas ya tomadas o que se tomarán en el futuro. Tras esto se pueden crear notas permanentes refinando la información y generando nuevas ideas, y finalmente conectar entre sí estas notas refinadas, haciendo que ninguna nota esté sin vincular.

Llevandolo a título personal para ejemplicar más mi idea, solía usar mucho google keep, un grupo de whatsapp de notas o incluso notion para apuntarme todas las ideas que me iban viniendo o los conocimientos que quería consolidar, por ejemplo, guardarme un vídeo de receta que he visto por rrss, o de ejercicios de estiramiento, una idea de regalo para la pareja, un detalle de programación que siempre me costaba recordar, etc, pero esto acababa inevitablemente en el olvido o pérdida de mucha de esa información. 

Las diferencias de lenguajes que son sensibles a mayúsculas y los que no es un ejemplo de algo que me habría gustado ir guardando y tener una lista propia de lo que ya he aprendido. Con este método, es sencillo apuntarlo en el momento en el que lo aprendo (JavaScript es case-sensitive), sin preocuparme de que esa información se vaya a perder, ya que se enlazará con distintos tags como el lenguaje ("JavaScript") y el tema ("case-sensitive") y en algún momento libre retornar a ello, filtrar por el tag que me importe, case-sensitive en este caso, y realizar una Nota o Zettle más pulida aunando todas las notas individuales que haya ido añadiendo en relación. Del mismo modo se puede hacer con recetas que incluyan cierto alimento ("pollo"), o de tipo de cocinado ("airfryer"), o ejercicios focalizados ("lumbares"). Es un método muy útil sobretodo en el entorno tecnológico actual ya que cuando se creó era manualmente en tarjetas físicas.

Las funcionalidades y la escala eran en un principio individuales, pero para este proyecto vamos a hacerlas multiusuario con compartición de notas, añadiendo auntenticación, seguridad y sesiones y permisos tanto para notas privadas como compartidas.

La estructura de la web se basará en una vista home donde poder visualizar todas las notas propias, ordenadas por fecha de creación descendente. También se dispondrá de un buscador para palabras clave y, lo más importante, el uso de filtros para seleccionar por etiquetas (tags). 

En esa misma home se dispondrá de enlace a otra pantalla de creación de notas, en la que no solo podrás disponer de los elementos clave para crear dicha nota, si no que al enlazarla a un tag automáticamente aparecerá el histórico de notas similares. 

Otra utilidad será las notas compartidas, en las que se podrá dar poderes de lectura o de edición, para o bien compartir un conocimiento refinado con otra gente o bien crear una nota conjunta en la que todos los usuarios puedan añadir contenido, esto aparecerá como otro filtro más en la ventan home.

Mejoras a largo plazo incluirá la posibilidad de descargar notas individuales en formato PDF, crear links de solo visualización sin necesidad de autenticación (a parte de los links compartidos con otros usuarios dentro de la propia plataforma)

Herramientas: Javascript, PHP, Laravel, Laravel Sail con Docker, Blade para las vistas, Testing con Cypress, 
