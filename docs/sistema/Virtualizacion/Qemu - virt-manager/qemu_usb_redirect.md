# Scripts de ejemplo de máquina virtual para redireccionamiento de puertos USB
Habrá que instalar estos paquetes:
 * **Opción 1:** spice-gtk spice-protocol usbredir
 * **Opción 2:** spice-gtk-git spice-protocol-git usbredir-git

```bash
HD_w10="HDD/Qemu_Win10LTS.qcow2"
ISO_win="ISOS/Windows 10 LTSC 2021 TI Free.iso"
ISO_driv_virtio="ISOS/virtio-win-0.1.221.iso"

qemu-system-x86_64 \
    --enable-kvm -name "Win 10" -m 2G -smp2 \
    --machine type=q35,accel=kvm -cpu host \
    --drive file=$HD_w10,index=0,media=disk,if=virtio \
    -net nic,macaddr=52:54:20:f5:3e:92,model=virtio-net-pci \
    -net bridge,br=br0 \
    -usb -device usb-tablet \
    -device ich9-intel-hda -device hda-output \
    -vga virtio \
    -vga qxl \
    --object secret,id=contra,ifle=pass.txt \
    -spice port=5930,password-secret=contra \
    -rtc base=localtime,clock=host \
    # Copy/paste => https://www.spice-space.org/usbredir.html \
    -device ich9-usb-ehci1,id=usb \
-device ich9-usb-uhci1,masterbus=usb.0,firstport=0,multifunction=on \
-device ich9-usb-uhci2,masterbus=usb.0,firstport=2 \
-device ich9-usb-uhci3,masterbus=usb.0,firstport=4 \
-chardev spicevmc,name=usbredir,id=usbredirchardev1 \
-device usb-redir,chardev=usbredirchardev1,id=usbredirdev1 \
-chardev spicevmc,name=usbredir,id=usbredirchardev2 \
-device usb-redir,chardev=usbredirchardev2,id=usbredirdev2 \
-chardev spicevmc,name=usbredir,id=usbredirchardev3 \
-device usb-redir,chardev=usbredirchardev3,id=usbredirdev3
```

## Bibliografía
* https://www.spice-space.org/usbredir.html
* https://www.youtube.com/watch?v=q_IuNO3Dz98
  * https://wiki.archlinux.org/title/QEMU#USB_redirection_with_SPICE
  * https://www.spice-space.org/spice-user-manual.html
  * https://people.freedesktop.org/~teuf/spice-doc/html/ch02s06.html
  * https://forum.manjaro.org/t/solved-cannot-redirect-usb-devices-after-moving-vms-to-different-path/50363
