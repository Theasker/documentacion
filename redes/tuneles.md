# 🚀 GUÍA DEFINITIVA DE TÚNELES Y EXPOSICIÓN DE SERVICIOS (POST-PROBADO)

Esta guía resume cómo exponer servicios locales o puentear restricciones de red 
en entornos de alta seguridad (como Oracle Cloud o redes corporativas).

---

## 1. NIP.IO | El traductor de nombres local
**Concepto:** Mapea una IP a un nombre de dominio sin configurar el archivo hosts.
**Uso:** Desarrollo web, pruebas de certificados y acceso desde móviles en la misma red.

### Pasos:
1. Levantar servidor local (ejemplo Python):
   $ python -m http.server 8000

2. Identificar IP de la máquina (ej. 192.168.1.15).

3. Acceder vía navegador:
   URL: http://cualquier-cosa.192.168.1.15.nip.io:8000

---

## 2. PINGGY.IO | Túnel SSH sin instalación
**Concepto:** Usa SSH para crear un túnel hacia un servidor público.
**Uso:** Exponer servicios rápido si el puerto 443 o 80 están abiertos para salida.

### Comandos Clave:

# A. Intento Estándar (Puerto 443 - Simula HTTPS)
ssh -p 443 -o StrictHostKeyChecking=no -R0:localhost:8000 qr@ssh.pinggy.io

# B. Si te pide contraseña (Falla de llaves en Oracle/VPS)
# Forzamos a no enviar llaves privadas locales:
ssh -o PubkeyAuthentication=no -o StrictHostKeyChecking=no -p 443 -R0:localhost:8000 qr@ssh.pinggy.io

# C. Si el DNS del VPS no resuelve 'ssh.pinggy.io':
# 1. Añadir IP manualmente al archivo hosts:
sudo echo "159.65.129.231 ssh.pinggy.io" >> /etc/hosts
# 2. Reintentar el comando anterior.

---

## 3. CLOUDFLARED | El salto de Firewall definitivo
**Concepto:** Usa tráfico HTTP/2 puro. Indetectable para firewalls que bloquean SSH.
**Uso:** Cuando Pinggy/ngrok fallan por inspección de paquetes (DPI).

### Instalación y Ejecución:

# 1. Descarga del binario (Linux AMD64):
curl -L -o cloudflared https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64
chmod +x cloudflared

# 2. Túnel rápido para servicio LOCAL (ej. tu servidor Python):
./cloudflared tunnel --url http://localhost:8000

# 3. Túnel para sitio EXTERNO (ej. saltarse bloqueo de Reddit):
# Nota: Usamos --http-host-header para que el destino no nos rechace.
./cloudflared tunnel --url https://www.reddit.com --http-host-header www.reddit.com

---

## 🛠 SOLUCIÓN DE PROBLEMAS (TROUBLESHOOTING)

### Error: "kex_exchange_identification"
- Causa: El firewall detecta SSH en puerto 443 y corta la conexión.
- Solución: DEJAR de usar Pinggy y usar Cloudflared (Punto 3).

### Error: "502 Bad Gateway" o "Invalid Host"
- Causa: El servidor destino (Reddit, etc) sabe que vienes de un proxy.
- Solución: Usar la bandera --http-host-header en cloudflared o crear un 
  pequeño script proxy en Node.js que limpie los headers en el VPS.

### Reparar DNS en VPS de Oracle rápido:
sudo echo "nameserver 8.8.8.8" > /etc/resolv.conf