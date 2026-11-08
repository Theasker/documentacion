### Tip: Hacer `git push` a dos repositorios remotos simultáneamente

Si necesitas que un único `git push` envíe los cambios a dos repositorios diferentes al mismo tiempo, puedes añadir una segunda URL de tipo `push` al remoto principal (`origin`).

**1. Configuración por comando:**
```bash
git remote set-url --add --push origin <url-del-segundo-repositorio>
```

**2. Verificación (en `.git/config`):**
Debe quedar configurado con múltiples líneas de `pushurl` dentro de la sección del remoto:
```ini
[remote "origin"]
    url = <url-del-primer-repositorio>
    fetch = +refs/heads/*:refs/remotes/origin/*
    pushurl = <url-del-primer-repositorio>
    pushurl = <url-del-segundo-repositorio>
```

**Uso:**
```bash
git push origin main
```
> **Nota:** A partir de este momento, cada `push` actualizará ambos repositorios automáticamente. Si uno falla, el proceso completo se detendrá.