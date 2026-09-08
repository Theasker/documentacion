# VPN Wireguard

La idea es instalar una VPN en casa y usarla con los contenedores de un VPS

## Configuración del servidor VPN
### Instalación de wireguard en la Raspberry PI (servidor)
Instalo Wireguard en la raspberry con docker

`docker-compose.yml`
```yaml
version: "2.1"
services:
  wireguard:
    image: lscr.io/linuxserver/wireguard
    container_name: wireguard
    cap_add:
      - NET_ADMIN
      - SYS_MODULE
    environment:
      - PUID=1000
      - PGID=1000
      - TZ=Europe/Madrid
      - SERVERURL=casa.theasker.ovh #optional
      - SERVERPORT=51820 #optional
      - PEERS=2 # Personas que se pueden conectar
      - PEERDNS=auto #optional
      - INTERNAL_SUBNET= #optional
      - ALLOWEDIPS=0.0.0.0/0 #optional
      - PERSISTENTKEEPALIVE_PEERS= #optional
      - LOG_CONFS=true #optional
    volumes:
      - $PWD/data/config:/config
      - /lib/modules:/lib/modules #optional
    ports:
      - 51820:51820/udp
    dns:
      - 172.18.0.4 #Ip del contenedor PiHole
    sysctls:
      - net.ipv4.conf.all.src_valid_mark=1
      - net.ipv4.ip_forward=1
    restart: unless-stopped
```

Una vez levantado el contenedor, podemos ver que se han creado todos los ficheros de configuración:
```
pi@raspberrypi:/mnt/datos/dockerCompose/wireguard $ tree 
.
├── data
│   └── config
│       ├── coredns
│       │   └── Corefile
│       ├── peer1
│       │   ├── peer1.conf
│       │   ├── peer1.png
│       │   ├── presharedkey-peer1
│       │   ├── privatekey-peer1
│       │   └── publickey-peer1
│       ├── peer2
│       │   ├── peer2.conf
│       │   ├── peer2.png
│       │   ├── presharedkey-peer2
│       │   ├── privatekey-peer2
│       │   └── publickey-peer2
│       ├── server
│       │   ├── privatekey-server
│       │   └── publickey-server
│       ├── templates
│       │   ├── peer.conf
│       │   └── server.conf
│       └── wg0.conf
└── docker-compose.yml
```

En el directorio `config/peer1' tenemos los ficheros de claves privada, pública, el código QR para poder cargar la configuración en otro cliente VPN, etc.

Para ver el código QR de los peers podemos ejecutar el comando de wireguard:
```bash
docker exec -it wireguard /app/show-peer 1
docker exec -it wireguard /app/show-peer 2
```

## Configuración del cliente VPN

## Cliente Wireguard

En el servidor se han creado los directorios de los clientes y la configuración necesaria que voy a copiar al VPS donde va a estar el cliente:
``` bash
scp pi:/mnt/datos/dockerCompose/wireguard/data/config/peer1/peer1.conf data/config/
```

Le cambio el nombre para que tenga el nombre por defecto de configuración de wireguard:

``` bash
cd data/config/
mv peer1.conf wg0.conf
```

## Archivo docker-compose.yml del cliente VPN

```yaml
version: "2.1"
services:
  wg-cli:
    image: lscr.io/linuxserver/wireguard
    container_name: wg-cli
    devices:
      - /dev/net/tun
    cap_add:
      - NET_ADMIN
      - SYS_MODULE
    environment:
      - PUID=1001
      - PGID=1001
      - TZ=Europe/Madrid
    volumes:
      - $PWD/data/wg0.conf:/config/wg0.conf
      - /lib/modules:/lib/modules:ro
    sysctls:
      - net.ipv4.conf.all.src_valid_mark=1
    restart: unless-stopped
    networks:
      - vpn-net

  # alpine-vpn:
  #   depends_on:
  #     - wg-cli
  #   container_name: alpine-vpn
  #   command: ping www.google.com
  #   network_mode: service:wg-cli
  #   image: alpine
  #   stdin_open: true
  #   tty: true

networks:
  vpn-net:
    driver: bridge
```

Todos los contenedores que ahora si quieran contectar y salir por este contenedor y VPN tendrán que tener la líne `network_mode: service:wg-cli`

## Bibliografía

### Wireguard
 * https://docs.linuxserver.io/images/docker-wireguard
 * https://www.linuxserver.io/blog/routing-docker-host-and-container-traffic-through-wireguard
 * https://atareao.es/podcast/mi-configuracion-de-wireguard/
 * https://www.youtube.com/watch?v=R29YBmYxXAk (Wireguard + PiHole - PeladoNerd)
 * https://www.procustodibus.com/blog/2022/02/wireguard-remote-access-to-docker-containers/
 * https://www.procustodibus.com/blog/2020/11/wireguard-point-to-point-config/
 * https://lanpixel.com/blog/guia-wireguard-paso-a-paso-con-mikrotik/
 * https://www.youtube.com/watch?v=EgHtry08zs0 => Instalación y configuración de WIREGUARD 🐍 en Linux con DOCKER🐳
 * https://www.sonicwall.com/support/knowledge-base/how-can-i-set-up-a-wireguard-tunnel-using-a-docker-container/211025104453553/
 * https://www.pedrolamas.com/2020/11/20/how-to-connect-to-a-wireguard-vpn-server-from-a-docker-container/

### Wg-easy
 * https://github.com/WeeJeWel/wg-easy/wiki/Using-WireGuard-Easy-with-Pi-Hole

### Wireguard + PiHole
 * https://github.com/Inushin/dockerPiholeWireguard