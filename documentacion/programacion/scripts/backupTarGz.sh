#!/bin/bash

#==============================================================================
# title: ScriptBackup.sh
# description:  Automatic Backup Script in Bash Shell
# author: Mauricio Segura Ariño
# date: 20191008
# usage: bash ScriptBackup.sh
# notes: Configure the initVars function and init de variables
# bash_version: 4.4.12(3)-release
# help links:
# https://enlaterminal.wordpress.com/2017/02/01/copias-de-seguridad-incrementales-y-diferenciales/
#==============================================================================

function initVars {
	DATE=$(date +"%Y%m%d-%H%M%S")
	DIRBACKUP=/etc/scripts/backups/backup
	DIRSOURCE=/etc/scripts/backups/datos
	DIRSNAPS=/etc/scripts/backups/snaps
	DIRLOGS=/etc/scripts/backups/log
	NUMINCREMENTAL=3
	NUMFULL=3
	RCLONESOURCE=$DIRBACKUP
	RCLONETARGET=box:/temp
	STATUS=0

	# Variables de nombres de fichero
	# Ficheros de backup
	FICHFULLBACKUP="$DIRBACKUP/"${DATE}_full.tar.gz
	FICHINCREMENTALBACKUP="$DIRBACKUP/"${DATE}_incremental.tar.gz
	# Ficheros snap
	FICHFULLSNAP="$DIRSNAPS/"full.snap
	FICHINCREMENTALSNAP="$DIRSNAPS/"incremental.snap
	# Ficheros de log
	FICHLOG="$DIRLOGS/backup.log"
	#FICHFULLLOG="$DIRLOGS/backupFull.log"
	#FICHINCREMENTALLOG="$DIRLOGS/backupIncremental.log"
	#FICHSNAPLOG="$DIRLOGS/backupSnap.log"
}

function makeDirs {
	# Comprobamos si existen los directorios y ficheros de log para crearlos
	if [ ! -d $DIRBACKUP ]; then echo "Creo el directorio $DIRBACKUP"; mkdir -p $DIRBACKUP; fi
	if [ ! -d $DIRSOURCE ]; then echo "Creo el directorio $DIRSOURCE"; mkdir -p $DIRSOURCE; fi
	if [ ! -d $DIRSNAPS ]; then echo "Creo el directorio $DIRSNAPS"; mkdir -p $DIRSNAPS; fi
	if [ ! -d $DIRLOGS ]; then echo "Creo el directorio $DIRLOGS"; mkdir -p $DIRLOGS; fi
	touch $FICHLOG
	#touch $FICHINCREMENTALLOG
	#touch $FICHSNAPLOG
}

function showVars {
	echo "DATE: $DATE"
	echo "DIRBACKUP: $DIRBACKUP"
	echo "DIRSOURCE: $DIRSOURCE"
	echo "DIRSNAPS: $DIRSNAPS"
	echo -e "DIRLOGS= $DIRLOGS\n"

	echo "FICHFULLBACKUP: $FICHFULLBACKUP"
	echo "FICHINCREMENTALBACKUP: $FICHINCREMENTALBACKUP"
	echo "FICHFULLSNAP: $FICHFULLSNAP"
	echo "FICHINCREMENTALSNAP: $FICHINCREMENTALSNAP"
	echo "FICHFULLLOG; $FICHFULLLOG"
	echo "FICHLOG; $FICHLOG"
	echo -e "FICHSNAPLOG: $FICHSNAPLOG\n"
}

