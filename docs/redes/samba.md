# Samba
- [Samba](#samba)
  - [Instalación](#instalación)
    - [Usuario invitado](#usuario-invitado)
  - [Configuración final](#configuración-final)
  - [Referencias](#referencias)

## Instalación

```bash
sudo pacman -S samba
```

El sistema no trae archivo de configuración `/etc/samba/smb.conf` y lo creamos usando el ejemplo del repositorio de GitHub de samba en https://git.samba.org/samba.git/?p=samba.git;a=blob_plain;f=examples/smb.conf.default;hb=HEAD:

```bash
cd /etc/samba
touch smb.conf
```

Pegamos el contenido del enlace https://git.samba.org/samba.git/?p=samba.git;a=blob_plain;f=examples/smb.conf.default;hb=HEAD:

Hay que cambiar el directorio de los logs, ya que el que viene por defecto no tiene permisos de escritura y dará error:
```
log file = /var/log/samba/%m.log
```

Para verificar la sintaxis correcta del fichero `/etc/samba/smb.conf`
```bash
$ sudo testparm /etc/samba/smb.conf
```

Creamos un usuario para su uso:
```bash
sudo smbpasswd -a theasker
``` 

### Usuario invitado
Tambien podemos crear un usuario sin contraseña para usuarios invitados:

```bash
useradd guest -s /bin/nologin
```

Add the following to /etc/samba/smb.conf:

`/etc/samba/smb.conf`
```
...
[global]
security = user
map to guest = bad user
guest account = guest

[guest_share]
    comment = guest share
    path = /tmp/
    public = yes
    only guest = yes
    writable = yes
    printable = no
```


Iniciamos el servicio
```bash
systemctl enable smb
Created symlink /etc/systemd/system/multi-user.target.wants/smb.service → /usr/lib/systemd/system/smb.service.
systemctl start smb
```

Instalamos el cliente:
```bash
pacman -S smbclient
```

Vemos los recursos compartidos:
```bash
smbclient -L localhost -U%
```

## Configuración final
```
[global]
	dns proxy = No
	guest account = guest
	log file = /var/log/samba/%m.log
	max log size = 50
	server role = standalone server
	server string = Samba Server
	idmap config * : backend = tdb


[printers]
	browseable = No
	comment = All Printers
	guest ok = Yes
	path = /usr/spool/samba
	printable = Yes


[tmp]
	comment = Temporary file space
	guest ok = Yes
	guest only = Yes
	path = /tmp
	read only = No


[Pelis]
	guest ok = Yes
	guest only = Yes
	path = /mnt/dato2/Media
	read only = No
```

## Referencias
* https://wiki.archlinux.org/title/samba
* https://access.redhat.com/documentation/es-es/red_hat_enterprise_linux/8/html/configuring_and_managing_virtualization/sharing-files-between-the-host-and-windows-virtual-machines_sharing-files-between-the-host-and-its-virtual-machines