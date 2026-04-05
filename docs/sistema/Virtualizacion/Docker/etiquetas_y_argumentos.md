# Etiquetas y argumentos

En un dockerfile podemos poner etiquetas y argumentos para saber que el contenedor que estamos usando, tiene dentro una versión específica de nuesta aplicación (referencia del commit en git, por ejemplo), y también la fecha en la que fué creado ese contenedor:

```dockerfile
FROM alpine:3.4

ARG vcs_ref="Unknown"
ARG build_date="Unknown"

RUN apk add --update py-pip=8.1.2-r0

LABEL org.label-schema.vcs-ref=$vcs_ref \
org.label-schema.build-date=$build_date

COPY app.py /app.py
CMD ["python","/app.py"]
```

a la hora de construir la imagen de docker con `docker build` le pasaremos como argumentos lo que necesitemos:

```bash
$ docker build \
 --build-arg vcs_ref='git rev-parse HEAD' \
 --build-arg date='date -u + "%Y-%m-%dT%H:%MZ"' \
 -t your_image_name .
```