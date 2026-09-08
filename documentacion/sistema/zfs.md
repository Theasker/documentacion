# Guía Técnica de ZFS: Desde la Instalación hasta la Gestión de Datos

Esta guía documenta el proceso de configuración de ZFS en un entorno Linux (Debian) y los conceptos fundamentales para la administración de un NAS profesional.

---

## 1. Instalación y Preparación del Sistema

ZFS no se incluye en el kernel de Debian por motivos de licencia (CDDL). Se instala vía **DKMS** (compilación automática del módulo para tu kernel).

### Configuración de Repositorios
Es necesario habilitar la rama `contrib` de Debian.
```bash
# Añadir componentes contrib y non-free a las fuentes
sudo apt update
sudo apt install software-properties-common
sudo apt-add-repository contrib
sudo apt update
```

### Instalación de Paquetes
```bash
# Instalación de cabeceras del kernel y herramientas de ZFS
sudo apt install linux-headers-amd64 zfs-dkms zfsutils-linux
```

### Carga del Módulo
```bash
# Cargar el driver en el kernel
sudo modprobe zfs

# Verificar carga
lsmod | grep zfs
```
**Salida esperada:**
`zfs                  5132288  0` (y otras dependencias como `zunicode`, `zzstd`, `zlua`, `zcommon`, `znvpair`, `spl`).

---

## 2. Conceptos Arquitectónicos (El "Mindset" ZFS)

Antes de ejecutar comandos, es vital entender la jerarquía:

1.  **ZPOOL (Pool):** La piscina de almacenamiento. Agrupa discos físicos. Gestiona el "espacio crudo".
2.  **VDEV (Virtual Device):** La unidad de redundancia dentro del pool. Un Pool puede tener varios VDEVs. 
    - *Tipos:* Single (sin redundancia), Mirror (RAID 1), RAID-Z1 (RAID 5), RAID-Z2 (RAID 6).
    - **Peligro:** Si un VDEV falla y no tiene redundancia, el POOL completo se pierde.
3.  **DATASET:** El "sistema de ficheros" lógico. Es lo que montas y usas. Se crean dentro del Pool.
4.  **ZVOL:** Emulación de dispositivo de bloque (como un disco virtual) sobre ZFS (usado para iSCSI o VMs).

---

## 3. Gestión de la Capa Física: El ZPOOL

### Creación de un Pool en Espejo (Mirror)
Utilizamos dos discos (`/dev/vdb` y `/dev/vdc`) para garantizar que los datos sobrevivan al fallo de uno de ellos.

```bash
# -f: fuerza la creación (ignora etiquetas previas)
# storage: nombre del pool
# mirror: tipo de redundancia
sudo zpool create -f storage mirror /dev/vdb /dev/vdc
```

### Verificación del Estado
```bash
sudo zpool status
```
**Salida esperada:**
```text
  pool: storage
 state: ONLINE
config:
	NAME        STATE     READ WRITE CKSUM
	storage     ONLINE       0     0     0
	  mirror-0  ONLINE       0     0     0
	    vdb     ONLINE       0     0     0
	    vdc     ONLINE       0     0     0
errors: No known data errors
```
*   **READ/WRITE/CKSUM:** Si estos valores son distintos de 0, hay problemas físicos o corrupción de datos.

---

## 4. Gestión de Datasets (Capa Lógica)

Los datasets son preferibles a las carpetas simples porque permiten aplicar reglas específicas a cada tipo de dato.

### Creación y Organización
```bash
# Crear estructura jerárquica
sudo zfs create storage/documentos
sudo zfs create storage/media
sudo zfs create storage/db
```

### Propiedades Críticas

ZFS gestiona las propiedades de forma dinámica y con herencia (lo que apliques al padre, afecta al hijo).


|Propiedades	    |Comando de ejemplo	                |Explicación                                            |
|-------------------|-----------------------------------|-------------------------------------------------------|
|compression        |zfs set compression=lz4 storage	|Activa LZ4. Recomendado en todo el pool.               |
|Quota	            |zfs set quota=10G storage/backups	|Límite máximo de espacio.                              |
|Reservation	    |zfs set reservation=5G storage/db	|Espacio garantizado para ese dataset.                  |
|Mountpoint	        |zfs set mountpoint=/datos storage	|Cambia dónde se ve el pool en el sistema.              |
|Atime	            |zfs set atime=off storage	        |Mejora rendimiento al no escribir fecha de acce so.    |

### Listado de Sistemas de Ficheros
```bash
zfs list
```
**Salida esperada:**
```text
NAME              USED  AVAIL     REFER  MOUNTPOINT
storage           100K  19.5G       24K  /storage
storage/backups    24K  19.5G       24K  /storage/backups
storage/datos      24K  19.5G       24K  /storage/datos
```
*   **REFER:** Espacio real usado por los datos en ese dataset.
*   **USED:** Espacio total usado (incluyendo snapshots y datasets hijos).

### Diferencia entre Dataset y Directorio

Para un uso práctico y profesional de ZFS, se deben distinguir estas dos capas:

