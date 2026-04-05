# How to fix in-acessible instance in Oracle Cloud Infrastructure (OCI)
Steps to recover inaccessible OCI Compute instances, fix ssh configuration, update ssh key pairs, fix /etc/fstab entries, fix boot parameters and so on

Step-by-Step Guide
1. Stop the instance from the OCI Compute console.
2. Detach the volume from the OCI console: (let's call it broken volume) [1].
    - Select the instance from the OCI Compute Console. 
    - Select 'Boot Volume' from the Resources.
    - Click the '...' on the boot volume snd select 'Detach'.
    - (Almacenamiento > Almacenamiento de bloques > Volúmenes de inicio > Click en la instancia > Instancias asociadas > ... > Desasociar de instancia)
3. Launch a recovery instance in the same AD. (Lets call it recovery instance). You may use an existing instance in the same Availability domain.
4. Once the instance is started, attach the broken volume as Block Volumes.
    - Select 'Attached Block Volumes' from the Resources.
    - Click ' Attach Block Volume' and select the broken volume from the 'BLOCK VOLUME' tab.
    - Click Attach - just make sure you have selected READ/Write' and attach the volume as Paravirtualized one so that you do not have to run the iSCSI commands.
    - (Recursos informáticos > Instancias > Click en la instancia > Volúmenes de bloque asociados > Asociar volumen en bloque)
5. If you have attached the volume as iSCSI disk, connect to the disk to the recovery instance using iSCSI commands from the OCI console [2].
6. SSH into the recovery instance and follow the steps:

Important: Run the commands as root user.

If the disk is attached and connected properly, you should be able to view it using 'lsblk' or similar commands:

[opc@jay ~]$ lsblk
NAME   MAJ:MIN RM  SIZE RO TYPE MOUNTPOINT 
sda      8:0    0 46.6G  0 disk 
├─sda1   8:1    0  512M  0 part /boot/efi 
├─sda2   8:2    0    8G  0 part [SWAP] 
└─sda3   8:3    0 38.1G  0 part / 
sdb      8:16   0   47G  0 disk >>>>> the disk is detected as /dev/sdb 
├─sdb1   8:17   0  512M  0 part >>>>> Boot partition 
├─sdb2   8:18   0    8G  0 part 
└─sdb3   8:19   0 38.1G  0 part >>>>> Root partition
[opc@jay ~]$

7. Mount the root partition on the broken disk on the temporary directory.
# mkdir /recovery 
# mount /dev/sdb3 /recovery
You might need to specify -o nouuid for some OS.

8. Analyze the logs and perform the recovery steps.


You have access to root volume of the broken instance under /recovery directory. You may now check the logs on the broken instance and apply the fixes accordingly.

For ssh issues, I suggest checking logs under /recovery/var/log/secure (For RHEL/OEL/CentOS), /recovery/var/log/auth.log (Debian/Ubuntu).
You can add a pubic key to the opc using by appending the key to file /recovery//home/opc/.ssh/authorized_keys file.
For boot issues, check /recovery/var/log/boot.log, /recovery/var/log/messages, /recovery/var/log/dmesg log files.
You can check and update the fstab entries here: /recovery/etc/fstab.

Once the recovery processes are done, you may proceed to detach the volume and attach it back to the original instance as boot volume.
12. If you have attached the volume as iSCSI volume, logout from the iSCSI session using the commands available in the OCI console.
13. Detach the volume from the OCI console [3].
14. Attach the volume back to the original instance as boot volume.
    - Select the instance from the OCI Compute Console.
    - Select 'Boot Volume' from the Resources.
    - Click the '...' on the boot volume (it should be the same volume we detached earlier).
    - Select 'Attach'.
    - (Recursos informáticos > Instancias > Click en la instancia > Detalles de la instancia > Volumen de inicio > ... > Asociar Volumen de inicio
15. Start the instance.

16. Check if the issue has been fixed, if not you need to redo the above process to check the logs again and try to fix it.
17. Once the issue is fixed, you may terminate the recovery instance (if it was created for this troubleshooting).
More Information
[1] Detach Boot Volume: https://docs.us-phoenix-1.oraclecloud.com/Content/Block/Tasks/detachingabootvolume.htm
[2] Connecting to a Volume: https://docs.us-phoenix-1.oraclecloud.com/Content/Block/Tasks/connectingtoavolume.htm
[3] Disconnecting From a Volume: https://docs.us-phoenix-1.oraclecloud.com/Content/Block/Tasks/disconnectingfromavolume.htm
[4] Attaching a Boot Volume https://docs.us-phoenix-1.oraclecloud.com/Content/Block/Tasks/attachingabootvolume.htm
