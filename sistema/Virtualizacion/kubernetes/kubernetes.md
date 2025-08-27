# Kubernetes

## kubectl
**kubectl** es la herramienta de línea de comandos oficial para interactuar con un clúster de Kubernetes. Permite gestionar y controlar los recursos del clúster, como Pods, Services, Deployments, Ingress, etc.

Instalamos `kubectl`:
```bash
curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl.sha256"
```

## Minikube
**Minikube** es una herramienta que permite ejecutar un clúster de Kubernetes de un solo nodo en tu máquina local. Está diseñado para facilitar el desarrollo, pruebas y aprendizaje de Kubernetes sin necesidad de un clúster completo en la nube o en un entorno de producción.

Instalamos `minikube`:
```bash
curl -LO https://github.com/kubernetes/minikube/releases/latest/download/minikube-linux-amd64
```

### Dashboard
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

## Pods

Creamos un pod a partir de la imagen de `nginx`
```bash
kubectl run nginx1 --image=nginx
pod/nginx1 created
```

Ahora podemos ver que se ha creado el pod:
```bash
kubectl get pods
NAME     READY   STATUS    RESTARTS   AGE
nginx1   1/1     Running   0          103s
```

Y podemos obtener toda la información del pod:
```bash
kubectl describe pod nginx1
Name:             nginx1
Namespace:        default
Priority:         0
Service Account:  default
Node:             minikube/192.168.49.2
Start Time:       Wed, 19 Mar 2025 08:33:41 +0100
Labels:           run=nginx1
Annotations:      <none>
Status:           Running
IP:               10.244.0.3
IPs:
  IP:  10.244.0.3
Containers:
  nginx1:
    Container ID:   docker://ca4d47129cfcfc3a84f201b6db6dec6e902f7fbaba04f2c084d5a31667a50ffb
    Image:          nginx
    Image ID:       docker-pullable://nginx@sha256:124b44bfc9ccd1f3cedf4b592d4d1e8bddb78b51ec2ed5056c52d3692baebc19
    Port:           <none>
    Host Port:      <none>
    State:          Running
      Started:      Wed, 19 Mar 2025 08:34:20 +0100
    Ready:          True
    Restart Count:  0
    Environment:    <none>
    Mounts:
      /var/run/secrets/kubernetes.io/serviceaccount from kube-api-access-d7q7r (ro)
Conditions:
  Type                        Status
  PodReadyToStartContainers   True
  Initialized                 True
  Ready                       True
  ContainersReady             True
  PodScheduled                True
Volumes:
  kube-api-access-d7q7r:
    Type:                    Projected (a volume that contains injected data from multiple sources)
    TokenExpirationSeconds:  3607
    ConfigMapName:           kube-root-ca.crt
    ConfigMapOptional:       <nil>
    DownwardAPI:             true
QoS Class:                   BestEffort
Node-Selectors:              <none>
Tolerations:                 node.kubernetes.io/not-ready:NoExecute op=Exists for 300s
                             node.kubernetes.io/unreachable:NoExecute op=Exists for 300s
Events:
  Type    Reason     Age   From               Message
  ----    ------     ----  ----               -------
  Normal  Scheduled  32m   default-scheduler  Successfully assigned default/nginx1 to minikube
  Normal  Pulling    32m   kubelet            Pulling image "nginx"
  Normal  Pulled     32m   kubelet            Successfully pulled image "nginx" in 15.195s (15.195s including waiting). Image size: 192004242 bytes.
  Normal  Created    32m   kubelet            Created container: nginx1
  Normal  Started    32m   kubelet            Started container nginx1
```

Vemos que el pod contiene un sólo container. 
Para entrar en ese container:
`kubectl exec -it <POD_NAME> -c <CONTAINER_NAME> -- /bin/bash`
```bash
kubectl exec -it nginx1 -c nginx1 -- /bin/bash
```

O también sólo ejecutar un comando a un contenedor del pod:
```bash
kubectl exec -it nginx1 -c nginx1 -- ls
```

Podemos ver los logs del pod con:
```bash
kubectl logs nginx1
```

Para borrar un pod:
```bash
kubectl delete pod apache
```

Crear un pod con puerto asociado:
```bash
kubectl run apache --image=httpd --port=8080
```

## Bibliografía
* Minikube — the mini-me of Kubernetes development on OCI: https://paulguerin.medium.com/minikube-the-mini-me-of-kubernetes-development-on-oci-acd8de9bb989
* Acceder al dashboard de minikube desde un host remoto: https://moreluz-ia.com/kubernetes/minikube/que-es-dashboard/
<<<<<<< HEAD
* Instalar kubectl: https://kubernetes.io/docs/tasks/tools/install-kubectl-linux/

### Cursos
* https://www.youtube.com/playlist?list=PLDbrnXa6SAzUaNBcudoGJW7juNouB30KR (dfbastidas)
* https://www.youtube.com/watch?v=gvqpZkdK4DU&t=1665s => Curso de 3 horas de "La tecnología avanza"
* 
=======
* Playgrounds
  * https://killercoda.com/
>>>>>>> 26df907fbb4932df755fdc471d53b1ac90f370ee