1. **Dataset (Capa de Administración)**: Se crea con `zfs create`. 
   - Se comporta como un sistema de ficheros independiente.
   - Permite aplicar Snapshots, Cuotas y Compresión de forma aislada.
   - *Uso:* Estructura de alto nivel (ej: `storage/multimedia`, `storage/proyectos`, `storage/backups`).

2. **Directorio (Capa de Usuario)**: Se crea con `mkdir`.
   - Se comporta como una carpeta estándar de Linux.
   - Hereda todas las propiedades del Dataset donde reside.
   - *Uso:* Organización interna de archivos (ej: `/storage/multimedia/peliculas/sci-fi`).

**Comando de emergencia:** Si los datasets no aparecen tras un reinicio o creación:
`sudo zfs mount -a` (Monta todos los sistemas de ficheros ZFS gestionados).

---

## 5. Propiedades en Tiempo Real

ZFS permite modificar el comportamiento del sistema de ficheros sin desmontar ni reiniciar.

### Compresión (Recomendado: LZ4)
LZ4 es extremadamente rápido. Si un dato no es comprimible, ZFS lo detecta y lo escribe en crudo sin penalización de CPU.
```bash
# Activar compresión
sudo zfs set compression=lz4 storage/datos

# Verificar
zfs get compression storage/datos
```

### Cuotas y Reservas
Para evitar que un usuario o servicio llene todo el NAS.
```bash
# Limitar el dataset de backups a 10GB
sudo zfs set quota=10G storage/backups

# Asegurar que el dataset de datos siempre tenga al menos 5GB disponibles
sudo zfs set reservation=5G storage/datos
```

---

## 6. Mantenimiento Preventivo

### El comando SCRUB
A diferencia de `fsck`, el `scrub` se hace con el sistema online. Lee todos los datos y verifica sus checksums contra el espejo.
```bash
# Iniciar verificación
sudo zpool scrub storage

# Ver progreso
sudo zpool status storage
```
*Recomendación:* Ejecutar un scrub una vez al mes en discos NAS y una vez a la semana en discos de escritorio.

---

## 7. Buenas Prácticas (Resumen Técnico)

1.  **No usar RAID por Hardware:** ZFS necesita acceso directo a los discos (HBA en modo IT) para gestionar la recuperación de errores y el caché.
2.  **RAM:** ZFS usa la RAM para el ARC (caché de lectura). Cuanta más, mejor, pero para uso doméstico, 8GB-16GB suelen ser suficientes para empezar.
3.  **Identificación de discos:** En sistemas reales, crear el pool usando `/dev/disk/by-id/` en lugar de `/dev/sdX` para evitar problemas si cambian los cables de sitio.
4.  **Regla del 80%:** No llenes el pool más allá del 80% de su capacidad. ZFS (Copy-on-Write) necesita espacio libre para encontrar bloques contiguos y mantener el rendimiento.

### Monitorización básica
```bash
# Ver salud de los discos y errores de integridad
zpool status

# Ver estadísticas de IO cada 5 segundos
zpool iostat -v 5
```

---

## Gestión de Memoria (ARC)

ZFS utiliza el ARC (Adaptive Replacement Cache) en lugar del Page Cache de Linux.

### Características del ARC:
- **Dinámico:** Libera memoria si el sistema (VMs, Dockers) la reclama.
- **Eficiente:** Almacena datos frecuentemente usados (MFU) y recientemente usados (MRU).
- **Configurable:** Por defecto usa el 50% de la RAM en Linux.

### Cómo limitar el uso de RAM por ZFS:
Si el NAS tiene poca RAM o muchos servicios, se puede limitar el ARC editando `/etc/modprobe.d/zfs.conf`:

```bash
# Ejemplo para limitar a 4GB (4 * 1024 * 1024 * 1024)
options zfs zfs_arc_max=4294967296
```

## Módulo: Snapshots (Instantáneas)

Los snapshots son la base de la seguridad en ZFS. Permiten volver atrás en el tiempo sin necesidad de recurrir a backups externos.

### Conceptos clave
- **Inmutabilidad**: Un snapshot es de solo lectura.
- **Copy-on-Write**: Solo consumen espacio cuando el dato original cambia.
- **Sintaxis**: `pool/dataset@nombre`

### Comandos básicos
```bash
# Crear un snapshot
zfs snapshot <dataset>@<nombre>

# Listar todos los snapshots
zfs list -t snapshot

# Eliminar un snapshot
zfs destroy <dataset>@<nombre>

# Volver atrás un dataset completo (Rollback)
# ¡Atención! Se pierden los cambios posteriores al snapshot.
zfs rollback <dataset>@<nombre>
```

### Acceso manual (Recuperación de archivos sueltos)

Cada dataset tiene un directorio invisible en su raíz llamado .zfs.

Ruta: /pool/dataset/.zfs/snapshot/<nombre_snapshot>/

Se puede navegar, hacer ls, cp o diff entre snapshots.

Buenas Prácticas

* **Snapshots automáticos:** En TrueNAS o sistemas en producción, se suelen programar snapshots cada hora o cada día.
* **Nomenclatura**: Usar fechas en el nombre (ej: @2023-10-27-1000) para facilitar la gestión.
* *Snapshots no son Backups*: Si el Pool muere (fallan demasiados discos), los snapshots mueren con él. El backup debe estar en otro pool o máquina (usando zfs send/receive).