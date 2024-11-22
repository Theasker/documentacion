# Volver a un commit anterior

    $ git log --oneline
    e4cd6b4 (HEAD -> main, origin/main) my change
    99541ed second change
    41f1f2a first change

Para revertir los 2 últimos commits

    git revert e4cd6b4 99541ed


También podemos ejecutar el comando git revert de la siguiente manera.

    $ git revert HEAD~2..HEAD

Luego guardamos el commit con la información:

    $ git commit -m "reverted commits e4cd6b4 99541ed"

Bibliografía
------------
 * https://www.delftstack.com/es/howto/git/git-go-back-to-previous-commit/#:~:text=aplicar%20al%20repositorio.-,Uso%20de%20git%20revert%20para%20volver%20a%20un%20commit%20anterior,para%20anular%20el%20commit%20anterior.

## Cómo deshacer un commit

Cómo deshacer un git pull y volver a un commit anterior en Git

A veces, después de hacer un git pull, te das cuenta de que algo salió mal: tal vez se eliminó accidentalmente una carpeta importante o se introdujeron cambios no deseados. Afortunadamente, Git te permite deshacer un git pull y volver a un commit anterior para restaurar el estado del repositorio. En este artículo, te guiaré a través de un ejemplo práctico paso a paso sobre cómo hacerlo.

### Paso 1: Verifica el historial de Git con reflog

Lo primero que debes hacer es identificar el commit anterior al git pull que quieres deshacer. Para esto, usaremos el comando git reflog, que muestra un historial detallado de las acciones recientes en el repositorio, incluidas las referencias a commits.

Abre una terminal o línea de comandos y navega a tu repositorio:

 
```bash
cd Repo
```

Luego, ejecuta:
```bash
2f10b05 (HEAD -> master, origin/master) HEAD@{0}: pull: Fast-forward
414b196 HEAD@{1}: commit (merge): Resolved merge conflicts
6efd60a HEAD@{2}: commit: feat: setup pre-production environment
a4012f2 HEAD@{3}: commit: rokitoh
ff101f9 (tag: 0.0.0-KO, origin/release, origin/hotfix, origin/develop, origin/HEAD, develop) HEAD@{4}: checkout: moving from develop to master
ff101f9 (tag: 0.0.0-KO, origin/release, origin/hotfix, origin/develop, origin/HEAD, develop) HEAD@{5}: clone: from https://github.com/redorbita/redorbita__iac.git
```

En este caso, el commit a4012f2 es el que queremos restaurar, justo antes del git pull que hicimos.

### Paso 2: Restablece el repositorio al commit anterior con git reset

Una vez que hayas identificado el commit deseado (en este ejemplo, a4012f2), puedes usar git reset para volver a ese punto en el historial.

Si deseas volver completamente al estado anterior, sin conservar ningún cambio introducido por el git pull, usa el siguiente comando:
```bash
git reset --hard a4012f2
```

**¿Qué hace `git reset --hard`?**

Este comando restablece tu repositorio exactamente al estado en que estaba en el commit a4012f2. El modificador --hard indica que se eliminarán todos los cambios que no se hayan confirmado, incluidas las modificaciones en los archivos de tu área de trabajo.
Paso 3: Verifica el estado del repositorio

Después de ejecutar el comando de reset, es recomendable verificar el estado de tu repositorio para asegurarte de que todo está como esperas.

Ejecuta el siguiente comando:

```bash
git status
```

Deberías ver un mensaje que indica que el repositorio está en la rama master y que no hay cambios pendientes:
```bash
On branch master
Your branch is up to date with 'origin/master'.

nothing to commit, working tree clean
```

### Paso 4: Verifica que la carpeta eliminada ha sido restaurada

Navega por el repositorio o usa comandos de exploración de archivos (ls, dir, etc.) para verificar que la carpeta que había sido eliminada por el git pull ahora está de nuevo presente.
Paso 5 (Opcional): Deshacer el pull pero mantener los cambios locales

Si, en lugar de perder todos los cambios locales, deseas mantener los archivos modificados en tu área de trabajo pero deshacer el git pull, puedes usar git reset --soft en lugar de --hard.

```bash
git reset --soft a4012f2
```

Con esta opción, se deshará el pull, pero los archivos seguirán marcados como modificados (no se eliminarán los cambios locales no confirmados).

### Conclusión

Restaurar tu repositorio a un estado anterior a un git pull es una acción útil cuando los cambios no deseados se introducen en tu código. Con git reset, puedes volver al estado anterior de manera rápida y eficiente. Solo recuerda que, si utilizas la opción --hard, cualquier cambio no confirmado se perderá, así que úsala con precaución.

## Bibliografia

* https://red-orbita.com/?p=12360#more-12360