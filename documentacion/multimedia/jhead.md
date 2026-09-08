# JHead

## Arreglar rápidamente las fechas de las fotos con jhead
Estándar
A veces nos encontramos con unas cuantas fotos que tienen la fecha mal puesta. Suele pasar con fotos que no son nuestras, y cuyo propietario no controla mucho y no sabe ni que la cámara tiene hora, no se ha acordado de poner en hora la cámara al cambiar la batería, o cosas así. La solución “estándar” sería importar las fotos a nuestro programa de gestión de fotos favoritos, y retocarlas allí, pero a veces esa no es la mejor opción, y puede ser más fácil arreglar el problema antes de hacer la importación.

Buscando un programita para estas tareas sencillas, me he encontrado con jhead. Es una utilidad de línea de comandos, sí, pero su uso es muy sencillo y extremadamente rápido para ciertas tareas repetitivas. Por ejemplo, el típico caso de una cámara que tenía la hora mal puesta lo solucionaríamos con algo así como:

`jhead -ta+01:30 *.jpg`

Así le decimos que nos sume 1h30 a todas las fotos. ¿Que no queremos hacer números? No hay problema, también podemos indicarle la fecha “buena” y la “mala”, y él calcula la diferencia:

`jhead -da2014:12:16/13:24-2009:06:06/12:20 *.jpg`

También permite cosas más sutiles. Por ejemplo, a veces tenemos un montón de fotos mezcladas de diferentes cámaras, pero sólo una de las cámaras tiene la fecha mal puesta. No hay problema, podemos decirle que sólo actúe sobre las fotos de una cámara en concreto:

`jhead -model “FinePix S6500fd” -ta+02.37 *.jpg`

O puede que los ficheros no tengan información EXIF porque la cámara es viejuna y no soportaba estas filigranas, pero la fecha de modificación de los ficheros en el sistema de ficheros sí es correcta y queremos incorporarla como fecha EXIF:

`jhead -mkexif -dsft *.jpg`

En fin, que se pueden hacer muchas cosas de forma muy fácil y rápida. Además, jhead está en todos los repositorios, por lo que la instalación es sencillísima. En definitiva, todo un descubrimiento que nos puede ahorrar tiempo y esfuerzo.
