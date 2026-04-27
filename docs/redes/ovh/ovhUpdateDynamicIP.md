# Actualización de un Dynhost de OVH

A simple (cron) script to update DynHost on OVH hosting

## Prerequisites
This script works with two linux commands : curl and dig.
If you do not have the dig you must install it from the package "dnsutils" :
```sh
sudo apt-get update
sudo apt-get install dnsutils
```

## How to use
1. Download the dynhost.sh script and put it in the folder /etc/cron.hourly (to check every hour)
2. Add execution permissions to file : chmod +x dynhost.sh
3. Rename dynhost.sh to dynhost (because "." at the end of the file name is not allowed in cron)
4. Modify the script with variables : HOST, LOGIN, PASSWORD

## How it works
1. The command dig is used to retrieve the IP address of your domain name.
2. The command curl (with the website ifconfig.co) is used to retrieve the current public IP address of your machine.
3. The two IPs are compared and if necessary a curl command to OVH is used to update your DynHost with your current public IP address.
4. Log file is on ```/var/log/dynhostovh.log```

## The code
```bash
#!/usr/bin/env sh

# Account configuration
HOST=DOMAINE_NAME
LOGIN=LOGIN
PASSWORD=PASSWORD

PATH_LOG=/var/log/dynhostovh.log

# Get current IPv4 and corresponding configured
HOST_IP=$(dig +short $HOST A)
CURRENT_IP=$(curl -m 5 -4 ifconfig.co 2>/dev/null)
if [ -z $CURRENT_IP ]
then
  CURRENT_IP=$(dig +short myip.opendns.com @resolver1.opendns.com)
fi
CURRENT_DATETIME=$(date -R)

# Update dynamic IPv4, if needed
if [ -z $CURRENT_IP ] || [ -z $HOST_IP ]
then
  echo "[$CURRENT_DATETIME]: No IP retrieved" >> $PATH_LOG
else
  if [ "$HOST_IP" != "$CURRENT_IP" ]
  then
    RES=$(curl -m 5 -L --location-trusted --user "$LOGIN:$PASSWORD" "https://www.ovh.com/nic/update?system=dyndns&hostname=$HOST&myip=$CURRENT_IP")
    echo "[$CURRENT_DATETIME]: IPv4 has changed - request to OVH DynHost: $RES" >> $PATH_LOG
  fi
fi
```

Bibliografía
------------
https://github.com/yjajkiew/dynhost-ovh