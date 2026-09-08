# MANUAL TÉCNICO: ARRANQUE DE ISOs DESDE GRUB (LOOPBACK)

Este documento detalla el procedimiento para configurar un sistema Arch Linux (Host) con particiones BTRFS y EXT4 para arrancar diversas distribuciones Linux directamente desde archivos ISO almacenados en disco.

---

## 1. PREPARACIÓN Y DIAGNÓSTICO (SIN REINICIAR)

Antes de editar la configuración de GRUB, es fundamental validar que el gestor de arranque puede "ver" los archivos.

### A. Identificar el UUID del disco de datos
Ejecuta `lsblk -f` para obtener el UUID de la partición donde residen las ISOs (en este caso, un disco EXT4).

### B. Validar rutas con grub-fstest
Para evitar reinicios innecesarios, usa esta herramienta para confirmar que la ruta es correcta para GRUB:
```bash
# Ejemplo para validar la ISO de Arch
sudo grub-fstest /dev/sdb1 ls /VMs/isos/archlinux-2026.01.01-x86_64.iso
```

### Parte 2: Entradas para Arch Linux y CachyOS


## 2. CONFIGURACIÓN DE /etc/grub.d/40_custom (Entradas Arch)

### Entrada para Arch Linux
```bash
menuentry "ISO: Arch Linux (Rescue)" {
    insmod ext2
    insmod loopback
    set uuid="01c1dfe1-0504-4dd1-ae7f-0e00c3fd6aa2"
    set isofile="/VMs/isos/archlinux-2026.01.01-x86_64.iso"
    search --no-floppy --fs-uuid --set=root $uuid
    loopback loop $isofile
    linux (loop)/arch/boot/x86_64/vmlinuz-linux archisobasedir=arch archisolabel=ARCH_202601 img_loop=$isofile img_dev=/dev/disk/by-uuid/$uuid
    initrd (loop)/arch/boot/x86_64/initramfs-linux.img
}
```

Entrada para CachyOS
```Bash
menuentry "ISO: CachyOS Desktop" {
    insmod ext2
    insmod loopback
    set uuid="01c1dfe1-0504-4dd1-ae7f-0e00c3fd6aa2"
    set isofile="/VMs/isos/cachyos-desktop-linux-251129.iso"
    search --no-floppy --fs-uuid --set=root $uuid
    loopback loop $isofile
    linux (loop)/boot/vmlinuz-linux archisobasedir=arch img_label=CACHYOS img_loop=$isofile img_dev=/dev/disk/by-uuid/$uuid driver=nonfree
    initrd (loop)/boot/initramfs-linux.img
}
```

Parte 3: Entrada Debian y Reglas Finales
Markdown

## 3. CONFIGURACIÓN (Otras Distros) Y REGLAS DE ORO

### Entrada para Debian/Ubuntu
```bash
menuentry "ISO: Debian Live" {
    insmod ext2
    insmod loopback
    set uuid="01c1dfe1-0504-4dd1-ae7f-0e00c3fd6aa2"
    set isofile="/VMs/isos/debian-live.iso"
    search --no-floppy --fs-uuid --set=root $uuid
    loopback loop $isofile
    linux (loop)/live/vmlinuz boot=live findiso=$isofile components quiet splash
    initrd (loop)/live/initrd.img
}
```