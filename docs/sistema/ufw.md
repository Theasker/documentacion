# Tutorial de UFW (Uncomplicated Firewall)

## ¿Qué es UFW?

UFW (Uncomplicated Firewall) es una herramienta para gestionar iptables de forma sencilla. Está diseñada para que cualquier persona pueda proteger su sistema sin conocer los detalles complejos del firewall de Linux.

## Instalación

```bash
sudo pacman -S ufw
```

## Estado

```bash
sudo ufw status verbose
```

Al principio mostrará `Status: inactive`.

## Reglas básicas

### Activar / Desactivar

```bash
sudo ufw enable    # Activar firewall
sudo ufw disable   # Desactivar firewall
```

### Política por defecto

```bash
# Denegar todo lo entrante, permitir todo lo saliente
sudo ufw default deny incoming
sudo ufw default allow outgoing
```

Esto es la base: por defecto nada externo entra, pero tu ordenador puede salir a internet con normalidad.

### Permitir puertos

```bash
# Puerto simple (TCP por defecto)
sudo ufw allow 22

# Puerto con protocolo específico
sudo ufw allow 80/tcp
sudo ufw allow 53/udp

# Rango de puertos
sudo ufw allow 6000:6007/tcp
```

### Permitir desde una IP o red específica

```bash
# Solo una IP
sudo ufw allow from 192.168.1.100 to any port 22

# Toda una red local
sudo ufw allow from 192.168.1.0/24 to any port 445 proto tcp

# Puerto y protocolo específicos desde una red
sudo ufw allow from 192.168.1.0/24 to any port 139,445 proto tcp
```

### Denegar / Bloquear

```bash
sudo ufw deny 23/tcp           # Bloquear puerto 23 (Telnet)
sudo ufw deny from 10.0.0.0/8 # Bloquear toda una red
```

### Eliminar reglas

```bash
# Por número (sácalo con 'ufw status numbered')
sudo ufw status numbered
sudo ufw delete 3

# O repitiendo la regla
sudo ufw delete allow 22/tcp
```

## Verbosidad

```bash
sudo ufw status         # Resumen
sudo ufw status verbose # Detallado
sudo ufw status numbered # Con números para borrar
```

## Logging

```bash
sudo ufw logging on     # Activar logs
sudo journalctl -u ufw  # Ver logs con systemd
```

## Reset (volver a empezar)

```bash
sudo ufw reset
```

Esto desactiva UFW y borra todas las reglas.

## Configuración para tu servidor (ejemplo real)

Lo mínimo para proteger tu máquina con Samba y SSH solo desde casa:

```bash
# 1. Política base
sudo ufw default deny incoming
sudo ufw default allow outgoing

# 2. SSH desde tu LAN
sudo ufw allow from 192.168.1.0/24 to any port 22 proto tcp

# 3. Samba desde tu LAN
sudo ufw allow from 192.168.1.0/24 to any port 139,445 proto tcp

# 4. Activar
sudo ufw enable
sudo ufw status verbose
```

## Reglas por aplicación (atajos)

UFW trae perfiles para aplicaciones conocidas:

```bash
sudo ufw app list                    # Ver perfiles disponibles
sudo ufw allow 'OpenSSH'            # Permitir SSH
sudo ufw allow 'Samba'              # Permitir Samba
```

## Notas importantes

1. **No te olvides de permitir SSH antes de activar** si accedes remotamente, o te quedarás fuera.
2. `ufw enable` se mantiene al reiniciar el sistema.
3. Si algo no funciona tras activarlo, usa `sudo ufw disable` para desactivarlo al momento.
4. Para ver si un puerto está siendo bloqueado: `sudo ss -tlnp | grep <puerto>` (te dice si el servicio está escuchando).

## Resumen visual

```
┌─────────────┐      ┌─────────────┐
│  Internet   │ ───→ │   UFW       │ ───→ ┌──────────┐
│ (resto)     │  ✗   │ (firewall)  │       │ Servicios│
└─────────────┘      │             │       │ (SSH,    │
                     │ allow:      │       │  Samba)  │
┌─────────────┐      │ - 22 (LAN)  │       └──────────┘
│  Tu router  │ ───→ │ - 139 (LAN) │
│  192.168.1.0│  ✓   │ - 445 (LAN) │
└─────────────┘      └─────────────┘
```

- Internet → bloqueado por defecto
- Tu red local (192.168.1.x) → permitido solo lo que hayas abierto
- Tu máquina → puede salir a internet sin restricciones
```
