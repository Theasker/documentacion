# Kubernetes

## Dashboard
```bash
minikube dashboard --port=<port> --url=false
```
* `url=false`: No intenta abrir el navegador y muestra la url

```bash
minikube dashboard
```

### Crear un tunnel ssh para acceder en remoto
> https://moreluz-ia.com/kubernetes/minikube/que-es-dashboard/

Primero arrancamos el dashboard de minikube:
```bash
$ minikube-linux-arm64 dashboard
🤔  Verifying dashboard health ...
🚀  Launching proxy ...
🤔  Verifying proxy health ...
🎉  Opening http://127.0.0.1:43573/api/v1/namespaces/kubernetes-dashboard/services/http:kubernetes-dashboard:/proxy/ in your default browser...
/usr/bin/xdg-open: 882: www-browser: not found
/usr/bin/xdg-open: 882: links2: not found
/usr/bin/xdg-open: 882: elinks: not found
/usr/bin/xdg-open: 882: links: not found
/usr/bin/xdg-open: 882: lynx: not found
/usr/bin/xdg-open: 882: w3m: not found
xdg-open: no method available for opening 'http://127.0.0.1:43573/api/v1/namespaces/kubernetes-dashboard/services/http:kubernetes-dashboard:/proxy/'

❌  Exiting due to HOST_BROWSER: failed to open browser: exit status 3
```

Con la salida del comando vemos que el puerto aleatorio asignado es el 43573. Con ese puerto creamos un tunel ssh desde el ordenador remoto:
```bash
ssh -L 43573:localhost:43573 poder
```

* `-L`: Esta opción se conoce como "forwarding local". Lo que hace es crear un túnel local en tu máquina, conectando un puerto local a un puerto remoto en otra `áquina.
* `43573:localhost:43573`: Esta parte especifica los puertos a conectar.
  * `43573`: Este es el puerto local en tu máquina. Cualquier conexión que se establezca en este puerto será reenviada a...
  * `localhost:43573`: Este es el puerto remoto en la máquina a la que te estás conectando (en este caso, "localhost" indica que es la misma máquina). Así, cualquier conexión que llegue al puerto 43573 de tu máquina local será reenviada al puerto 43573 de la máquina remota.
* `poder`: Este es el nombre de host o la dirección IP de la máquina a la que te quieres conectar.

## Bibliografía
* Minikube — the mini-me of Kubernetes development on OCI: https://paulguerin.medium.com/minikube-the-mini-me-of-kubernetes-development-on-oci-acd8de9bb989
* Acceder al dashboard de minikube desde un host remoto: https://moreluz-ia.com/kubernetes/minikube/que-es-dashboard/