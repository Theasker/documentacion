# Clonar la tarjeta SD de la raspberry en caliente

> https://foratdot.info/como-clonar-raspberry-pi-a-microsd-en-caliente/

El proyecto es **RonR-RaspberryPi-image-utils** y está en la página https://github.com/scruss/RonR-RaspberryPi-image-utils

## Pasos para hacer un backup

Clonamos el repositorio

    git clone https://github.com/scruss/RonR-RaspberryPi-image-utils

Doy permiso para ejecución de los archivos:

    cd RonR-RaspberryPi-image-utils/
    chmod 700 *

Ejecuto la aplicación:

    $ sudo ./image-backup

    Image file to create? /mnt/datos/temp/backup.img

    Initial image file ROOT filesystem size (MB) [10117]?

    Added space for incremental updates after shrinking (MB) [0]?

    Create /mnt/datos/temp/backup.img (y/n)? y

    Starting full backup (for incremental backups, run: ./image-backup /mnt/datos/temp/backup.img)

## Pasos para hacer un restore

Introducimos el pendrive con la tarjeta SD vacía y vemos en qué unidad lo detecta nuestra raspberry con `lsblk --fs`. Una vez que sabemos la unidad, podemos realizar la restauración/clonado del backup de varias maneras:

    $ sudo ./image-restore /mnt/datos/temp/backup.img

O también usando `dd`:

    dd bs=4M status=progress if=/ruta/a/la/imagen/backup.img of=/dev/<unidad> conv=fdatasync

