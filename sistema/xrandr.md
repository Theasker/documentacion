# Cómo cambiar la resolución de pantalla usando xrandr

## Cómo usar xrandr

Primero, listá las distintas resoluciones disponibles para tu monitor:

    xrandr -q

En caso de que la resolución que buscás no aparezca listada, ello puede deberse a que tu monitor realmente no la soporte o que necesites instalar un mejor driver (ati, intel, o nvidia).

Luego, establecé la resolución que quieras utilizar (cambiá “1400×1050″ por la resolución deseada):

    xrandr -s 1920x1080

**NOTA:**

Con la aplicación **arandr** se puede generar el comando xrandr de la configuración actual de pantallas.

Bibliografía
------------
https://blog.desdelinux.net/como-cambiar-la-resolucion-de-pantalla-usando-xrandr/