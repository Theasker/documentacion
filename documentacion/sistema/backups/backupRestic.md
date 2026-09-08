# Copias de seguridad con Restic

- [Copias de seguridad con Restic](#copias-de-seguridad-con-restic)
	- [Preparando el repositorio](#preparando-el-repositorio)
	- [Creando copias de seguridad](#creando-copias-de-seguridad)
		- [Evitar crear snapshots if no cambia nada](#evitar-crear-snapshots-if-no-cambia-nada)
		- [Probando backups con `Dry run`](#probando-backups-con-dry-run)
		- [Excluyendo ficheros](#excluyendo-ficheros)
		- [Incluyendo ficheros](#incluyendo-ficheros)
		- [Comparando Snapshots](#comparando-snapshots)
		- [Leer datos desde un comando](#leer-datos-desde-un-comando)
		- [Etiquetas para los backup](#etiquetas-para-los-backup)
		- [Requerimientos de espacio](#requerimientos-de-espacio)
		- [Códigos de estado de salida](#códigos-de-estado-de-salida)
		- [Variables de entorno](#variables-de-entorno)
	- [Trabajando con repositorios](#trabajando-con-repositorios)
		- [Listando las copias](#listando-las-copias)
		- [Listando ficheros](#listando-ficheros)
	- [Bibliografía](#bibliografía)


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

### Probando backups con `Dry run`

Con la opción `--Dry run` / `-n` podemos probar y ver lo que podría suceder al realizar un backup sin escribir nada en el repositorio. Combinado con la opción `--verbose` veremos la lista de cambios
```bash
restic -r /srv/restic-repo backup ~/work --dry-run -vv | grep "added"
modified  /plan.txt, saved in 0.000s (9.110 KiB added)
modified  /archive.tar.gz, saved in 0.140s (25.542 MiB added)
Would be added to the repository: 25.551 MiB
```

### Excluyendo ficheros

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

### Incluyendo ficheros

* `--files-from` debe ser el nombre de un archivo de texto que contenga un patrón por línea. (https://pkg.go.dev/path/filepath#Match)
* `-files-from-verbatim` debe ser el nombre de un archivo de texto que contenga una ruta por línea, por ejemplo, tal y como lo genera GNU find con el indicador -print.
A diferencia de `-files-from`, `--files-from-verbatim` no expande ningún carácter especial en la lista de rutas, no elimina ningún espacio en blanco y no ignora las líneas que comienzan con un #. Utilice esta opción cuando desee realizar una copia de seguridad de una lista de nombres de archivo que contengan caracteres especiales que, de otro modo, se expandirían al utilizar `--files-from`.

Por ejemplo, tal vez desee hacer una copia de seguridad de los archivos cuyo nombre coincida con un determinado patrón de expresión regular (utiliza GNU find):
```bash
find /tmp/some_folder -regex PATTERN -print0 > /tmp/files_to_backup
```

A continuación, puede utilizar restic para hacer una copia de seguridad de los archivos filtrados:
```bash
restic -r /srv/restic-repo backup --files-from-raw /tmp/files_to_backup
```

Puede combinar las tres opciones entre sí y con los argumentos de archivo normales:
```bash
restic backup --files-from /tmp/files_to_backup /tmp/some_additional_file
restic backup --files-from /tmp/glob-pattern --files-from-raw /
```

### Comparando Snapshots

Miro los snapshots que tengo para localizar los ID que quiero comparar:
```
$ restic snapshots                                                 1 ✘  21:30:53 
repository 2e81e6bb opened (version 2, compression level auto)
ID        Time                 Host         Tags        Paths                                                                                 Size
--------------------------------------------------------------------------------------------------------------------------------------------------------
816e98e7  2025-10-05 12:10:25  theasker-pc              /home/theasker/Escritorio/datos1/scripts/bash/backupRestic/pruebas                    3.360 KiB
7150893d  2025-10-05 12:10:33  theasker-pc              /home/theasker/Escritorio/datos1/scripts/bash/backupRestic/pruebas                    3.360 KiB
2a921c93  2025-10-05 12:14:05  theasker-pc              /home/theasker/Escritorio/datos1/scripts/bash/backupRestic/Taller_Restic_Completo.md  51.907 KiB
eb7255e4  2025-10-05 12:26:52  theasker-pc              /home/theasker/Escritorio/datos1/scripts/bash/backupRestic/Taller_Restic_Completo.md  51.907 KiB
35c72c10  2025-10-06 19:07:30  theasker-pc              /mnt/datos1/scripts/bash/backupRestic/Taller_Restic_Completo.md                       51.907 KiB
97eee9d4  2025-10-06 19:07:40  theasker-pc              /mnt/datos1/scripts/bash/backupRestic/Taller_Restic_Completo.md                       51.907 KiB
--------------------------------------------------------------------------------------------------------------------------------------------------------
```

Y luego hago la comparación:
```
restic diff 816e98e7 2a921c93                                                                      ✔  21:31:22 
repository 2e81e6bb opened (version 2, compression level auto)
comparing snapshot 816e98e7 to 2a921c93:

[0:00] 100.00%  2 / 2 index files loaded
+    /Taller_Restic_Completo.md
-    /pruebas/
-    /pruebas/.restic_pass
-    /pruebas/01_prueba.sh
-    /pruebas/backup_servers.conf
-    /pruebas/temp.sh

Files:           1 new,     4 removed,     0 changed
Dirs:            0 new,     1 removed
Others:          0 new,     0 removed
Data Blobs:      1 new,     4 removed
Tree Blobs:      1 new,     2 removed
  Added:   52.302 KiB
  Removed: 5.216 KiB
```

Si sólo quieres comparar subcarpetas `<snapshot>:<subfolder>`


### Leer datos desde un comando

A veces, puede resultar útil guardar directamente la salida de un programa, por ejemplo, `mysqldump`, para poder restaurar posteriormente el SQL. Restic admite este modo de funcionamiento; solo hay que proporcionar la opción `--stdin-from-command` al utilizar la acción `backup` y escribir el comando en lugar de los archivos/directorios. Para evitar que restic interprete los argumentos del comando, asegúrese de añadir -- antes de que comience el comando:
```bash
restic -r /srv/restic-repo backup --stdin-from-command -- mysqldump --host example mydb [...]
```
Este comando crea una nueva instantánea basada en la salida estándar de `mysqldump`. De forma predeterminada, la salida estándar del comando se guarda en un archivo llamado `stdin`. Se puede especificar un nombre diferente con `--stdin-filename`:
```bash
restic -r /srv/restic-repo backup --stdin-filename production.sql --stdin-from-command -- mysqldump --host example mydb [...]
```
Restic utiliza el código de salida del comando para determinar si el comando se ha ejecutado correctamente. Un código de salida distinto de cero del comando hace que restic cancele la copia de seguridad. Esto provoca que restic falle con el código de salida 1. En este caso, no se creará ninguna instantánea.

### Etiquetas para los backup
```bash
restic -r /srv/restic-repo backup --tag projectX --tag foo --tag bar ~/work
[...]
```

Las etiquetas se pueden utilizar posteriormente para conservar (u olvidar) instantáneas con el comando `forget`. El comando `tag` se puede utilizar para modificar las etiquetas de una instantánea existente.


### Requerimientos de espacio

Si se queda sin espacio durante una copia de seguridad, habrá algunos datos adicionales en el repositorio, pero la instantánea nunca se creará, ya que solo se escribiría al final (correcto) de la operación de copia de seguridad. Las instantáneas anteriores seguirán estando ahí y seguirán funcionando.

### Códigos de estado de salida

Restic devuelve un código de estado de salida después de ejecutar el comando de copia de seguridad:

* 0 cuando la copia de seguridad se ha realizado correctamente (instantánea con todos los archivos de origen creados)
* 1 cuando se ha producido un error grave (no se ha creado ninguna instantánea)
* 3 cuando no se han podido leer algunos archivos de origen (instantánea incompleta con los archivos restantes creados) 
* los demás códigos de salida se documentan en [Códigos de salida.](https://restic.readthedocs.io/en/stable/075_scripting.html#exit-codes)

| 0 | Command was successful |
| 1 | Command failed, see command help for more details |
| 2 | Go runtime error |
| 3 | `backup` command could not read some source data |
| 10 | Repository does not exist (since restic 0.17.0) |
| 11 | Failed to lock repository (since restic 0.17.0) |
| 12 | Wrong password (since restic 0.17.1) |
| 130 | Restic was interrupted using SIGINT or SIGSTOP |

### Variables de entorno

```
RESTIC_REPOSITORY_FILE              Name of file containing the repository location (replaces --repository-file)
RESTIC_REPOSITORY                   Location of repository (replaces -r)
RESTIC_PASSWORD_FILE                Location of password file (replaces --password-file)
RESTIC_PASSWORD                     The actual password for the repository
RESTIC_PASSWORD_COMMAND             Command printing the password for the repository to stdout
RESTIC_KEY_HINT                     ID of key to try decrypting first, before other keys
RESTIC_CACERT                       Location(s) of certificate file(s), comma separated if multiple (replaces --cacert)
RESTIC_TLS_CLIENT_CERT              Location of TLS client certificate and private key (replaces --tls-client-cert)
RESTIC_CACHE_DIR                    Location of the cache directory
RESTIC_COMPRESSION                  Compression mode (only available for repository format version 2)
RESTIC_HOST                         Only consider snapshots for this host / Set the hostname for the snapshot manually (replaces --host)
RESTIC_PROGRESS_FPS                 Frames per second by which the progress bar is updated
RESTIC_PACK_SIZE                    Target size for pack files
RESTIC_READ_CONCURRENCY             Concurrency for file reads

TMPDIR                              Location for temporary files (except Windows)
TMP                                 Location for temporary files (only Windows)

AWS_ACCESS_KEY_ID                   Amazon S3 access key ID
AWS_SECRET_ACCESS_KEY               Amazon S3 secret access key
AWS_SESSION_TOKEN                   Amazon S3 temporary session token
AWS_DEFAULT_REGION                  Amazon S3 default region
AWS_PROFILE                         Amazon credentials profile (alternative to specifying key and region)
AWS_SHARED_CREDENTIALS_FILE         Location of the AWS CLI shared credentials file (default: ~/.aws/credentials)
RESTIC_AWS_ASSUME_ROLE_ARN          Amazon IAM Role ARN to assume using discovered credentials
RESTIC_AWS_ASSUME_ROLE_SESSION_NAME Session Name to use with the role assumption
RESTIC_AWS_ASSUME_ROLE_EXTERNAL_ID  External ID to use with the role assumption
RESTIC_AWS_ASSUME_ROLE_POLICY       Inline Amazion IAM session policy
RESTIC_AWS_ASSUME_ROLE_REGION       Region to use for IAM calls for the role assumption (default: us-east-1)
RESTIC_AWS_ASSUME_ROLE_STS_ENDPOINT URL to the STS endpoint (default is determined based on RESTIC_AWS_ASSUME_ROLE_REGION). You generally do not need to set this, advanced use only.

AZURE_ACCOUNT_NAME                  Account name for Azure
AZURE_ACCOUNT_KEY                   Account key for Azure
AZURE_ACCOUNT_SAS                   Shared access signatures (SAS) for Azure
AZURE_ENDPOINT_SUFFIX               Endpoint suffix for Azure Storage (default: core.windows.net)
AZURE_FORCE_CLI_CREDENTIAL          Force the use of Azure CLI credentials for authentication

B2_ACCOUNT_ID                       Account ID or applicationKeyId for Backblaze B2
B2_ACCOUNT_KEY                      Account Key or applicationKey for Backblaze B2

GOOGLE_PROJECT_ID                   Project ID for Google Cloud Storage
GOOGLE_APPLICATION_CREDENTIALS      Application Credentials for Google Cloud Storage (e.g. $HOME/.config/gs-secret-restic-key.json)

OS_AUTH_URL                         Auth URL for keystone authentication
OS_REGION_NAME                      Region name for keystone authentication
OS_USERNAME                         Username for keystone authentication
OS_USER_ID                          User ID for keystone v3 authentication
OS_PASSWORD                         Password for keystone authentication
OS_TENANT_ID                        Tenant ID for keystone v2 authentication
OS_TENANT_NAME                      Tenant name for keystone v2 authentication

OS_USER_DOMAIN_NAME                 User domain name for keystone authentication
OS_USER_DOMAIN_ID                   User domain ID for keystone v3 authentication
OS_PROJECT_NAME                     Project name for keystone authentication
OS_PROJECT_DOMAIN_NAME              Project domain name for keystone authentication
OS_PROJECT_DOMAIN_ID                Project domain ID for keystone v3 authentication
OS_TRUST_ID                         Trust ID for keystone v3 authentication

OS_APPLICATION_CREDENTIAL_ID        Application Credential ID (keystone v3)
OS_APPLICATION_CREDENTIAL_NAME      Application Credential Name (keystone v3)
OS_APPLICATION_CREDENTIAL_SECRET    Application Credential Secret (keystone v3)

OS_STORAGE_URL                      Storage URL for token authentication
OS_AUTH_TOKEN                       Auth token for token authentication

RCLONE_BWLIMIT                      rclone bandwidth limit

RESTIC_REST_USERNAME                Restic REST Server username
RESTIC_REST_PASSWORD                Restic REST Server password

ST_AUTH                             Auth URL for keystone v1 authentication
ST_USER                             Username for keystone v1 authentication
ST_KEY                              Password for keystone v1 authentication
```

Los programas externos que restic puede ejecutar incluyen rclone (para backends rclone) y ssh (para el backend SFTP). Estos pueden responder a otras variables de entorno y archivos de configuración; consulte sus respectivos manuales.

## Trabajando con repositorios

### Listando las copias
Para listar las copias usamos:
```bash
restic -r /srv/restic-repo snapshots
enter password for repository:
ID        Date                 Host    Tags   Directory        Size
-------------------------------------------------------------------------
40dc1520  2015-05-08 21:38:30  kasimir        /home/user/work  20.643GiB
79766175  2015-05-08 21:40:19  kasimir        /home/user/work  20.645GiB
bdbd3439  2015-05-08 21:45:17  luigi          /home/art        3.141GiB
590c8fc8  2015-05-08 21:47:38  kazik          /srv             580.200MiB
9f0bc19e  2015-05-08 21:46:11  luigi          /srv             572.180MiB
```
Puedes filtrar el listado por la ruta del directorio:
```bash
restic -r /srv/restic-repo snapshots --path="/srv"
enter password for repository:
ID        Date                 Host    Tags   Directory  Size
-------------------------------------------------------------------
590c8fc8  2015-05-08 21:47:38  kazik          /srv       580.200MiB
9f0bc19e  2015-05-08 21:46:11  luigi          /srv       572.180MiB
```

O filtrar por host:
```bash
restic -r /srv/restic-repo snapshots --host luigi
enter password for repository:
ID        Date                 Host    Tags   Directory  Size
-------------------------------------------------------------------
bdbd3439  2015-05-08 21:45:17  luigi          /home/art  3.141GiB
9f0bc19e  2015-05-08 21:46:11  luigi          /srv       572.180MiB
Combining filters is also possible.
```

Además puedes agrupar la salida por los mismos filtros (host, paths, tags):
```bash
restic -r /srv/restic-repo snapshots --group-by host

enter password for repository:
snapshots for (host [kasimir])
ID        Date                 Host    Tags   Directory        Size
------------------------------------------------------------------------
40dc1520  2015-05-08 21:38:30  kasimir        /home/user/work  20.643GiB
79766175  2015-05-08 21:40:19  kasimir        /home/user/work  20.645GiB
2 snapshots
snapshots for (host [luigi])
ID        Date                 Host    Tags   Directory  Size
-------------------------------------------------------------------
bdbd3439  2015-05-08 21:45:17  luigi          /home/art  3.141GiB
9f0bc19e  2015-05-08 21:46:11  luigi          /srv       572.180MiB
2 snapshots
snapshots for (host [kazik])
ID        Date                 Host    Tags   Directory  Size
-------------------------------------------------------------------
590c8fc8  2015-05-08 21:47:38  kazik          /srv       580.200MiB
1 snapshots
```

### Listando ficheros

Listamos los ficheros de un snapshot específico:
```bash
restic ls 073a90db

snapshot 073a90db of [/home/user/work.txt] filtered by [] at 2024-01-21 16:51:18.474558607 +0100 CET):
/home
/home/user
/home/user/work.txt
```

Listamos los ficheros del último snapshot:
```bash
restic ls --host kasimir latest

snapshot 073a90db of [/home/user/work.txt] filtered by [] at 2024-01-21 16:51:18.474558607 +0100 CET):
/home
/home/user
/home/user/work.txt
```
Listamos un directorio específico:
```bash
restic ls latest /home

snapshot 073a90db of [/home/user/work.txt] filtered by [/home] at 2024-01-21 16:51:18.474558607 +0100 CET):
/home
/home/user
```

Y el directorio de forma recursiva:
```bash
restic ls --recursive latest /home

snapshot 073a90db of [/home/user/work.txt] filtered by [/home] at 2024-01-21 16:51:18.474558607 +0100 CET):
/home
/home/user
/home/user/work.txt
```

































---

Vamos a configurar todo para hacer copias de seguridad desde

export RESTIC_REPOSITORY="sftp:ubuntu@instancia:/home/ubuntu/temp/restic_repo"

## Bibliografía

* Documentación oficial: https://restic.readthedocs.io/en/stable/
* https://geekland.eu/copias-de-seguridad-con-restic-de-forma-automatica/
* https://adamtheautomator.com/restic-backup/
* https://voidnull.es/restic-el-programa-que-hace-copias-de-seguridad-correctamente/