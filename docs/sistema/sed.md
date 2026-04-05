# Comando `sed`

## Uso básico
De maneral general, sed opera sobre un flujo de texto que es leido desde un archivo o desde "standard input". Esto quiere decir que se puede enviar la salida de un comando directamente a sed para ser editado o tu puedes indicar un archivo que ya este previamente creado.

Por otro lado, el resultado o salida de sed es enviado por default a la pantalla. O si es redireccionado será enviado a la archivo que indiques.

Modos básicos es:
```bash
$ sed [opciones] comandos [archivo_a_editar]
$ sed [opciones] comandos archivo > archivo_de_salida    
$ otro_comado | sed [opciones] comandos
$ otro_comado | sed [opciones] comandos > archivo_de_salida
```

`sed` opera siempre línea por línea, es decir, acepta una línea, opera en ella con el o los comandos indicados y después muestra el texto resultante en la salida antes de operar con la siguiente línea.

## Imprimiendo líneas



## Bibliografía
* https://www.linuxtotal.com.mx/index.php?cont=sed-manipular-texto-basico-1