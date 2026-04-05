# Erase una vez kubernetes

Para ver la vrsión de kubectl
```bash
$ kubectl version --client
```

Para clonar el repositorio con los talleres:
```bash
$ git clone https://github.com/mmorejon/erase-una-vez-k8s.git && \
cd erase-una-vez-k8s
```

Para ver los nodos del cluster:
```bash
$ kubectl get nodes
NAME           STATUS   ROLES           AGE     VERSION
controlplane   Ready    control-plane   7d22h   v1.33.2
node01         Ready    <none>          7d22h   v1.33.2
```

+---------------------------------------------------------------+
|                        nodo control-plane                     |
|                                                               |
|   +---------------------+       <--------------------------+  |
|   | kube-controller-    | <-->  |                          |  |
|   |     manager         |       |                          |  |
|   +---------------------+       |                          |  |
|                                  |                          |  |
|   +---------------------+        |                          |  |
|   |        etcd         | <------+--> kube-apiserver <------+--+
|   +---------------------+        |                          |
|                                  |                          |
|   +---------------------+        |                          |
|   |   kube-scheduler    | <------+                          |
|   +---------------------+        |                          |
|                                  |                          |
|   +---------------------+        |                          |
|   |     kube-proxy      | <------+                          |
|   +---------------------+                                   |
|                                                             |
|   +---------+  +---------------+  +-----+  +----------------+ |
|   | coredns |  | network policy|  | cri |  |    kubelet     | |
|   +---------+  +---------------+  +-----+  +----------------+ |
+---------------------------------------------------------------+

                |                                   |
                v                                   v

+-------------------------------+   +-------------------------------+
|          nodo worker          |   |         nodo worker2          |
|                               |   |                               |
|  +---------+  +-----+         |   |  +---------+  +-----+         |
|  | kubelet |  | cri |         |   |  | kubelet |  | cri |         |
|  +---------+  +-----+         |   |  +---------+  +-----+         |
|  +---------+  +---------------+|  |  +---------+  +---------------+|
|  |kube-proxy|  |network policy||  |  |kube-proxy|  |network policy||
|  +---------+  +---------------+|  |  +---------+  +---------------+|
+-------------------------------+   +-------------------------------+

## Componentes del plano de control
Los componentes del nodo control-plane constituyen el plano de control. Entre sus elementos se
encuentra kube-apiserver, etcd, kube-scheduler y kube-controller-manager. Veamos algunos detalles
sobre su funcionamiento y responsabilidades.

### kube-apiserver
El componente kube-apiserver expone la API de Kubernetes; es la interfaz de comunicación para
acceder al plano de control. Todas las peticiones realizadas a Kubernetes pasan por esta interfaz donde los objetos son examinados y validados antes de ser aplicados.

### etcd
Almacenamiento de llave - valor consistente y de alta disponibilidad utilizado en Kubernetes para
almacenar los objetos del cluster.

### kube-scheduler
El componente kube-scheduler (en Español “Planificador”) garantiza la asignación de aplicaciones en los nodos. El sistema busca el nodo que tenga las mejores condiciones de recursos cada vez que llega una solicitud.

### kube-controller-manager
El componente kube-controller-manager es un binario compuesto por diferentes controladores de
Kubernetes, entre los que se encuentran:
* **Node**: Responsable de notificar y responder cuando un nodo deja de funcionar.
* **Replication**: Responsable de mantener el número correcto de aplicaciones.
* **Endpoints**: Responsable de generar enlaces dinámicos entre las aplicaciones desplegadas.
* **Service Account y Token**: Responsable de crear las cuentas y credenciales para las aplicaciones desplegadas.

## Componentes del plano de datos
Los nodos worker y worker2 conforman el plano de datos. Entre sus elementos se encuentra kubeproxy
y kubelet. Veamos algunos detalles sobre su funcionamiento y responsabilidades.

### kube-proxy
kube-proxy es un componente de red. Está presente en todos los nodos porque sino no existiera
comunicación entre el nodo y el plano de control. Su responsabilidad es gestionar las reglas de red entre los nodos y las aplicaciones.
### kubelet
kubelet se encarga de la gestión de contenedores dentro del nodo. Todas las aplicaciones desplegadas en el cluster se tienen que desplegar en forma de contenedor. Este componente es un binario iniciado directamente en el nodo, no es un contenedor.

Los componentes de los nodos se pueden ver utilizando un comando similar al anterior, pero
realizando una pequeña variación en el selector de campo.
```bash
$ kubectl get pods \
--namespace kube-system \
--field-selector spec.nodeName=book-worker
NAME READY STATUS RESTARTS AGE
kindnet-lt6pq 1/1 Running 0 10m
kube-proxy-9d52d 1/1 Running 0 10m
```

## Complementos en Kubernetes
Los complementos son funcionalidades establecidas a lo largo de todo el cluster. Existen múltiples complementos disponibles, pero por el momento vamos a mencionar aquellos que estén presentes en nuestro cluster.

### DNS
Su función es gestionar los enlaces a las aplicaciones de forma dinámica. En nuestro caso utilizamos CoreDNS. Este complemento se instala durante la creación del cluster. Es obligatorio tener este componente funcionando dentro del cluster.

### Conexiones y políticas de redes
Existen múltiples complementos para gestionar conexiones y políticas de redes, entre los más conocidos están: Calico, Flannel y Cilium. En kind se ha desarrollado un componente especial llamado kindnetd para cubrir las necesidades específicas de la herramienta. Es un producto simple y ligero.

## Los objetos dentro del cluster
Los objetos en Kubernetes son entidades almacenadas en etcd (entidades persistentes), donde cada objeto guarda una parte de la información gestionada en el cluster. La estructura de los objetos se componen por campos comunes y específicos.

El objetivo de los campos comunes es identificar cada objeto de forma única. Los atributos utilizados para identificar cada objeto son los siguientes.
* **apiVersion**: Establece la versión del objeto. Una distribución de Kubernetes puede utilizar
múltiples versiones del mismo objeto. Este comportamiento permite la evolución progresiva
de Kubernetes sin afectar a los usuarios finales.
* **kind**: Define el tipo de objeto. Entre los posibles valores están los Pods, Replicasets y
Deployments. El listado completo de tipos de objetos lo puedes obtener en la web oficial
[enlace].
* **metadata**: Dentro de los metadatos se encuentra el nombre, el área, anotaciones y etiquetas.
Ejemplo de estructura común para todos los objetos.
```yaml
apiVersion: v1
kind: Pod
metadata:
  name: example
```
El formato utilizado para describir los objetos es YAML. Los equipos que utilizan Kubernetes tienen un gran número de ficheros YAML en sus repositorios para gestionar el estado de sus clusters. Dentro de un fichero puedes definir tantos objetos de Kubernetes como desees, siempre y cuando los separes por una **línea con tres guiones** (---).
