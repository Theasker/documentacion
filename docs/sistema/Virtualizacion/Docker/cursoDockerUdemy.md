# Curso Docker de Udemy (Ricardo Andre Gonzalez Gomez)

<!-- TOC -->

- [Curso Docker de Udemy (Ricardo Andre Gonzalez Gomez)](#curso-docker-de-udemy-ricardo-andre-gonzalez-gomez)
  - [Imagenes y su contruccion (Dockerfile)](#imagenes-y-su-contruccion-dockerfile)
    - [FROM](#from)
    - [RUN](#run)
    - [COPY / ADD  ](#copy--add--)
    - [ENV](#env)
    - [WORKDIR](#workdir)
    - [EXPOSE](#expose)
    - [LABEL](#label)
    - [USER](#user)
    - [CMD](#cmd)
    - [dockerignore](#dockerignore)
    - [resumen hasta ahora](#resumen-hasta-ahora)
    - [Buenas prácticas en creación de DockerFile](#buenas-prácticas-en-creación-de-dockerfile)
    - [Multi Stage Build](#multi-stage-build)
    - [Tratamiento de imagenes](#tratamiento-de-imagenes)
      - [Eliminar imágenes `docker rmi`](#eliminar-imágenes-docker-rmi)
      - [Eliminar imágenes "huerfana"](#eliminar-imágenes-huerfana)
      - [Contruir una imagen con otro fichero distinto de DockerFile](#contruir-una-imagen-con-otro-fichero-distinto-de-dockerfile)
    - [Creación de imágenes de ejemplo](#creación-de-imágenes-de-ejemplo)
      - [Apache + PHP + SSL](#apache--php--ssl)
      - [Creación de una imagen con Nginx y PHP-FPM](#creación-de-una-imagen-con-nginx-y-php-fpm)
      - [Apache + php 7](#apache--php-7)
  - [Contenedores](#contenedores)
    - [Listar contenedores](#listar-contenedores)
    - [Creación de contenedores](#creación-de-contenedores)
      - [Operaciones con contenedores](#operaciones-con-contenedores)
      - [Destruir contenedores automáticamente o contenedores temporales](#destruir-contenedores-automáticamente-o-contenedores-temporales)
      - [Cambiar el Document Root de docker](#cambiar-el-document-root-de-docker)
      - [Sobreescribir el CMD de una imagen en el contenedor](#sobreescribir-el-cmd-de-una-imagen-en-el-contenedor)
      - [Ejemplos de contenedores](#ejemplos-de-contenedores)
        - [Crear un contenedor con MySQL](#crear-un-contenedor-con-mysql)
        - [Crear un contenedor de MongoDB](#crear-un-contenedor-de-mongodb)
        - [Crear un contenedor con Apache / Ngnix / Tomcat](#crear-un-contenedor-con-apache--ngnix--tomcat)
        - [Crear un contenedor con Postgress](#crear-un-contenedor-con-postgress)
        - [Crear un contenedor con Jenkins](#crear-un-contenedor-con-jenkins)
        - [Ejercicio](#ejercicio)
      - [Mapeo de puertos](#mapeo-de-puertos)
      - [Limitar recursos de un contenedor](#limitar-recursos-de-un-contenedor)
        - [Memoria](#memoria)
        - [CPU](#cpu)
    - [Docker commit](#docker-commit)
  - [Volúmenes](#volúmenes)
    - [Volumenes de Host - Caso práctico MySQL](#volumenes-de-host---caso-práctico-mysql)
    - [Volumenes anónimos](#volumenes-anónimos)
      - [VOLUME en un Dockerfile](#volume-en-un-dockerfile)
    - [Volúmenes nombrados](#volúmenes-nombrados)
    - [Eliminar dangling Volumes](#eliminar-dangling-volumes)
    - [Ejemplos de contenedores con datos persistentes](#ejemplos-de-contenedores-con-datos-persistentes)
  - [Redes en Docker](#redes-en-docker)
    - [Red por defecto de docker](#red-por-defecto-de-docker)
    - [Crear una red definida por el usuario](#crear-una-red-definida-por-el-usuario)
    - [Conectar contenedores de la misma red](#conectar-contenedores-de-la-misma-red)
    - [Conectar contenedores de distintas redes](#conectar-contenedores-de-distintas-redes)
    - [Eliminar redes](#eliminar-redes)
    - [Asignar una IP a un contenedor](#asignar-una-ip-a-un-contenedor)
    - [La red Host](#la-red-host)
    - [La red None](#la-red-none)
    - [Enlazar contenedores](#enlazar-contenedores)
      - [Enlace de 2 contenedores con apache2 y MySQL para su uso en producción.](#enlace-de-2-contenedores-con-apache2-y-mysql-para-su-uso-en-producción)
  - [Docker Compose](#docker-compose)
    - [Variables de entorno](#variables-de-entorno)
    - [Volúmenes](#volúmenes-1)
      - [Volumen nombrado](#volumen-nombrado)
      - [Volumen de host](#volumen-de-host)
    - [Redes](#redes)
    - [Construir imágenes en Docker Compose](#construir-imágenes-en-docker-compose)
    - [Sobreescribir el CMD de un contenedor con Docker Compose](#sobreescribir-el-cmd-de-un-contenedor-con-docker-compose)
    - [Limitar recursos para contenedores usando Docker Compose](#limitar-recursos-para-contenedores-usando-docker-compose)
    - [Política de reinicio de contenedores](#política-de-reinicio-de-contenedores)
    - [Personalizar el nombre de tu proyecto en Docker Compose](#personalizar-el-nombre-de-tu-proyecto-en-docker-compose)
    - [Ejemplos de Docker Compose](#ejemplos-de-docker-compose)
      - [Wordpress + MySQL](#wordpress--mysql)
      - [Drupal +  PostgreSQL](#drupal---postgresql)
      - [PrestaShop + MySQL](#prestashop--mysql)
      - [Joomla + MySQL](#joomla--mysql)
      - [Reaction Ecommerce - NodeJS + MongoDB](#reaction-ecommerce---nodejs--mongodb)
      - [Instalando Guacamole](#instalando-guacamole)
      - [Instala Zabbix con Compose](#instala-zabbix-con-compose)
    - [Ejercicios](#ejercicios)
      - [Ejercicio 1](#ejercicio-1)
      - [Ejercicio 2](#ejercicio-2)
  - [Docker Registry](#docker-registry)
    - [Subir imágenes al repositorio local](#subir-imágenes-al-repositorio-local)
    - [Descargar imágenes del repositorio local](#descargar-imágenes-del-repositorio-local)
    - [Compartir imágenes con la red](#compartir-imágenes-con-la-red)
    - [Crear un Docker Registry con autenticación](#crear-un-docker-registry-con-autenticación)
  - [Enlaces](#enlaces)

<!-- /TOC -->

## Imagenes y su contruccion (Dockerfile)
Una imagen de docker está formada por capas de solo lectura que se forman con las instrucciones FROM, RUN, CMD en los ficheros DockerFile.

El comando CMD de un DockerFile es la ejecución de un comando o aplicación. Cuando terminan todos los procesos de un contenedor, el contenedor se cerrará, por eso se suelen ejecutar servicios en contenedores.

### FROM

La imagen desde la que se construirá nuestra imagen.

### RUN

Cosas que podemos ejecutar por líneas de comando en la formación de la imagen.

### COPY / ADD <src> <dest>

* **`COPY`** Copia archivos desde nuestro sistema operativo hacia la imagen que estamos creando.

* **`ADD`** También copia pero permite que el origen sea una url.

En las buenas prácticas en Docker, se recomienda usar COPY siempre que se pueda y no sea necesario usar ADD.

### ENV

Sirver para agregar variables de entorno a nuestra imagen.

````dockerfile
ENV contenido prueba
RUN echo "$contenido" > /var/www/html/prueba.html
````
Hemos creado la variable de entorno llamada `contenido` con valor "prueba" y la almacenamos en un fichero.

### WORKDIR

Asignamos cual es el directorio de trabajo en un momento dado de la creación de la imagen.
````dockerfile
WORKDIR /var/www/html
````

### EXPOSE

"Exponemos" un puerto del host asignado para un puerto de la imagen.
````dockerfile
EXPOSE 8080
````
### LABEL

Son etiquetas / metadatos que dan información sobre la imagen creada.
````dockerfile
LABEL version=1.0
LABEL description=This is apache image
LABEL vendor=xxxxx
````

### USER

Le decimos quien esta ejecutando la tarea posterior

````dockerfile
FROM centos

LABEL version=1.0
LABEL description=This is apache image
LABEL vendor=xxxxx

RUN yum install httpd -y

RUN echo "$(shoami)" > /var/www/html/usar1.html
RUN useradd mauri
USER mauri
RUN echo "$(shoami)" > /tmp/usar2.html
USER root
RUN cp /tmp/user2.html /var/www/html/user2.html
CMD apachectl -DFOREGROUND
````
El primer echo será el usuario `root` que es el de por defecto, y el segundo echo es el usuario que hemos creado `mauri`.

### CMD

Es lo que mantiene vivo al contenedor, es decir, cuando finalice la ejecución del comando introducido en la sentencia CMD finalizará la ejecución del contenedor.

No sólo podemos ejecutar comandos del sistema operativo, sino también scripts. Por ejemplo:

`run.sh`
````bash
#!/bin/bash

echo "Iniciando container..."
apachectl -DFOREGROUND
````

y luego en nuestro DockerFile
````dockerfile
COPY run.sh /run.sh
CMD sh /run.sh
````

### dockerignore

Archivo oculto con nombre `.dockerignore` que sirve para ignorar cualquier cosa que esté en el directorio de trabajo actual que no queramos incluir en la imagen, ya que al construir la imagen, se envía lo que esté en el directorio de trabajo.

### resumen hasta ahora

````dockerfile
FROM nginx
RUN useradd mauri
COPY fruit /usr/share/nginx/html
ENV archivo docker
WORKDIR /usr/share/nginx/html
RUN echo "$archivo" > /usr/share/nginx/html/env.html
EXPOSE 90
LABEL version=1
USER mauri
RUN echo "Yo soy $(whoami)" > /tmp/yo.html
USER root
RUN cp /tmp/yo.html /usr/share/nginx/html/docker.html
VOLUME /var/log/nginx
CMD nginx -g 'daemon off;'
````

### Buenas prácticas en creación de DockerFile
* El servicio que se cree, se tiene que poder destruir con gran facilidad, que sea **efímero**.
* Debería haber un sólo servicio por contenedor, osea un sólo servicio instalado por imagen.
* Si vamos a tener archivos grandes o que no queremos que estén dentro del contexto 
* Hay que intentar reducir el número de capas de la imagen.
* Separar los argumentos en varias líneas para que sea más legible.
Esto crea 3 capas:
````dockerfile
RUN echo "1" >> /usr/share/nginx/html/test.txt
RUN echo "2" >> /usr/share/nginx/html/test.txt
RUN echo "2" >> /usr/share/nginx/html/test.txt
````
y habría que dejarlo así para que sea más legible:
````dockerfile
RUN \
    echo "1" >> /usr/share/nginx/html/test.txt && \
    echo "2" >> /usr/share/nginx/html/test.txt && \
    echo "2" >> /usr/share/nginx/html/test.txt
````
o incluso así usando una variable de entorno:
````dockerfile
ENV fich /usr/share/nginx/html/test.txt
RUN \
    echo "1" >> $fich && \
    echo "2" >> $fich && \
    echo "2" >> $fich
````

* Poner varios argumentos en una sola capa.
* No instalar paquetes innecesarios.
* Usar LABELS que den información de nuestra imagen, como pueden ser versiones y descripciones.


### Multi Stage Build

Es una funcionalidad en la que nos permiete untilizar varios **FROM** dentro de un mismo DockerFile. 

Por ejemplo, podemos querer contruir un fichero .jar desde una imagen MAVEN que necesita muchas dependencias y una vez que esté creado el .jar lo puedo copiar a una imagen JAVA sin necesidad de instalar todas las dependencias que se necesitaba para su compilación, con el ahorro considerable de espacio.

El último **FROM** será siempre el válido.

````dockerfile
FROM maven:3.5-alpine as builder
COPY app /app
RUN cd /app && mvn package

FROM openjdk:8-alpine
COPY --from=builder /app/target/my-app-1.0.SNAPSHOT.jar /opt/app.jar
CMD java -jar /opt/app.jar
````

Una vez que tenemos el Dockerfile creamos la imagen
````
docker build -t java .
````

### Tratamiento de imagenes

#### Eliminar imágenes `docker rmi`

`docker rmi` <id>|<image name>
````
docker rmi centos:prueba apache-centos:latest
````

#### Eliminar imágenes "huerfana"

Cada vez que creas una imagen con el mismo tag, pero que se ha modificado alguna de las capas de la imagen (comando RUN del DockerFile, por ejemplo), al ser las capas de **solo lectura** docker, creará otra imagen nueva y dejará huerfana la anterior por no poder usar el mismo tag en 2 imagenes. Para eliminar estas capas

````
<none>                         <none>              c6e31f151420        About an hour ago   349MB
````
Lo eliminaremos con el id de la imagen:
````
docker rmi c6e31
````

Visualizamos las imagenes "colgadas" o "huerfanas"
````
docker images -f dangling=true
````

Con la opción `-q` le decimos q sólo muestre los ids, y con eso y con el comando `xargs` de linux podemos decirle que nos borre todas las imágnes con esos ids.

````
docker images -f dangling=true -q | xargs docker rmi
````

#### Contruir una imagen con otro fichero distinto de DockerFile
Si en vez de usar el típico fichero `DockerFile` usamos otro nombre como `my-dockerfile`:
````
docker build -t test -f my-dockerfile .
````

### Creación de imágenes de ejemplo

#### Apache + PHP + SSL

````dockerfile
FROM centos:7
RUN yum -y install httpd php php-cli php-common
RUN echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php
COPY startbootstrap-sb-admin-2-gh-pages /var/www/html
CMD apachectl -DFOREGROUND
````
Creamos el archivo `.dockerignore` por seguir las buenas prácticas.
`.dockerignore`
````
gh-pages.zip
startbootstrap-sb-admin-2-gh-pages
````

Ahora creamos un certificado autofirmado:
````bash
openssl req -x509 -nodes -days 365 -newkey rsa:2018 -keyout mysitename.key -out mysitename.crt
````

creamos el archivo `ssl.conf`
````conf
<VirtualHost *:443>
    DocumentRoot /var/www/html
    ServerName localhot
    SSLEngine on
    SSLCertificateFile /docker.crt
    SSLCertificateKeyFile /docker.key
    # SSLCertificateChainFile /ruta/a/DigiCertCA.crt
</VirtualHost> 
````

modificamos nuestro DockerFile:
````dockerfile
FROM centos:7
RUN yum -y install httpd \
    php \
    php-cli \
    php-common \
    mod_ssl \
    openssl
RUN echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php
COPY startbootstrap-sb-admin-2-gh-pages /var/www/html
COPY ssl.conf /etc/httd/conf.d/default.conf
COPY docker.crt /docker.crt
COPY docker.key /docker.key
EXPOSE 443
CMD apachectl -DFOREGROUND
````
Construimos la imagen con:
````
docker build -t apache:ssl-ok .
````

Y luego creamos el contenedor
````
docker run -d -p 443:443 apache:ssl-ok
````

#### Creación de una imagen con Nginx y PHP-FPM

````dockerfile
FROM centos:7
COPY ./conf/nginx.repo /etc/yum.repos.d/nginx.repo
RUN yum -y install nginx --enablerepo=nginx && \
    yum -y install https://centos7.iuscommunity.org/ius-release.rpm && \
    yum -y install \
        php71u-fpm \
        php71u-cli \
        php71u-mysqlnd \
        php71u-soap \
        php71u-xml \
        php71u-zip \
        php71u-json \
        php71u-mcrypt \
        php71u-mbstring \
        php71u-gd \
        -- enablerepo=ius && yum clean all
EXPOSE 443
VOLUME /var/www/html /var/log/php-fpm /var/lib/php-fpm
COPY ./vonf/nginx.conf /etc/nginx/conf.d/nginx.conf
COPY ./bin/start.sh /start.sh
CMD /start.sh
````

#### Apache + php 7
````dockerfile
FROM debian:9
RUN     apt-get update && \
        apt-get -y install apache2 php7.0 php7.0-cli php7.0-common
RUN echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php
RUN a2enmod php7.0
CMD apachectl -DFOREGROUND
````
Creamos la imagen con ese Dockerfile
````
docker build -t apachephp .
````
Luego creamos un contenedor usando esa imagen
````
docker run -d p 81:80 --name apachephp apachephp
````

## Contenedores

El contenedor es una instancia de una imagen.

Los contenedores son temporales, por lo que si queremos que los cambios sean permanentes, debemos definirlo en el Dockerfile de la imagen. Las capas de las imágenes son de solo lectura pero **la capa de contenedor es de lectura y escritura**. Podemos crear todos los contenedores que queramos partiendo de una sola imagen.

### Listar contenedores

* Con `docker ps` listamos los contenedores en funcionamiento.
* Con `docker ps -a` listamos todos los contenedores, incluso lo que están parados y no funcionando.

### Creación de contenedores

#### Operaciones con contenedores
El requisito principal es basarnos en una imagen, ya que un contenedor es una instancia de una imagen.

* **`docker pull jenkins`**: Descargamos la imagen de Jenkins.
* `docker run -d`: Creación y ejecución de un contenedor en segundo plano sin una consola interactiva.

* **Renombrar contenedor**: `docker rename <old name> <new name>`
* **Detener contenedor**: `docker stop <name|id>`
* **Arranchar contenedor**: `docker start <name|id>`
* **Reiniciar contenedor**: `docker restart <name|id>`
* **Entrar al contenedor por una shell**: `docker exec -ti <name|id> <container name> <shell>` por ejemplo `docker exec -ti jenkins bash`

Si no es especifica **hostname** el hostname por defecto será el id de contenedor.

* **Entrar en un contenedor con un usuario específico**: `docker exec -u root -ti jenkins bash`. Aqui entraríamos como usuario `root`
* **Eliminar contenedores**: `docker rm <nombre|id>`
* **Eliminar todos contenedores**: Con `docker ps -q` visualizamos las ids de los contenedores y asi con el comando `xarg` de linux al que le pasamos como parámetro ese resultado: `docker ps -q | xargs docker rm -f` o también `docker rm -fv $(docker ps -aq)`
* **Crear una variable de entorno al crear el contenedor**: `docker run -dti -e "prueba1=4321" --name jenkinsenv jenkins`
* **Ver la memoria consumida por un contenedor**: `docker stats my-mongo`
* **Ver la salida de un contenedor**: `docker log mongo` y si lo ponemos con el parámtro `-f` (follow) se quedará mostrando todo lo que salga por consola `docker log -f mongo`
* **Copiar ficheros a o desde un contenedor**: 
  * Desde el host a un contenedor: `docker cp ./index.html apache:/usr/local/apache2/htdocs/index.html`
  * Desde el contenedor al host: `docker cp apache:/var/log/dpkg.log .`

#### Destruir contenedores automáticamente o contenedores temporales
Una vez que se termina la ejecución y salgamos del contenedor se destruirá.
````bash
docker run --rm -ti centos bash
````

#### Cambiar el Document Root de docker
Es toda la jerarquía de directorios que crea docker para guardar las imágnes, contenedores, redes, etc. Lo podemos ver con el comando:
````
$ docker info | grep -i root
WARNING: No swap limit support
Docker Root Dir: /var/lib/docker
````
````
# ls -la /var/lib/docker/
total 56
drwx--x--x 12 root root  4096 sep 18 13:23 .
drwxr-xr-x 26 root root  4096 oct 20  2017 ..
drwx------  2 root root  4096 oct  5  2017 builder
drwx------  7 root root  4096 sep 19 11:25 containers
drwx------  3 root root  4096 oct  5  2017 image
drwxr-x---  3 root root  4096 oct  5  2017 network
drwx------ 65 root root 12288 sep 19 11:25 overlay2
drwx------  4 root root  4096 oct  5  2017 plugins
drwx------  2 root root  4096 oct  5  2017 swarm
drwx------  2 root root  4096 sep 18 13:23 tmp
drwx------  2 root root  4096 oct  5  2017 trust
drwx------ 18 root root  4096 sep 18 12:02 volumes
````
Editamos el fichero de configuración `/lib/systemd/system/docker.service`. Buscamos la línea donde dice **ExecStart=/usr/bin/dockerd** y la modificamos de esta manera **ExecStart=/usr/bin/dockerd --data-root /mnt/datos1/docker**.

Luego avisamos que hemos hecho un cambio en la configuración a **Systemd** y reiniciamos el servicio
````
systemctl daemon-reload
systemctl restart docker
````

#### Sobreescribir el CMD de una imagen en el contenedor
Creamos un contenedor normal
````bash
$ docker run -dti centos
$ docker ps
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS               NAMES
8a39ad1a5e7e        centos              "/bin/bash"         5 seconds ago       Up 4 seconds                            vigilant_euler
````

Creamos ahora un contenedor sustituyendo el CMD de la imagen
````bash
$ docker run -dti centos echo "hola mundo"
$ docker ps -a
CONTAINER ID        IMAGE                                 COMMAND                  CREATED             STATUS                      PORTS               NAMES
c8141e6837bd        centos                                "echo 'hola mundo'"      29 seconds ago      Exited (0) 28 seconds ago                       pedantic_almeida
8a39ad1a5e7e        centos                                "/bin/bash"              58 seconds ago      Up 57 seconds                                   vigilant_euler
````
#### Ejemplos de contenedores

##### Crear un contenedor con MySQL

Que creará un usuario y una contraseña asignada a una base de datos nueva:
`docker run -d -p 3333:3306 --name my-db2 -e "MYSQL_ROOT_PASSWORD=12345678" -e "MYSQL_DATABASE=docker-db" -e "MYSQL_USER=docker-user" -e "MYSQL_PASSWORD=87654321" mysql:5.7`

##### Crear un contenedor de MongoDB
`docker run -d --name my-mongo -p 27017:27017 mongo`

##### Crear un contenedor con Apache / Ngnix / Tomcat

**Ngnix**
````
docker run -d -p 8888/:80 --name ngnix ngnix
````
**Apache**
````
docker run -d -p 9999/:80 --name apache apache
````
**Tomcat**
````
docker run -d -p 7777/:8080 --name tomcat tomcat:9.0.8-jre8-alpine
````

##### Crear un contenedor con Postgress
````
docker run -d --name postgress -e "POSTGRES_PASSWORD=12345678" -e "POSTGRESS_USER=docker-user" -e "POSTGRES_DB=docker-db" -p 5432:5432 postgres
````

##### Crear un contenedor con Jenkins
````
docker run -d --name jenkins -p 7070:8080 jenkins
````

##### Ejercicio

En donde trabajas, solicitan los siguientes contendores con las siguientes características:

Un contenedor con la imagen de Apache + php creada en la anterior solicitud con:
* 50Mb límites de RAM
* Solo podrá acceder a la CPU 0
* Debe tener dos variables de entorno:
    * ENV = dev
    * VIRTUALIZATION = docker
* El webserver debe ser accesible vía puerto 5555 en el navegador
````
docker run -d -m "50mb" --cpuset-cpus 0 --name apachephpmemcpu -e "ENV=dev" -e "VIRTUALIZATION=docker" -p 5555:80 apachephp
````

- Un contenedor con la imagen de Apache + php creada en la anterior solicitud con:

* 100Mb límites de RAM
* Podrá acceder a la cpu 0 y 1
* Debe tener tres variables de entorno:
    * ENV = stg
    * VIRTUALIZATION = docker
    * TYPE = container
* El webserver debe ser accesible vía puerto 8181 en el navegador
````
 docker run -d -m "100mb" --cpuset-cpus 0-1 --name apachephpmemcpu -e "ENV=stg" -e "VIRTUALIZATION=docker" -e "TYPE=container" -p 8181:80 apachephp
````

#### Mapeo de puertos

Si ejecutamos:
````
$ docker run -d jenkins
````
````
theasker@vps462589:~/docker-images$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                 NAMES
a9177342bf59        jenkins             "/bin/tini -- /usr..."   14 seconds ago      Up 13 seconds       8080/tcp, 50000/tcp   elegant_jackson
````

Con el comando docker ps vemos los contenedores en ejecución y vemos que en el apartado PORTS están expuestos los puertos 8080 y 50000, pero como en nuestro comando de ejecución `docker run -d jenkins` no hemos mapeado los puertos, es decir, que no le hemos dicho a docker en que puertos de nuestra maquina fisica queremos que se mapeen los puertos de nuestro contenedor, no podremos acceder a esos puertos. Para eso tenemos la opción `-p` de docker run en la que le diremos `-p <puerto host>: <puerto contenedor>`:
````
docker run -d -p 9090:8080 jenkins
````
````
$ docker ps
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                               NAMES
2e82c8958791        jenkins             "/bin/tini -- /usr..."   5 seconds ago       Up 4 seconds        0.0.0.0:9090->8080/tcp, 50000/tcp   angry_banach
````
`0.0.0.0:9090->8080/tcp`: Todas las interfaces de nuestra máquina en el puerto 9090 están siendo mapeadas al puerto 8080 de nuestro contenedor.

#### Limitar recursos de un contenedor

Con el comando `docker stats <nombre contenedor>` vemos los recursos que está consumiendo el contenedor.

##### Memoria
Con `docker run -d -m "500mb" --name mongo` (mb, gb).

##### CPU
El número de CPUs va dede 0 que es 1 CPU, por lo que para limitar el número de CPUs a usar entre una y dos, sería:
````
docker run -d  --name mongo mongo
````

### Docker commit

Sirve para crear una imagen a partir de un contenedor. **Si en ese contenedor se han hecho cambios en un volumen, estos no se guardarán**, ya que se supone que ese volumen se monta en el host y se guardan alli.
````
docker commit <contenedor origen> <nombre imagen destino>
````

## Volúmenes

Nos permite guardar datos de un contenedor de manera persistente en el host local. Como los contenedores son temporales, si se eliminan, se elimina también los datos, pero al usar volúmenes, estos datos persisten aunque se borre el contenedor. 

Hay 3 tipos de volúmenes:
* **Host**: Se almacenan en una carpeta dentro de nuestro sistema de ficheros que nosotros definimos.
* **Anonymus**: No definimos una carpeta pero docker asigna un directorio random donde persiste la información.
* **Named Volumes**: No son carpetas nuestras y si que tienen un nombre asignado y las administra docker.

### Volumenes de Host - Caso práctico MySQL

Queremos que las bases de datos de MySQL no se pierdan si destruimos el contenedor. Para eso creamos una carpeta en nuestro sistema de archivos donde se guardarán las bases de datos.
````
mkdir /opt/mysql
````
Ahora creamos nuestro contenedor
````
docker run -d --name db -p 3306:3306 -e "MYSQL_ROOT_PASSWORD=12345678" -v /opt/mysql/:/var/lib/mysql mysql:5.7
````

### Volumenes anónimos
Ahora creamos nuestro contenedor, casi igual que el comando anterior, pero sólo definire el volumen del contenedor y docker le asignara una carpeta al azar dentro de mi máquina
````
docker run -d --name db -p 3306:3306 -e "MYSQL_ROOT_PASSWORD=12345678" -v /var/lib/mysql mysql:5.7
````

Si hacemos un `docker info` veremos la carpeta root de docker, que está en `/var/lib/docker`.
````
$ docker info | grep -i root
Docker Root Dir: /var/lib/docker
````
Dentro del docker root hay una carpeta llamada **volumes** en `/var/lib/docker/volumes` en donde creará una carpeta aleatoria, que podemos ver cual es ejecutando `docker inspect db` y mirando en el apartado "Mounts".

No es recomendable usar este tipo de volúmenes, ya que cuando elimino el volumen con la opcion `-v` también se eliminará el volumen:
````
docker rm -fv db
````
Si se elimina el contendor **sin la opción `-v`**, es decir, `docker rm -f db`, el volumen no se eliminará.

#### VOLUME en un Dockerfile

Si ponemos dentro de un Dockerfile la instrucción `VOLUME /opt/`, se creará un **volumen anónimo** en el host enlazando a la ruta del contenedor `/opt`. Que podemos ver listando los volumenes con `docker volume ls`

### Volúmenes nombrados
Es como la **unión de un volumen de host y un volumen anónimo**, es decir, es un volumen creado independiente de un contenedor:
````
$ docker volume create mysql-data
mysql-data
$ docker volume ls
DRIVER              VOLUME NAME
local               mysql-data
````

Ahora ya sólo nos queda asignar ese volumen a un contenedor:
````
docker run -d --name mysql -v mysql-data:/var/lib/mysql -p 3306:3306 -e "MYSQL_ROOT_PASSWORD=12345678" -e "MYSQL_DATABASE=docker-db"  mysql:5.7
````

La diferencia con los volúmenes anónimos es que si luego eliminamos el contenedor con la opción `-v` no se elimina el volumen:
````
docker rm -fv mysql
````

### Eliminar dangling Volumes
````docker volume ls -f dangling=true -q | xargs docker volume rm````

### Ejemplos de contenedores con datos persistentes

* **Persistiendo bases de datos en MongoDB**:
````
docker run -d -p 27017:27017 -v /opt/mongo/:/data/db mongo
````
Con este volumen, la próxima vez que creemos un nuevo contenedor, tendremos las bases de datos guardadas.

* **Persistiendo configuración y datos en Jenkins**: 
````
docker run -d -p 8080:8080 --name jenkins -v /opt/jenkins/:/var/jenkins_home jenkins
docker exec jenkins bash -c "cat /var/jenkins_home/secrets/initicalAdminPassword"
````
Y el resultado lo pegamos en la pantalla que lo pide en la instalación.

Con estos datos, la próxima vez que creemos un nuevo contenedor, que apunte al mismo volumen, Jenkins estará ya instalado y con todos los datos y tareas que ya tuvieramos.

* **Persistiendo configuración y datos en Jenkins**: 
````
docker run -d --name nginx -p 80:80 -v /opt/logs_nginx/:/var/log/nginx/ nginx
````
* **ejercicio**:

- Un contenedor con la imagen de Apache + php creada en la anterior solicitud con:
  * 50Mb límites de RAM
  * Solo podrá acceder a la CPU 0
   * Debe tener dos variables de entorno:
      * ENV = dev
      * VIRTUALIZATION = docker
  * El webserver debe ser accesible vía puerto 5555 en el navegador
  * En /opt/source1 (Debes crear el directorio en tu máquina local) debe persistir el código que se incluya en el webserver. En este caso, para pruebas, utilizarás un phpinfo que debe sobrevivir a la eliminación del contenedor.

````
docker run -d -e ENV=dev -e VIRTUALIZATION=docker -p 5555:80 -v /home/theasker/docker-images/volumes/source1/:/var/www/html/ apachephp

````

## Redes en Docker

Cuando instalamos Docker, se crea una interfaz de red virtual con su propia ip, en este caso `172.17.0.1/16`:

    # ip a | grep docker
    3: docker0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc noqueue state UP group default
        inet 172.17.0.1/16 scope global docker0
    25: vethb962238@if24: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc noqueue master docker0 state UP group default


Si creamos un contenedor y lo inspeccionamos con `docker inspect <id|nombre>`:
````
"Gateway": "172.17.0.1",
"IPAddress": "172.17.0.3",
````

Tomaremos como premisa que la IP de nuestro Docker Host es `192.168.100.2` 

Al exponer un puerto en un contenedor, por defecto, este utiliza todas las interfaces de nuestra máquina. Veámos un ejemplo:

    [ricardo@localhost ~]$ docker run -d -p 8080:80 nginx
    196a13fe6198e1a3e8d55aedda90882f6abd80f4cdf41b2f29219a9632e5e3a1
    [ricardo@localhost ~]$ docker ps -l
    CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                  NAMES
    196a13fe6198        nginx               "nginx -g 'daemon of…"   5 seconds ago       Up 2 seconds        0.0.0.0:8080->80/tcp   frosty_jenning

Si observamos la parte de ports, veremos un 0.0.0.0 . Esto significa que podremos acceder al servicio en el puerto 8080  utilizando localhost:8080 , o 127.0.0.1:8080 , 192.168.100.2:8080 .

Si quisiéramos que sea accesible solamente vía localhost  y no vía 192.168.100.2 , entonces haríamos lo siguiente:

    [ricardo@localhost ~]$ docker run -d -p 127.0.0.1:8081:80 nginx
    1d7e82ff15da55b8c774baae56827aef12d59ab848a5f5fb7f883d1f6d1ee6e1
    [ricardo@localhost ~]$ docker ps -l
    CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                    NAMES
    1d7e82ff15da        nginx               "nginx -g 'daemon of…"   3 seconds ago       Up 1 second         127.0.0.1:8081->80/tcp   musing_tesla

Como observamos, ahora en vez de 0.0.0.0  vemos un 127.0.0.1 , lo que indica que nuestro servicio es accesible sólo vía localhost  y no usando 192.168.100.2 

### Red por defecto de docker

Para ver la red por defecto de docker:
````
$ docker network ls
NETWORK ID          NAME                DRIVER              SCOPE
e0f4e1b1d817        bridge              bridge              local
7f2c0cb5a8b7        host                host                local
64f49e30aa86        none                null                local
````

Si inspeccionamos la red **bridge**:
````json
$ docker inspect bridge
[
    {
        "Name": "bridge",
        "Id": "e0f4e1b1d8175718dcca9ea0596f86a2ec7fbb413e0f968cab5bdbe68029c91a",
        "Created": "2018-09-18T13:23:35.21909352+02:00",
        "Scope": "local",
        "Driver": "bridge",
        "EnableIPv6": false,
        "IPAM": {
            "Driver": "default",
            "Options": null,
            "Config": [
                {
                    "Subnet": "172.17.0.0/16",
                    "Gateway": "172.17.0.1"
                }
            ]
        },
        "Internal": false,
        "Attachable": false,
        "Ingress": false,
        "ConfigFrom": {
            "Network": ""
        },
        "ConfigOnly": false,
        "Containers": {
            "a15fac664663b8e82acdeda49a9d5cda38634eef79ed5c4b110df609e53dd21a": {
                "Name": "confident_cray",
                "EndpointID": "035984b4b666b307a7aeaad134c91056b0c61bedfe8745d3a879dc33b35d9ab3",
                "MacAddress": "02:42:ac:11:00:03",
                "IPv4Address": "172.17.0.3/16",
                "IPv6Address": ""
            }
        },
        "Options": {
            "com.docker.network.bridge.default_bridge": "true",
            "com.docker.network.bridge.enable_icc": "true",
            "com.docker.network.bridge.enable_ip_masquerade": "true",
            "com.docker.network.bridge.host_binding_ipv4": "0.0.0.0",
            "com.docker.network.bridge.name": "docker0",
            "com.docker.network.driver.mtu": "1500"
        },
        "Labels": {}
    }
]
````

Veremos que el **Gateway** es el que sale en el interfaz que hemos visto `172.17.0.1` y la ip asignada al contenedor está en la subred que salía en la interfaz de red virtual `172.17.0.3`.

Si creamos 2 contenedores dentro de la misma red, podemos hacer un ping  de uno a otro:
````
$ docker run -dti centos
ea3a7da8f4479f0e40eb9467167b4491cc45664ebc93b8e07d8f10abf52b5703
theasker@vps462589:~$ docker run -dti centos
6857747bd1534a5b43ffabc57ccd33f42c26603033a49d6ffa939022327868f6
$ docker ps -a
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS               NAMES
6857747bd153        centos              "/bin/bash"              6 seconds ago       Up 5 seconds                            focused_bassi
ea3a7da8f447        centos              "/bin/bash"              7 seconds ago       Up 6 seconds                            sleepy_einstein
$ docker inspect focused_bassi| grep IPAddress
"IPAddress": "172.17.0.4",
````
````
$ docker inspect sleepy_einstein| grep IPAddress
"IPAddress": "172.17.0.2",
````
````
$ docker exec focused_bassi bash -c "ping 172.17.0.2"
PING 172.17.0.2 (172.17.0.2) 56(84) bytes of data.
64 bytes from 172.17.0.2: icmp_seq=1 ttl=64 time=0.279 ms
64 bytes from 172.17.0.2: icmp_seq=2 ttl=64 time=0.325 ms
64 bytes from 172.17.0.2: icmp_seq=3 ttl=64 time=0.064 ms
````
**En la red bridge se pueden ver los contedores por IP pero no por nombre**.

### Crear una red definida por el usuario

Para crear una red con **driver bridge**, que es el driver por defecto, que es una red virtual donde podemos agregar contenedores.
````
$ docker network

Usage:  docker network COMMAND

Manage networks

Options:
      --help   Print usage

Commands:
  connect     Connect a container to a network
  create      Create a network
  disconnect  Disconnect a container from a network
  inspect     Display detailed information on one or more networks
  ls          List networks
  prune       Remove all unused networks
  rm          Remove one or more networks

Run 'docker network COMMAND --help' for more information on a command.
````

Para crear una nueva red:
````
$ docker network create --help

Usage:  docker network create [OPTIONS] NETWORK

Create a network

Options:
      --attachable             Enable manual container attachment
      --aux-address map        Auxiliary IPv4 or IPv6 addresses used by Network
                               driver (default map[])
  -d, --driver string          Driver to manage the Network (default "bridge")
      --gateway stringSlice    IPv4 or IPv6 Gateway for the master subnet
      --help                   Print usage
      --ingress                Create swarm routing-mesh network
      --internal               Restrict external access to the network
      --ip-range stringSlice   Allocate container ip from a sub-range
      --ipam-driver string     IP Address Management Driver (default "default")
      --ipam-opt map           Set IPAM driver specific options (default map[])
      --ipv6                   Enable IPv6 networking
      --label list             Set metadata on a network
  -o, --opt map                Set driver specific options (default map[])
      --subnet stringSlice     Subnet in CIDR format that represents a network
                               segment
````
````docker network create test-network````

Para poder definir la subred en la q operará y el gateway en una red diferente a la de docker:
 ````docker network create -d bridge --subnet 172.124.10.0/24 --gateway 172.124.10.1 docker-test-network````

Y vemos que se ha creado
````
$ docker network ls | grep test
6981a4feb7af        docker-test-network   bridge              local
````

Inspeccionamos la red creada:
````json
$ docker network inspect docker-test-network
[
    {
        "Name": "docker-test-network",
        "Id": "6981a4feb7af3bcb32505ad4906f5fd41405ca602760882a994ced727f59af50",
        "Created": "2018-09-27T09:50:47.384951321+02:00",
        "Scope": "local",
        "Driver": "bridge",
        "EnableIPv6": false,
        "IPAM": {
            "Driver": "default",
            "Options": {},
            "Config": [
                {
                    "Subnet": "172.124.10.0/24",
                    "Gateway": "172.124.10.1"
                }
            ]
        },
        "Internal": false,
        "Attachable": false,
        "Ingress": false,
        "ConfigFrom": {
            "Network": ""
        },
        "ConfigOnly": false,
        "Containers": {},
        "Options": {},
        "Labels": {}
    }
]
````

Y para agregar un contenedor a una red específica que hemos creado:
````
docker run --network docker-test-network -d --name test3 -ti centos
````

e inspeccionando el contendor, vemos que se ha agregado a la red docker-test-network y que la ip asignada, así como el gateway es el correcto de la red que hemos configurado.
````json
 "Networks": {
    "docker-test-network": {
        "IPAMConfig": null,
        "Links": null,
        "Aliases": [
            "c487a17122ac"
        ],
        "NetworkID": "6981a4feb7af3bcb32505ad4906f5fd41405ca602760882a994ced727f59af50",
        "EndpointID": "4205a956c12aa9f872b6772c204fdeac4c77ffe9519feb7a8abbe0a8334662bb",
        "Gateway": "172.124.10.1",
        "IPAddress": "172.124.10.2",
        "IPPrefixLen": 24,
        "IPv6Gateway": "",
        "GlobalIPv6Address": "",
        "GlobalIPv6PrefixLen": 0,
        "MacAddress": "02:42:ac:7c:0a:02",
        "DriverOpts": null
    }
````

### Conectar contenedores de la misma red

Creamos 2 contenedores de la red que hemos creado:
````
$ docker run -d -it --network docker-test-network --name cont1 centos
b3b0821f3504f2eee21ffe7d25516c5d2f23a604bdf9da7932e90cafc4083e63
$ docker run -d -it --network docker-test-network --name cont2 centos
ec5a78d51685268a970994287b1292a3b887261959390e542ee01463965881a2
````

Si inspeccionamos los contenedores, vemos que están en el rango que le dijimos la crear la red:
````
$ docker inspect cont1 | grep -i "ipaddress"
    "IPAddress": "172.124.10.2",
$ docker inspect cont2 | grep -i "ipaddress"
    "IPAddress": "172.124.10.3",
````

Por lo que si hacemos un ping de uno al otro funcionará
````
$ docker exec cont1 bash -c "ping -c2 172.124.10.3"
PING 172.124.10.3 (172.124.10.3) 56(84) bytes of data.
64 bytes from 172.124.10.3: icmp_seq=1 ttl=64 time=0.292 ms
64 bytes from 172.124.10.3: icmp_seq=2 ttl=64 time=0.103 ms
````

En la red de docker, no podemos ver ni hacer ping a las redes por su nombre, pero en las redes definidas por el usuario, si:
````
$ docker exec cont1 bash -c "ping -c2 cont2"
PING cont2 (172.124.10.3) 56(84) bytes of data.
64 bytes from cont2.docker-test-network (172.124.10.3): icmp_seq=1 ttl=64 time=0.162 ms
64 bytes from cont2.docker-test-network (172.124.10.3): icmp_seq=2 ttl=64 time=0.075 ms
````

### Conectar contenedores de distintas redes

Tenemos 2 redes en diferentes subred:
````
$ docker network inspect test | grep -i subnet
                    "Subnet": "172.18.0.0/16",
$ docker network inspect docker-test-network | grep -i subnet
                    "Subnet": "172.124.10.0/24",
````

Creamos 2 contenedores cada uno en una red:
````
$ docker run -dit --network docker-test-network --name test1 centos
d686820a8cfc13b7efb176935704a1b0b5e8975005249c53bdd1f8c4141109af
$ docker run -dit --network test --name test2 centos
6f784f9cd4926e3752d1c07dd42e62705dfa07c6288c5be8bc17bf2402a5e77c
````

Y vemos que no se ven un contenedor con el otro:
````
$ docker exec test1 bash -c "ping -c3 test2"
ping: test2: Name or service not known
````

Para poder conectar 2 redes con distintas subred podemos usar el comando `docker network connect`. Lo que hace este comando es conectar un contenedor de otra subred a la red a la que queremos conectar.
````
$ docker network connect --help

Usage:  docker network connect [OPTIONS] NETWORK CONTAINER

Connect a container to a network

Options:
      --alias stringSlice           Add network-scoped alias for the container
      --help                        Print usage
      --ip string                   IPv4 address (e.g., 172.30.100.104)
      --ip6 string                  IPv6 address (e.g., 2001:db8::33)
      --link list                   Add link to another container
      --link-local-ip stringSlice   Add a link-local address for the container
````

En nuestro ejemplo sería:
````
docker network connect docker-test-network test2
````

Si ahora inspeccionamos el contenedor test2, veremos que se la ha agregado la nueva red, por lo que ahora pertenecerá a 2 redes:
````
$ docker inspect test2
 "Networks": {
    "docker-test-network": {
        "IPAMConfig": {},
        "Links": null,
        "Aliases": [
            "6f784f9cd492"
        ],
        "NetworkID": "6981a4feb7af3bcb32505ad4906f5fd41405ca602760882a994ced727f59af50",
        "EndpointID": "ca282c6ddcd91dc4f602715568340b3fc086d2b786233ad3b8a566f1a2669994",
        "Gateway": "172.124.10.1",
        "IPAddress": "172.124.10.3",
        "IPPrefixLen": 24,
        "IPv6Gateway": "",
        "GlobalIPv6Address": "",
        "GlobalIPv6PrefixLen": 0,
        "MacAddress": "02:42:ac:7c:0a:03",
        "DriverOpts": null
    },
    "test": {
        "IPAMConfig": null,
        "Links": null,
        "Aliases": [
            "6f784f9cd492"
        ],
        "NetworkID": "bd134ca63e3b624e2f182674ab95132f9ecd5aa8e7d6de609f24e9c85b511cf6",
        "EndpointID": "ff29b9915c7ca0f9891a7076125554c3501ad275e785e885905b8d7371e22d59",
        "Gateway": "172.18.0.1",
        "IPAddress": "172.18.0.2",
        "IPPrefixLen": 16,
        "IPv6Gateway": "",
        "GlobalIPv6Address": "",
        "GlobalIPv6PrefixLen": 0,
        "MacAddress": "02:42:ac:12:00:02",
        "DriverOpts": null
    }
}
````

jY ahora ya funciona la conexión:
````
docker exec test1 bash -c "ping test2"
PING test2 (172.124.10.3) 56(84) bytes of data.
64 bytes from test2.docker-test-network (172.124.10.3): icmp_seq=1 ttl=64 time=0.053 ms
````

Para volver a desconectarlo y dejarlo en su propia red:
````
docker network disconnect docker-test-network test2
````

### Eliminar redes

No podremos eliminar redes si hay algún contenedor conectado a esa red. Una vez eliminados los contenedores que estén conectados a la red, lo eliminaremos:
````
docker network rm docker-test-network
````

### Asignar una IP a un contenedor

Creamos una nueva red:
````
$ docker network create --subnet 172.128.10.0/24 --gateway 172.128.10.1 -d bridge my-net
0d68a4089b3e2953c4a3a86ffa0eef4d8f4be9e662f43e3496c12c978807540e
````

Si queresmos un contenedor en la ip 172.128.10.50, haremos:
````
docker run --network my-net --ip 172.128.10.50 -d --name nginx -ti centos
6faf0c7fed90cb137db6d014b2281350fdbf952a2efecebb904f884152dadd8b
````

Y podemos ver que se ha creado con esa ip:
````
$ docker inspect nginx | grep -i ipadd
            "SecondaryIPAddresses": null,
            "IPAddress": "",
                    "IPAddress": "172.128.10.50",
````

Para que funcione esto, tenemos que habe creado **una red con una subred y un gateway definidas**.

### La red Host

Es la red de nuestra máquina la ip es `145.239.196.19`:
````
$ ip a | grep ens
2: ens3: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc pfifo_fast state UP group default qlen 1000
    inet 145.239.196.19/32 brd 145.239.196.19 scope global ens3
````

Creamos un contenedor en la red **Host** con `--network host` y entramos para ver la ip y vemos que tiene ahora la misma que el host `145.239.196.19` (tendremos que instalar una utilidad de centos llamada **net-tools** para poder ejecutar el comando **ifconfig**:
````
theasker@vps462589:~$ docker run -it --name centos --network host centos bash
[root@vps462589 /]# ifconfig
...
ens3: flags=4163<UP,BROADCAST,RUNNING,MULTICAST>  mtu 1500
        inet 145.239.196.19  netmask 255.255.255.255  broadcast 145.239.196.19
        inet6 fe80::f816:3eff:fe4f:83a0  prefixlen 64  scopeid 0x20<link>
        ether fa:16:3e:4f:83:a0  txqueuelen 1000  (Ethernet)
        RX packets 977125  bytes 234510022 (223.6 MiB)
        RX errors 0  dropped 0  overruns 0  frame 0
        TX packets 892585  bytes 122746776 (117.0 MiB)
        TX errors 0  dropped 0 overruns 0  carrier 0  collisions 0
...
````
Adquiere todas las propiedades de la máquina host, incluso el **hostname**:
````
theasker@vps462589:~$ hostname
vps462589
theasker@vps462589:~$ docker exec -it centos bash -c "hostname"
vps462589
````



### La red None

Es para que los contenedores a los contenedores que pongamos en la red None no tenga salida a red.

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

	root@web:/# ping db
	PING db (172.17.0.2): 56 data bytes
	64 bytes from 172.17.0.2: icmp_seq=0 ttl=64 time=0.128 ms

Ahora nos podemos conectar a MySQL desde el contenedor web

	root@web:~# mysql -h db -uroot
	...
	mysql>

## Docker Compose

Es una aplicación que nos ayuda a crear aplicaciones multicontenedor.

Se confecciona en archivos **.yml** con 4 grandes partes 2 obligatorias y 2 opcionales:
* **`version:`** (obligatorio) Siempre ponemos la última versión que haya, consultando en la documentación de docker-compose.
* **`services:`** (obligatorio)
* **`volumes:`** (opcional)
* **`networks:`** (opcional)

Vamos a crear por ejemplo un contenedor con el servidor web nginx, exponiendo el puerto, como crearíamos con el comando:
````
docker run -d --name nginx -p 8080:80 nginx
````

La opción `-d` de **detached** es por defecto. Creamos el fichero `docker-compose.yml`
````yml
version: '3'
services:
  web:
    container_name: nginx1
    ports:
      - "8080:80"
    image: nginx
````

Para "levantar" esta configuración ejecutaremos:
* `docker-compose up` si queremos que se quede enviando la salida por la pantalla.
* `docker-compose up -d` (detached) si queremos separar la ejecución del contenedor de la consola, para poder ejecutar otros comandos.
````
$ docker-compose up -d
Creating network "dockercompose_default" with the default driver
Creating nginx1
````
Vemos que automáticamente docker crea una red para ese contenedor llamada **dockercompose_default**.

Para elminar el contenedor y la red creada asociada a ese contenedor creado con docker-compose tenemos que ejecutar:
````
$ docker-compose down
Stopping nginx1 ... done
Removing nginx1 ... done
Removing network dockercompose_default
````

Y vemos como elimina lo que hemos creado.

### Variables de entorno

Creo un fichero `docker-compose-mysql.yml` para usar la imagen de MySQL
````yml
version: '2'
services:
  db:
    image: mysql:5.7
    container_name: mysql
    ports:
      - "3333:3306"
    environment:
      - "MYSQL_ROOT_PASSWORD=12345678"
````

Y lo iniciamos, y vemos las variables de entorno que hay dentro del contenedor
````
$ docker-compose -f docker-compose-mysql.yml up -d
Creating network "dockercompose_default" with the default driver
Creating mysql
theasker@vps462589:~/docker-images/dockerCompose$ docker exec -it mysql bash
root@7db79e2765d2:/# env
HOSTNAME=7db79e2765d2
MYSQL_ROOT_PASSWORD=12345678
PWD=/
HOME=/root
MYSQL_MAJOR=5.7
GOSU_VERSION=1.7
MYSQL_VERSION=5.7.23-1debian9
TERM=xterm
SHLVL=1
PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
_=/usr/bin/env
````

También podemos poner las variables de entorno dentro de un fichero. Metemos nuestra variable en el fichero `common.env`:
````
MYSQL_ROOT_PASSWORD=12345678
````
Y luego en el fichero docker-compose en vez de **environment:** lo cambiamos por **env_file:** apuntando al fichero en que tenemos las variables de entorno, y el fichero docker-compose quedaría así:
````yml
version: '2'
services:
  db:
    image: mysql:5.7
    container_name: mysql
    ports:
      - "3333:3306"
    env_file: common.env
````

### Volúmenes

#### Volumen nombrado
````yml
version: '2'
services:
  web:
    container_name: nginx1
    ports:
      - "8080:80"
    image: nginx
    volumes:
      - "vol2:/usr/share/nginx/html"
volumes:
    vol2:
````
Nos creará un volumen en disco en el **docker root** por defecto en `/var/lib/docker`. Este volumen se creará entonces en `/var/lib/docker/volumes/dockercompose_vol2`.

#### Volumen de host

````yml
version: '3'
services:
  web:
    container_name: nginx1
    ports:
      - "8080:80"
    image: nginx
    volumes:
      - "/home/theasker/volumen:/usr/share/nginx/html"
````

### Redes

````yml
version: '2'
services:
  web:
    container_name: nginx1
    ports:
      - "8080:80"
    image: nginx
    networks:
      - net-test
networks:
    net-test:
````

Con esto, nos creará una red llamada net-test. Con esto podemos enlazar y comunicar contenedores creando varios servicios con la misma red:

````yml
version: '2'
services:
  web:
    container_name: apache1
    ports:
      - "8080:80"
    image: httpdping
    networks:
      - net-test
  web2:
    container_name: apache2
    ports:
      - "8081:80"
    image: httpdping
    networks:
      - net-test
networks:
    net-test:
````

Y arrancamos los contenedores:
````
$ docker-compose -f docker-compose-networks.yml up -d
Creating network "dockercompose_net-test" with the default driver
Creating apache2
Creating apache1
````

Como hemos agregado los 2 contenedores (web, web2) a la misma red `net-test`, podemos hacer un ping de uno a otro:
````
theasker@vps462589:~/docker-images/dockerCompose$ docker exec -ti apache1 sh -c "ping apache2"
PING apache2 (172.27.0.2) 56(84) bytes of data.
64 bytes from apache2.dockercompose_net-test (172.27.0.2): icmp_seq=1 ttl=64 time=0.134 ms
64 bytes from apache2.dockercompose_net-test (172.27.0.2): icmp_seq=2 ttl=64 time=0.092 ms
64 bytes from apache2.dockercompose_net-test (172.27.0.2): icmp_seq=3 ttl=64 time=0.068 ms
64 bytes from apache2.dockercompose_net-test (172.27.0.2): icmp_seq=4 ttl=64 time=0.074 ms
64 bytes from apache2.dockercompose_net-test (172.27.0.2): icmp_seq=5 ttl=64 time=0.106 ms
^C
--- apache2 ping statistics ---
5 packets transmitted, 5 received, 0% packet loss, time 4098ms
rtt min/avg/max/mdev = 0.068/0.094/0.134/0.026 ms
theasker@vps462589:~/docker-images/dockerCompose$ docker exec -ti apache2 sh -c "ping apache1"
PING apache1 (172.27.0.3) 56(84) bytes of data.
64 bytes from apache1.dockercompose_net-test (172.27.0.3): icmp_seq=1 ttl=64 time=0.055 ms
64 bytes from apache1.dockercompose_net-test (172.27.0.3): icmp_seq=2 ttl=64 time=0.070 ms
64 bytes from apache1.dockercompose_net-test (172.27.0.3): icmp_seq=3 ttl=64 time=0.085 ms
^C
--- apache1 ping statistics ---
3 packets transmitted, 3 received, 0% packet loss, time 2041ms
rtt min/avg/max/mdev = 0.055/0.070/0.085/0.012 ms
````

Pero no sólo podemos hacer ping por nombre del contenedor, sino por el nombre del servicio que le hemos dado en el archivo docker-compose, es decir, al serivicio **web** y **web2**:

````
theasker@vps462589:~/docker-images/dockerCompose$ docker exec -ti apache1 sh -c "ping -c2 web2"
PING web2 (172.27.0.2) 56(84) bytes of data.
64 bytes from apache2.dockercompose_net-test (172.27.0.2): icmp_seq=1 ttl=64 time=0.111 ms
64 bytes from apache2.dockercompose_net-test (172.27.0.2): icmp_seq=2 ttl=64 time=0.088 ms

--- web2 ping statistics ---
2 packets transmitted, 2 received, 0% packet loss, time 1024ms
rtt min/avg/max/mdev = 0.088/0.099/0.111/0.015 ms
theasker@vps462589:~/docker-images/dockerCompose$ docker exec -ti apache2 sh -c "ping -c2 web"
PING web (172.27.0.3) 56(84) bytes of data.
64 bytes from apache1.dockercompose_net-test (172.27.0.3): icmp_seq=1 ttl=64 time=0.057 ms
64 bytes from apache1.dockercompose_net-test (172.27.0.3): icmp_seq=2 ttl=64 time=0.180 ms

--- web ping statistics ---
2 packets transmitted, 2 received, 0% packet loss, time 1029ms
rtt min/avg/max/mdev = 0.057/0.118/0.180/0.062 ms
````

### Construir imágenes en Docker Compose

Docker Compose tiene el comando **build** para construir imágenes.

Creamos el fichero `docker-compose.yml`. Con el apartado **context** le decimos en que carpeta está el fichero Dockerfile
````
version: '2'
services:
  web:
    container_name: web
    image: web-test
    build:
      context: .
      dockerfile: Dockerfile2
````



Y el fichero `Dockerfile2`, que es el nombre que le hemos dicho, que buscará al contruir la imagen, que tiene que estar situado en el directorio que hemos puesto en **context** que está el docker-compose.yml.
````dockerfile
FROM centos

RUN mkdir /opt/test
````

Luego ejecutamos el comando **`docker-compose build`** para constuir la imagen y ejecutar el servicio
````
$ docker-compose build
Building web
Step 1/2 : FROM centos
 ---> 5182e96772bf
Step 2/2 : RUN mkdir /opt/test
 ---> Running in 610769722bc7
Removing intermediate container 610769722bc7
 ---> 49d977a2b675
Successfully built 49d977a2b675
Successfully tagged web-test:latest
````

### Sobreescribir el CMD de un contenedor con Docker Compose

Primero vamos a ver el CMD por defecto de un contenedor de Centos (que vamos a usar por ejemplo):
````
$ docker run -dti --name centos centos
11d152c3d103931e200301b31c1ff9a1cc429b89ad524f1db8ad1afdadf8fbea
$ docker ps
CONTAINER ID        IMAGE               COMMAND             CREATED             STATUS              PORTS               NAMES
11d152c3d103        centos              "/bin/bash"         6 seconds ago       Up 4 seconds                            centos
````
Y vemos que el CMD por defecto de la imagen de Centos es `/bin/bash`

Creamos el fichero `docker-compose-cmd.yml`
````
version: '2'
services:
  web:
    image: centos
    command: python -m SimpleHTTPServer 8080
    ports:
      - "8080:8080"
````
Aquí definimos un nuevo CMD para el contenedor que creamos y debería sobreescribir el CMD por defecto que lleve la imagen.
````
e$ docker-compose -f docker-compose-cmd.yml up -d
Creating network "dockercompose_default" with the default driver
Creating dockercompose_web_1
theasker@vps462589:~/docker-images/dockerCompose$ docker ps -a
CONTAINER ID        IMAGE               COMMAND                  CREATED             STATUS              PORTS                    NAMES
bcefeabaac8a        centos              "python -m SimpleHTT…"   11 seconds ago      Up 11 seconds       0.0.0.0:8080->8080/tcp   dockercompose_web_1
11d152c3d103        centos              "/bin/bash"              About an hour ago   Up About an hour                             centos
````
Vemos que el CMD ahora es el que hemos especificado en el Docker Compose.

### Limitar recursos para contenedores usando Docker Compose

Creamos el archivo `docker-compose-memory.yml`
````
version: '2'
services:
  web:
    container_name: nginx
    mem_limit: 20m
    cpuset: "0"
    image: nginx:alpine
````

Levantamos el servicio con el comando:
````
docker-compose -f docker-compose-memory.yml up -d
````

Con el comando **stats** veremos las estadísticas de recursos en uso según contenedor:
````
$ docker stats --no-stream
CONTAINER ID        NAME                CPU %               MEM USAGE / LIMIT   MEM %               NET I/O             BLOCK I/O           PIDS
485e8f347c10        nginx               0.00%               1.754MiB / 20MiB    8.77%               1.72kB / 0B         0B / 0B             2
````
Y vemos como está limitada la memoria.

### Política de reinicio de contenedores

Condiciones o reglas en las que le decimos a un contenedor que tiene que ser reiniciado.

Usaremos la opción **`restart`**, que **por defecto está a no**.
* **restart: "no"**: Opción por defecto
* **restart: always**: Se reinicia siempre que se haya apagado
* **restart: on-failure**: Sólo se reinicia si hay un error interno en el contenedor. Si el código de error es difernte a 0 lo reiniciará. Por ejemplo, lo podemos forzar con un `exit 1` en un script.
* **restart: unless-stopped**: A menos que nosotros hayamos detenido el contenedor se reiniciará, con `docker stop test`.

Todo esto también lo podemos hacer diretamente en línea de comandos con **docker run**:

    docker run -dit --restar unless-stopped redis

Voy a crear un archivo de Docker Compose:

````
version: '2'
services:
  test:
    container_name: test
    image: restar-image
    build: .
    restart: always
````

Ahora creamos un Dockerfile que usará el apartado **build**:

    FROM centos
    COPY start.sh /start.sh
    RUN chmod +x /start.sh
    CMD /start.sh


Ahora creamos el script `start.sh`:

    #!/bin/bash
    echo "Estoy vivo"
    sleep 5
    echo "Estoy detenido"


Ahora creamos la imagen:

    docker-compose build

Arrancamos el contenedor

    docker-compose up -d

Luego con el comando **watch**, veremos como se va reiniciando:

    watch -d docker ps -a

Y sino con los logs del contenedor de docker veremos el histórico de lo que ha hecho:

    docker logs -f test

### Personalizar el nombre de tu proyecto en Docker Compose

Cuando levantamos un servicio, docker automáticamente nos **crea una red con el nombre de la carpeta** y poniendo después "default".

    # docker-compose -f docker-compose-cmd.yml up -d
    Creating network "dockercompose_default" with the default driver
    Creating dockercompose_web_1

Pero esto lo podemos personalizar usando la opción **`-p`** de proyect, de esta manera:

    # docker-compose -p webtest -f docker-compose-cmd.yml up -d
    Creating network "webtest_default" with the default driver
    Creating webtest_web_1

### Ejemplos de Docker Compose

#### Wordpress + MySQL

````
version: '2.4'

services:
  db:
    container_name: wp-mysql
    image: mysql:5.7
    volumes:
      - $PWD/data:/var/lib/mysql
    environment:
      MYSQL_ROOT_PASSWORD: 12345678
      MYSQL_DATABASE: wordpress
      MYSQL_USER: wordpress
      MYSQL_PASSWORD: wordpress
    ports:
      - "3306:3306"
    networks:
      - my_net

  wordpress:
    container_name: wp
    image: wordpress
    depends_on:
      - db
    volumes:
      - "$PWD/html:/var/www/html"
    environment:
      WORDPRESS_DB_HOST: db:3306
      WORDPRESS_DB_USER: wordpress
      WORDPRESS_DB_PASSWORD: wordpress
    ports:
      - "81:80"
    networks:
      - my_net
networks:
  my_net:
````

Cuando iniciamos los servicios con `docker-compose up -d` podemos usar otro comando de docker para ver como va iniciando y los mensajes que va arrojando a medida que se ejecutan los servicios con:

    docker-compose logs -f

#### Drupal +  PostgreSQL

````docker
version: '2.4'

services:
  drupal:
    volumes:
      - drupal:/var/www/html
    image: drupal:8-apache
    ports:
      - "80:80"
    networks:
      - net
  postgres:
    image: postgres:10
    environment:
      POSTGRES_PASSWORD: drupal
    volumes:
      - "$PWD/data:/var/lib/postgreslq/data"    
    networks:
      - net
volumes:
  drupal:
networks:
  net:
````

#### PrestaShop + MySQL

````docker
version: '2.4'

services:
  db:
    container_name: ps-mysql
    image: mysql:5.7
    volumes:
      - $PWD/data:/var/lib/mysql
    environment:
      MYSQL_ROOT_PASSWORD: 12345678
      MYSQL_DATABASE: ps
      MYSQL_USER: ps
      MYSQL_PASSWORD: ps
    ports:
      - "3306:3306"
    networks:
      - net
  ps:
    container_name: ps-web
    volumes:
      - "$PWD/html:/var/www/html"
    depends_on:
      - db
    image: prestashop/prestashop
    ports:
      - "80:80"
    environment:
      DB_SERVER: db
      DB_USER: ps
      DB_PASSWD: ps
      DB_NAME: ps
    networks:
      - net
networks:
  net:
````

#### Joomla + MySQL

````
version: '2'
services:
  web:
    imge: joomla
    ports:
      - 8080:80
    environment:
      JOOMLA_DB_HOST: db
      JOOMLA_DB_PASSWORD: example
      JOOMLA_DB_USER: joomla
      JOOMLA_DB_NAME: joomla
  db:
    image: mysql:5.7
    environment:
      MYSQL_ROOT_PASSWORD: example
      MYSQL_USER: joomla
      MYSQL_DATABASE: joomla
      MYSQL_PASSWORD: joomla
````

#### Reaction Ecommerce - NodeJS + MongoDB

````
version: '2'

services:
  reaction:
    image: reactioncommerce/reaction:latest
    networks:
      - net
    depends_on:
      - mongo
    ports:
      - "3000:3000"
    environment:
      ROOT_URL: "http:/localhost"
      MONGO_URL: "mongodb://mongo:27017/reaction"

  mongo:
    image: mongo
    volumes:
      - $PWD/data:/data/db
    networks:
      - net

  networks:
    net:
````

#### Instalando Guacamole

`./docker-compose.yml`
````
version: '2'
services:
  db:
    container_name: guacamole-db
    networks:
      - net
    image: mysql:5.7
    volumes:
      - ./conf/initdb.sql:/docker-entrypoint-initdb.d/initdb.sql
      - .data:/var/lib/mysql
    env_file: .env
  daemon:
    container_name: guacamole-daemon
    networks:
      - net
    image: guacamole/guacd
    depends_on:
      - db
  web:
    container_name: guacamole-web
    networks:
      - net
    image: guacamole/guacamole
    env_file: .env
    depends_on:
      - daemon
  proxy:
    container_name: guacamole-proxy
    hostname: guacamole-proxy
    networks:
      - net
    image: nginx
    ports:
      - "80:80"
    restart: always
    volumes
networks:
  net:
    driver: bridge
````
* https://guacamole.apache.org/doc/gug/guacamole-docker.html
Con este comando sacamos el script de base de datos con las tablas que necesita Guacamole y nos genera el fichero `initdb.sh`:

    $ docker run --rm guacamole/guacamole /opt/guacamole/bin/initdb.sh --mysql > initdb.sql

Fichero de variables `.env`:
````
MYSQL_ROOT_PASSWORD=12345678
MYSQL_DATABASE=guacamole_db
GUACD_HOSTNAME=daemon
MYSQL_HOSTNAME=db
MYSQL_PASSWORD=12345678
MYSQL_USER=guacamole
````

Usamos el servidor web nginx para usarlo de proxy y que redireccione el puerto 8080 de guacamole por el 80 o el que queramos, con el archivo de configuración `nginx.conf`.

* https://guacamole.apache.org/doc/gug/proxying-guacamole.html

````
location /guacamole/ {
    proxy_pass http://HOSTNAME:8080/guacamole/;
    proxy_buffering off;
    proxy_http_version 1.1;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection $http_connection;
    access_log off;
````

#### Instala Zabbix con Compose

Zabbix es una herramienta para monitorizar servidores.

`docker-compose.yml`
````
version: '2'
services:
  zabbix:
    container_name: zabbix-web
    image: zabbix
    build: .
    volumes:
      - "$PWD/html:/usr/share/zabbix"
    ports:
      - "80:80"
    networks:
      - net
  db:
    container_name: zabbix-db
    image: mysql:5.7
    environment:
      MYSQL_ROOT_PASSWORD: 123456
      MYSQL_USER: zabbix
      MYSQL_PASSWORD: zabbix
      MYSQL_DATABASE: zabbix
    volumes:
      - "$PWD/data:/var/lib/mysql"
      - "$PWD/conf/create.sql:/docker-entrypoint-initdb.d/zabbix.sql"
    ports:
      - "3306:3306"
    networks:
      - net
networks:
  net:
````

Generamos nuestra propia imagen de **Zabbix** con el comando `build .`, que buscará el fichero **Dockerfile** en la carpeta en la que está el fichero docker-compose.yml y se ejecutará cuando ejecutemos el comando `docker-compose up -d`.

Usamos el **directorio html** con los datos de una instalación limpia en un volumen separado.

En el volumen de la base de datos de MySQL usamos un volumen para guardar los datos de la base de datos y otro que será un script. 

**IMPORTANTE:** Cuando usamos la **imagen oficial de MySQL** tiene una funcionalidad: si colocamos con un archivo sql en la ruta `/docker-entrypoint-initdb.d/` MySQL lo ejecutará cuando se inicie la base de datos.

`Dockerfile`
````
FROM centos:7

ENV ZABBIX_REPO http://repo.zabbix.com/zabbix/3.4/rhel/7/x86_64/zabbix-release-3.4.1.el7.centos.noarch.rpm

RUN \
  yum -y install $ZABBIX_REPO && \
  yum -y install \
    zabbix-get \
    zabbix-server-mysql \
    zabbix-web-mysql \
    zabbix-agent

EXPOSE 80 443
COPY ./bin/start.sh /start.sh
COPY ./conf/zabbix-http.conf /etc/httpd/conf.d/zabbix.conf
COPY ./conf/zabbix-server.conf /etc/zabbix/zabbix_server.conf
COPY ./conf/zabbix-conf.conf /etc/zabbix/web/zabbix.conf.php
VOLUME /usr/share/zabbix /var/log/httpd
RUN chmod +x /start.sh
CMD /start.sh
````

Fichero de arranque del servicio `start.sh`:
````
#!/bin/bash

# Start zabbix server
/usr/sbin/zabbix_server -c /etc/zabbix/zabbix_server.conf

# Start zabbix agent
/usr/sbin/zabbix_agentd

# Start httpd
apachectl -DFOREGROUND
````

Fichero de configuración de Apache para Zabbix `zabbix.conf`:
````
#
# Zabbix monitoring system php web fronted
#

Alias /zabbix /usr/share/zabbix

<Dyrectory "/usr/share/zabbix">
  Options FollowSymLinks
  AllowOverride None
  Require all granted

  <IfModule mod_php5.c>
    php_value max_execution_time 300
    php_value memory_limit 128M
    php_value post_max_size 16M
    php_value upload_max_filesize 2M
    php_value max_input_time 300
    php_value always_populate_raw_post_data -1
    php_value date.timezone Europe/Madrid
  </IfModule>
</Dyrectory>

<Dyrectory "/usr/share/zabbix/conf">
  Require all denied
</Dyrectory>

<Dyrectory "/usr/share/zabbix/app">
  Require all denied
</Dyrectory>

<Dyrectory "/usr/share/zabbix/include">
  Require all denied
</Dyrectory>

<Dyrectory "/usr/share/zabbix/local">
  Require all denied
</Dyrectory>
````

El fichero `zabbix_server.conf` es el archivo de configuración de zabbix que lo podemos copiar de la web, en donde se encuentra, entre otras cosas, la configuración de conexión a la base de datos.

El archivo `zabbix.conf.php` es la configuración de conexión a la base de datos pero en php.

### Ejercicios

Deberás crear un docker-compose para cada definición de contenedores de los ejercicios anteriores:

Puntos a tener en cuenta:

* Docker Compose V2

* La imagen (De apache y php) debe poder construirse desde el docker-compose

* Se deben definir los volúmenes solicitados en el ejericicio anterior, más las variables de entorno, límite y demás requisitos especificados en los ejercicios anteriores.

---

#### Ejercicio 1

En donde trabajas, solicitan los siguientes contendores con las siguientes características:

Un contenedor con la imagen de Apache + php creada en la anterior solicitud con:
* 50Mb límites de RAM
* Solo podrá acceder a la CPU 0
* Debe tener dos variables de entorno:
    * ENV = dev
    * VIRTUALIZATION = docker
* El webserver debe ser accesible vía puerto 5555 en el navegador

**Con línea de comandos**:
````
docker run -d -m "50mb" --cpuset-cpus 0 --name apachephpmemcpu -e "ENV=dev" -e "VIRTUALIZATION=docker" -p 5555:80 apachephp
````

**Con docker-compose**:

`Dockerfile`
````
FROM debian:9
RUN     apt-get update && \
        apt-get -y install apache2 php7.0 php7.0-cli php7.0-common
RUN echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php

RUN a2enmod php7.0

CMD apachectl -DFOREGROUND
````
`docker-compose.yml`
````
version: '2'
services:
  web:
    container_name: apachephp
    build: .
    ports:
      - "5555:80"
    volumes:
      - "./html:/var/www/html"
    environment:
      ENV: stg
      VIRTUALIZATION: docker
      TYPE: container
    mem_limit: 50m
    cpuset: "0"
    networks:
      - net
networks:
  net:
````
Como creamos un volumen con el documentRoot del sistio web de apache está vacío, por lo que creamos un fichero con información de php

  echo "<?php phpinfo(); ?>" > ./html/phpinfo.php

#### Ejercicio 2

Crear un docker-compose v3 con dos servicios:

db y admin.

En el servicio DB, debe ir una db con mysql:5.7 y las credenciales de tu preferencia.

En el admin, debes usar la imagen oficial de phpmyadmin, y por medio de redes, comunicarla con mysql. Debes exponer el puerto de tu preferencia y para validar que funcione, debes loguearte en el UI de phpmyadmin vía navegador, usando las credenciales del root de mysql.

`docker-compose.yml`
````
version: '2'
services:
  db:
    container_name: db
    image: mysql:5.7
    environment:
      MYSQL_ROOT_PASSWORD: 123456
      MYSQL_USER: phpmyadmin
      MYSQL_PASSWORD: phpmyadmin
      MYSQL_DATABASE: phpmyadmin
    volumes:
      - "$PWD/data:/var/lib/mysql"
    networks:
      - net
  phpmyadmin:
    container_name: phpmyadmin
    image: phpmyadmin/phpmyadmin
    depends_on:
      - db
    ports:
      - "81:80"
    environment:
      PMA_HOST: db
    networks:
      - net
networks:
  net:
````

## Docker Registry

Docker registry es un servicio al que nosotros podemos subir y bajar imágenes. Es decir crear un repositorio privado para subir y bajar imágenes propias.

La documentación está en https://docs.docker.com/registry/.

  docker run -d -p 5000:5000 --name registry -v $PWD/data:/var/lib/registry registry:2

### Subir imágenes al repositorio local

* Primero descargamos o creamos la imagen
* Luego etiquetamos la imagen con el repositorio de destino con **`docker tag`**, cuyo uso es **`docker tag <imagen origen>[:TAG] <servidor de destino:puerto>/<nombre imagen en el registry>[:TAG]`**. 
````
$ docker tag --help

Usage:  docker tag SOURCE_IMAGE[:TAG] TARGET_IMAGE[:TAG]

Create a tag TARGET_IMAGE that refers to SOURCE_IMAGE
````
* Finalmente la subimos con **`docker push <imagen>`**:
````
docker push --help

Usage:  docker push [OPTIONS] NAME[:TAG]

Push an image or a repository to a registry

Options:
      --disable-content-trust   Skip image signing (default true)
````

Como ejemplo vamos a subir la imagen `hello world` de docker:

````
$ docker pull hello-world
Using default tag: latest
latest: Pulling from library/hello-world
d1725b59e92d: Pull complete
Digest: sha256:0add3ace90ecb4adbf7777e9aacf18357296e799f81cabc9fde470971e499788
Status: Downloaded newer image for hello-world:latest
$ docker images | grep hello-world
hello-world             latest              4ab4c602aa5e        2 months ago        1.84kB
$ docker tag hello-world:latest localhost:5000/my-hello-world
$ docker images | grep hello-world
hello-world                     latest              4ab4c602aa5e        2 months ago        1.84kB
localhost:5000/my-hello-world   latest              4ab4c602aa5e        2 months ago        1.84kB
$ docker push localhost:5000/my-hello-world
The push refers to repository [localhost:5000/my-hello-world]
428c97da766c: Pushed
latest: digest: sha256:1a6fd470b9ce10849be79e99529a88371dff60c60aab424c077007f6979b4812 size: 524
````

Vemos que ahora el directorio **data** que hemos puesto como volumen está ahora con datos:
````
$ tree -d -L 5 data/
data/
└── docker
    └── registry
        └── v2
            ├── blobs
            │   └── sha256
            └── repositories
                └── my-hello-world
````

### Descargar imágenes del repositorio local

Simplemente es usar el el comando **`docker pull <servidor>/<nombre de la imagen>`**

````
docker pull localhost:5000/hello-world
````

### Compartir imágenes con la red

Si intentamos subir una imagen a un repositorio con ip nos va a dar un error:
````
$ docker tag hello-world 145.239.196.19:5000/hello-world
$ docker push 145.239.196.19:5000/hello-world
The push refers to repository [145.239.196.19:5000/hello-world]
Get https://145.239.196.19:5000/v2/: http: server gave HTTP response to HTTPS client
````

Ya que estamos intentando subir a una localización insegura sin autenticación. Sólo podemos hacer esto cuando es localhost por ser nuestra misma máquina. 

Para poder realizar estas operaciones, es decir, **subir y descargar imágenes de un registro de otra localización** que no es localhost, tenemos que editar el fichero `/lib/systemd/system/docker.service` y modificar la línea **ExecStart** y añadir **`--insecure-registry ip:port`**, quedando:

  ExecStart=/usr/bin/dockerd --insecure-registry 145.239.196.19:5000 -H fd://

y ahora ya nos dejaría subir nuestras imágenes:
````
$ docker push 145.239.196.19:5000/hello-world
The push refers to repository [145.239.196.19:5000/hello-world]
Get https://145.239.196.19:5000/v2/: http: server gave HTTP response to HTTPS client
````

### Crear un Docker Registry con autenticación

* https://www.linuxfacilito.online/docker-registry-con-basic-auth/


## Enlaces
* https://www.udemy.com/docker-de-principiante-a-experto
* Para hacer todas las pruebas hay un **sandbox de Docker** en: https://labs.play-with-docker.com
* **Docker registry**: https://docs.docker.com/registry/
* **Docker registri con autenticación**: https://www.linuxfacilito.online/docker-registry-con-basic-auth/ 