# OVHcloud CLI - Configuración y Uso

## Descripción

Guía para configurar y usar la CLI de OVHcloud (`ovhcloud`) para gestionar DNS, dominios y servicios de OVH desde consola.

---

## Instalación

### Linux (aarch64/x86_64)

```bash
# Versión actual
ovhcloud version

# Actualizar (si es binario estático, descargar nueva versión)
curl -LO https://github.com/ovh/ovhcloud-cli/releases/latest/download/ovhcloud-linux-arm64
chmod +x ovhcloud-linux-arm64
sudo mv ovhcloud-linux-arm64 /usr/local/bin/ovhcloud
```

---

## Autenticación

### Credenciales necesarias

La CLI necesita 3 valores que se obtienen en https://www.ovh.com/auth/api/createToken:

| Variable | Descripción |
|----------|-------------|
| `OVH_ENDPOINT` | Endpoint API (`ovh-eu` para Europa) |
| `OVH_APPLICATION_KEY` | Application Key (identificador de la app) |
| `OVH_APPLICATION_SECRET` | Application Secret (secreto de la app) |
| `OVH_CONSUMER_KEY` | Consumer Key (token de acceso, caduca) |

### Paso 1: Crear aplicación OVH (solo una vez)

1. Ve a https://www.ovh.com/auth/api/createToken
2. Crea una aplicación y obtén:
   - Application Key
   - Application Secret

### Paso 2: Crear Consumer Key

El consumer key caduca y necesita validación desde navegador:

```bash
source ~/.ovh_env

curl -s -X POST "https://eu.api.ovh.com/1.0/auth/credential" \
  -H "X-Ovh-Application: $OVH_APPLICATION_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "accessRules": [
      {"method": "GET", "path": "/domain/*"},
      {"method": "POST", "path": "/domain/*"},
      {"method": "PUT", "path": "/domain/*"},
      {"method": "DELETE", "path": "/domain/*"}
    ]
  }'
```

La respuesta incluye:
- `consumerKey`: tu nuevo consumer key
- `validationUrl`: URL que debes abrir en navegador para validar

**Valida abriendo la URL en tu navegador antes de que caduque.**

### Paso 3: Configurar entorno

Crear `~/.ovh_env`:

```bash
export OVH_ENDPOINT="ovh-eu"
export OVH_APPLICATION_KEY="<tu-application-key>"
export OVH_APPLICATION_SECRET="<tu-application-secret>"
export OVH_CONSUMER_KEY="<tu-consumer-key>"
```

Crear `~/.config/ovhcloud/config.yaml`:

```yaml
endpoint: eu.api.ovh.com
application_key: "<tu-application-key>"
application_secret: "<tu-application-secret>"
consumer_key: "<tu-consumer-key>"
```

### Paso 4: Verificar

```bash
source ~/.ovh_env
ovhcloud domain-zone list
```

---

## Gestión de DNS

### Dominios disponibles

| Zona | Servidor DNS |
|------|-------------|
| `theasker.ovh` | dns108.ovh.net / ns108.ovh.net |
| `theasker.org.es` | dns19.ovh.net / ns19.ovh.net |
| `mauriciosegura.org.es` | dns19.ovh.net / ns19.ovh.net |

### Subdominios configurados (theasker.ovh)

#### IP 144.24.194.24 (VPS secundario)
`(raiz)`, `instancia`, `nginx`, `codeserver`, `freshrss`, `uptime`, `wiki`, `vaultwarden`, `linkding`, `gotify`, `pruebas`, `filezilla`, `ssh`, `logs`, `n8n`, `ereader`, `git`, `python`, `bbdd`, `angular`, `draw`, `memos`, `watch`, `id`, `php`

#### IP 129.151.225.48 (VPS principal - PoderEuropeo)
`poder`, `firefox`, `termix`, `silverbullet`, `rubencode`, `webtop`, `logseq`, `localstack`, `sql`, `coder`, `wgpoder`, `home`, `ines`

#### IPs locales / otros
`casa` → 79.117.135.218, `pihole` → 88.6.86.113, `mp3` → 88.6.86.113, `wireguard` → 88.6.86.113, `torrents` → 88.6.86.113, `sync` → 88.6.86.113, `fotos` → 88.6.83.172

#### Otros servidores
`rustfs`, `traefik`, `nginx2`, `filebrowser` → 35.225.26.104

---

## Comandos DNS

### Zonas

```bash
# Listar zonas
ovhcloud domain-zone list

# Obtener detalles de una zona
ovhcloud domain-zone get <zona>

# Refrescar zona (aplicar cambios)
ovhcloud domain-zone refresh <zona>
```

### Registros

