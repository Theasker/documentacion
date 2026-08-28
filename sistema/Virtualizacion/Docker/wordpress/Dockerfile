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
