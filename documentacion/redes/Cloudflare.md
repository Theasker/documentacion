# Configurar y Administrar Cloudflare Tunnel en un Servidor (VPS o Casa)
#redes #internet #tunel #cloudflare #sysadmin

Esta guía detalla los pasos para exponer cualquier servicio local (servidores web, APIs o aplicaciones) a través de un túnel seguro de Cloudflare, sin necesidad de abrir puertos en el cortafuegos, gestionando múltiples subdominios y realizando operaciones avanzadas de mantenimiento.

---

## Paso 1: Instalar `cloudflared`

Descarga e instala el binario oficial de Cloudflare en tu sistema (ejemplo para arquitecturas Linux AMD64):

```bash
# Crear directorio para binarios si no existe
mkdir -p ~/bin

# Descargar la última versión del binario oficial
curl -L --output ~/bin/cloudflared https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64

# Dar permisos de ejecución
chmod +x ~/bin/cloudflared

# Moverlo a un directorio global para usarlo cómodamente
sudo mv ~/bin/cloudflared /usr/local/bin/cloudflared
```

---

## Paso 2: Autenticarte en Cloudflare

Inicia sesión en tu cuenta de Cloudflare desde la terminal ejecutando el siguiente comando:

```bash
cloudflared tunnel login
```
* **Acción:** Copia la URL que aparece en pantalla, pégala en tu navegador web, inicia sesión y selecciona el dominio principal (zona) que vas a utilizar. Se descargará un certificado inicial (`cert.pem`) automáticamente en `~/.cloudflared/`.

---

## Paso 3: Crear el Túnel

Crea un túnel nuevo con un nombre descriptivo (por ejemplo, `vps-tunel`):

```bash
cloudflared tunnel create vps-tunel
```
* **Acción:** Cloudflare generará un ID único (UUID) y creará un archivo de credenciales `.json` dentro del directorio `~/.cloudflared/`.

---

## Paso 4: Configurar las reglas de tráfico (Ingress) de forma centralizada

Se recomienda centralizar la configuración en el directorio global del sistema (`/etc/cloudflared/`) para que el servicio del sistema pueda leerla sin problemas de permisos:

```bash
sudo mkdir -p /etc/cloudflared
sudo nano /etc/cloudflared/config.yml
```

Añade la siguiente estructura (reemplazando los datos por los tuyos y añadiendo tantas reglas de `ingress` como subdominios necesites):

```yaml
tunnel: <UUID_DEL_TUNEL>
credentials-file: /etc/cloudflared/<UUID_DEL_TUNEL>.json

ingress:
  # Primer servicio (ejemplo: Nginx en puerto 8080)
  - hostname: pruebas.tudominio.com
    service: http://localhost:8080
  
  # Segundo servicio (ejemplo: otro servicio en puerto 8881)
  - hostname: panel.tudominio.com
    service: http://localhost:8881
    
  # Regla de seguridad obligatoria al final para el tráfico no mapeado
  - service: http_status:404
```

---

## Paso 5: Vincular los subdominios en DNS

Asocia cada subdominio con el túnel recién creado para que Cloudflare cree los registros tipo CNAME automáticamente:

```bash
cloudflared tunnel route dns vps-tunel pruebas.tudominio.com
cloudflared tunnel route dns vps-tunel panel.tudominio.com
```
*(Si el registro ya existiera previamente y da error, puedes añadir el parámetro `--overwrite-dns` al final del comando para forzar la sobrescritura).*

---

## Paso 6: Configurar como Servicio del Sistema (Systemd)

Para que el túnel corra de forma persistente en segundo plano y arranque automáticamente con el servidor:

1. **Instalar el servicio apuntando al fichero de configuración global:**
   ```bash
   sudo cloudflared --config /etc/cloudflared/config.yml service install
   ```

2. **Iniciar y habilitar el servicio:**
   ```bash
   sudo systemctl daemon-reload
   sudo systemctl enable --now cloudflared
   ```

3. **Verificar el estado:**
   ```bash
   sudo systemctl status cloudflared
   ```
   *Debe aparecer como `active (running)`.*

---

## Acciones Avanzadas y de Diagnóstico con el Cliente de Cloudflare

Estas operaciones te permitirán auditar, validar y depurar el estado del túnel directamente desde la terminal:

* **Validar la sintaxis del archivo de configuración:**
  Antes de reiniciar el servicio tras hacer cambios en el YAML, puedes comprobar que la estructura es correcta:
  ```bash
  cloudflared tunnel ingress validate
  ```

* **Comprobar el estado y conexiones activas ("Edges"):**
  Muestra cuántas conexiones concurrentes mantiene tu túnel con la red global de Cloudflare y su latencia:
  ```bash
  cloudflared tunnel info vps-tunel
  ```

* **Listar todos los túneles creados en tu cuenta:**
  Permite ver los UUIDs y los nombres de los túneles registrados:
  ```bash
  cloudflared tunnel list
  ```

* **Inspeccionar los logs del servicio en tiempo real:**
  Ideal para ver si las peticiones HTTP externas están llegando correctamente al túnel o si hay errores de comunicación con los puertos locales:
  ```bash
  sudo journalctl -u cloudflared -f
  ```

* **Comprobar qué regla de Ingress procesa una URL concreta:**
  Puedes simular cómo enrutaría `cloudflared` una petición para verificar que tu archivo `config.yml` redirige al puerto adecuado:
  ```bash
  cloudflared tunnel ingress rule http://localhost:8080 pruebas.tudominio.com
  ```