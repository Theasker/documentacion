# OCI CLI - Instalación y Configuración

## Descripción

Guía para instalar y configurar el Oracle Cloud Infrastructure (CLI) CLI en Ubuntu (aarch64/x86_64), permitiendo administrar toda la infraestructura de Oracle Cloud desde consola.

---

## Requisitos previos

- Acceso a la consola web de Oracle Cloud (https://cloud.oracle.com)
- Ubuntu con Python 3.6+
- Acceso terminal

---

## Paso 1: Instalar OCI CLI

### 1.1 Descargar el instalador

```bash
mkdir -p ~/.oci-install
cd ~/.oci-install
curl -LO https://raw.githubusercontent.com/oracle/oci-cli/master/scripts/install/install.sh
```

### 1.2 Ejecutar el instalador

```bash
bash ~/.oci-install/install.sh --install-dir ~/.local --exec-dir ~/.local/bin --accept-all-defaults
```

### 1.3 Añadir al PATH

```bash
echo 'export PATH="$HOME/.local/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
```

### 1.4 Verificar instalación

```bash
oci --version
```

---

## Paso 2: Generar par de claves API

```bash
mkdir -p ~/.oci

# Generar clave privada RSA (2048 bits)
openssl genrsa -out ~/.oci/oci_api_key.pem 2048

# Extraer clave pública
openssl rsa -pubout -in ~/.oci/oci_api_key.pem -out ~/.oci/oci_api_key_public.pem

# Permisos correctos
chmod 600 ~/.oci/oci_api_key.pem
chmod 644 ~/.oci/oci_api_key_public.pem
```

Ver la clave pública:

```bash
cat ~/.oci/oci_api_key_public.pem
```

---

## Paso 3: Subir clave pública a OCI Console

1. Entrar en https://cloud.oracle.com
2. Click en tu **perfil** (icono arriba a la derecha) → **User Settings**
3. Ir a **API Keys** → **Add API Key**
4. Seleccionar **Paste a public key**
5. Pegar el contenido completo de `oci_api_key_public.pem`
6. Click en **Add**
7. **Copiar el Fingerprint** que aparece en pantalla

---

## Paso 4: Obtener los OCIDs necesarios

### User OCID
OCI Console → Perfil → **User Settings** → copiar el OCID (empieza por `ocid1.user.oc1..`)

### Tenancy OCID
OCI Console → Perfil → **Tenancy** → copiar el OCID (empieza por `ocid1.tenancy.oc1..`)

### Región
Aparece en la barra superior de OCI Console. Códigos comunes:
- `eu-marseille-1` → Marsella (MRS)
- `eu-madrid-1` → Madrid (MAD)
- `us-ashburn-1` → Ashburn (US)
- `eu-frankfurt-1` → Frankfurt

---

## Paso 5: Crear archivo de configuración

Crear `~/.oci/config`:

```ini
[DEFAULT]
user=ocid1.user.oc1..<TU_USER_OCID>
fingerprint=<TU_FINGERPRINT>
tenancy=ocid1.tenancy.oc1..<TU_TENANCY_OCID>
region=<TU_REGION>
key_file=/home/<TU_USUARIO>/.oci/oci_api_key.pem
```

Establecer permisos:

```bash
chmod 600 ~/.oci/config
```

---

## Paso 6: Verificar funcionamiento

```bash
# Listar compartments
oci iam compartment list --all

# Listar instancias
oci compute instance list --compartment-id <COMPARTMENT_OCID> --all

# Listar VCNs
oci network vcn list --compartment-id <COMPARTMENT_OCID> --all
```

---

## Comandos útiles

### Instancias

```bash
oci compute instance list --compartment-id <OCID> --all
oci compute instance get --instance-id <INSTANCE_OCID>
oci compute instance action --instance-id <INSTANCE_OCID> --action SOFTSTOP
oci compute instance action --instance-id <INSTANCE_OCID> --action START
oci compute instance terminate --instance-id <INSTANCE_OCID>
```

### Redes

```bash
oci network vcn list --compartment-id <OCID> --all
oci network subnet list --compartment-id <OCID> --vcn-id <VCN_OCID> --all
oci network security-list list --compartment-id <OCID> --vcn-id <VCN_OCID> --all
oci network nsg list --compartment-id <OCID> --all
```

### Almacenamiento

```bash
oci bv boot-volume list --compartment-id <OCID> --all
oci bv volume list --compartment-id <OCID> --all
oci os bucket list --compartment-id <OCID>
```

### Identidad

```bash
oci iam compartment list --all
oci iam user list --all
oci iam group list --all
oci iam policy list --compartment-id <OCID> --all
```

### Consultas

```bash
oci search resource free-text-search --text "nombre-recurso"
oci search resource structured-search --query "query instance resources where displayName = 'mi-servidor'"
```

---

## Solución de problemas

### Warning de seguridad de la clave

```bash
echo "OCI_API_KEY" >> ~/.oci/oci_api_key.pem
```

O suprimir:

```bash
export SUPPRESS_LABEL_WARNING=True
echo 'export SUPPRESS_LABEL_WARNING=True' >> ~/.bashrc
```

### Error de permisos

```bash
chmod 600 ~/.oci/config
chmod 600 ~/.oci/oci_api_key.pem
```

### Error "NotAuthenticated" o "InvalidService"

- Verificar fingerprint, user OCID, región
- Verificar que la clave pública subida coincide con la privada local

---

## Notas de seguridad

- **NUNCA** compartir la clave privada (`oci_api_key.pem`)
- **NUNCA** subir la clave privada a repositorios git
- La clave pública es la que se sube a OCI Console
- `~/.oci/config` contiene datos sensibles → permisos `600`
