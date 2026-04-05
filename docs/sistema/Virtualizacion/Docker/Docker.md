# Docker

- [Docker](#docker)
	- [Uso básico de Docker](#uso-básico-de-docker)
		- [Busquedas en la nube](#busquedas-en-la-nube)
		- [Parada y comienzo de un container](#parada-y-comienzo-de-un-container)
		- [Publicar una imagen a dockerhub](#publicar-una-imagen-a-dockerhub)
		- [Grabar / importar una imagen de docker](#grabar--importar-una-imagen-de-docker)
	- [Networking](#networking)
		- [Exponer un puerto](#exponer-un-puerto)
		- [Publicar un puerto](#publicar-un-puerto)
		- [Enlazar contenedores](#enlazar-contenedores)
			- [Enlace de 2 contenedores con apache2 y MySQL para su uso en producción.](#enlace-de-2-contenedores-con-apache2-y-mysql-para-su-uso-en-producción)
	- [Volumenes](#volumenes)
		- [Contenedores de datos](#contenedores-de-datos)
	- [Supervisor](#supervisor)
		- [Lanzar supervisor en primer plano en un container](#lanzar-supervisor-en-primer-plano-en-un-container)
	- [Dockerfile](#dockerfile)
		- [Comando de Dockerfile](#comando-de-dockerfile)
	- [Enlaces](#enlaces)


## Uso básico de Docker
Ejecución de un container con consola interactiva

	docker run --name test -h test -it ubuntu bash

  * **--name test**: Le damos un nombre a la sesión
  * **-h test**: El hostname
  * **-it**: Consola interactiva
  * **bash**: Comando que ejecutamos

Ver los dockers que se han ejecutado

	docker ps -a

Información de un container:

	docker inspect <ID CONTAINER>

Creación de una nueva imagen basada en un container:

	docker commit test ubuntu_apache
	docker commit -m "Contenedor con node" -a "Theasker" node theasker/node:0.1

 * **test**: Nombre del contenedor en el que nos basamos.
 * **ubuntu_apache**: Nombre que le damos al conetenedor.
 * **-a**: Autor
 * **`theasker/node:0.1`**: <autor>/<contenedor de la imagen>:<version>

Ejecutar un container en segundo plano basado en una imagen con un servidor web activo en FOREGROUND:

	docker run -d --name web -h web ubuntu_apache bash -c "apache2ctl -D FOREGROUND"

 * **-d**: Desatachado, es decir, sin consola virtual interactiva (ejecutado de fondo o background).
 * **--name web**: Nombre que le damos al contenedor.
 * **-h web**: Hostname
 * **ubuntu_apache**: imagen en la que nos basamos para ejecutar el contenedor.
 * **bash -c "apache2ctl -D FOREGROUND"**: Comando que ejecutamos desde bash

Para salir de un container dejándolo en ejecución en segundo plano:

	 CTRL-p CTRL-q

Comandos que se están ejecutando en un contenedor:

	docker top <CONTAINER NAME/ID>

Mostrar la salida de un contenedor en ejecución:

	docker logs <CONTAINER NAME/ID>

Ejectuar un comando dentro de un container en ejecución:

	docker exec web bash -c "apt-get update && apt-get install nano"	
	docker exec -it web bash -c "apt-get update && apt-get install nano"	

Otra forma de entrar en un container en ejecución sería con 'docker attach <hash>' entramos en forma interactiva a ese container

	docker attach fce2354f3

 * **--sig-proxy=true**: Lo que hace es decirle que todo lo que ejecutemos en la consola que abrimos le mande las señales a la consola principal, por lo que si hicieramos un 'Crtl+c' mataríamos el comando principal con el que ejecutamos el contenedor (docker ... bash -c "apache2ctl -D FOREGROUND")

Eliminar un contenedor

	docker rm fce2354f3

Eliminar todos los contenedores

	docker container prune

Copiar un fichero al container:

	docker cp temp.txt web:/tmp

 * **web:/tmp**: 'web es el nombre del container y '/tmp' es la ruta donde quero copiar el fichero

Para ver las diferencias del contenedor con las diferentes instancias. El hash que pongamos es a partir del cual nos mostrará las diferencias del contenedor:

	docker diff 8a46cfae0b49

### Busquedas en la nube

	docker search gentoo

Busca los contenedores con gentoo y directamente podemos arrancarlos y si no los tiene los descarga.

### Parada y comienzo de un container

	docker stop <id/name>

	docker start <id/name>

### Publicar una imagen a dockerhub

Ponerle una etiqueta a la imagene que queremos subir.

	docker tag ubuntu_apache theasker/ubuntu_apache

Subimos la imagen

	doker push theasker/ubuntu_apache

### Grabar / importar una imagen de docker

Para guardar una imagen en nuestro disco duro:

	docker pull ubuntu:latest
	docker save ubuntu:latest | gzip > /ruta/al/fichero/ubuntu.tar.gz

Para importar una imagen:

	docker load < /ruta/al/fichero/ubuntu.tar.gz

## Networking

### Exponer un puerto

Publicamos los puertos que usa el contenedor

	docker run -it --expose 80 --expose 3306 --name test debian bash

### Publicar un puerto

"Publicamos" 

Publicamos el puerto 80 del contenedor en un puerto aleatorio del host

	docker run -it --name test -p 80 debian bash


Publicamos el puerto 80 del contenedor en el 8080 del host

	docker run -it --name test -p 8080:80 debian bash


Publicamos todos los puertos expuestos a puertos aleatorios del host

	docker run -it --name test -P debian bash


Comprobación de puertos publicados de un contenedor

	docker port test
	80/tcp -> 0.0.0.0:32788

	docker port test1 80
	0.0.0.0:32788

Exponemos el puerto 80 del contenedor y publicamos en el host todos los puertos, como no le hemos dicho a qué puerto del host lo vamos a redireccionar, docker lo publicará a un puerto aleatorio

	docker run --expose 80 -P -d --name web -h web mijack/apache2 bash -c "apache2ctl -D FOREGROUND"

Vemos que lo ha redireccionado a:

	docker port web
	80/tcp -> 0.0.0.0:32768


### Enlazar contenedores

Crear un contenedor añadiendole cierta información de otro contenedor ya existente para que el acceso entre ambas sea más sencillo con variables de entorno.

	docker run -it --name test1 -h test1 mijack/apache2 bash
	docker run --link test1 -rm -it --name test2 -h test2 debian bash


Docker modifica el fichero '/etc/hosts' añadiendo la ip de la máquina enlazada para que su acceso sea rápido

	root@test2:/# cat /etc/hosts
	[...]
	172.17.0.2      test1 test1
	172.17.0.3      test2

Crea unas variables de entorno por cada puerto expuesto en test1 para que sea más fácil su acceso, así como el hostname del contenedor enlazado, así como su variable HOME y PATH.

	root@test2:/# env
	TEST1_NAME=/test2/test1
	TEST1_PORT_80_TCP_ADDR=172.17.0.2
	HOSTNAME=test2
	TEST1_PORT_80_TCP=tcp://172.17.0.2:80
	TERM=xterm
	TEST1_PORT_80_TCP_PROTO=tcp
	TEST1_PORT=tcp://172.17.0.2:80
	PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
	TEST1_PORT_80_TCP_PORT=80
	PWD=/
	SHLVL=1
	HOME=/root
	_=/usr/bin/env

#### Enlace de 2 contenedores con apache2 y MySQL para su uso en producción.

Vamos a enlazar un contendor con apache2 y otro con Mysql.

Primero creamos un contenedor ('run') con consola virtual interactiva ('-it') que lo llamaremos db ('--name db') con hostname db ('-h db') basado en debian con el intérprete de comandos bash

	docker run -it --name db -h db debian bash

Comentamos la línea del archivo /etc/mysql/my.cnf

	#bind-address = 127.0.0.1

Comprobamos que es cierto que mysql está escuchando en todos los interfaces de red. (Instalamos antes net-tools para usar netstat)

	netstat -planet |grep LISTEN
	tcp        0      0 0.0.0.0:3306            0.0.0.0:*               LISTEN      104        128309      -             

Damos permisos a todas las bases de datos al usuario root

	mysql> grant all on *.* to 'root'@'%';
	mysql> flush privileges;

Ahora arrancamos el segundo contenedor enlazado al contenedor web:

	docker run --link db -it --name web -h web mijack/apache2 bash

Vemos las variables de entorno:

	root@web:/# env
	HOSTNAME=web
	DB_NAME=/web/db
	TERM=xterm
	PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
	PWD=/
	SHLVL=1
	HOME=/root
	_=/usr/bin/env

Ahora podemos hacer un ping a la máquina enlazada

	root@web:/#  ping db
	PING db (172.17.0.2): 56 data bytes
	64 bytes from 172.17.0.2: icmp_seq=0 ttl=64 time=0.128 ms

Ahora nos podemos conectar a MySQL desde el contenedor web

	root@web:~# mysql -h db -uroot
	...
	mysql>


## Volumenes

Creamos un contenedor que monta el directorio '/home/theasker/Descargas' del host en '/mnt/datos' del contenedor.

	docker run -it -v /home/theasker/Descargas:/mnt/datos debian bash

Muy utip para montar por ejemplo un direcotorio con el código de nuestra aplicación, o para montar un directorio donde guardemos los logs del sistema, etc.

### Contenedores de datos

Crear un contenedor al que vamos a enlazar un volumen (que lo llamo 'container_code' que no lo vamos a utilizar, es decir, que no se va a ejecutar (comando '/bin/false'), los demás containers montarán los volúmenes de este container

	docker run -it -v /home/theasker/Descargas:/mnt/descargas --name container_code debian /bin/false

Ahora podemos crear un container que le diremos que monte los volúmenes del container que hemos usado anteriormente para los volúmenes, en mi caso 'container_name'

	docker run -it --name code -h code --volumes-from container_code debian bash

Podremos poner varios nombres de containers después de '-volumes-from' para que nuestro container monte todos los volúmenes que queremos.

## Supervisor

Es un servicio que sin tener que usar el sistema de runlevels podamos lanzar varios procesos. Podemos crear archivos .conf en el directorio '/etc/supervisor/conf.d/*.conf' con las configuraciones que queramos del estilo a:

	[supervisord]
	nodaemon=true
	
	[program:apache2]
	command=/bin/bash -c "source /etc/apache2/envvars && exec /usr/sbin/apache2 -DFOREGROUND"
	autorestart=true

La opción 'autorestart=true' hace que si en algún momento el comando sale o no está en ejecución supervisor vuelva a intentar la ejecución del comando.

### Lanzar supervisor en primer plano en un container

	docker run -it --name superv -h superv supervisor_web bash -c "/usr/bin/supervisord"

## Dockerfile

Un ejemplo de Dockerfile para crear una imagen de Wordpress

	FROM debian:latest
	
	MAINTAINER Thesker <theasker@gmail.com>
	
	RUN apt-get update
	
	# mysql config and install
	RUN echo "mysql-server-5.5 mysql-server/root_password password root" | debconf-set-selections
	RUN echo "mysql-server-5.5 mysql-server/root_password_again password root" | debconf-set-selections
	
	# services and libs installation
	RUN apt-get install apache2 php5-mysql php5 libapache2-mod-php5 mysql-server-5.5 wget nano curl -y
	
	# mysql permission set
	RUN /etc/init.d/mysql start && mysql -uroot -proot -e "create database wordpress;" && mysql -uroot -proot -e "grant all on wordpress.* to wordpress@localhost identified by 'dbpassword';flush privileges "
	
	# Wordpress download and install
	
	# Wordpress download and decompress
	RUN cd /tmp;wget https://wordpress.org/latest.tar.gz
	RUN cd /var/www/html; rm index.html; tar xvzf /tmp/latest.tar.gz; mv wordpress/* .;rm -rf wordpress
	
	# Wordpress main config file copy
	COPY wp-config.php /var/www/html/wp-config.php
	
	# permission set /var/www/html
	RUN chown -R www-data:www-data /var/www/html/
	
	# ssh
	RUN apt-get install ssh -y
	RUN useradd -m -G users,root -s /bin/bash theasker
	RUN echo "theasker:theasker" | chpasswd	theasker
	RUN mkdir /var/run/sshd # Cuando lanzamos a mano sin el init.d el ssh este directorio no existe y hay que crearlo
	
	## Supervisord config
	RUN apt-get install supervisor -y
	COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
	
	# Informamos que vamos a usar los puertos 22 y 80
	EXPOSE 22 80
	CMD ["/usr/bin/supervisord"]

Ahora en comando para crear la imagen:

	docker build -t wordpress .

Para probarlo podemos usar el comando create de docker para crear un contenedor sin ejecutar ningún comando, ya que todo lo que necesitamos ejecutar lo hace supervisor, aunque con esta opción, hay que iniciar el contenedor:

	docker create -P --name test -h test wordpress
	docker start test

### Comando de Dockerfile

	* **`RUN`:** Especifica la imagen base sobe la que vamos a partir.
	* **`MAINTAINER`:** Información sobre el creador de la imagen
	* **`ENV`:** Permite crear variables de entor que serán interpretadas por Dockerfile:
```docker
# Dockerfile
FROM ubuntu:14.04
ENV root /carpeta_prueba
ADD $root
```
y esto lo que hará es declarar una variable de entorno `root` cuyo valor será `carpeta_prueba` y la añadirá al sistema de ficheros del contenedor.

Las variables se pueden escribir como `$nombre_var` o como `${nombre_var}`.

  * **`RUN`:** Se utiliza para ejecutar instrucciones en nuestro contenedor. **Importante:** Hay que intentar no introducir muchas líneas con `RUN`, y sustituir por la concatenación de instrucciones con `&&`, ya que cada vez que usemos el mocando `RUN` se creará un nuevo layer en nuestra imagen.
  * **`CMD`:** Esta instrucción indica un comando o programa que se ejecutará dentro del contenedor una vez que esté completamente construido. La sintaxis correcta es:

```bash
CMD ["echo","hello world"]
```

  * **`COPY`:** Copia ficheros del host al container. Se suele usar para copiar archivos de configuración ya modificados como por ejemplo los de wordpress, apache ya modificados a nuestro gusto.
  * **`ADD`:** `ADD <origen>... <destino>` Esta instrucción copia ficheros, directorios o ficheros remotos desde y los añade al sistema de ficheros del contenedor en el path. El parámetro <origen> acepta caracteres comodín (tipo ?, *…) que se cumplirán (o no) en base a las reglas impuestas por filepath.Match. En caso de que el origen sea un fichero comprimido, lo descomprime en el destino de la misma forma que lo haríamos con tar -x. Hay unas cuantas reglas a tener en cuenta. Es muy recomendable echarles un ojo ya que explican las diferentes casuísticas que se pueden dar. Un tema básico es que <origen> debe estar dentro del contexto de contrucción de la imagen, es decir, donde está el Dockerfile.
  * **`WORKDIR`:** Permite especificarle a Docker en que directorio va a ejecutar una instrucción CMD, RUM o ENTRYPOINT, como por ejemplo:

```
WORKDIR /var/www
RUN pwd
```

dónde esto devolvería /var/www.

  * **`EXPOSE`:** Esta instrucción permite exponer puertos, permitiéndonos exponer un contenedor con el mundo exterior (internet, nuestro host, etc…). Por ejemplo, podemos exponer los puertos 22 (para SSH), 80 (para HTTP), 3306 (MySQL), etc…
  * **`VOLUME`:** Esta instrucción permite establecer puntos de montaje para que cuando usemos el contenedor podamos tener acceso externo (desde nuestro host) a un determinado directorio del contenedor. Por ejemplo, al indicar la instrucción VOLUME ["/var/www"] estaremos creando un punto de montaje en el directorio especificado permitiendo compartir dicho directorio con otros contenedores o con la máquina anfitrión.
  * **`USER`:** La instrucción USER especifica un usuario en el que la imagen se ejecutará, por ejemplo: `USER nginx` Esto hará que los contenedores creados a partir de la imagen se ejecuten por el usuario nginx. Podemos especificar un nombre de usuario o un UID y grupo o GID. O incluso una combinación de los mismos, por ejemplo:

```
USER user
USER user:group
USER uid
USER uid:gid
USER user:gid
USER uid:group
```

También podemos sobrescribir ésto en tiempo de ejecución especificando el parámetro –u con el comando “docker run”.

  * **`VOLUME`:** La instrucción VOLUME añade volúmenes a cualquier contenedor creado a partir de una imagen. Un volumen es un directorio especialmente designado dentro de uno o más contenedores que no utiliza el sistema de archivos del contenedor, aunque se integra en el mismo para proporcionar varias funciones útiles para que los datos sean persistentes y se puedan compartir con facilidad:
  	* Los volúmenes pueden ser compartidos y reutilizados entre los contenedores.
  	* Un contenedor no tiene que estar en ejecución para compartir sus volúmenes.
  	* Los cambios en un volumen se hacen directamente.
  	* Los cambios en un volumen no se incluirán al actualizar una imagen.
  	* Los volúmenes persisten incluso cuando dejan de usarlo los contenedores.
Esto nos permite añadir datos (como el código fuente), una base de datos, o cualquier otro contenido en una imagen sin comprometer la imagen y nos permite compartir los datos entre los contenedores. Esto se puede utilizar para hacer pruebas con los contenedores y el código de una aplicación, administrar registros, o manejar bases de datos dentro de un contenedor.
La instrucción VOLUME se puede utilizar de la siguiente forma:

```
VOLUME [“/opt/app”]
```

Esta instrucción crea un punto de montaje en “/opt/app” a cualquier contenedor creado desde esta imagen.
También podemos especificar múltiples volúmenes especificando un array:

	VOLUME [“/opt/app”,”/datos”]


## Enlaces

 * [Curso de Docker de Miguel Arranz](https://www.youtube.com/playlist?list=PLfW3im2fiA7W9F4DbjmRDIZgAHsea20ON)
 * Home page: https://www.docker.com/
 * Compilar y documentar tu servidor usando Dockerfile: https://platzi.com/blog/imagenes-con-dockerfile/
 * [Redirección de puertos en Virtualbox](http://miperrosecomiolosrespaldos.blogspot.com.es/2013/08/redireccion-de-puertos-en-virtualbox.html)
 * [Curso de docker en Capside](http://capside-academy.usefedora.com/courses/docker-devops)
 * [The Complete Docker Course for DevOps and Developers](https://www.udemy.com/docker-tutorial-for-devops-run-docker-containers/learn/v4/)
 * [Instrucciones en el fichero Dockerfile](http://www.nacionrosique.es/2016/06/instrucciones-en-el-fichero-dockerfile.html)
 * [Redes en Docker](http://www.nacionrosique.es/2016/07/redes-en-docker.html)