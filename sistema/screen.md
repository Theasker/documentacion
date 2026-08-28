# Screen

Es un multiplexor de terminales linux, es decir, que puedes comenzar una sesión de screen y seguirá ejecutándose aunque haya un corte o desconexión.

## Atajos de teclado

 * `Ctrl+a ?` : Ayuda
 * `screen -S <session name>` : Inicia una sesión de screen con el nombre indicado  (ej: `screen -S mysession`)
 * `Ctrl+a c` : Crea una nueva ventana asociada a la sesión creada que va de 0 a 9
 * `Ctrl+a "` : Lista todas las ventanas activas de screen.
 * `Ctrl+a 0` : Cambia a ventana número 0
 * `Ctrl+a A` : Cambia el nombre de la ventana activa.
 * `Ctrl+a S` : Divide la región actual horizontalmente en dos regiones.
 * `Ctrl+a |` : Divide la región actual verticalmente en dos regiones.
 * `Ctrl+a tab` : Cambia el foco a la siguiente región.
 * `Ctrl+a Ctrl+a` : Alternar entre la ventana actual y la anterior
 * `Ctrl+a Q` : Cierra todas las ventanas menos la actual.
 * `Ctrl+a X` : Cierra la ventana actual.

## Desatachar una sesión screen

 * `Ctrl+a d` : Despega la sesión activa
 * `screen -r` : Reanuda la sesión de screen que se ha desatachado
 * `screen -ls` : Lista las sesiones activas

```
There are screens on:
    10835.pts-0.linuxize-desktop   (Detached)
    10366.pts-0.linuxize-desktop   (Detached)
2 Sockets in /run/screens/S-linuxize.
```

Para restaurar la sesión 10835.pts-0 haríamos:

`screen -r 10835`

