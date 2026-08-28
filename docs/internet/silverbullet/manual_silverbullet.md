#manual #info

# Manual SilverBullet

## Espacios
* **Abrir la paleta de comandos**: `Ctrl + /`
* **Space**: Es una colección de archivos Markdown en una carpeta del servidor. SilverBullet indexa estos archivos en tiempo real.
* **Page Picker** (`Ctrl+K`): Herramienta central para navegar por las páginas. Si el nombre no existe, ofrece crear la página.
* **Command Palette** (/ o `Ctrl + Shift + /`): Permite ejecutar comandos internos del sistema.
* **Sintaxis de rutas**: Se recomienda usar `Carpeta/Subcarpeta/NombreDePagina` para mantener el orden visual y físico.
* **Enlaces Bidireccionales**: Uso de `[[Nombre de Página]]` para conectar conceptos.
* Pulsa `Ctrl+/` y busca el comando Quick Note. Ejecútalo para ver cómo se genera una nota automática en `/inbox`

## Navegación y páginas
* `Ctrl + qq` : Crea una nota rápida cuya ruta es el día y luego la hora `Inbox/yyyy-mm-dd/hh-mm-ss`
* **Ir a la página `index`**: `Ctrl + Shift + h`
* Si vamos al selector de páginas `Ctrl + k` y pulsamos la barra espaciadora ( `space` ) nos autocompletará la carpeta en la que estamos para poder crear más páginas dentro de la carpeta.
* Cuando escribimos en el navegador de páginas un nombre y ruta que no existe, nos ofrece la creación de página que se puede hacer con `Shift + ⏎`



## Enlaces a otras páginas
* Cuando introduces `[[ ]]` aparecerán las páginas a las que puedes enlazar
* Si creamos un enlace con `[[ ]]` a una página que no existe, se crea una “página aspirante” que si hacemos click se creará.

## Tareas vinculadas
Cuando nombras una página con `[[ ]]` puedes asignarle una tarea que será controlada y vinculada automáticamente.


## Etiquetas
* Para crear una etiqueta lo hacemos como un título pero sin espacio entre el `#` y la palabra, es decir, #etiqueta.
* Si hacemos click con el ratón en la etiqueta que elijamos nos crea una página donde nos lista todas las páginas que tienen esa etiqueta.
* Si usamos el selector de páginas `Ctrl + k`, también podremos buscar páginas por su etiqueta.
* También podemos asignar etiquetas a otros “objetos” como tareas o listas #info

#contactos
```#contactos
uno: uno
dos: dos
tres: tres
```

## Tareas
Creamos una tarea con `[ ]`.
También lo podemos hacer escribiendo un texto y al final con el “Slash command” `/task`

## Slash commands

Los comandos de barra son formas rápidas de realizar tareas repetitivas. Se activan al escribir `/` en el texto (después de un espacio) seguido del nombre de la orden. La función de autocompletado ayuda a encontrar la orden correcta.

### Edición

- `/h1`, `/h2`, `/h3`, `/h4` — convierte la línea actual en un encabezado del nivel indicado.
- `/task` — convierte la línea actual en una tarea (`* [ ] ...`).
- `/frontmatter` — inserta un bloque de *frontmatter* YAML en la parte superior de la página.
- `/space-lua` — inserta un bloque de código `space-lua` con resaltado de sintaxis.

### Fechas

- `/today` — inserta la fecha de hoy (por ejemplo, `[[documentacion/docs/internet/silverbullet/modelo_permutas_signed.pdf]]2026-03-04`).
- `/yesterday` — inserta la fecha de ayer.
- `/tomorrow` — inserta la fecha de mañana.

### Slash Templates

La mayoría de los comandos de barra se implementan como **Plantillas de Barra (Slash Templates)**, es decir, páginas etiquetadas con `#meta/template/slash` cuyo contenido se inserta en la posición actual del cursor. La biblioteca estándar incluye plantillas para:

- `/query` — inserta un bloque de consulta SLIQ.
- `/lua-query` — inserta una expresión de consulta Lua.
- `/code` — inserta un bloque de código delimitado.
- `/table` — inserta una tabla Markdown.
- `/hr` — inserta una línea horizontal.
- `/note-admonition`, `/warning-admonition`, `/success-admonition`, `/danger-admonition` — insertan bloques de notas y advertencias.
- `/tpl` — inserta una expresión de plantilla.
- `/func` — inserta la definición de una función Lua.
- `/lua-command` — inserta la definición de un comando Lua.
- `/lua-slash-command` — inserta la definición de un comando de barra (slash command).

## Documentos

* Usando la paleta de comandos `Ctrl + Shift + /` puedes subir cualquier archivo que esté en el ordenador.
* También puedes pinchar y arrastrar un fichero a la página en la que estás y te preguntará la localización donde quieres copiarlo. Creará un enlace al fichero, y si ponemos un `!` al principio del enlace hará una previsualización con opciones para descargarlo, visualizarlo, etc.
* 


## Citas
* Hola > **danger** Danger

> **note** Note
> 

> **warning** Warning 
> Texto del warning

> **Descripción:** Esto es la descripción

> **success** Success
>

## Emojis
Pulsamos `:` y luego empezamos a escribir el nombre del emoji.

## Tipografía
* **Bold**: `Ctrl + b`
* _Cursiva_: `Ctrl + i`
* **Link**: Copias el enlace al portapapeles, luego seleccionas el texto que quieres usar como enlace y lo pegas.

## Bibliografía

**Vídeos oficiales de Silverbullet**
* [Welcome to SilverBullet part 1: Basic Markdown and Outlines](https://www.youtube.com/watch?v=bb1USz_cEBY&t=32s)
* [Welcome to SilverBullet part 2: Multiple pages, navigation and linking](https://www.youtube.com/watch?v=7hyLvEfw34w)
* [Welcome to SilverBullet part 3: Structuring your Space](https://www.youtube.com/watch?v=bZ79-RbyNoU)
* [Varias librerías y utilidades para silverbullet](https://github.com/Mr-xRed/silverbullet-libraries)
