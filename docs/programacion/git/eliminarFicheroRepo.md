# Cómo eliminar archivos de un repositorio Git después de ignorarlos

    git filter-branch --force --index-filter 'git rm --cached --ignore-unmatch ./nasa/.env' --prune-empty --tag-name-filter cat -- --all


Bibliografía
------------
 * https://dev.to/matiasfha/git-como-eliminar-un-archivo-de-la-historia-mpp
 * https://blog.gitguardian.com/rewriting-git-history-cheatsheet/