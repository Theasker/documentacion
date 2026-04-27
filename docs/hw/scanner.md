# Howto: Mustek 1200 UB Plus USB Scanner Install

Modificación de los parámetros del kernel
```
Device Drivers  --->
   USB support  --->
   < >   OHCI HCD support
   <*>   UHCI HCD (most Intel and VIA) support
```

```
Device Drivers -->
   SCSI device support -->
      [*] legacy /proc/scsi support
```

Instalo apliaciones para la detección del scaner y sus parámetros de configuración

	emerge -av usbutils lsusb
	emerge media-gfx/sane-backends

```
# sane-find-scanner -q
found USB scanner (vendor=0x055f, product=0x021c [USB Scanner], chip=GT-6816) at libusb:003:002
```

Creo el fichero con las reglas udev `/etc/udev/rules.d/70-libsane.rules`

	# Packard Bell 1200 plus
	ATTRS{idVendor}=="055f", ATTRS{idProduct}=="021c", MODE="0664", GROUP="scanner", ENV{libsane_matched}="yes"

Agrego el usuario al grupo scanner

	gpasswd -a <yourusername> scanner

Copio y descargo los drivers del scanner de [SANE gt68xx-backend site](http://www.meier-geinitz.de/sane/gt68xx-backend/):

	cd /usr/share/sane/gt68xx
	sudo wget http://www.meier-geinitz.de/sane/gt68xx-backend/firmware/sbfw.usb

Edito el archivo de configuración correspondiente `/etc/sane.d/gt68xx.conf` y descomento la línea donde pone:

	#override "mustek-scanexpress-1200-ub-plus"

Recargo el servicio udev:

	# /etc/init.d/udev reload


## Links

  * [Howto: Mustek 1200 UB Plus USB Scanner Install / Setup  ](https://ubuntuforums.org/showthread.php?t=154429)
  * [Gentoo - USB Scanner](http://gentoo-en.vfose.ru/wiki/USB_Scanner)