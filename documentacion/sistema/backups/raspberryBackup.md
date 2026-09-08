## Backup del sistema de nuestra Raspberry

### Backup en caliente de la Raspberry

#### image-backup:

Ejecutando `image-backup`sin parámetros creará un backup completo:

    # image-backup

Si ponemos como parámetro una imagen ya creada, hará una imagen incremental

    # image-backup <fichero_existente.img>

#### image-check:
image-check will check the integrity of a standard 'raw' image file. Usage is:

    image-check imagefile [W95|Linux]

Where W95 checks the BOOT partition and Linux checks the ROOT partition. If neither is specified, Linux is assumed.

#### image-compare:

image-compare compares a running Raspbian system to an existing standard 'raw' image file and displays the incremental changes that image-backup would perform if run. Usage is:

    image-compare [imagefile]

#### image-mount:

image-mount mounts a standard 'raw' image file to allow it to be read or written as if it were a device. Usage is:

    image-mount imagefile mountpoint [W95|Linux]

where W95 mounts the BOOT partition and Linux mounts the ROOT partition. If neither is specified, Linux is assumed.

#### image-set-ptuuid:

image-set-ptuuid sets the Partition Table UUID value of a standard 'raw' image file. Usage is:

    image-set-ptuuid imagefile ptuuid

where ptuuid is 8 hex digits

#### image-shrink:

image-shrink shrinks a standard 'raw' image file to its smallest possible size (plus an optional additional amount of free space). Usage is:

    image-shrink imagefile [Additional MB]

where Additional MB is an additional amount of free space to be added.

Bibliografía
------------
 * https://foratdot.info/como-clonar-raspberry-pi-a-microsd-en-caliente/
 * https://github.com/seamusdemora/RonR-RPi-image-utils