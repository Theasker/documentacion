# Guía Completa: Aislamiento y Sandboxing con Contenedores de systemd (`systemd-nspawn`)

Esta documentación recoge en detalle los conceptos, comandos y configuraciones explicados en el vídeo **"Evita que te hackeen usando contenedores de systemd"** (*Linux con Last Dragon*). 

Se incluye la adaptación paso a paso tanto para **Debian** (sistema utilizado en el tutorial original) como para **Arch Linux**.

---

## 1. Introducción y Conceptos Clave

`systemd-nspawn` es una herramienta nativa integrada en `systemd` que permite instanciar contenedores en espacio de usuario. Funciona de manera análoga a un `chroot` con esteroides, pero proporcionando un aislamiento completo de namespaces (procesos, red, IPC, PID, montajes y hostname).

### Ventajas frente a otras soluciones:
* **Fácil gestión de montajes (bindings):** A diferencia de `chroot` manual (donde hay que montar `/proc`, `/sys`, `/dev` con `mount --bind`), `systemd-nspawn` automatiza estas tareas y ofrece flags sencillos (`--bind`).
* **Ligereza absoluta:** No requiere demonios residentes en segundo plano como Docker (`dockerd`) o Podman.
* **Aislamiento seguro:** Ideal para crear *sandboxes* de aplicaciones no confiables (Firefox, Telegram, scripts externos) o para compilar código sin ensuciar el sistema anfitrión con paquetes de desarrollo (`build-essential`, `base-devel`, etc.).
* **Limpieza inmediata:** Eliminar un contenedor consiste simplemente en borrar su directorio.

---

## 2. Optimización del Sistema de Archivos (Reflinks / CoW)

Para maximizar la eficiencia en disco y la velocidad al instanciar múltiples contenedores, se recomienda usar sistemas de archivos compatibles con **Reflinks / Copy-on-Write (CoW)** como **Btrfs** o **XFS** (creado con soporte de reflink).

### Clonación sin consumo adicional de espacio
Al hacer una copia con la opción `--reflink=always`, el nuevo contenedor comparte los mismos bloques de datos que la imagen base. Solo las modificaciones posteriores consumirán espacio en disco.

```bash
# Clonar un contenedor base de forma instantánea y sin ocupar espacio extra
cp -r --reflink=always /var/lib/machines/base /var/lib/machines/ejemplo1
cp -r --reflink=always /var/lib/machines/base /var/lib/machines/ejemplo2
```
*Si se utiliza EXT4 u otro sistema de archivos tradicional, la copia funcionará pero duplicará el espacio consumido por cada contenedor.*

---

## 3. Instalación de Requisitos y Creación de la Imagen Base

### En Debian

1. **Instalar dependencias necesarias:**
   ```bash
   sudo apt update
   sudo apt install systemd-container debootstrap
   ```

2. **Crear el contenedor base (ejemplo con Debian 13 "Trixie" o 12 "Bookworm"):**
   ```bash
   sudo mkdir -p /var/lib/machines/deb-base
   sudo debootstrap trixie /var/lib/machines/deb-base [http://deb.debian.org/debian/](http://deb.debian.org/debian/)
   ```

---

### En Arch Linux

En Arch Linux, `systemd-nspawn` ya viene incluido en el paquete principal `systemd`.

1. **Instalar herramientas de instalación de contenedores:**
   ```bash
   sudo pacman -S arch-install-scripts debootstrap
   ```

2. **Crear un contenedor base de Arch Linux (usando `pacstrap`):**
   ```bash
   sudo mkdir -p /var/lib/machines/arch-base
   sudo pacstrap -K /var/lib/machines/arch-base base bash coreutils iproute2 nano
   ```

3. **(Opcional) Crear un contenedor base de Debian desde Arch Linux:**
   ```bash
   sudo mkdir -p /var/lib/machines/deb-base
   sudo debootstrap trixie /var/lib/machines/deb-base http://deb.debian.org/debian
   ```

---

## 4. Uso Básico de `systemd-nspawn`

### 4.1. Acceder al Contenedor
Para entrar en la consola del contenedor interactivo:

```bash
sudo systemd-nspawn -D /var/lib/machines/ejemplo1
```

Dentro del contenedor se observará:
* Aislamiento de procesos (`ps aux` solo muestra los procesos internos).
* El usuario por defecto será `root` del contenedor.

### 4.2. Instalar herramientas dentro del contenedor
* **En Debian (dentro del contenedor):**
  ```bash
  apt update && apt install -y btop htop
  ```
* **En Arch Linux (dentro del contenedor):**
  ```bash
  pacman -Sy btop htop
  ```

Al salir del contenedor (`exit`), el espacio gastado será únicamente el de las aplicaciones nuevas instaladas gracias al Copy-on-Write.

---

## 5. Sandboxing Avanzado: Gráficos (Wayland/X11), Audio (PipeWire) y GPU

Para ejecutar aplicaciones de escritorio (como Firefox, Telegram o Chromium) de forma aislada sin comprometer el sistema anfitrión, es necesario compartir ciertos sockets y dispositivos específicos.

