# Amazon Web Services (Miguel Arranz)

**Región** Sería un datacenter donde podemos trabajar con los productos de AWS. En Europa a día de hoy hay 2 una en Irlanda y otra en Frankfurt. Cada región tiene 2 ó 3 **zonas de disponibilidad** o **Availability Zones** separados totalmente unos de otros, estas zonas de disponibilidad podemos elegir en cual podemos usar para desplegar los recursos.

## Key Pair

Es un conjunta de claves público/privadas para acceder a nuestros servidores. 

Después de crearla se descarga un fichero con extensión .pem y podemos usar esta clave para entrar en nuestros servidores sin necesidad de usar una contraseña.

[http://docs.aws.amazon.com/es_es/AWSEC2/latest/UserGuide/AccessingInstancesLinux.html](Connecting to Your Linux Instance Using SSH)

## Security Groups

En las **Security Groups** decimos las reglas de firewall que va a tener una instancia. Donde ponemos los puertos que abrimos y hacia dónde los abrimos.

## Elastic IPs

Cuando levantamos una instancia, va a tener una ip pública y una ip privada. Con las ips privadas de las intancias podemos crear una infraestructura ( 192.168.0.0/24 ), pero la ip pública, puede cambiar cada vez que reiniciamos o paramos la instancia, es decir, no es una ip fija. Para solucionar esto y poder conectarnos correctamente con nuestra instancia, usaremos **Elastic IP**. Cuando generamos una Elastic IP, el sistema generará una ip que va a estar siempre asociada a tu cuenta.

La IP pública que tiene cada instancia no es siempre la misma, es decir, cuando tu paras y vuelves a arrancar una instancia, la IP pública puede cambiar.

Cuando creemos una instancia, nos permitirá asiciar esta ip a la instancia para poder acceder.

Por todo esto tenemos la **Elastic IPs**, que crea una IP asignada a tu cuenta de AWS y que podemos asignar a cada instancia.

Cada usuario tiene una VPC, es decir tiene su propia red privada para poder comunicar sus máquinas virtuales.

## Instancias

Cuando terminamos una instancia, la destruimos y no podremos acceder a ellas más, aunque se quedan en la lista de instancias con el status de "terminated", durante unas horas.

### AMIs

Cuando lanzamos una instancia, tenemos que usar un AMI (Amazon Machina Images) que es una imagen de una máquina virtual ya preparada para ser lanzada.

En **AWS marketplace** tenemos una lista de máquinas virtuales para ser usadas (https://aws.amazon.com/marketplace)

### Configuración de instancias.

#### Subnet

Aquí elegiremos la subred que puede estar en una **Availability Zone** diferente, que son sitios diferentes de cada región.

### Conexión por ssh


Para conectarnos a una instancia por ssh, tendremos que ir a **Elastic IP** y asociar esa ip a la instancia.

	$ ssh -l ubuntu -i /mnt/datos1/Documentos/aws-key-pair/Theasker.pem 52.51.239.175
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	@         WARNING: UNPROTECTED PRIVATE KEY FILE!          @
	@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
	Permissions 0644 for '/mnt/datos1/Documentos/aws-key-pair/Theasker.pem' are too open.
	It is required that your private key files are NOT accessible by others.
	This private key will be ignored.
	Load key "/mnt/datos1/Documentos/aws-key-pair/Theasker.pem": bad permissions
	Permission denied (publickey).

Nos da un error de permisos. Nos dice que tiene demasiados permisos, por lo que tenemos que reducirselos con:

	$ chmod 600 Theasker.pem


