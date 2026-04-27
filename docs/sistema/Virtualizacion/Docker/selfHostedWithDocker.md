# Docker selfhosted

* pawelmalak/snippet-box: Snippet Box is a simple self-hosted app for organizing your code snippets
* composerize: Convierte comando docker client a fichero docker-compose
* filebrowser/filebrowser: File Browser provides a file managing interface within a specified directory and it can be used to upload, delete, preview, rename and edit your files. It allows the creation of multiple users and each user can have its own directory.
* diun: Nos avisa cuando alguna de nuestros contenedores docker se ha actulizado en el docker registry
* Duplicati: un contenedor que os permitirá automatizar las copias de seguridad de vuestros contenedores visualmente a través del navegador y volcarlas donde queráis, incluido Google Drive
* docker-autoheal: Monitor and restart unhealthy docker containers.
* b4bz/homer: A dead simple static HOMe for your servER to keep your services on hand from a simple yaml config. 
* tailscale/tailscale: Tailscale lets you connect your devices and users together in your own secure virtual private network. Tailscale enables encrypted point-to-point connections using the open source WireGuard protocol.
* photoprism/photoprism: PhotoPrism® is an AI-Powered Photos App for the Decentralized Web. It makes use of the latest technologies to tag and find pictures automatically without getting in your way. You can run it at home, on a private server, or in the cloud.
* Firefly III: is a (self-hosted) manager for your personal finances. 
* Snippet Box: guarda tus trozos de código
* Para controlar desde consola los contenedores docker:
    ```sh
    docker run -it --rm -v /var/run/docker.sock:/var/run/docker.sock lirantal/dockly
    ```
* https://github.com/cncf/landscape2?tab=readme-ov-file --> Crea una landpage de nuestros servicios
* https://hub.docker.com/u/kasmweb => Contendores docker de escritorios linux
  * https://www.kasmweb.com/docs/latest/guide/custom_images.html
  * https://github.com/kasmtech/workspaces-images
* https://docs.techdox.nz/ => Muchas ideas para hacer con docker
* https://elblogdelazaro.org/posts/2024-10-28-mi_servidor_nas_en_2024/

