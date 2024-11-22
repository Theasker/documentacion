| Modelo OSI      |-| Modelo TCP/IP   |
|:---------------:|-|:---------------:|
| Aplicación      |-|                 |
| Presentación    |-| Aplicación      |
| Sesión          |-|                 |
| Transporte      |-| Transporte      |
| Red             |-| Internet        |
| Enlace de datos |-|                 |
| Red             |-| Acceso a Red    |



https://www.youtube.com/watch?v=ODY4q4_3Acc&t=194s

## Modelo OSI

1. **Nivel Físico**
Interconexión de los sistemas en red. Cables, conectores
2. **Enlace de datos**
Switch, bridges, standard 802.3 (Ethernet) y 802.11 (WiFi). Definen los enlaces de datos alámbricos e inalámbricos. Hace que se produzca la transferencia de datos entre 2 sistemas. Hay 2 subcapas:
    1. **LLC - Control de Enlace lógico**: Su función es convertir la señal en 1 y 0
    2. **MAC - Control de acceso al medio**: Mueve los paquetes de datos de una tarjeta de red a otra tarjeta de red y puede corregir errores.
3. **Red**
Las direcciones IP.El enrutamiento de los routers o switches de capa 3. Una posible incidencia puede ser la tardanza en llegar de los paquetes de datos. Protocolos:
    * OSPF: Determina el mejor camino que tienen que seguir los datos para llegar a su destino.
    * IP
    * IPSec
    * ARP
    * NAT
    * ICMP

4. **Transporte**
Protocolos TCP y UDP. Una posible incidencia de esta capa es que los puertos por donde entran los datos, no estén abiertos. Se deciden los puertos a utilizar en la comunicación.
5. **Sesión**
   * NetBIOS
   * RPC - Comunica procesos e intercambia información
   * PPTP (vpn)
   * PAP

6. **Presentación**
   Encriptado y descifrado. Presenta los datos de las capas inferiores.
7. **Aplicación**
Comunicación entre la aplicación y la red. Navegadores web
   * HTTPs
   * DNS
   * FTP
   * SMTP