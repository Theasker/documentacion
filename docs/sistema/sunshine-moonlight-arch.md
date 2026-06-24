# Streaming local con Sunshine + Moonlight
### Arch Linux (RTX 3060) → laptop cliente (Windows 10 / Linux)

> **Objetivo:** el usuario `jugador` accede remotamente a su propia sesión de escritorio desde el laptop, con GPU real para juegos, mientras el usuario principal sigue trabajando sin perder el control del teclado ni el ratón.

---

## Índice

1. [Requisitos previos](#1-requisitos-previos)
2. [Crear el usuario jugador](#2-crear-el-usuario-jugador)
3. [Instalar Sunshine en el servidor](#3-instalar-sunshine-en-el-servidor)
4. [Configurar uinput (dispositivos de entrada virtuales)](#4-configurar-uinput-dispositivos-de-entrada-virtuales)
5. [Crear el display virtual para la sesión del jugador](#5-crear-el-display-virtual-para-la-sesión-del-jugador)
6. [Configurar y arrancar Sunshine](#6-configurar-y-arrancar-sunshine)
7. [Instalar Moonlight en el cliente](#7-instalar-moonlight-en-el-cliente)
8. [Emparejar y conectar](#8-emparejar-y-conectar)
9. [Automatizar el arranque](#9-automatizar-el-arranque)
10. [Solución de problemas frecuentes](#10-solución-de-problemas-frecuentes)

---

## 1. Requisitos previos

### En el servidor (Arch Linux)

- Drivers NVIDIA propietarios instalados (`nvidia`, `nvidia-utils`)
- Entorno de escritorio para el usuario jugador (XFCE, KDE, Openbox…)
- Red local: WiFi 5 GHz o cable Ethernet (recomendado)

Verificar que los drivers están activos:

```bash
nvidia-smi
```

Instalar dependencias base:

```bash
sudo pacman -S xorg-server xorg-xinit xf86-video-dummy uinput
```

### En el cliente (Windows 10)

- Moonlight Game Streaming (ver sección 7)
- Misma red local que el servidor

---

## 2. Crear el usuario jugador

```bash
# Crear usuario con directorio home
sudo useradd -m -s /bin/bash jugador

# Asignar contraseña
sudo passwd jugador

# Añadir a los grupos necesarios
sudo usermod -aG input,video,audio jugador
```

Instala un entorno de escritorio ligero para ese usuario si no tienes uno ya. XFCE es una buena opción para juegos:

```bash
sudo pacman -S xfce4 xfce4-goodies
```

---

## 3. Instalar Sunshine en el servidor

Sunshine está en el AUR:

```bash
# Con yay
yay -S sunshine

# O con paru
paru -S sunshine
```

Alternativamente, descarga el paquete `.pkg.tar.zst` directamente desde:
https://github.com/LizardByte/Sunshine/releases

```bash
sudo pacman -U sunshine-*.pkg.tar.zst
```

Verificar la instalación:

```bash
sunshine --version
```

---

## 4. Configurar uinput (dispositivos de entrada virtuales)

Este es el paso clave para que el jugador tenga su propio ratón y teclado **virtuales**, sin interferir con los tuyos físicos.

### Cargar el módulo uinput

```bash
sudo modprobe uinput
```

Para que cargue automáticamente en cada arranque:

```bash
echo "uinput" | sudo tee /etc/modules-load.d/uinput.conf
```

Verificar que está activo:

```bash
lsmod | grep uinput
```

### Regla udev para permisos

```bash
sudo nano /etc/udev/rules.d/85-sunshine.rules
```

Contenido:

```
KERNEL=="uinput", GROUP="input", MODE="0660"
```

Aplicar los cambios:

```bash
sudo udevadm control --reload-rules
sudo udevadm trigger
```

---

## 5. Crear el display virtual para la sesión del jugador

El usuario jugador necesita su propio servidor X en el display `:1`. Tu sesión principal sigue en `:0`.

### Configuración del display virtual

```bash
sudo nano /etc/X11/xorg-jugador.conf
```

Contenido:

```
Section "Device"
    Identifier  "Dummy"
    Driver      "dummy"
    VideoRam    256000
EndSection

Section "Monitor"
    Identifier  "Monitor0"
    HorizSync   28-80
    VertRefresh 48-75
EndSection

Section "Screen"
    Identifier  "Screen0"
    Device      "Dummy"
    Monitor     "Monitor0"
    DefaultDepth 24
    SubSection "Display"
        Depth  24
        Modes  "1920x1080"
    EndSubSection
EndSection
```

> **Nota:** aunque el display sea "dummy" (virtual), Sunshine usará la RTX 3060 real para renderizar y codificar. El driver dummy solo gestiona la capa de display, no el renderizado 3D.

### Script de arranque de la sesión del jugador

```bash
sudo nano /usr/local/bin/arrancar-sesion-jugador.sh
```

Contenido:

```bash
#!/bin/bash

# Arrancar servidor X en display :1 con config dummy
sudo -u jugador Xorg :1 -config /etc/X11/xorg-jugador.conf &
sleep 3

# Arrancar escritorio XFCE para el jugador en :1
sudo -u jugador DISPLAY=:1 XAUTHORITY=/home/jugador/.Xauthority startxfce4 &
sleep 3

# Arrancar Sunshine apuntando a la sesión del jugador
sudo -u jugador DISPLAY=:1 XAUTHORITY=/home/jugador/.Xauthority sunshine &
```

Dar permisos de ejecución:

```bash
sudo chmod +x /usr/local/bin/arrancar-sesion-jugador.sh
```

---

## 6. Configurar y arrancar Sunshine

### Interfaz web de Sunshine

Una vez arrancado Sunshine, accede a su panel desde el servidor:

```
http://localhost:47990
```

La primera vez te pedirá crear usuario y contraseña para el panel.

### Configuración de entrada (clave para el aislamiento)

En el panel web: **Configuration → Input**

| Opción | Valor recomendado |
|--------|------------------|
| Tipo de entrada | `uinput` |
| Captura de teclado | Solo para la sesión remota |
| Mouse mode | `relative` o `absolute` según prefieras |

Con `uinput` activado, Moonlight crea dispositivos virtuales (`/dev/uinput`) completamente separados de tus dispositivos físicos. **Tú conservas el control total de tu teclado y ratón.**

### Configuración de vídeo

En **Configuration → Video**:

| Opción | Valor recomendado |
|--------|------------------|
| Codec | `HEVC` (H.265) — mejor calidad/bitrate con RTX |
| Encoder | `nvenc` (NVIDIA hardware) |
| Bitrate | 20-50 Mbps para red local |
| FPS | 60 |
| Resolución | 1920x1080 (o la del laptop cliente) |

---

## 7. Instalar Moonlight en el cliente

### Windows 10

Descarga el instalador desde:
https://moonlight-stream.org

Instala normalmente y abre la aplicación. No requiere configuración especial.

### Linux (alternativa)

Si el laptop cliente tiene Linux:

```bash
# Arch
yay -S moonlight-qt

# Ubuntu/Debian
sudo apt install moonlight-qt

# O Flatpak (universal)
flatpak install flathub com.moonlight_stream.Moonlight
```

---

## 8. Emparejar y conectar

1. Asegúrate de que el servidor y el laptop están en la **misma red local**
2. Abre Moonlight — debería detectar automáticamente el servidor por mDNS
3. Si no aparece, añade la IP manualmente (busca la IP del servidor con `ip addr`)
4. Haz clic en el servidor → Moonlight mostrará un **PIN de 4 dígitos**
5. Introduce ese PIN en el panel web de Sunshine: **PIN → Pair**
6. Una vez emparejado, aparecerán los escritorios y aplicaciones configuradas
7. Conecta al escritorio del usuario `jugador`

---

## 9. Automatizar el arranque

Para que la sesión del jugador arranque automáticamente al encender el servidor, crea un servicio systemd:

```bash
sudo nano /etc/systemd/system/sesion-jugador.service
```

Contenido:

```ini
[Unit]
Description=Sesión gráfica del usuario jugador con Sunshine
After=graphical.target network.target
Wants=graphical.target

[Service]
Type=forking
ExecStart=/usr/local/bin/arrancar-sesion-jugador.sh
ExecStop=/usr/bin/pkill -u jugador
Restart=on-failure
RestartSec=5

[Install]
WantedBy=graphical.target
```

Activar el servicio:

```bash
sudo systemctl daemon-reload
sudo systemctl enable sesion-jugador.service
sudo systemctl start sesion-jugador.service
```

Comprobar el estado:

```bash
sudo systemctl status sesion-jugador.service
```

---

## 10. Solución de problemas frecuentes

### El jugador no ve ningún escritorio en Moonlight

Verificar que el display `:1` está activo:

```bash
DISPLAY=:1 xdpyinfo | head -5
```

Si falla, revisar los logs de Xorg:

```bash
cat /home/jugador/.local/share/xorg/Xorg.1.log
```

### Sigo perdiendo el control del ratón

Confirmar que Sunshine usa `uinput` y no `x11grab`:

```bash
cat ~/.config/sunshine/sunshine.conf | grep input
```

Debe aparecer `input = uinput`. Si no, editar el fichero directamente o cambiarlo desde el panel web.

### Minecraft va lento o con lag

Verificar que el encoder NVENC está activo en los logs de Sunshine:

```bash
journalctl -u sesion-jugador.service | grep -i nvenc
```

Si usa software encoding en lugar de NVENC, asegúrate de que el usuario `jugador` tiene acceso a `/dev/nvidia*`:

```bash
ls -la /dev/nvidia*
sudo usermod -aG video jugador
```

### El laptop no detecta el servidor en la red

Comprobar que el puerto 47989 (TCP) y 47998-48000 (UDP) no están bloqueados:

```bash
sudo firewall-cmd --add-port=47989/tcp --permanent
sudo firewall-cmd --add-port=47998-48000/udp --permanent
sudo firewall-cmd --reload
```

O si usas `ufw`:

```bash
sudo ufw allow 47984:48010/tcp
sudo ufw allow 47998:48010/udp
```

---

## Resumen del flujo final

```
Tu sesión (:0)          Sesión jugador (:1)
Teclado físico    →     [sin acceso]
Ratón físico      →     [sin acceso]
                        Teclado virtual (uinput) ← Moonlight
                        Ratón virtual (uinput)   ← Moonlight
                        RTX 3060 (NVENC) → H.265 → red local → laptop
```

Tú trabajas con normalidad. El jugador controla su sesión desde el laptop con latencia mínima y GPU real.
