# Copia de seguridad del sistema con PartClone

## Backup Sistema
sudo partclone.ext4 -c -s /dev/sdc2 -o /mnt/datos1/backup/backupSistema/2021-07-30-archlinux.pcl

## Restaurar Sistema
sudo partclone.ext4 -r -s ~/image_sda1.pcl -o /dev/sda1

## Bibliografía

 * https://blog.desdelinux.net/partclone-una-aplicacion-la-clonacion-y-restauracion-de-particiones/