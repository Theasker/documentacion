# Copias de seguridad con Borg backup

## Crear el repositorio remoto

Es donde se almacernarán las copias de seguridad.

   borg init --encryption=repokey /media/backup/borgdemo

o

   borg init -e repokey-blake2 borgbackup@BACKUPHOSTNAME:/home/borgbackup/borgbackup/

Yo he hecho:

   $ borg init --encryption=none ubuntu@podereuropeo.duckdns.org:/home/ubuntu/borgbackups

## Crear un backup a un repositorio remoto

Por ssh especificando el puerto

    $ sudo borg create --list -p --stats ssh://ubuntu@podereuropeo.duckdns.org:22/home/ubuntu/borgbackups::ahora07 ./docker /home/ubuntu/documents

Al día siguiente haremos otro backup

    $ borg create --list --stats /path/to/repo::Tuesday ~/src ~/Documents

Dando la información y agregando fecha y hora:

    $ sudo borg create --list -vp --stats ssh://ubuntu@podereuropeo.duckdns.org:22/home/ubuntu/borgbackups::$(hostname)_$(date
 +%Y-%m-%d_%H:%M:%S)_docker /home/ubuntu/docker --info

## Crear un backup localmente de un host remoto
```bash
# Backup a remote host locally ("pull" style) using sshfs
$ mkdir sshfs-mount
$ sshfs root@example.com:/ sshfs-mount
$ cd sshfs-mount
$ borg create /path/to/repo::example.com-root-{now:%Y-%m-%d} .
$ cd ..
$ fusermount -u sshfs-mount
```

## Listar los backups de un repositorio

    $ sudo borg list ssh://ubuntu@podereuropeo.duckdns.org:22/home/ubuntu/borgbackups

Listar el contenido de un backup específico

    $ sudo borg list ssh://ubuntu@podereuropeo.duckdns.org:22/home/ubuntu/borgbackups::theasker-20220727_2022-11-02_07:
29:40_docker

## Restaurar un backup

Restaurar un backup específico

    $ borg extract /path/to/repo::Monday

Podemos descargar un backup comprimido

    borg export-tar /path/to/repo::Monday Monday.tar.gz --exclude '*.so'
    
## Montar backups

Para elegir los archivos especícos podemos montar un backup específico y tendremos acceso a todos los ficheros de ese backup:

    $ sudo borg mount ssh://ubuntu@podereuropeo.duckdns.org:22/home/ubuntu/borgbackups::backup01 /mnt/borgbackup

Si no especificamos ningún backup se montarán todos como directorios:

    $ sudo borg umount ssh://ubuntu@podereuropeo.duckdns.org:22/home/ubuntu/borgbackups /mnt/borgbackup

## Borrar backups

Borrar un backup específico (no libera el espacio)

    $ borg delete /path/to/repo::Monday

Recupere espacio en disco compactando los archivos de segmento en el repositorio:

    $ borg compact /path/to/repo


Bibliografía
------------
 * https://www.borgbackup.org/demo.html
 * https://borgbackup.readthedocs.io/en/stable/index.html
 * https://www.linuxsysadmin.ml/2019/05/haciendo-backups-con-borgbackup.html
 * https://howtoforge.es/copias-de-seguridad-solo-con-borg-en-otro-vps-o-servidor-dedicado/
 * https://www.cloudcenterandalucia.es/blog/una-historia-sobre-backups-borg-la-resistencia-es-inutil/
 * https://atareao.es/podcast/hice-un-rm-rf-salvado-por-borg/
 * Ejemplo de copia de seguridad de base de datos docker: https://immich.app/docs/guides/template-backup-script/