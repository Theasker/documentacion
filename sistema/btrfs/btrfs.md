# Sistema de ficheros btrfs

- [Sistema de ficheros btrfs](#sistema-de-ficheros-btrfs)
  - [Sistemas de archivos simple](#sistemas-de-archivos-simple)
  - [Soporte multidispositivo](#soporte-multidispositivo)
    - [Creación de sistema con 2 discos](#creación-de-sistema-con-2-discos)
    - [Soporte RAID](#soporte-raid)
    - [Añadir dispostivo al RAID](#añadir-dispostivo-al-raid)
  - [Creación de subvolumnes y snapshots](#creación-de-subvolumnes-y-snapshots)
    - [Creación de subvolúmenes](#creación-de-subvolúmenes)
    - [Borrado de volúmenes](#borrado-de-volúmenes)
    - [Creación de snapshots](#creación-de-snapshots)
  - [Redimensión de espacio](#redimensión-de-espacio)
  - [Recuperación del sistema de ficheros](#recuperación-del-sistema-de-ficheros)
    - [Crear un RAID5](#crear-un-raid5)
    - [Cómo montar un disco RAID](#cómo-montar-un-disco-raid)
      - [Visualizar el estado de un sistema de ficheros Btrfs](#visualizar-el-estado-de-un-sistema-de-ficheros-btrfs)
    - [Equilibrar los datos entre todos los discos BTRFS](#equilibrar-los-datos-entre-todos-los-discos-btrfs)
    - [Cómo sustituir o añadir una unidad](#cómo-sustituir-o-añadir-una-unidad)
    - [Cómo reparar un volumen dañado](#cómo-reparar-un-volumen-dañado)
  - [Fragmentación](#fragmentación)
  - [Compresión al vuelo](#compresión-al-vuelo)
    - [Ajustar compresión en archivo /etc/fstab](#ajustar-compresión-en-archivo-etcfstab)
    - [Ajustes](#ajustes)
  - [BTRFS con timeshift snapshots en el menu grub](#btrfs-con-timeshift-snapshots-en-el-menu-grub)
    - [`timeshift-autosnap`](#timeshift-autosnap)
    - [Snapshots en Grub](#snapshots-en-grub)
  - [Bibliografía](#bibliografía)


## Sistemas de archivos simple

Crear un sistema de fichero btrfs de un sólo disco

    # mkfs.btrfs /dev/vde

## Soporte multidispositivo

### Creación de sistema con 2 discos

Crear un sistema de ficheros btrfs de 2 discos, que detecta como RAID 1

    # mkfs.btrfs /dev/sde4 /dev/sde5 -f
    btrfs-progs v5.19
    See http://btrfs.wiki.kernel.org for more information.

    NOTE: several default settings have changed in version 5.15, please make sure
        this does not affect your deployments:
        - DUP for metadata (-m dup)
        - enabled no-holes (-O no-holes)
        - enabled free-space-tree (-R free-space-tree)

    Label:              (null)
    UUID:               23ab11aa-b88b-42c5-b16d-3288c8d5270f
    Node size:          16384
    Sector size:        4096
    Filesystem size:    6.00GiB
    Block group profiles:
    Data:             single            8.00MiB
    Metadata:         RAID1           256.00MiB
    System:           RAID1             8.00MiB
    SSD detected:       no
    Zoned device:       no
    Incompat features:  extref, skinny-metadata, no-holes
    Runtime features:   free-space-tree
    Checksum:           crc32c
    Number of devices:  2
    Devices:
    ID        SIZE  PATH
        1     2.00GiB  /dev/sde4
        2     4.00GiB  /dev/sde5

Ahora montamos cualquiera de las 2 particiones y vemos su tamaño:

    # mount /dev/sde4 /mnt/temp/
    # lsblk -f /dev/sde
    NAME   FSTYPE FSVER LABEL UUID                                 FSAVAIL FSUSE% MOUNTPOINTS
    sde                                                                           
    ├─sde1 btrfs              d2faf3b9-af59-4f3e-990b-75a97424454b                
    ├─sde2 btrfs              198788ef-a66a-443c-b048-a85dc33e4d8f                
    ├─sde3 btrfs              b3879a79-9a53-46d4-9c99-3cfb87caafa9                
    ├─sde4 btrfs              23ab11aa-b88b-42c5-b16d-3288c8d5270f    5,5G     0% /mnt/temp
    └─sde5 btrfs              23ab11aa-b88b-42c5-b16d-3288c8d5270f 

### Soporte RAID

Tres dispositivos con distinto tamaño. Para ello vamos a crear en este caso un RAID 1 (aunque ademas del RAID1, podremos crear un raid 0, raid 5, raid 6 y raid 10).

    # lsblk | tail -3
    ├─sde3   8:67   0     1G  0 part 
    ├─sde4   8:68   0     2G  0 part /mnt/temp
    └─sde5   8:69   0     4G  0 part 

Estos tres dispositivos los crearemos en raid 1 con el siguiente comando(tendremos que indicarle nuevamente la opción -f por que estamos utilizando discos usados, así que con esa opción frozamos el formateo como hemos realizado anteriormente).

    # mkfs.btrfs -d raid1 /dev/sde3 /dev/sde4 /dev/sde5 -f
    btrfs-progs v5.19
    See http://btrfs.wiki.kernel.org for more information.

    NOTE: several default settings have changed in version 5.15, please make sure
        this does not affect your deployments:
        - DUP for metadata (-m dup)
        - enabled no-holes (-O no-holes)
        - enabled free-space-tree (-R free-space-tree)

    Label:              (null)
    UUID:               d3f081bc-eb1d-4092-88f0-b52a517c4a81
    Node size:          16384
    Sector size:        4096
    Filesystem size:    7.00GiB
    Block group profiles:
    Data:             RAID1           358.38MiB
    Metadata:         RAID1           256.00MiB
    System:           RAID1             8.00MiB
    SSD detected:       no
    Zoned device:       no
    Incompat features:  extref, skinny-metadata, no-holes
    Runtime features:   free-space-tree
    Checksum:           crc32c
    Number of devices:  3
    Devices:
    ID        SIZE  PATH
        1     1.00GiB  /dev/sde3
        2     2.00GiB  /dev/sde4
        3     4.00GiB  /dev/sde5

Una vez creado dicho RAID 1 mostraremos los dispositivos con el siguiente comando y veremos como se ha creado correctamente.

    # btrfs filesystem show
    Label: none  uuid: d2faf3b9-af59-4f3e-990b-75a97424454b
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 10.00GiB used 536.00MiB path /dev/sde1

    Label: none  uuid: 198788ef-a66a-443c-b048-a85dc33e4d8f
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 1.00GiB used 126.38MiB path /dev/sde2

    Label: none  uuid: d3f081bc-eb1d-4092-88f0-b52a517c4a81
        Total devices 3 FS bytes used 144.00KiB
        devid    1 size 1.00GiB used 0.00B path /dev/sde3
        devid    2 size 2.00GiB used 622.38MiB path /dev/sde4
        devid    3 size 4.00GiB used 622.38MiB path /dev/sde5

Comprobamos como está montado

    # lsblk -f /dev/sde
    NAME   FSTYPE FSVER LABEL UUID                                 FSAVAIL FSUSE% MOUNTPOINTS
    sde                                                                           
    ├─sde1 btrfs              d2faf3b9-af59-4f3e-990b-75a97424454b                
    ├─sde2 btrfs              198788ef-a66a-443c-b048-a85dc33e4d8f                
    ├─sde3 btrfs              bf9f5e69-cf78-4158-b85d-93711b9e97f4    1,7G     0% /mnt/temp
    ├─sde4 btrfs              bf9f5e69-cf78-4158-b85d-93711b9e97f4                
    └─sde5 btrfs              bf9f5e69-cf78-4158-b85d-93711b9e97f4

### Añadir dispostivo al RAID

    # btrfs device add /dev/sde2 /mnt/temp -f

Vemos como ha queado el RAID

    # btrfs filesystem show
    Label: none  uuid: bf9f5e69-cf78-4158-b85d-93711b9e97f4
        Total devices 4 FS bytes used 144.00KiB
        devid    1 size 1.00GiB used 0.00B path /dev/sde3
        devid    2 size 2.00GiB used 622.38MiB path /dev/sde4
        devid    3 size 4.00GiB used 622.38MiB path /dev/sde5
        devid    4 size 1.00GiB used 0.00B path /dev/sde2

    Label: none  uuid: d2faf3b9-af59-4f3e-990b-75a97424454b
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 10.00GiB used 536.00MiB path /dev/sde1

Como vemos, se a añadido correctamente pero el uso del dispositivo esta a 0.00B , por ello vamos a activar el balanceo de carga para que se reparta la información entre los discos, incluyendo el nuevo que hemos añadido.

    # btrfs balance start --full-balance /mnt/temp
    Done, had to relocate 3 out of 3 chunks

Acto seguido vamos a escribir en el disco, en nuestro caso vamos a utilizar el comando dd para llenar los bloques con ceros hasta el maximo que pueda retener nuestro RAID.

    # dd if=/dev/zero of=/mnt/temp/prueba
    dd: escribiendo en '/mnt/temp/prueba': No queda espacio en el dispositivo
    7792346+0 records in
    7792345+0 records out
    3989680640 bytes (4,0 GB, 3,7 GiB) copied, 310,686 s, 12,8 MB/s

Como hemos dicho anteriormente cobre la flexibilidad del RAID, en este caso hemos podido mas de lo que un RAID tradicional permite ya que uno tradicional se habría adaptado al mas pequeño y solo podriamos haber escrito 1G, pero como BTRFS va repartiendo la información puede aprovechar la memoria en el caso de que los dispositivos tengan diferentes capacidades (pero siempre hay que tener en cuenta que no se aprovecha toda la totalidad de todo el almacenamiento).
Acto seguido vemos de nuevo la información de los dispositivos y vemos como ya se ha ido escribiendo en los 4 dispositivos por igual.

    # btrfs filesystem show
    Label: none  uuid: bf9f5e69-cf78-4158-b85d-93711b9e97f4
        Total devices 4 FS bytes used 3.72GiB
        devid    1 size 1.00GiB used 1023.00MiB path /dev/sde3
        devid    2 size 2.00GiB used 2.00GiB path /dev/sde4
        devid    3 size 4.00GiB used 4.00GiB path /dev/sde5
        devid    4 size 1.00GiB used 1023.00MiB path /dev/sde2

    Label: none  uuid: d2faf3b9-af59-4f3e-990b-75a97424454b
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 10.00GiB used 536.00MiB path /dev/sde1

En el caso de que queramos saber el estado en el que se encuentra nuestro raid (de cara a la integridad de datos) tendremos que utilizar el siguiente comando en el cual tendremos que indicarle el punto de montaje en el que se encuentra el RAID. Pero antes se pasaremos el comprobador de errores indicando el punto de montaje.

    # btrfs scrub start /mnt/temp
    scrub started on /mnt/temp, fsid bf9f5e69-cf78-4158-b85d-93711b9e97f4 (pid=27003)

Acto seguido le pasamos el siguiente comando para ver como va el proceso y si existe algun error.

    # btrfs scrub status /mnt/temp
    UUID:             bf9f5e69-cf78-4158-b85d-93711b9e97f4
    Scrub started:    Tue Oct 18 19:51:52 2022
    Status:           finished
    Duration:         0:13:04
    Total to scrub:   7.44GiB
    Rate:             9.72MiB/s
    Error summary:    no errors found

Si deseamos eliminar algun dispositivo por que se ha dañado algun disco tendremos que añadir un nuevo disco y acto seguido eliminar el dañado para que así se pueda restaurar el raid correctamente y se pueda retirar el disco dañado.
Para empezar vemos los dispositivos y comprobamos como existe un fallo en uno de ellos.

    # btrfs device add /dev/vdf /mnt/ -f
    # btrfs device delete /dev/vdb /mnt

## Creación de subvolumnes y snapshots

Primero vamos a crear el sistema de ficheros btrfs:
```bash
    # lsblk /dev/sde
    NAME   MAJ:MIN RM   SIZE RO TYPE MOUNTPOINTS
    sde      8:64   0 111,8G  0 disk 
    ├─sde1   8:65   0    10G  0 part 
    ├─sde2   8:66   0     1G  0 part 
    ├─sde3   8:67   0     1G  0 part 
    ├─sde4   8:68   0     2G  0 part 
    └─sde5   8:69   0     4G  0 part 
    
    # mkfs.btrfs /dev/sde1 /dev/sde2 /dev/sde3 /dev/sde4 /dev/sde5 -f -L "Multiples_vol"
    btrfs-progs v5.19
    See http://btrfs.wiki.kernel.org for more information.

    NOTE: several default settings have changed in version 5.15, please make sure
        this does not affect your deployments:
        - DUP for metadata (-m dup)
        - enabled no-holes (-O no-holes)
        - enabled free-space-tree (-R free-space-tree)

    Label:              Multiples_vol
    UUID:               2e8f51bf-ebcf-4a86-ba86-c66c32884421
    Node size:          16384
    Sector size:        4096
    Filesystem size:    18.00GiB
    Block group profiles:
    Data:             single            8.00MiB
    Metadata:         RAID1           256.00MiB
    System:           RAID1             8.00MiB
    SSD detected:      
  no
    Incompat features:  extref, skinny-metadata, no-holes
    Runtime features:   free-space-tree
    Checksum:           crc32c
    Number of devices:  5
    Devices:
    ID        SIZE  PATH
        1    10.00GiB  /dev/sde1
        2     1.00GiB  /dev/sde2
        3     1.00GiB  /dev/sde3
        4     2.00GiB  /dev/sde4
        5     4.00GiB  /dev/sde5
```
### Creación de subvolúmenes

Ahora crearemos los subvolumentes.
Los subvolumenes son dinamicos lo que quiere decir que iran expandiendo cuando sea necesario y se tiene que crear estando el dispositivo montado.
```bash
# mount /dev/sde1 /mnt/temp

root@theasker-proliantml110g5 /home/theasker # btrfs subvolume create /mnt/temp/sub1
Create subvolume '/mnt/temp/sub1'
root@theasker-proliantml110g5 /home/theasker # btrfs subvolume create /mnt/temp/sub2
Create subvolume '/mnt/temp/sub2'
root@theasker-proliantml110g5 /home/theasker # btrfs subvolume create /mnt/temp/sub3
Create subvolume '/mnt/temp/sub3'
root@theasker-proliantml110g5 /home/theasker # btrfs subvolume create /mnt/temp/sub4
Create subvolume '/mnt/temp/sub4'
root@theasker-proliantml110g5 /home/theasker # btrfs subvolume create /mnt/temp/sub5
Create subvolume '/mnt/temp/sub5'
```
Ahora mostramos los subvolumenes creados:
```bash
    # btrfs subvolume list /mnt/temp 
    ID 256 gen 8 top level 5 path sub1
    ID 257 gen 9 top level 5 path sub2
    ID 258 gen 10 top level 5 path sub3
    ID 259 gen 11 top level 5 path sub4
    ID 260 gen 12 top l
evel 5 path sub5
```
En el caso en el queramos montar un subvolumen en concreto tendremos que utilizar el ID del subvolume y para ello visualizaremos las caracteristicas del subvolumen con el siguiente comando.
```bash
# btrfs subvolume show /mnt/temp/sub1
sub1
    Name: 			sub1
    UUID: 			c24c3297-a0ca-0747-b8ca-95df4bf1768b
    Parent UUID: 		-
    Received UUID: 		-
    Creation time: 		2022-10-18 21:20:02 +0200
    Subvolume ID: 		256
    Generation: 		8
    Gen at creation: 	8
    Parent ID: 		5
    Top level ID: 		5
    Flags: 			-
    Send transid: 		0
    Send time: 		2022-10-18 21:20:02 +0200
    Receive transid: 	0
    Receive time: 		-
    Snapshot(s):
```
Dicho Id lo utilizaremos para montarlo con el siguiente comando y así trabajar directamente con ese subvolumen.
```bash
# mount -o subvolid=256 /dev/sde1 /mnt/temp2
```
Realizaremos un df -h y veremos como esta ese subvolumen montado (de esta forma podremos acceder al subvolumen por dos vias que es donde esta el dispositivo creado y donde lo hemos montado que en nuestro caso seria en /mnt/temp/sub1 y /mnt/temp2).
```bash
    # df -h | grep sde
    /dev/sde1         18G   4,0M   18G   1% /mnt/temp
    /dev/sde1         18G   4,0M   18G   1% /mnt/temp2
```
### Borrado de volúmenes

Tendremos que ejecutar:
```bash
    # btrfs subvolume delete /mnt/temp/sub4
    Delete subvolume (no-commit): '/mnt/temp/sub4'

    # ls /mnt/temp
    sub1  sub2  sub3  sub5
```
### Creación de snapshots

Para copias de seguridad de un subvolumen con snapshot:

    # btrfs subvolume snapshot /mnt/temp/sub1/ /mnt/temp/snapshot_sub1
    Create a snapshot of '/mnt/temp/sub1/' in '/mnt/temp/snapshot_sub1'

Vemos como ha sido creado:

    # ls /mnt/temp
    snapshot_sub1  sub1  sub2  sub3  sub5

## Redimensión de espacio

Para esta prueba vamos a crear un sistema de archivos btrfs de una partición de 4Gb

    # mkfs.btrfs /dev/sde5 -f
    # mount /dev/sde5 /mnt/temp
    # btrfs filesystem show /mnt/temp
    Label: none  uuid: cc81d66e-a268-4e2f-b0d5-fbf60c4aa33d
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 4.00GiB used 536.00MiB path /dev/sde5

Vamos a quitar 1Gb al tamaño de la partición:

    # btrfs filesystem resize -1g /mnt/temp
    Resize device id 1 (/dev/sde5) from 4.00GiB to 3.00GiB

Y mostramos de nuevo la información de la partición:

    # btrfs filesystem show /mnt/temp
    Label: none  uuid: cc81d66e-a268-4e2f-b0d5-fbf60c4aa33d
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 3.00GiB used 536.00MiB path /dev/sde5

Para añadir 1g al tamaño:

    # btrfs filesystem resize +1g /mnt/temp
    Resize device id 1 (/dev/sde5) from 3.00GiB to 4.00GiB
    
    # btrfs filesystem show /mnt/temp
    Label: none  uuid: cc81d66e-a268-4e2f-b0d5-fbf60c4aa33d
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 4.00GiB used 536.00MiB path /dev/sde5

## Recuperación del sistema de ficheros

Vamos a hacer un ejemplo, de cómo recuperar la información de un disco dañado.

### Crear un RAID5

Primero crearemos un RAID5 con 5 discos:

    root@theasker-proliantml110g5 /mnt # lsblk /dev/sde
    NAME   MAJ:MIN RM   SIZE RO TYPE MOUNTPOINTS
    sde      8:64   0 111,8G  0 disk 
    ├─sde1   8:65   0    10G  0 part 
    ├─sde2   8:66   0     1G  0 part 
    ├─sde3   8:67   0     1G  0 part 
    ├─sde4   8:68   0     2G  0 part 
    └─sde5   8:69   0     4G  0 part 

    root@theasker-proliantml110g5 /mnt # mkfs.btrfs -L data -m raid5 -d raid5 -f /dev/sde1 /dev/sde2 /dev/sde3 /dev/sde4 /dev/sde5
    btrfs-progs v5.19
    See http://btrfs.wiki.kernel.org for more information.

    WARNING: RAID5/6 support has known problems is strongly discouraged
        to be used besides testing or evaluation.

    NOTE: several default settings have changed in version 5.15, please make sure
        this does not affect your deployments:
        - DUP for metadata (-m dup)
        - enabled no-holes (-O no-holes)
        - enabled free-space-tree (-R free-space-tree)

    Label:              data
    UUID:               2b0a78d6-de5c-454c-a3bd-aa4d0ca84a12
    Node size:          16384
    Sector size:        4096
    Filesystem size:    18.00GiB
    Block group profiles:
    Data:             RAID5             1.44GiB
    Metadata:         RAID5           204.75MiB
    System:           RAID5            12.75MiB
    SSD detected:       no
    Zoned device:       no
    Incompat features:  extref, raid56, skinny-metadata, no-holes
    Runtime features:   free-space-tree
    Checksum:           crc32c
    Number of devices:  5
    Devices:
    ID        SIZE  PATH
        1    10.00GiB  /dev/sde1
        2     1.00GiB  /dev/sde2
        3     1.00GiB  /dev/sde3
        4     2.00GiB  /dev/sde4
        5     4.00GiB  /dev/sde5

  * `L` - es una etiqueta o nombre del sistema de archivos
  * `d` - establecemos el tipo RAID5 para los datos.
  * `m` - establecemos el tip RAID5 para metadatos.
  * `f` - sirve para forzar la creación de btrfs incluso si alguna de las unidades está formateada en un sistema de archivos diferente.

### Cómo montar un disco RAID

Ahora, se puede montar utilizando cualquiera de las unidades que están incluidas.

    # mount /dev/sde1 /mnt/temp

    # sudo df -h .
    S.ficheros     Tamaño Usados  Disp Uso% Montado en
    /dev/sde1         18G   3,7M  7,8G   1% /mnt/temp

#### Visualizar el estado de un sistema de ficheros Btrfs

Y para ver la **información sobre el espacio ocupado** y libre de la matriz, introduzca:

    # sudo btrfs filesystem usage /mnt/temp
    Overall:
        Device size:		  18.00GiB
        Device allocated:		   2.07GiB
        Device unallocated:		  15.93GiB
        Device missing:		     0.00B
        Used:			 180.00KiB
        Free (estimated):		  14.19GiB	(min: 14.19GiB)
        Free (statfs, df):		   7.78GiB
        Data ratio:			      1.25
        Metadata ratio:		      1.25
        Global reserve:		   3.50MiB	(used: 0.00B)
        Multiple profiles:		        no

    Data,RAID5: Size:1.44GiB, Used:0.00B (0.00%)
    /dev/sde1	 368.62MiB
    /dev/sde2	 368.62MiB
    /dev/sde3	 368.62MiB
    /dev/sde4	 368.62MiB
    /dev/sde5	 368.62MiB

    Metadata,RAID5: Size:204.75MiB, Used:128.00KiB (0.06%)
    /dev/sde1	  51.19MiB
    /dev/sde2	  51.19MiB
    /dev/sde3	  51.19MiB
    /dev/sde4	  51.19MiB
    /dev/sde5	  51.19MiB

    System,RAID5: Size:12.75MiB, Used:16.00KiB (0.12%)
    /dev/sde1	   3.19MiB
    /dev/sde2	   3.19MiB
    /dev/sde3	   3.19MiB
    /dev/sde4	   3.19MiB
    /dev/sde5	   3.19MiB

    Unallocated:
    /dev/sde1	   9.59GiB
    /dev/sde2	 601.00MiB
    /dev/sde3	 601.00MiB
    /dev/sde4	   1.59GiB
    /dev/sde5	   3.59GiB

Para desmontar un array, basta con introducir:

    sudo umount /mnt/temp

### Equilibrar los datos entre todos los discos BTRFS
Si queremos rebalancear los datos que contiene cada uno de los discos de un FS BTRFS para que cada uno soporte el mismo volumen de carga, ejecutaremos el siguiente comando:

    # btrfs filesystem balance /mnt/temp
    Done, had to relocate 3 out of 3 chunks  

De esta manera evitamos que, por ejemplo, todos los datos estén almacenados en el disco /dev/sde1

### Cómo sustituir o añadir una unidad

Para reemplazar una unidad debe escribir btrfs replace en el terminal. Se ejecuta de forma asíncrona, es decir, se ejecuta gradualmente:

 * **start** - para iniciar,
 * **cancel** - para parar,
 * **status** - para ver el estado.

Lo primero que hay que hacer es determinar el número de la unidad dañada:

    # sudo btrfs filesystem show
    Label: 'data'  uuid: 2b0a78d6-de5c-454c-a3bd-aa4d0ca84a12
        Total devices 5 FS bytes used 144.00KiB
        devid    1 size 10.00GiB used 423.00MiB path /dev/sde1
        devid    2 size 1.00GiB used 423.00MiB path /dev/sde2
        devid    3 size 1.00GiB used 423.00MiB path /dev/sde3
        devid    4 size 2.00GiB used 423.00MiB path /dev/sde4
        devid    5 size 4.00GiB used 423.00MiB path /dev/sde5

Luego se debe cambiar por una nueva:

    btrfs replace start <dispositivo a eliminar o su ID> < dispositivo a añadir> <la ruta donde se monta el dispositivo btrfs>

Si fallara el disco 3

    # btrfs replace start 3 /dev/sde6 /mnt/temp

Donde: 3 – es el número de la unidad que falta a sustituir, y sde6 la unidad nueva que sustituye, y /mnt/temp donde esta montado el RAID5.

### Cómo reparar un volumen dañado

Para restaurar una matriz Btrfs, debemos utilizar la opción de montaje incorporada - recovery:

    # mount -o recovery /dev/sde1 /mnt/temp2

Para reparar un volumen btrfs que está corrupto o dañado no puede estar montado

    btrfs check /dev/sdb2

## Fragmentación

En esta caso práctico se muestra como realizar una fragmentación de un sistema de ficher BTRFS ya que para ofrecer tantas ventajas como las que ofrece tiene un punto negativo y es que sufre fragmentación. Para poder realizar una desfragmentación en btrfs tendremos que introducir el siguiente comando.

    root@apache:/# btrfs filesystem defrag /mnt/

## Compresión al vuelo

Existen dos forma de compresión al vuelo y es ZLIB y LZO. La diferencia que existe entre los dos es que LZO es una compresión mas rápida pero menos exigente lo que penaliza la capacidad de compresión (no llega a comprimirlo tanto) y la opción ZLIB es mas exigente y por ello comprime más la información con la penalización en cuanto a tiempo y la carga de CPU que es mayor.

    # mount -o compress=zstd /dev/sde2 /mnt/temp

Una vez introducido este comando vamos a introducir información  para así comprobar como puede almacenar mas información de la que permite el dispositivo de almacenamiento ya que la comprime. En nuestro caso vamos a utilizar un dispositivo de almacenamiento con 1G de capacidad como aparece a continuación.

    # btrfs filesystem show
    Label: none  uuid: 4cdd3e63-18af-41a8-a650-48dcec4f4146
        Total devices 1 FS bytes used 144.00KiB
        devid    1 size 1.00GiB used 126.38MiB path /dev/sde2

Acto seguido introduciremos información con el comando dd.

    # dd if=/dev/zero of=/mnt/temp/fichero
    dd: escribiendo en '/mnt/temp/fichero': No queda espacio en el dispositivo
    59234114+0 records in
    59234113+0 records out
    30327865856 bytes (30 GB, 28 GiB) copied, 517,176 s, 58,6 MB/s

Vemos lo que ocupa el fichero:

    # ls -la
    total 29617080
    drwxr-xr-x 1 root root          14 oct 20 20:09 .
    drwxr-xr-x 8 root root        4096 oct 18 19:14 ..
    -rw-r--r-- 1 root root 30327865856 oct 20 20:18 fichero
    root@theasker-proliantml110g5 /mnt/temp # du -h
    29G

Por último mostramos las caracteristicas de ese dispositivo y vemos como eso 25G comprimidos en el tamaño real del dispositivo solo esta ocupando 1023.00MiB.

    # btrfs filesystem show /mnt/temp
    Label: none  uuid: 4cdd3e63-18af-41a8-a650-48dcec4f4146
        Total devices 1 FS bytes used 941.94MiB
        devid    1 size 1.00GiB used 1023.00MiB path /dev/sde2

### Ajustar compresión en archivo /etc/fstab

/dev/sdb                /srv            btrfs           compress=zstd:9,relatime,rw     0 0

### Ajustes

Es conveniente desactivar la chaché de escritura en discos SSD o NVME
```bash
# hdparm -W 0 /dev/sdc1
```

O también se puede hacer con:
```bash
smartctl --set wcache,off
```

## BTRFS con timeshift snapshots en el menu grub
```bash
sudo pacman -S timeshift
```
Como dependencia Timeshift instala `cronie`, pero no se conecta por defecto:
```bash
sudo systemctl enable --now cronie.service
```
Ahora podemos ejecutar Timeshift y configurar los backups que queremos realizar.

### `timeshift-autosnap` 

**timeshift-autosnap** proporciona un script que crea una instantánea antes de cada actualización de paquete/sistema utilizando un gancho Pacman. El número de instantáneas que se guardan por defecto es tres. Las instantáneas antiguas se borran para dejar espacio a las nuevas.

Si queremos esa funcionalidad lo instalaremos
```bash
yay -S timeshift-autosnap
```

Si no se instala, los snapshots se harán manualmente.

### Snapshots en Grub
Para incluir las instantáneas BTRFS de Timeshift en las opciones de arranque, es necesario reconfigurar **grub-btrfs**, ya que está utilizando /.snapshots como directorio de instantáneas por defecto y Timeshift utiliza un directorio diferente:
```bash
sudo pacman -S grub-btrfs
```

Para incluir las instantáneas BTRFS de Timeshift en las opciones de arranque, es necesario reconfigurar grub-btrfs, ya que está utilizando /.snapshots como directorio de instantáneas por defecto y Timeshift utiliza un directorio diferente:
```bash
sudo systemctl edit --full grub-btrfsd
```

Añade la opción `-timeshift-auto` cambiando la siguiente línea:
```
ExecStart=/usr/bin/grub-btrfsd --syslog /.snapshots 
```
y quedaría:

```
ExecStart=/usr/bin/grub-btrfsd --syslog --timeshift-auto
```

El último paso es reconstruir el archivo de configuración de grub:
```bash
sudo grub-mkconfig -o /boot/grub/grub.cfg
```

y nos avisa que hay que realizar esta acción por última vez para que añada los snapshots hay que volver a ejecutar el mismo comando:
```
...
Found 1 snapshot(s)

WARNING: 'grub-mkconfig' needs to run at least once to generate the snapshots (sub)menu entry in grub the main menu. After that this script can run alone to generate the snapshot entries.

Unmount /tmp/grub-btrfs.WiQApNAH5h .. Success
hecho
```

```bash
sudo grub-mkconfig -o /boot/grub/grub.cfg
```

Y activar el servicio:
```bash
sudo systemctl enable --now grub-btrfsd
```

## Bibliografía
 * **https://juanjoselo.wordpress.com/2018/01/28/uso-de-btrfs-en-linux/**
 * https://puerto53.com/linux/filesystems-btrfs/
 * https://www.nishantnadkarni.tech/posts/arch_installation/
 * https://wiki.archlinux.org/title/Btrfs_(Espa%C3%B1ol)
 * https://odiseageek.es/posts/instalar-archlinux-con-btrfs-y-encriptacion-luks/
 * https://hetmanrecovery.com/es/blog/how-to-recover-data-from-btrfs-raid.htm => Cómo usar "recovery"
 * https://wiki.gentoo.org/wiki/Btrfs/es
 * https://linuxhint.com/enable-btrfs-filesystem-compression/ => Compresión
 * https://discovery.endeavouros.com/encrypted-installation/btrfs-with-timeshift-snapshots-on-the-grub-menu/2022/02/ => Btrfs 