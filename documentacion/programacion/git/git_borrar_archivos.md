# Borrar archivos de git como si no hubieran estado

La solución es ejecutar la siguiente orden,

    git filter-branch --force --index-filter \\
    'git rm --cached --ignore-unmatch <archivo-que-quieres-borrar>' \
    --prune-empty --tag-name-filter cat -- --all

Posteriormente tendrás que ejecutar la siguiente orden,

    git push origin --force --all


Bibliografía
------------
 * https://atareao.es/como/borrar-archivos-de-git/