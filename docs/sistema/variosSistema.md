# Utilidades varias de Sistemas

## Limitar uso de CPU

    cpulimit -p <PID proceso> -l <porcentaje límite>

## Hardware

    badblocks -v /dev/sda > /tmp/badblocks.txt

## Virtualización con aceleración gráfica QEMU

https://www.youtube.com/watch?v=WqvtGeIsngU

## Dotfiles

  * https://atareao.es/podcast/dotdrop-un-completo-gestor-de-dotfiles/ => Gestor de dotfiles

## Bash

### Tutorial sobre la terminal de Atareao.es

 * https://atareao.es/tutorial/terminal/


### Unificar el historial de bash

Agregar estas líneas al fichero `~/.bashrc`:

```bash
#unified bash history
shopt -s histappend 
PROMPT_COMMAND="${PROMPT_COMMAND:+$PROMPT_COMMAND$'\n'}history -a; history -c; history -r"
```

> Fuente: https://www.enmimaquinafunciona.com/pregunta/25760/pueden-unificar-archivos-de-historial-de-bash

### Bash
 * https://starship.rs/ => Ayuda para la modificación del prompt de sistema
 * https://robotmoon.com/bash-prompt-generator/ => Generador de prompt, variable PS1 con colores.
 * https://bashrcgeneracolors prompt linuxtor.com/ => Creador de prompt
 * https://github.com/fcambus/ansiweather => Mostrar el clima en la terminal

## Varios
 * **ventoy**: Aplicación para grabar isos en USB
 * Crear un fichero de ceros: `dd if=/dev/zero of=/archivogrande bs=1024 count=512k`
 * **LSD (LSDeluxe)**: https://github.com/Peltoche/lsd
 * **starship**: El prompt minimalista, ultrarápido e infinitamente personalizable para cualquier intérprete de comandos => https://starship.rs

## fstab
 * **genfstab**: Detecta los puntos posibles de montaje y genera la salida para `/etc/fstab`