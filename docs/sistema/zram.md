# Zram

## ¿Qué es ZRAM?
ZRAM es un módulo para optimizar Linux notablemente el uso de la memoria de intercambio SWAP y mejora notablemente el rendimiento general del sistema. En términos técnicos, ZRAM comprime la caché de la memoria de intercambio de SWAP en forma de páginas comprimidas, también llamadas ZPAGES. Estas páginas se almacenan en la memoria RAM y se ejecuta desde allí.

El uso de zram también es una buena manera de reducir los ciclos de lectura/escritura del disco debido al intercambio en SSD.

## Instalar en Arch Linux

    $ yay -S zramd
    # systemctl enable zramd
    # systemctl start zramd

Vemos que está activo con:

    $ swapon
    NAME       TYPE      SIZE   USED PRIO
    /dev/sdd2  partition 7,8G 355,5M   -2
    /dev/zram0 partition   2G 409,5M  100
    /dev/zram1 partition   2G 385,6M  100

o con mucha más información:

    $ zramctl 
    NAME       ALGORITHM DISKSIZE   DATA COMPR TOTAL STREAMS MOUNTPOINT
    /dev/zram1 zstd            2G  77,7M 19,8M 21,5M       4 [SWAP]
    /dev/zram0 zstd            2G 103,2M 23,9M 25,5M       4 [SWAP]

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

## Bibliografía

* https://wiki.archlinux.org/title/Improving_performance_(Espa%C3%B1ol)
* https://www.youtube.com/watch?v=J5VSlVoumrk