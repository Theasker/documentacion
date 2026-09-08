## Usos de pacman

### Configurar paquete para que no se actualice

Editar fichero `/etc/pacman.conf`:
```bash
IgnorePkg = linux-lts linux-lts-headers gcc-libs
```

### Instalar paquetes de anteriores versiones

La caché de pacman está en `/var/cache/pacman/pkg/':

