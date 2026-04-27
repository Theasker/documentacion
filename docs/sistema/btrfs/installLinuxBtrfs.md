# Instalación Arch Linux sobre Btrfs

 * Formateo la partición
    ```bash
    # mkfs.btrfs /dev/sdc3
    ```
 * Monto la partición
    ```bash
    # mount -o compress=zstd /dev/sdc3 /mnt/arch
    ```
 * Creo los subvolumenes
    ```bash
    # mount -o compress=zstd /dev/sdc3 /mnt/arch
    # cd /mkt
    # btrfs sub cr /mnt/arch/@
    # btrfs sub cr /mnt/arch/@boot
    # btrfs sub cr /mnt/arch/@etc
    # btrfs sub cr /mnt/arch/@home
    # btrfs sub cr /mnt/arch/@tmp
    # btrfs sub cr /mnt/arch/@log
    # btrfs sub cr /mnt/arch/@pkg
    # btrfs sub cr /mnt/arch/@snapshots
    ```


 * /dev/sda3 => datos1 => 0d475404-655d-4d8c-af2c-8e3258ce5bd3
 * /dev/sdd1 => datos2 => 4a894b50-328d-4f57-9460-f76b4bd2a67e
 * /dev/sdb1 => datos3 => 7720197f-b04d-47fd-b180-143b028eb852

``` 
UUID=0bfb231a-c214-4c65-856e-e7c4479e7f16	/	ext4	defaults,noatime	0	1
tmpfs	/tmp	tmpfs	defaults,noatime,mode=1777	0	0

/dev/sdd2   none    swap    defaults    0   0

UUID=0d475404-655d-4d8c-af2c-8e3258ce5bd3	/mnt/datos1	btrfs	compress=zstd,relatime,rw  0 0
UUID=4a894b50-328d-4f57-9460-f76b4bd2a67e	/mnt/datos2	btrfs	compress=zstd,relatime,rw  0 0
UUID=7720197f-b04d-47fd-b180-143b028eb852	/mnt/datos3	btrfs	compress=zstd,relatime,rw  0 0

/dev/sdb                /srv            btrfs           compress=zstd:9,relatime,rw     0 0
```

## Bibliografía

 * https://www.muylinux.com/2022/01/27/archinstall-2-3-1/
 * https://www.arcolinuxd.com/installing-arch-linux-with-a-btrfs-filesystem/
 * https://wiki.archlinux.org/title/Installation_guide_(Espa%C3%B1ol)
 * https://wiki.archlinux.org/title/btrfs
 * https://www.nishantnadkarni.tech/posts/arch_installation/
 * https://github.com/Deebble/arch-btrfs-install-guide

### Opciones de montaje útiles del sistema de archivos Btrfs - Sugerencia para Linux
 * https://ciksiti.com/es/chapters/4271-useful-mount-options-of-the-btrfs-filesystem--linux-hint


### Optimizando fstab para discos SSD con Btrfs
 * https://elblogdelazaro.org/posts/2019-07-26-optimizando-fstab-para-discos-ssd--con-btrfs/
 * https://gist.github.com/yoyo308/9a4ae6a517597a698a19