# Oracle Cloud Infrastructure - Plan Always Free

## Descripción

Guía de recursos disponibles en el plan **Always Free** de OCI y comandos para gestionarlos.

---

## Recursos Always Free

### Compute

| Recurso | Especificación |
|---------|---------------|
| **VM.Standard.E2.1.Micro** | Hasta 2 instancias, 1 OCPU, 1 GB RAM cada una |
| **VM.Standard.A1.Flex** | Hasta 4 OCPUs y 24 GB RAM total (ARM) |
| **Boot volume** | Hasta 200 GB total entre todas las instancias |
| **Volumes de bloque** | Hasta 100 GB total, 10 VPUs/GB |

### Almacenamiento

| Recurso | Especificación |
|---------|---------------|
| **Object Storage** | 10 GB de almacenamiento estándar |
| **Block Volume** | 100 GB total (incluido en boot volumes) |
| **Archive Storage** | 10 GB |

### Redes

| Recurso | Especificación |
|---------|---------------|
| **VCNs** | Ilimitadas |
| **Security Lists / NSGs** | Ilimitadas |
| **Load Balancer** | 1 Load Balancer (10 Mbps) |
| **Public IPs** | 1 IP pública por instancia |
| **Outbound data transfer** | 10 TB/mes |

### Bases de datos

| Recurso | Especificación |
|---------|---------------|
| **Autonomous Database** | Hasta 2 instancias (20 GB cada una) |
| **MySQL Database System** | 1 instancia (8 GB RAM, 50 GB storage) |

### Otros

| Recurso | Especificación |
|---------|---------------|
| **Monitoring** | 500,000 métricas/mes |
| **Notifications** | 1,000 emails/mes |
| **DNS** | 1 zona DNS gestionada |

---

## Comandos de gestión

### Instancias

```bash
# Listar todas las instancias
oci compute instance list --compartment-id <TENANCY_OCID> --all

# Detalles de una instancia
oci compute instance get --instance-id <INSTANCE_OCID>

# Ver VNICs e IPs de una instancia
oci compute instance list-vnics --instance-id <INSTANCE_OCID>

# Iniciar instancia
oci compute instance action --instance-id <INSTANCE_OCID> --action START

# Parar instancia (graceful)
oci compute instance action --instance-id <INSTANCE_OCID> --action SOFTSTOP

# Parar instancia (forzado)
oci compute instance action --instance-id <INSTANCE_OCID> --action STOP

# Reiniciar instancia
oci compute instance action --instance-id <INSTANCE_OCID> --action SOFTRESET

# Terminar instancia
oci compute instance terminate --instance-id <INSTANCE_OCID>

# Cambiar shape (ej: escalar ARM)
oci compute instance update --instance-id <INSTANCE_OCID> --shape VM.Standard.A1.Flex --shape-config '{"ocpus": 4, "memoryInGbs": 24}'
```

### Crear instancia Always Free (ARM)

```bash
oci compute instance launch \
  --compartment-id <TENANCY_OCID> \
  --availability-domain <AD> \
  --shape VM.Standard.A1.Flex \
  --shape-config '{"ocpus": 4, "memoryInGbs": 24}' \
  --image-id <IMAGE_OCID> \
  --subnet-id <SUBNET_OCID> \
  --display-name "mi-servidor" \
  --ssh-authorized-keys-file ~/.ssh/id_rsa.pub
```

### Volúmenes de bloque

```bash
# Listar boot volumes
oci bv boot-volume list --compartment-id <TENANCY_OCID> --all

# Listar volúmenes de bloque
oci bv volume list --compartment-id <TENANCY_OCID> --all

# Crear volumen (Always Free: hasta 100 GB total)
oci bv volume create \
  --compartment-id <TENANCY_OCID> \
  --availability-domain <AD> \
  --size-in-gbs 50 \
  --display-name "mi-volumen"

# Adjuntar volumen a instancia
oci compute volume-attachment attach \
  --instance-id <INSTANCE_OCID> \
  --volume-id <VOLUME_OCID> \
  --type paravirtualized

# Desadjuntar volumen
oci compute volume-attachment detach --volume-attachment-id <ATTACHMENT_OCID>

# Eliminar volumen
oci bv volume delete --volume-id <VOLUME_OCID>

# Cambiar rendimiento (VPUs/GB)
oci bv volume update --volume-id <VOLUME_OCID> --vpus-per-gb 10
```