```bash
# Listar todos los registros de una zona
ovhcloud domain-zone record list <zona>

# Listar solo registros A
ovhcloud domain-zone record list <zona> -o json | jq '.[] | select(.fieldType=="A") | {sub: .subDomain, ip: .target}'

# Crear registro A
ovhcloud domain-zone record create <zona> \
  --sub-domain <subdominio> \
  --field-type A \
  --target <IP> \
  --ttl 60

# Crear registro CNAME
ovhcloud domain-zone record create <zona> \
  --sub-domain <subdominio> \
  --field-type CNAME \
  --target <destino> \
  --ttl 300

# Crear registro TXT
ovhcloud domain-zone record create <zona> \
  --sub-domain <subdominio> \
  --field-type TXT \
  --target "texto del registro" \
  --ttl 300

# Eliminar registro (necesitas el ID)
ovhcloud domain-zone record delete <zona> --id <RECORD_ID>

# Actualizar registro
ovhcloud domain-zone record edit <zona> --id <RECORD_ID> --target <NUEVA_IP>
```

### IDs de registros

Para obtener el ID de un registro (necesario para editar/eliminar):

```bash
ovhcloud domain-zone record list <zona> -o json | jq '.[] | select(.subDomain=="home") | .id'
```

---

## Ejemplos prácticos

### Crear un nuevo subdominio

```bash
source ~/.ovh_env

# Crear subdominio que apunte a tu VPS
ovhcloud domain-zone record create theasker.ovh \
  --sub-domain miapp \
  --field-type A \
  --target 129.151.225.48 \
  --ttl 60

# Refrescar la zona
ovhcloud domain-zone refresh theasker.ovh

# Verificar propagación
dig +short miapp.theasker.ovh
```

### Apuntar subdominio a otra máquina

```bash
# Cambiar la IP de un subdominio existente
# Primero obtener el ID
ID=$(ovhcloud domain-zone record list theasker.ovh -o json | python3 -c "
import json,sys
data = json.load(sys.stdin)
for r in data:
    if r.get('subDomain') == 'nuevaapp':
        print(r['id'])
")

# Editar el registro
ovhcloud domain-zone record edit theasker.ovh --id $ID --target 1.2.3.4

# Refrescar
ovhcloud domain-zone refresh theasker.ovh
```

### Crear subdominio con CNAME

```bash
ovhcloud domain-zone record create theasker.ovh \
  --sub-domain redireccion \
  --field-type CNAME \
  --target otro-dominio.ejemplo.com. \
  --ttl 300

ovhcloud domain-zone refresh theasker.ovh
```

---

## Gestión de dominios

```bash
# Listar dominios
ovhcloud domain-name list

# Detalles de un dominio
ovhcloud domain-name get <dominio>

# Transferir dominio (obtener auth code)
ovhcloud domain-name transfer-out --domain <dominio>
```

---

## Certificados SSL

```bash
# Listar certificados
ovhcloud ssl list

# Obtener certificado
ovhcloud ssl get --service <nombre-servicio>

# Crear certificado
ovhcloud ssl create --domain <dominio>
```

---

## Logs Data Platform (LDP)

```bash
# Listar streams
ovhcloud ldp stream list --service <nombre-servicio>

# Crear stream
ovhcloud ldp stream create --service <nombre-servicio> --name "mi-stream"

# Listar indices
ovhcloud ldp index list --service <nombre-servicio>

# Buscar logs
ovhcloud ldp search --service <nombre-servicio> --query "message:error" --start "2026-01-01T00:00:00Z" --end "2026-01-02T00:00:00Z"
```

---

## Solución de problemas

### Consumer key caducado

Si obtienes error 403 `Client::Forbidden`:

```bash
# Crear nuevo consumer key
source ~/.ovh_env
curl -s -X POST "https://eu.api.ovh.com/1.0/auth/credential" \
  -H "X-Ovh-Application: $OVH_APPLICATION_KEY" \
  -H "Content-Type: application/json" \
  -d '{"accessRules": [{"method": "GET", "path": "/domain/*"}, {"method": "POST", "path": "/domain/*"}, {"method": "PUT", "path": "/domain/*"}, {"method": "DELETE", "path": "/domain/*"}]}'

# Validar desde navegador la URL devuelta
# Luego actualizar el consumer key en:
# - ~/.ovh_env
# - ~/.config/ovhcloud/config.yaml
```

### DNS no se propaga

1. Verificar con `dig +short subdominio.dominio.com`
2. Refrescar zona: `ovhcloud domain-zone refresh <zona>`
3. Esperar hasta 24h para propagación completa (normalmente minutos)

### Verificar permisos del consumer key

El consumer key necesita permisos de `GET`, `POST`, `PUT`, `DELETE` sobre `/domain/*`. Si falta alguno, los errores serán 403.
