# Variables en Bash
_(https://atareao.es/tutorial/scripts-en-bash/variables-en-bash/)_

#### Ámbito de las variables

Las variables en Bash tienen un ámbito global a excepción de que lo definas como local. Esto quiere decir que definas la variable donde la definas, la podrás utilizar en cualquier lugar de tu script. Sin embargo, si la defines dentro de una función como **local** esta solo tendrá aplicación en la propia función.

Tampoco te preocupes, mucho sobre este tema, que ya tendrás tiempo de verlo con detalle cuando lleguemos a las funciones.

### Variables especiales en Bash

En Bash, hay algunas variables especiales y que están definidas por defecto, y que se refieren al script, al que ha ejecutado el script, o a la máquina en la que se ha ejecutado el script. Así, algunas de ellas son las siguientes,

*   `$0` representa el nombre del script
*   `$1` – `$9` los primeros nueve argumentos que se pasan a un script en Bash
*   `$#` el número de argumentos que se pasan a un script
*   `$@` todos los argumentos que se han pasado al script
*   `$?` la salida del último proceso que se ha ejecutado
*   `$$` el ID del proceso del script
*   `$USER` el nombre del usuario que ha ejecutado el script
*   `$HOSTNAME` se refiere al _hostname_ de la máquina en la que se está ejecutando el script
*   `$SECONDS` se refiere al tiempo transcurrido desde que se inició el script, contabilizado en segundos.
*   `$RANDOM` devuelve un número aleatorio cada vez que se _lee_ esta variable.
*   `$LINENO` indica el número de líneas que tiene nuestro script.

Además de estas variables que vienen definidas por defecto en Bash, y que seguro te serán de gran utilidad, también hay normalmente otras, y que se refieren al entorno en el que trabajas. Para conocer estas variables puedes utilizar el comando `env`

Si lo ejecutas puedes encontrar algunas variables tan interesantes como,

*   `$SHELL` que indica el _shell_ que estás ejecutando
*   `$EDITOR` donde está indicado el editor por defecto. En mi caso _vim_.
*   `$HOME` la ruta del usuario. En mi caso `/home/lorenzo`
*   `$USERNAME` el nombre del usuario. En mi caso Lorenzo 😄
*   `$PATH` la ruta por defecto donde encontrar binarios, etc.

#### ¿Como utilizar los argumentos con tu script?

Como puedes ver, es posible pasar argumentos a nuestro script, para lo que tenemos que utilizar las variables especiales `$1` a `$9`. Crea el siguiente script con el nombre `prueba.sh`

    #!/bin/bash
    echo $1
    

Ahora, solo tienes que ejecutar `bash prueba.sh variable1` y obtendras como respuesta `variable1`. Si lo ejecutas simplemente con `bash prueba.sh` no te va a devolver nada, y si por lo contrario lo ejecutas con `bash prueba.sh variable1 variable2` te devolverá igualmente `variable1`.

Esto mismo lo puedes hacer con el resto de variables que se definen por defecto para los scripts. Así, puedes obtener el número de argumentos o incluso todos los argumentos.

Es probable que te preguntes que pasa cuando tienes 10 argumentos, ¿Como tratar el décimo argumento?¿y los demas?

Puedes obtener el décimo argumento utilizando `echo "${10}"`, y así sucesivamente.

### Las comillas en Bash

En el ejemplo anterior cuando he _escrito_ el valor `valor` en la `variable`, lo he hecho directamente,

    variable=valor
    

esto es así porque es un valor sencillo. Sin embargo, ¿que ocurrirá si queremos guardar `un valor`?

    variable=un valor
    

Si lo ejecutans en el intérprete de comandos (_shell_) te devolverá `valor: orden no encontrada`. Esto es así por que Bash **utiliza el espacio para separar elementos**. Así cuando quieres hacer asignaciones que contienen espacios, deberás utilizar las comillas. Pero, **¿que comillas utilizar?**. **Comillas simples o dobles**.

Es **importante** que tengas en cuenta que en Bash no todas **las comillas son iguales**. No es lo mismo las simples que las dobles comillas.

Con las comillas simples se guarda en la variable **literalmente** lo que hay entre ellas. Con las comillas dobles se **interpreta** el contenido.

Fíjate, bien en este par de ejemplos. Primero con comillas simples,

    #!/bin/bash
    variable1=Juan
    variable2='Esta es la casa de $variable1'
    echo variable2
    

La respuesta es `Esta es la casa de $variable1`. Es decir, que a `variable2` le has asignado estrictamente su contenido.

En cambio si utilizas comillas dobles, como en el siguiente script,

    #!/bin/bash
    variable1=Juan
    variable2="Esta es la casa de $variable1"
    echo variable2
    

El resultado es el esperado `Esta es la casa de Juan`. Así es **muy importante** que entiendas que **las comillas simples son distintas de las dobles**. Tratan el contenido de forma completamente diferente.

Por último nos quedan las comillas inclinadas. Estas se utilizan como en el caso de la asignación de comandos a variables, tal y como puedes ver en el siguiente apartado.

### Asignación de comandos a variables

En tus scripts, querrás guardar en una variable el resultado de la ejecución de un comando para poder tratarlo posteriormente. Por ejemplo, si quisieras saber cuantos archivos hay en un directorio, ejecutarías la siguiente instrucción, utilizando el comando `find` para [buscar archivos](https://atareao.es/tutorial/terminal/buscar-archivos-en-el-terminal/) y el comando `wc` para [contar líneas](https://atareao.es/tutorial/terminal/procesar-texto-con-head-tail-cat-split/),

    find . -maxdepth 1 -type f | wc -l
    

Así, para tu script, donde quieres mostrar un texto donde se indique el número de archivos de un directorio, lo puedes hacer como,

    #!/bin/bash
    archivos=$(find . -maxdepth 1 -type f | wc -l)
    echo "Hay $archivos archivos"
    

Como te decía en el apartado anterior, esto mismo se puede hacer con las _comillas inclinadas_, tal y como puedes ver a continuación,

    #!/bin/bash
    archivos=`find . -maxdepth 1 -type f | wc -l`
    echo "Hay $archivos archivos"
    

Y, para perfeccionar el script anterior, puedes añadir también los directorios. De esta forma te quedará,

    #!/bin/bash
    archivos=$(find . -maxdepth 1 -type f | wc -l)
    directorios=$(find . -maxdepth 1 -type d | wc -l)
    echo "Hay $archivos archivos y $directorios directorios"
    

En mi caso, al ejecutar este script en mi directorio de inicio, me devuelve `Hay 15 archivos y 16 directorios`.

Todo lo que está entre `$()` se ejecuta, y el resultado se asigna a la variable. Ten en cuenta que si el resultado tiene varias líneas, se mostrará como una línea sola. Por ejemplo,

    #!/bin/bash
    listado=$(ls)
    echo $listado
    

Te devolverá algo como lo siguiente,

    lrwxrwxrwx 1 lorenzo lorenzo 11 abr 22 18:09 apps -> /datos/apps lrwxrwxrwx 1 lorenzo lorenzo 30 abr 22 18:09 .audacity-data -> /datos/dotfiles/.audacity-data -rw------- 1 lorenzo lorenzo 99K may 22 06:59 .bash_history 
    

Donde si te das cuenta, y no con el resultado del ejemplo que yo te he puesto, si no probándolo tu mismo, se han **reemplazado los saltos de línea por espacios**. Esto lo tienes que tener muy en cuenta a la hora de mostrar tus resultados.

### Operaciones aritméticas

Por otro lado, además de la asignación del resultado de la ejecución de comandos, también es posible realizar la asignación de operaciones matemáticas. Para ello, en lugar de utilizar una sola pareja de paréntesis, tienes que utilizar dos parejas. Así, por ejemplo, para asignar el resultado de `2*2`, tendrás que realizar lo siguiente,

    #!/bin/bash
    resultado=$((2*2))
    echo $resultado
    

Por supuesto que puedes operar con el propio resultado de la operación. Por ejemplo,

    #!/bin/bash
    resultado=$((2*2))
    resultado=$((2*$resultado))
    echo $resultado
    

### Operaciones con texto

Al igual que es posible realizar operaciones aritméticas en el terminal, de la forma tan sencilla como te he indicado en el apartado anterior, también es posible trabajar con texto.

Si quieres ver todas las posibilidades que tienes, te recomiendo leas el artículo sobre [manipular texto en el terminal](https://atareao.es/como/manipulando-texto-en-el-terminal/). Sin embargo, indicarte algunas operaciones que seguro te serán de utilidad,

*   Para extraer parte de un texto, es tan sencillo como `echo ${cadena:1:2}`
*   En el caso de que lo que quieras hacer es sustituir texto la operación es `echo ${texto/de/a}`
*   Eliminar todas las apariciones de un texto dentro de otro `echo ${texto//de}`

Estas operaciones las puedes hacer tanto con texto como con números. Al final, al utilizar `${}` lo trata como si fuera un texto.

Como te digo, esto no son mas que unas pocas operaciones que puedes hacer con texto. Si quieres leer mas sobre este tema, lee sobre [manipular texto en el terminal](https://atareao.es/como/manipulando-texto-en-el-terminal/).