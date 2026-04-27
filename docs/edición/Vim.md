# Uso de Vim

Tutorial al ejecutar "vimtutor es"

- [Uso de Vim](#uso-de-vim)
  - [Lección 1.1: MOVIMIENTOS DEL CURSOR](#lección-11-movimientos-del-cursor)
  - [Lección 1.2: ENTRANDO Y SALIENDO DE VIM](#lección-12-entrando-y-saliendo-de-vim)
  - [Lección 1.3: EDICIÓN DE TEXTO - BORRADO](#lección-13-edición-de-texto---borrado)
  - [Lección 1.4: EDICIÓN DE TEXTO - INSERCIÓN](#lección-14-edición-de-texto---inserción)
  - [Lección 2.1: MANDATOS PARA BORRAR](#lección-21-mandatos-para-borrar)
  - [Lección 2.2: MÁS MANDATOS PARA BORRAR](#lección-22-más-mandatos-para-borrar)
  - [Lección 2.3: SOBRE MANDATOS Y OBJETOS](#lección-23-sobre-mandatos-y-objetos)
  - [Lección 2.4: UNA EXCEPCIÓN AL 'MANDATO-OBJETO'](#lección-24-una-excepción-al-mandato-objeto)
  - [Lección 2.5: EL MANDATO DESHACER](#lección-25-el-mandato-deshacer)
  - [Lección 3.1: EL MANDATO «PUT» (poner)](#lección-31-el-mandato-put-poner)
  - [Lección 3.2: EL MANDATO «REPLACE» (remplazar)](#lección-32-el-mandato-replace-remplazar)
  - [Lección 3.3: EL MANDATO «CHANGE» (cambiar)](#lección-33-el-mandato-change-cambiar)
  - [Lección 3.4: MÁS CAMBIOS USANDO c](#lección-34-más-cambios-usando-c)
  - [Lección 4.1: SITUACIÓN EN EL FICHERO Y SU ESTADO](#lección-41-situación-en-el-fichero-y-su-estado)
  - [Lección 4.2: EL MANDATO «SEARCH» (buscar)](#lección-42-el-mandato-search-buscar)
  - [Lección 4.3: BÚSQUEDA PARA COMPROBAR PARÉNTESIS](#lección-43-búsqueda-para-comprobar-paréntesis)
  - [Lección 4.4: UNA FORMA DE CAMBIAR ERRORES](#lección-44-una-forma-de-cambiar-errores)
  - [Lección 5.1: CÓMO EJECUTAR UN MANDATO EXTERNO](#lección-51-cómo-ejecutar-un-mandato-externo)
  - [Lección 5.2: MÁS SOBRE GUARDAR FICHEROS](#lección-52-más-sobre-guardar-ficheros)
  - [Lección 5.3: UN MANDATO DE ESCRITURA SELECTIVO](#lección-53-un-mandato-de-escritura-selectivo)
  - [Lección 5.4: RECUPERANDO Y MEZCLANDO FICHEROS](#lección-54-recuperando-y-mezclando-ficheros)
  - [Lección 6.1: EL MANDATO «OPEN» (abrir)](#lección-61-el-mandato-open-abrir)
  - [Lección 6.2: EL MANDATO «APPEND» (añadir)](#lección-62-el-mandato-append-añadir)
  - [Lección 6.3: OTRA VERSIÓN DE «REPLACE» (remplazar)](#lección-63-otra-versión-de-replace-remplazar)
  - [Lección 6.4: FIJAR OPCIONES](#lección-64-fijar-opciones)
  - [Lección 7: MANDATOS PARA LA AYUDA EN LÍNEA](#lección-7-mandatos-para-la-ayuda-en-línea)
  - [Bibliografia](#bibliografia)


## Lección 1.1: MOVIMIENTOS DEL CURSOR
* Para mover el cursor, pulse las teclas h,j,k,l de la forma que se indica.
      ^
      k       Indicación: La tecla h está a la izquierda y mueve a la izquierda.
 < h     l >              La tecla l está a la derecha y mueve a la derecha.
      j                   La tecla j parece una flecha que apunta hacia abajo.
      v

## Lección 1.2: ENTRANDO Y SALIENDO DE VIM
* `:q!` <INTRO> => provoca la salida del editor SIN guardar ningún cambio
* `:wq` <INTRO> => Si quiere guardar los cambios y salir escriba
P
## Lección 1.3: EDICIÓN DE TEXTO - BORRADO
* Estando en modo Normal pulse  `x`  para borrar el carácter sobre el cursor.
                                
## Lección 1.4: EDICIÓN DE TEXTO - INSERCIÓN
Estando en modo Normal pulse  `i`  para insertar texto.

## Lección 2.1: MANDATOS PARA BORRAR
* `dw` => para borrar hasta el final de una palabra

## Lección 2.2: MÁS MANDATOS PARA BORRAR
* `d$` =>  para borrar hasta el final de la línea.
* **Borrar un caracter**: posicionar el cursor sobre el carácter a borrar y presionar `x`. Esto también borra el espacio ocupado por el caracter. Para borrar el carácter anterior a la posición del cursor pulsar X.
* **Remover una palabra**: posicionar el cursor al principio de la palabra y pulsar `dw`, entonces se borrara la palabra y el espacio que la misma ocupaba. Para borrar parte de una palabra, hay que colocar el cursor a la derecha de la parte a modificar y teclear `dw`.
* **Eliminar una línea**: pulsando `dd` se borra una línea y el espacio que esta ocupaba. Para remover parte de una línea, podemos a) borrar todo lo que este a la derecha del cursor presionando la tecla `D`, o `b`) borrar todo lo que este a la izquierda del mismo basta con pulsar `d0` (`d` seguida del número `cero`).
* **Borrar hasta el final del archivo**: posicionar el cursor sobre la primera línea que se desea eliminar y presionar `dG`. Esto eliminará todo desde la línea actual hasta el final del archivo.
* **Borrar desde el principio del archivo**: colocar el cursor en la última línea que haya que remover y luego presionar `d1G`.

## Lección 2.3: SOBRE MANDATOS Y OBJETOS
El formato del mandato de borrar   d   es como sigue:
`[número]   d   objeto      O        d   [número]   objeto`

donde:
*   número - es cuántas veces se ha de ejecutar el mandato (opcional, defecto=1).
*   d - es el mandato para borrar.
*   objeto - es sobre lo que el mandato va a operar (lista, abajo).

  Una lista corta de objetos:
*   `w` - desde el cursor hasta el final de la palabra, incluyendo el espacio.
*   e - desde el cursor hasta el final de la palabra, SIN incluir el espacio.
*   $ - desde el cursor hasta el final de la línea.

## Lección 2.4: UNA EXCEPCIÓN AL 'MANDATO-OBJETO'
* `dd` => para borrar una línea entera.
* `2dd` => para borrar una 2 líneas enteras.

## Lección 2.5: EL MANDATO DESHACER
* `u` =>  para deshacer los últimos mandatos.
* `U` =>   para deshacer una línea entera.
* `CTRL-R` => para rehacer lo deshecho.

## Lección 3.1: EL MANDATO «PUT» (poner)
* `p` => para poner lo último que ha borrado después del cursor.

## Lección 3.2: EL MANDATO «REPLACE» (remplazar)
* `r` =>  y un carácter para sustituir el carácter sobre el cursor.

## Lección 3.3: EL MANDATO «CHANGE» (cambiar)
* `cw` => Para cambiar parte de una palabra o toda ella

## Lección 3.4: MÁS CAMBIOS USANDO c
** El mandato change se utiliza con los mismos objetos que delete. **
* El mandato change funciona de la misma forma que delete. El formato es:
     [número]   c   objeto       O        c   [número]   objeto
* Los objetos son tambiém los mismos, tales como  w (palabra), $ (fin de
la línea), etc.

## Lección 4.1: SITUACIÓN EN EL FICHERO Y SU ESTADO
* `CTRL-g` => para mostrar su situación en el fichero y su estado.
* `[número] + MAYU-G` => para moverse a una determinada línea del fichero. Si no pulsas un número irás al final del fichero.

## Lección 4.2: EL MANDATO «SEARCH» (buscar)
* `/ seguido de una frase` => para buscar la frase.
* Para repetir la búsqueda, simplemente pulse `n`
* Para buscar la misma frase en la dirección opuesta, pulse `Mayu-N`
* Si quiere buscar una frase en la dirección opuesta (hacia arriba),
utilice el mandato  `?`  en lugar de  `/`
* Cuando la búsqueda alcanza el final del fichero continuará desde el
principio.

## Lección 4.3: BÚSQUEDA PARA COMPROBAR PARÉNTESIS
* `%` =>  para encontrar el paréntesis correspondiente a ),] o } .

## Lección 4.4: UNA FORMA DE CAMBIAR ERRORES
* `:s/viejo/nuevo` => Para cambiar viejo por nuevo en una línea pulse
* `:s/viejo/nuevo/g` => Para sustituir 'viejo' por 'nuevo'.
* `:#,#s/viejo/nuevo/g` => Para cambiar todas las apariciones de una expresión ente dos líneas. Donde #,# son los números de las dos
líneas.
* `:%s/viejo/nuevo/g` =>  Para hacer los cambios en todo el fichero.

## Lección 5.1: CÓMO EJECUTAR UN MANDATO EXTERNO
* `:!` => Seguido de un mandato externo para ejecutar ese mandato.

## Lección 5.2: MÁS SOBRE GUARDAR FICHEROS
* `:w NOMBRE_DE_FICHERO` => Para guardar los cambios hechos en un fichero.

## Lección 5.3: UN MANDATO DE ESCRITURA SELECTIVO
* `:#,# NOMBRE_DEL_FICHERO` => Para guardar parte del fichero con los números de línea desde y hasta.

## Lección 5.4: RECUPERANDO Y MEZCLANDO FICHEROS
* `:r NOMBRE_DEL_FICHERO` => Para insertar el contenido de un fichero.

## Lección 6.1: EL MANDATO «OPEN» (abrir)
* `o` =>  para abrir una línea debajo del cursor y situarle en modo Insert
* `Shift + O` =>  para abrir una línea encima del cursor y situarle en modo Insert

## Lección 6.2: EL MANDATO «APPEND» (añadir)
* `$` => para ir al final de una línea.
* `a` => para insertar texto DESPUÉS del cursor.
* `A` => para insertar texto DESPUÉS de una línea.

## Lección 6.3: OTRA VERSIÓN DE «REPLACE» (remplazar)
* `R` =>  Para sustituir más de un carácter.

## Lección 6.4: FIJAR OPCIONES

Fijar una opción de forma que una búsqueda o sustitución de la caja de búsqueda. Para el resaltado de las búsquedas.
* `:set ic` => Ignorar caja de letra
* `:set hls is` => Fije las opciones 'hlsearch' y 'insearch'

## Lección 7: MANDATOS PARA LA AYUDA EN LÍNEA
* pulse la tecla `<AYUDA>` (si dispone de ella)
* pulse la tecla `<F1>` (si dispone de ella)
* escriba `:help <INTRO>`
* `:q <INTRO>` => para cerrar la ventana de ayuda.

Para encontrar ayuda de diferentes temas:
* `:help w <INTRO>`
* `:help c_<T <INTRO>`
* `:help insert-index <INTRO>`


Bibliografia
------------
* https://platzi.com/blog/guia-definitiva-para-vim-y-neovim-instalacion-comandos-y-trucos/