function checkBackupType {
	BACKUPTYPE=""
	# Compruebo si existe algun backup completo
	CHECKLOG=$(grep full $FICHLOG)
	echo "Compruebo si existe algun backup completo ..."
	if [ -z "$CHECKLOG" ]; then
		echo "NO hay backups completos"
		BACKUPTYPE="full"
	else # Hay alguna copia Completa
		echo "Hay $(ls -la $DIRBACKUP | grep full | wc -l $1) copia completas"
		# Comprobamos si hay que hacer copia completa o incremental
		echo "Compruebo si existe algun backup incremental ..."
		CHECKLOG=$(grep incremental $FICHLOG)
		if [ -z "$CHECKLOG" ]; then # NO hay ningún backup incremental
			echo "No hay backups incrementales"
			BACKUPTYPE="incremental"
		else # Compruebo cuantos incrementales hay respecto al último completo
			echo "Compruebo cuantos incrementales hay respecto al último completo ..."
			LASTFICHFULL=$(grep full $FICHLOG | tail -n 1)
			LISTINCREMENTAL=$(grep incremental $FICHLOG)
			# Recorro todos los incrementales
			let NUMBACKUPCHECK=$NUMINCREMENTAL+2
			COUNT=0
			# Cuento los incrementales que hay desde la última copia completa
			for line in $(grep incremental $FICHLOG | tail -n $NUMBACKUPCHECK); do
				# Compruebo si el fichero es más antiguo que el backup completo de referencia
				# Si el fichero que leo ($line) es mas antiguo que el último fichero de backup completo ...
				if [[ $(stat -c %Y $line) -gt $(stat -c %Y $LASTFICHFULL) ]]; then
					let COUNT=COUNT+1
				fi
			done
			echo "El número de copias incrementales después de la última copia completa es: $COUNT"
			# Si las copias incrementales desde la última completa 
			# son menos de las que ha de haber se hace otra incremental
			if [[ $COUNT -lt $NUMINCREMENTAL ]];then
				BACKUPTYPE="incremental"
			else
				BACKUPTYPE="full"
			fi
		fi
	fi
}

function backupFull {
	echo "Creando copia completa ..."
	# Borro antes el fichero de snap de copia completa para que cree uno nuevo
	if [ -e $FICHINCREMENTALSNAP ]; then rm $FICHINCREMENTALSNAP; fi
	if [ -e $FICHFULLSNAP ]; then rm $FICHFULLSNAP; fi

	tar -cvf $FICHFULLBACKUP -g $FICHFULLSNAP $DIRSOURCE
	if [ $? -ne 0 ]; then
		echo "Error en la copia completa"
		STATUS=1
	else
		echo "$FICHFULLBACKUP" >> $FICHLOG
		# Creo un nuevo fichero de snap incremental a partir del de copia completa
		cp $FICHFULLSNAP $FICHINCREMENTALSNAP
	fi
}

function backupIncremental {
	echo "Creando backup incremental ..."
	# echo "tar -cvf $FICHINCREMENTALBACKUP -g $FICHINCREMENTALSNAP $DIRSOURCE"
	tar -cvf $FICHINCREMENTALBACKUP -g $FICHINCREMENTALSNAP $DIRSOURCE
	# Compruebo que se ha realizado
	if [ $? -ne 0 ]; then
		echo "Error en la copia incremental"
		STATUS=1
	else
		echo "$FICHINCREMENTALBACKUP" >> $FICHLOG
	fi
}

function checkDeleteBackup {
	echo "Comprobando backups a eliminar ..."
	LASTFICHFULL=$(basename $(grep full $FICHLOG | tail -n $(($NUMFULL+1)) | head -n 1))
	cd $DIRBACKUP
	TODELETE=$(find . -type f ! -newer $LASTFICHFULL ! -name $LASTFICHFULL)
	if [ ! -z "$TODELETE" ]; then # Si hay algun backup a borrar
		echo "Estos ficheros se han borrado:"
		find . -type f ! -newer $LASTFICHFULL ! -name $LASTFICHFULL
		find . -type f ! -newer $LASTFICHFULL ! -name $LASTFICHFULL -delete
		cd ..
	fi
}

function sync {
	echo "DIRBACKUP: $DIRBACKUP"
	echo "RCLONESOURCE: $RCLONESOURCE"
	echo "RCLONETARGET: $RCLONETARGET"

	echo rclone sync $RCLONESOURCE $RCLONETARGET
	rclone sync $RCLONESOURCE $RCLONETARGET
}

initVars
makeDirs
#showVars

#Compruebo el tipo de backup a realizar
checkBackupType
if [ "$BACKUPTYPE" = "full" ]; then
	backupFull
elif [[ "$BACKUPTYPE" = "incremental" ]]; then
	backupIncremental
fi

checkDeleteBackup
sync
exit $STATUS
@Theasker