### Redes

```bash
# Listar VCNs
oci network vcn list --compartment-id <TENANCY_OCID> --all

# Crear VCN
oci network vcn create \
  --compartment-id <TENANCY_OCID> \
  --cidr-block "10.0.0.0/16" \
  --display-name "mi-vcn" \
  --dns-label "mivcn"

# Crear subnet
oci network subnet create \
  --compartment-id <TENANCY_OCID> \
  --vcn-id <VCN_OCID> \
  --cidr-block "10.0.1.0/24" \
  --display-name "mi-subnet" \
  --dns-label "misubnet"

# Listar security lists
oci network security-list list --compartment-id <TENANCY_OCID> --vcn-id <VCN_OCID> --all

# Añadir regla de ingress (ej: abrir puerto 8080)
oci network security-list update \
  --security-list-id <SL_OCID> \
  --ingress-security-rules '[
    {
      "description": "Mi servicio",
      "protocol": "6",
      "source": "0.0.0.0/0",
      "source-type": "CIDR_BLOCK",
      "is-stateless": false,
      "tcp-options": {
        "destination-port-range": {"max": 8080, "min": 8080}
      }
    }
  ]' \
  --force

# Listar NSGs
oci network nsg list --compartment-id <TENANCY_OCID> --all

# Crear NSG
oci network nsg create \
  --compartment-id <TENANCY_OCID> \
  --vcn-id <VCN_OCID> \
  --display-name "mi-nsg"

# Añadir regla a NSG
oci network nsg rules add \
  --nsg-id <NSG_OCID> \
  --security-rules '[
    {
      "description": "Mi servicio",
      "direction": "INGRESS",
      "protocol": "6",
      "source": "0.0.0.0/0",
      "source-type": "CIDR_BLOCK",
      "tcp-options": {
        "destination-port-range": {"max": 8080, "min": 8080}
      }
    }
  ]'

# Asociar NSG a VNIC
oci compute vnic-attachment update \
  --vnic-attachment-id <VNIC_ATTACHMENT_OCID> \
  --nsg-ids '["<NSG_OCID>"]'
```

### Object Storage

```bash
# Listar buckets
oci os bucket list --compartment-id <TENANCY_OCID>

# Crear bucket
oci os bucket create \
  --compartment-id <TENANCY_OCID> \
  --name mi-bucket \
  --public-access-type NoPublicAccess

# Subir archivo
oci os object put \
  --bucket-name mi-bucket \
  --file /ruta/al/archivo

# Listar objetos
oci os object list --bucket-name mi-bucket

# Descargar archivo
oci os object get \
  --bucket-name mi-bucket \
  --name archivo.txt \
  --file ./archivo.txt

# Eliminar objeto
oci os object delete --bucket-name mi-bucket --name archivo.txt
```

### DNS

```bash
# Listar zonas
oci dns zone list --compartment-id <TENANCY_OCID>

# Crear zona
oci dns zone create \
  --compartment-id <TENANCY_OCID} \
  --name ejemplo.com \
  --zone-type PRIMARY

# Listar registros
oci dns record zone list --zone-name-or-id ejemplo.com

# Añadir registro A
oci dns record rrset update \
  --zone-name-or-id ejemplo.com \
  --domain subdominio.ejemplo.com \
  --rtype A \
  --items '[{"domain": "subdominio.ejemplo.com", "rdata": "1.2.3.4", "ttl": 300}]' \
  --force
```

### Load Balancer (Always Free: 10 Mbps)

