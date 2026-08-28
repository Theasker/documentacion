# Sunshine + Moonlight multiusuario en Linux (Xorg dummy + inputtino)

Guía completa para montar un **servidor de streaming de juegos** con [Sunshine](https://github.com/LizardByte/Sunshine) en un PC Linux con NVIDIA, de modo que un **usuario secundario (ruben)** pueda jugar desde un portátil con **Moonlight** mientras el usuario principal (theasker) sigue trabajando en su propia sesión.

> **Escenario real documentado:** PC con EndeavourOS (Arch), XFCE, RTX 3060 Ti, driver NVIDIA 610.57.04. Cliente: portátil Windows con Moonlight.

---

## 1. Objetivo y arquitectura

| Componente | Detalle |
|---|---|
| **Servidor** | PC Linux (theasker-pc, 192.168.1.69) — ejecuta Sunshine |
| **Cliente** | Portátil Windows (192.168.1.202) — ejecuta Moonlight |
| **Usuario de streaming** | `ruben` (uid 1002) — sesión X **virtual** en display `:1` |
| **Usuario principal** | `theasker` (uid 1000) — sesión X real en display `:0` |
| **Resolución** | 1920×1080 @ 60 Hz (la elige Moonlight desde su configuración) |
| **GPU** | RTX 3060 Ti — NVENC H.264/HEVC para el encode |

```

```
┌─────────────────────────────┐          ┌──────────────────────────────┐
│  Portátil Windows (Moonlight)│  HTTPS   │  PC Linux (Sunshine server)   │
│  usuario: ruben              │ ───────► │  Xorg :1  (display virtual)   │
└─────────────────────────────┘  47990   │  ruben (xfce4 + pipewire)     │
                                          └──────────────────────────────┘
```

```

**Concepto clave:** Sunshine ya no inyecta input por XTEST. Las versiones modernas
(2026.x) usan **exclusivamente `inputtino`**: crean dispositivos *virtuales* de
entrada (`Mouse passthrough`, `Keyboard passthrough`) vía `/dev/uinput`. Para que
esos dispositivos funcionen hace falta un **Xorg real** (con drivers de input
evdev/libinput) — **Xvfb no sirve** porque no tiene driver de input y jamás
"lee" esos dispositivos → el video se ve pero el ratón/teclado no responden.

La solución: un **Xorg con el driver `dummy`** que crea un framebuffer virtual de
1080p sin tocar la GPU, y cuyo stack de input solo acepta los dispositivos
virtuales de Sunshine.

---

## 2. Requisitos previos

- Sistema Linux con Xorg y un gestor de sesión (aquí: XFCE + LightDM).
- Paquete `sunshine` instalado y con la Web UI accesible (`https://IP:47990`).
- NVIDIA con NVENC (driver propietario o `nvidia-open`).
- Paquetes Arch:

```bash
sudo pacman -S xorg-server xf86-video-dummy xf86-input-libinput \
  xdotool xauth openssl
```

- El módulo `uinput` debe cargarse al arranque (para los dispositivos virtuales
  de input de Sunshine):

```bash
# /usr/lib/modules-load.d/60-sunshine.conf
uhid
uinput
```

---

## 3. Usuario de streaming `ruben`

```bash
sudo useradd -m -u 1002 -G input,render,video ruben
```

| Grupo | Para qué |
|---|---|
| `input` | acceder a `/dev/uinput` (dispositivos virtuales de Sunshine) |
| `render` + `video` | acceder a `/dev/nvidia*` y `/dev/dri` |

> Nota: `ruben` **no** necesita estar en `audio`: su audio va por una sesión
> PipeWire propia con un null-sink (ver sección 6).

---

## 4. Display virtual: `/etc/X11/xorg-ruben.conf`

Este archivo define un **Xorg headless** que:
- usa el driver `dummy` (framebuffer virtual 1920×1080, sin GPU),
- **ignora todos los dispositivos de input reales** del PC,
- **solo habilita** los dispositivos passthrough que Sunshine crea.

```conf
Section "ServerLayout"
    Identifier     "RubenDummyLayout"
    Screen         0  "RubenScreen"
EndSection

Section "Module"
    Load "extmod"
    Load "glx"
    Load "dri"
    Load "dbe"
EndSection

Section "Monitor"
    Identifier  "RubenMonitor"
    ModelName   "Virtual 1080p"
    HorizSync   30.0-100.0
    VertRefresh 30.0-100.0
    Modeline    "1920x1080" 173.00 1920 2048 2248 2576 1080 1083 1088 1120 -hsync +vsync
EndSection

Section "Device"
    Identifier "RubenDummyDevice"
    Driver     "dummy"
    VideoRam    32768
EndSection

Section "Screen"
    Identifier "RubenScreen"
    Device     "RubenDummyDevice"
    Monitor    "RubenMonitor"
    DefaultDepth 24
    SubSection "Display"
        Depth   24
        Modes   "1920x1080"
        Virtual 1920 1080
    EndSubSection
EndSection

Section "ServerFlags"
    Option "AutoAddDevices" "true"
    Option "DontZap"        "true"
EndSection

Section "InputClass"
    Identifier "IgnoreRealDevices"
    MatchDevicePath "/dev/input/event*"
    Option "Ignore" "true"
EndSection

Section "InputClass"
    Identifier "SunshineMouse"
    MatchIsPointer "on"
    MatchProduct   "Mouse passthrough"
    Option "Ignore" "false"
EndSection

Section "InputClass"
    Identifier "SunshineMouseAbs"
    MatchIsPointer "on"
    MatchProduct   "Mouse passthrough (absolute)"
    Option "Ignore" "false"
EndSection

Section "InputClass"
    Identifier "SunshineKeyboard"
    MatchIsKeyboard "on"
    MatchProduct    "Keyboard passthrough"
    Option "Ignore" "false"
EndSection
```

**Puntos críticos de esta config:**

1. **`VideoRam 32768`** — OBLIGATORIO. Sin esto el framebuffer de 1920×1080
   falla con `Virtual size (1920x1080) exceeds video memory` (el default del
   driver dummy es 4096 KB).
2. **Las reglas InputClass están en orden:** primero se ignoran TODOS los
   `/dev/input/event*` (`IgnoreRealDevices`), y después se *des-ignoran* solo
   los passthrough. En Xorg la **última** regla que matchea gana.
3. `Mouse passthrough` y `Mouse passthrough (absolute)` deben declararse
   **por separado** (el nombre con `(absolute)` no matchea por substring
   completo en todos los casos).

---

## 5. Aislar los dispositivos virtuales de la sesión del usuario principal

Cada vez que Sunshine arranca (re)crea los dispositivos uinput, y como Xorg
auto-agrega todo `/dev/input/*`, esos dispositivos **también se conectarían a
la sesión `:0` de theasker** → cuando ruben mueve el ratón en Moonlight, el
cursor de theasker también se movería.

Para evitarlo, crea una regla que **ignore los passthrough en TODAS las
sesiones**:

```conf
# /etc/X11/xorg.conf.d/80-ignore-sunshine-input.conf
Section "InputClass"
    Identifier "IgnoreSunshineVirtualInput"
    MatchProduct "passthrough"
    Option "Ignore" "true"
EndSection
```

> ¿Por qué esto no rompe la sesión `:1` de ruben? Porque cuando Xorg se lanza
> con `-config /etc/X11/xorg-ruben.conf`, las reglas del **archivo de config
> ganan** sobre las de `xorg.conf.d/`. En la práctica `:1` conserva los
> passthrough y `:0` los ignora. *(Comportamiento verificado: el orden de
> precedencia es config-file > xorg.conf.d.)*

Aun así, el wrapper además hace un `xinput float` automático al arrancar (ver
sección 8) como defensa inmediata contra la reconexión de los uinput.

---

## 6. Audio propio para ruben (PipeWire + null-sink)

Moonlight (cliente Windows) **aborta la sesión a los ~20 s** si el stream no
tiene pista de audio (error de host + input congelado). La solución es darle a
`ruben` su propia sesión de audio PipeWire con un sink nulo.

```conf
# /home/ruben/.config/pipewire/pipewire.conf.d/50-null-sink.conf
context.objects = [
    {
        factory = adapter
        args = {
            factory.name = support.null-audio-sink
            node.name = "ruben_out"
            node.description = "Ruben Null Sink"
            media.class = "Audio/Sink"
            audio.position = "FL,FR"
        }
    }
]
```

**Gotchas del audio:**

- PipeWire **1.6.8 no trae** `libpipewire-module-null-audio-sink.so`. El sink
  nulo se crea con el **factory SPA** `support.null-audio-sink` dentro de
  `context.objects` (no con `context.modules`).
- No instalar el paquete `pulseaudio` (conflicto con `pipewire-pulse`).
- `XDG_RUNTIME_DIR=/tmp/ruben-runtime` porque logind no crea `/run/user/1002`
  si ruben no tiene login gráfico.
- Verificación como ruben:

```bash
sudo runuser -u ruben -- pactl list short sinks
# → debe listar ruben_out y ruben_out.monitor
```

---

## 7. Configuración de Sunshine

```ini
# /home/ruben/.config/sunshine/sunshine.conf
bitrate = 50000000
csrf_allowed_origins = https://192.168.1.69:47990
locale = es
```

- `csrf_allowed_origins`: la Web UI (accedida desde el portátil) devuelve
  bloqueos CSRF si falta. Poner la URL exacta con `https://`.
- `bitrate`: 50 Mbps para FHD.

### Apps (`apps.json`)

```json
{
  "env": {
    "PATH": "$(PATH):$(HOME)/.local/bin"
  },
  "apps": [
    {
      "name": "Desktop",
      "image-path": "desktop.png"
    },
    {
      "name": "Steam Big Picture",
      "detached": [
        "setsid steam steam://open/bigpicture"
      ],
      "prep-cmd": [
        {
          "do": "",
          "undo": "setsid steam steam://close/bigpicture"
        }
      ],
      "image-path": "steam.png"
    },
    {
      "name": "Minecraft (Prism Launcher)",
      "image-path": "prismlauncher.png",
      "detached": [
        "setsid prismlauncher"
      ]
    }
  ]
}
```

> El pareo con Moonlight (dispositivo `Win11-virtual`, `uniqueid`) vive en
> `sunshine_state.json` — se preserva al reiniciar Sunshine.

---

## 8. Servicio systemd + wrapper

### 8.1 El servicio corre como ROOT

Xorg necesita correr como **root** para tomar una VT (`systemd-logind`):
ejecutado como `ruben` falla con `TakeControl failed: Only owner of session may
take control`. Por eso el servicio no define `User=`, y el wrapper reparte
privilegios: **Xorg como root, apps como ruben**.

```ini
# /etc/systemd/system/sunshine-ruben.service
[Unit]
Description=Sunshine game streaming for ruben (Xorg dummy FHD :1)
After=network-online.target
Wants=network-online.target

[Service]
Type=simple
ExecStart=/usr/local/bin/sunshine-ruben.sh
Restart=on-failure
RestartSec=5
TimeoutStopSec=20

[Install]
WantedBy=multi-user.target
```

### 8.2 El wrapper (`/usr/local/bin/sunshine-ruben.sh`)

```bash
#!/usr/bin/env bash
set -euo pipefail

DISPLAY_NUM=":1"
XAUTH="/home/ruben/.Xauthority"
RUNTIME="/tmp/ruben-runtime"
XORG_LOG="/tmp/xorg-ruben.log"

pkill -f "Xorg :1" 2>/dev/null || true
pkill -u ruben -x Xvfb 2>/dev/null || true
pkill -u ruben -x xfce4-session 2>/dev/null || true
pkill -u ruben -x sunshine 2>/dev/null || true
pkill -u ruben -x pipewire 2>/dev/null || true
pkill -u ruben -x wireplumber 2>/dev/null || true
pkill -u ruben -x pipewire-pulse 2>/dev/null || true
sleep 2

umount "$RUNTIME"/* 2>/dev/null || true
rm -rf "$RUNTIME"
mkdir -p "$RUNTIME"
chown ruben:ruben "$RUNTIME"
chmod 700 "$RUNTIME"

rm -f "$XAUTH"*
touch "$XAUTH"
chown ruben:ruben "$XAUTH"
chmod 600 "$XAUTH"
COOKIE=$(openssl rand -hex 16)
xauth -f "$XAUTH" add "$DISPLAY_NUM" MIT-MAGIC-COOKIE-1 "$COOKIE"
chown ruben:ruben "$XAUTH"

rm -f "$XORG_LOG"
Xorg :1 -config /etc/X11/xorg-ruben.conf \
  -nolisten tcp -novtswitch -noreset \
  -auth "$XAUTH" -logfile "$XORG_LOG" &

for i in $(seq 1 30); do
  [ -S /tmp/.X11-unix/X1 ] && break
  sleep 1
done

(
  sleep 12
  DISPLAY=:0 XAUTHORITY=/run/lightdm/root/:0 \
    xinput list 2>/dev/null | grep -oE "passthrough.*id=[0-9]+" | \
    grep -oE "id=[0-9]+" | cut -d= -f2 | while read -r id; do
      DISPLAY=:0 XAUTHORITY=/run/lightdm/root/:0 xinput float "$id" 2>/dev/null || true
    done
) &

exec runuser -u ruben -- env \
  DISPLAY="$DISPLAY_NUM" \
  XAUTHORITY="$XAUTH" \
  XDG_RUNTIME_DIR="$RUNTIME" \
  dbus-run-session -- bash -c '
    export DISPLAY=":1"
    export XAUTHORITY="/home/ruben/.Xauthority"
    export XDG_RUNTIME_DIR="/tmp/ruben-runtime"

    xfce4-session >/dev/null 2>&1 &

    pipewire >/dev/null 2>&1 &
    wireplumber >/dev/null 2>&1 &
    pipewire-pulse >/dev/null 2>&1 &

    sleep 5
    exec sunshine
  '
```

**Qué hace cada parte:**

1. **Limpieza** — mata restos de ejecuciones previas (Xorg :1, Xvfb, xfce,
   sunshine, pipewire) y espera 2 s.
2. **RUNTIME dir** — antes de `rm -rf`, hace `umount "$RUNTIME"/*` porque
   **gvfs-fuse monta subdirectorios** (`gvfs`, `doc`) dentro de
   `$XDG_RUNTIME_DIR`; sin el umount, `rm -rf` falla con `set -euo pipefail` y
   aborta todo el script.
3. **Xauthority** — cookie fresca de 16 bytes hex. **Gotcha xauth 1.1.5:**
   `xauth add :1 MIT-MAGIC-COOKIE-1 <hex>` **sin** el punto del screen
   (`:1 . MIT-...` falla con "bad add command line"). El `chown` se repite
   **después** de `xauth add` porque xauth puede recrear el archivo como root.
   Se borran también los locks `$XAUTH*` (`-c`, `-l`).
4. **Xorg :1** — como root, con la config dummy, `-novtswitch` (no roba la VT
   activa de theasker) y `-auth` con la cookie de ruben.
5. **Auto-aislamiento** — subshell en background que a los 12 s (tiempo para
   que Sunshine cree los uinput) **desconecta los passthrough de la sesión `:0`**
   con `xinput float`. **Gotcha:** `xinput float` con varios IDs solo toma el
   **primero** → se itera uno por uno. Usa `XAUTHORITY=/run/lightdm/root/:0`
   (la cookie del Xorg :0, propiedad de root).
6. **Sesión de ruben** — `runuser -u ruben` + `dbus-run-session` mantiene vivo
   el bus mientras corren xfce4-session, pipewire, wireplumber, pipewire-pulse
   y finalmente `exec sunshine`.

### 8.3 Activar

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now sunshine-ruben.service
```

---

## 9. Verificación

```bash
# 1) El servicio está activo
systemctl is-active sunshine-ruben.service          # → active

# 2) Xorg :1 corriendo
pgrep -a -f "Xorg :1"

# 3) En la sesión :1 SOLO están los passthrough (nada de ratón/teclado reales)
sudo runuser -u ruben -- env DISPLAY=:1 XAUTHORITY=/home/ruben/.Xauthority \
  xinput list
#   → Mouse passthrough, Mouse passthrough (absolute), Keyboard passthrough

# 4) En la sesión :0 los passthrough están AISLADOS
DISPLAY=:0 xinput list | grep passthrough
#   → todos [floating slave]

# 5) El cursor responde en :1
sudo runuser -u ruben -- env DISPLAY=:1 XAUTHORITY=/home/ruben/.Xauthority \
  xdotool mousemove 400 300 getmouselocation
#   → x:400 y:300

# 6) Audio de ruben
sudo runuser -u ruben -- pactl list short sinks
#   → ruben_out y ruben_out.monitor

# 7) Log de Sunshine limpio
tail -f /home/ruben/.config/sunshine/sunshine.log
#   → "Streaming display: screen with res 1920x1080" / h264_nvenc|hevc_nvenc
```

---

## 10. Backups

Existe un sistema de backup/restore en `/home/theasker/sunshine-ruben-backup/`:

```bash
# Respaldar estado actual
/home/theasker/sunshine-ruben-backup/backup.sh

# Restaurar (archivos + systemd + servicio)
/home/theasker/sunshine-ruben-backup/restore.sh
# Variantes:
#   restore.sh --info     # muestra qué restauraría
#   restore.sh --no-start # solo archivos, sin tocar el servicio
```

Además, al usar Btrfs + snapper (EndeavourOS):

```bash
# Snapshot manual del sistema completo
sudo snapper -c root create -d "antes-de-cambio" -c "pre"

# Ver cambios desde un snapshot
sudo snapper -c root diff <NUM_SNAPSHOT>

# Revertir el sistema al snapshot
sudo snapper -c root rollback <NUM_SNAPSHOT>
sudo reboot
```

---

## 11. Troubleshooting (errores reales encontrados)

| Síntoma | Causa raíz | Solución |
|---|---|---|
| Video se ve pero **el ratón no se mueve** | Sunshine 2026.x usa solo inputtino (uinput); Xvfb no tiene driver de input | Usar **Xorg dummy** (esta guía) |
| `xauth add` → `bad add command line` | La sintaxis con el punto del screen | `xauth add :1 MIT-MAGIC-COOKIE-1 <hex>` (sin el `.`) |
| `Virtual size exceeds video memory` en Xorg | `VideoRam` default insuficiente | `Option "VideoRam" "32768"` en el Device |
| `TakeControl failed: Only owner of session may take control` | Xorg como usuario normal no puede tomar la VT | El **servicio corre como root**, apps vía `runuser` |
| `rm: no se puede borrar '...': Es un directorio` (gvfs) | gvfs-fuse montó dentro de `$RUNTIME` | `umount "$RUNTIME"/*` antes de `rm -rf` |
| Sesión Moonlight **muere a los ~20 s** | No hay pista de audio → el cliente Windows aborta | PipeWire + null-sink `ruben_out` (sección 6) |
| Bloqueos CSRF en la Web UI desde el portátil | Falta `csrf_allowed_origins` | Poner la URL exacta con `https://IP:47990` |
| El cursor del usuario principal se mueve "solo" | Los uinput de Sunshine se auto-conectan a `:0` | Regla `80-ignore-sunshine-input.conf` + `xinput float` en el wrapper |
| `xinput float 14 15 16` no flota todo | El comando toma solo el primer ID | Flotar uno por uno en un loop |
| PipeWire no crea `libpipewire-module-null-audio-sink.so` | No existe en PipeWire 1.6.x | Usar `support.null-audio-sink` con `factory = adapter` |

---

## 12. Notas finales

- La **resolución no se configura en la Web UI de Sunshine**: se elige en
  Moonlight (engranaje del host → Resolution & FPS → 1920×1080/60).
- NVENC de la RTX 3060 Ti: H.264 y HEVC OK; **AV1 no soportado**.
- El display virtual usa `xf86-video-dummy` → el renderizado 3D es por
  software (llvmpipe) dentro de la sesión virtual. El **encode** sigue siendo
  por GPU (NVENC). Si se quiere renderizado 3D con GPU real para juegos
  pesados, habría que migrar a un display virtual NVIDIA (kernel
  `drm.edid_firmware` / CustomEDID) — otra guía.
- Fuente del descubrimiento clave (inputtino-only en Sunshine 2026.516):
  revisar el binario (`strings`) y el panel de config de la Web UI — no existe
  opción para elegir uinput vs XTEST en estas builds.

EndSection
```

**Puntos críticos de esta config:**

1. **`VideoRam 32768`** — OBLIGATORIO. Sin esto el framebuffer de 1920×1080
   falla con `Virtual size (1920x1080) exceeds video memory` (el default del
   driver dummy es 4096 KB).
2. **Las reglas InputClass están en orden:** primero se ignoran TODOS los
   `/dev/input/event*` (`IgnoreRealDevices`), y después se *des-ignoran* solo
   los passthrough. En Xorg la **última** regla que matchea gana.
3. `Mouse passthrough` y `Mouse passthrough (absolute)` deben declararse
   **por separado** (el nombre con `(absolute)` no matchea por substring
   completo en todos los casos).

---

## 5. Aislar los dispositivos virtuales de la sesión del usuario principal

Cada vez que Sunshine arranca (re)crea los dispositivos uinput, y como Xorg
auto-agrega todo `/dev/input/*`, esos dispositivos **también se conectarían a
la sesión `:0` de theasker** → cuando ruben mueve el ratón en Moonlight, el
cursor de theasker también se movería.

Para evitarlo, crea una regla que **ignore los passthrough en TODAS las
sesiones**:

```conf
# /etc/X11/xorg.conf.d/80-ignore-sunshine-input.conf
Section "InputClass"
    Identifier "IgnoreSunshineVirtualInput"
    MatchProduct "passthrough"
    Option "Ignore" "true"
EndSection
```

> ¿Por qué esto no rompe la sesión `:1` de ruben? Porque cuando Xorg se lanza
> con `-config /etc/X11/xorg-ruben.conf`, las reglas del **archivo de config
> ganan** sobre las de `xorg.conf.d/`. En la práctica `:1` conserva los
> passthrough y `:0` los ignora. *(Comportamiento verificado: el orden de
> precedencia es config-file > xorg.conf.d.)*

Aun así, el wrapper además hace un `xinput float` automático al arrancar (ver
sección 8) como defensa inmediata contra la reconexión de los uinput.

---

## 6. Audio propio para ruben (PipeWire + null-sink)

Moonlight (cliente Windows) **aborta la sesión a los ~20 s** si el stream no
tiene pista de audio (error de host + input congelado). La solución es darle a
`ruben` su propia sesión de audio PipeWire con un sink nulo.

```conf
# /home/ruben/.config/pipewire/pipewire.conf.d/50-null-sink.conf
context.objects = [
    {
        factory = adapter
        args = {
            factory.name = support.null-audio-sink
            node.name = "ruben_out"
            node.description = "Ruben Null Sink"
            media.class = "Audio/Sink"
            audio.position = "FL,FR"
        }
    }
]
```

**Gotchas del audio:**

- PipeWire **1.6.8 no trae** `libpipewire-module-null-audio-sink.so`. El sink
  nulo se crea con el **factory SPA** `support.null-audio-sink` dentro de
  `context.objects` (no con `context.modules`).
- No instalar el paquete `pulseaudio` (conflicto con `pipewire-pulse`).
- `XDG_RUNTIME_DIR=/tmp/ruben-runtime` porque logind no crea `/run/user/1002`
  si ruben no tiene login gráfico.
- Verificación como ruben:

```bash
sudo runuser -u ruben -- pactl list short sinks
# → debe listar ruben_out y ruben_out.monitor
```

---

## 7. Configuración de Sunshine

```ini
# /home/ruben/.config/sunshine/sunshine.conf
bitrate = 50000000
csrf_allowed_origins = https://192.168.1.69:47990
locale = es
```

- `csrf_allowed_origins`: la Web UI (accedida desde el portátil) devuelve
  bloqueos CSRF si falta. Poner la URL exacta con `https://`.
- `bitrate`: 50 Mbps para FHD.

### Apps (`apps.json`)

```json
{
  "env": {
    "PATH": "$(PATH):$(HOME)/.local/bin"
  },
  "apps": [
    {
      "name": "Desktop",
      "image-path": "desktop.png"
    },
    {
      "name": "Steam Big Picture",
      "detached": [
        "setsid steam steam://open/bigpicture"
      ],
      "prep-cmd": [
        {
          "do": "",
          "undo": "setsid steam steam://close/bigpicture"
        }
      ],
      "image-path": "steam.png"
    },
    {
      "name": "Minecraft (Prism Launcher)",
      "image-path": "prismlauncher.png",
      "detached": [
        "setsid prismlauncher"
      ]
    }
  ]
}
```

> El pareo con Moonlight (dispositivo `Win11-virtual`, `uniqueid`) vive en
> `sunshine_state.json` — se preserva al reiniciar Sunshine.

---

## 8. Servicio systemd + wrapper

### 8.1 El servicio corre como ROOT

Xorg necesita correr como **root** para tomar una VT (`systemd-logind`):
ejecutado como `ruben` falla con `TakeControl failed: Only owner of session may
take control`. Por eso el servicio no define `User=`, y el wrapper reparte
privilegios: **Xorg como root, apps como ruben**.

```ini
# /etc/systemd/system/sunshine-ruben.service
[Unit]
Description=Sunshine game streaming for ruben (Xorg dummy FHD :1)
After=network-online.target
Wants=network-online.target

[Service]
Type=simple
ExecStart=/usr/local/bin/sunshine-ruben.sh
Restart=on-failure
RestartSec=5
TimeoutStopSec=20

[Install]
WantedBy=multi-user.target
```

### 8.2 El wrapper (`/usr/local/bin/sunshine-ruben.sh`)

```bash
#!/usr/bin/env bash
set -euo pipefail

DISPLAY_NUM=":1"
XAUTH="/home/ruben/.Xauthority"
RUNTIME="/tmp/ruben-runtime"
XORG_LOG="/tmp/xorg-ruben.log"

pkill -f "Xorg :1" 2>/dev/null || true
pkill -u ruben -x Xvfb 2>/dev/null || true
pkill -u ruben -x xfce4-session 2>/dev/null || true
pkill -u ruben -x sunshine 2>/dev/null || true
pkill -u ruben -x pipewire 2>/dev/null || true
pkill -u ruben -x wireplumber 2>/dev/null || true
pkill -u ruben -x pipewire-pulse 2>/dev/null || true
sleep 2

umount "$RUNTIME"/* 2>/dev/null || true
rm -rf "$RUNTIME"
mkdir -p "$RUNTIME"
chown ruben:ruben "$RUNTIME"
chmod 700 "$RUNTIME"

rm -f "$XAUTH"*
touch "$XAUTH"
chown ruben:ruben "$XAUTH"
chmod 600 "$XAUTH"
COOKIE=$(openssl rand -hex 16)
xauth -f "$XAUTH" add "$DISPLAY_NUM" MIT-MAGIC-COOKIE-1 "$COOKIE"
chown ruben:ruben "$XAUTH"

rm -f "$XORG_LOG"
Xorg :1 -config /etc/X11/xorg-ruben.conf \
  -nolisten tcp -novtswitch -noreset \
  -auth "$XAUTH" -logfile "$XORG_LOG" &

for i in $(seq 1 30); do
  [ -S /tmp/.X11-unix/X1 ] && break
  sleep 1
done

(
  sleep 12
  DISPLAY=:0 XAUTHORITY=/run/lightdm/root/:0 \
    xinput list 2>/dev/null | grep -oE "passthrough.*id=[0-9]+" | \
    grep -oE "id=[0-9]+" | cut -d= -f2 | while read -r id; do
      DISPLAY=:0 XAUTHORITY=/run/lightdm/root/:0 xinput float "$id" 2>/dev/null || true
    done
) &

exec runuser -u ruben -- env \
  DISPLAY="$DISPLAY_NUM" \
  XAUTHORITY="$XAUTH" \
  XDG_RUNTIME_DIR="$RUNTIME" \
  dbus-run-session -- bash -c '
    export DISPLAY=":1"
    export XAUTHORITY="/home/ruben/.Xauthority"
    export XDG_RUNTIME_DIR="/tmp/ruben-runtime"

    xfce4-session >/dev/null 2>&1 &

    pipewire >/dev/null 2>&1 &
    wireplumber >/dev/null 2>&1 &
    pipewire-pulse >/dev/null 2>&1 &

    sleep 5
    exec sunshine
  '
```

**Qué hace cada parte:**

1. **Limpieza** — mata restos de ejecuciones previas (Xorg :1, Xvfb, xfce,
   sunshine, pipewire) y espera 2 s.
2. **RUNTIME dir** — antes de `rm -rf`, hace `umount "$RUNTIME"/*` porque
   **gvfs-fuse monta subdirectorios** (`gvfs`, `doc`) dentro de
   `$XDG_RUNTIME_DIR`; sin el umount, `rm -rf` falla con `set -euo pipefail` y
   aborta todo el script.
3. **Xauthority** — cookie fresca de 16 bytes hex. **Gotcha xauth 1.1.5:**
   `xauth add :1 MIT-MAGIC-COOKIE-1 <hex>` **sin** el punto del screen
   (`:1 . MIT-...` falla con "bad add command line"). El `chown` se repite
   **después** de `xauth add` porque xauth puede recrear el archivo como root.
   Se borran también los locks `$XAUTH*` (`-c`, `-l`).
4. **Xorg :1** — como root, con la config dummy, `-novtswitch` (no roba la VT
   activa de theasker) y `-auth` con la cookie de ruben.
5. **Auto-aislamiento** — subshell en background que a los 12 s (tiempo para
   que Sunshine cree los uinput) **desconecta los passthrough de la sesión `:0`**
   con `xinput float`. **Gotcha:** `xinput float` con varios IDs solo toma el
   **primero** → se itera uno por uno. Usa `XAUTHORITY=/run/lightdm/root/:0`
   (la cookie del Xorg :0, propiedad de root).
6. **Sesión de ruben** — `runuser -u ruben` + `dbus-run-session` mantiene vivo
   el bus mientras corren xfce4-session, pipewire, wireplumber, pipewire-pulse
   y finalmente `exec sunshine`.

### 8.3 Activar

```bash
sudo systemctl daemon-reload
sudo systemctl enable --now sunshine-ruben.service
```

---

## 9. Verificación

```bash
# 1) El servicio está activo
systemctl is-active sunshine-ruben.service          # → active

# 2) Xorg :1 corriendo
pgrep -a -f "Xorg :1"

# 3) En la sesión :1 SOLO están los passthrough (nada de ratón/teclado reales)
sudo runuser -u ruben -- env DISPLAY=:1 XAUTHORITY=/home/ruben/.Xauthority \
  xinput list
#   → Mouse passthrough, Mouse passthrough (absolute), Keyboard passthrough

# 4) En la sesión :0 los passthrough están AISLADOS
DISPLAY=:0 xinput list | grep passthrough
#   → todos [floating slave]

# 5) El cursor responde en :1
sudo runuser -u ruben -- env DISPLAY=:1 XAUTHORITY=/home/ruben/.Xauthority \
  xdotool mousemove 400 300 getmouselocation
#   → x:400 y:300

# 6) Audio de ruben
sudo runuser -u ruben -- pactl list short sinks
#   → ruben_out y ruben_out.monitor

# 7) Log de Sunshine limpio
tail -f /home/ruben/.config/sunshine/sunshine.log
#   → "Streaming display: screen with res 1920x1080" / h264_nvenc|hevc_nvenc
```

---

## 10. Backups

Existe un sistema de backup/restore en `/home/theasker/sunshine-ruben-backup/`:

```bash
# Respaldar estado actual
/home/theasker/sunshine-ruben-backup/backup.sh

# Restaurar (archivos + systemd + servicio)
/home/theasker/sunshine-ruben-backup/restore.sh
# Variantes:
#   restore.sh --info     # muestra qué restauraría
#   restore.sh --no-start # solo archivos, sin tocar el servicio
```

Además, al usar Btrfs + snapper (EndeavourOS):

```bash
# Snapshot manual del sistema completo
sudo snapper -c root create -d "antes-de-cambio" -c "pre"

# Ver cambios desde un snapshot
sudo snapper -c root diff <NUM_SNAPSHOT>

# Revertir el sistema al snapshot
sudo snapper -c root rollback <NUM_SNAPSHOT>
sudo reboot
```

---

## 11. Troubleshooting (errores reales encontrados)

| Síntoma | Causa raíz | Solución |
|---|---|---|
| Video se ve pero **el ratón no se mueve** | Sunshine 2026.x usa solo inputtino (uinput); Xvfb no tiene driver de input | Usar **Xorg dummy** (esta guía) |
| `xauth add` → `bad add command line` | La sintaxis con el punto del screen | `xauth add :1 MIT-MAGIC-COOKIE-1 <hex>` (sin el `.`) |
| `Virtual size exceeds video memory` en Xorg | `VideoRam` default insuficiente | `Option "VideoRam" "32768"` en el Device |
| `TakeControl failed: Only owner of session may take control` | Xorg como usuario normal no puede tomar la VT | El **servicio corre como root**, apps vía `runuser` |
| `rm: no se puede borrar '...': Es un directorio` (gvfs) | gvfs-fuse montó dentro de `$RUNTIME` | `umount "$RUNTIME"/*` antes de `rm -rf` |
| Sesión Moonlight **muere a los ~20 s** | No hay pista de audio → el cliente Windows aborta | PipeWire + null-sink `ruben_out` (sección 6) |
| Bloqueos CSRF en la Web UI desde el portátil | Falta `csrf_allowed_origins` | Poner la URL exacta con `https://IP:47990` |
| El cursor del usuario principal se mueve "solo" | Los uinput de Sunshine se auto-conectan a `:0` | Regla `80-ignore-sunshine-input.conf` + `xinput float` en el wrapper |
| `xinput float 14 15 16` no flota todo | El comando toma solo el primer ID | Flotar uno por uno en un loop |
| PipeWire no crea `libpipewire-module-null-audio-sink.so` | No existe en PipeWire 1.6.x | Usar `support.null-audio-sink` con `factory = adapter` |

---

## 12. Notas finales

- La **resolución no se configura en la Web UI de Sunshine**: se elige en
  Moonlight (engranaje del host → Resolution & FPS → 1920×1080/60).
- NVENC de la RTX 3060 Ti: H.264 y HEVC OK; **AV1 no soportado**.
- El display virtual usa `xf86-video-dummy` → el renderizado 3D es por
  software (llvmpipe) dentro de la sesión virtual. El **encode** sigue siendo
  por GPU (NVENC). Si se quiere renderizado 3D con GPU real para juegos
  pesados, habría que migrar a un display virtual NVIDIA (kernel
  `drm.edid_firmware` / CustomEDID) — otra guía.
- Fuente del descubrimiento clave (inputtino-only en Sunshine 2026.516):
  revisar el binario (`strings`) y el panel de config de la Web UI — no existe
  opción para elegir uinput vs XTEST en estas builds.
