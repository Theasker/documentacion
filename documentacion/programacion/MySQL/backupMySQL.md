# Programar copias de seguridad para MySQL y MariaDB
( [http://www.ochobitshacenunbyte.com/2016/05/10/programar-copias-de-seguridad-para-mysql-y-mariadb/](Sacdo de ochobitshacenunbyte) ).


Debemos utilizar credenciales seguras, y para no añadirlas directamente a los parámetros, creamos primero un fichero, donde almacenaremos dichas claves.

El fichero lo guardaremos en nuestra home, con permisos de lectura y escritura sólo para root.

	nano /home/davidochobits/.credenciales.cnf

Lo hacemos oculto, añadiendo el punto delante del nombre, y añadimos:

	[mysqldump]
	user=userochobits
	password=contrasea

Guardamos y salimos del editor. Ahora modificamos los permisos:

	chmod 600 /home/davidochobits/.credenciales.cnf

Una vez hecho, teniendo en cuenta que utilizamos un base de datos ficticia llamada ‘testochobits’, probamos que todo funcione. Utilizamos mysqldump de la siguiente manera:

	mysqldump --defaults-file=/home/davidochobits/.credenciales.cnf testochobits > /home/davidochobits/backup.sql

Vale, si todo ha salido bien, es que tanto el fichero de credenciales como el comando y los parámetros, son correctos. Se nos presenta un pequeño problema, si queremos guardar copias diarias, debemos crear algún método, para que no nos “pise” la copia del segundo día al del primero. Para ello añadiremos la fecha al fichero resultante, de la siguiente manera:

	mysqldump --defaults-file=/home/davidochobits/.credenciales.cnf testochobits > /home/davidochobits/"backup-$(date+ "%d%m%Y").sql"

De ésta manera tendremos cada copia con su fecha correspondiente.

Para añadir la programación, modicaremos el fichero contrab ubicado en /etc/crontab, y añadimos:

```bash
# m h dom mon dow user command
15 3 * * 7 bk mysqldump --defaults-file=/home/davidochobits/.credenciales.cnf testochobits > /home/davidochobits/"backup-$(date+ "%d%m%Y").sql"
```

De esta manera haremos copia de seguridad semanal, si queremos hacerla diaria sólo debemos modificar los parámetros relacionados con la fecha.

Espero que os haya parecido interesante. Para realizar el artículo me he servidor de la siguiente documentación