# Problemas con arch

## 2024-05-09 nvidia-dkms no compila

Hay que hacer un downgrade de `gcc` y `gcc-lib` y volver a compilar nvidia-dkms

```bash
sudo pacman -U /var/cache/pacman/pkg/gcc-13.2.1-6-x86_64.pkg.tar.zst pacman -U /var/cache/pacman/pkg/gcc-libs-13.2.1-6-x86_64.pkg.tar.zst
yay -Syu nvidia-470xx-dkms nvidia-470xx-utils nvidia-470xx-settings
```

Luego hay que bloquear los paquetes **gcc** y **gcc-libs** para que no se vuelvan a actualizar, usando el fichero de configuración de pacman `/etc/pacman.conf`:
```
IgnorePkg = linux-lts linux-lts-headers gcc gcc-libs
```