# Eliminar cambios

Si hemos tocado algún archivo y queremos dejarlo tal como estaba
```bash
git checkout -- nombrearchivo
```

Si añadimos un archivo al staging index [ git add index.html] y queremos volver enviarlo al working directory:
```bash
git reset HEAD index.html
```

Eliminar commits para siempre⚠️
```bash
git reset --hard 95ee28b
```

Elimina los commits de nuestro repositorio y deja los archivos del commit en el stagin index para luego volver hacer el commit
```bash
git reset --soft 95ee28b
```

Elimina los commits de nuestro repositorio y deja los archivos del commit en el working directory para luego volver hacer git add y git commit -m "mensaje"
```bash
git reset --mixed 95ee28b
```