### 5.1. Coincidencia de Usuarios y UID
Las aplicaciones de escritorio y servidores de sonido (PipeWire/PulseAudio) verifican el ID de usuario (`UID`). Para que la integración funcione sin problemas:
* El usuario del contenedor debe tener el mismo UID que el del anfitrión (generalmente `1000`).

**Dentro del contenedor (crear usuario):**
* **Debian / Arch:**
  ```bash
  useradd -m -u 1000 -s /bin/bash miusuario
  ```

---

### 5.2. Passthrough de GPU y Dispositivos

#### A) GPU AMD / Intel
Para aceleración por hardware con tarjetas gráficas integradas Intel o AMD Radeon:
```bash
--bind=/dev/dri
```

#### B) GPU NVIDIA
NVIDIA requiere montar los dispositivos de caracteres `/dev/nvidia*` y las librerías propietarias de la gráfica.
* **Debian:** Se pasan los dispositivos y las librerías almacenadas en `/usr/lib/x86_64-linux-gnu/nvidia/` y binarios como `nvidia-smi`.
* **Arch Linux:** Las librerías de NVIDIA se ubican habitualmente en `/usr/lib/` (`libcuda.so*`, `libnvidia*.so*`, etc.).

---

### 5.3. Passthrough de Audio (PipeWire)
El socket de PipeWire se encuentra en `/run/user/1000/pipewire-0`. Se enlaza al contenedor mediante:
```bash
--bind=/run/user/1000/pipewire-0
```

---

### 5.4. Entorno Gráfico (Wayland / X11)
Para permitir que la ventana del contenedor se dibuje en la pantalla del host:

* **Obtener la variable de pantalla Wayland (en el host):**
  ```bash
  echo $WAYLAND_DISPLAY   # Habitualmente: wayland-0
  ```
* **Flags requeridas:**
  ```bash
  --bind=/run/user/1000
  --setenv=XDG_RUNTIME_DIR=/run/user/1000
  --setenv=WAYLAND_DISPLAY=wayland-0
  ```

---

## 6. Comandos Completos de Lanzamiento de Entornos Gráficos

### 6.1. Comando para GPU AMD / Intel (Wayland + PipeWire)

```bash
sudo systemd-nspawn -D /var/lib/machines/ejemplo1 \
  --user=miusuario \
  --bind=/dev/dri \
  --bind=/run/user/1000/pipewire-0 \
  --bind=/run/user/1000 \
  --setenv=XDG_RUNTIME_DIR=/run/user/1000 \
  --setenv=WAYLAND_DISPLAY=wayland-0 \
  firefox
```

---

### 6.2. Comando para GPU NVIDIA (Wayland + PipeWire + Drivers NVIDIA)

#### En Debian Host:
```bash
sudo systemd-nspawn -D /var/lib/machines/ejemplo1 \
  --user=miusuario \
  --bind=/dev/dri \
  --bind=/dev/nvidia0 \
  --bind=/dev/nvidiactl \
  --bind=/dev/nvidia-modeset \
  --bind=/dev/nvidia-uvm \
  --bind=/usr/lib/x86_64-linux-gnu/nvidia \
  --bind=/run/user/1000/pipewire-0 \
  --bind=/run/user/1000 \
  --setenv=XDG_RUNTIME_DIR=/run/user/1000 \
  --setenv=WAYLAND_DISPLAY=wayland-0 \
  firefox
```

#### En Arch Linux Host:
```bash
sudo systemd-nspawn -D /var/lib/machines/ejemplo1 \
  --user=miusuario \
  --bind=/dev/dri \
  --bind=/dev/nvidia0 \
  --bind=/dev/nvidiactl \
  --bind=/dev/nvidia-modeset \
  --bind=/dev/nvidia-uvm \
  --bind=/run/user/1000/pipewire-0 \
  --bind=/run/user/1000 \
  --setenv=XDG_RUNTIME_DIR=/run/user/1000 \
  --setenv=WAYLAND_DISPLAY=wayland-0 \
  firefox
```

---

## 7. Casos de Uso Prácticos

1. **Navegación / Sandboxing Seguro:**
   Ejecutar un navegador dentro del contenedor para abrir sitios no confiables, PDFs o adjuntos. Cualquier galleta, rastreador o malware queda encapsulado en la carpeta del contenedor.

2. **Entornos de Compilación Limpios:**
   Instalar dependencias pesadas de compilación (`gcc`, `make`, `cmake`, paquetes `-dev` o `-devel`) en un contenedor desechable. Una vez obtenido el binario compilado, se copia fuera y se destruye el contenedor sin dejar rastro de paquetes innecesarios en el sistema host.

3. **Prueba de Software Inseguro:**
   Probar aplicaciones o scripts de terceros sin peligro de comprometer los archivos personales de `/home` ni los archivos del sistema.

4. **Destrucción Inmediata del Contenedor:**
   ```bash
   sudo rm -rf /var/lib/machines/ejemplo1
   ```

---
*Documentación generada en base al vídeo de "Linux con Last Dragon": "Evita que te hackeen usando contenedores de systemd".*

## Bibliografía
* [Linux con Last Dragon: Evita que te hackeen usando contenedores de systemd](https://www.youtube.com/watch?v=kW6kLKdUjic)