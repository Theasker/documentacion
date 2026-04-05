**Copia completa**
rsync -avzhH ubuntu@laflordearagon.es:/home/ubuntu/temp ./backups/

**Copia incremental con Hard Links**
rsync -avzhb -H --delete --backup-dir=/home/ubuntu/temp/incrementales/copia_$(date +%d%m%Y%H%M%S) ubuntu@laflordearagon.es:/home/ubuntu/temp/ ./backups/

 * -H es para crear Hard Links

Con `--link-dest` compara para crear una copia incremental y lo que no se modifica crea un "Hard Link"

  rsync -azv -e "ssh -p $PORT" --link-dest $LASTBACKUP $SOURCE $DEST

## Bibliografía
* https://linuxconfig.org/how-to-create-incremental-backups-using-rsync-on-linux
* https://linuxconfig.org/rsync-command-examples
* https://www.youtube.com/results?search_query=copias+incrementales+con+rsync
* https://web.archive.org/web/20210516191405/https://www.vicente-navarro.com/blog/2008/01/13/backups-con-rsync/
* Hard Links y rsync: https://digitalis.io/blog/linux/incremental-backups-with-rsync-and-hard-links/