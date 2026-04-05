# Crear un servidor propio de Git

Crear el usuario git
--------------------

    $ sudo adduser git

y colocamos una clave suficientemente segura y nos aseguramos que la carpeta ssh sea accesible al usuario.

    $ sudo mkdir /home/git/.ssh
    $ sudo chown -R git:git /home/git/.ssh

Ahora probamos que nos podemos conectar al SERVER por ssh con el usuario git. Recordar que si se tiene la restricción de usuario () en el ssh es necesario agregarle al nuevo git.

    $ ssh git@myserver.com

Logrado ésto (y sin salir del ssh) creamos una carpeta e inicializamos un repositorio en ella.

    $ mkdir mi_proyecto.git
    $ cd !$
    $ git --bare init

Ahora podemos cerrar el ssh con:

    $ exit

Preparación del CLIENTE
-----------------------

### Instalamos git y lo configuramos

Le indicamos a GIT quien soy  

    $ git config --global user.name "Nombre de usuario"
    $ git config --global user.email "mi_email@algo.com"

Algunos parámetros útiles para el modo texto pueden ser:

    $ git config --global color.status auto
    $ git config --global color.branch auto
    $ git config --global color.diff auto
    $ git config --global color.interactive auto

Nos paramos donde queremos poner nuestros proyectos

    $ mkdir mi_codigo
    $ cd !$

Partimos de la base que no tenemos nada de código hecho aun. Entonces clonamos el contenido del servidor que aun esta vacío.

    $ git clone git@127.0.0.1:mi_proyecto.git

Esto inicializa un git en esa carpeta y trae los datos del servidor. En caso de que el SERVIDOR tenga el ssh escuchando en el puerto 522 en vez del que viene por defecto (22) se debe hacer:

    $ git clone ssh://git@127.0.0.1:522/~/mi_proyecto.git

con esto nos crea la carpeta mi_proyecto dentro de mi_codigo con lo necesario.

Y ya podemos trabajar:

    $ git add .
    $ git commit "Subo mi código con este comentario"

Y ahora le decimos que lo mande al repositorio.

    $ git push origin master

Bibliografía
------------

 * https://git-scm.com/book/en/v2/Git-on-the-Server-Getting-Git-on-a-Server
 * https://principiantedelinux.wordpress.com/2011/06/25/configurar-un-servidor-git-propio/?utm_source=pocket_mylist