# Tunel ssh

````bash
ssh -p 22 -N -D 8081 pi@theasker.mooo.com
````

* **`-p 22`**: Con este parámetro indicamos el puerto de escucha de nuestros servidor SSH. El puerto de escucha estándard acostumbra a ser el 22 para los servidores SSH. En el caso que hayamos modificado el puerto de escucha deberemos sustituir el 22 por nuestro puerto real de escucha.

* **`-D 8081`**: **Estamos especificando que se realice un reenvio dinámico de puertos o tunel dinámico**. Por lo tanto con este comando estamos indicando que el tunel SSH se establezca a través del puerto local 8081. En el puerto 8081 habrá un servidor proxy socks que escuchará las conexiones del puerto 8081. Cuando el servidor proxy socks detecte una solicitud o conexión en el puerto 8081 enviará el tráfico cifrado a través del tunel SSH que creamos entre la cafetería y nuestra casa. Una vez la petición/información llegue a nuestra casa o servidor SSH, como hemos establecido un tunel dinámico, se redirigirá al sitio de Internet que queremos conectarnos que por ejemplo podría ser Facebook o Twitter.

> Nota: Estamos usando el puerto 8081 pero se puede usar cualquiera de los puertos no privilegiado que van desde el 1025 al 65535. En el caso de querer usar un puerto privilegiado deberemos hacerlo como root añadiendo sudo al inicio comando para establecer el tunel SSH.

> Nota: El servidor local Proxy Socks es quien realmente está enviando el trafico de nuestro ordenador al servidor SSH que está en una red local segura. Por lo tanto la información que reciba el servidor proxy socks a través del cliente, que en este caso es Firefox, debe enviarse con el protocolo Socks. Por lo tanto los clientes como Firefox u otros deberán ser capaces de enviar sus peticiones a través del protocolo socks. En el caso que la aplicación no soporte la comunicación con el protocolo socks no se podrá establecer la conexión. Para aplicaciones que no soporten socks tenemos la opción de usar tsocks en nuestro ordenador. En futuros post comentaremos como usar tsocks.

* **`-N`**: **Permite que se establezca el tunel SSH pero que no se abra una sesión interactiva con el servidor SSH**. En el caso de no usar esta opción, nuestra conexión SSH estará permanentemente abierta y si alguien nos robará el ordenador o nos despistaros y alguien malintencionado tuviera acceso al ordenador nos podría por ejemplo borrar completamente la información que tenemos almacenada en nuestro servidor SSH.

## VPN con ssh
 * https://www.youtube.com/watch?v=NxUga9g7xa0&t=1185s
### Configuración servidor

Primero activaremos en la configuración de ssh la activación de tunel en la configuración de ssh en el archivo `/etc/ssh/sshd_config`:
```
PermitTunnel yes
```

Con el comando `ip` creamos una interface de tipo **tun** (tunel)
```bash
ip tuntap add mode tun tun0
```
Este comando se utiliza para crear una interfaz TUN (Tunnel) o TAP (Ethernet Bridging) en un sistema Linux. Estas interfaces se utilizan comúnmente para configurar túneles de red virtuales para una variedad de propósitos, como redes privadas virtuales (VPN), túneles IPv6 sobre IPv4, entre otros.

En cuanto al comando específico:

 * `ip` es una utilidad de línea de comandos para manipular diferentes aspectos de la red en Linux.
 * `tuntap` es un subcomando de ip que permite manipular interfaces TUN/TAP.
 * `add` es la acción que indica que se está añadiendo una nueva interfaz TUN o TAP.
 * `mode` tun especifica que se está creando una interfaz del tipo TUN (dispositivo de red punto a punto, capa 3), que opera en el nivel de la capa de red (nivel IP).
 * `tun0` es el nombre de la interfaz que se está creando. Puede ser cualquier nombre válido para la interfaz.

En resumen, este comando crea una interfaz de red TUN (nivel IP) en el sistema con el nombre tun0. Las interfaces TUN se utilizan generalmente para enrutar paquetes IP entre diferentes redes o sistemas, creando un túnel de red virtual punto a punto. Este túnel puede ser utilizado para varios propósitos, incluyendo la implementación de VPN, redes privadas, entre otros.

Ahora vamos a asignar una ip a esa interfaz
```bash
ip add add 192.168.0.71/24 dev tun0
```
Este comando ip add add 192.168.0.71/24 dev tun0 se utiliza para asignar una dirección IP a una interfaz de red específica (tun0 en este caso) en un sistema Linux utilizando la herramienta ip para la configuración de red.

Explicando los elementos del comando:

 * `ip` es la utilidad de línea de comandos para manipular aspectos de la red en Linux.
 * `add` address
 * `add` indica que se va a agregar una nueva configuración a la interfaz de red especificada.
 * `192.168.0.71/24` es la dirección IP que se asignará a la interfaz de red. El /24 es la notación de máscara de red que define la porción de red de la dirección IP (en este caso, los primeros 24 bits, lo que indica que los primeros tres octetos de la dirección IP son la parte de red).
 * `dev tun0` especifica que la dirección IP se agregará a la interfaz tun0. Esta interfaz es una interfaz TUN (dispositivo de red punto a punto, capa 3) que puede haber sido creada previamente para establecer un túnel virtual.

En resumen, este comando asigna la dirección IP 192.168.0.71 con una máscara de red de /24 a la interfaz de red tun0 en el sistema. Esto se usa comúnmente en configuraciones de redes virtuales, como VPNs (Redes Privadas Virtuales), para asignar direcciones IP a interfaces de túneles de red virtuales.

Ahora sólo tenemos que levantar la interface:
```bash
ip link set tun0 up
```

### Configuración en el cliente

```bash
ip tuntap add mode tun tun0
ip add add 192.168.0.70/24 dev tun0
```
Ahora sólo tenemos que levantar la interface:
```bash
ip link set tun0 up
```

### Creación del tunel con ssh en el cliente
```bash
ssh -Nf -c aes256-ctr -w 0:0 theasker@<ip remota>
```
* **`-N`** -> No se ejecute ningún comando en la terminal
* **`-f`** -> La ejecución del comando, se manda al background como un servicio
* **`-w 0:0`** -> -w local_tun[:remote_tun] -> Solicita el reenvío de dispositivos de túnel con los dispositivos tun especificados entre el cliente (local_tun) y el servidor (remote_tun). enlace los túneles especificados.
* **`-c aes256-ctr`** -> Selección **opcional** de cifrado.

### Hacer que pase todo el tráfico por el tunerl
Activar en el kernel el reenvío de paquetes
```bash
echo '1' > /proc/sys/net/ipv4/ip_forward
```

Activar NAT con iptables
```bash
iptables -t nat -A POSTROUTING -j MASQUERADE 
```


## Enlaces
* https://geekland.eu/establecer-un-tunel-ssh/
* https://geekland.eu/que-es-y-para-que-sirve-un-tunel-ssh/
* https://blog.desdelinux.net/como-crear-un-tunel-ssh-entre-un-servidor-linux-y-un-cliente-windows/
* VPN con ssh => https://www.youtube.com/watch?v=NxUga9g7xa0