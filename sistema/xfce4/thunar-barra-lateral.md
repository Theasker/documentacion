# Barra lateral de Thunar — Solución

## Problema

Los accesos directos de la barra lateral de Thunar (Documentos, Música,
Imágenes, Vídeos, Descargas) apuntaban a directorios vacíos dentro de
`/home/theasker/`, cuando los directorios reales están montados en
`/mnt/datos*`.

Thunar **no** lee las rutas de `~/.config/user-dirs.dirs` para la barra
lateral. Usa los **bookmarks de GTK** en `~/.config/gtk-3.0/bookmarks`.

## Archivos involucrados

| Archivo | Propósito |
|---|---|
| `~/.config/user-dirs.dirs` | Variables XDG para apps que las consultan (`xdg-open`, etc.) |
| `~/.config/gtk-3.0/bookmarks` | Barra lateral de Thunar (y otros gestores de archivos GTK) |

## Cambios realizados

En `~/.config/gtk-3.0/bookmarks` se reemplazaron las rutas viejas:

| Antes | Después |
|---|---|
| `file:///home/theasker/Documentos` | `file:///mnt/datos1/Documentos` |
| `file:///home/theasker/M%C3%BAsica` | `file:///mnt/datos3/M%C3%BAsica` |
| `file:///home/theasker/Im%C3%A1genes` | `file:///mnt/datos1/Im%C3%A1genes` |
| `file:///home/theasker/V%C3%ADdeos` | `file:///mnt/datos2/Media` |
| `file:///home/theasker/Descargas` | `file:///mnt/datos2/temp` |

Las rutas con caracteres especiales (tildes) están codificadas en
porcentaje UTF-8, igual que en el archivo original.

## Formato del archivo de bookmarks

Cada línea tiene el formato:

```
file:///ruta/al/directorio
```

Se puede agregar una etiqueta personalizada separada por un espacio:

```
file:///ruta/al/directorio NombreVisible
```

## Notas

- Thunar no tiene un archivo de shortcuts propio en este sistema. Usa
  directamente los bookmarks de GTK. Si en el futuro aparece un directorio
  `~/.config/Thunar/sidebar/`, ese tendría prioridad.
- Los cambios se reflejan al reiniciar Thunar (o al abrir una ventana nueva).
- `user-dirs.dirs` no necesita modificarse — ya está correcto para el resto
  del sistema.
