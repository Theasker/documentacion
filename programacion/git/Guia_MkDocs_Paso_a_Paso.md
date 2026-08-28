# Guía: Transformar Repositorio Markdown a Web con MkDocs y GitHub Pages

El objetivo de esta guía es transformar un repositorio estándar de GitHub compuesto por carpetas y ficheros `.md` (Markdown puro) en un sitio web de documentación elegante generado estáticamente usando la herramienta **MkDocs** (con su famoso tema Material) y desplegando automáticamente en **GitHub Pages** mediante **GitHub Actions**.

## Filosofía "Docs As Code"
- **No se altera el Markdown original.**
- Solo hay que agregar archivos de configuración.
- Toda la estructura se deduce de los nombres de tus carpetas y archivos.

---

## PASO 1: Reorganizar la Estructura de Directorios

MkDocs necesita saber en qué carpeta reside la documentación que tiene que procesar. Por defecto, busca en la carpeta `/docs`.

1. Clona el repositorio en tu ordenador (si no lo tienes ya):
   ```bash
   git clone https://github.com/tu-usuario/tu-repositorio.git
   cd tu-repositorio
   ```

2. Crea una carpeta llamada `docs`:
   ```bash
   mkdir docs
   ```

3. Mueve **absolutamente todos** tus directorios de categorías (IA, programacion, sistemas, redes, hw...) a esa carpeta.

4. Mueve tu fichero principal `README.md` a la carpeta `docs` y renómbralo a `index.md`. Este archivo actuará como la página de bienvenida o portada del sitio web:
   ```bash
   mv README.md docs/index.md
   ```

---

## PASO 2: Crear el archivo principal de Configuración

En la **raíz** de tu repositorio (fuera de `docs/`), crea un archivo llamado `mkdocs.yml`. Este es el corazón de la herramienta. Pega el siguiente contenido (puedes personalizarlo a tu gusto):

```yaml
site_name: Base de Conocimiento Global
site_description: Documentación de Desarrollo, Sistemas e IA
site_author: Tu Nombre

# Usaremos el mejor tema que existe para este ecosistema
theme:
  name: material
  
  # Este bloque abilita la paleta de colores con el botón de "Modo Noche"
  palette: 
    - scheme: default
      toggle:
        icon: material/brightness-7 
        name: Cambiar a modo oscuro
    - scheme: slate
      toggle:
        icon: material/brightness-4
        name: Cambiar a modo claro

# Configuración de los complementos. Incluimos el buscador de texto integrado
plugins:
  - search:
      lang: es
```

---

## PASO 3: Definir Dependencias de Python

MkDocs está hecho en Python. Debemos decirle a la máquina de integración continua que necesita instalarlo antes de procesar la web.
En la **raíz** del repositorio, crea un archivo llamado `requirements.txt` con este contenido:

```text
mkdocs-material
```

*(Eso instalará todas las dependencias necesarias de `mkdocs` y del tema visual de forma conjunta).*

---

## PASO 4: Automatizar la Publicación en GitHub (CI/CD)

Queremos que cada vez que hagas un commit con nuevos apuntes y los empujes hacia `master` (o `main`), se regenere la web automáticamente.

Para ello, crea la ruta de carpetas `.github/workflows` y, dentro de ella, un fichero YAML con el siguiente nombre `.github/workflows/deploy.yml`:

```yaml
name: Publicar Documentacion MkDocs

# El proceso se activa al hacer "push" hacia la rama principal
on:
  push:
    branches:
      - master # Si tu rama principal se llama "main", sustituir aquí.

# Permisos para que GH Actions pueda empujar código compilado (HTML)
permissions:
  contents: write

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Descargar el código
        uses: actions/checkout@v4
        
      - name: Configurar Python
        uses: actions/setup-python@v5
        with:
          python-version: 3.x
          
      - name: Instalar las dependencias exactas
        run: pip install -r requirements.txt
        
      - name: Ejecutar MkDocs y publicar a "gh-pages"
        run: mkdocs gh-deploy --force
```

---

## PASO 5: Confirmar y Sincronizar Cambios

A partir de este punto, simplemente debes fijar los cambios y enviarlos a GitHub.

```bash
git add .
git commit -m "chore: setup mkdocs as documentation system"
git push origin master
```

**Mágicamente, lo que sucederá en internet es:**
GitHub lanzará un servidor con Ubuntu, instalará nuestro `requirements.txt`, ejecutará `mkdocs gh-deploy`, convertirá todos los Markdown a archivos de formato HTML, creará una nueva rama huérfana en tu repositorio llamada **`gh-pages`** y meterá allí el resultado del código puro de tu web.

---

## PASO 6: Habilitar GitHub Pages (Finalización Manual)

El paso final lo tienes que dar tú a nivel de sistema dentro de la propia plataforma de Github. Hasta ahora, el HTML existe, pero Github no sabe que tiene que publicarlo. 

Sigue estos pasos en tu navegador:
1. Navega hacia tu repositorio en GitHub (https://github.com/).
2. Haz click en la pestaña superior derecha: **⚙️ Settings** (Configuraciones).
3. En el menú de navegación izquierdo, busca debajo de "Code and automation" el panel **Pages**.
4. Verás una sección llamada **Build and deployment**. Asegúrate de que el "Source" es `Deploy from a branch`.
5. Selecciona la rama denominada `gh-pages` justo debajo. 
6. Pulsa en **Save**.

🎉 **¡Enhorabuena!** Dentro de 1 o 2 minutos, tu repositorio generará una URL pública que aparecerá reflejada en las opciones Pages, indicando: `Your site is published at...`. Desde ese momento tienes la documentación puramente configurada y viva en Internet. Cada Markdown que añadas a tu repo en el futuro aparecerá en vivo a todos.
