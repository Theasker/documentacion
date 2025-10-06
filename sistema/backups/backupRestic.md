# Copias de seguridad con Restic

## Preparando el repositorio

Para automatizar las tareas Restic soporta variables de entorno para configurarlas y poder usarlas posteriormente:
* `RESTIC_REPOSITORY`: Variable de entorno con la localización del repositorio
* `RESTIC_REPOSITORY_FILE`: Variable de entorno de la ubicación del repositorio, y también con la opción `--repository-file`.
* `RESTIC_PASSWORD`: Variable de entorno con la contraseña del repositorio
* `RESTIC_PASSWORD_FILE`: Variable de entorno con la localización del fichero donde está la contraseña del repositorio.

El comando `init` de Restic puede ser llamado con la opción `--repository-version`, para establecer explícitamente la versión del nuevo repositorio.

Con este comando inicializamos el repositorio:
```bash
restic init --repo /srv/restic-repo
```

Para realizar una copia de seguridad de los datos a través de SFTP, primero debe configurar un servidor con SSH e indicarle su clave pública. El inicio de sesión sin contraseña es importante, ya que no es posible realizar copias de seguridad automáticas si el servidor solicita credenciales.
```bash
restic -r sftp:user@host:/srv/restic-repo init
```
Con un puerto diferente sería `sftp://user@[::1]:2222//srv/restic-repo`

Tenga en cuenta que los servidores SFTP cierran las conexiones cuando el cliente no recibe datos. Esto puede ocurrir cuando restic está procesando grandes cantidades de datos sin cambios. Para evitar este problema, añada las siguientes líneas al archivo .ssh/config del cliente:
```
ServerAliveInterval 60
ServerAliveCountMax 240
```
También se pueden acceder a repositorios remotos con **rclone**.
Para iniciar un repositorio en un remoto de rclone llamado `b2prod` en el bucket `yggdrasil`:
```bash
restic -r rclone:b2prod:yggdrasil init
```
O en un path:
```bash
restic -r rclone:b2prod:yggdrasil/foo/bar/baz init
```

También podemos pasar argumentos a rclone

Podemos inicializar un repositorio sin contraseña, especificando la opción `--insecure-no-password`:
```bash
restic init --insecure-no-password
```

## Creando copias de seguridad

El contenido de un directorio en un punto del tiempo específico es un **snapshot**:

```bash
restic -r /srv/restic-repo --verbose backup ~/work
```

Si pasamos 2 veces la opción `--verbose` o (`--verbose=2`) veremos todo lo que restic está haciendo.

### Evitar crear snapshots if no cambia nada

Con la opción `--skip-if-unchanged` no crea el snapshot si no hay ningún cambio:
```bash
restic -r /srv/restic-repo --verbose backup ~/work --skip-if-unchanged
```

## Probando backups con `Dry run`

Con la opción `--Dry run` / `-n` podemos probar y ver lo que podría suceder al realizar un backup sin escribir nada en el repositorio. Combinado con la opción `--verbose` veremos la lista de cambios
```bash
restic -r /srv/restic-repo backup ~/work --dry-run -vv | grep "added"
modified  /plan.txt, saved in 0.000s (9.110 KiB added)
modified  /archive.tar.gz, saved in 0.140s (25.542 MiB added)
Would be added to the repository: 25.551 MiB
```

## Excluyendo ficheros

Podemos excluir carpetas y ficheros usando patrones con estas opciones:

* `--exclude` Especificado una o más veces para excluir uno o más elementos.
* `--iexclude` Igual que `-exclude`, pero ignora las mayúsculas y minúsculas de las rutas.
* `--exclude-caches` Especificado una vez para excluir el contenido de una carpeta si contiene el archivo especial CACHEDIR.TAG, pero conservar
* `-exclude-file` Especificado una o más veces para excluir los elementos enumerados en un archivo determinado.
* `-iexclude-file` Igual que `xclude-fil`, pero ignora casos como en `--iexclud`.
* `-exclude-if-present foo` Especificado una o más veces para excluir el contenido de una carpeta si contiene un archivo llamado foo (opcionalmente con un encabezado determinado, no se admiten comodines para el nombre del archivo).
* `-exclude-larger-than size` Especificado una vez para excluir archivos mayores que el tamaño indicado.
  * La unidad predeterminada para el valor del tamaño es bytes, por lo que, por ejemplo, --exclude-larger-than 2048 excluiría los archivos mayores de 2048 bytes (2 KiB). Para especificar otras unidades, añada al valor del tamaño uno de los siguientes sufijos: k/K para KiB (1024 bytes), m/M para MiB (1024^2 bytes), g/G para GiB (1024^3 bytes) y t/T para TiB (1024^4 bytes), por ejemplo, 1k, 10K, 20m, 20M, 30g, 30G, 2t o 2T).
* `-exclude-cloud-files` Especificado una vez para excluir archivos en la nube solo en línea (como OneDrive Files On-Demand), actualmente solo compatible con Windows.

Consulte `restic help backup` para obtener información más específica sobre cada opción de exclusión.

Supongamos que tenemos un archivo llamado `excludes.txt` con el siguiente contenido:
```
# exclude go-files
*.go
# exclude foo/x/y/z/bar foo/x/bar foo/bar
foo/**/bar
```

Puede ser usado asi:
```bash
restic -r /srv/restic-repo backup ~/work --exclude="*.c" --exclude-file=excludes.txt
```

Esto indica a restic que excluya los archivos que cumplan los siguientes criterios:

* Todos los archivos que coincidan con `*.c` (parámetro `--exclude`)
* Todos los archivos que coincidan con `*.go` (segunda línea en `excludes.txt`)
* Todos los archivos y subdirectorios llamados `bar` que se encuentren en algún lugar debajo de un directorio llamado `foo` (cuarta línea en `excludes.txt`)

Los patrones utilizan la sintaxis de la función filepath.Match de Go (https://pkg.go.dev/path/filepath#Match) y se comprueban con la ruta completa de un archivo/directorio que se va a guardar, incluso si se pasa a restic una ruta relativa para guardar. Las líneas vacías y las líneas que comienzan con un # se ignoran.
```
pattern:
	{ term }
term:
	'*'         matches any sequence of non-Separator characters
	'?'         matches any single non-Separator character
	'[' [ '^' ] { character-range } ']'
	            character class (must be non-empty)
	c           matches character c (c != '*', '?', '\\', '[')
	'\\' c      matches character c

character-range:
	c           matches character c (c != '\\', '-', ']')
	'\\' c      matches character c
	lo '-' hi   matches character c for lo <= c <= hi
```

## Incluyendo ficheros












---

Vamos a configurar todo para hacer copias de seguridad desde

export RESTIC_REPOSITORY="sftp:ubuntu@instancia:/home/ubuntu/temp/restic_repo"

## Bibliografía

* Documentación oficial: https://restic.readthedocs.io/en/stable/
* https://geekland.eu/copias-de-seguridad-con-restic-de-forma-automatica/
* https://adamtheautomator.com/restic-backup/
* https://voidnull.es/restic-el-programa-que-hace-copias-de-seguridad-correctamente/