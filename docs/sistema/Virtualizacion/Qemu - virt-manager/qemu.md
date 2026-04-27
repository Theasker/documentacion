# Qemu

- [Qemu](#qemu)
  - [Instalación de Qemu en Arch Linux](#instalación-de-qemu-en-arch-linux)
  - [Virsh](#virsh)
  - [Creación de imagen virtual](#creación-de-imagen-virtual)
  - [Ejecución de máquina](#ejecución-de-máquina)
  - [Activar red](#activar-red)
  - [Ejemplos de comandos](#ejemplos-de-comandos)
  - [Activar aceleración gráfica con NVIDIA](#activar-aceleración-gráfica-con-nvidia)
  - [Bibliografía](#bibliografía)


## Instalación de Qemu en Arch Linux
Instalación del paquete completo
```bash
sudo pacman -S qemu-full virt-manager
sudo pacman -S spice-vdagent xf86-video-qxl
```

Activamos el servicio de libvirtd
```bash
$ sudo systemctl enable --now libvirtd
Created symlink /etc/systemd/system/multi-user.target.wants/libvirtd.service → /usr/lib/systemd/system/libvirtd.service.
Created symlink /etc/systemd/system/sockets.target.wants/virtlockd.socket → /usr/lib/systemd/system/virtlockd.socket.
Created symlink /etc/systemd/system/sockets.target.wants/virtlogd.socket → /usr/lib/systemd/system/virtlogd.socket.
Created symlink /etc/systemd/system/sockets.target.wants/libvirtd.socket → /usr/lib/systemd/system/libvirtd.socket.
Created symlink /etc/systemd/system/sockets.target.wants/libvirtd-ro.socket → /usr/lib/systemd/system/libvirtd-ro.socket.
Created symlink /etc/systemd/system/sockets.target.wants/libvirtd-admin.socket → /usr/lib/systemd/system/libvirtd-admin.socket.
Created symlink /etc/systemd/system/sockets.target.wants/virtlockd-admin.socket → /usr/lib/systemd/system/virtlockd-admin.socket.
Created symlink /etc/systemd/system/sockets.target.wants/virtlogd-admin.socket → /usr/lib/systemd/system/virtlogd-admin.socket.
```

Modificamos el fichero `/etc/libvirt/libvirtd.conf` cambiando estas líneas:
* Descomentamos la línea `unix_sock_group = "libvirt"`
* Descomentamos la línea `unix_sock_ro_perms = "0777"` y la cambiamos para que quede `unix_sock_ro_perms = "0770"`

Agregamos a nuestro usuario al grupo `libvirt`:
```bash
sudo usermod -aG libvirt theasker
```

Modificamos también el fichero `/etc/libvirt/qemu.conf` las líneas:
* Duplicamos y modificamos la línea `#user = "libvirt-qemu"` por `user = "theasker"`
* Duplicamos y moficiamos la línea `#group = "libvirt-qemu"` por `group = "theasker"`

## Virsh
Comando para ejecutar tareas de virtualización y 

Ver las redes del activas sistema
```bash
$ virsh --connect=qemu:///system net-list --all
 Nombre    Estado   Inicio automático   Persistente
-----------------------------------------------------
 default   activo   no                  si
```

La activamos y le decimos que se autoarranque cuando iniciamos una máquina
```bash
$ virsh --connect=qemu:///system net-autostart default
 Network default marked as autostarted
```

## Creación de imagen virtual
```bash
qemu-img create -f qcow2 datos1.qcow2 5G
```
* `create`: Decimos que va a crear una imagen de disco virtual
* `-f qcow2`: Formato de disco duro virtual de qemu
* `datos1.qcow2`: Nombre del fichero a crear
* `5G`: Tamaño del disco

Para consultar la información del disco virtual:
```bash
$ qemu-img info datos1.qcow2
image: datos1.qcow2
file format: qcow2
virtual size: 5 GiB (5368709120 bytes)
disk size: 192 KiB
cluster_size: 65536
Format specific information:
    compat: 1.1
    compression type: zlib
    lazy refcounts: false
    refcount bits: 16
    corrupt: false
    extended l2: false
Child node '/file':
    filename: datos1.qcow2
    protocol type: file
    file length: 192 KiB (197120 bytes)
    disk size: 192 KiB
```


## Ejecución de máquina
```bash
qemu-system-x86_64 -m 1G -smp 1 -name 'Alpine Linux' -boot d -cdrom ./alpine-standard-3.19.1-x86_64.iso
qemu-system-x86_64 -m 1G -smp 1 -hda ./datos1.qcow2 -hdb ./datos2.qcow2 -hdc ./datos3.qcow2 -name 'Alpine Linux' -boot d -cdrom ./alpine-standard-3.19.1-x86_64.iso
```
* `-m`: memoria
* `-smp`: procesadores
* `-hda`, `-hdb`, `-hdc`: Disco duro virtual asignado que hemos creado anteriormente
* `-name`: nombre del 
* `-boot d`: Para que arranque de disco
* `-cdrom`: CDROM virtual que se suele poner una imagen ISO

## Activar red 

Listamos las redes

```bash
$ sudo virsh net-list --all

 Nombre    Estado     Inicio automático   Persistente
-------------------------------------------------------
 default   inactivo   si                  si
```

La activamos
```bash
$ sudo virsh net-start default
La red default se ha iniciado
```

## Ejemplos de comandos

Comando de instalación de debian:
```bash
qemu_system-x86_64 --enable-kvm -cpu qemu64 -M q35 -m 8G -drive file=disco.qcow2,if=virtio,media=disk,cache=unsafe -drive file=/home/....../debian.iso,media=cdrom -vga virtio -boot menu=on
```

## Activar aceleración gráfica con NVIDIA
> https://www.reddit.com/r/archlinux/comments/18jkln3/libvirt_virtmanager_nvidia_3d_acceleration_black/?tl=es-es
> https://nyblnet.blogspot.com/2020/11/virgl-3d-acceleration-on-kvm-with.html
Modificar el apartado `<graphics>` y `<video>`
```xml
<graphics type="spice">
  <listen type="none"/>
</graphics>
<graphics type="egl-headless">
  <gl rendernode="/dev/dri/renderD128"/>
</graphics>
<video>
  <model type="virtio" heads="1" primary="yes">
    <acceleration accel3d="yes"/>
  </model>
  <address type="pci" domain="0x0000" bus="0x00" slot="0x01" function="0x0"/>
</video>
```

## Bibliografía
 * Qemu | Tutorial fácil (Locos por Linux) => https://www.youtube.com/watch?v=ISvdxtW-Cls
 * https://wiki.archlinux.org/title/Virt-manager
 * https://rumble.com/v39ycci-mquina-virtual-windows-11-en-linux-qemuvirt-manager.html
 * https://wiki.archlinux.org/title/QEMU
 * https://access.redhat.com/documentation/es-es/red_hat_enterprise_linux/5/html/virtualization/index
 * https://www.youtube.com/watch?v=NGN9p4pulFM&t=1855s
 * https://h4ckseed.wordpress.com/2021/07/29/administrando-virtualizacion-kvm-con-virsh/
 * https://nb.fedorapeople.org/cvsfedora/web/html/docs/virtualization-guide/f12/es-ES/html/part-Virtualization_Guide-Virtualization_Reference_Guide.html
 * **Aceleración Gráfica con NVIDIA y libvirt**: https://nyblnet.blogspot.com/2020/11/virgl-3d-acceleration-on-kvm-with.html