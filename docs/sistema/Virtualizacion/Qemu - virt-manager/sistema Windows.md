# Proyecto: Laboratorio de Windows Virtualizados en Arch Linux

Esta documentación detalla la configuración de un entorno de 3 máquinas virtuales Windows ligeras (Tiny) comunicadas entre sí y con acceso a recursos del host.

---

## 1. Perfil del Sistema (Host)
- **SO:** Arch Linux
- **CPU:** Intel Xeon E5-2680 (Plataforma x99)
- **RAM:** 32 GB
- **Software:** QEMU/KVM, virt-manager, Samba.

## 2. Configuración Crítica de Libvirt
Para gestionar correctamente las redes virtuales en Arch, se debe evitar la sesión de usuario y utilizar la de Sistema.

- **Conexión:** qemu:///system
- **Red Virtual:** Se utiliza el puente por defecto virbr0.
- **Comandos de activación:**
  $ sudo virsh net-start default
  $ sudo virsh net-autostart default

## 3. Despliegue de Máquinas Virtuales (VMs)
Para ahorrar recursos y tiempo, se utilizó una imagen Tiny Windows.

1. **Instalación Base:** Se creó una VM maestra optimizada con el modelo de CPU host-passthrough.
2. **Drivers:** Instalación de virtio-win para mejorar rendimiento de disco y red.
3. **Clonación:** Las VMs 2 y 3 se generaron mediante la función de clonado de virt-manager para asegurar identificadores de hardware únicos (MAC) y nombres de red distintos sin reinstalar el SO.

## 4. Configuración de Red e Interconectividad
Para que las VMs se vean entre ellas (ping y servicios), se realizaron los siguientes ajustes internos en Windows:

- **Perfil de Red:** Cambiado de "Público" a "Privado".
- **Regla de Firewall (ICMP):** Habilitada para permitir el tráfico de diagnóstico (Ping).
  - Comando PowerShell (Admin):
    netsh advfirewall firewall add rule name="Permitir Ping" protocol=icmpv4:8,any dir=in action=allow
- **IPs del Entorno:** Rango 192.168.122.x.

## 5. Compartición de Archivos (Samba)
Se configuró un servidor Samba en Arch Linux para servir archivos desde discos montados en /mnt/.

### Configuración de /etc/samba/smb.conf:
[global]
    workgroup = WORKGROUP
    server string = ArchHost
    netbios name = archlinux
    security = user
    map to guest = Bad User
    interfaces = lo virbr0
    bind interfaces only = yes

[Compartida]
    path = /mnt/datos2/temp
    writable = yes
    browsable = yes
    guest ok = yes
    force user = theasker

### Solución a Problemas de Acceso:
1. **Permisos de Ruta:** Se aplicó "sudo chmod o+x" a /mnt y /mnt/datos2 para permitir el acceso al servicio Samba a través de los puntos de montaje.
2. **Punto de Acceso:** En lugar de usar la IP física del host (192.168.1.69), las VMs deben conectar a la IP de la interfaz virtual interna: \\192.168.122.1\Compartida.

## 6. Optimizaciones Finales
- **Hostname:** Cambio manual del nombre de equipo en cada Windows para evitar colisiones en el protocolo NetBIOS.
- **Firewall del Host:** Asegurar que la interfaz virbr0 esté permitida en el firewall de Arch.

---
Documento generado para soporte técnico de laboratorio virtual en Arch Linux.