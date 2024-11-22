# Emular raspberry PI con qemu

Primero descargamos una imagen de raspberry como RasPiOs y al descomprimir obtenermos un fichero `2024-07-04-raspios-bookworm-arm64.img`

Una raspberry no tiene BIOS y trabaja con 2 particiones y una actua como BIOS y podemos montar esas particiones en directorios para obtener el kernel.

Con fdisk podemos ver las particiones iniciales:
```bash
$ sudo fdisk -l 2024-07-04-raspios-bookworm-arm64.img 
[sudo] contraseña para theasker: 
Disco 2024-07-04-raspios-bookworm-arm64.img: 5,66 GiB, 6081740800 bytes, 11878400 sectores
Unidades: sectores de 1 * 512 = 512 bytes
Tamaño de sector (lógico/físico): 512 bytes / 512 bytes
Tamaño de E/S (mínimo/óptimo): 512 bytes / 512 bytes
Tipo de etiqueta de disco: dos
Identificador del disco: 0x75d6d1b4

Disposit.                              Inicio Comienzo    Final Sectores Tamaño Id Tipo
2024-07-04-raspios-bookworm-arm64.img1            8192  1056767  1048576   512M  c W95 FAT32 (LBA)
2024-07-04-raspios-bookworm-arm64.img2         1056768 11878399 10821632   5,2G 83 Linux
```

Con mount podemos montar las particiones de ese fichero y obtendremos la dirección de memoria multiplicando los sectores por el tamaño del sector:
 * En la primera partición `8192 x 512 = 4194304`, por lo que para montar esa partición haríamos:
 ```bash
 mkdir rpi1
 sudo mount 2024-07-04-raspios-bookworm-arm64.img rpi1 -o offset=4194304
 ```
 * Y para la otra partición sería `1056768 x 512 = 541065216`, y para montarla:
 ```bash
 mkdir rpi2
 sudo mount 2024-07-04-raspios-bookworm-arm64.img rpi2 -o offset=541065216
 ```