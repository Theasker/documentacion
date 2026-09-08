# Cómo trabajar con submodules en Git

- [Cómo trabajar con submodules en Git](#cómo-trabajar-con-submodules-en-git)
  - [Agregar un nuevo submódulo a un proyecto existente](#agregar-un-nuevo-submódulo-a-un-proyecto-existente)
  - [Clonado de repositorios con submódulos](#clonado-de-repositorios-con-submódulos)
  - [Actualizar submódulos cuando hay nuevos commits](#actualizar-submódulos-cuando-hay-nuevos-commits)
    - [Comprobar si hay actualizaciones en una repositorio remoto](#comprobar-si-hay-actualizaciones-en-una-repositorio-remoto)
    - [1ª opción](#1ª-opción)
    - [2ª opción](#2ª-opción)
    - [3ª opción](#3ª-opción)
    - [4ª opcion](#4ª-opcion)
  - [Bibliografía](#bibliografía)


## Agregar un nuevo submódulo a un proyecto existente

    git submodule add git@theasker.duckdns.org:/mnt/datos/git/pruebas.git submodules/pruebas

Esto añade el repositorio pruebas en la localización `submodules/pruebas` de nuestro proyecto.

Si hacemos un `git status` veremos que se han creado **2 ficheros**, uno es `.gitmodules` y otro un fichero llamado como la ruta del submódulo `submodules/pruebas`

    $ git status
    On branch master
    Your branch is up to date with 'origin/master'.

    Changes to be committed:
    (use "git restore --staged <file>..." to unstage)
        new file:   .gitmodules
        new file:   submodules/pruebas

Si miramos el archivo `.gitmodules` veremos que tiene los metadatos de la información del submódulo, con la ruta donde está clonado y su url:

    $ cat .gitmodules 
    [submodule "submodulo/pruebas"]
        path = submodules/pruebas
        url = git@theasker.duckdns.org:/mnt/datos/git/pruebas.git

Agregamos los nuevos ficheros, para poder consultar lo que hay en esos nuevos ficheros (`.gitmodules` y `submodules/pruebas`):

    $ git add .
    $ git diff --staged
    diff --git a/.gitmodules b/.gitmodules
    new file mode 100644
    index 0000000..e28f9a5
    --- /dev/null
    +++ b/.gitmodules
    @@ -0,0 +1,3 @@
    +[submodule "submodules/pruebas"]
    +       path = submodules/pruebas
    +       url = git@theasker.duckdns.org:/mnt/datos/git/pruebas.git
    diff --git a/submodules/pruebas b/submodules/pruebas
    new file mode 160000
    index 0000000..6bb3588
    --- /dev/null
    +++ b/submodules/pruebas
    @@ -0,0 +1 @@
    +Subproject commit 6bb3588b68e13eb6ea5a7846e5f4e140ee128bf4

Hacemos el commit para tenerlo guardado:

    $ git commit -m "Agregado submodulo"
    $ git push

## Clonado de repositorios con submódulos

Si en estos momentos clonáramos el repositorio en otra ubicación, veremos que el contenido de la carpeta del submódulo está vacía:

    $ git clone test/ test2
    Cloning into 'test2'...
    done.
    $ tree
    .
    ├── test
    │   ├── README.md
    │   └── submodules
    │       └── pruebas
    │           └── README.md
    └── test2
        ├── README.md
        └── submodules
            └── pruebas

    6 directories, 3 files

Listamos el directorio del submodulo

    $ cd test2/
    $ ls submodules/pruebas/
    $

Cuando se clona un repositorio con submódulos, **lo primero que tendremos que hacer es inicializar los submódulos** para registrar los submodulos:

    $ git submodule init
    Submodule 'submodules/pruebas' (git@theasker.duckdns.org:/mnt/datos/git/pruebas.git) registered for path 'submodules/pruebas'

A pesar de esto aun está vacío el submódulo:

    $ tree
    .
    ├── README.md
    └── submodules
        └── pruebas

Luego una vez que git ya lo ha registrado, tendremos que actualiza el submódulo para descargarlos:

    $ git submodule update
    Cloning into '/home/ubuntu/temp/test2/submodules/pruebas'...
    git@theasker.duckdns.org's password: 
    Submodule path 'submodules/pruebas': checked out '6bb3588b68e13eb6ea5a7846e5f4e140ee128bf4'

Con esto ya veremos los archivos en el submódulo:

    $ tree
    .
    ├── README.md
    └── submodules
        └── pruebas
            └── README.md


## Actualizar submódulos cuando hay nuevos commits

Con el comando `git submodule` compruebo los submódulos que hay en mi repo y el hash del commit al que apunta.

    $ git submodule
    6bb3588b68e13eb6ea5a7846e5f4e140ee128bf4 submodules/pruebas (heads/master)

### Comprobar si hay actualizaciones en una repositorio remoto

Comprobamos si hay modificaciones en nuestro submódulo y si las hay nos las descargamos directamente en el submódulo:

    ubuntu@theasker-20220727:~/temp/test/$ cd submodules/pruebas
    ubuntu@theasker-20220727:~/temp/test/$ git fetch
    ubuntu@theasker-20220727:~/temp/test/$ git diff ...origin
    diff --git a/fichero.txt b/fichero.txt
    new file mode 100644
    index 0000000..38eead0
    --- /dev/null
    +++ b/fichero.txt
    @@ -0,0 +1 @@
    +Segundo fichero

También podríamos haber consultado las diferencias con:

    ubuntu@theasker-20220727:~/temp/test/$ git diff remotes/origin/master master

o

    ubuntu@theasker-20220727:~/temp/test/$ git diff master...origin

### 1ª opción

Una vez hemos visto que hay diferencias las descargamos

    $ git pull
    git@theasker.duckdns.org's password: 
    Updating 6bb3588..ccf68a3
    Fast-forward
    fichero.txt | 1 +
    1 file changed, 1 insertion(+)
    create mode 100644 fichero.txt

Al hacer un `git diff` nos propone actualizar el repositorio, que ve la carpeta **submodules/pruebas** como un fichero en vez de un directorio:

    ubuntu@theasker-20220727:~/temp/test/submodules/pruebas$ cd ..
    ubuntu@theasker-20220727:~/temp/test/submodules$ cd ..
    ubuntu@theasker-20220727:~/temp/test$ git diff
    diff --git a/submodules/pruebas b/submodules/pruebas
    index 6bb3588..ccf68a3 160000
    --- a/submodules/pruebas
    +++ b/submodules/pruebas
    @@ -1 +1 @@
    -Subproject commit 6bb3588b68e13eb6ea5a7846e5f4e140ee128bf4
    +Subproject commit ccf68a36c94982695ca28f6aa09c4bd94380ea0a

El comando git submodule ya nos muestra el hash actualizado del submodulo

    ubuntu@theasker-20220727:~/temp/test$ git submodule
    +ccf68a36c94982695ca28f6aa09c4bd94380ea0a submodules/pruebas (heads/master)

Actulizamos el repo con la actualización del submódulo:

    ubuntu@theasker-20220727:~/temp/test$ git add .
    ubuntu@theasker-20220727:~/temp/test$ git commit -m "Submudulo actualizado"
    [master 74a0be3] Submudulo actualizado
    1 file changed, 1 insertion(+), 1 deletion(-)

### 2ª opción

Hacemos un "merge" con la rama principal:

    ubuntu@theasker-20220727:~/temp/test2/submodules/pruebas$ git merge origin/master
    Updating 6bb3588..ccf68a3
    Fast-forward
    fichero.txt | 1 +
    1 file changed, 1 insertion(+)
    create mode 100644 fichero.txt

### 3ª opción

Cuando hay múltiples submódulos y queramos actualizar todos:

    ubuntu@theasker-20220727:~/temp/test3$ git submodule update --remote --recursive
    git@theasker.duckdns.org's password: 
    Submodule path 'submodules/pruebas': checked out 'ccf68a36c94982695ca28f6aa09c4bd94380ea0a'

Ahora ya si queremos podemos actualizar nuestro repositorio principal, ya que tenemos cambios en el submódulo como nos indica:

    ubuntu@theasker-20220727:~/temp/test3$ git diff
    diff --git a/submodules/pruebas b/submodules/pruebas
    index 6bb3588..ccf68a3 160000
    --- a/submodules/pruebas
    +++ b/submodules/pruebas
    @@ -1 +1 @@
    -Subproject commit 6bb3588b68e13eb6ea5a7846e5f4e140ee128bf4
    +Subproject commit ccf68a36c94982695ca28f6aa09c4bd94380ea0a

### 4ª opcion

Podemos actualizar todo de golpe:

    ubuntu@theasker-20220727:~/temp/test4$ git pull --recurse-submodules
    remote: Enumerating objects: 5, done.
    remote: Counting objects: 100% (5/5), done.
    remote: Compressing objects: 100% (2/2), done.
    remote: Total 3 (delta 1), reused 0 (delta 0), pack-reused 0
    Unpacking objects: 100% (3/3), 277 bytes | 277.00 KiB/s, done.
    From /home/ubuntu/temp/test
    260e261..74a0be3  master     -> origin/master
    Updating 260e261..74a0be3
    Fast-forward
    submodules/pruebas | 2 +-
    1 file changed, 1 insertion(+), 1 deletion(-)

Incluso haciendo un `git clone`:

    ubuntu@theasker-20220727:~/temp$ git clone --recurse-submodules git@theasker.duckdns.org:/mnt/datos/git/test.git test-final
    Cloning into 'test-final'...
    git@theasker.duckdns.org's password: 
    remote: Enumerando objetos: 25, listo.
    remote: Contando objetos: 100% (25/25), listo.
    remote: Comprimiendo objetos: 100% (19/19), listo.
    remote: Total 25 (delta 6), reusado 0 (delta 0)
    Receiving objects: 100% (25/25), done.
    Resolving deltas: 100% (6/6), done.
    Submodule 'submodules/pruebas' (git@theasker.duckdns.org:/mnt/datos/git/pruebas.git) registered for path 'submodules/pruebas'
    Cloning into '/home/ubuntu/temp/test-final/submodules/pruebas'...
    git@theasker.duckdns.org's password: 
    remote: Enumerando objetos: 6, listo.        
    remote: Contando objetos: 100% (6/6), listo.        
    remote: Comprimiendo objetos: 100% (3/3), listo.        
    remote: Total 6 (delta 0), reusado 0 (delta 0)        
    Receiving objects: 100% (6/6), done.
    Submodule path 'submodules/pruebas': checked out '6bb3588b68e13eb6ea5a7846e5f4e140ee128bf4'


Bibliografía
------------
 * https://git-scm.com/book/es/v2/Herramientas-de-Git-Subm%C3%B3dulos
 * https://www.youtube.com/watch?v=YVUkxt3Bvwg