*   ~[AdGuard-Home](https://elblogdelazaro.org/posts/2024-03-11-adguard-home-doh-en-unraid/): Solución para eliminar todos los anuncios y proteger tu privacidad en cualquier dispositivo con Windows, macOS, Android o iOS.~
*   [Backrest](https://elblogdelazaro.org/posts/2024-06-24-backrest-una-interfaz-web-para-restic-backup/): Interfaz web y orquestador para copias de seguridad para [restic](https://elblogdelazaro.org/posts/2019-11-28-restic-backups-bien-hechos/).
*   [bitwarden-secure-sync](https://github.com/AronMarinelli/bitwarden-secure-sync): Una herramienta sencilla que se puede utilizar para exportar la bóveda Bitwarden/Vaultwarden a un archivo local de forma periódica.
*   ~[db-backup](https://elblogdelazaro.org/posts/2024-07-29-backrest-backups-en-nubes-s3): Realiza copias de seguridad de servidores CouchDB, InfluxDB, MySQL/MariaDB, Microsoft SQL, MongoDB, Postgres, Redis.~
*   [ddns-updater](https://elblogdelazaro.org/posts/2023-03-27-actualizar-dns-dinamicos-con-ddns-updater/): Programa para mantener actualizados los registros DNS A y/o AAAA para múltiples proveedores de DNS.
*   [Gotify](https://gotify.net/): Servidor para el envío y recepción de mensajes
*   [Homebox](https://github.com/hay-kot/homebox): Ayuda a gestionar el inventario y la organización del hogar.
*   [Homepage](https://elblogdelazaro.org/posts/2023-06-21-docker-homepage-dashboard/): Página de inicio altamente personalizable, integrado con más de 100 servicios API.
*   [Immich](https://immich.app/): Solución de copia de seguridad de fotografías y vídeos autohospedada de alto rendimiento.
*   [Immich-Kiosk](https://github.com/damongolding/immich-kiosk): Presentación de diapositivas ligeras para correr en dispositivos de quiosco y navegadores que utiliza Immich como fuente de datos.
*   [Jellyfin](https://elblogdelazaro.org/tags/jellyfin/): Centro multimedia y multiplataforma para visualizar contenido desde cualquier tipo de dispositivo vía Web de código abierto.
*   [Jellyseerr](https://elblogdelazaro.org/posts/2023-02-06-universo-arr-parte-vi-jellyseerr/): Clon de Overseerr pero para Jellyfin/Emby, realiza búsquedas o recomendaciones.
*   [Lidarr](https://elblogdelazaro.org/posts/2023-01-09-universo-arr-arte-iii-lidarr/): Administrar nuestra colección de música, realiza un seguimiento de los álbumes de cada artista.
*   [Linkwarden](https://linkwarden.app/): Gestor de marcadores colaborativo de código abierto para recopilar, organizar y preservar páginas web.
*   [LubeLogger](https://elblogdelazaro.org/posts/2024-08-26-mantenimiento-de-tu-vehiculo-con-lubelogger): Lleva el mantenimiento, gastos de combustible y kilometraje de vehículos desde una interfaz web.
*   [Medama](https://oss.medama.io/introduction): Proyecto de código abierto dedicado al análisis de sitios web auto-alojados y sin cookies ofrece análisis en tiempo, prioriza la privacidad del usuario.
*   [Miniflux](https://elblogdelazaro.org/posts/2023-06-05-unraid-instalacion-de-mniflux/) : Lector de RSS ligero, sencillo y bastante rápido.
*   ~[Navidrome](https://www.navidrome.org/): Servicio de streaming de música rápido, ligero y compatible con Subsonic.~
*   [Nginx-Proxy-Manager-Official](https://elblogdelazaro.org/posts/2023-04-10-alojar-un-sitio-web-estatico-con-nginx-proxy-manager/): Proxy inverso y permitir la solicitud/renovación de certificaos de diferentes entidades.
*   [Paperless-ngx](https://elblogdelazaro.org/posts/2024-04-01-instalacion-de-paperlees-ngx-en-unraid/): Sistema de gestión de documentos que transforma tus documentos físicos en documentos electrónicos.
*   [Pgadmin4](https://elblogdelazaro.org/posts/2023-05-29-docker-gestion-de-bbdd-postgres-con-pgadmin/): Pplataforma de gestión y desarrollo basada en web para PostgreSQL,
*   [PGBackweb](https://github.com/eduardolat/pgbackweb): Copias de seguridad de mis BBDD en PostgreSQL con una interfaz web fácil de usar.
*   [PostgreSQL\_Immich](https://registry.hub.docker.com/_/postgres/): Este contenedor (basado en PostgreSQL 16) está específicamente configurado para una integración perfecta con el contenedor [Immich](https://immich.app/).
*   ~[Pingvin-share](https://github.com/stonith404/pingvin-share): Intercambio de archivos, alternativa a WeTransfer.~
*   [Prowlarr](https://elblogdelazaro.org/posts/2023-01-02-universo-arr-parte-ii-prowlarr/): Indexador o buscador de torrents en diferentes trackers, tanto públicos como privados.
*   [Qbittorrent](https://elblogdelazaro.org/posts/2022-12-12-rutorrent-y-qbittorrent-mediante-docker/): Cliente para la descarga de torrents.
*   [Radarr](https://elblogdelazaro.org/posts/2023-01-23-universo-arr-parte-v-radarr/): Descarga y gestiona películas
*   [Radicale](https://elblogdelazaro.org/posts/2019-02-15-sincroniza-tu-calendario-con-org-caldav/): Servidor CalDAV y CardDAV
*   [Redis](https://redis.io/): Motor de base de datos en memoria
*   [SearXNG](https://elblogdelazaro.org/posts/2024-06-03-searxng-aloja-tu-propio-buscador-en-unRaid): Motor de búsqueda gratuito, resultados de más de 70 servicios de búsqueda, los usuarios no son rastreados ni perfilados.
*   [Sonarr](https://elblogdelazaro.org/posts/2023-01-16-universo-arr-parte-iv-sonarr/): Administra la colección de series realiza un seguimiento de los episodios, y de manera automática realiza una búsqueda de los episodios
*   [Syncthing](https://elblogdelazaro.org/tags/syncthing/): Sincroniza archivos entre dispositivos en una red local, o entre dispositivos remotos a través de Internet.
*   [Umami](https://elblogdelazaro.org/posts/2023-06-26-unraid-instalacion-de-umami/): Alternativa simple, rápida y centrada en la privacidad a Google Analytics
*   [unraid-simple-monitoring-api](https://github.com/NebN/unraid-simple-monitoring-api): Simple REST API para monitorizar métricas básicas del servidor Unraid en [Homepage](https://elblogdelazaro.org/posts/2023-06-21-docker-homepage-dashboard/).
*   [Vaultwarden](https://hub.docker.com/r/vaultwarden/server): Implementación alternativa del servidor Bitwarden
*   [Viseron](https://viseron.netlify.app/): Software NVR con AI Computer Vision. Con características como detección de objetos, detección de movimiento, reconocimiento facial y más.