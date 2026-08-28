# sshfs



## Montaje

Para poder montar un directorio, el usuario de SSH necesita poder acceder a él. Invoque sshfs para montar un directorio remoto:

    $ sshfs [usuario@]host:[dir] puntodemontaje [opciones]

Por ejemplo:

    $ sshfs miusuario@micomputadora:/ruta/remota /ruta/local -C -p 9876

Aquí -p 9876 especifica el número de puerto y -C activa la compresión. Para más opciones véase la sección #Opciones.

Y para sólo lectura:

    sshfs user@123.123.123.123:/home/user /media/mountpoint/ -o allow_other -o ro

 * `-o allow_other` => allow access by all users

### Desmontaje

    $ umount /ruta/local

## Opciones

sshfs' puede convertir automáticamente entre ID de usuarios locales y remotos. Utilice la opción idmap=user para traducir el UID del usuario que se conecta al usuario remoto miusuario (GID permanece sin cambios):

    $ sshfs miusuario@micomputadora:/ruta/remota /ruta/local -o idmap=user

Si necesita más control sobre la traducción de UID y GID, mire las opciones idmap=file, uidfile y gidfile.


Bibliografía
------------
 * https://wiki.archlinux.org/title/SSHFS_(Espa%C3%B1ol)
 * https://geekland.eu/montar-sistema-archivos-remoto-con-sshfs/