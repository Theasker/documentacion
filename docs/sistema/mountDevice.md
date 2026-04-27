# How To Mount A USB Flash Disk On The Raspberry Pi

	ls -l /dev/disk/by-uuid/
	sudo mkdir /media/usb
	sudo chown -R pi:pi /media/usb
	sudo mount /dev/sda1 /media/usb -o uid=pi,gid=pi


	umount /media/usb

If you used the fstab file to auto-mount it you will need to use :

	sudo umount /media/usb

	sudo nano /etc/fstab

	UUID=18A9-9943 /media/usb vfat auto,nofail,noatime,users,rw,uid=pi,gid=pi 0 0

The “nofail” option allows the boot process to proceed if the drive is not plugged in. The “noatime” option stops the file access time being updated every time a file is read from the USB stick. This helps improve performance.
