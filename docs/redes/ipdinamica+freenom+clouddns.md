# Configuración de IP Dinámica con Freenom.com y ClouDNS

![cloudDns](https://www.cloudns.net/images/logo/social.png) ![freenom](https://my.freenom.com/templates/freenom/img/logo.png)

Tenemos un dominio de **freenom** con un TLD (Top Level Domain) .tk, .ml, .ga, .cf o .gq pero necesitamos que se asocie a una típica red doméstica con IP dinámica.

Vamos a configurar un dominio .tk en un servidor con IP dinámica, estos dominios son suministrados por **freenom** pero no da este tipo de servicio. Para ello utilizaremos un servicio que da **clouDns** de forma gratuíta. Lo que vamos a hacer es que la gestión del dominio no la haga **freenom** sino **cloudDns**:

1. Primero crearemos una **Zona DNS** desde `Dashboard > DNS Hosting > Create zone > Master zone`, y se abrirá una ventana modal con título **New domain zone** con los nombre de dominio `ns41.cloudns.net`, `ns42.cloudns.net`, `ns43.cloudns.net` y `ns44.cloudns.net` y en **Domain name** ponemos el nombre del dominio **xxxxx.ml** y pulsamos en **Create**. Al crear la zona, nos crea los 4 registros de **Name Server** (NS).
2. Vamos a crear el registro tipo **A** pulsando en la pestaña correspondiente y luego pulsando en el botón **+ Add new record**. Al hacer esto se abre una ventana modal con título **Add new record**. En el campo **Host** pondremos www y en **Points to** ponemos por ejemplo `10.10.10.10` y pulsamos en botón **Save**.
3. Para activar el servicio de IP dinámica en el registro que acabamos de crear, en los iconos de la parte derecha pulsamos sobre las 2 flechas, que al pasar el ratón por encimas dice **Dynamic URL**. Se abrirá una ventana modal con título **Inactive Dynamic URL** para pedir confirmación y pulsar el botón **Activate it** y se abrirán varias opciones para elegir el método por el que se hará la actualización de la IP dinámica, hay para elegir actulizaciones por **wget**, **Perl**, **Python**, **PHP** y **Windows script** y voy a elegir wget y copiaré la línea `wget -q --read-timeout=0.0 --waitretry=5 --tries=400 --background https://ipv4.cloudns.net/api/dynamicURL/?q=xxxxxxx`
4. Creamos una tarea con cron el el serividor donde queremos que apunte el dominio haciendo `crontab -e` e introduciendo esa línea que se ejecute cada 5 min. La línea quedaría así: `*/5 * * * * wget -q --read-timeout=0.0 --waitretry=5 --tries=400 --background https://ipv4.cloudns.net/api/dynamicURL/?q=xxxxxxx`. Podemos comprobar que cuando actualizamos la página de los registros, vemos que se ha actualizado la ip.



https://my.freenom.com/clientarea.php

## Enlaces
* https://www.cloudns.net/
* https://www.freenom.com/

## Bibliografía
* https://www.youtube.com/watch?v=tUBiwPIRrYA