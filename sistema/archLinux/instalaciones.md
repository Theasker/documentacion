# Instalaciones del sistema

- [Instalaciones del sistema](#instalaciones-del-sistema)
  - [Docker](#docker)
  - [Hardware / drivers](#hardware--drivers)
    - [Nvidia drivers](#nvidia-drivers)
  - [sistema](#sistema)
    - [Desactivar el firewall](#desactivar-el-firewall)
    - [Modificar `~/.bashrc`:](#modificar-bashrc)
    - [Zram (mejor no)](#zram-mejor-no)
    - [Dotfiles con dotdrop](#dotfiles-con-dotdrop)
    - [/etc/fstab](#etcfstab)
    - [Grub](#grub)
    - [Swap](#swap)
    - [Btrfs, snapshots y backups](#btrfs-snapshots-y-backups)
    - [Varios](#varios)
  - [Internet](#internet)
  - [Multimedia](#multimedia)
    - [Plex Media Server](#plex-media-server)
  - [Varios](#varios-1)


## Docker
```bash
sudo pacman -S docker docker-compose
sudo usermod -aG docker $USER
sudo newgrp docker

sudo systemctl enable docker
sudo systemctl start docker

sudo pacman -S lightdm-gtk-greeter-settings 
```

## Hardware / drivers

### Nvidia drivers
```bash
yay -S nvidia-inst
``` 

Con esto comprobamos la tarjeta que tenemos y nos dirá qué tenemos que instalar:

```bash
$ nvidia-inst
2023-06-20 16:52:09: Info: Running: nvidia-inst v23-5
2023-06-20 16:52:09: Info: Command line: nvidia-inst 
2023-06-20 16:52:09: Info: Selected mode: nvidia
NVIDIA card id: 1287
Fetching driver data from nvidia.com ...
2023-06-20 16:52:12: Info: Installing packages: nvidia-470xx-dkms nvidia-470xx-utils nvidia-470xx-settings
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
COMMANDS TO RUN:
    yay -Syu nvidia-470xx-dkms nvidia-470xx-utils nvidia-470xx-settings
    nvidia-installer-kernel-para nvidia-drm.modeset=1 add3.2.1-
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
2023-06-20 16:52:12: Error: Sorry, nvidia-inst does not support installing packages from AUR.
    To continue, manually run all commands from COMMANDS TO RUN above.
...

```bash
$ nvidia-inst
2023-06-20 17:25:44: Info: Running: nvidia-inst v23-5
2023-06-20 17:25:44: Info: Command line: nvidia-inst 
2023-06-20 17:25:44: Info: Selected mode: nvidia
NVIDIA card id: 1287
Fetching driver data from nvidia.com ...
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
COMMANDS TO RUN:
    yay -Syu nvidia-470xx-dkms nvidia-470xx-utils nvidia-470xx-settings
    nvidia-installer-kernel-para nvidia-drm.modeset=1 add
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

==> NOTE: running the commands may take several minutes...

Root Contraseña:
```

## sistema
```bash
sudo pacman -S gnome-disk-utility gparted
sudo pacman -S mc
sudo pacman -S partclone
sudo pacman -S htop
sudo pacman -S veracrypt
```
### Desactivar el firewall
```bash
sudo systemctl stop firewalld
sudo systemctl disable --now firewalld
sudo pacman -R firewalld
```
### Modificar `~/.bashrc`:
```bash
nano ~/.bashrc
PS1='\[$(tput setaf 39)\]\u\[$(tput setaf 81)\]@\[$(tput setaf 77)\]\h \[$(tput setaf 226)\]\w \[$(tput sgr0)\]\$ '
```
### Zram (mejor no)
```bash
$ yay -S zramd
# systemctl enable zramd
# systemctl start zramd
```
Vemos que está activo con:
```bash
$ swapon
NAME       TYPE      SIZE   USED PRIO
/dev/sdd2  partition 7,8G 355,5M   -2
/dev/zram0 partition   2G 409,5M  100
/dev/zram1 partition   2G 385,6M  100
```
o con mucha más información:
```bash
$ zramctl 
NAME       ALGORITHM DISKSIZE   DATA COMPR TOTAL STREAMS MOUNTPOINT
/dev/zram1 zstd            2G  77,7M 19,8M 21,5M       4 [SWAP]
/dev/zram0 zstd            2G 103,2M 23,9M 25,5M       4 [SWAP]
```
El archivo de configuración está en `/etc/default/zramd`
```
$ cat /etc/default/zramd 
# See available algorithms by running "cat /sys/block/zramX/comp_algorithm"
# ALGORITHM=zstd

# Max fraction of physical memory to use
# FRACTION=1.0

# Max total swap size in MB
MAX_SIZE=4096

# Number of zram devices to create
NUM_DEVICES=2

# Swap priority
# PRIORITY=100

# Skip initialization if running inside a virtual machine
# SKIP_VM=false
```
### Dotfiles con dotdrop
```bash
cd /mnt/datos1/backup/dotfiles
sudo pacman -S python-pip
pip3 install -r dotdrop/requirements.txt --user
```
Modificar `~/.bashrc`:
```bash
nano ~/.bashrc
alias dotdrop='/mnt/datos1/backup/dotfiles/dotdrop/dotdrop.sh'
```
### /etc/fstab
```bash
# genfstab
sudo pacman -S arch-install-scripts
sudo mkdir /mnt/datos1 /mnt/datos2 /mnt/datos3 /mnt/raspberry
sudo chown theasker:theasker /mnt/*
sudo chmod 777 /mnt/*
```
Podemos generar el archivo `/etc/fstab` con:
```bash
genfstab / > /etc/fstab
```
```
UUID=48b71f34-1eca-4f20-96fd-016bcbf12a57 /              ext4    defaults,noatime 0 1
tmpfs                                     /tmp           tmpfs   defaults,noatime,mode=1777 0 0
# Swap
UUID=f6a5653d-8f33-4efe-99d7-0f710342c949   none    swap    defaults    0   0

# Discos de datos
UUID=0d475404-655d-4d8c-af2c-8e3258ce5bd3	/mnt/datos1	btrfs	compress=zstd,relatime,rw  0 0
UUID=d93076c2-bdd2-4261-afc2-9e18e5fdc7f7	/mnt/datos2	ext4	defaults  0 0
UUID=260af836-02dd-400a-8dec-06928e0b5124	/mnt/datos3	ext4	defaults  0 0

# Raspberry
pi@192.168.0.70:/mnt/datos/torrents/ /mnt/raspberry  fuse.sshfs  noauto,x-systemd.automount,_netdev,user,idmap=user,follow_symlinks,identityfile=/home/theasker/.ssh/id_rsa,allow_other,default_permissions,uid=1000,gid=1000 0 0
```
### Grub
```bash
sudo mkdir /mnt/datos1 /mnt/datos2 /mnt/datos3 && chmod 777 /mnt/*
sudo systemctl daemon-reload
sudo mount /mnt/datos1 && sudo mount /mnt/datos2 && sudo mount /mnt/datos3
```
Editar fichero `etc/default/grub` y descomentar la línea: `#GRUB_DISABLE_OS_PROBER=false` y luego recargar
```bash
sudo grub-mkconfig -o /boot/grub/grub.cfg
```

### Swap

Activar la partición swap:
```bash
mkswap /dev/sdb2
swapon /dev/sdb2
```

Agregar la línea al fichero `/etc/fstab`:
```
UU`D=d627940a-9377-4337-b6fa-3e6ca8b62bf9   none    swap    defaults    0   0
```

### Btrfs, snapshots y backups

Instalamos **Timeshift**
```bash
pacman -S timeshift
```

### Varios

Configuración de dispositivos Logitech
```bash    
sudo pacman -S piper
```
## Internet
```bash
sudo pacman -S telegram-desktop
```
## Multimedia

### Plex Media Server
```bash
yay -S plex-media-server
sudo systemctl enable plexmediaserver
sudo systemctl start plexmediaserver
```

Configurar en la url `http://localhost:32400/web/`

## Varios
```bash
sudo pacman -S smplayer && yay -Syu nvidia-470xx-dkms nvidia-470xx-utils nvidia-470xx-settings
```
```bash
sudo pacman -S xfce4-cpugraph-plugin xfce4-weather-plugin
```