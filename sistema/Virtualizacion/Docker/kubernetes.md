# Kubernentes

## Instalación

### Instalar kubectl

* https://kubernetes.io/es/docs/tasks/tools/included/install-kubectl-linux/
```bash
curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
```

Verificamos la versión
```bash
$ kubectl version --output=json
{
  "clientVersion": {
    "major": "1",
    "minor": "27",
    "gitVersion": "v1.27.3",
    "gitCommit": "25b4e43193bcda6c7328a6d147b1fb73a33f1598",
    "gitTreeState": "clean",
    "buildDate": "2023-06-14T09:53:42Z",
    "goVersion": "go1.20.5",
    "compiler": "gc",
    "platform": "linux/amd64"
  },
  "kustomizeVersion": "v5.0.1"
}
The connection to the server localhost:8080 was refused - did you specify the right host or port?
```

## Crear un kluster de kubernetes en Linux

### Instalar minikube

* https://minikube.sigs.k8s.io/docs/start/

minikube es Kubernetes local y se centra en facilitar el aprendizaje y el desarrollo para Kubernetes.

minikube necesita casi 2Gb de memoria para ejecutarse, por lo que uso la instancia de la máquina arm que tiene 24 Gb, pero hay que instalar la versión específica para arm

```bash
curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-arm
sudo install minikube-linux-arm /usr/local/bin/minikube
```

Como falla la ejecución, descargo una versión más vieja de aqui https://github.com/kubernetes/minikube/releases/download/v1.26.0/minikube-linux-arm como sugieren con el error que me da en las incidencias de github en https://github.com/kubernetes/minikube/issues/14410

## Bibliografía

 * https://www.youtube.com/watch?v=DCoBcpOA7W4 => Video curso de Pelado Nerd
 * https://videocursos.co/modulo/curso-de-kubernetes/ => El mismo vídeo de Pelado Nerd
 * https://kubernetes.io/es/docs/tasks/tools/included/install-kubectl-linux/
 * https://minikube.sigs.k8s.io/docs/start/
 * https://github.com/kubernetes/minikube/issues/14410
 * https://github.com/kubernetes/minikube/releases/download/v1.26.0/
 * https://minikube.sigs.k8s.io/docs/handbook/controls/
 * https://k8slens.dev/ => Interfaz gráfica para kubectl