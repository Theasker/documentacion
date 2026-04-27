# GlusterFS

GlusterFS es un sistema de archivos distribuido.

## Instalación
```bash
apt install glusterfs-cli glusterfs-client glusterfs-common glusterfs-server
```

## Arrancar y habilitar el servicio GlusterFS
```bash
systemctl start glusterd ; systemctl enable glusterd
```

## Configuramos el fichero `/etc/hosts`
```
192.168.122.9   debian1
192.168.122.207 debian2
```

## Agregar un peer al cluster
Así permitimos que GlusterFS sepa con que servidores cuenta
```bash
gluster peer probe <host>
```
```bash
gluster peer probe debian2
peer probe: success
```
Después del peer probe, se puede ver si se han conectado los equipos remotos con el comando.

Desde el host **debian1**
```bash
gluster peer status
Number of Peers: 1

Hostname: debian2
Uuid: eb62bccf-84f6-4e17-8b9b-3ea5a5ca36d0
State: Peer in Cluster (Connected)
```

Desde el host **debian2**
```bash
gluster peer status
Number of Peers: 1

Hostname: debian1
Uuid: 0a7f7082-a919-4171-b1a8-29b437c6e760
State: Peer in Cluster (Connected)
```

## Agregando ***`bricks`***
Crear los bricks que no son otra cosa que directorios que serán exportados al estilo NFS.

Creamos este directorio en los 2 hosts (debian1 y debian2) en un disco diferente a root
```bash
mount /dev/vdb1 /mnt/datos/
mkdir -p /mnt/datos/glustervol
```

### Creando un volumen similar a RAID
```bash
gluster volume create <volume name> replica <numero de replicas> transport <tcp|udp> <host1>:<path1> <host2>:<path2> ...
```
```bash
gluster volume create theaskervol replica 2 transport tcp debian1:/mnt/datos/glustervol/ debian2:/mnt/datos/glustervol/
Replica 2 volumes are prone to split-brain. Use Arbiter or Replica 3 to avoid this. See: http://docs.gluster.org/en/latest/Administrator-Guide/Split-brain-and-ways-to-deal-with-it/.
Do you still want to continue?
 (y/n) y
volume create: theaskervol: success: please start the volume to access data
```

El numero seguido de del método “replica“ , el numero 2 indica que seran replicados 2 archivos completos en cada brick, siendo que el cluster es de 2 peers significa que cada peer tendrá una copia exacta de cada archivo.

Después de creado el volumen, este debe iniciarse para que este listo para servir y montarse
```bash
gluster volume start NombreDelVolumen
```
En nuestro caso
```bash
gluster volume start theaskervol
volume start: theaskervol: success
```

Ahora verificamos el estado del volumen (cluster):
```bash
gluster volume status
Status of volume: theaskervol
Gluster process                             TCP Port  RDMA Port  Online  Pid
------------------------------------------------------------------------------
Brick debian1:/mnt/datos/glustervol         52173     0          Y       12473
Brick debian2:/mnt/datos/glustervol         55765     0          Y       828  
Self-heal Daemon on localhost               N/A       N/A        Y       12490
Self-heal Daemon on debian2                 N/A       N/A        Y       845  
 
Task Status of Volume theaskervol
------------------------------------------------------------------------------
There are no active volume tasks
```

## Montaje del sistema GlusterFS 
Ahora vamos a montar el sistema del volumen creado para que funcione

Creamos el directorio donde se va a montar

En el host `debian1`
```bash
mkdir /mnt/distribuido
```
En el host `debian2`
```bash
mkdir /mnt/distribuido
```

Y luego lo montamos en cada host:

En el host `debian1`
```bash
mount debian1:/mnt/datos/glustervol/ -t glusterfs /mnt/distribuido
```
En el host `debian2`
```bash
mount debian2:/mnt/datos/glustervol/ -t glusterfs /mnt/distribuido
```

## Bibliografía
* **Blog de Last Dragon** => https://www.lastdragon.net/?p=2117
* **Documentación de Rocky Linux** => https://docs.rockylinux.org/es/guides/file_sharing/glusterfs/
* https://www.digitalocean.com/community/tutorials/how-to-create-a-redundant-storage-pool-using-glusterfs-on-ubuntu-20-04-es