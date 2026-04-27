# Sistema de ficheos XFS

Formatear partición
```bash
# mkfs.xfs /dev/vdb1 
meta-data=/dev/vdb1              isize=512    agcount=4, agsize=1310592 blks
         =                       sectsz=512   attr=2, projid32bit=1
         =                       crc=1        finobt=1, sparse=1, rmapbt=0
         =                       reflink=1    bigtime=1 inobtcount=1 nrext64=0
data     =                       bsize=4096   blocks=5242368, imaxpct=25
         =                       sunit=0      swidth=0 blks
naming   =version 2              bsize=4096   ascii-ci=0, ftype=1
log      =internal log           bsize=4096   blocks=16384, version=2
         =                       sectsz=512   sunit=0 blks, lazy-count=1
realtime =none                   extsz=4096   blocks=0, rtextents=0
Discarding blocks...Done.
```

Copia referenciada que es como hacer enlace duro:
```bash
cp -a -R --reflink=always usr refusr
```
Con esto no se reducirá el tamaño usado en el disco.

## xfs_freeze
Congelar un sistema de archivos de sólo lectura. Se suele usar para copias de seguridad.
Cuando usamos esta opción se paraliza la escritura en el sistema de archivos, pero el Sistema Operativo sigue aceptando las solicitudes de escritura, que se ejecutarán cuando se elimine este "congelado", por lo que esto se puede hacer en "caliente" para hacer la copia de seguridad y cuando haya finalizado se actualizarán todas las solicitudes de escritura que el Sistema Operativo ha recibido.
```bash
xfs_freeze -f <punto_montaje>
```
```bash
xfs_freeze -f /mnt/xfs
```

Para desbloquear o descongelar:
```bash
xfs_freeze -u <punto_montaje>
```
```bash
xfs_freeze -u  /mnt/xfs
```

## Copias de seguridad `xfsdump`
Primero hay que instalar la aplicación:
```bash
apt install xfsdump
```

Para usarlo sería conveniente hacer antes un `xfs_freeze` para que no se modifique el sistema de archivos mientras se hace la copia de seguridad. Esta copia es, en resumen, un fichero tar sin compresión de los archivos de 
Ahora hacemos la copia:
```bash
xfsdump -l 0 -f backup1.back /xfs
```
* `-l`: Indica el índice de backup, en este caso el 0, ya que es el primer backup
* `-f`: Fichero que se va a crear

Para restaurar la copia usaremos `xfsrestore`

## xfs_copy
Copia a nivel de bloques, que es como `dd` de linux
```bash
xfs_copy -d /dev/vdb1 xfscopy.back
```

Para restaurar esa copia:
```bash
xfs_copy -d xfscopy.back /dev/vdb1
```

También se puede hacer una migración de un dispositivo a otro
```bash
xfs_copy -d /dev/vdb1 /dev/vdc1
```
Si positeriormente el sistema de archivos hay que aumentarlo, se puede hacer con el comando `xfs_grow
```bash
xfs_grow <punto de montaje>
```

## Reparar una partición
```bash
xfs_repair <dispositivo>
```
## Desfragmentar una
```bash
# xfs_fsr 
xfs_fsr -m /proc/mounts -t 7200 -f /var/tmp/.fsrlast_xfs ...
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
/xfs start inode=0
Completed all 10 passes
```
