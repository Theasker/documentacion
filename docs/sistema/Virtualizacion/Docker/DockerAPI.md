# Uso del API de Docker

## Activación para su uso

Lanzando docker man, vemos que la opción que buscamos es:

    -H, --host=[unix:///var/run/docker.sock]: tcp://[host]:[port][path] to bind or
        unix://[/path/to/socket] to use.
            The socket(s) to bind to in daemon mode specified using one or more
            tcp://host:port/path, unix:///path/to/socket, fd://* or fd://socketfd.
            If the tcp port is not specified, then it will default to either 2375 when
            --tls is off, or 2376 when --tls is on, or --tlsverify is specified.

Esta opción debe pasarse en el arranque del daemon de Docker. Para configurar esta opción durante el arranque de Docker Engine tenemos dos opciones:

 - modificar el arranque del daemon modificando la configuración de `/lib/systemd/system/docker.service`
 - añadiendo las opciones en el fichero de configuración de Docker Engine. Para sistemas Linux con systemd, la configuración del daemon de Docker se realiza a través del fichero daemon.json ubicado en /etc/docker/.

Usamos el primer método y editamos el fichero `/lib/systemd/system/docker.service`:

Modificamos la línea `ExecStart=/usr/bin/dockerd -H fd://` y añadimos: `-H tcp://0.0.0.0:2375` de manera que quede:

    ExecStart=/usr/bin/dockerd -H fd:// -H tcp://0.0.0.0:2375

Esto hace que dockerd escuche en todas las interfaces disponibles. En el caso de la máquina virtual en la que estoy probando, sólo tengo una, pero lo correcto sería especificar la dirección IP donde quieres que escuche dockerd.

Guardamos los cambios.

Recargamos la configuración y reiniciamos el servicio.

Para comprobar que hemos el API funciona, lanzamos una consulta usando curl:

    # systemctl daemon-reload
    # systemctl restart docker
    # curl http://localhost:2375/version
    {"Version":"17.05.0-ce","ApiVersion":"1.29","MinAPIVersion":"1.12","GitCommit":"89658be","GoVersion":"go1.7.5","Os":"linux","Arch":"amd64","KernelVersion":"3.16.0-4-amd64","BuildTime":"2017-05-04T22:04:27.257991431+00:00"}
    #

## Bibliografía

 * https://onthedock.github.io/post/170506-habilita-el-acceso-remoto-via-api-a-docker/