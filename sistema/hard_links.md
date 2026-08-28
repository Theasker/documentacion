# Hard Links

## Crear un hard link

    $ ln /mnt/datos1/scripts/bin/telegrambot.py /mnt/datos1/scripts/bin/telegrambot.py.lnk

## Encontrar todos los hard links de un fichero

    $ find /mnt/datos1/scripts/ -samefile /mnt/datos1/scripts/bin/telegrambot.py