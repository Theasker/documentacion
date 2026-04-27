# Polybar

## Instalación
```bash
sudo pacman -S polybar
```

Vamos a la página de polybar-themes en https://github.com/adi1090x/polybar-themes y clonamos el repositorio
```bash
https://github.com/adi1090x/polybar-themes.git
```

Ejecutamos el fichero de instalación que instalará las diversas configuraciones y fuentes de letra:
```bash
$ cd polybar-themes
$ chmod +x setup.sh
```

## Configuración

Para que polybar nos muestre los monitores que tenemos conectados y donde podemos configurar polybar:
```bash
$ polybar -m
HDMI-0: 2560x1440+1400+0 (primary)
VGA-0: 1400x900+0+0
```
