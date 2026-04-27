# Wake On LAN via Tailscale

## Problema

WOL no funciona a través de internet por defecto. El magic packet es un broadcast de capa 2 y no cruza routers.

## Solución

Usar **Tailscale** para crear un túnel privado entre el dispositivo que envía el magic packet y la PC de casa.

```
[Movil/VPS] --túnel Tailscale--> [PC casa]
    |                                |
    +---magic packet a IP Tailscale--+
        (100.64.x.x:9)
```

---

## Requisitos previos

- Tailscale instalado en:
  - PC de casa
  - Dispositivo desde donde enviarás el magic packet (móvil/VPS)
- Ambos conectados a la misma red Tailscale

---

## Configuración del PC de casa

### 1. Verificar la NIC

```bash
ip link show
```

Identificar la interfaz principal (ej: `enp6s0`). Anotar la MAC:

```bash
ip addr show enp6s0 | grep ether
# Output: link/ether 0a:e0:af:ad:36:72 brd ff:ff:ff:ff:ff:ff
```

### 2. Habilitar WOL en la BIOS

Entrar a la BIOS y buscar opciones similares a:
- **Power On By PME**: Enabled
- **Wake on LAN**: Enabled
- **Deep Sleep**: Disabled (puede bloquear WOL)

### 3. Habilitar WOL en Linux (persistente)

Crear archivo de configuración para systemd-networkd:

```bash
sudo mkdir -p /etc/systemd/network
sudo tee /etc/systemd/network/50-wol.rules << 'EOF'
[Match]
Name=enp6s0

[Link]
WakeOnLan=magic
EOF
```

Reiniciar el servicio:

```bash
sudo systemctl restart systemd-networkd
```

### 4. Verificar que WOL está activo

Después de reiniciar, verificar con:

```bash
sudo ethtool enp6s0 | grep -i wake
```

Debería mostrar: `Wake-on: g`

---

## Enviar magic packet desde móvil/VPS

### Opción A: Usar una app de WOL

1. Abrir la app
2. Introducir:
   - **Dirección IP**: La IP de Tailscale del PC de casa (ej: `100.64.x.x`)
   - **Puerto**: `9` (puerto estándar para WOL)
   - **MAC Address**: `0a:e0:af:ad:36:72` (reemplazar por la MAC real)

### Opción B: Usar Termux (Android)

```bash
pkg update
pkg install wakeonlan
wakeonlan -i 100.64.x.x 0a:e0:af:ad:36:72
```

### Opción C: Desde un VPS con Linux

```bash
# Instalar etherwake o wakeonlan
sudo apt install etherwake -y

# Enviar magic packet
sudo etherwake -i tailscale0 0a:e0:af:ad:36:72
```

---

## Notas importantes

- **Shutdown completo**: Al apagar desde XFCE, el PC queda en estado de bajo consumo donde la NIC escucha el magic packet. Funciona en la mayoría demotherboards modernos.
- **IP de Tailscale**: Se puede ver en la app de Tailscale o ejecutando `tailscale ip -4` en el PC de casa.
- **Puerto**: Por defecto es UDP 9, pero algunas BIOS/routers usan el 7 o 0.

---

## Configuración actual de este equipo

| Elemento | Valor |
|----------|-------|
| Interfaz NIC | `enp6s0` |
| MAC Address | `0a:e0:af:ad:36:72` |
| Driver | `r8169` (Realtek RTL8111/8168) |
| Wake-on-LAN | `g` (habilitado) |

Para obtener la IP de Tailscale de este equipo:

```bash
tailscale ip -4
```

---

## Troubleshooting

| Problema | Solución |
|----------|----------|
| No enciende | Verificar BIOS, habilitar "Power On by PME" |
| No enciende | Verificar que ethtool muestre `Wake-on: g` |
| No llega el packet | Verificar que ambos dispositivos estén en la misma red Tailscale |
| Llega pero no enciende | El motherboard puede no soportar WOL en shutdown completo. Probar en suspensión en su lugar. |