```bash
# Crear load balancer
oci lb load-balancer create \
  --compartment-id <TENANCY_OCID> \
  --display-name "mi-lb" \
  --shape-name "10Mbps" \
  --subnet-ids '["<SUBNET_OCID>"]'

# Crear backend set
oci lb backend-set create \
  --load-balancer-id <LB_OCID> \
  --name "mi-backend-set" \
  --policy ROUND_ROBIN \
  --health-checker-protocol HTTP \
  --health-checker-url-path "/" \
  --health-checker-port 80

# Añadir backend
oci lb backend create \
  --load-balancer-id <LB_OCID> \
  --backend-set-name "mi-backend-set" \
  --ip-address 10.0.0.10 \
  --port 80 \
  --weight 1

# Crear listener
oci lb listener create \
  --load-balancer-id <LB_OCID> \
  --name "mi-listener" \
  --backend-set-name "mi-backend-set" \
  --port 443 \
  --protocol HTTP
```

### Autonomous Database (Always Free)

```bash
# Listar databases
oci db autonomous-database list --compartment-id <TENANCY_OCID> --all

# Crear Autonomous Database (Always Free)
oci db autonomous-database create \
  --compartment-id <TENANCY_OCID} \
  --display-name "mi-adb" \
  --db-name "MIADB" \
  --admin-password "MiPassword123!" \
  --cpu-core-count 1 \
  --data-storage-size-in-tbs 1 \
  --db-workload OLTP \
  --is-free-tier true

# Obtener wallet (credenciales)
oci db autonomous-database generate-wallet \
  --autonomous-database-id <ADB_OCID> \
  --password "MiPassword123!" \
  --file wallet.zip
```

### Monitoreo y alertas

```bash
# Listar métricas disponibles
oci monitoring metric list \
  --compartment-id <TENANCY_OCID> \
  --namespace oci_computeagent

# Obtener datos de métricas
oci monitoring metric-data summarize-metrics-data \
  --compartment-id <TENANCY_OCID> \
  --namespace oci_computeagent \
  --query-text 'CpuUtilization[1m]{resourceId = "<INSTANCE_OCID>"}.mean()'

# Listar alarmas
oci monitoring alarm list --compartment-id <TENANCY_OCID>
```

### Identidad y acceso

```bash
# Listar compartments
oci iam compartment list --all

# Crear compartment
oci iam compartment create \
  --compartment-id <TENANCY_OCID> \
  --name "mi-compartment" \
  --description "Mi compartment"

# Listar usuarios
oci iam user list --all

# Listar grupos
oci iam group list --all

# Listar políticas
oci iam policy list --compartment-id <TENANCY_OCID> --all

# Crear política
oci iam policy create \
  --compartment-id <TENANCY_OCID> \
  --name "mi-politica" \
  --description "Mi política" \
  --statements '["Allow group MiGroup to manage virtual-network-family in compartment MiCompartment"]'
```

### Presupuesto y costos

```bash
# Listar presupuestos
oci budget budget list --compartment-id <TENANCY_OCID>

# Crear presupuesto (alerta al 80%)
oci budget budget create \
  --compartment-id <TENANCY_OCID> \
  --display-name "presupuesto-mensual" \
  --target-type COMPARTMENT \
  --targets '["<COMPARTMENT_OCID>"]' \
  --amount 100 \
  --reset-period MONTHLY

# Crear alerta de presupuesto
oci budget alert-rule create \
  --budget-id <BUDGET_OCID> \
  --type ACTUAL \
  --threshold 80 \
  --threshold-type PERCENTAGE
```

---

## Tips para no salir del plan gratuito

1. **Usa shapes Always Free**: `VM.Standard.E2.1.Micro` y `VM.Standard.A1.Flex`
2. **No superes 200 GB** de boot volumes en total
3. **No superes 100 GB** de block volumes adicionales
4. **Usa 1 Load Balancer** de 10 Mbps (no el de 100 Mbps o 1 Gbps)
5. **Monitorea tu uso** con `oci budget` para recibir alertas
6. **Elimina recursos no usados**: IPs públicas no asociadas, volúmenes sin adjuntar, instancias paradas (siguen consumiendo boot volume)
7. **Object Storage**: mantente bajo 10 GB
8. **Data Transfer**: 10 TB/mes de salida es generoso, pero monitoriza si usas CDN o descargas pesadas
