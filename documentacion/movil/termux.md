# Termux

## Acceso a external SD
Hay que ejecutar el comando "termux-setup-storage" y luego se crea un simple archivo en storage

    rsync -azv --append --progress --partial -e "ssh -p 8022" u0_a495@192.168.0.101:/storage/1A0B-92C5/DCIM .
    rsync -azv --append --progress --partial -e "ssh -p 8022" u0_a495@192.168.0.101:/data/data/com.termux/files/home/storage/shared/DCIM ./internalSD/