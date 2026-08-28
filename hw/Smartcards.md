# Instalación de Smartcards

El dispositivo es:
```bash
$ lsusb | grep SCM
Bus 002 Device 004: ID 04e6:5116 SCM Microsystems, Inc. SCR331-LC1 / SCR3310 SmartCard Reader
```

## Instalación

Instalamos los drivers
```bash
sudo pacman -S ccid opensc
```

Y activamos el servicio:
```bash
sudo systemctl enable pcscd
sudo systemctl start pcscd
```

Instalamos las utilidades de smartcard
```bash
sudo pacman -S pcsc-tools
```

## Escanear lector de tarjetas

Insertamos una tarjeta y escaneamos con la utlidad recien instalada:

```bash
$ pcsc_scan 
PC/SC device scanner
V 1.6.2 (c) 2001-2022, Ludovic Rousseau <ludovic.rousseau@free.fr>
Using reader plug'n play mechanism
Scanning present readers...
0: SCM Microsystems Inc. SCR 3310 [CCID Interface] 00 00
 
Sun Apr 23 09:55:40 2023
 Reader 0: SCM Microsystems Inc. SCR 3310 [CCID Interface] 00 00
  Event number: 1
  Card state: Card inserted, Shared Mode, 
  ATR: 3B FF 18 00 00 81 31 FE 45 00 6B 11 05 07 00 01 21 01 43 4E 53 10 31 80 4A

ATR: 3B FF 18 00 00 81 31 FE 45 00 6B 11 05 07 00 01 21 01 43 4E 53 10 31 80 4A
+ TS = 3B --> Direct Convention
+ T0 = FF, Y(1): 1111, K: 15 (historical bytes)
  TA(1) = 18 --> Fi=372, Di=12, 31 cycles/ETU
    129032 bits/s at 4 MHz, fMax for Fi = 5 MHz => 161290 bits/s
  TB(1) = 00 --> VPP is not electrically connected
  TC(1) = 00 --> Extra guard time: 0
  TD(1) = 81 --> Y(i+1) = 1000, Protocol T = 1 
-----
  TD(2) = 31 --> Y(i+1) = 0011, Protocol T = 1 
-----
  TA(3) = FE --> IFSC: 254
  TB(3) = 45 --> Block Waiting Integer: 4 - Character Waiting Integer: 5
+ Historical bytes: 00 6B 11 05 07 00 01 21 01 43 4E 53 10 31 80
  Category indicator byte: 00 (compact TLV data object)
    Tag: 6, len: B (pre-issuing data)
      Data: 11 05 07 00 01 21 01 43 4E 53
    Mandatory status indicator (3 last bytes)
      LCS (life card cycle): 10 (Proprietary)
      SW: 3180 (Error not defined by ISO 7816)
+ TCK = 4A (correct checksum)

Possibly identified card (using /usr/share/pcsc/smartcard_list.txt):
3B FF 18 00 00 81 31 FE 45 00 6B 11 05 07 00 01 21 01 43 4E 53 10 31 80 4A
	Oberthur ID-One Cosmo V7-n it's a java card 2.2.2
	Izenpe Certificado Ciudadano (eID)
	https://www.izenpe.eus/informacion/certificado-ciudadano/s15-content/es/
```

## NNS

Network Security Services (NSS) es un conjunto de bibliotecas diseñadas para admitir el desarrollo multiplataforma de aplicaciones de servidor y cliente con seguridad habilitada.

```bash
$ sudo pacman -S nss
```
`
### Uso

NSS se implementa en términos de operaciones en una lista configurada dinámicamente de módulos PKCS #11. Cada módulo puede ejecutar operaciones criptográficas y almacenar objetos criptográficos. La lista configurada de módulos generalmente se almacena en un directorio arbitrario, proporcionado por un usuario en la inicialización de NSS, en el archivo pkcs11.txt. La lista siempre contiene un módulo integrado "NSS Internal PKCS #11 Module" con tokens "NSS Generic Crypto Services" y "NSS Certificate DB". El primer token proporciona mecanismos criptográficos como RSA, SHA256, TLS, etc. El segundo token almacena certificados y claves privadas en el mismo directorio proporcionado por el usuario en los archivos cert9.db y key4.db. Los archivos pkcs11.txt, cert9.db y key4.db también se denominan "bases de datos NSS". Las rutas a las bases de datos NSS para algunas aplicaciones se enumeran en la siguiente tabla. Debe proporcionar alguna ruta para cada operación. Los ejemplos a continuación usarán ~/.pki/nssdb/.

|     Application     |	Path to NSS databases           |
|---------------------|---------------------------------|
| chromium, evolution |	~/.pki/nssdb/                   |
| firefox             |	~/.mozilla/firefox/<profile>/   |
| thunderbird         |	~/.thunderbird/<profile>/       |
| libreoffice-fresh   |	configurable via Options [1]    |


Bibliografía
------------
 * https://wiki.archlinux.org/title/Smartcards
 * https://wiki.archlinux.org/title/Network_Security_Services
 * https://man.archlinux.org/man/modutil.1
 * https://man.archlinux.org/man/pkcs11-register.1
 * https://wiki.archlinux.org/title/User:Timofonic/DNIe_(Espa%C3%B1ol)
 * https://elblogdepicodev.blogspot.com/2013/11/instalar-un-lector-de-tarjetas-inteligentes-en-arch-linux.html
 * https://aprendolinux.com/instalar-dni-electronico-linux/